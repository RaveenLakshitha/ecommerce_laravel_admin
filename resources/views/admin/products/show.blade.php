@extends('layouts.app')

@section('title', $product->name . ' - ' . __('file.product_details', [], 'en'))

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        {{-- Header Content --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('products.index') }}"
                        class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                        &larr; Back to Products
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0 flex items-center gap-3">
                    {{ $product->name }}
                    @if($product->is_visible)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">
                            Visible
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a20 dark:text-gray-300">
                            Hidden
                        </span>
                    @endif
                    @if($product->is_featured)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400">
                            Featured
                        </span>
                    @endif
                </h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('products.edit', $product->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-surface-tonal-a20 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Product
                </a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                    onsubmit="return confirm('Delete this product? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white border border-transparent rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left Column (Details & Variants) --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- General Info Card --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">General Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-primary-a0">{{ $product->slug }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Base Price</p>
                            <p class="mt-1 text-sm text-indigo-600 dark:text-indigo-400 font-bold">
                                ${{ number_format($product->base_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Brand</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-primary-a0">
                                {{ optional($product->brand)->name ?? 'No Brand assigned' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Categories</p>
                            <div class="mt-1 flex flex-wrap gap-1 mt-1">
                                @forelse($product->categories as $category)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500">Uncategorized</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Short Description</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-primary-a0">
                                {{ $product->short_description ?: 'No short description provided.' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Full Description Card --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Full Description</h3>
                    </div>
                    <div class="p-6">
                        @if($product->description)
                            <div class="prose prose-sm dark:prose-invert max-w-none">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No detailed description provided.</p>
                        @endif
                    </div>
                </div>

                {{-- Variants Card --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Product Variants</h3>
                        <a href="{{ route('products.variants.create', $product->id) }}"
                            class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:underline">
                            + Add Variant
                        </a>
                    </div>

                    @if($product->variants->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-surface-tonal-a10/50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            SKU</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Attributes</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Price / Sale</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Stock</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($product->variants as $variant)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-primary-a0">{{ $variant->sku }}
                                                </div>
                                                @if($variant->is_default)
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 mt-1 rounded text-[10px] font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400 uppercase">Default</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @forelse($variant->attributeValues as $value)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">
                                                            {{ $value->attribute->name }}: {{ $value->value }}
                                                        </span>
                                                    @empty
                                                        <span class="text-sm text-gray-500">-</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-primary-a0">
                                                @if($variant->sale_price)
                                                    <span
                                                        class="line-through text-gray-500 text-xs">${{ number_format($variant->price, 2) }}</span>
                                                    <br>
                                                    <span
                                                        class="text-green-600 dark:text-green-400 font-medium">${{ number_format($variant->sale_price, 2) }}</span>
                                                @else
                                                    ${{ number_format($variant->price, 2) }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="text-sm font-medium {{ $variant->isInStock() ? 'text-gray-900 dark:text-primary-a0' : 'text-red-500' }}">
                                                    {{ $variant->available_quantity }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            No variants have been created for this product.
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Column (Images & Metadata) --}}
            <div class="space-y-6">

                {{-- Product Images Card --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Images
                        </h3>
                        <span
                            class="text-xs font-medium bg-gray-100 dark:bg-surface-tonal-a30 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full">{{ $product->images->count() }}
                            Total</span>
                    </div>
                    <div class="p-6">
                        @if($product->images->count() > 0)
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($product->images as $image)
                                    <div
                                        class="relative rounded-lg overflow-hidden border border-gray-200 dark:border-surface-tonal-a30 aspect-square group">
                                        <img src="{{ Storage::url($image->path) }}" alt="Product Image"
                                            class="w-full h-full object-cover">
                                        @if($image->is_primary)
                                            <div
                                                class="absolute top-2 left-2 bg-indigo-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                                                Main</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-6 text-gray-400 dark:text-gray-500">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-sm">No images uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Metadata Card --}}
                <div class="bg-gray-50 dark:bg-surface-tonal-a10/50 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 p-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-primary-a0 uppercase tracking-wider mb-4">Meta Data
                    </h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Created At</span>
                            <span
                                class="text-gray-900 dark:text-primary-a0 font-medium">{{ $product->created_at->format('M d, Y h:i A') }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Last Updated</span>
                            <span
                                class="text-gray-900 dark:text-primary-a0 font-medium">{{ $product->updated_at->format('M d, Y h:i A') }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Total Variants</span>
                            <span class="text-gray-900 dark:text-primary-a0 font-medium">{{ $product->variants->count() }}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endsection
