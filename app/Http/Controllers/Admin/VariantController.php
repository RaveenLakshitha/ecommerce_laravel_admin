<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VariantController extends Controller
{
    public function create(Product $product)
    {
        $attributes = Attribute::with('values')->orderBy('sort_order')->get();
        return view('admin.products.variants.create', compact('product', 'attributes'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:variants,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight_grams' => 'nullable|integer|min:0',
            'dimensions' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'is_default' => 'boolean',
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'exists:attribute_values,id',
            'variant_images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,avif|max:2048'
        ]);

        $validated['is_default'] = $request->has('is_default');

        // If this is set as default, unset others for this product
        if ($validated['is_default']) {
            $product->variants()->update(['is_default' => false]);
        }

        $variant = $product->variants()->create($validated);
        $variant->attributeValues()->sync($request->attribute_values);

        if ($request->hasFile('variant_images')) {
            $this->handleVariantImages($product, $variant, $request->file('variant_images'));
        }

        return redirect()->route('products.edit', $product->id)->with('success', 'Variant created successfully.');
    }

    protected function handleVariantImages(Product $product, Variant $variant, $images)
    {
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');

            $variant->images()->create([
                'product_id' => $product->id,
                'file_path' => $path,
                'file_name' => $image->getClientOriginalName(),
                'sort_order' => $variant->images()->count() + $index,
                'is_primary' => $variant->images()->count() === 0 && $index === 0,
            ]);
        }
    }

    public function edit(Product $product, Variant $variant)
    {
        $attributes = Attribute::with('values')->orderBy('sort_order')->get();
        return view('admin.products.variants.edit', compact('product', 'variant', 'attributes'));
    }

    public function update(Request $request, Product $product, Variant $variant)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:255', Rule::unique('variants')->ignore($variant->id)],
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight_grams' => 'nullable|integer|min:0',
            'dimensions' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'is_default' => 'boolean',
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'exists:attribute_values,id',
            'variant_images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,avif|max:2048'
        ]);

        $validated['is_default'] = $request->has('is_default');

        // If this is set as default, unset others
        if ($validated['is_default'] && !$variant->is_default) {
            $product->variants()->where('id', '!=', $variant->id)->update(['is_default' => false]);
        }

        $variant->update($validated);
        $variant->attributeValues()->sync($request->attribute_values);

        \Illuminate\Support\Facades\Log::info('Variant update triggered', ['variant_id' => $variant->id]);
        if ($request->hasFile('variant_images')) {
            \Illuminate\Support\Facades\Log::info('Images detected in request', ['count' => count($request->file('variant_images'))]);
            $this->handleVariantImages($product, $variant, $request->file('variant_images'));
        } else {
            \Illuminate\Support\Facades\Log::info('No images detected in request');
        }

        return redirect()->route('products.edit', $product->id)->with('success', 'Variant updated successfully.');
    }

    public function destroy(Product $product, Variant $variant)
    {
        $variant->delete();
        return redirect()->route('products.edit', $product->id)->with('success', 'Variant deleted successfully.');
    }

    public function deleteImage(Product $product, Variant $variant, $imageId)
    {
        $image = $variant->images()->findOrFail($imageId);

        // Delete from storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->file_path);
        }

        // Delete from database
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}
