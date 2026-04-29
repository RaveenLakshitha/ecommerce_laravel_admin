@extends('layouts.app')

@section('title', __('file.shipment_dynamics') . ': #' . str_pad($shipment->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="admin-page animate-fade-in-up">
    <div class="admin-page-inner">

        {{-- Breadcrumbs --}}
        <div class="mb-4 mt-10">
            <a href="{{ route('shipping.shipments.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                &larr; {{ __('file.back_to_logistics_manifest') }}
            </a>
        </div>

        {{-- Header Area --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('file.shipment_manifest') }} #{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</h1>
                    <span class="admin-badge admin-badge-neutral text-[10px] font-bold uppercase tracking-wider italic">
                        {{ __('file.active_transit_payload') }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-semibold">
                    {{ __('file.assigned_to_order') }} <a href="{{ route('orders.show', $shipment->order->id) }}" class="text-indigo-600 dark:text-indigo-400 underline decoration-indigo-500/30 underline-offset-4">#{{ str_pad($shipment->order->id, 5, '0', STR_PAD_LEFT) }}</a>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                    <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    {{ __('file.print_waybill') }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Left Column: Config & Timeline --}}
            <div class="lg:col-span-2 space-y-4">
                
                {{-- Logistics Configuration --}}
                <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">{{ __('file.routing_configuration') }}</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('shipping.shipments.update', $shipment) }}" method="POST">
                            @csrf @method('PUT')
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">{{ __('file.target_courier') }}</label>
                                    <select name="courier_id" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-900 dark:text-white outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 cursor-pointer transition-all">
                                        <option value="">{{ __('file.none_in_store_pickup_logic') }}</option>
                                        @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}" {{ $shipment->courier_id == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">{{ __('file.lifecycle_status') }}</label>
                                    <select name="status" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-900 dark:text-white outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 cursor-pointer transition-all">
                                        @foreach(['pending','shipped','out_for_delivery','delivered','failed','returned'] as $s)
                                            <option value="{{ $s }}" {{ $shipment->status == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">{{ __('file.carrier_tracking_protocol') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $shipment->tracking_number) }}" placeholder="e.g. TRK-XXXX-YYYY-ZZ"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 pl-9 pr-3 py-2 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 shadow-sm transition-all uppercase">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">{{ __('file.logistics_annotation_internal') }}</label>
                                    <textarea name="notes" rows="3" placeholder="{{ __('file.append_transit_observations_placeholder') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-900 dark:text-white outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 transition-all resize-none">{{ old('notes', $shipment->notes) }}</textarea>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-surface-tonal-a30 flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 shadow-md transition-all active:scale-95">
                                    {{ __('file.update_logistics') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Real-time Event Stream --}}
                <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">{{ __('file.real_time_event_stream') }}</h2>
                        <span class="text-xs font-bold text-emerald-500 uppercase tracking-wider italic">{{ __('file.chronological_sync') }}</span>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @forelse($shipment->trackingEvents as $event)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-100 dark:bg-surface-tonal-a30" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <div class="h-8 w-8 rounded-lg flex items-center justify-center ring-4 ring-white dark:ring-surface-tonal-a20 {{ $event->status === 'delivered' ? 'bg-emerald-500 shadow-sm' : 'bg-gray-100 dark:bg-surface-tonal-a30 text-gray-500 dark:text-gray-400' }}">
                                                    @if($event->status === 'delivered')
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    @else
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1 flex flex-col sm:flex-row sm:justify-between items-start gap-2">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">{{ ucfirst(str_replace('_', ' ', $event->status)) }}</p>
                                                        @if($event->location)
                                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20 italic">{{ $event->location }}</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium italic underline decoration-gray-100 dark:decoration-surface-tonal-a30 underline-offset-4">{{ $event->description }}</p>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-300 dark:text-gray-600 italic">
                                                    <time datetime="{{ $event->timestamp->toIso8601String() }}">{{ $event->timestamp->format('M j — h:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                    <div class="flex flex-col items-center justify-center py-10 text-center">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('file.no_event_data_broadcasted') }}</p>
                                    </div>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-100 dark:border-surface-tonal-a30">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">{{ __('file.append_operational_update') }}</h4>
                            <form action="{{ route('shipping.shipments.tracking', $shipment) }}" method="POST" class="bg-gray-100/50 dark:bg-surface-tonal-a10/30 p-4 rounded-xl border border-gray-100 dark:border-surface-tonal-a30 space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.event_status') }}</label>
                                        <select name="status" class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-white dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-900 dark:text-white outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 transition-all cursor-pointer" required>
                                            <option value="pending">Authorize: Pending</option>
                                            <option value="shipped">Protocol: Shipped</option>
                                            <option value="in_transit">Protocol: In Transit</option>
                                            <option value="out_for_delivery">Final Leg: Out for Delivery</option>
                                            <option value="delivered">Completion: Delivered</option>
                                            <option value="failed">Exception: Failed</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.geo_location_node') }}</label>
                                        <input type="text" name="location" placeholder="e.g. CMB-HUB-01" 
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-white dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 transition-all uppercase">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.protocol_details') }}</label>
                                    <input type="text" name="description" placeholder="{{ __('file.specify_precise_event_observations_placeholder') }}" required 
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-white dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-900 dark:text-white outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 transition-all">
                                </div>
                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="px-5 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-black dark:hover:bg-gray-100 shadow-md transition-all active:scale-95">
                                        {{ __('file.execute_event_log') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Intelligence & Actions --}}
            <div class="col-span-1 space-y-4">
                
                {{-- Consignee Intelligence --}}
                <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-4">
                    <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">{{ __('file.consignee_intelligence') }}</h3>
                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase tracking-tighter">{{ $shipment->order->customer?->first_name }} {{ $shipment->order->customer?->last_name }}</p>
                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 truncate">{{ $shipment->order->customer?->email }}</p>
                        <p class="text-[10px] font-bold text-gray-500 mt-1 italic">{{ $shipment->order->customer?->phone }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-surface-tonal-a30">
                        <a href="{{ route('orders.show', $shipment->order->id) }}" class="flex items-center justify-between group">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider group-hover:text-indigo-600 transition-colors">{{ __('file.order_manifest') }}</span>
                            <svg class="h-4 w-4 text-gray-300 group-hover:text-indigo-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Geodesic Destination --}}
                <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-4 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4 relative z-10">{{ __('file.geodesic_destination') }}</h3>
                    <div class="relative z-10">
                        @if($shipment->pickupLocation)
                            <div class="p-3 rounded-lg bg-blue-500/5 border border-blue-500/10">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                                    <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ __('file.protocol_in_hub_pickup') }}</span>
                                </div>
                                <p class="text-xs font-black text-gray-900 dark:text-white leading-tight">{{ $shipment->pickupLocation->name }}</p>
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{{ $shipment->pickupLocation->address_line_1 }}<br>{{ $shipment->pickupLocation->city }}</p>
                            </div>
                        @else
                            @if($shipment->order->deliveryAddress)
                                <div class="p-3 rounded-lg bg-gray-100/50 dark:bg-surface-tonal-a10/20 border border-gray-100 dark:border-surface-tonal-a30">
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg class="h-3.5 w-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">{{ __('file.protocol_direct_fulfillment') }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs font-black text-gray-900 dark:text-white leading-tight">{{ $shipment->order->deliveryAddress->address_line_1 }}</p>
                                        @if($shipment->order->deliveryAddress->address_line_2)
                                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400">{{ $shipment->order->deliveryAddress->address_line_2 }}</p>
                                        @endif
                                        <p class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest pt-1">{{ $shipment->order->deliveryAddress->city }}, {{ $shipment->order->deliveryAddress->postal_code }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $shipment->order->deliveryAddress->country }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="p-3 rounded-lg bg-rose-500/5 border border-rose-500/10 flex items-center gap-3">
                                    <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span class="text-[9px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest leading-tight">{{ __('file.geocoding_logic_error_no_destination') }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Output Processing --}}
                <div class="bg-gray-900 dark:bg-white rounded-lg shadow-lg p-4 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 dark:bg-gray-900/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider relative z-10">{{ __('file.output_processing') }}</h4>
                    <p class="text-xs mt-2 text-white/60 dark:text-gray-900/60 leading-relaxed font-semibold relative z-10 italic">{{ __('file.generate_printable_logistics_manifest_text') }}</p>
                    <button onclick="window.print()" class="w-full mt-4 py-3 bg-white dark:bg-gray-900 rounded-lg shadow-md hover:scale-[1.02] transition-all relative z-10 group/btn">
                        <span class="text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider flex items-center justify-center gap-2 group-hover/btn:translate-x-1 transition-transform">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            {{ __('file.finalize_packing_slip') }}
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
