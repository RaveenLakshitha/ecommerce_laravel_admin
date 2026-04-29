@extends('frontend.layouts.app')

@section('title', __('file.order_details') . ' - #' . $order->order_number)
@section('body_class', 'light-page')

@section('content')
<div style="background-color: var(--bg-creamy); color: #1a1a1a;" class="min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumbs & Header --}}
        <div class="mb-8">
            <nav class="flex text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('account.dashboard') }}" class="hover:text-primary-600 transition-colors">{{ __('file.my_account') }}</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i data-feather="chevron-right" class="w-4 h-4 mx-1"></i>
                            <a href="{{ route('account.dashboard', ['tab' => 'orders']) }}" class="hover:text-primary-600 transition-colors">{{ __('file.orders') }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center text-gray-900 font-medium">
                            <i data-feather="chevron-right" class="w-4 h-4 mx-1 text-gray-500"></i>
                            {{ __('file.order_num') }}{{ $order->order_number }}
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('file.order_num') }}{{ $order->order_number }}</h1>
                    <p class="text-gray-500 mt-1">{{ __('file.placed_on') }} {{ $order->placed_at ? $order->placed_at->format('F j, Y \a\t g:i A') : $order->created_at->format('F j, Y') }}</p>
                </div>
                <div>
                    <a href="#" onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i data-feather="printer" class="w-4 h-4 mr-2"></i> {{ __('file.print_receipt') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Items and Tracking --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Status & Tracking --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-900">{{ __('file.order_status') }}</h2>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if(strtolower($order->status) == 'delivered') bg-green-100 text-green-800 
                                @elseif(in_array(strtolower($order->status), ['cancelled', 'failed'])) bg-red-100 text-red-800 
                                @elseif(strtolower($order->status) == 'processing') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ __('file.' . strtolower($order->status)) }}
                            </span>
                        </div>

                        {{-- Simple Progress Bar --}}
                        <div class="relative">
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-100">
                                @php
                                    $progress = 25; // Pending
                                    if(strtolower($order->status) == 'processing') $progress = 50;
                                    if(strtolower($order->status) == 'shipped') $progress = 75;
                                    if(strtolower($order->status) == 'delivered') $progress = 100;
                                    if(in_array(strtolower($order->status), ['cancelled', 'failed'])) $progress = 0;
                                @endphp
                                <div style="width: {{ $progress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $progress == 0 ? 'bg-red-500' : 'bg-primary-500' }}"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 font-medium">
                                <span>{{ __('file.order_placed') }}</span>
                                <span>{{ __('file.processing') }}</span>
                                <span>{{ __('file.shipped') }}</span>
                                <span>{{ __('file.delivered') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('file.items_ordered') }}</h2>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <li class="p-6 flex flex-col sm:flex-row gap-6">
                            <div class="flex-shrink-0 w-24 h-24 bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                @if($item->variant && $item->variant->product && $item->variant->product->primaryImage)
                                    <img src="{{ asset('storage/' . $item->variant->product->primaryImage->file_path) }}" alt="{{ $item->product_name_snapshot }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">{{ __('file.no_image') }}</div>
                                @endif
                            </div>
                            
                            <div class="flex-1 flex flex-col">
                                <div class="flex justify-between">
                                    <div>
                                        <h4 class="text-base font-bold text-gray-900">
                                            @if($item->variant && $item->variant->product)
                                                <a href="{{ route('frontend.products.show', $item->variant->product->slug) }}" class="hover:text-primary-600 transition-colors">{{ $item->product_name_snapshot }}</a>
                                            @else
                                                {{ $item->product_name_snapshot }}
                                            @endif
                                        </h4>
                                        <div class="mt-1 flex flex-wrap gap-2 text-sm text-gray-500">
                                            @if(is_array($item->variant_attributes))
                                                @foreach($item->variant_attributes as $key => $val)
                                                    @if($key != 'image' && $key != 'slug')
                                                        <span class="bg-gray-100 px-2 py-0.5 rounded">{{ ucfirst($key) }}: {{ $val }}</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-base font-bold text-gray-900">@price($item->unit_price, $order->currency)</p>
                                </div>
                                <div class="mt-auto pt-4 flex justify-between items-end">
                                    <p class="text-sm text-gray-600">{{ __('file.qty') }}: {{ $item->quantity }}</p>
                                    <p class="text-sm font-medium text-gray-900">{{ __('file.subtotal') }}: @price($item->total, $order->currency)</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </div>

            {{-- Right Column: Summary & Details --}}
            <div class="space-y-8">
                
                {{-- Order Summary --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('file.order_summary') }}</h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <dt>{{ __('file.subtotal') }}</dt>
                                <dd class="font-medium text-gray-900">@price($order->subtotal, $order->currency)</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>{{ __('file.shipping') }}</dt>
                                <dd class="font-medium text-gray-900">@price($order->shipping_amount, $order->currency)</dd>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="flex justify-between text-red-600">
                                <dt>{{ __('file.discount') }}</dt>
                                <dd class="font-medium">-@price($order->discount_amount, $order->currency)</dd>
                            </div>
                            @endif
                            @if($order->tax_amount > 0)
                            <div class="flex justify-between">
                                <dt>{{ __('file.tax') }}</dt>
                                <dd class="font-medium text-gray-900">@price($order->tax_amount, $order->currency)</dd>
                            </div>
                            @endif
                            <div class="border-t border-gray-100 pt-4 flex justify-between">
                                <dt class="text-base font-bold text-gray-900">{{ __('file.total') }}</dt>
                                <dd class="text-base font-bold text-gray-900">@price($order->total_amount, $order->currency)</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Shipping Details --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('file.delivery_information') }}</h2>
                    </div>
                    <div class="p-6">
                        @if($order->shippingAddress)
                            <address class="not-italic text-sm text-gray-600 space-y-1">
                                <span class="block font-medium text-gray-900 mb-2">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</span>
                                <span class="block">{{ $order->shippingAddress->address_line1 }}</span>
                                @if($order->shippingAddress->address_line2)
                                    <span class="block">{{ $order->shippingAddress->address_line2 }}</span>
                                @endif
                                <span class="block">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}</span>
                                <span class="block">{{ $order->shippingAddress->country }}</span>
                                <span class="block mt-3 pt-3 border-t border-gray-100">
                                    <i data-feather="phone" class="w-4 h-4 inline-block mr-1 text-gray-400"></i> {{ $order->shippingAddress->phone ?? $order->customer_phone }}
                                </span>
                            </address>
                        @else
                            <p class="text-sm text-gray-500">Shipping information not available.</p>
                        @endif
                    </div>
                </div>

                {{-- Payment Details --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('file.payment_details') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-600 space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-900">{{ __('file.method') }}</span>
                                <span>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-900">{{ __('file.status') }}</span>
                                <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full 
                                    @if(strtolower($order->payment_status) == 'paid') bg-green-100 text-green-800 
                                    @elseif(strtolower($order->payment_status) == 'failed') bg-red-100 text-red-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ __('file.' . strtolower($order->payment_status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection
