@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
@php
    $existingOptionsJson  = '[]';
    $existingVariantsJson = '[]';
    $basePrice            = 0;
    $allAttributesJson    = $allAttributes->map(function($attr) {
        return [
            'id' => $attr->id,
            'name' => $attr->name,
            'values' => $attr->values->pluck('value')->toArray()
        ];
    })->toJson();
@endphp
    <div class="admin-page animate-fade-in-up" x-data="productForm()">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; Back to Products
                </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Add New Product</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new product entry</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-product-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        Publish Product
                    </button>
                </div>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="create-product-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- LEFT COLUMN - Wider --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- General Information --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">General Information</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Product Name</label>
                                    <input type="text" name="name" id="name" x-model="formData.name" required
                                        placeholder="e.g. Puffer Jacket With Pocket Detail"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    @error('name')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Short Description</label>
                                    <textarea name="short_description" id="short_description" rows="2"
                                        placeholder="Brief summary for listings..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('short_description') }}</textarea>
                                    @error('short_description')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Description</label>
                                    <textarea name="description" id="description" rows="5"
                                        placeholder="Describe the fabric, fit, and unique features..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md h-28 resize-y">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Fabric Details</label>
                                    <textarea name="fabric_details" id="fabric_details" rows="2"
                                        placeholder="e.g. 100% Cotton, Machine washable..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('fabric_details') }}</textarea>
                                    @error('fabric_details')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Pricing and Brands --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Pricing & Brand</h2>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Base Price</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="base_price" value="{{ old('base_price', '0.00') }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                            <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Sale Price</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                            <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="brand_id" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Brand</label>
                                        <div class="relative">
                                            <select name="brand_id" id="brand_id" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                                <option value="">No Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Organization / Category --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Organization</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Category</label>
                                    <div class="relative">
                                        <select name="category_id" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Product Gallery --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Product Gallery</h2>
                                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest leading-none">Manage Media</span>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4" id="media-grid">
                                    <label class="aspect-square admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Add Media</p>
                                        <input type="file" id="image-upload-input" multiple accept="image/*" class="hidden" onchange="handleMediaSelect(this)">
                                    </label>
                                </div>
                                <input type="file" id="actual-image-input" name="images[]" multiple class="hidden">
                                @error('images') <p class="text-[10px] text-red-500 mt-2 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Options & Variants --}}
                        @include('admin.products.partials.variants-panel')

                </div>{{-- end lg:col-span-2 --}}

                    {{-- RIGHT COLUMN --}}
                    <div class="lg:col-span-1 space-y-4">


                        {{-- Search Engine Optimization --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">SEO Settings</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Meta Title</label>
                                    <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                        placeholder="SEO Title"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Meta Description</label>
                                    <textarea name="meta_description" rows="3"
                                        placeholder="Search engine description preview..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('meta_description') }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                        placeholder="keyword1, keyword2..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Canonical URL</label>
                                    <input type="url" name="canonical_url" value="{{ old('canonical_url') }}"
                                        placeholder="https://..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Fields --}}
                        <input type="hidden" name="slug" :value="slugify(formData.name)">
                        <input type="hidden" name="is_visible" value="1">
                        <input type="hidden" name="is_featured" value="0">
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const pendingFiles = [];
            const dataTransfer = new DataTransfer();

            function productForm() {
                return {
                    formData: {
                        name: '{{ old('name') }}',
                    },
                    slugify(text) {
                        return text.toString().toLowerCase()
                            .replace(/\s+/g, '-')
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                    }
                }
            }

            function handleMediaSelect(input) {
                if (input.files) {
                    const grid = document.getElementById('media-grid');
                    const uploadBtn = grid.querySelector('label');

                    Array.from(input.files).forEach(file => {
                        const reader = new FileReader();
                        
                        const wrapperId = 'img-' + Math.random().toString(36).substr(2, 9);
                        
                        dataTransfer.items.add(file);
                        document.getElementById('actual-image-input').files = dataTransfer.files;

                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.id = wrapperId;
                            wrapper.className = 'aspect-square rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden relative group animate-fade-in-scale';
                            wrapper.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                ${grid.querySelectorAll('[id^="img-"]').length === 0 ? '<div class="absolute top-2 left-2 px-2 py-0.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[8px] font-bold rounded uppercase tracking-widest pointer-events-none">Primary</div>' : ''}
                                <button type="button" onclick="removeSelectedImage('${wrapperId}', '${file.name}')" class="absolute top-2 right-2 w-6 h-6 bg-white dark:bg-surface-tonal-a30 text-red-500 rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            `;
                            grid.insertBefore(wrapper, uploadBtn);
                        };
                        reader.readAsDataURL(file);
                    });
                    
                    // Reset input so the same files can be chosen again if wiped
                    input.value = '';
                }
            }

            function removeSelectedImage(wrapperId, fileName) {
                document.getElementById(wrapperId).remove();
                
                // Remove from datatransfer
                const newDataTransfer = new DataTransfer();
                Array.from(dataTransfer.files).forEach(file => {
                    if(file.name !== fileName) {
                        newDataTransfer.items.add(file);
                    }
                });
                
                // Keep the global dataTransfer in sync
                dataTransfer.items.clear();
                Array.from(newDataTransfer.files).forEach(file => dataTransfer.items.add(file));
                
                document.getElementById('actual-image-input').files = dataTransfer.files;
            }
        </script>
    @endpush

@endsection