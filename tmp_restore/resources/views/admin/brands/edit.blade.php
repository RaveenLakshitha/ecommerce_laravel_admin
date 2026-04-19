@extends('layouts.app')

@section('title', 'Edit Brand')

@section('content')
    <div class="admin-page">
        <div class="admin-page-inner">

            {{-- Header --}}
            <div class="admin-page-header">
                <div>
                    <a href="{{ route('brands.index') }}" class="admin-breadcrumb">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back to Brands
                    </a>
                    <h1 class="admin-page-title">Edit Brand</h1>
                    <p class="admin-page-subtitle">Update details for: <span class="font-semibold text-gray-900 dark:text-white">{{ $brand->name }}</span></p>
                </div>
            </div>

            <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- General Information --}}
                        <div class="admin-card">
                            <div class="admin-card-header">
                                <h2>General Information</h2>
                            </div>
                            <div class="admin-card-body space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="name" class="fi-label fi-label-required">Brand Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required class="fi">
                                        @error('name') <p class="fi-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="slug" class="fi-label">Slug</label>
                                        <input type="text" name="slug" id="slug" value="{{ old('slug', $brand->slug) }}" placeholder="auto-generated" class="fi">
                                        @error('slug') <p class="fi-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="website_url" class="fi-label">Website URL</label>
                                        <input type="url" name="website_url" id="website_url" value="{{ old('website_url', $brand->website_url) }}" placeholder="https://example.com" class="fi">
                                        @error('website_url') <p class="fi-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="sort_order" class="fi-label">Sort Order</label>
                                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $brand->sort_order) }}" class="fi">
                                        @error('sort_order') <p class="fi-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="description" class="fi-label">Description</label>
                                    <textarea name="description" id="description" rows="4" placeholder="Brief brand story or overview…" class="fi">{{ old('description', $brand->description) }}</textarea>
                                    @error('description') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SEO --}}
                        <div class="admin-card">
                            <div class="admin-card-header">
                                <h2>Search Engine Optimization (SEO)</h2>
                            </div>
                            <div class="admin-card-body space-y-5">
                                <div>
                                    <label for="meta_title" class="fi-label">Meta Title</label>
                                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $brand->meta_title) }}" placeholder="SEO Title for search results" class="fi">
                                </div>
                                <div>
                                    <label for="meta_description" class="fi-label">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" rows="3" placeholder="Brief summary for search engine snippets…" class="fi">{{ old('meta_description', $brand->meta_description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-6">
                        <div class="admin-card sticky top-24">
                            <div class="admin-card-header">
                                <h2>Status & Logo</h2>
                            </div>
                            <div class="admin-card-body space-y-6">
                                <div class="space-y-3">
                                    <label class="ck-card">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-500 transition-all">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Featured Brand</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Highlight on storefront</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <label class="fi-label">Brand Logo</label>
                                    <div class="aspect-square admin-upload-zone"
                                         onclick="document.getElementById('logo-input').click()">
                                        <img id="logo-preview" src="{{ $brand->logo_url }}" class="absolute inset-0 w-full h-full object-contain p-4 {{ $brand->logo_url ? '' : 'hidden' }}">
                                        <div id="logo-placeholder" class="admin-upload-placeholder {{ $brand->logo_url ? 'hidden' : '' }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p>Change Brand Logo</p>
                                        </div>
                                        <input type="file" name="logo" id="logo-input" class="hidden" accept="image/*" onchange="previewLogo(this)">
                                    </div>

                                    @if($brand->logo_url)
                                        <label class="flex items-center gap-2 mt-1 cursor-pointer group">
                                            <input type="checkbox" name="remove_logo" id="remove_logo" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-red-500 transition-colors">Remove existing logo</span>
                                        </label>
                                    @endif

                                    @error('logo') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" class="admin-btn-primary">Save Changes</button>
                                    <a href="{{ route('brands.index') }}" class="admin-btn-secondary">Cancel</a>
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
                    reader.onload = function(e) {
                        document.getElementById('logo-preview').src = e.target.result;
                        document.getElementById('logo-preview').classList.remove('hidden');
                        document.getElementById('logo-placeholder').classList.add('hidden');
                        const removeCheckbox = document.getElementById('remove_logo');
                        if (removeCheckbox) removeCheckbox.checked = false;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection
