<div class="group premium-card !bg-white border-none hover:shadow-2xl transition-all duration-500">
    {{-- Card Header --}}
    <div class="px-6 py-4 bg-gray-50/30 flex flex-wrap justify-between items-center gap-4 border-b border-gray-100">
        <div class="flex items-center gap-8">
            <div>
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('file.order_placed') }}</span>
                <span class="text-xs font-black text-gray-900 font-display">{{ $order->created_at->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('file.total') }}</span>
                <span class="text-xs font-black text-gold font-display">@price($order->grand_total ?? $order->total_amount)</span>
            </div>
            <div>
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('file.order_num') }}</span>
                <span class="text-xs font-black text-gray-900 font-display">#{{ $order->id }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="acc-badge
                @if(strtolower($order->status) == 'delivered') acc-badge--green
                @elseif(strtolower($order->status) == 'cancelled') acc-badge--red
                @else acc-badge--amber @endif">
                {{ $order->status }}
            </span>
            <a href="{{ route('account.orders.show', $order->id) }}" class="p-2 bg-white rounded-lg border border-gray-100 hover:border-gold hover:text-gold transition-all shadow-sm">
                <i data-feather="external-link" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="p-6">
        <div class="flex items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="flex -space-x-3 overflow-hidden">
                    @foreach($order->items->take(4) as $item)
                        @php
                            $product = $item->variant ? $item->variant->product : ($item->product ?? null);
                            $image = $product && $product->primaryImage ? $product->primaryImage->url : 'https://placehold.co/400?text=No+Image';
                        @endphp
                        <div class="inline-block h-14 w-14 rounded-xl ring-4 ring-white overflow-hidden bg-white border border-gray-100 shadow-sm transition-transform group-hover:scale-105" style="transition-delay: {{ $loop->index * 50 }}ms">
                            <img src="{{ $image }}" alt="{{ $item->product_name_snapshot }}" class="h-full w-full object-cover">
                        </div>
                    @endforeach
                    @if($order->items->count() > 4)
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl ring-4 ring-white bg-gray-50 border border-gray-100 text-[10px] font-black text-gray-400 shadow-sm">
                            +{{ $order->items->count() - 4 }}
                        </div>
                    @endif
                </div>
                
                <div class="hidden sm:block ml-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                        {{ $order->items->count() }} {{ trans_choice('file.items', $order->items->count()) }}
                    </p>
                    <p class="text-xs font-bold text-gray-900 truncate max-w-[200px]">
                        {{ $order->items->first()->product_name_snapshot }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col items-end gap-2">
                <a href="{{ route('account.orders.show', $order->id) }}" class="btn-gold !py-2 !px-6 !text-[9px]">
                    {{ __('file.view_details') }}
                </a>
                @if(strtolower($order->status) === 'delivered' && $order->items->first() && $order->items->first()->variant && $order->items->first()->variant->product)
                    <a href="{{ route('frontend.products.show', $order->items->first()->variant->product->slug) }}#write-review" class="text-[9px] font-bold text-gray-400 hover:text-gold uppercase tracking-[0.1em] transition-colors">
                        {{ __('file.write_review') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
