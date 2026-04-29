@extends('layouts.app')

@section('title', __('file.edit_discount_rule'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('discount-rules.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_rules') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.edit_discount_rule') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.modify_settings_for') }}: <span
                            class="text-indigo-600 dark:text-indigo-400 font-bold tracking-widest uppercase">{{ $discountRule->name }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-discount-rule-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.update_rule') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('discount-rules.update', $discountRule) }}" method="POST" enctype="multipart/form-data"
                id="edit-discount-rule-form"
                x-data="{ ruleType: '{{ old('type', $discountRule->type) }}', appliesTo: '{{ old('applies_to', $discountRule->applies_to) }}' }">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- General settings --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.rule_settings') }}
                                </h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.rule_name') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $discountRule->name) }}"
                                        required
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.description') }}</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 resize-y">{{ old('description', $discountRule->description) }}</textarea>
                                    @error('description') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.type') }}
                                            <span class="text-red-500">*</span></label>
                                        <select name="type" x-model="ruleType" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 cursor-pointer">
                                            <option value="percentage">{{ __('file.percentage_off') }} (%)</option>
                                            <option value="fixed">{{ __('file.fixed_amount_off') }} ($)</option>
                                            <option value="bogo">{{ __('file.bogo') }} ({{ __('file.buy_one_get_one') }})
                                            </option>
                                            <option value="buy_x_get_y">{{ __('file.buy_x_get_y') }}
                                                ({{ __('file.custom') }})</option>
                                        </select>
                                    </div>

                                    <div x-show="['percentage', 'fixed'].includes(ruleType)" x-transition.opacity>
                                        <label for="value"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.discount_value') }}</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" min="0" name="value" id="value"
                                                value="{{ old('value', $discountRule->value) }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 font-mono pr-7">
                                            <span x-text="ruleType === 'percentage' ? '%' : '$'"
                                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none"></span>
                                        </div>
                                        @error('value') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>

                                    <div x-show="['bogo', 'buy_x_get_y'].includes(ruleType)" x-transition.opacity
                                        class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="buy_quantity"
                                                class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.buy_x') }}</label>
                                            <input type="number" min="1" name="buy_quantity" id="buy_quantity"
                                                value="{{ old('buy_quantity', $discountRule->buy_quantity ?? 1) }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 font-mono">
                                        </div>
                                        <div>
                                            <label for="get_quantity"
                                                class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.get_y') }}</label>
                                            <input type="number" min="1" name="get_quantity" id="get_quantity"
                                                value="{{ old('get_quantity', $discountRule->get_quantity ?? 1) }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 font-mono">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Requirements & Scope --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.requirements_and_scope') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="min_order_amount"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.minimum_order_amount') }}</label>
                                        <div class="relative">
                                            <span
                                                class="absolute inset-y-0 left-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                            <input type="number" step="0.01" min="0" name="min_order_amount"
                                                id="min_order_amount"
                                                value="{{ old('min_order_amount', $discountRule->min_order_amount) }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 pl-7">
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.applies_to') }}
                                            <span class="text-red-500">*</span></label>
                                        <select name="applies_to" x-model="appliesTo" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 cursor-pointer">
                                            <option value="all">{{ __('file.entire_store') }}</option>
                                            <option value="products">{{ __('file.specific_products') }}</option>
                                            <option value="categories">{{ __('file.specific_categories') }}</option>
                                            <option value="collections">{{ __('file.specific_collections') }}</option>
                                        </select>
                                    </div>
                                </div>

                                @php
                                    $selProd = old('product_ids', $discountRule->products->pluck('id')->toArray());
                                    $selCat = old('category_ids', $discountRule->categories->pluck('id')->toArray());
                                    $selColl = old('collection_ids', $discountRule->collections->pluck('id')->toArray());
                                @endphp

                                {{-- Dynamic Selectors --}}
                                <div x-show="appliesTo === 'products'" x-transition.opacity class="space-y-4" x-data="{ 
                                    search: '', 
                                    page: 1, 
                                    perPage: 10,
                                    products: [ @foreach($products as $p) { id: {{ $p->id }}, name: '{{ addslashes($p->name) }}', checked: {{ (is_array($selProd) && in_array($p->id, $selProd)) ? 'true' : 'false' }} }, @endforeach ],
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
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-0">{{ __('file.select_products') }}</label>
                                        <div class="relative w-full sm:max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                                placeholder="Search products..."
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 pl-9 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-xl border border-gray-200 dark:border-surface-tonal-a30 divide-y divide-gray-50 dark:divide-surface-tonal-a30 overflow-hidden bg-white dark:bg-surface-tonal-a20">
                                        <template x-for="product in paginated" :key="product.id">
                                            <label
                                                class="flex items-center gap-4 px-4 py-3 hover:bg-gray-100 dark:hover:bg-white/5 cursor-pointer transition">
                                                <input type="checkbox" name="product_ids[]" :value="product.id"
                                                    x-model="product.checked"
                                                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="product.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="p-8 text-center text-sm text-gray-400 dark:text-gray-500">No products
                                            found.</div>
                                    </div>
                                    <div class="flex items-center justify-between px-1" x-show="totalPages > 1">
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Page <span
                                                x-text="page"></span> of <span x-text="totalPages"></span></span>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="if(page > 1) page--" :disabled="page === 1"
                                                class="p-1.5 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button type="button" @click="if(page < totalPages) page++"
                                                :disabled="page === totalPages"
                                                class="p-1.5 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <template
                                        x-for="item in products.filter(p => p.checked && !paginated.find(pg => pg.id === p.id))"
                                        :key="'hidden-'+item.id">
                                        <input type="hidden" name="product_ids[]" :value="item.id">
                                    </template>
                                </div>

                                <div x-show="appliesTo === 'categories'" x-transition.opacity class="space-y-4" x-data="{ 
                                    search: '', 
                                    page: 1, 
                                    perPage: 10,
                                    categories: [ @foreach($categories as $c) { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', checked: {{ (is_array($selCat) && in_array($c->id, $selCat)) ? 'true' : 'false' }} }, @endforeach ],
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
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-0">{{ __('file.select_categories') }}</label>
                                        <div class="relative w-full sm:max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                                placeholder="Search categories..."
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 pl-9 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-xl border border-gray-200 dark:border-surface-tonal-a30 divide-y divide-gray-50 dark:divide-surface-tonal-a30 overflow-hidden bg-white dark:bg-surface-tonal-a20">
                                        <template x-for="category in paginated" :key="category.id">
                                            <label
                                                class="flex items-center gap-4 px-4 py-3 hover:bg-gray-100 dark:hover:bg-white/5 cursor-pointer transition">
                                                <input type="checkbox" name="category_ids[]" :value="category.id"
                                                    x-model="category.checked"
                                                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="category.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="p-8 text-center text-sm text-gray-400 dark:text-gray-500">No categories
                                            found.</div>
                                    </div>
                                    <div class="flex items-center justify-between px-1" x-show="totalPages > 1">
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Page <span
                                                x-text="page"></span> of <span x-text="totalPages"></span></span>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="if(page > 1) page--" :disabled="page === 1"
                                                class="p-1.5 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button type="button" @click="if(page < totalPages) page++"
                                                :disabled="page === totalPages"
                                                class="p-1.5 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
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

                                <div x-show="appliesTo === 'collections'" x-transition.opacity class="space-y-4" x-data="{ 
                                    search: '', 
                                    page: 1, 
                                    perPage: 10,
                                    collections: [ @foreach($collections as $c) { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', checked: {{ (is_array($selColl) && in_array($c->id, $selColl)) ? 'true' : 'false' }} }, @endforeach ],
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
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-0">{{ __('file.select_collections') }}</label>
                                        <div class="relative w-full sm:max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                                placeholder="Search collections..."
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 pl-9 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-xl border border-gray-200 dark:border-surface-tonal-a30 divide-y divide-gray-50 dark:divide-surface-tonal-a30 overflow-hidden bg-white dark:bg-surface-tonal-a20">
                                        <template x-for="collection in paginated" :key="collection.id">
                                            <label
                                                class="flex items-center gap-4 px-4 py-3 hover:bg-gray-100 dark:hover:bg-white/5 cursor-pointer transition">
                                                <input type="checkbox" name="collection_ids[]" :value="collection.id"
                                                    x-model="collection.checked"
                                                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="collection.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="p-8 text-center text-sm text-gray-400 dark:text-gray-500">No collections
                                            found.</div>
                                    </div>
                                    <div class="flex items-center justify-between px-1" x-show="totalPages > 1">
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Page <span
                                                x-text="page"></span> of <span x-text="totalPages"></span></span>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="if(page > 1) page--" :disabled="page === 1"
                                                class="p-1.5 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button type="button" @click="if(page < totalPages) page++"
                                                :disabled="page === totalPages"
                                                class="p-1.5 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 disabled:opacity-30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
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
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.promotional_banners') }}</h2>
                                <button type="button" onclick="addBannerRow()"
                                    class="inline-flex items-center px-3 py-1.5 text-[10px] font-black text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-all active:scale-95 uppercase tracking-widest">
                                    + {{ __('file.add_banner') }}
                                </button>
                            </div>
                            <div class="p-4">
                                <div id="banners-container" class="space-y-4">
                                    {{-- Rows injected here --}}
                                </div>
                                <div id="no-banners-msg"
                                    class="py-12 text-center border-2 border-dashed border-gray-100 dark:border-surface-tonal-a30 rounded-xl bg-gray-50/30 dark:bg-surface-tonal-a30/10">
                                    <div
                                        class="mx-auto w-10 h-10 bg-white dark:bg-surface-tonal-a20 rounded-full flex items-center justify-center text-gray-300 dark:text-gray-600 mb-2 shadow-sm">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p
                                        class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                        {{ __('file.no_banners_added_yet') }}</p>
                                    <p class="text-[8px] text-gray-400 dark:text-gray-500 mt-1 uppercase tracking-widest">
                                        {{ __('file.banners_appear_on_category_pages') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">

                        {{-- Status Card --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.status_and_visibility') }}</h2>
                            </div>
                            <div class="p-4 space-y-6">

                                <div class="space-y-3">
                                    <label
                                        class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                        <div class="mt-0.5">
                                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $discountRule->is_active) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-green-500 focus:ring-green-500 transition-all cursor-pointer">
                                        </div>
                                        <div class="ml-3">
                                            <h3
                                                class="text-xs font-bold text-gray-900 dark:text-white leading-none uppercase tracking-wider">
                                                {{ __('file.rule_active') }}</h3>
                                            <p
                                                class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                                {{ __('file.can_be_applied_to_checkouts') }}</p>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                        <div class="mt-0.5">
                                            <input type="checkbox" name="is_flash_sale" id="is_flash_sale" value="1" {{ old('is_flash_sale', $discountRule->is_flash_sale) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-purple-500 focus:ring-purple-500 transition-all cursor-pointer">
                                        </div>
                                        <div class="ml-3">
                                            <h3
                                                class="text-xs font-bold text-purple-600 dark:text-purple-400 leading-none uppercase tracking-wider">
                                                {{ __('file.flash_sale') }}</h3>
                                            <p
                                                class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                                {{ __('file.show_badges_and_priority_banners') }}</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="pt-2">
                                    <label for="priority"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.priority_score') }}</label>
                                    <input type="number" min="0" name="priority" id="priority"
                                        value="{{ old('priority', $discountRule->priority) }}" required
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-indigo-600 dark:text-indigo-400 outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    <p class="text-[8px] text-gray-400 uppercase tracking-widest font-black mt-1">
                                        {{ __('file.higher_runs_first') }}</p>
                                </div>

                                <hr class="border-gray-50 dark:border-surface-tonal-a30">

                                <div class="space-y-4">
                                    <div>
                                        <label for="starts_at"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.starts_at') }}</label>
                                        <input type="datetime-local" name="starts_at" id="starts_at"
                                            value="{{ old('starts_at', $discountRule->starts_at ? $discountRule->starts_at->format('Y-m-d\TH:i') : '') }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                    <div>
                                        <label for="expires_at"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.expires_at') }}</label>
                                        <input type="datetime-local" name="expires_at" id="expires_at"
                                            value="{{ old('expires_at', $discountRule->expires_at ? $discountRule->expires_at->format('Y-m-d\TH:i') : '') }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" form="edit-discount-rule-form"
                                        class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.update_rule') }}
                                    </button>
                                    <a href="{{ route('discount-rules.index') }}"
                                        class="px-6 py-3 border border-gray-200 dark:border-surface-tonal-a30 text-gray-500 text-sm font-bold rounded-xl text-center hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                        {{ __('file.cancel') }}
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
                row.className = 'relative rounded-xl border border-gray-100 dark:border-surface-tonal-a30 p-4 bg-gray-50/30 dark:bg-surface-tonal-a30/10 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all group animate-fade-in-up';

                row.innerHTML = `
                    <button type="button" onclick="removeBannerRow(${index})"
                            class="absolute top-3 right-3 p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">{{ __('file.banner_image') }}</p>
                            <div class="aspect-[16/7] rounded-lg border-2 border-dashed border-gray-100 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 overflow-hidden cursor-pointer hover:border-indigo-400 transition-all relative flex flex-col items-center justify-center gap-1 group/picker"
                                 onclick="document.getElementById('banner-img-${index}').click()">
                                <img id="preview-${index}" src="${data.image_url || ''}" class="absolute inset-0 w-full h-full object-cover ${data.image_url ? '' : 'hidden'}">
                                <div id="placeholder-${index}" class="flex flex-col items-center gap-1 ${data.image_url ? 'hidden' : ''}">
                                    <svg class="h-6 w-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.choose_image') }}</p>
                                </div>
                            </div>
                            <input type="file" name="banners[${index}][image]" id="banner-img-${index}" class="hidden" onchange="previewBanner(this, ${index})" accept="image/*">
                            <input type="hidden" name="banners[${index}][existing_image]" value="${data.existing_image || ''}">
                        </div>
                        <div class="md:col-span-3 space-y-3">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.banner_title') }}</label>
                                <input type="text" name="banners[${index}][title]" value="${data.title || ''}"
                                       placeholder="Catchy headline..."
                                       class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-white dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.description') }}</label>
                                <textarea name="banners[${index}][description]" rows="2"
                                          placeholder="Sub-text highlighting the offer..."
                                          class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-white dark:bg-surface-tonal-a20 px-3 py-2 text-xs text-gray-600 dark:text-gray-400 focus:ring-2 focus:ring-indigo-500 transition-all resize-none">${data.description || ''}</textarea>
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

            document.addEventListener('DOMContentLoaded', function () {
                @if(old('banners'))
                    @foreach(old('banners') as $index => $banner)
                        addBannerRow({
                            title: '{{ addslashes($banner['title'] ?? '') }}',
                            description: '{{ addslashes($banner['description'] ?? '') }}'
                        });
                    @endforeach
                @elseif($discountRule->banner_images)
                    @foreach($discountRule->banner_urls as $banner)
                        addBannerRow({
                            image_url: '{{ $banner['image_url'] }}',
                            existing_image: '{{ $banner['image'] }}',
                            title: '{{ addslashes($banner['title'] ?? '') }}',
                            description: '{{ addslashes($banner['description'] ?? '') }}'
                        });
                    @endforeach
                @endif

                if (!document.getElementById('banners-container').children.length) {
                    document.getElementById('no-banners-msg').classList.remove('hidden');
                }
            });
        </script>
    @endpush
@endsection