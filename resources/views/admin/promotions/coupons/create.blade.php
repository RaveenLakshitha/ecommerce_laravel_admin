@extends('layouts.app')

@section('title', __('file.add_new_coupon'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('coupons.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_coupons') }}
                </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.add_new_coupon') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.create_new_coupon_entry') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-coupon-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_coupon') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('coupons.store') }}" method="POST" id="create-coupon-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- LEFT COLUMN - Wider --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- General Information --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.general_information') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.coupon_code') }}</label>
                                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                                        placeholder="e.g. SUMMER2024"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md uppercase tracking-widest">
                                    @error('code')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.internal_description') }}</label>
                                    <textarea name="description" id="description" rows="2"
                                        placeholder="What is this coupon for?"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Discount Configuration --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.discount_configuration') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.discount_type') }}</label>
                                        <select name="type" id="type" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                                Percentage (%)</option>
                                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount
                                                ($)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.discount_value') }}</label>
                                        <input type="number" step="0.01" min="0" name="value" id="value"
                                            value="{{ old('value') }}" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md font-mono">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.max_discount_amount') }}</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0" name="max_discount_amount"
                                            id="max_discount_amount" value="{{ old('max_discount_amount') }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                        <span
                                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1 font-medium italic">
                                        {{ __('file.max_discount_helper') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Requirements & Limits --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.requirements_and_limits') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.min_order_amount') }}</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" min="0" name="min_order_amount"
                                                id="min_order_amount" value="{{ old('min_order_amount') }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                            <span
                                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.applies_to') }}</label>
                                        <select name="applies_to" id="applies_to" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                            <option value="all" {{ old('applies_to') == 'all' ? 'selected' : '' }}>Entire
                                                Order</option>
                                            <option value="specific_products" {{ old('applies_to') == 'specific_products' ? 'selected' : '' }}>Specific Products</option>
                                            <option value="specific_categories" {{ old('applies_to') == 'specific_categories' ? 'selected' : '' }}>Specific Categories</option>
                                            <option value="specific_collections" {{ old('applies_to') == 'specific_collections' ? 'selected' : '' }}>Specific
                                                Collections</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Dynamic Selectors --}}
                                <div class="hidden space-y-1.5" id="products_container">
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.select_products') }}</label>
                                    <select name="product_ids[]" id="product_ids" multiple
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 p-2 text-xs font-bold text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md h-40 custom-scrollbar">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ (is_array(old('product_ids')) && in_array($product->id, old('product_ids'))) ? 'selected' : '' }}>
                                                {{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="hidden space-y-1.5" id="categories_container">
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.select_categories') }}</label>
                                    <select name="category_ids[]" id="category_ids" multiple
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 p-2 text-xs font-bold text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md h-40 custom-scrollbar">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="hidden space-y-1.5" id="collections_container">
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.select_collections') }}</label>
                                    <select name="collection_ids[]" id="collection_ids" multiple
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 p-2 text-xs font-bold text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md h-40 custom-scrollbar">
                                        @foreach($collections as $collection)
                                            <option value="{{ $collection->id }}" {{ (is_array(old('collection_ids')) && in_array($collection->id, old('collection_ids'))) ? 'selected' : '' }}>
                                                {{ $collection->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-gray-100 dark:border-surface-tonal-a30">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.global_usage_limit') }}</label>
                                        <input type="number" min="1" name="usage_limit" id="usage_limit"
                                            value="{{ old('usage_limit') }}" placeholder="∞ Unlimited"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.usage_limit_per_user') }}</label>
                                        <input type="number" min="1" name="usage_per_user" id="usage_per_user"
                                            value="{{ old('usage_per_user') }}" placeholder="∞ Unlimited"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="lg:col-span-1 space-y-4">
                        {{-- Active Period --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.active_period') }}
                                </h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.starts_at') }}</label>
                                    <input type="datetime-local" name="starts_at" id="starts_at"
                                        value="{{ old('starts_at') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.expires_at') }}</label>
                                    <input type="datetime-local" name="expires_at" id="expires_at"
                                        value="{{ old('expires_at') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                            </div>
                        </div>

                        {{-- Status Card --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.status') }}</h2>
                            </div>
                            <div class="p-4">
                                <label
                                    class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                        class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                            {{ __('file.coupon_enabled') }}</h3>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ __('file.coupon_enabled_helper') }}</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const appliesToSelect = document.getElementById('applies_to');
                const prodContainer = document.getElementById('products_container');
                const catContainer = document.getElementById('categories_container');
                const collContainer = document.getElementById('collections_container');

                if (appliesToSelect) {
                    function toggleContainers() {
                        prodContainer.classList.add('hidden');
                        catContainer.classList.add('hidden');
                        collContainer.classList.add('hidden');

                        const val = appliesToSelect.value;
                        if (val === 'specific_products') prodContainer.classList.remove('hidden');
                        if (val === 'specific_categories') catContainer.classList.remove('hidden');
                        if (val === 'specific_collections') collContainer.classList.remove('hidden');
                    }

                    appliesToSelect.addEventListener('change', toggleContainers);
                    toggleContainers();
                }
            });
        </script>

        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #E5E7EB;
                border-radius: 10px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #334155;
            }
        </style>
    @endpush
@endsection