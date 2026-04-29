@extends('layouts.app')

@section('title', 'Shipment Dynamics: #' . str_pad($shipment->id, 5, '0', STR_PAD_LEFT))

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('shipping.shipments.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Logistics Manifest</a>
                    <div class="flex items-center gap-3 mt-2">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Shipment Manifest
                            #{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</h1>
                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-indigo-500/10 text-indigo-600 border border-indigo-500/20 italic">Active
                            Transit Payload</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">Assigned to Order <span
                            class="text-gray-900 dark:text-white font-black italic underline decoration-indigo-500/30 underline-offset-4">#{{ str_pad($shipment->order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Main Content: Config & Timeline --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Logistics Configuration --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900 dark:text-white">Routing Configuration</h2>
                            <span
                                class="text-[10px] font-black text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded border border-amber-500/20 uppercase tracking-widest italic">Mutable
                                Parameters</span>
                        </div>
                        <div class="p-8">
                            <form action="{{ route('shipping.shipments.update', $shipment) }}" method="POST">
                                @csrf @method('PUT')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Target
                                            Courier</label>
                                        <select name="courier_id"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                            <option value="">None / In-Store Pickup Logic</option>
                                            @foreach($couriers as $courier)
                                                <option value="{{ $courier->id }}" {{ $shipment->courier_id == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Lifecycle
                                            Status</label>
                                        <select name="status"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                            @foreach(['pending', 'shipped', 'out_for_delivery', 'delivered', 'failed', 'returned'] as $s)
                                                <option value="{{ $s }}" {{ $shipment->status == $s ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="sm:col-span-2 space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Carrier
                                            Tracking Protocol</label>
                                        <div class="relative flex items-center">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="tracking_number"
                                                value="{{ old('tracking_number', $shipment->tracking_number) }}"
                                                placeholder="e.g. TRK-XXXX-YYYY-ZZ"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 pl-11 pr-4 py-3 text-sm font-mono text-indigo-600 dark:text-indigo-400 focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2 space-y-1.5">
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Logistics
                                            Annotation (Internal)</label>
                                        <textarea name="notes" rows="3"
                                            placeholder="Append transit observations or dispatcher remarks..."
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">{{ old('notes', $shipment->notes) }}</textarea>
                                    </div>
                                </div>

                                <div
                                    class="mt-8 pt-6 border-t border-gray-50 dark:border-surface-tonal-a30 flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                                        Update Logistics
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Global Tracking History --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900 dark:text-white">Real-time Event Stream</h2>
                            <span
                                class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20 uppercase tracking-widest">Chronological
                                Sync</span>
                        </div>
                        <div class="p-8">
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    @forelse($shipment->trackingEvents as $event)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span
                                                        class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-100 dark:bg-surface-tonal-a30"
                                                        aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex items-start space-x-4">
                                                    <div class="relative mt-1">
                                                        <div
                                                            class="h-10 w-10 rounded-xl flex items-center justify-center ring-8 ring-white dark:ring-surface-tonal-a20 {{ $event->status === 'delivered' ? 'bg-emerald-500 shadow-lg shadow-emerald-500/20' : 'bg-gray-100 dark:bg-surface-tonal-a30 text-gray-500 dark:text-gray-400' }}">
                                                            @if($event->status === 'delivered')
                                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @else
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="min-w-0 flex-1 pt-0.5 flex flex-col sm:flex-row sm:justify-between items-start gap-2">
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <p
                                                                    class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                                                    {{ ucfirst(str_replace('_', ' ', $event->status)) }}</p>
                                                                @if($event->location)
                                                                    <span
                                                                        class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20 italic">{{ $event->location }}</span>
                                                                @endif
                                                            </div>
                                                            <p
                                                                class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium italic underline decoration-gray-100 dark:decoration-surface-tonal-a30 underline-offset-4">
                                                                {{ $event->description }}</p>
                                                        </div>
                                                        <div
                                                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-300 dark:text-gray-600 italic">
                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <time
                                                                datetime="{{ $event->timestamp->toIso8601String() }}">{{ $event->timestamp->format('M j, Y — h:i A') }}</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <div class="flex flex-col items-center justify-center py-12 text-center">
                                            <div
                                                class="w-16 h-16 rounded-full bg-gray-50 dark:bg-surface-tonal-a10 flex items-center justify-center mb-4">
                                                <svg class="h-8 w-8 text-gray-300 dark:text-gray-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No event data
                                                broadcasted</p>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="mt-12 pt-8 border-t border-gray-50 dark:border-surface-tonal-a30">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Append
                                    Operational Update</h4>
                                <form action="{{ route('shipping.shipments.tracking', $shipment) }}" method="POST"
                                    class="bg-gray-100/50 dark:bg-surface-tonal-a10/30 p-6 rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label
                                                class="text-[9px] font-black text-gray-400 uppercase tracking-tighter ml-1">Event
                                                Status</label>
                                            <select name="status"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                                                required>
                                                <option value="pending">Authorize: Pending</option>
                                                <option value="shipped">Protocol: Shipped</option>
                                                <option value="in_transit">Protocol: In Transit / Sorting Hub</option>
                                                <option value="out_for_delivery">Final Leg: Out for Delivery</option>
                                                <option value="delivered">Completion: Delivered</option>
                                                <option value="failed">Exception: Delivery Attempt Failed</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label
                                                class="text-[9px] font-black text-gray-400 uppercase tracking-tighter ml-1">Geo-Location
                                                Node</label>
                                            <input type="text" name="location" placeholder="e.g. CMB-HUB-01"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-xs font-mono text-indigo-600 dark:text-indigo-400 focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm uppercase">
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-tighter ml-1">Protocol
                                            Details</label>
                                        <input type="text" name="description"
                                            placeholder="Specify precise event observations..." required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-xs font-medium text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                    </div>
                                    <div class="flex justify-end pt-2">
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-black dark:hover:bg-gray-100 shadow-lg transition-all active:scale-95">
                                            Execute Event Log
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Intelligence & Actions --}}
                <div class="space-y-6">

                    {{-- Recipient Intelligence --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Consignee
                                Intelligence</h3>
                            <div class="flex flex-col gap-1">
                                <p
                                    class="text-lg font-black text-gray-900 dark:text-white leading-tight uppercase tracking-tighter">
                                    {{ $shipment->order->customer?->first_name }}
                                    {{ $shipment->order->customer?->last_name }}</p>
                                <p
                                    class="text-xs font-bold text-indigo-600 dark:text-indigo-400 underline decoration-indigo-500/10">
                                    {{ $shipment->order->customer?->email }}</p>
                                <p class="text-xs font-mono text-gray-500 mt-1 italic">
                                    {{ $shipment->order->customer?->phone }}</p>
                            </div>
                            <div class="mt-6 pt-6 border-t border-gray-50 dark:border-surface-tonal-a30">
                                <a href="{{ route('orders.show', $shipment->order) }}"
                                    class="flex items-center justify-between group">
                                    <span
                                        class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Order
                                        Manifest</span>
                                    <svg class="h-4 w-4 text-gray-300 group-hover:text-indigo-600 transition-all"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Payload Destination --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 italic">Geodesic
                                Destination</h3>
                            <div class="text-sm space-y-3">
                                @if($shipment->pickupLocation)
                                    <div class="p-3 rounded-xl bg-blue-500/5 border border-blue-500/10">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                            <span
                                                class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Protocol:
                                                In-Hub Pickup</span>
                                        </div>
                                        <p class="font-black text-gray-900 dark:text-white leading-tight">
                                            {{ $shipment->pickupLocation->name }}</p>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                                            {{ $shipment->pickupLocation->address_line_1 }}<br>{{ $shipment->pickupLocation->city }}
                                        </p>
                                    </div>
                                @else
                                    @if($shipment->order->deliveryAddress)
                                        <div
                                            class="p-4 rounded-2xl bg-gray-100/50 dark:bg-surface-tonal-a10/20 border border-gray-100 dark:border-surface-tonal-a30">
                                            <div class="flex items-center gap-2 mb-3">
                                                <svg class="h-3 w-3 text-indigo-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                                <span
                                                    class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Protocol:
                                                    Direct Fulfillment</span>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-xs font-bold text-gray-900 dark:text-white leading-tight">
                                                    {{ $shipment->order->deliveryAddress->address_line_1 }}</p>
                                                @if($shipment->order->deliveryAddress->address_line_2)
                                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                        {{ $shipment->order->deliveryAddress->address_line_2 }}</p>
                                                @endif
                                                <p
                                                    class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest pt-1 italic">
                                                    {{ $shipment->order->deliveryAddress->city }},
                                                    {{ $shipment->order->deliveryAddress->postal_code }}</p>
                                                <p class="text-[10px] text-gray-400 font-medium">
                                                    {{ $shipment->order->deliveryAddress->country }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-4 rounded-xl bg-red-500/5 border border-red-500/10 flex items-center gap-3">
                                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span
                                                class="text-[10px] font-black text-red-600 dark:text-red-400 uppercase tracking-widest leading-tight">Geocoding
                                                Logic Error: No Destination found</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Quick Operations --}}
                    <div class="bg-gray-900 dark:bg-white rounded-2xl shadow-2xl p-6  relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 dark:bg-gray-900/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <h4
                            class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest relative z-10">
                            Output Processing</h4>
                        <p
                            class="text-xs mt-2 text-white/60 dark:text-gray-900/60 leading-relaxed font-medium relative z-10 italic underline decoration-white/20 dark:decoration-gray-900/10 underline-offset-4">
                            Generate printable logistics manifest for internal fulfillment routing.</p>
                        <button onclick="alert('Digital Waybill generation initialized.')"
                            class="w-full mt-6 py-4 bg-white dark:bg-gray-900 rounded-xl shadow-xl hover:shadow-2xl hover:scale-[1.02] transition-all relative z-10 group/btn">
                            <span
                                class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest flex items-center justify-center gap-2 group-hover/btn:translate-x-1 transition-transform">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Finalize Packing Slip
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection