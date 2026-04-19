@extends('layouts.app')

@section('title', 'Edit Coupon: ' . $coupon->code)

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('coupons.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Coupons</a>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Edit Coupon</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Updating settings for <span class="text-indigo-600 dark:text-indigo-400 font-bold tracking-widest uppercase">{{ $coupon->code }}</span></p>
                </div>
            </div>

            <form action="{{ route('coupons.update', $coupon) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column: Configuration --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- General Information --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">General Information</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="space-y-1.5">
                                    <label for="code"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Coupon Code <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" required
                                        placeholder="e.g. SUMMER2024"
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm uppercase tracking-widest">
                                    @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label for="description"
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Internal Description</label>
                                    <textarea name="description" id="description" rows="2"
                                        placeholder="What is this coupon for?"
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm resize-none">{{ old('description', $coupon->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Discount Details --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Discount Configuration</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label for="type"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Discount Type <span
                                                class="text-red-500">*</span></label>
                                        <select name="type" id="type" required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                            <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="value"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Discount Value <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" min="0" name="value" id="value" value="{{ old('value', $coupon->value) }}" required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm font-mono">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="max_discount_amount"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Maximum Discount Cap (Optional)</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 dark:text-gray-500 text-sm font-bold pointer-events-none">$</span>
                                        <input type="number" step="0.01" min="0" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 pl-8 pr-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-medium italic">Only applicable for percentage discounts.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Requirements & Limits --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Requirements & Usage Limits</h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label for="min_order_amount"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Minimum Order Amount</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 dark:text-gray-500 text-sm font-bold pointer-events-none">$</span>
                                            <input type="number" step="0.01" min="0" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 pl-8 pr-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="applies_to"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Apply Filter <span
                                                class="text-red-500">*</span></label>
                                        <select name="applies_to" id="applies_to" required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                            <option value="all" {{ old('applies_to', $coupon->applies_to) == 'all' ? 'selected' : '' }}>Entire Order</option>
                                            <option value="specific_products" {{ old('applies_to', $coupon->applies_to) == 'specific_products' ? 'selected' : '' }}>Specific Products</option>
                                            <option value="specific_categories" {{ old('applies_to', $coupon->applies_to) == 'specific_categories' ? 'selected' : '' }}>Specific Categories</option>
                                            <option value="specific_collections" {{ old('applies_to', $coupon->applies_to) == 'specific_collections' ? 'selected' : '' }}>Specific Collections</option>
                                        </select>
                                    </div>
                                </div>

                                @php
                                    $selProd = old('product_ids', $coupon->products->pluck('id')->toArray());
                                    $selCat = old('category_ids', $coupon->categories->pluck('id')->toArray());
                                    $selColl = old('collection_ids', $coupon->collections->pluck('id')->toArray());
                                @endphp

                                {{-- Dynamic Selectors --}}
                                <div class="hidden space-y-3" id="products_container">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Select Restricted Products</label>
                                    <select name="product_ids[]" id="product_ids" multiple class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all h-40 custom-scrollbar">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ (is_array($selProd) && in_array($product->id, $selProd)) ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="hidden space-y-3" id="categories_container">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Select Restricted Categories</label>
                                    <select name="category_ids[]" id="category_ids" multiple class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all h-40 custom-scrollbar">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (is_array($selCat) && in_array($category->id, $selCat)) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="hidden space-y-3" id="collections_container">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Select Restricted Collections</label>
                                    <select name="collection_ids[]" id="collection_ids" multiple class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 p-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all h-40 custom-scrollbar">
                                        @foreach($collections as $collection)
                                            <option value="{{ $collection->id }}" {{ (is_array($selColl) && in_array($collection->id, $selColl)) ? 'selected' : '' }}>{{ $collection->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-gray-50 dark:border-surface-tonal-a30">
                                    <div class="space-y-1.5">
                                        <label for="usage_limit"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Global Usage Limit</label>
                                        <input type="number" min="1" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                            placeholder="∞ Unlimited"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="usage_per_user"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Usage Limit Per User</label>
                                        <input type="number" min="1" name="usage_per_user" id="usage_per_user" value="{{ old('usage_per_user', $coupon->usage_per_user) }}"
                                            placeholder="∞ Unlimited"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Active Dates --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Active Period</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label for="starts_at"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Starts At</label>
                                        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="expires_at"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Expires At</label>
                                        <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Right Column: Side Actions --}}
                    <div class="lg:col-span-1 space-y-6">

                        {{-- Status Card --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Publication Status</h2>
                            </div>
                            <div class="p-6 space-y-6">

                                <label
                                    class="flex items-center p-3 rounded-xl border border-gray-100 dark:border-surface-tonal-a30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/50 transition cursor-pointer">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                                        class="h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">Coupon Enabled</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Can be applied at checkout</p>
                                    </div>
                                </label>

                                <div class="pt-4 flex flex-col gap-3">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center px-6 py-4 rounded-xl bg-gray-900 dark:bg-white text-sm font-bold text-white dark:text-gray-900 hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl shadow-gray-200 dark:shadow-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                                        Save Changes
                                    </button>
                                    <a href="{{ route('coupons.index') }}"
                                        class="w-full flex items-center justify-center px-6 py-3 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-transparent text-sm font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/50 transition-all">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const appliesToSelect = document.getElementById('applies_to');
                const prodContainer = document.getElementById('products_container');
                const catContainer = document.getElementById('categories_container');
                const collContainer = document.getElementById('collections_container');

                if(appliesToSelect) {
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
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        </style>
    @endpush
@endsection
