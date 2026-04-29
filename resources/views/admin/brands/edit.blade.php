@extends('layouts.app')

@section('title', __('file.edit_brand'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('brands.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_brands') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('file.edit_brand') }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.updating') }}: <span
                            class="font-bold text-gray-900 dark:text-white">{{ $brand->name }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-brand-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_changes') }}
                    </button>
                </div>
            </div>

            <form id="edit-brand-form" action="{{ route('brands.update', $brand->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')

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
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.brand_name') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}"
                                            required
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                        @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="slug"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.slug') }}</label>
                                        <input type="text" name="slug" id="slug" value="{{ old('slug', $brand->slug) }}"
                                            placeholder="{{ __('file.auto_generated') }}"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 font-mono">
                                        @error('slug') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="website_url"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.website_url') }}</label>
                                        <input type="url" name="website_url" id="website_url"
                                            value="{{ old('website_url', $brand->website_url) }}"
                                            placeholder="https://example.com"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-primary dark:text-primary outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                        @error('website_url') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="sort_order"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.sort_order') }}</label>
                                        <input type="number" name="sort_order" id="sort_order"
                                            value="{{ old('sort_order', $brand->sort_order) }}"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                        @error('sort_order') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.description') }}</label>
                                    <textarea name="description" id="description" rows="4"
                                        placeholder="{{ __('file.brief_brand_story') }}…"
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 resize-y">{{ old('description', $brand->description) }}</textarea>
                                    @error('description') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                    {{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SEO --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.seo_settings') }}
                                </h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="meta_title"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.meta_title') }}</label>
                                    <input type="text" name="meta_title" id="meta_title"
                                        value="{{ old('meta_title', $brand->meta_title) }}"
                                        placeholder="{{ __('file.seo_title_search') }}"
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                </div>
                                <div>
                                    <label for="meta_description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.meta_description') }}</label>
                                    <textarea name="meta_description" id="meta_description" rows="3"
                                        placeholder="{{ __('file.brief_summary_seo') }}…"
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 resize-y">{{ old('meta_description', $brand->meta_description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.status_and_logo') }}
                                </h2>
                            </div>
                            <div class="p-4 space-y-6">
                                <label
                                    class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium text-gray-900 dark:text-white leading-none">
                                            {{ __('file.featured_brand') }}</h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1">
                                            {{ __('file.highlight_on_storefront') }}</p>
                                    </div>
                                </label>

                                <div class="space-y-2">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.brand_logo') }}</label>
                                    <div class="aspect-square admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-white/5 bg-gray-100/50 dark:bg-surface-tonal-a10 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all relative overflow-hidden group"
                                        onclick="document.getElementById('logo-input').click()">
                                        <img id="logo-preview" src="{{ $brand->logo_url }}"
                                            class="absolute inset-0 w-full h-full object-contain p-4 {{ $brand->logo_url ? '' : 'hidden' }}">
                                        <div id="logo-placeholder"
                                            class="flex flex-col items-center justify-center gap-1 {{ $brand->logo_url ? 'hidden' : '' }}">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-400">
                                                {{ __('file.change_logo') }}</p>
                                        </div>
                                        <input type="file" name="logo" id="logo-input" class="hidden" accept="image/*"
                                            onchange="previewLogo(this)">
                                    </div>

                                    @if($brand->logo_url)
                                        <label class="flex items-center gap-2 mt-2 cursor-pointer group">
                                            <input type="checkbox" name="remove_logo" id="remove_logo" value="1"
                                                class="h-4 w-4 rounded border-gray-300 text-red-500 focus:ring-red-500 transition-all">
                                            <span
                                                class="text-xs font-medium text-gray-500 dark:text-gray-400 group-hover:text-red-500 transition-colors">{{ __('file.remove_existing_logo') }}</span>
                                        </label>
                                    @endif

                                    @error('logo') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" form="edit-brand-form"
                                        class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.save_changes') }}
                                    </button>
                                    <a href="{{ route('brands.index') }}"
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
            function previewLogo(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('logo-preview').src = e.target.result;
                        document.getElementById('logo-preview').classList.remove('hidden');
                        document.getElementById('logo-placeholder').classList.add('hidden');
                        const removeCheckbox = document.getElementById('remove_logo');
                        if (removeCheckbox) removeCheckbox.checked = false;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            document.getElementById('name')?.addEventListener('input', function () {
                document.getElementById('slug').value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });
        </script>
    @endpush
@endsection