<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-4">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-100/50 flex flex-wrap justify-between items-center gap-4">
        <div class="flex items-center gap-6">
            <div>
                <span
                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">{{ __('file.order_placed') }}</span>
                <span
                    class="text-sm font-medium text-gray-900">{{ $order->placed_at ? $order->placed_at->format('M d, Y') : $order->created_at->format('M d, Y') }}</span>
            </div>
            <div>
                <span
                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">{{ __('file.total') }}</span>
                <span class="text-sm font-medium text-gray-900">@price($order->total_amount, $order->currency)</span>
            </div>
            <div>
                <span
                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">{{ __('file.order_num') }}</span>
                <span class="text-sm font-medium text-gray-900">{{ $order->order_number }}</span>
            </div>
        </div>
        <div>
            <a href="{{ route('account.orders.show', $order->id) }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                {{ __('file.view_details') }}
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="flex justify-between items-start">
            <div class="flex-1 pr-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if(strtolower($order->status) == 'delivered') bg-green-100 text-green-800 
                        @elseif(in_array(strtolower($order->status), ['cancelled', 'failed'])) bg-red-100 text-red-800 
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ __('file.' . strtolower($order->status)) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        @if(strtolower($order->status) == 'delivered')
                            {{ __('file.delivered_on') }} {{ $order->updated_at->format('M d, Y') }}
                        @else
                            {{ __('file.estimated_delivery') }}: {{ $order->created_at->addDays(5)->format('M d, Y') }}
                        @endif
                    </span>
                </div>

                <div class="flex flex-wrap gap-4">
                    @foreach($order->items->take(4) as $item)
                        <div class="relative group">
                            @if($item->variant && $item->variant->product && $item->variant->product->primaryImage)
                                <img src="{{ asset('storage/' . $item->variant->product->primaryImage->file_path) }}"
                                    alt="{{ $item->product_name_snapshot }}"
                                    class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            @else
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center text-xs text-gray-400">
                                    {{ __('file.no_image') }}</div>
                            @endif
                        </div>
                    @endforeach
                    @if($order->items->count() > 4)
                        <div
                            class="w-16 h-16 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center text-sm font-medium text-gray-600">
                            +{{ $order->items->count() - 4 }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="hidden sm:block">
                <a href="{{ route('account.orders.show', $order->id) }}"
                    class="text-sm font-medium text-primary-600 hover:text-primary-700 hover:underline">
                    {{ __('file.track_package') }}
                </a>
            </div>
        </div>
    </div>
</div>