<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories.index', ['only' => ['index', 'show', 'details', 'datatable']]);
        $this->middleware('permission:categories.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:categories.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:categories.delete', ['only' => ['destroy', 'bulkDelete']]);
    }
    public function index()
    {
        if (!Auth::user()->can('categories.index')) {
            return redirect()->route('home')
                ->with('error', __('file.module_access_denied'));
        }

        return view('admin.categories.index');
    }

    public function tree()
    {
        if (!Auth::user()->can('categories.index')) {
            return redirect()->route('home')
                ->with('error', __('file.module_access_denied'));
        }

        $categories = Category::whereNull('parent_id')
            ->with([
                'children' => function ($q) {
                    $q->orderBy('name');
                },
                'children.children' => function ($q) {
                    $q->orderBy('name');
                },
                'children.children.children'
            ])
            ->orderBy('name')
            ->get();

        return view('admin.categories.tree', compact('categories'));
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $searchValue = trim($request->input('search.value', ''));

        $statusFilter = $request->status;

        $query = Category::query()
            ->select('categories.*')
            ->with('parent:id,name')
            ->when($searchValue !== '', function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%");
            })
            ->when($statusFilter, function ($q) use ($statusFilter) {
                if ($statusFilter === 'active')
                    return $q->where('is_active', true);
                if ($statusFilter === 'inactive')
                    return $q->where('is_active', false);
            });

        $totalRecords = Category::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'name',
            2 => 'parent_id',
            3 => 'is_active',
            default => 'name',
        };

        // If sorting parent_id, sort as requested. Otherwise always prioritize parents (parent_id IS NULL)
        if ($sortColumn === 'parent_id') {
            $query->leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
                ->orderByRaw("COALESCE(parents.name, 'zzz') {$orderDir}")
                ->select('categories.*');
        } else {
            $query->orderByRaw('parent_id IS NOT NULL')->orderBy($sortColumn, $orderDir);
        }

        $categories = $query->offset($start)->limit($length)->get();

        $data = $categories->map(function ($category) {
            $statusHtml = $category->is_active
                ? '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">' . __('file.active') . '</span>'
                : '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">' . __('file.inactive') . '</span>';

            $edit_url = Auth::user()->can('categories.edit') ? route('categories.edit', $category) : null;
            $delete_url = Auth::user()->can('categories.delete') ? route('categories.destroy', $category) : null;

            return [
                'id' => $category->id,
                'name' => $category->name,
                'parent_name' => $category->parent?->name ?? '—',
                'parent_id' => $category->parent_id,
                'is_active' => $category->is_active,
                'status_html' => $statusHtml,
                'show_url' => route('categories.show', $category),
                'edit_url' => $edit_url,
                'delete_url' => $delete_url,
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    public function create()
    {
        if (!Auth::user()->can('categories.create')) {
            return redirect()->route('categories.index')
                ->with('error', __('file.categories_create_denied'));
        }

        $parents = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('categories.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
            ],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'banners' => 'nullable|array',
            'banners.*.title' => 'nullable|string|max:255',
            'banners.*.description' => 'nullable|string',
            'banners.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'image.image' => __('file.image_upload_limit_error'),
            'image.mimes' => __('file.image_mimes_error'),
            'banners.*.image.image' => __('file.banner_image_upload_limit_error'),
            'banners.*.image.mimes' => __('file.banner_image_mimes_error'),
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->has('banners')) {
            $banners = [];
            foreach ($request->input('banners', []) as $index => $bannerData) {
                $bannerEntry = [
                    'title' => $bannerData['title'] ?? '',
                    'description' => $bannerData['description'] ?? '',
                ];

                if ($request->hasFile("banners.{$index}.image")) {
                    $bannerEntry['image'] = $request->file("banners.{$index}.image")->store('categories/banners', 'public');
                }
                
                if (isset($bannerEntry['image']) || !empty($bannerEntry['title'])) {
                    $banners[] = $bannerEntry;
                }
            }
            $validated['banner_images'] = $banners;
        }

        $category = Category::create($validated);

        if (isset($validated['image'])) {
            $category->addMedia(\Illuminate\Support\Facades\Storage::disk('public')->path($validated['image']))
                     ->preservingOriginal()
                     ->toMediaCollection('images');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('file.category_created_successfully')
            ]);
        }
        return redirect()->route('categories.index')
            ->with('success', __('file.category_created_successfully'));
    }

    public function show(Category $category)
    {
        if (!Auth::user()->can('categories.index')) {
            return redirect()->route('categories.index')
                ->with('error', __('file.categories_show_denied'));
        }

        $category->load([
            'parent',
            'children' => fn($q) => $q->orderBy('name')
        ]);

        return view('admin.categories.show', compact('category'));
    }

    public function details(Category $category)
    {
        if (!Auth::user()->can('categories.index')) {
            return response()->json(['error' => __('file.unauthorized')], 403);
        }

        $category->load([
            'parent:id,name',
            'children' => fn($q) => $q->select('id', 'name', 'is_active', 'parent_id')
                ->with(['children:id,name,is_active,parent_id'])
        ]);

        return response()->json([
            'category' => $category,
            'subcategories' => $category->children->toArray(),
        ]);
    }

    public function edit(Category $category)
    {
        if (!Auth::user()->can('categories.edit')) {
            return redirect()->route('categories.index')
                ->with('error', __('file.categories_edit_denied'));
        }

        $parents = Category::where('id', '!=', $category->id)
            ->where(function ($q) use ($category) {
                $q->whereNull('parent_id')
                    ->orWhereNotIn('id', $this->getDescendantIds($category));
            })
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        if (!Auth::user()->can('categories.edit')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('file.unauthorized')
                ], 403);
            }
            return redirect()->route('categories.index')->with('error', __('file.unauthorized'));
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($value && in_array($value, $this->getDescendantIds($category))) {
                        $fail(__('file.descendant_category_error'));
                    }
                },
            ],
            'is_active' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'banners' => 'nullable|array',
            'banners.*.title' => 'nullable|string|max:255',
            'banners.*.description' => 'nullable|string',
            'banners.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'banners.*.existing_image' => 'nullable|string',
        ], [
            'image.image' => __('file.image_upload_limit_error'),
            'image.mimes' => __('file.image_mimes_error'),
            'banner_images.*.image' => __('file.banner_image_upload_limit_error'),
            'banner_images.*.mimes' => __('file.banner_image_mimes_error'),
        ]);

        $validated['is_active'] = $request->boolean('is_active', $request->has('is_active') ? $request->is_active : $category->is_active);

        if ($request->hasFile('image')) {
            if ($category->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->has('banners')) {
            $banners = [];
            $existingImages = $category->banner_images ? array_column($category->banner_images, 'image') : [];
            $newImagesPaths = [];

            foreach ($request->input('banners', []) as $index => $bannerData) {
                $bannerEntry = [
                    'title' => $bannerData['title'] ?? '',
                    'description' => $bannerData['description'] ?? '',
                    'image' => $bannerData['existing_image'] ?? null,
                ];

                if ($request->hasFile("banners.{$index}.image")) {
                    $bannerEntry['image'] = $request->file("banners.{$index}.image")->store('categories/banners', 'public');
                    $newImagesPaths[] = $bannerEntry['image'];
                }
                
                if ($bannerEntry['image']) {
                    $banners[] = $bannerEntry;
                }
            }

            // Cleanup old images that are no longer used
            $finalImages = array_column($banners, 'image');
            foreach ($existingImages as $oldPath) {
                if (!in_array($oldPath, $finalImages)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }

            $validated['banner_images'] = $banners;
        } else {
            // If the banners field itself is missing from the request, we might want to keep old ones
            // but if it's there but empty, we clear them.
            if ($request->has('banners_submitted')) {
               $validated['banner_images'] = [];
            } else {
               unset($validated['banner_images']);
            }
        }

        $category->update($validated);

        if ($request->hasFile('image') && isset($validated['image'])) {
            $category->clearMediaCollection('images');
            $category->addMedia(\Illuminate\Support\Facades\Storage::disk('public')->path($validated['image']))
                     ->preservingOriginal()
                     ->toMediaCollection('images');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('file.category_updated_successfully')
            ]);
        }

        return redirect()->route('categories.index')->with('success', __('file.category_updated_successfully'));
    }

    public function destroy(Category $category)
    {
        if (!Auth::user()->can('categories.delete')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => __('file.permission_denied')], 403);
            }
            return redirect()->route('categories.index')
                ->with('error', __('file.permission_denied'));
        }

        $category->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.category_deleted_successfully')]);
        }

        return back()->with('success', __('file.category_deleted_successfully'));
    }

    public function bulkDelete(Request $request)
    {
        if (!Auth::user()->can('categories.delete')) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => __('file.permission_denied')], 403);
            }
            return back()->with('error', __('file.permission_denied'));
        }

        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids ?? '')));
        }

        if (!is_array($ids) || empty($ids)) {
            $msg = __('file.no_items_selected');
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        $validator = Validator::make(['ids' => $ids], [
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        if ($validator->fails()) {
            $msg = __('file.validation_failed');
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg, 'errors' => $validator->errors()], 422);
            }
            return back()->with('error', $msg);
        }

        Category::whereIn('id', $ids)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('file.categories_bulk_deleted_successfully')
            ]);
        }

        return back()->with('success', __('file.categories_bulk_deleted_successfully'));
    }

    protected function getDescendantIds(Category $category): array
    {
        $ids = [];
        $children = $category->children()->pluck('id')->toArray();

        foreach ($children as $childId) {
            $ids[] = $childId;
            $child = Category::find($childId);
            if ($child) {
                $ids = array_merge($ids, $this->getDescendantIds($child));
            }
        }

        return $ids;
    }
}
