@extends('layouts.app')

@section('title', __('file.edit_variant') ?? 'Edit Variant')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                Edit Variant: {{ $variant->sku }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Updating variant for product <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:underline">{{ $product->name }}</a>
            </p>
        </div>
        <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-surface-tonal-a30 dark:hover:bg-gray-600 text-gray-900 dark:text-primary-a0 rounded-lg text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Product
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div class="ml-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('products.variants.update', [$product->id, $variant->id]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        @php
            $currentAttributeValues = $variant->attributeValues->pluck('id')->toArray();
        @endphp

        <div class="bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
            <div class="p-6 sm:p-8">
                <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-6">Attributes</h2>
                @if($attributes->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">No attributes configured in the system. <a href="{{ route('attributes.create') }}" class="text-indigo-600 hover:underline">Create an attribute</a> first if you need variations.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($attributes as $attribute)
                            @if($attribute->values->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $attribute->name }}</label>
                                <select name="attribute_values[]" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                                    <option value="">-- None --</option>
                                    @foreach($attribute->values as $val)
                                        <option value="{{ $val->id }}" {{ (is_array(old('attribute_values')) ? in_array($val->id, old('attribute_values')) : in_array($val->id, $currentAttributeValues)) ? 'selected' : '' }}>
                                            {{ $val->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
            <div class="p-6 sm:p-8">
                <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-6">Pricing & Inventory</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SKU *</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $variant->sku) }}" required class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>
                    <div>
                        <label for="barcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Barcode (ISBN, UPC, GTIN, etc.)</label>
                        <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $variant->barcode) }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price *</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $variant->price) }}" required class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price', $variant->sale_price) }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>

                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $variant->stock_quantity) }}" required class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>
                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $variant->low_stock_threshold) }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
            <div class="p-6 sm:p-8">
                <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-6">Shipping & Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="weight_grams" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Weight (grams)</label>
                        <input type="number" name="weight_grams" id="weight_grams" value="{{ old('weight_grams', $variant->weight_grams) }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dimensions (L x W x H)</label>
                        <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $variant->dimensions) }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                    </div>
                    
                    <div class="col-span-full">
                        <label class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input type="checkbox" name="is_default" value="1" {{ old('is_default', $variant->is_default) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:bg-surface-tonal-a10 dark:border-gray-600">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Set as default variant</span>
                                <p class="text-gray-500 dark:text-gray-400">If checked, this variant will be shown first when customers view the product.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-surface-tonal-a10/50 border-t border-gray-200 dark:border-surface-tonal-a30 flex justify-end gap-3">
                <a href="{{ route('products.edit', $product->id) }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                    Update Variant
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

