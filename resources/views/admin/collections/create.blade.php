@extends('layouts.app')

@section('title', __('file.add_new_collection'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('collections.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_collections') }}
                </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.add_new_collection') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.create_new_collection_helper') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-collection-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_collection') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('collections.store') }}" method="POST" enctype="multipart/form-data"
                id="create-collection-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
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
                                    <label for="name"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.collection_name') }}</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="{{ __('file.eg_summer_essentials') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.description') }}</label>
                                    <textarea name="description" id="description" rows="4"
                                        placeholder="{{ __('file.brief_description_collection') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('description') }}</textarea>
                                    @error('description') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                                    <div>
                                        <label for="start_date"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.start_date') }}</label>
                                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                        @error('start_date') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="end_date"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.end_date') }}</label>
                                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                        @error('end_date') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Products Selection --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.products') }}</h2>
                            </div>
                            <div class="p-4">
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
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-0">{{ __('file.select_products') }}</label>
                                        <div class="relative w-full sm:max-w-xs">
                                            <input type="text" x-model="search" @input="page = 1"
                                            placeholder="{{ __('file.search_products') }}..."
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 pl-9 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
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
                                                <input type="checkbox" name="products[]" :value="product.id"
                                                    x-model="product.checked"
                                                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    x-text="product.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="p-8 text-center text-sm text-gray-400 dark:text-gray-500">{{ __('file.no_products_found') }}</div>
                                    </div>
                                    <div class="flex items-center justify-between px-1" x-show="totalPages > 1">
                                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('file.Page') }} <span
                                                x-text="page"></span> {{ __('file.of') }} <span x-text="totalPages"></span></span>
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
                                @error('products') <p class="text-[10px] text-red-500 mt-2 font-bold px-1">{{ $message }}
                                </p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.status_visibility') }}</h2>
                            </div>
                            <div class="p-4 space-y-6">
                                <div class="space-y-3">
                                    <label
                                        class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                            class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                        <div class="ml-3">
                                            <h3
                                                class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                                {{ __('file.collection_active') }}</h3>
                                            <p
                                                class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-0.5">
                                                {{ __('file.available_to_customers') }}</p>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                            class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                        <div class="ml-3">
                                            <h3
                                                class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                                {{ __('file.featured_content') }}</h3>
                                            <p
                                                class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-0.5">
                                                {{ __('file.highlight_on_homepage') }}</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.banner_image') }}</label>
                                    <div class="aspect-[16/9] admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all overflow-hidden relative"
                                        onclick="document.getElementById('banner-input').click()">
                                        <img id="banner-preview" src=""
                                            class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                        <div id="banner-placeholder"
                                            class="flex flex-col items-center justify-center gap-1">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                {{ __('file.upload_banner') }}</p>
                                        </div>
                                        <input type="file" name="banner_image" id="banner-input" class="hidden"
                                            accept="image/*" onchange="previewBanner(this)">
                                    </div>
                                    @error('banner_image') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                    {{ $message }}</p> @enderror
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