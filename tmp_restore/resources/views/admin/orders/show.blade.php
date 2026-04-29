@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('orders.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Orders</a>
                    <div class="flex items-center gap-4 mt-2">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $order->order_number }}</h1>
                        @php
                            $statusMap = [
                                'pending' => ['label' => 'Pending', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'],
                                'processing' => ['label' => 'Processing', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border-blue-200 dark:border-blue-500/20'],
                                'shipped' => ['label' => 'Shipped', 'class' => 'bg-violet-100 text-violet-700 dark:bg-violet-500/10 dark:text-violet-400 border-violet-200 dark:border-violet-500/20'],
                                'delivered' => ['label' => 'Delivered', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20'],
                                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400 border-red-200 dark:border-red-500/20'],
                                'returned' => ['label' => 'Returned', 'class' => 'bg-gray-100 text-gray-700 dark:bg-surface-tonal-a30 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-100 text-gray-700 dark:bg-surface-tonal-a30 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30'];
                        @endphp
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $s['class'] }}">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        Placed on {{ $order->created_at->format('M d, Y • H:i') }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
                        class="px-5 py-2.5 bg-gray-900 dark:bg-white border border-transparent rounded-xl text-sm font-bold text-white dark:text-gray-900 hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl shadow-gray-200 dark:shadow-none">
                        Print Invoice
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                {{-- Left Column: Items & Summary --}}
                <div class="xl:col-span-2 space-y-6">

                    {{-- Order Items --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900 dark:text-white">Order Items</h2>
                            <span
                                class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $order->items->count() }}
                                Items</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-gray-100/50 dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            Product</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">
                                            Price</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                            Qty</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @foreach($order->items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/20 transition-colors">
                                            <td class="px-6 py-4">
                                                <div
                                                    class="text-sm font-bold text-gray-700 dark:text-white underline decoration-indigo-500/30 decoration-2 underline-offset-4">
                                                    {{ $item->product_name_snapshot }}</div>
                                                @if($item->variant_attributes)
                                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                                        @foreach(json_decode($item->variant_attributes, true) ?? [] as $key => $val)
                                                            <span
                                                                class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-surface-tonal-a30 text-gray-500 dark:text-gray-400 text-[8px] font-bold border border-gray-200 dark:border-surface-tonal-a30 uppercase tracking-tighter">
                                                                {{ $key }}: {{ $val }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $order->currency }}
                                                    {{ number_format($item->unit_price, 2) }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-black border border-indigo-100 dark:border-indigo-900/30">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="text-sm font-bold text-gray-900 dark:text-white font-mono tabular-nums">{{ $order->currency }}
                                                    {{ number_format($item->total, 2) }}</span>
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
                                        <span class="uppercase tracking-widest">Subtotal</span>
                                        <span class="font-mono tabular-nums">{{ $order->currency }}
                                            {{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    @if($order->discount_amount > 0)
                                        <div class="flex justify-between items-center text-xs font-bold text-red-500">
                                            <span class="uppercase tracking-widest">Discount</span>
                                            <span class="font-mono tabular-nums">-{{ $order->currency }}
                                                {{ number_format($order->discount_amount, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($order->shipping_amount > 0)
                                        <div
                                            class="flex justify-between items-center text-xs font-bold text-gray-500 dark:text-gray-400">
                                            <span class="uppercase tracking-widest">Shipping</span>
                                            <span class="font-mono tabular-nums">{{ $order->currency }}
                                                {{ number_format($order->shipping_amount, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($order->tax_amount > 0)
                                        <div
                                            class="flex justify-between items-center text-xs font-bold text-gray-500 dark:text-gray-400">
                                            <span class="uppercase tracking-widest">Tax</span>
                                            <span class="font-mono tabular-nums">{{ $order->currency }}
                                                {{ number_format($order->tax_amount, 2) }}</span>
                                        </div>
                                    @endif
                                    <div
                                        class="pt-3 border-t border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center">
                                        <span
                                            class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Total</span>
                                        <span
                                            class="text-xl font-black text-indigo-600 dark:text-indigo-400 font-mono tabular-nums">{{ $order->currency }}
                                            {{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Refund History & Actions --}}
                    @if($order->refunds->count() > 0)
                        <div
                            class="bg-red-50/30 dark:bg-red-950/10 rounded-2xl border border-red-100 dark:border-red-950/20 overflow-hidden">
                            <div class="px-6 py-4 border-b border-red-100 dark:border-red-950/20 mt-0">
                                <h2 class="font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                    </svg>
                                    Refund History
                                </h2>
                            </div>
                            <div class="p-0">
                                <table class="w-full text-left">
                                    <tbody class="divide-y divide-red-100/50 dark:divide-red-950/30">
                                        @foreach($order->refunds as $refund)
                                            <tr class="text-xs">
                                                <td
                                                    class="px-6 py-4 font-bold text-red-600 dark:text-red-400 uppercase tracking-widest">
                                                    {{ $refund->created_at->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                                    {{ $refund->reason ?: 'No reason' }}</td>
                                                <td class="px-6 py-4 text-right font-black text-red-600 dark:text-red-400">
                                                    -${{ number_format($refund->amount, 2) }}</td>
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
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-lg bg-red-500 text-white flex items-center justify-center text-lg">↺</span>
                                Process Marketplace Refund
                            </h3>
                            <form action="{{ route('orders.refund', $order->id) }}" method="POST"
                                class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">
                                @csrf
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Amount
                                        ({{ $order->currency }})</label>
                                    <input type="number" step="0.01" name="amount" min="0.01" max="{{ $refundableAmount }}"
                                        value="{{ $refundableAmount }}" required
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-2.5 text-sm font-black text-red-600 dark:text-red-400 focus:ring-2 focus:ring-red-500 transition-all font-mono">
                                    <p class="text-[9px] text-gray-400 font-medium">Max possible:
                                        ${{ number_format($refundableAmount, 2) }}</p>
                                </div>
                                <div class="space-y-1.5 sm:col-span-1">
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reason</label>
                                    <input type="text" name="reason" placeholder="Returned, Damage, etc..."
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 transition-all">
                                </div>
                                <div class="sm:col-span-1">
                                    <button type="submit" onclick="return confirm('Confirm refund processing?')"
                                        class="w-full h-10 flex items-center justify-center rounded-xl bg-red-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-red-700 shadow-xl shadow-red-500/20 active:scale-95 transition-all">
                                        Execute Refund
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>

                {{-- Right Column: Side Cards --}}
                <div class="xl:col-span-1 space-y-6">

                    {{-- Status Modification Card --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="font-semibold text-gray-900 dark:text-white">Workflow Phase</h2>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Order
                                        Status</label>
                                    <select name="status"
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-xs font-bold text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition-all">
                                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'] as $st)
                                            <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                                                {{ ucfirst($st) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-widest hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-95">
                                    Transition Status
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Customer Identity Card --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Customer Identity
                        </h3>
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-lg font-black text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30">
                                {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-sm font-black text-gray-900 dark:text-white leading-tight underline decoration-indigo-500/30 decoration-2 underline-offset-2">{{ $order->customer_name }}</span>
                                <span
                                    class="text-[10px] font-bold text-gray-400 mt-0.5 truncate max-w-[150px]">{{ $order->customer_email }}</span>
                            </div>
                            @if($order->customer_id)
                                <a href="{{ route('customers.show', $order->customer_id) }}"
                                    class="ml-auto p-2 rounded-xl bg-gray-50 dark:bg-surface-tonal-a30 text-gray-400 hover:text-indigo-500 transition-all">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-surface-tonal-a30">
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Phone Contact</p>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                    {{ $order->customer_phone ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Logistic Destination Card --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6 relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 p-4 opacity-5 group-hover:rotate-12 transition-transform duration-500">
                            <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Logistic Destination
                        </h3>
                        @if($order->shippingAddress)
                            <div class="space-y-1 relative z-10">
                                <p class="text-sm font-black text-gray-900 dark:text-white">
                                    {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 leading-relaxed">
                                    {{ $order->shippingAddress->address_line_1 }}<br>
                                    @if($order->shippingAddress->address_line_2)
                                    {{ $order->shippingAddress->address_line_2 }}<br> @endif
                                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                                    {{ $order->shippingAddress->postal_code }}<br>
                                    <span
                                        class="font-black text-indigo-500 uppercase tracking-tighter">{{ $order->shippingAddress->country }}</span>
                                </p>
                            </div>
                        @else
                            <p class="text-xs italic text-gray-400 font-medium">No logistical destination defined.</p>
                        @endif
                    </div>

                    {{-- Staff Annotations Card --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Staff Annotations
                        </h3>
                        <form action="{{ route('orders.notes.add', $order->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <textarea name="internal_notes" rows="4" placeholder="Record internal observations..."
                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all resize-none">{{ $order->internal_notes }}</textarea>
                            <button type="submit"
                                class="w-full h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-surface-tonal-a30 text-gray-600 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-gray-100 dark:border-surface-tonal-a30">
                                Commit Annotation
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection