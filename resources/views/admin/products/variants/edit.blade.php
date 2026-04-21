@extends('layouts.app')

@section('title', __('file.edit_variant') ?? 'Edit Variant')

@section('content')
<div class="admin-page animate-fade-in-up">
    <div class="admin-page-inner">

        {{-- Breadcrumbs --}}
        <div class="mb-4 mt-10">
            <a href="{{ route('products.edit', $product->id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                &larr; Back to Product
            </a>
        </div>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Edit Variant: {{ $variant->sku }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Updating variant for product <span class="font-bold text-gray-900 dark:text-white">{{ $product->name }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" form="edit-variant-form"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                    Save Variant Changes
                </button>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-xl">
            <ul class="list-disc pl-5 space-y-1 text-sm text-red-600 dark:text-red-400 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('products.variants.update', [$product->id, $variant->id]) }}" method="POST" id="edit-variant-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @php
                $currentAttributeValues = $variant->attributeValues->pluck('id')->toArray();
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                
                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-4">
                    
                    {{-- Attributes --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Attributes</h2>
                        </div>
                        <div class="p-4">
                            @if($attributes->isEmpty())
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">No attributes configured. <a href="{{ route('attributes.create') }}" class="text-indigo-600 dark:text-indigo-400 underline ml-1">Create one</a></p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    @foreach($attributes as $attribute)
                                        @if($attribute->values->count() > 0)
                                        <div>
                                            <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ $attribute->name }}</label>
                                            <select name="attribute_values[]" 
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                                <option value="">-- None --</option>
                                                @foreach($attribute->values as $val)
                                                    <option value="{{ $val->id }}" {{ (is_array(old('attribute_values')) ? in_array($val->id, old('attribute_values')) : in_array($val->id, $currentAttributeValues)) ? 'selected' : '' }}>
                                                        {{ $val->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Pricing & Inventory --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Pricing & Inventory</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="sku" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">SKU <span class="text-red-500">*</span></label>
                                    <input type="text" name="sku" id="sku" value="{{ old('sku', $variant->sku) }}" required 
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                                <div>
                                    <label for="barcode" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Barcode</label>
                                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $variant->barcode) }}" 
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                                <div>
                                    <label for="price" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Price <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $variant->price) }}" required 
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="sale_price" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Sale Price</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price', $variant->sale_price) }}" 
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md pr-7">
                                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 font-black text-xs pointer-events-none">$</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="stock_quantity" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $variant->stock_quantity) }}" required 
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                                <div>
                                    <label for="low_stock_threshold" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Low Stock Threshold</label>
                                    <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $variant->low_stock_threshold) }}" 
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="lg:col-span-1 space-y-4">
                    
                    {{-- Media --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Variant Media</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-3" id="variant-media-grid">
                                @foreach($variant->images as $image)
                                    <div class="aspect-square rounded-xl border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden relative group bg-gray-50 dark:bg-surface-tonal-a30/20" id="image-container-{{ $image->id }}">
                                        <img src="{{ Storage::url($image->file_path) }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                            <button type="button" onclick="deleteVariantImage({{ $product->id }}, {{ $variant->id }}, {{ $image->id }})" class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 shadow-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach

                                <label class="aspect-square admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Add Media</p>
                                    <input type="file" name="variant_images[]" multiple accept="image/*" class="hidden" onchange="handleMediaSelect(this)">
                                </label>
                            </div>
                            @error('variant_images') <p class="text-[10px] text-red-500 mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    {{-- Shipping & Settings --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Shipping & Settings</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <label for="weight_grams" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Weight (grams)</label>
                                <input type="number" name="weight_grams" id="weight_grams" value="{{ old('weight_grams', $variant->weight_grams) }}" 
                                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                            </div>
                            <div>
                                <label for="dimensions" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">Dimensions (L x W x H)</label>
                                <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $variant->dimensions) }}" placeholder="e.g. 10x20x5 cm" 
                                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                            </div>
                            <div class="pt-2">
                                <label class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="is_default" value="1" {{ old('is_default', $variant->is_default) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-surface-tonal-a20 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-xs font-bold text-gray-900 dark:text-white leading-none">Default Variant</h3>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1">Shown first to customers</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex flex-col gap-3">
                        <button type="submit" form="edit-variant-form" 
                            class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                            Save Variant Changes
                        </button>
                        <a href="{{ route('products.edit', $product->id) }}" 
                            class="px-6 py-3 border border-gray-200 dark:border-surface-tonal-a30 text-gray-500 text-sm font-bold rounded-xl text-center hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const dataTransfer = new DataTransfer();

    function handleMediaSelect(input) {
        if (input.files) {
            const grid = document.getElementById('variant-media-grid');
            const uploadBtn = grid.querySelector('label');

            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                
                const wrapperId = 'v-img-' + Math.random().toString(36).substr(2, 9);
                
                dataTransfer.items.add(file);
                // We'll update the actual hidden input or keep this one updated
                // Actually, we can use the same input name or a new one
                // The backend expects variant_images[]
                
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.id = wrapperId;
                    wrapper.className = 'aspect-square rounded-xl border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden relative group animate-fade-in-scale';
                    wrapper.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeNewMedia('${wrapperId}', '${file.name}')" class="absolute top-2 right-2 w-6 h-6 bg-white dark:bg-surface-tonal-a30 text-red-500 rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    `;
                    grid.insertBefore(wrapper, uploadBtn);
                };
                reader.readAsDataURL(file);
            });
            
            // Sync the input files with our dataTransfer
            input.files = dataTransfer.files;
        }
    }

    function removeNewMedia(wrapperId, fileName) {
        document.getElementById(wrapperId).remove();
        
        const newDataTransfer = new DataTransfer();
        Array.from(dataTransfer.files).forEach(file => {
            if (file.name !== fileName) {
                newDataTransfer.items.add(file);
            }
        });
        
        dataTransfer.items.clear();
        Array.from(newDataTransfer.files).forEach(file => dataTransfer.items.add(file));
        
        document.querySelector('input[name="variant_images[]"]').files = dataTransfer.files;
    }

    function deleteVariantImage(productId, variantId, imageId) {
        if (!confirm('Are you sure you want to remove this image from the variant?')) return;

        fetch(`/admin/products/${productId}/variants/${variantId}/images/${imageId}`, {
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
</script>
@endpush
