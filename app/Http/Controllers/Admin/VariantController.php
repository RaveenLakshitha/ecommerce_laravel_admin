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
        ]);

        $validated['is_default'] = $request->has('is_default');

        // If this is set as default, unset others for this product
        if ($validated['is_default']) {
            $product->variants()->update(['is_default' => false]);
        }

        $variant = $product->variants()->create($validated);
        $variant->attributeValues()->sync($request->attribute_values);

        return redirect()->route('products.edit', $product->id)->with('success', 'Variant created successfully.');
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
        ]);

        $validated['is_default'] = $request->has('is_default');

        // If this is set as default, unset others
        if ($validated['is_default'] && !$variant->is_default) {
            $product->variants()->where('id', '!=', $variant->id)->update(['is_default' => false]);
        }

        $variant->update($validated);
        $variant->attributeValues()->sync($request->attribute_values);

        return redirect()->route('products.edit', $product->id)->with('success', 'Variant updated successfully.');
    }

    public function destroy(Product $product, Variant $variant)
    {
        $variant->delete();
        return redirect()->route('products.edit', $product->id)->with('success', 'Variant deleted successfully.');
    }
}
