<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin.brands.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = \App\Models\Brand::withCount('products');

        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%");
        }

        $totalRecords = \App\Models\Brand::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            0 => 'id',
            2 => 'name',
            3 => 'sort_order',
            4 => 'products_count',
            default => 'id',
        };

        $query->orderBy($sortColumn, $orderDir);
        $brands = $query->offset($start)->limit($length)->get();

        $data = $brands->map(function ($brand) {
            $logoHtml = $brand->logo_url
                ? '<img src="' . $brand->logo_url . '" alt="' . htmlspecialchars($brand->name) . '" class="h-10 w-auto max-w-[80px] object-contain">'
                : '<div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 flex items-center justify-center border border-gray-200 dark:border-gray-600 shadow-sm"><svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>';

            $nameHtml = '<div class="text-sm font-semibold text-gray-900 dark:text-primary-a0">' . htmlspecialchars($brand->name) . '</div>';
            if ($brand->website_url) {
                $host = parse_url($brand->website_url, PHP_URL_HOST) ?? 'Website';
                $nameHtml .= '<a href="' . htmlspecialchars($brand->website_url) . '" target="_blank" class="text-xs text-indigo-500 hover:underline flex items-center gap-1 mt-1">' . htmlspecialchars($host) . '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>';
            }

            $productsHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">' . $brand->products_count . ' ' . __('file.Products') . '</span>';

            $statusHtml = $brand->is_featured ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800"><svg class="w-3 h-3 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>' . __('file.featured') . '</span>' : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-surface-tonal-a20 dark:text-gray-400 border border-transparent">' . __('file.Standard') . '</span>';

            return [
                'id' => $brand->id,
                'logo_html' => $logoHtml,
                'name_html' => $nameHtml,
                'sort_order' => $brand->sort_order,
                'products_html' => $productsHtml,
                'status_html' => $statusHtml,
                'edit_url' => route('brands.edit', $brand->id),
                'delete_url' => route('brands.destroy', $brand->id),
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
            return view('admin.brands.partials.form', ['brand' => null])->render();
        }
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url|max:255',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        $brand = Brand::create($validated);

        if (isset($validated['logo_path'])) {
            $brand->addMedia(\Illuminate\Support\Facades\Storage::disk('public')->path($validated['logo_path']))
                  ->preservingOriginal()
                  ->toMediaCollection('images');
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.brand_created_successfully')]);
        }

        return redirect()->route('brands.index')->with('success', __('file.brand_created_successfully'));
    }

    public function edit(Request $request, Brand $brand)
    {
        if ($request->ajax()) {
            return view('admin.brands.partials.form', compact('brand'))->render();
        }
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($brand->id),
            ],
            'description' => 'nullable|string',
            'website_url' => 'nullable|url|max:255',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('logo')) {
            if ($brand->logo_path && Storage::disk('public')->exists($brand->logo_path)) {
                Storage::disk('public')->delete($brand->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
        } elseif ($request->has('remove_logo') && $request->remove_logo == '1') {
            if ($brand->logo_path && Storage::disk('public')->exists($brand->logo_path)) {
                Storage::disk('public')->delete($brand->logo_path);
            }
            $validated['logo_path'] = null;
        }

        $brand->update($validated);

        if (isset($validated['logo_path'])) {
            $brand->clearMediaCollection('images');
            $brand->addMedia(\Illuminate\Support\Facades\Storage::disk('public')->path($validated['logo_path']))
                  ->preservingOriginal()
                  ->toMediaCollection('images');
        } elseif ($request->has('remove_logo') && $request->remove_logo == '1') {
            $brand->clearMediaCollection('images');
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.brand_updated_successfully')]);
        }

        return redirect()->route('brands.index')->with('success', __('file.brand_updated_successfully'));
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => __('file.cannot_delete_brand_products')], 400);
            }
            return redirect()->route('brands.index')->with('error', __('file.cannot_delete_brand_products'));
        }

        if ($brand->logo_path && Storage::disk('public')->exists($brand->logo_path)) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $brand->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.brand_deleted_successfully')]);
        }

        return redirect()->route('brands.index')->with('success', __('file.brand_deleted_successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids ?? '')));
        }

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => __('file.no_items_selected')], 400);
        }

        $brands = \App\Models\Brand::whereIn('id', $ids)->get();
        foreach ($brands as $brand) {
            if ($brand->products()->count() > 0) {
                continue; // Skip brands with products
            }
            if ($brand->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($brand->logo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($brand->logo_path);
            }
            $brand->delete();
        }

        return response()->json([
            'success' => true,
            'message' => __('file.brands_bulk_deleted_successfully')
        ]);
    }
}
