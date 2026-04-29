@extends('layouts.app')

@section('title', 'Create Discount Rule')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('discount-rules.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Rules</a>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Create Discount Rule
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Set up automatic discounts, BOGO offers, or
                        flash sales.</p>
                </div>
            </div>

            <form action="{{ route('discount-rules.store') }}" method="POST" enctype="multipart/form-data"
                x-data="{ ruleType: '{{ old('type', 'percentage') }}', appliesTo: '{{ old('applies_to', 'all') }}' }">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column: Settings & Banners --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- General settings --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Rule Settings</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="space-y-1.5">
                                    <label for="name"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Rule Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="e.g. Summer Sale 20% Off"
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label for="description"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                        placeholder="Internal notes about this rule…"
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm resize-none">{{ old('description') }}</textarea>
                                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Type
                                            <span class="text-red-500">*</span></label>
                                        <select name="type" x-model="ruleType" required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                            <option value="percentage">Percentage (%) Off</option>
                                            <option value="fixed">Fixed Amount ($) Off</option>
                                            <option value="bogo">BOGO (Buy One Get One)</option>
                                            <option value="buy_x_get_y">Buy X Get Y (Custom)</option>
                                        </select>
                                    </div>

                                    <div x-show="['percentage', 'fixed'].includes(ruleType)" x-transition.opacity
                                        class="space-y-1.5">
                                        <label for="value"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Discount
                                            Value</label>
                                        <div class="relative">
                                            <span x-text="ruleType === 'percentage' ? '%' : '$'"
                                                class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-indigo-500 opacity-50"></span>
                                            <input type="number" step="0.01" min="0" name="value" id="value"
                                                value="{{ old('value') }}"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 pl-4 pr-10 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm font-mono">
                                        </div>
                                        @error('value') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div x-show="['bogo', 'buy_x_get_y'].includes(ruleType)" x-transition.opacity
                                        class="grid grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label for="buy_quantity"
                                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Buy
                                                X</label>
                                            <input type="number" min="1" name="buy_quantity" id="buy_quantity"
                                                value="{{ old('buy_quantity', 1) }}"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label for="get_quantity"
                                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Get
                                                Y</label>
                                            <input type="number" min="1" name="get_quantity" id="get_quantity"
                                                value="{{ old('get_quantity', 1) }}"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Requirements & Scope --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Requirements & Scope</h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label for="min_order_amount"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Minimum
                                            Order Amount</label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-indigo-500 opacity-50">$</span>
                                            <input type="number" step="0.01" min="0" name="min_order_amount"
                                                id="min_order_amount" value="{{ old('min_order_amount') }}"
                                                placeholder="0.00"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 pl-8 pr-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Applies
                                            To <span class="text-red-500">*</span></label>
                                        <select name="applies_to" x-model="appliesTo" required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <option value="all">Entire Store</option>
                                            <option value="products">Specific Products</option>
                                            <option value="categories">Specific Categories</option>
                                            <option value="collections">Specific Collections</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Dynamic Selectors --}}
                                <div x-show="appliesTo === 'products'" x-transition.opacity class="space-y-3" x-data="{ 
                                                search: '', 
                                                page: 1, 
                                                perPage: 10,
                                                products: [ @foreach($products as $p) { id: {{ $p->id }}, name: '{{ addslashes($p->name) }}', checked: {{ (is_array(old('product_ids')) && in_array($p->id, old('product_ids'))) ? 'true' : 'false' }} }, @endforeach ],
                                                get filtered() {
                                                    return this.products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
                                                },
                                                get paginated() {
                                                    return this.filtered.slice((this.page - 1) * this.perPage, this.page * this.perPage);
                                                },
                                                get totalPages() {
                                                    return Math.ceil(this.filtered.length / this.perPage) || 1;
                                                }
                                             }">
                                    <div class="flex items-center justify-between gap-4">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Select
                                            Products</label>
                                        <div class="relative flex-1 max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                                placeholder="Search products..."
                                                class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-xl border border-gray-200 dark:border-surface-tonal-a30 divide-y divide-gray-100 dark:divide-surface-tonal-a30 shadow-inner overflow-hidden bg-white dark:bg-surface-tonal-a20">
                                        <template x-for="product in paginated" :key="product.id">
                                            <label
                                                class="flex items-center gap-4 px-4 py-3 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/20 cursor-pointer transition">
                                                <input type="checkbox" name="product_ids[]" :value="product.id"
                                                    x-model="product.checked"
                                                    class="h-5 w-5 rounded-lg border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="product.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">No products
                                            found.</div>
                                    </div>
                                    <div class="flex items-center justify-between px-2" x-show="totalPages > 1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Page <span
                                                x-text="page"></span> of <span x-text="totalPages"></span></span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="if(page > 1) page--" :disabled="page === 1"
                                                class="p-2 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button type="button" @click="if(page < totalPages) page++"
                                                :disabled="page === totalPages"
                                                class="p-2 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    {{-- Hidden inputs to ensure checked items NOT on current page are still submitted --}}
                                    <template
                                        x-for="item in products.filter(p => p.checked && !paginated.find(pg => pg.id === p.id))"
                                        :key="'hidden-'+item.id">
                                        <input type="hidden" name="product_ids[]" :value="item.id">
                                    </template>
                                </div>

                                <div x-show="appliesTo === 'categories'" x-transition.opacity class="space-y-3" x-data="{ 
                                                search: '', 
                                                page: 1, 
                                                perPage: 10,
                                                categories: [ @foreach($categories as $c) { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', checked: {{ (is_array(old('category_ids')) && in_array($c->id, old('category_ids'))) ? 'true' : 'false' }} }, @endforeach ],
                                                get filtered() {
                                                    return this.categories.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                                                },
                                                get paginated() {
                                                    return this.filtered.slice((this.page - 1) * this.perPage, this.page * this.perPage);
                                                },
                                                get totalPages() {
                                                    return Math.ceil(this.filtered.length / this.perPage) || 1;
                                                }
                                             }">
                                    <div class="flex items-center justify-between gap-4">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Select
                                            Categories</label>
                                        <div class="relative flex-1 max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                                placeholder="Search categories..."
                                                class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-xl border border-gray-200 dark:border-surface-tonal-a30 divide-y divide-gray-100 dark:divide-surface-tonal-a30 shadow-inner overflow-hidden bg-white dark:bg-surface-tonal-a20">
                                        <template x-for="category in paginated" :key="category.id">
                                            <label
                                                class="flex items-center gap-4 px-4 py-3 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/20 cursor-pointer transition">
                                                <input type="checkbox" name="category_ids[]" :value="category.id"
                                                    x-model="category.checked"
                                                    class="h-5 w-5 rounded-lg border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="category.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">No categories
                                            found.</div>
                                    </div>
                                    <div class="flex items-center justify-between px-2" x-show="totalPages > 1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Page <span
                                                x-text="page"></span> of <span x-text="totalPages"></span></span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="if(page > 1) page--" :disabled="page === 1"
                                                class="p-2 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button type="button" @click="if(page < totalPages) page++"
                                                :disabled="page === totalPages"
                                                class="p-2 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <template
                                        x-for="item in categories.filter(c => c.checked && !paginated.find(pg => pg.id === c.id))"
                                        :key="'hidden-'+item.id">
                                        <input type="hidden" name="category_ids[]" :value="item.id">
                                    </template>
                                </div>

                                <div x-show="appliesTo === 'collections'" x-transition.opacity class="space-y-3" x-data="{ 
                                                search: '', 
                                                page: 1, 
                                                perPage: 10,
                                                collections: [ @foreach($collections as $c) { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', checked: {{ (is_array(old('collection_ids')) && in_array($c->id, old('collection_ids'))) ? 'true' : 'false' }} }, @endforeach ],
                                                get filtered() {
                                                    return this.collections.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                                                },
                                                get paginated() {
                                                    return this.filtered.slice((this.page - 1) * this.perPage, this.page * this.perPage);
                                                },
                                                get totalPages() {
                                                    return Math.ceil(this.filtered.length / this.perPage) || 1;
                                                }
                                             }">
                                    <div class="flex items-center justify-between gap-4">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Select
                                            Collections</label>
                                        <div class="relative flex-1 max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                                placeholder="Search collections..."
                                                class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-xl border border-gray-200 dark:border-surface-tonal-a30 divide-y divide-gray-100 dark:divide-surface-tonal-a30 shadow-inner overflow-hidden bg-white dark:bg-surface-tonal-a20">
                                        <template x-for="collection in paginated" :key="collection.id">
                                            <label
                                                class="flex items-center gap-4 px-4 py-3 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/20 cursor-pointer transition">
                                                <input type="checkbox" name="collection_ids[]" :value="collection.id"
                                                    x-model="collection.checked"
                                                    class="h-5 w-5 rounded-lg border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="collection.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">No collections
                                            found.</div>
                                    </div>
                                    <div class="flex items-center justify-between px-2" x-show="totalPages > 1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Page <span
                                                x-text="page"></span> of <span x-text="totalPages"></span></span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="if(page > 1) page--" :disabled="page === 1"
                                                class="p-2 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button type="button" @click="if(page < totalPages) page++"
                                                :disabled="page === totalPages"
                                                class="p-2 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <template
                                        x-for="item in collections.filter(c => c.checked && !paginated.find(pg => pg.id === c.id))"
                                        :key="'hidden-'+item.id">
                                        <input type="hidden" name="collection_ids[]" :value="item.id">
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Banners Section --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Promotional Banners</h2>
                                <button type="button" onclick="addBannerRow()"
                                    class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                                    + Add Banner
                                </button>
                            </div>
                            <div class="p-6">
                                <div id="banners-container" class="space-y-6">
                                    {{-- Rows injected here --}}
                                </div>
                                <div id="no-banners-msg"
                                    class="py-12 text-center border-2 border-dashed border-gray-200 dark:border-surface-tonal-a30 rounded-2xl">
                                    <div
                                        class="mx-auto w-12 h-12 bg-gray-50 dark:bg-surface-tonal-a30 rounded-full flex items-center justify-center text-gray-400 mb-2">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No banners added yet.
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">These will appear as defaults
                                        on category pages.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Status & Sidebar --}}
                    <div class="lg:col-span-1 space-y-6">

                        {{-- Status & Meta --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Status & Visibility</h2>
                            </div>
                            <div class="p-6 space-y-6">

                                <div class="space-y-4">
                                    <label
                                        class="flex items-center p-3 rounded-xl border border-gray-100 dark:border-surface-tonal-a30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/50 transition cursor-pointer">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                            class="h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-green-500 focus:ring-green-500 transition-all">
                                        <div class="ml-4">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Rule Active</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Can be applied to checkouts
                                            </p>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-3 rounded-xl border border-gray-100 dark:border-surface-tonal-a30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/50 transition cursor-pointer">
                                        <input type="checkbox" name="is_flash_sale" id="is_flash_sale" value="1" {{ old('is_flash_sale') ? 'checked' : '' }}
                                            class="h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-purple-500 focus:ring-purple-500 transition-all">
                                        <div class="ml-4">
                                            <p class="text-sm font-bold text-purple-600 dark:text-purple-400">Flash Sale</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Show badges and priority
                                                banners</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-1.5 pt-2">
                                    <label for="priority"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Priority
                                        Score</label>
                                    <input type="number" min="0" name="priority" id="priority"
                                        value="{{ old('priority', 0) }}" required
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-indigo-600 dark:text-indigo-400 focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Higher runs
                                        first</p>
                                </div>

                                <hr class="border-gray-100 dark:border-surface-tonal-a30">

                                <div class="space-y-4">
                                    <div class="space-y-1.5">
                                        <label for="starts_at"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Starts
                                            At</label>
                                        <input type="datetime-local" name="starts_at" id="starts_at"
                                            value="{{ old('starts_at') }}"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a30 px-4 py-2.5 text-xs text-gray-700 dark:text-gray-300">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="expires_at"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Ends
                                            At</label>
                                        <input type="datetime-local" name="expires_at" id="expires_at"
                                            value="{{ old('expires_at') }}"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a30 px-4 py-2.5 text-xs text-gray-700 dark:text-gray-300">
                                    </div>
                                </div>

                                <div class="pt-4 flex flex-col gap-3">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center px-6 py-4 rounded-xl bg-gray-900 dark:bg-white text-sm font-bold text-white dark:text-gray-900 hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl shadow-gray-200 dark:shadow-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                                        Create Rule
                                    </button>
                                    <a href="{{ route('discount-rules.index') }}"
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
            let bannerCount = 0;

            function addBannerRow(data = {}) {
                const container = document.getElementById('banners-container');
                document.getElementById('no-banners-msg').classList.add('hidden');

                const index = bannerCount++;
                const row = document.createElement('div');
                row.id = `banner-row-${index}`;
                row.className = 'relative rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 p-6 bg-gray-50/30 dark:bg-surface-tonal-a30/10 hover:border-indigo-200 dark:hover:border-indigo-900 transition-all group animate-fade-in-up';

                row.innerHTML = `
                                    <button type="button" onclick="removeBannerRow(${index})"
                                            class="absolute top-4 right-4 p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-surface-tonal-a30 transition-all opacity-0 group-hover:opacity-100">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                                        <div class="md:col-span-2">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Banner Image</p>
                                            <div class="aspect-[16/7] rounded-xl border-2 border-dashed border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 overflow-hidden cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-600 transition-all relative flex flex-col items-center justify-center gap-2 group/picker"
                                                 onclick="document.getElementById('banner-img-${index}').click()">
                                                <img id="preview-${index}" src="${data.image_url || ''}" class="absolute inset-0 w-full h-full object-cover ${data.image_url ? '' : 'hidden'}">
                                                <div id="placeholder-${index}" class="flex flex-col items-center gap-1 transition-transform group-hover/picker:scale-110 ${data.image_url ? 'hidden' : ''}">
                                                    <svg class="h-8 w-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Choose Image</p>
                                                </div>
                                            </div>
                                            <input type="file" name="banners[${index}][image]" id="banner-img-${index}" class="hidden" onchange="previewBanner(this, ${index})" accept="image/*">
                                        </div>
                                        <div class="md:col-span-3 space-y-4">
                                            <div class="space-y-1.5">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Banner Title</label>
                                                <input type="text" name="banners[${index}][title]" value="${data.title || ''}"
                                                       placeholder="Catchy headline..."
                                                       class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Description</label>
                                                <textarea name="banners[${index}][description]" rows="2"
                                                          placeholder="Sub-text highlighting the offer..."
                                                          class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-400 focus:ring-2 focus:ring-indigo-500 transition-all resize-none">${data.description || ''}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                `;

                container.appendChild(row);
            }

            function removeBannerRow(index) {
                const row = document.getElementById(`banner-row-${index}`);
                row.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    row.remove();
                    if (!document.getElementById('banners-container').children.length) {
                        document.getElementById('no-banners-msg').classList.remove('hidden');
                    }
                }, 200);
            }

            function previewBanner(input, index) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById(`preview-${index}`).src = e.target.result;
                        document.getElementById(`preview-${index}`).classList.remove('hidden');
                        document.getElementById(`placeholder-${index}`).classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Restore old banners from validation failure
            document.addEventListener('DOMContentLoaded', function () {
                @if(old('banners'))
                    @foreach(old('banners') as $index => $banner)
                        addBannerRow({
                            title: '{{ addslashes($banner['title'] ?? '') }}',
                            description: '{{ addslashes($banner['description'] ?? '') }}'
                        });
                    @endforeach
                @endif
                            });
        </script>

        <style>
            @keyframes fade-in-up {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fade-in-up 0.3s ease-out forwards;
            }
        </style>
    @endpush
@endsection