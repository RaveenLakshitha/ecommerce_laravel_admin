<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:collections.index', ['only' => ['index', 'show', 'datatable']]);
        $this->middleware('permission:collections.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:collections.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:collections.delete', ['only' => ['destroy', 'bulkDelete']]);
    }

    public function index()
    {
        if (!Auth::guard('admin')->user()->can('collections.index') && !Auth::user()->can('collections.index')) {
            return redirect()->route('admin.dashboard')->with('error', __('file.module_access_denied'));
        }

        return view('admin.collections.index');
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

        $query = Collection::query()
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

        $totalRecords = Collection::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'name',
            2 => 'start_date',
            3 => 'end_date',
            4 => 'is_featured',
            5 => 'is_active',
            default => 'created_at',
        };

        $query->orderBy($sortColumn, $orderDir);
        
        $collections = $query->offset($start)->limit($length)->get();

        $data = $collections->map(function ($collection) {
            $statusHtml = $collection->is_active
                ? '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">' . __('file.active') . '</span>'
                : '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">' . __('file.inactive') . '</span>';
            
            $featuredHtml = $collection->is_featured
                ? '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">' . __('file.Yes') . '</span>'
                : '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">' . __('file.No') . '</span>';

            $user = Auth::guard('admin')->user() ?? Auth::user();
            $edit_url = $user->can('collections.edit') ? route('collections.edit', $collection) : null;
            $delete_url = $user->can('collections.delete') ? route('collections.destroy', $collection) : null;

            return [
                'id' => $collection->id,
                'name' => $collection->name,
                'banner_url' => $collection->banner_url,
                'start_date' => $collection->start_date ? $collection->start_date->format('Y-m-d') : '—',
                'end_date' => $collection->end_date ? $collection->end_date->format('Y-m-d') : '—',
                'is_featured' => $collection->is_featured,
                'featured_html' => $featuredHtml,
                'is_active' => $collection->is_active,
                'status_html' => $statusHtml,
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

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin.collections.partials.form', ['collection' => null])->render();
        }
        $products = \App\Models\Product::all();
        return view('admin.collections.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('collections', 'name')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('collections', 'slug')],
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('banner_image')) {
            $validated['banner_url'] = $request->file('banner_image')->store('collections', 'public');
        }

        $collection = Collection::create($validated);

        if (isset($validated['banner_url'])) {
            $collection->addMedia(\Illuminate\Support\Facades\Storage::disk('public')->path($validated['banner_url']))
                       ->preservingOriginal()
                       ->toMediaCollection('images');
        }

        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.collection_created_successfully')]);
        }

        return redirect()->route('collections.index')
            ->with('success', __('file.collection_created_successfully'));
    }

    public function edit(Request $request, Collection $collection)
    {
        if ($request->ajax()) {
            return view('admin.collections.partials.form', compact('collection'))->render();
        }
        $products = \App\Models\Product::all();
        return view('admin.collections.edit', compact('collection', 'products'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('collections')->ignore($collection->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('collections')->ignore($collection->id)],
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('banner_image')) {
            if ($collection->banner_url && Storage::disk('public')->exists($collection->banner_url)) {
                Storage::disk('public')->delete($collection->banner_url);
            }
            $validated['banner_url'] = $request->file('banner_image')->store('collections', 'public');
        }

        $collection->update($validated);

        if (isset($validated['banner_url'])) {
            $collection->clearMediaCollection('images');
            $collection->addMedia(\Illuminate\Support\Facades\Storage::disk('public')->path($validated['banner_url']))
                       ->preservingOriginal()
                       ->toMediaCollection('images');
        }

        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        } else {
            $collection->products()->detach();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('file.collection_updated_successfully')
            ]);
        }

        return redirect()->route('collections.index')->with('success', __('file.collection_updated_successfully'));
    }

    public function destroy(Collection $collection)
    {
        if ($collection->banner_url && Storage::disk('public')->exists($collection->banner_url)) {
            Storage::disk('public')->delete($collection->banner_url);
        }
        $collection->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.collection_deleted_successfully')]);
        }

        return back()->with('success', __('file.collection_deleted_successfully'));
    }

    public function bulkDelete(Request $request)
    {
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
            'ids'   => 'required|array',
            'ids.*' => 'exists:collections,id'
        ]);

        if ($validator->fails()) {
            $msg = __('file.validation_failed');
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg, 'errors' => $validator->errors()], 422);
            }
            return back()->with('error', $msg);
        }

        $collections = Collection::whereIn('id', $ids)->get();
        foreach ($collections as $collection) {
            if ($collection->banner_url && Storage::disk('public')->exists($collection->banner_url)) {
                Storage::disk('public')->delete($collection->banner_url);
            }
            $collection->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('file.collections_bulk_deleted_successfully')
            ]);
        }

        return back()->with('success', __('file.collections_bulk_deleted_successfully'));
    }
}
