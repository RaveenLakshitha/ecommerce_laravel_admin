@extends('layouts.app')

@section('title', $product->name . ' - ' . __('file.details'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_products') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $product->name }}</h1>
                        <div class="flex gap-2">
                            @if($product->is_visible)
                                <span class="px-3 py-1 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">{{ __('file.visible') }}</span>
                            @else
                                <span class="px-3 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 dark:bg-surface-tonal-a30 dark:text-gray-400 border border-gray-200 dark:border-surface-tonal-a30">{{ __('file.hidden') }}</span>
                            @endif
                            @if($product->is_featured)
                                <span class="px-3 py-1 rounded-lg text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">{{ __('file.featured') }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.product_details') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('products.edit', $product->id) }}"
                        class="px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.edit_product') }}
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('{{ __('file.confirm_delete_product_permanently') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-lg text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all shadow-sm active:scale-[0.98]">
                            {{ __('file.delete') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- General Information --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.product_overview') }}</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-8">
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.base_price') }}</p>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">@price($product->base_price)</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.sale_price') }}</p>
                                    <p class="text-xl font-bold {{ $product->sale_price ? 'text-emerald-500' : 'text-gray-900 dark:text-white' }}">{{ $product->sale_price ? '$' . number_format($product->sale_price, 2) : __('file.none') ?? 'None' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.brand') }}</p>
                                    <p class="text-sm font-bold text-gray-700 dark:text-white">{{ $product->brand->name ?? __('file.none') ?? 'None' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.slug') }}</p>
                                    <p class="text-xs font-mono text-gray-500 dark:text-gray-400 truncate">{{ $product->slug }}</p>
                                </div>
                                <div class="sm:col-span-2 space-y-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.category') }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @if($product->category)
                                            <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 text-gray-700 dark:text-gray-300 text-xs font-medium">{{ $product->category->name }}</span>
                                        @else
                                            <span class="text-sm text-gray-400 font-medium italic">No category</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Content Details --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.detailed_description') }}</h2>
                        </div>
                        <div class="p-4 space-y-6">
                            @if($product->short_description)
                                <div class="space-y-1.5">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.short_description') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->short_description }}</p>
                                </div>
                            @endif

                            <div class="space-y-1.5">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.full_story') }}</h3>
                                @if($product->description)
                                    <div class="prose prose-indigo dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 font-medium italic">{{ __('file.no_detailed_description_provided') }}</p>
                                @endif
                            </div>

                            @if($product->fabric_details)
                                <div class="space-y-1.5">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.fabric_details') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->fabric_details }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Variants Table --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.product_variants') }}</h2>
                            <span class="text-xs font-medium text-indigo-500">{{ $product->variants->count() }} {{ __('file.product_variants') }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-surface-tonal-a20 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 min-w-[60px]">{{ __('file.image') }}</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.sku') }}</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.attributes') }}</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">{{ __('file.stock') }}</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-right">{{ __('file.price') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($product->variants as $variant)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-colors">
                                            <td class="px-6 py-3.5">
                                                @php $vImage = $variant->images->where('is_primary', true)->first() ?? $variant->images->first(); @endphp
                                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a30/20">
                                                    @if($vImage)
                                                        <img src="{{ Storage::url($vImage->file_path) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
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
                                                        <span class="px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-medium border border-indigo-100 dark:border-indigo-500/20">
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
                                                    <span class="text-sm font-bold text-gray-900 dark:text-white">@price($variant->price)</span>
                                                    @if($variant->sale_price)
                                                        <span class="text-[10px] text-emerald-500 font-bold">@price($variant->sale_price)</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-xs font-medium text-gray-500 uppercase tracking-widest">{{ __('file.no_variants_defined') }}</td>
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
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.seo_settings') }}</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.meta_title') }}</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-white">{{ $product->meta_title ?: 'None' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.meta_description') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->meta_description ?: 'None' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.meta_keywords') }}</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-white">{{ $product->meta_keywords ?: 'None' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.canonical_url') }}</p>
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
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.product_gallery') }}</h2>
                        </div>
                        <div class="p-4">
                            @php $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                            
                            @if($primary)
                                <div class="aspect-square rounded-xl border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden mb-3 bg-gray-50 dark:bg-surface-tonal-a30/20 relative">
                                    <img src="{{ Storage::url($primary->file_path) }}" class="w-full h-full object-contain p-2">
                                    <div class="absolute top-2 left-2 px-2 py-1 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[10px] font-medium rounded-lg uppercase tracking-wider">Primary</div>
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
                                <div class="aspect-square rounded-xl border-2 border-dashed border-gray-100 dark:border-surface-tonal-a30 flex flex-col items-center justify-center gap-2 text-gray-400 p-8 text-center bg-gray-100/50 dark:bg-surface-tonal-a30/20">
                                    <svg class="h-8 w-8 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">No images available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Inventory Stats --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden relative group p-4 transition-all">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">{{ __('file.inventory_summary') }}</h3>
                        <div class="space-y-3 relative z-10">
                            <div class="flex items-end justify-between">
                                <span class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('file.total_stock') }}</span>
                                <span class="text-xl font-black text-gray-900 dark:text-white">{{ $product->variants->sum('available_quantity') }}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-surface-tonal-a30 h-1 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full transition-all duration-1000" style="width: {{ $product->variants->sum('available_quantity') > 0 ? '100%' : '0%' }}"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('file.active_variants') }}</span>
                                <span class="text-xs font-black text-gray-900 dark:text-white">{{ $product->variants->count() }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- Barcode & Label Management --}}
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ __('file.barcodes_and_labels') }}</h2>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" id="select-all-labels" checked
                                        class="w-4 h-4 rounded border-gray-300 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer bg-white dark:bg-surface-tonal-a20">
                                    <span class="text-sm font-medium text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors">{{ __('file.select_all') }}</span>
                                </label>
                            </div>
                            <button onclick="printLabels()" class="text-sm font-bold text-emerald-600 dark:text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-400 flex items-center gap-1.5 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                {{ __('file.print_labels') }}
                            </button>
                        </div>
                        <div class="p-4 space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar">
                            @foreach($product->variants as $variant)
                                <label class="block p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-100/50 dark:bg-surface-tonal-a10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all cursor-pointer group relative">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <input type="checkbox" name="selected_labels[]" value="{{ $variant->id }}" checked
                                                class="variant-label-checkbox w-4 h-4 rounded border-gray-300 dark:border-white/10 text-gray-900 dark:text-white focus:ring-gray-500 transition-all cursor-pointer bg-white dark:bg-surface-tonal-a20">
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-base font-bold text-gray-900 dark:text-white leading-none">{{ $variant->sku }}</span>
                                                <span class="text-base font-bold text-gray-900 dark:text-white">@price($variant->price ?? 0)</span>
                                            </div>
                                            <div class="mb-1.5">
                                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                                    {{ $variant->attributeValues->map(fn($av) => $av->attribute->name . ': ' . $av->value)->join(', ') }}
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-gray-400 font-mono tracking-wider">{{ $variant->barcode }}</span>
                                                <span class="text-[10px] font-medium text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">Select for Print</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
        <script>
            function printLabels() {
                const selectedIds = Array.from(document.querySelectorAll('.variant-label-checkbox:checked')).map(cb => cb.value);
                if (selectedIds.length === 0) {
                    alert("{{ __('file.please_select_at_least_one_variant') }}");
                    return;
                }

                const printWindow = window.open('', '_blank');
                const product = @json($product);
                const allVariants = @json($product->variants);
                const selectedVariants = allVariants.filter(v => selectedIds.includes(v.id.toString()));
                
                let labelsHtml = '';
                selectedVariants.forEach(v => {
                    const price = v.price ? parseFloat(v.price).toFixed(2) : '0.00';
                    const barcodeVal = v.barcode || '';
                    
                    // Collect attributes (e.g. Size: L, Color: Red)
                    let attrText = '';
                    if (v.attribute_values && v.attribute_values.length > 0) {
                        attrText = v.attribute_values.map(av => {
                            const attrName = av.attribute ? av.attribute.name : '';
                            return `${attrName}: ${av.value}`;
                        }).join(', ');
                    }

                    labelsHtml += `
                        <div class="label-item">
                            <div class="product-name">${product.name}</div>
                            <div class="variant-sku">${v.sku}</div>
                            <div class="attributes">${attrText}</div>
                            <div class="barcode-container">
                                <svg id="print-barcode-${v.id}"></svg>
                            </div>
                            <div class="barcode-text font-mono">${barcodeVal}</div>
                            <div class="price">$${price}</div>
                        </div>
                    `;
                });

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Print Labels - ${product.name}</title>
                            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"><\/script>
                            <style>
                                @page { margin: 0; }
                                body { font-family: 'Inter', sans-serif; margin: 20px; color: #000; }
                                .labels-grid { 
                                    display: grid; 
                                    grid-template-columns: repeat(3, 1fr); 
                                    gap: 15px; 
                                }
                                .label-item { 
                                    border: 1px solid #eee; 
                                    padding: 10px; 
                                    text-align: center;
                                    page-break-inside: avoid;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                                    min-height: 180px;
                                }
                                .product-name { font-size: 10px; font-weight: 800; text-transform: uppercase; margin-bottom: 2px; }
                                .variant-sku { font-size: 8px; color: #666; margin-bottom: 2px; }
                                .attributes { font-size: 8px; font-weight: 600; color: #444; margin-bottom: 5px; }
                                .barcode-container { margin: 5px 0; }
                                .barcode-container svg { width: 100%; height: 50px; }
                                .barcode-text { font-size: 8px; margin-bottom: 5px; letter-spacing: 2px; }
                                .price { font-size: 12px; font-weight: 900; }
                                @media print {
                                    .btn-print { display: none; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="labels-grid">
                                ${labelsHtml}
                            </div>
                            <script>
                                window.onload = () => {
                                    ${selectedVariants.map(v => `
                                        JsBarcode("#print-barcode-${v.id}", "${v.barcode}", {
                                            format: "CODE128",
                                            width: 1.5,
                                            height: 40,
                                            displayValue: false
                                        });
                                    `).join('\n')}
                                    setTimeout(() => {
                                        window.print();
                                        // window.close();
                                    }, 500);
                                };
                            <\/script>
                        </body>
                    </html>
                `);
                printWindow.document.close();
            }

            document.getElementById('select-all-labels')?.addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.variant-label-checkbox').forEach(cb => {
                    cb.checked = checked;
                });
            });
        </script>
    @endpush
@endsection
