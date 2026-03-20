@extends('layouts.app')

@section('title', __('file.edit_product') ?? 'Edit Product')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                {{ __('file.edit_product') ?? 'Edit Product' }}: {{ $product->name }}
            </h1>
        </div>
        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
            &larr; {{ __('file.back_to_list') ?? 'Back to list' }}
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 mb-6">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Slug *</label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" required 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Base Price *</label>
                    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Brand</label>
                    <select name="brand_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">
                        <option value="">No Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categories</label>
                    <select name="categories[]" multiple class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0" size="5">
                        @php
                            $selectedCategories = old('categories', $product->categories->pluck('id')->toArray());
                        @endphp
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (is_array($selectedCategories) && in_array($category->id, $selectedCategories)) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hold CTRL to select multiple categories</p>
                </div>
                
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Short Description</label>
                    <textarea name="short_description" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">{{ old('short_description', $product->short_description) }}</textarea>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="6" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0">{{ old('description', $product->description) }}</textarea>
                </div>
                
                <div class="col-span-1 md:col-span-2 flex gap-6 mt-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $product->is_visible) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:bg-surface-tonal-a10 dark:border-gray-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Is Visible</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:bg-surface-tonal-a10 dark:border-gray-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Is Featured</span>
                    </label>
                </div>

                <div class="col-span-1 md:col-span-2 mt-4 pt-4 border-t border-gray-200 dark:border-surface-tonal-a30">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Product Images</label>
                    
                    @if($product->images->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-6">
                        @foreach($product->images as $image)
                        <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-surface-tonal-a30" id="image-container-{{ $image->id }}">
                            <img src="{{ Storage::url($image->file_path) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover">
                            
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" onclick="deleteImage({{ $product->id }}, {{ $image->id }})" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            
                            @if($image->is_primary)
                            <div class="absolute top-2 left-2 px-2 py-1 bg-indigo-600 text-white text-xs font-bold rounded">
                                Primary
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload New Images</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                         class="block w-full text-sm text-slate-500
                         file:mr-4 file:py-2 file:px-4
                         file:rounded-full file:border-0
                         file:text-sm file:font-semibold
                         file:bg-indigo-50 file:text-indigo-700
                         hover:file:bg-indigo-100 dark:text-gray-400
                         dark:file:bg-gray-800 dark:file:text-gray-300">
                     <p class="mt-1 text-xs text-gray-500">You can select multiple images to add to the existing gallery. Max 2MB each.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-surface-tonal-a30 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Variants Section -->
    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 mb-6">
        <div class="p-6 border-b border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Product Variants</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage pricing, stock, and attributes for different versions of this product.</p>
            </div>
            <a href="{{ route('products.variants.create', $product->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-surface-tonal-a30 dark:hover:bg-gray-600 text-gray-900 dark:text-primary-a0 rounded-lg text-sm font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add Variant
            </a>
        </div>
        @if($product->variants->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-surface-tonal-a10">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attributes</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($product->variants as $variant)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-primary-a0">{{ $variant->sku }}</div>
                            @if($variant->is_default)
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">Default</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($variant->attributeValues as $value)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">
                                        {{ $value->attribute->name ?? 'Attribute' }}: {{ $value->value }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500">No attributes</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-primary-a0">
                            @if($variant->sale_price)
                                <span class="line-through text-gray-500 mr-2">${{ number_format($variant->price, 2) }}</span>
                                <span class="text-green-600 dark:text-green-400">${{ number_format($variant->sale_price, 2) }}</span>
                            @else
                                ${{ number_format($variant->price, 2) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm {{ $variant->isInStock() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $variant->available_quantity }} available
                            </div>
                            @if($variant->reserved_quantity > 0)
                                <div class="text-xs text-gray-500 mt-1">{{ $variant->reserved_quantity }} reserved</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('products.variants.edit', [$product->id, $variant->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                <form action="{{ route('products.variants.destroy', [$product->id, $variant->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this variant?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center border-t border-gray-200 dark:border-surface-tonal-a30">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-primary-a0">No variants</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This product currently has no variants.</p>
        </div>
        @endif
    </div>
</div>

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
                document.getElementById(`image-container-${imageId}`).remove();
                
                // Add success toast/notification
                const container = document.createElement('div');
                container.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300';
                container.textContent = 'Image deleted successfully';
                document.body.appendChild(container);
                
                setTimeout(() => {
                    container.style.opacity = '0';
                    setTimeout(() => container.remove(), 300);
                }, 3000);
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
@endsection

