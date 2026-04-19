@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
    <div class="admin-page">
        <div class="admin-page-inner">

            {{-- Header --}}
            <div class="admin-page-header">
                <div>
                    <a href="{{ route('categories.index') }}" class="admin-breadcrumb">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back to Categories
                    </a>
                    <h1 class="admin-page-title">Add New Category</h1>
                    <p class="admin-page-subtitle">Create a new inventory category to organize your products.</p>
                </div>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Basic Information --}}
                        <div class="admin-card">
                            <div class="admin-card-header">
                                <h2>Basic Information</h2>
                            </div>
                            <div class="admin-card-body space-y-5">
                                <div>
                                    <label for="name" class="fi-label fi-label-required">Category Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="e.g. Surgical Instruments" class="fi">
                                    @error('name') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="parent_id" class="fi-label">Parent Category</label>
                                    <select name="parent_id" id="parent_id" class="fi">
                                        <option value="">None (Top Level)</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="description" class="fi-label">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                        placeholder="Brief description of this category…" class="fi">{{ old('description') }}</textarea>
                                    @error('description') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Banners --}}
                        <div class="admin-card">
                            <div class="admin-card-header flex items-center justify-between">
                                <h2>Category Banners</h2>
                                <button type="button" onclick="addBannerRow()" class="admin-btn-add !w-auto !py-2 !px-4 !text-xs">
                                    + Add Banner
                                </button>
                            </div>
                            <div class="admin-card-body">
                                <div id="banners-container" class="space-y-6"></div>
                                <div id="no-banners-msg"
                                    class="py-12 text-center border-2 border-dashed border-gray-200 dark:border-surface-tonal-a30 rounded-2xl">
                                    <div class="mx-auto w-12 h-12 bg-gray-50 dark:bg-surface-tonal-a30 rounded-full flex items-center justify-center text-gray-400 mb-2">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No banners added yet.</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Recommended aspect ratio: 4:1 (e.g., 1920x480px)</p>
                                </div>
                                @error('banners.*.image') <p class="fi-error mt-2">{{ $message }}</p> @enderror
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
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Category Active</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Visible on storefront</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <label class="fi-label">Thumbnail Image</label>
                                    <div class="aspect-square admin-upload-zone"
                                         onclick="document.getElementById('image-input').click()">
                                        <img id="image-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                        <div id="image-placeholder" class="admin-upload-placeholder">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p>Upload Thumbnail</p>
                                        </div>
                                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewMainImage(this)">
                                    </div>
                                    @error('image') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" class="admin-btn-primary">Create Category</button>
                                    <a href="{{ route('categories.index') }}" class="admin-btn-secondary">Cancel</a>
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
                    reader.onload = function(e) {
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
                row.className = 'relative rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 p-6 bg-gray-50/30 dark:bg-surface-tonal-a30/10 hover:border-gray-300 dark:hover:border-surface-tonal-a40 transition-all group animate-fade-in-up';

                row.innerHTML = `
                    <button type="button" onclick="removeBannerRow(${index})"
                            class="absolute top-4 right-4 p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-surface-tonal-a30 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <div class="md:col-span-2">
                            <label class="fi-label">Banner Image</label>
                            <div class="aspect-[16/7] admin-upload-zone"
                                 onclick="document.getElementById('banner-img-${index}').click()">
                                <img id="preview-${index}" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                <div id="placeholder-${index}" class="admin-upload-placeholder">
                                    <svg class="!h-8 !w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <p>Choose Image</p>
                                </div>
                            </div>
                            <input type="file" name="banners[${index}][image]" id="banner-img-${index}" class="hidden" onchange="previewBanner(this, ${index})" accept="image/*">
                        </div>
                        <div class="md:col-span-3 space-y-4">
                            <div>
                                <label class="fi-label">Banner Title</label>
                                <input type="text" name="banners[${index}][title]" value="${data.title || ''}"
                                       placeholder="Catchy headline..." class="fi">
                            </div>
                            <div>
                                <label class="fi-label">Description</label>
                                <textarea name="banners[${index}][description]" rows="2"
                                          placeholder="Sub-text highlighting the offer..." class="fi">${data.description || ''}</textarea>
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
                @endif
            });
        </script>
    @endpush
@endsection
