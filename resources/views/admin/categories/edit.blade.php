@extends('layouts.app')

@section('title', __('file.edit_category'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('categories.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_categories') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.edit_category') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.updating') }}: <span
                            class="font-bold text-gray-900 dark:text-white">{{ $category->name }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-category-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.update_category') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data"
                id="edit-category-form">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Basic Information --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.basic_information') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.category_name') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                                        required
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                    @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div>
                                    <label for="parent_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.parent_category') }}</label>
                                    <select name="parent_id" id="parent_id"
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 cursor-pointer">
                                        <option value="">{{ __('file.none_top_level') }}</option>
                                        @foreach($parents as $parent)
                                            @if($parent->id !== $category->id)
                                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('parent_id') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.description') }}</label>
                                    <textarea name="description" id="description" rows="4"
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 resize-y">{{ old('description', $category->description) }}</textarea>
                                    @error('description') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                    {{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Banners --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.category_banners') }}</h2>
                                <button type="button" onclick="addBannerRow()"
                                    class="py-1.5 px-3 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-lg transition-all active:scale-[0.98]">
                                    + {{ __('file.add_banner') }}
                                </button>
                            </div>
                            <div class="p-4">
                                <div id="banners-container" class="space-y-4"></div>
                                <div id="no-banners-msg"
                                    class="py-12 text-center border-2 border-dashed border-gray-100 dark:border-surface-tonal-a30 rounded-2xl hidden bg-gray-50/10">
                                    <div
                                        class="mx-auto w-12 h-12 bg-gray-100 dark:bg-surface-tonal-a30 rounded-full flex items-center justify-center text-gray-400 mb-2">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                        {{ __('file.no_banners_added') }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 uppercase tracking-widest">
                                        {{ __('file.banner_recommended_size') }}</p>
                                </div>
                                @error('banners.*.image') <p class="text-[10px] text-red-500 mt-2 font-bold px-1">
                                {{ $message }}</p> @enderror
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
                                    {{ __('file.status_and_visibility') }}</h2>
                            </div>
                            <div class="p-4 space-y-6">
                                <label
                                    class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white leading-none uppercase tracking-wider">
                                            {{ __('file.category_active') }}</h3>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                            {{ __('file.visible_on_storefront') }}</p>
                                    </div>
                                </label>

                                <div class="space-y-2">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.thumbnail_image') }}</label>
                                    <div class="aspect-square admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all overflow-hidden relative group"
                                        onclick="document.getElementById('image-input').click()">
                                        <img id="image-preview" src="{{ $category->image_url }}"
                                            class="absolute inset-0 w-full h-full object-cover {{ $category->image ? '' : 'hidden' }}">
                                        <div id="image-placeholder"
                                            class="flex flex-col items-center justify-center gap-1 {{ $category->image ? 'hidden' : '' }}">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                {{ __('file.change_thumbnail') }}</p>
                                        </div>
                                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*"
                                            onchange="previewMainImage(this)">
                                    </div>
                                    @error('image') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" form="edit-category-form"
                                        class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.update_category') }}
                                    </button>
                                    <a href="{{ route('categories.index') }}"
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

            function previewMainImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('image-preview').src = e.target.result;
                        document.getElementById('image-preview').classList.remove('hidden');
                        document.getElementById('image-placeholder').classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function addBannerRow(data = {}) {
                const container = document.getElementById('banners-container');
                const noMsg = document.getElementById('no-banners-msg');
                if (noMsg) noMsg.classList.add('hidden');

                const index = Date.now() + Math.floor(Math.random() * 1000);
                const row = document.createElement('div');
                row.id = `banner-row-${index}`;
                row.className = 'relative rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 p-6 bg-gray-50/10 dark:bg-surface-tonal-a30/5 hover:border-indigo-200 dark:hover:border-indigo-500/20 transition-all group animate-fade-in-up';

                row.innerHTML = `
                    <button type="button" onclick="removeBannerRow(${index})"
                            class="absolute top-4 right-4 p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.banner_image') }}</label>
                            <div class="aspect-[16/7] admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all overflow-hidden relative"
                                 onclick="document.getElementById('banner-img-${index}').click()">
                                <img id="preview-${index}" src="${data.image_url || ''}" class="absolute inset-0 w-full h-full object-cover ${data.image_url ? '' : 'hidden'}">
                                <div id="placeholder-${index}" class="flex flex-col items-center justify-center gap-1 ${data.image_url ? 'hidden' : ''}">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.choose_image') }}</p>
                                </div>
                            </div>
                            <input type="file" name="banners[${index}][image]" id="banner-img-${index}" class="hidden" onchange="previewBanner(this, ${index})" accept="image/*">
                            <input type="hidden" name="banners[${index}][existing_image]" value="${data.existing_image || ''}">
                        </div>
                        <div class="md:col-span-3 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.banner_title') }}</label>
                                <input type="text" name="banners[${index}][title]" value="${data.title || ''}" placeholder="{{ __('file.catchy_headline') }}" 
                                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.description') }}</label>
                                <textarea name="banners[${index}][description]" rows="2" placeholder="{{ __('file.sub_text_highlighting_offer') }}" 
                                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 resize-y">${data.description || ''}</textarea>
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
                            description: '{{ addslashes($banner['description'] ?? '') }}',
                            existing_image: '{{ $banner['existing_image'] ?? '' }}'
                        });
                    @endforeach
                @else
                    @foreach($category->banner_urls as $banner)
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