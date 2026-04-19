@extends('layouts.app')

@section('title', __('file.add_variant') ?? 'Add Variant')

@section('content')
<div class="admin-page">
    <div class="admin-page-inner">

        {{-- Header --}}
        <div class="admin-page-header">
            <div>
                <a href="{{ route('products.edit', $product->id) }}" class="admin-breadcrumb">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Product
                </a>
                <h1 class="admin-page-title">Add Variant to: {{ $product->name }}</h1>
                <p class="admin-page-subtitle">Create a new specific version of this product (e.g., Red, Large).</p>
            </div>
        </div>

        @if($errors->any())
        <div class="admin-alert-error">
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('products.variants.store', $product->id) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Attributes --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2>Attributes</h2>
                </div>
                <div class="admin-card-body">
                    @if($attributes->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No attributes configured. <a href="{{ route('attributes.create') }}" class="text-gray-900 dark:text-white underline font-medium">Create an attribute</a> first.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            @foreach($attributes as $attribute)
                                @if($attribute->values->count() > 0)
                                <div>
                                    <label class="fi-label">{{ $attribute->name }}</label>
                                    <select name="attribute_values[]" class="fi">
                                        <option value="">-- None --</option>
                                        @foreach($attribute->values as $val)
                                            <option value="{{ $val->id }}" {{ is_array(old('attribute_values')) && in_array($val->id, old('attribute_values')) ? 'selected' : '' }}>
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

            {{-- Pricing & Inventory --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2>Pricing & Inventory</h2>
                </div>
                <div class="admin-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="sku" class="fi-label fi-label-required">SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}" placeholder="e.g. PRD-RED-LG" required class="fi">
                        </div>
                        <div>
                            <label for="barcode" class="fi-label">Barcode</label>
                            <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}" class="fi">
                        </div>
                        <div>
                            <label for="price" class="fi-label fi-label-required">Price</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->base_price) }}" required class="fi">
                        </div>
                        <div>
                            <label for="sale_price" class="fi-label">Sale Price</label>
                            <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" class="fi">
                        </div>
                        <div>
                            <label for="stock_quantity" class="fi-label fi-label-required">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" required class="fi">
                        </div>
                        <div>
                            <label for="low_stock_threshold" class="fi-label">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" class="fi">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping & Settings --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2>Shipping & Settings</h2>
                </div>
                <div class="admin-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="weight_grams" class="fi-label">Weight (grams)</label>
                            <input type="number" name="weight_grams" id="weight_grams" value="{{ old('weight_grams') }}" class="fi">
                        </div>
                        <div>
                            <label for="dimensions" class="fi-label">Dimensions (L x W x H)</label>
                            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions') }}" placeholder="e.g. 10x20x5 cm" class="fi">
                        </div>
                        <div class="col-span-full">
                            <label class="ck-card">
                                <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-500 transition-all">
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Set as default variant</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Shown first when customers view the product.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-surface-tonal-a30 flex justify-end gap-3">
                    <a href="{{ route('products.edit', $product->id) }}" class="admin-btn-secondary !w-auto">Cancel</a>
                    <button type="submit" class="admin-btn-primary !w-auto">Create Variant</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
