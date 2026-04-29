@extends('layouts.app')

@section('title', 'Add Collection')

@section('content')
    <div class="admin-page">
        <div class="admin-page-inner">

            {{-- Header --}}
            <div class="admin-page-header">
                <div>
                    <a href="{{ route('collections.index') }}" class="admin-breadcrumb">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Collections
                    </a>
                    <h1 class="admin-page-title">Add New Collection</h1>
                    <p class="admin-page-subtitle">Curate a group of products for specialized browsing or promotions.</p>
                </div>
            </div>

            <form action="{{ route('collections.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- General Information --}}
                        <div class="admin-card">
                            <div class="admin-card-header">
                                <h2>General Information</h2>
                            </div>
                            <div class="admin-card-body space-y-5">
                                <div>
                                    <label for="name" class="fi-label fi-label-required">Collection Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="e.g. Summer Essentials" class="fi">
                                    @error('name') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="description" class="fi-label">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                        placeholder="Brief description of this collection…"
                                        class="fi">{{ old('description') }}</textarea>
                                    @error('description') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                                    <div>
                                        <label for="start_date" class="fi-label">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                            class="fi">
                                        @error('start_date') <p class="fi-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="end_date" class="fi-label">End Date</label>
                                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                            class="fi">
                                        @error('end_date') <p class="fi-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Products Selection --}}
                        <div class="admin-card">
                            <div class="admin-card-header">
                                <h2>Products</h2>
                            </div>
                            <div class="admin-card-body">
                                <div class="space-y-4" x-data="{
                                        search: '',
                                        page: 1,
                                        perPage: 10,
                                        products: [ @foreach($products as $p) { id: {{ $p->id }}, name: '{{ addslashes($p->name) }}', checked: {{ (is_array(old('products')) && in_array($p->id, old('products'))) ? 'true' : 'false' }} }, @endforeach ],
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
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <label class="fi-label !mb-0">Select Products</label>
                                        <div class="relative w-full sm:max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                                placeholder="Search products..." class="fi !pl-9 !py-2 !text-xs">
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
                                                class="flex items-center gap-4 px-4 py-3 hover:bg-gray-100/50 dark:hover:bg-surface-tonal-a30/30 cursor-pointer transition">
                                                <input type="checkbox" name="products[]" :value="product.id"
                                                    x-model="product.checked"
                                                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-500 transition-all">
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
                                        <input type="hidden" name="products[]" :value="item.id">
                                    </template>
                                </div>
                                @error('products') <p class="fi-error mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-6">
                        <div class="admin-card sticky top-24">
                            <div class="admin-card-header">
                                <h2>Status & Visibility</h2>
                            </div>
                            <div class="admin-card-body space-y-6">
                                <div class="space-y-3">
                                    <label class="ck-card">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-500 transition-all">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Collection
                                                Active</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Available to customers</p>
                                        </div>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-500 transition-all">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Featured Content
                                            </h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Highlight on homepage</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <label class="fi-label">Banner Image</label>
                                    <div class="aspect-[16/9] admin-upload-zone"
                                        onclick="document.getElementById('banner-input').click()">
                                        <img id="banner-preview" src=""
                                            class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                        <div id="banner-placeholder" class="admin-upload-placeholder">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p>Click to Upload Banner</p>
                                        </div>
                                        <input type="file" name="banner_image" id="banner-input" class="hidden"
                                            accept="image/*" onchange="previewBanner(this)">
                                    </div>
                                    @error('banner_image') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" class="admin-btn-primary">Create Collection</button>
                                    <a href="{{ route('collections.index') }}" class="admin-btn-secondary">Cancel</a>
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
            function previewBanner(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('banner-preview').src = e.target.result;
                        document.getElementById('banner-preview').classList.remove('hidden');
                        document.getElementById('banner-placeholder').classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection