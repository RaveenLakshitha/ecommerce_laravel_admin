<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.products.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = \App\Models\Product::with('brand', 'images');

        if ($searchValue !== '') {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhereHas('brand', function ($q2) use ($searchValue) {
                        $q2->where('name', 'like', "%{$searchValue}%");
                    });
            });
        }

        $totalRecords = \App\Models\Product::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'id',
            3 => 'name',
            4 => 'brand_id',
            5 => 'base_price',
            6 => 'is_visible',
            default => 'id',
        };

        $query->orderBy($sortColumn, $orderDir);
        $products = $query->offset($start)->limit($length)->get();

        $data = $products->map(function ($product) {
            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
            $imageUrl = $primaryImage ? \Illuminate\Support\Facades\Storage::url($primaryImage->file_path) : null;

            $imageHtml = $imageUrl
                ? '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($product->name) . '" class="h-12 w-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600 shadow-sm">'
                : '<div class="h-12 w-12 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 flex items-center justify-center border border-gray-200 dark:border-gray-600 shadow-sm"><svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>';

            $nameHtml = '<div class="text-sm font-semibold text-gray-900 dark:text-primary-a0">' . htmlspecialchars($product->name) . '</div><div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate max-w-[200px]" title="' . htmlspecialchars(strip_tags($product->description)) . '">' . \Illuminate\Support\Str::limit(strip_tags($product->description), 40) . '</div>';

            $brandHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">' . htmlspecialchars($product->brand ? $product->brand->name : '—') . '</span>';

            $priceHtml = '$' . number_format($product->base_price, 2);

            $statusHtml = '<div class="flex flex-col items-center gap-1">';
            if ($product->is_visible) {
                $statusHtml .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Visible</span>';
            } else {
                $statusHtml .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a20 dark:text-gray-300 border border-gray-200 dark:border-surface-tonal-a30"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span> Hidden</span>';
            }
            if ($product->is_featured) {
                $statusHtml .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800 mt-1"><svg class="w-3 h-3 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> Featured</span>';
            }
            $statusHtml .= '</div>';

            return [
                'id' => $product->id,
                'id_html' => '<span class="text-sm font-medium text-gray-900 dark:text-primary-a0">#' . str_pad($product->id, 5, '0', STR_PAD_LEFT) . '</span>',
                'image_html' => $imageHtml,
                'name_html' => $nameHtml,
                'brand_html' => $brandHtml,
                'price_html' => $priceHtml,
                'status_html' => $statusHtml,
                'show_url' => route('products.show', $product->id),
                'edit_url' => route('products.edit', $product->id),
                'delete_url' => route('products.destroy', $product->id),
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = \App\Models\Brand::all();
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'fabric_details' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,avif|max:2048'
        ]);

        $validated['is_visible'] = $request->has('is_visible');
        $validated['is_featured'] = $request->has('is_featured');

        $product = \App\Models\Product::create($validated);

        if ($request->hasFile('images')) {
            $this->handleImages($product, $request->file('images'));
        }

        if ($request->has('categories')) {
            $product->categories()->attach($request->categories);
        }



        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    protected function handleImages(\App\Models\Product $product, $images)
    {
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');

            $product->images()->create([
                'file_path' => $path,
                'file_name' => $image->getClientOriginalName(),
                'sort_order' => $product->images()->count() + $index,
                'is_primary' => $product->images()->count() === 0 && $index === 0,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = \App\Models\Product::with(['categories', 'collections', 'images', 'brand', 'variants.attributeValues'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = \App\Models\Product::with(['categories', 'collections', 'images', 'variants.attributeValues'])->findOrFail($id);
        $brands = \App\Models\Brand::all();
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'fabric_details' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,avif|max:2048'
        ]);

        $validated['is_visible'] = $request->has('is_visible');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        if ($request->hasFile('images')) {
            $this->handleImages($product, $request->file('images'));
        }

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        } else {
            $product->categories()->detach();
        }



        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        foreach ($product->images as $image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function deleteImage($id, $imageId)
    {
        $product = \App\Models\Product::findOrFail($id);
        $image = $product->images()->findOrFail($imageId);

        // Delete from storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->file_path);
        }

        // Delete from database
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids ?? '')));
        }

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.'], 400);
        }

        $products = \App\Models\Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            foreach ($product->images as $image) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($image->file_path);
                }
                $image->delete();
            }
            $product->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Selected products deleted successfully.'
        ]);
    }
}
