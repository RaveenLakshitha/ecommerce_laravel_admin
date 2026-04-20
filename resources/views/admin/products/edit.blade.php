@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; Back to Products
                </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Edit Product</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Updating: <span class="font-bold text-gray-900 dark:text-white">{{ $product->name }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-product-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        Save All Changes
                    </button>
                </div>
            </div>

            <form id="edit-product-form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Basic Information --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Basic Information</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="name" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Product Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                        @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="slug" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Slug</label>
                                        <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" required class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md font-mono text-xs">
                                        @error('slug') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <div>
                                        <label for="base_price" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Base Price</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                            <input type="number" step="0.01" name="base_price" id="base_price" value="{{ old('base_price', $product->base_price) }}" required class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pl-7">
                                        </div>
                                        @error('base_price') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="sale_price" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Sale Price</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                            <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pl-7">
                                        </div>
                                        @error('sale_price') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="brand_id" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Brand</label>
                                        <div class="relative">
                                            <select name="brand_id" id="brand_id" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                                <option value="">No Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('brand_id') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Content Details --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Detailed Description</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="short_description" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Short Description</label>
                                    <textarea name="short_description" id="short_description" rows="2"
                                        placeholder="Brief summary for listings…" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>
                                <div>
                                    <label for="description" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Full Story</label>
                                    <textarea name="description" id="description" rows="6"
                                        placeholder="Detailed story, features, and specs…" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md h-24 resize-y">{{ old('description', $product->description) }}</textarea>
                                </div>
                                <div>
                                    <label for="fabric_details" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Fabric Details</label>
                                    <textarea name="fabric_details" id="fabric_details" rows="2"
                                        placeholder="e.g. 100% Cotton, Machine washable..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('fabric_details', $product->fabric_details) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Organization --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Organization</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="category_id" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Category</label>
                                    <div class="relative">
                                        <select name="category_id" id="category_id" required
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Media Gallery --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Product Gallery</h2>
                                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest leading-none">Manage Media</span>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4" id="media-grid">
                                    @foreach($product->images as $image)
                                        <div class="aspect-square rounded-xl border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden relative group bg-gray-50 dark:bg-surface-tonal-a30/20" id="image-container-{{ $image->id }}">
                                            <img src="{{ Storage::url($image->file_path) }}" class="w-full h-full object-cover">
                                            @if($image->is_primary)
                                                <div class="absolute top-2 left-2 px-2 py-0.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[8px] font-bold rounded uppercase tracking-widest">Primary</div>
                                            @endif
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                                <button type="button" onclick="deleteImage({{ $product->id }}, {{ $image->id }})" class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 shadow-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <label class="aspect-square admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Add Media</p>
                                        <input type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="handleMediaSelect(this)">
                                    </label>
                                </div>
                                @error('images') <p class="text-xs text-red-500 mt-4 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Variants --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">Product Variants</h2>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Inventory Options</p>
                                </div>
                                <a href="{{ route('products.variants.create', $product->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-600/20 active:scale-[0.98]">
                                    + Add Variant
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-surface-tonal-a20 border-b border-gray-100 dark:border-surface-tonal-a30">
                                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKU</th>
                                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Attributes</th>
                                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Stock</th>
                                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Price</th>
                                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                        @forelse($product->variants as $variant)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-colors group">
                                                <td class="px-6 py-3.5">
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-bold text-gray-700 dark:text-white">{{ $variant->sku }}</span>
                                                        @if($variant->is_default)
                                                            <span class="text-[8px] font-black text-indigo-500 uppercase tracking-tighter">Default</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($variant->attributeValues as $value)
                                                            <span class="px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold border border-indigo-100 dark:border-indigo-500/20">
                                                                {{ $value->attribute->name }}: {{ $value->value }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3.5 text-center">
                                                    <span class="text-xs font-bold {{ $variant->isInStock() ? 'text-emerald-500' : 'text-red-500' }}">
                                                        {{ $variant->available_quantity }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3.5 text-right">
                                                    <div class="flex flex-col items-end">
                                                        <span class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($variant->price, 2) }}</span>
                                                        @if($variant->sale_price)
                                                            <span class="text-[10px] text-emerald-500 font-bold">${{ number_format($variant->sale_price, 2) }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <a href="{{ route('products.variants.edit', [$product->id, $variant->id]) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                        </a>
                                                        <button type="button" onclick="confirmDeleteVariant({{ $product->id }}, {{ $variant->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-10 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">No variants defined</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="xl:col-span-1 space-y-4">
                        {{-- Search Engine Optimization --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">SEO Settings</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Meta Title</label>
                                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}"
                                        placeholder="SEO Title"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Meta Description</label>
                                    <textarea name="meta_description" rows="3"
                                        placeholder="Search engine description preview..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('meta_description', $product->meta_description) }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                        placeholder="keyword1, keyword2..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Canonical URL</label>
                                    <input type="url" name="canonical_url" value="{{ old('canonical_url', $product->canonical_url) }}"
                                        placeholder="https://..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-6">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Status & Visibility</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="space-y-2">
                                    <label class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                        <div class="mt-0.5">
                                            <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $product->is_visible) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-surface-tonal-a20 transition-all cursor-pointer">
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-xs font-bold text-gray-900 dark:text-white leading-none">Store Visibility</h3>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1">Available to customers</p>
                                        </div>
                                    </label>

                                    <label class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                        <div class="mt-0.5">
                                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-surface-tonal-a20 transition-all cursor-pointer">
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-xs font-bold text-gray-900 dark:text-white leading-none">Featured Product</h3>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1">Highlight in promotions</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" form="edit-product-form" class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                                        Save All Changes
                                    </button>
                                    <a href="{{ route('products.index') }}" class="px-6 py-3 border border-gray-200 dark:border-surface-tonal-a30 text-gray-500 text-sm font-bold rounded-xl text-center hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                        Discard Changes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="delete-variant-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            function deleteImage(productId, imageId) {
                if (!confirm('Are you sure you want to delete this image?')) return;

                fetch(`/admin/products/${productId}/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`image-container-${imageId}`).classList.add('scale-90', 'opacity-0');
                        setTimeout(() => document.getElementById(`image-container-${imageId}`).remove(), 200);
                    } else {
                        alert(data.message || 'Error deleting image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the image');
                });
            }

            function handleMediaSelect(input) {
                if (input.files) {
                    const grid = document.getElementById('media-grid');
                    const uploadBtn = grid.querySelector('label');

                    Array.from(input.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'aspect-square rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden relative group animate-fade-in-scale';
                            wrapper.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                <button type="button" onclick="this.closest('div').remove()" class="absolute top-2 right-2 w-6 h-6 bg-white dark:bg-surface-tonal-a30 text-red-500 rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            `;
                            grid.insertBefore(wrapper, uploadBtn);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            document.getElementById('name').addEventListener('input', function() {
                document.getElementById('slug').value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });

            function confirmDeleteVariant(productId, variantId) {
                if (confirm('Are you sure you want to delete this variant?')) {
                    const form = document.getElementById('delete-variant-form');
                    form.action = `/admin/products/${productId}/variants/${variantId}`;
                    form.submit();
                }
            }
        </script>
    @endpush
@endsection
