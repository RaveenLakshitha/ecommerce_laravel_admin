<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $query = \App\Models\Product::with('brand', 'images', 'category');

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

            $priceHtml = Setting::formatPrice($product->base_price);

            $statusHtml = '<div class="flex flex-col items-center gap-1">';
            if ($product->is_visible) {
                $statusHtml .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> ' . __('file.visible') . '</span>';
            } else {
                $statusHtml .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a20 dark:text-gray-300 border border-gray-200 dark:border-surface-tonal-a30"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span> ' . __('file.hidden') . '</span>';
            }
            if ($product->is_featured) {
                $statusHtml .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800 mt-1"><svg class="w-3 h-3 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> ' . __('file.featured') . '</span>';
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
        $allAttributes = \App\Models\Attribute::with('values')->orderBy('name')->get();
        return view('admin.products.create', compact('brands', 'categories', 'allAttributes'));
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
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'fabric_details' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        $validated['is_visible'] = $request->has('is_visible');
        $validated['is_featured'] = $request->has('is_featured');

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            $this->handleImages($product, $request->file('images'));
        }

        $this->syncVariants($product, $request);

        return redirect()->route('products.edit', $product->id)->with('success', __('file.product_created_successfully'));
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
        $product = \App\Models\Product::with(['category', 'collections', 'images', 'brand', 'variants.attributeValues.attribute'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = \App\Models\Product::with(['category', 'collections', 'images', 'variants.attributeValues'])->findOrFail($id);
        $brands = \App\Models\Brand::all();
        $categories = \App\Models\Category::all();
        $allAttributes = \App\Models\Attribute::with('values')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'brands', 'categories', 'allAttributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'fabric_details' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        $validated['is_visible'] = $request->has('is_visible');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        if ($request->hasFile('images')) {
            $this->handleImages($product, $request->file('images'));
        }

        $this->syncVariants($product, $request);

        return redirect()->route('products.edit', $product->id)->with('success', __('file.product_updated_successfully'));
    }

    /**
     * Sync variants from the inline Shopify-style panel.
     * Finds-or-creates Attributes and AttributeValues dynamically.
     */
    protected function syncVariants(Product $product, Request $request): void
    {
        $options      = $request->input('options', []);
        $variantsData = $request->input('variants', []);

        // Nothing submitted → leave existing variants untouched
        if (empty($options) && empty($variantsData)) {
            return;
        }

        // ── Step 1: Build attribute/value DB records ──────────────────────────
        // $optionMap[optIndex] = ['attribute' => Attribute, 'values' => [string => AttributeValue]]
        $optionMap = [];
        foreach ($options as $optIndex => $option) {
            $attrName = trim($option['name'] ?? '');
            if (empty($attrName)) continue;

            $attribute = Attribute::firstOrCreate(
                ['name' => $attrName],
                ['slug' => Str::slug($attrName), 'type' => 'select', 'sort_order' => (int) $optIndex]
            );

            $valMap = [];
            foreach ($option['values'] ?? [] as $val) {
                $val = trim($val);
                if (empty($val)) continue;
                $attrValue = AttributeValue::firstOrCreate(
                    ['attribute_id' => $attribute->id, 'value' => $val],
                    ['slug' => Str::slug($val), 'sort_order' => 0]
                );
                $valMap[$val] = $attrValue;
            }
            $optionMap[$optIndex] = ['attribute' => $attribute, 'values' => $valMap];
        }

        // ── Step 2: Iterate submitted variant rows ────────────────────────────
        $keptIds         = [];
        $existingVariants = $product->variants()->with('attributeValues')->get();
        $isFirst         = true;

        foreach ($variantsData as $rowIndex => $variantData) {
            // Skip unchecked rows
            if (empty($variantData['enabled'])) continue;

            // Resolve attribute value IDs for this combination
            $attrValueIds = [];
            foreach ($variantData['opts'] ?? [] as $optIdx => $value) {
                $value = trim($value);
                if (isset($optionMap[$optIdx]['values'][$value])) {
                    $attrValueIds[] = $optionMap[$optIdx]['values'][$value]->id;
                }
            }
            sort($attrValueIds);

            // Find existing variant by explicit ID, or by matching attribute combo
            $variant = null;
            if (!empty($variantData['id'])) {
                $variant = $existingVariants->find($variantData['id']);
            }
            if (!$variant && !empty($attrValueIds)) {
                $variant = $existingVariants->first(function ($v) use ($attrValueIds) {
                    $ids = $v->attributeValues->pluck('id')->sort()->values()->toArray();
                    return $ids === $attrValueIds;
                });
            }

            $price = floatval($variantData['price'] ?? $product->base_price);
            $sku   = trim($variantData['sku'] ?? '');

            $fields = [
                'sku'            => $sku ?: ('SKU-' . strtoupper(Str::random(6))),
                'price'          => $price > 0 ? $price : $product->base_price,
                'barcode'        => $variantData['barcode'] ?? null ?: null,
                'stock_quantity' => max(0, intval($variantData['stock_quantity'] ?? 0)),
                'is_default'     => $isFirst,
            ];

            if ($variant) {
                $variant->update($fields);
            } else {
                $variant = $product->variants()->create($fields);
            }

            $variant->attributeValues()->sync($attrValueIds);
            $keptIds[] = $variant->id;
            $isFirst   = false;

            // Handle per-variant image upload
            $file = $request->file("variant_images.$rowIndex");
            if ($file && $file->isValid()) {
                \Illuminate\Support\Facades\Log::info('Variant image received in syncVariants', [
                    'rowIndex' => $rowIndex,
                    'variant_id' => $variant->id,
                    'file_name' => $file->getClientOriginalName()
                ]);
                $path = $file->store('products', 'public');
                $variant->images()->create([
                    'product_id' => $product->id,
                    'file_path'  => $path,
                    'file_name'  => $file->getClientOriginalName(),
                    'sort_order' => 0,
                    'is_primary' => true,
                ]);
            }
        }

        // ── Step 3: Remove variants that were unchecked / deleted ─────────────
        if (!empty($keptIds)) {
            $product->variants()->whereNotIn('id', $keptIds)->each(fn ($v) => $v->delete());
        }
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

        return redirect()->route('products.index')->with('success', __('file.product_deleted_successfully'));
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

        return response()->json(['success' => true, 'message' => __('file.image_deleted_successfully')]);
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

        $products = Product::whereIn('id', $ids)->get();
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
            'message' => __('file.selected_products_deleted_successfully')
        ]);
    }
}
