@extends('layouts.app')

@section('title', $product->name . ' - Details')

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; Back to Products
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $product->name }}</h1>
                        <div class="flex gap-2">
                            @if($product->is_visible)
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 uppercase tracking-widest border border-emerald-200 dark:border-emerald-500/20">Visible</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black bg-gray-100 text-gray-600 dark:bg-surface-tonal-a30 dark:text-gray-400 uppercase tracking-widest border border-gray-200 dark:border-surface-tonal-a30">Hidden</span>
                            @endif
                            @if($product->is_featured)
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 uppercase tracking-widest border border-amber-200 dark:border-amber-500/20">Featured</span>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Product Details</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('products.edit', $product->id) }}"
                        class="px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-[0.98]">
                        Edit Product
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-lg text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all shadow-sm active:scale-[0.98]">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- General Information --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Product Overview</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-8">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Base Price</p>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">${{ number_format($product->base_price, 2) }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sale Price</p>
                                    <p class="text-xl font-bold {{ $product->sale_price ? 'text-emerald-500' : 'text-gray-900 dark:text-white' }}">{{ $product->sale_price ? '$' . number_format($product->sale_price, 2) : 'None' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Brand</p>
                                    <p class="text-sm font-bold text-gray-700 dark:text-white">{{ $product->brand->name ?? 'None' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Slug</p>
                                    <p class="text-xs font-mono text-gray-500 dark:text-gray-400 truncate">{{ $product->slug }}</p>
                                </div>
                                <div class="sm:col-span-2 space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Categories</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @forelse($product->categories as $category)
                                            <span class="px-2 py-0.5 rounded-md bg-gray-100 dark:bg-surface-tonal-a30 text-gray-600 dark:text-gray-400 text-[10px] font-bold">{{ $category->name }}</span>
                                        @empty
                                            <span class="text-[10px] text-gray-400 font-medium italic">No categories</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Content Details --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Detailed Description</h2>
                        </div>
                        <div class="p-4 space-y-6">
                            @if($product->short_description)
                                <div class="space-y-1.5">
                                    <h3 class="text-[10px] font-black text-black dark:text-white uppercase tracking-widest">Short Description</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->short_description }}</p>
                                </div>
                            @endif

                            <div class="space-y-1.5">
                                <h3 class="text-[10px] font-black text-black dark:text-white uppercase tracking-widest">Full Story</h3>
                                @if($product->description)
                                    <div class="prose prose-indigo dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 font-medium italic">No detailed description provided.</p>
                                @endif
                            </div>

                            @if($product->fabric_details)
                                <div class="space-y-1.5">
                                    <h3 class="text-[10px] font-black text-black dark:text-white uppercase tracking-widest">Fabric Details</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->fabric_details }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Variants Table --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Product Variants</h2>
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $product->variants->count() }} Variants</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-surface-tonal-a20 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKU</th>
                                        <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Attributes</th>
                                        <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Stock</th>
                                        <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($product->variants as $variant)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-colors">
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-xs font-medium text-gray-500 uppercase tracking-widest">No variants defined</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-span-1 space-y-4">
                    
                    {{-- Search Engine Optimization --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">SEO Data</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Meta Title</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-white">{{ $product->meta_title ?: 'None' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Meta Description</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->meta_description ?: 'None' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Meta Keywords</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-white">{{ $product->meta_keywords ?: 'None' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Canonical URL</p>
                                @if($product->canonical_url)
                                    <a href="{{ $product->canonical_url }}" target="_blank" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline truncate block">{{ $product->canonical_url }}</a>
                                @else
                                    <p class="text-sm font-bold text-gray-700 dark:text-white">None</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Product Images --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Product Gallery</h2>
                        </div>
                        <div class="p-4">
                            @php $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                            
                            @if($primary)
                                <div class="aspect-square rounded-xl border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden mb-3 bg-gray-50 dark:bg-surface-tonal-a30/20 relative">
                                    <img src="{{ Storage::url($primary->file_path) }}" class="w-full h-full object-contain p-2">
                                    <div class="absolute top-2 left-2 px-2 py-0.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[8px] font-bold rounded uppercase tracking-widest">Primary</div>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($product->images as $image)
                                        @if($image->id !== $primary->id)
                                            <div class="aspect-square rounded-lg border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden bg-gray-50 dark:bg-surface-tonal-a30/20">
                                                <img src="{{ Storage::url($image->file_path) }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="aspect-square rounded-xl border-2 border-dashed border-gray-100 dark:border-surface-tonal-a30 flex flex-col items-center justify-center gap-2 text-gray-400 p-8 text-center bg-gray-50/50 dark:bg-surface-tonal-a30/20">
                                    <svg class="h-8 w-8 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">No images available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Inventory Stats --}}
                    <div class="bg-indigo-600 rounded-lg shadow-sm border border-indigo-500 overflow-hidden relative group p-4 text-white hover:bg-indigo-700 transition-colors">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-3">Inventory Summary</h3>
                        <div class="space-y-3 relative z-10">
                            <div class="flex items-end justify-between">
                                <span class="text-sm font-bold opacity-80">Total Stock</span>
                                <span class="text-xl font-black">{{ $product->variants->sum('available_quantity') }}</span>
                            </div>
                            <div class="w-full bg-white/20 h-1 rounded-full overflow-hidden">
                                <div class="bg-white h-full transition-all duration-1000" style="width: {{ $product->variants->sum('available_quantity') > 0 ? '100%' : '0%' }}"></div>
                            </div>
                            <div class="flex items-center justify-between opacity-80">
                                <span class="text-[10px] font-bold uppercase tracking-widest">Active Variants</span>
                                <span class="text-xs font-black">{{ $product->variants->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
