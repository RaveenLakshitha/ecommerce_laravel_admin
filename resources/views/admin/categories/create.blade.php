@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
    <div class="admin-page">
        <div class="admin-page-inner">

            {{-- Header --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('categories.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; Back to Categories
                </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 text-white">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Add New Category</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new organizational tier for your storefront products.</p>
                </div>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Basic Information --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Basic Information</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Category Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                        placeholder="e.g. Surgical Instruments"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="parent_id" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Parent Category</label>
                                    <select name="parent_id" id="parent_id" 
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md cursor-pointer">
                                        <option value="">None (Top Level)</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Description</label>
                                    <textarea name="description" id="description" rows="4" 
                                        placeholder="Brief description of this category..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('description') }}</textarea>
                                    @error('description') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Banners --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Category Banners</h2>
                                <button type="button" onclick="addBannerRow()" 
                                    class="py-1 px-3 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-black tracking-widest uppercase rounded-lg transition-all active:scale-[0.98]">
                                    + Add Banner
                                </button>
                            </div>
                            <div class="p-4">
                                <div id="banners-container" class="space-y-4"></div>
                                <div id="no-banners-msg" class="py-12 text-center border-2 border-dashed border-gray-100 dark:border-surface-tonal-a30 rounded-2xl bg-gray-50/10">
                                    <div class="mx-auto w-12 h-12 bg-gray-100 dark:bg-surface-tonal-a30 rounded-full flex items-center justify-center text-gray-400 mb-2">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">No banners added yet.</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 uppercase tracking-widest">Recommended: 1920x480px (4:1)</p>
                                </div>
                                @error('banners.*.image') <p class="text-[10px] text-red-500 mt-2 font-bold px-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Status & Visibility</h2>
                            </div>
                            <div class="p-4 space-y-6">
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a30/10 cursor-pointer hover:bg-gray-100/50 dark:hover:bg-surface-tonal-a30/20 transition-all">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                        <div>
                                            <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Category Active</h3>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-0.5">Visible on storefront</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Thumbnail Image</label>
                                    <div class="aspect-square admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all overflow-hidden relative"
                                         onclick="document.getElementById('image-input').click()">
                                        <img id="image-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                        <div id="image-placeholder" class="flex flex-col items-center justify-center gap-1">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Thumbnail</p>
                                        </div>
                                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewMainImage(this)">
                                    </div>
                                    @error('image') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                                        Create Category
                                    </button>
                                    <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95 text-center">
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
                row.className = 'relative rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 p-6 bg-gray-50/10 dark:bg-surface-tonal-a30/5 hover:border-indigo-200 dark:hover:border-indigo-500/20 transition-all group animate-fade-in-up';

                row.innerHTML = `
                    <button type="button" onclick="removeBannerRow(${index})"
                            class="absolute top-4 right-4 p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Banner Image</label>
                            <div class="aspect-[16/7] admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all overflow-hidden relative"
                                 onclick="document.getElementById('banner-img-${index}').click()">
                                <img id="preview-${index}" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                <div id="placeholder-${index}" class="flex flex-col items-center justify-center gap-1">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Choose Image</p>
                                </div>
                            </div>
                            <input type="file" name="banners[${index}][image]" id="banner-img-${index}" class="hidden" onchange="previewBanner(this, ${index})" accept="image/*">
                        </div>
                        <div class="md:col-span-3 space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Banner Title</label>
                                <input type="text" name="banners[${index}][title]" value="${data.title || ''}" placeholder="Catchy headline..." 
                                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-white dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Description</label>
                                <textarea name="banners[${index}][description]" rows="2" placeholder="Sub-text highlighting the offer..." 
                                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-white dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">${data.description || ''}</textarea>
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
