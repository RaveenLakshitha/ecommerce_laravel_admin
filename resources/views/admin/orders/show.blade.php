@extends('layouts.app')

@section('title', __('file.order') . ' ' . $order->order_number)

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('orders.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_orders') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $order->order_number }}</h1>
                        @php
                            $statusMap = [
                                'pending' => ['label' => __('file.pending'), 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'],
                                'processing' => ['label' => __('file.processing'), 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border-blue-200 dark:border-blue-500/20'],
                                'shipped' => ['label' => __('file.shipped'), 'class' => 'bg-violet-100 text-violet-700 dark:bg-violet-500/10 dark:text-violet-400 border-violet-200 dark:border-violet-500/20'],
                                'delivered' => ['label' => __('file.delivered'), 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20'],
                                'cancelled' => ['label' => __('file.cancelled'), 'class' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400 border-red-200 dark:border-red-500/20'],
                                'returned' => ['label' => __('file.returned'), 'class' => 'bg-gray-100 text-gray-700 dark:bg-surface-tonal-a30 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-100 text-gray-700 dark:bg-surface-tonal-a30 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30'];
                        @endphp
                        <span
                            class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest border {{ $s['class'] }}">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.placed_on') }}
                        {{ $order->created_at->format('M d, Y • H:i') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
                        class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-lg transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.print_invoice') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Order Items --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.order_items') }}</h2>
                            <span
                                class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $order->items->count() }}
                                {{ __('file.items') }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-gray-50 dark:bg-surface-tonal-a20 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            {{ __('file.product') }}</th>
                                        <th
                                            class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">
                                            {{ __('file.price') }}</th>
                                        <th
                                            class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                            {{ __('file.qty') }}</th>
                                        <th
                                            class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">
                                            {{ __('file.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @foreach($order->items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-700 dark:text-white">
                                                    {{ $item->product_name_snapshot }}
                                                </div>
                                                @if($item->variant_attributes)
                                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                                        @foreach(($item->variant_attributes ?? []) as $key => $val)
                                                            <span
                                                                class="px-1.5 py-0.5 rounded-md bg-gray-100 dark:bg-surface-tonal-a30 text-gray-500 dark:text-gray-400 text-[8px] font-bold border border-gray-200 dark:border-surface-tonal-a30 uppercase tracking-tighter">
                                                                {{ $key }}: {{ $val }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="text-xs font-mono text-gray-500 dark:text-gray-400">@price($item->unit_price)</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center justify-center min-w-[24px] h-6 px-1.5 rounded-md bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-black border border-indigo-100 dark:border-indigo-900/30">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="text-sm font-bold text-gray-900 dark:text-white font-mono tabular-nums">@price($item->total)</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Financial Summary Footer --}}
                        <div
                            class="p-6 bg-gray-100/50 dark:bg-surface-tonal-a20/50 border-t border-gray-100 dark:border-surface-tonal-a30">
                            <div class="flex justify-end">
                                <div class="w-full sm:w-64 space-y-3">
                                    <div
                                        class="flex justify-between items-center text-xs font-bold text-gray-500 dark:text-gray-400">
                                        <span class="uppercase tracking-widest">{{ __('file.subtotal') }}</span>
                                        <span
                                            class="font-mono tabular-nums">@price($order->subtotal)</span>
                                    </div>
                                    @if($order->discount_amount > 0)
                                        <div class="flex justify-between items-center text-xs font-bold text-red-500">
                                            <span class="uppercase tracking-widest">{{ __('file.discount') }}</span>
                                            <span
                                                class="font-mono tabular-nums">-@price($order->discount_amount)</span>
                                        </div>
                                    @endif
                                    @if($order->shipping_amount > 0)
                                        <div
                                            class="flex justify-between items-center text-xs font-bold text-gray-500 dark:text-gray-400">
                                            <span class="uppercase tracking-widest">{{ __('file.shipping') }}</span>
                                            <span
                                                class="font-mono tabular-nums">@price($order->shipping_amount)</span>
                                        </div>
                                    @endif
                                    @if($order->tax_amount > 0)
                                        <div
                                            class="flex justify-between items-center text-xs font-bold text-gray-500 dark:text-gray-400">
                                            <span class="uppercase tracking-widest">{{ __('file.tax') }}</span>
                                            <span
                                                class="font-mono tabular-nums">@price($order->tax_amount)</span>
                                        </div>
                                    @endif
                                    <div
                                        class="pt-3 border-t border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center">
                                        <span
                                            class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">{{ __('file.total') }}</span>
                                        <span
                                            class="text-xl font-black text-indigo-600 dark:text-indigo-400 font-mono tabular-nums">@price($order->total_amount)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Refund History --}}
                    @if($order->refunds->count() > 0)
                        <div
                            class="bg-rose-50/30 dark:bg-rose-950/10 rounded-lg border border-rose-100 dark:border-rose-950/20 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-rose-100 dark:border-rose-950/20 bg-rose-50/50 dark:bg-rose-950/20">
                                <h2
                                    class="text-sm font-bold text-rose-700 dark:text-rose-400 flex items-center gap-2 uppercase tracking-widest">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                    </svg>
                                    {{ __('file.refund_history') }}
                                </h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <tbody class="divide-y divide-rose-100/50 dark:divide-rose-950/30">
                                        @foreach($order->refunds as $refund)
                                            <tr class="text-xs">
                                                <td
                                                    class="px-6 py-4 font-bold text-rose-600 dark:text-rose-400 uppercase tracking-widest">
                                                    {{ $refund->created_at->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 italic">
                                                    {{ $refund->reason ?: __('file.no_reason') }}</td>
                                                <td class="px-6 py-4 text-right font-black text-rose-600 dark:text-rose-400">
                                                    -@price($refund->amount)</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Refund Action Form --}}
                    @php $refundableAmount = $order->total_amount - $order->refunded_amount; @endphp
                    @if($refundableAmount > 0)
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                            <h3
                                class="text-sm font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2 uppercase tracking-widest">
                                <span
                                    class="w-6 h-6 rounded bg-rose-500 text-white flex items-center justify-center text-xs">↺</span>
                                {{ __('file.process_marketplace_refund') }}
                            </h3>
                            <form action="{{ route('orders.refund', $order->id) }}" method="POST"
                                class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">
                                @csrf
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.total_amount') }}
                                        ({{ $currency_symbol }})</label>
                                    <input type="number" step="0.01" name="amount" min="0.01" max="{{ $refundableAmount }}"
                                        value="{{ $refundableAmount }}" required
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-rose-600 dark:text-rose-400 outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 font-mono">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                                        {{ __('file.max_possible') }}: @price($refundableAmount)</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.reason') }}</label>
                                    <input type="text" name="reason" placeholder="{{ __('file.returned_damage_etc') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-gray-700 dark:text-white outline-none focus:bg-white dark:focus:bg-surface-tonal-a30">
                                </div>
                                <div>
                                    <button type="submit" onclick="return confirm('{{ __('file.confirm_refund_processing') }}')"
                                        class="w-full py-2.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-rose-700 shadow-md active:scale-95 transition-all">
                                        {{ __('file.execute_refund') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Right Column --}}
                <div class="col-span-1 space-y-4">

                    {{-- Workflow Phase --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">
                                {{ __('file.workflow_phase') }}</h2>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('file.order_status') }}</label>
                                    <select name="status"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-700 dark:text-gray-200 outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 cursor-pointer">
                                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'] as $st)
                                            <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                                                {{ __('file.'.$st) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                    class="w-full py-2.5 bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-widest rounded-lg hover:bg-black dark:hover:bg-gray-100 transition-all shadow-md active:scale-95">
                                    {{ __('file.transition_status') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Customer Identity --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-4">
                        <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4">
                            {{ __('file.customer_identity') }}</h3>
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-lg font-black text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30 shadow-sm">
                                {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span
                                    class="text-sm font-black text-gray-900 dark:text-white leading-tight truncate">{{ $order->customer_name }}</span>
                                <span
                                    class="text-[10px] font-bold text-gray-400 mt-0.5 truncate">{{ $order->customer_email }}</span>
                            </div>
                            @if($order->customer_id)
                                <a href="{{ route('customers.show', $order->customer_id) }}"
                                    class="ml-auto p-2 rounded-lg bg-gray-50 dark:bg-surface-tonal-a30 text-gray-400 hover:text-indigo-500 transition-all border border-gray-100 dark:border-surface-tonal-a30 shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-surface-tonal-a30">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                    {{ __('file.phone_contact') }}</p>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                    {{ $order->customer_phone ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Logistic Destination --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-4 relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <h3
                            class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4 relative z-10">
                            {{ __('file.logistic_destination') }}</h3>
                        @if($order->shippingAddress)
                            <div class="space-y-1 relative z-10">
                                <p class="text-sm font-black text-gray-900 dark:text-white">
                                    {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                                <div
                                    class="text-xs font-bold text-gray-500 dark:text-gray-400 leading-relaxed uppercase tracking-tighter">
                                    {{ $order->shippingAddress->address_line_1 }}<br>
                                    @if($order->shippingAddress->address_line_2)
                                    {{ $order->shippingAddress->address_line_2 }}<br> @endif
                                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                                    {{ $order->shippingAddress->postal_code }}<br>
                                    <span
                                        class="text-indigo-600 dark:text-indigo-400">{{ $order->shippingAddress->country }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-xs italic text-gray-400 font-bold uppercase tracking-widest relative z-10">
                                {{ __('file.no_destination_defined') }}</p>
                        @endif
                    </div>

                    {{-- Staff Annotations --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-4">
                        <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4">
                            {{ __('file.staff_annotations') }}</h3>
                        <form action="{{ route('orders.notes.add', $order->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <textarea name="internal_notes" rows="4"
                                placeholder="{{ __('file.record_internal_observations') }}"
                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-gray-700 dark:text-gray-300 outline-none focus:bg-white dark:focus:bg-surface-tonal-a30 resize-none">{{ $order->internal_notes }}</textarea>
                            <button type="submit"
                                class="w-full py-2.5 bg-gray-50 dark:bg-surface-tonal-a30 text-gray-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-gray-100 dark:border-surface-tonal-a30">
                                {{ __('file.commit_annotation') }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection