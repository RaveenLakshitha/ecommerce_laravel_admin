@extends('layouts.app')

@section('title', 'Shipment Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2 uppercase">
                <a href="{{ route('shipping.shipments.index') }}" class="hover:text-gray-600 transition-colors">Shipments</a>
                <span>/</span>
                <span class="text-gray-600 dark:text-gray-300">#{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Shipment for Order #{{ str_pad($shipment->order->id, 5, '0', STR_PAD_LEFT) }}</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Shipment Config -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Edit Configuration</h3>
                <form action="{{ route('shipping.shipments.update', $shipment) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Courier</label>
                            <select name="courier_id" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                <option value="">None / In-Store Pickup</option>
                                @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}" {{ $shipment->courier_id == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                @foreach(['pending','shipped','out_for_delivery','delivered','failed','returned'] as $s)
                                    <option value="{{ $s }}" {{ $shipment->status == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tracking Number</label>
                            <input type="text" name="tracking_number" value="{{ $shipment->tracking_number }}" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Internal)</label>
                            <textarea name="notes" rows="2" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">{{ $shipment->notes }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="inline-flex justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700">Update Settings</button>
                    </div>
                </form>
            </div>

            <!-- Timeline -->
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Tracking History</h3>
                </div>

                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($shipment->trackingEvents as $event)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-surface-tonal-a30" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800 {{ $event->status === 'delivered' ? 'bg-green-500' : 'bg-gray-400' }}">
                                            <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-primary-a0">{{ ucfirst(str_replace('_', ' ', $event->status)) }}</p>
                                            <p class="text-sm text-gray-500">{{ $event->description }}</p>
                                            @if($event->location)<p class="text-xs text-gray-400 mt-0.5">Location: {{ $event->location }}</p>@endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            <time datetime="{{ $event->timestamp->toIso8601String() }}">{{ $event->timestamp->format('M j, Y h:i A') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                            <div class="text-sm text-gray-500 py-4">No tracking history recorded.</div>
                        @endforelse
                    </ul>
                </div>

                <hr class="border-gray-200 dark:border-surface-tonal-a30 my-6">
                
                <h4 class="text-sm font-medium text-gray-900 dark:text-primary-a0 mb-3">Add Manual Update</h4>
                <form action="{{ route('shipping.shipments.tracking', $shipment) }}" method="POST" class="bg-gray-50 dark:bg-surface-tonal-a10/50 p-4 rounded-lg flex flex-col gap-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <select name="status" class="block w-full text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0" required>
                            <option value="pending">Pending</option>
                            <option value="shipped">Shipped</option>
                            <option value="in_transit">In Transit / At Hub</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="failed">Attempt Failed</option>
                        </select>
                        <input type="text" name="location" placeholder="Location Code/City" class="block w-full text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                    </div>
                    <input type="text" name="description" placeholder="Description/Event Details *" required class="block w-full text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                    <div class="text-right">
                        <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500">Log Event</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Meta -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Customer Details</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <p class="font-bold text-gray-900 dark:text-primary-a0">{{ $shipment->order->customer?->first_name }} {{ $shipment->order->customer?->last_name }}</p>
                    <p>{{ $shipment->order->customer?->email }}</p>
                    <p>{{ $shipment->order->customer?->phone }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-surface-tonal-a30 text-sm">
                    <a href="{{ route('orders.show', $shipment->order) }}" class="text-indigo-600 font-medium hover:underline">View Original Order &rarr;</a>
                </div>
            </div>

            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Shipping Destination</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    @if($shipment->pickupLocation)
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mb-2">In-Store Pickup</span>
                        <p class="font-bold">{{ $shipment->pickupLocation->name }}</p>
                        <p>{{ $shipment->pickupLocation->address_line_1 }}</p>
                        <p>{{ $shipment->pickupLocation->city }}</p>
                    @else
                        @if($shipment->order->deliveryAddress)
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mb-2">Home Delivery</span>
                            <p>{{ $shipment->order->deliveryAddress->address_line_1 }}</p>
                            <p>{{ $shipment->order->deliveryAddress->address_line_2 }}</p>
                            <p>{{ $shipment->order->deliveryAddress->city }}, {{ $shipment->order->deliveryAddress->postal_code }}</p>
                            <p>{{ $shipment->order->deliveryAddress->country }}</p>
                        @else
                            <p class="text-red-500">No destination configured on order record.</p>
                        @endif
                    @endif
                </div>
            </div>
            
            <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-100 dark:border-indigo-800 p-6">
                <h4 class="font-medium text-indigo-900 dark:text-indigo-300">Generate Waybill/Label</h4>
                <p class="text-sm mt-1 mb-3 text-indigo-700 dark:text-indigo-400">Creates a printable PDF layout for internal packing purposes.</p>
                <button onclick="alert('Label generation PDF functionality mocked natively.')" class="w-full px-3 py-2 bg-white text-indigo-600 font-semibold rounded shadow-sm text-sm border border-indigo-200 text-center hover:bg-gray-50 transition">Print Packing Slip</button>
            </div>
        </div>
    </div>
</div>
@endsection

