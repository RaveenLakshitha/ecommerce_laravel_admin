@extends('layouts.app')

@section('title', __('file.edit_brand') ?? 'Edit Brand')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                    {{ __('file.edit_brand') ?? 'Edit Brand' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('file.update_brand_details') ?? 'Update the details for' }} <span class="font-medium text-gray-900 dark:text-primary-a0">{{ $brand->name }}</span>
                </p>
            </div>
            <a href="{{ route('brands.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-surface-tonal-a30 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('file.back') ?? 'Back to Brands' }}
            </a>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden relative">
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-6">{{ __('file.brand_details') ?? 'Brand Details' }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('file.name') ?? 'Brand Name' }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0 sm:text-sm px-4 py-2 border">
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('file.slug') ?? 'Slug' }}
                                </label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                        {{ url('/brands') }}/
                                    </span>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug', $brand->slug) }}"
                                        class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0">
                                </div>
                            </div>

                            <div>
                                <label for="website_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('file.website_url') ?? 'Website URL' }}
                                </label>
                                <input type="url" name="website_url" id="website_url" value="{{ old('website_url', $brand->website_url) }}" placeholder="https://..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0 sm:text-sm px-4 py-2 border">
                            </div>

                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('file.sort_order') ?? 'Sort Order' }}
                                </label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $brand->sort_order) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0 sm:text-sm px-4 py-2 border">
                            </div>

                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:ring-offset-gray-800">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_featured" class="font-medium text-gray-700 dark:text-gray-300">{{ __('file.featured') ?? 'Featured Brand' }}</label>
                                    <p class="text-gray-500 dark:text-gray-400">Highlight this brand on the storefront.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('file.logo') ?? 'Brand Logo' }}
                                </label>

                                @if($brand->logo_url)
                                    <div class="mb-4 relative group w-max">
                                        <img src="{{ $brand->logo_url }}" alt="Current Logo" class="h-32 w-auto object-contain rounded-lg border border-gray-200 dark:border-surface-tonal-a30 p-2 bg-white dark:bg-surface-tonal-a20">
                                    </div>
                                    <div class="flex items-center gap-2 mb-4">
                                        <input type="checkbox" name="remove_logo" id="remove_logo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 dark:border-gray-600 dark:bg-surface-tonal-a30">
                                        <label for="remove_logo" class="text-sm text-red-600 dark:text-red-400">Remove current logo</label>
                                    </div>
                                @endif

                                <div class="mt-1 flex justify-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-10 hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors bg-gray-50 dark:bg-surface-tonal-a10/50">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="mt-4 flex text-sm leading-6 text-gray-600 dark:text-gray-400 justify-center">
                                            <label for="logo" class="relative cursor-pointer rounded-md bg-white dark:bg-surface-tonal-a20 font-semibold text-indigo-600 dark:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                                <span>Upload a new file</span>
                                                <input id="logo" name="logo" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs leading-5 text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB. This will replace the current logo.</p>
                                    </div>
                                </div>
                                <div id="image-preview-container" class="mt-4 hidden">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Preview:</p>
                                    <img id="image-preview" src="#" alt="Preview" class="h-32 w-auto object-contain rounded border border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 p-1">
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('file.description') ?? 'Description' }}
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0 sm:text-sm px-4 py-2 border">{{ old('description', $brand->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Section -->
            <div class="bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden relative">
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-6">Search engine optimization</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Page title
                            </label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $brand->meta_title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0 sm:text-sm px-4 py-2 border">
                        </div>
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Meta description
                            </label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0 sm:text-sm px-4 py-2 border">{{ old('meta_description', $brand->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-surface-tonal-a10/50 border-t border-gray-200 dark:border-surface-tonal-a30 flex items-center justify-end gap-3">
                    <a href="{{ route('brands.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                        Update Brand
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('image-preview-container').classList.remove('hidden');
                    
                    // Uncheck 'remove_logo' if a new file is selected
                    let removeLogoCheckbox = document.getElementById('remove_logo');
                    if(removeLogoCheckbox) {
                        removeLogoCheckbox.checked = false;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
@endsection

