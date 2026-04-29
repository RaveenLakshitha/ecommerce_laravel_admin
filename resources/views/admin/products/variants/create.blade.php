@extends('layouts.app')

@section('title', __('file.add_variant'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('products.edit', $product->id) }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_product') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.add_variant_to') }}: {{ $product->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.create_new_variant_helper') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-variant-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_variant') }}
                    </button>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-xl">
                    <ul class="list-disc pl-5 space-y-1 text-sm text-red-600 dark:text-red-400 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.variants.store', $product->id) }}" method="POST" id="create-variant-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Attributes --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.attributes') }}</h2>
                            </div>
                            <div class="p-4">
                                @if($attributes->isEmpty())
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">
                                        {{ __('file.no_attributes_configured') }}. <a href="{{ route('attributes.create') }}"
                                            class="text-indigo-600 dark:text-indigo-400 underline ml-1">{{ __('file.create_one') }}</a>
                                    </p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        @foreach($attributes as $attribute)
                                            @if($attribute->values->count() > 0)
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ $attribute->name }}</label>
                                                    <select name="attribute_values[]"
                                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                                        <option value="">-- {{ __('file.none') }} --</option>
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
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.pricing_and_inventory') }}</h2>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label for="sku"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.sku') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                            placeholder="e.g. PRD-RED-LG" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    </div>
                                    <div>
                                        <label for="barcode"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.barcode') }}</label>
                                        <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    </div>
                                    <div>
                                        <label for="price"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.price') }}
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="price" id="price"
                                                value="{{ old('price', $product->base_price) }}" required
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                            <span
                                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="sale_price"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.sale_price') }}</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="sale_price" id="sale_price"
                                                value="{{ old('sale_price') }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                            <span
                                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="stock_quantity"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.stock_quantity') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="number" name="stock_quantity" id="stock_quantity"
                                            value="{{ old('stock_quantity', 0) }}" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    </div>
                                    <div>
                                        <label for="low_stock_threshold"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.low_stock_threshold') }}</label>
                                        <input type="number" name="low_stock_threshold" id="low_stock_threshold"
                                            value="{{ old('low_stock_threshold', 5) }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">
                        {{-- Shipping & Settings --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.shipping_and_settings') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="weight_grams"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.weight_grams') }}</label>
                                    <input type="number" name="weight_grams" id="weight_grams"
                                        value="{{ old('weight_grams') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                                <div>
                                    <label for="dimensions"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.dimensions') }}
                                        (L x W x H)</label>
                                    <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions') }}"
                                        placeholder="e.g. 10x20x5 cm"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                                <div class="pt-2">
                                    <label
                                        class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                        <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                                            class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-surface-tonal-a20 transition-all cursor-pointer">
                                        <div class="ml-3">
                                            <h3
                                                class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                                {{ __('file.default_variant') }}</h3>
                                            <p
                                                class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                                {{ __('file.shown_first_to_customers') }}</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection