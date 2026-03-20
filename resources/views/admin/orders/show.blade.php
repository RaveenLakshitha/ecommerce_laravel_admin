@extends('layouts.app')

@section('title', __('file.order_details') ?? 'Order Details')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">

            {{-- ── Header ─────────────────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500 mb-2 font-mono tracking-wider uppercase">
                        <a href="{{ route('orders.index') }}" class="hover:text-gray-600 dark:hover:text-gray-300 transition-colors">Orders</a>
                        <span>/</span>
                        <span class="text-gray-600 dark:text-gray-300">{{ $order->order_number }}</span>
                    </div>
                    <h1 class="flex items-center gap-3 text-2xl sm:text-3xl font-semibold tracking-tight text-gray-900 dark:text-primary-a0">
                        {{ $order->order_number }}
                        @php
                            $statusMap = [
                                'pending' => ['label' => 'Pending', 'class' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 ring-amber-500/20'],
                                'processing' => ['label' => 'Processing', 'class' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 ring-blue-500/20'],
                                'shipped' => ['label' => 'Shipped', 'class' => 'bg-violet-500/10 text-violet-600 dark:text-violet-400 ring-violet-500/20'],
                                'delivered' => ['label' => 'Delivered', 'class' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-emerald-500/20'],
                                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-500/10 text-red-600 dark:text-red-400 ring-red-500/20'],
                                'returned' => ['label' => 'Returned', 'class' => 'bg-gray-500/10 text-gray-600 dark:text-gray-400 ring-gray-500/20'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label' => $order->display_status, 'class' => 'bg-gray-500/10 text-gray-600 ring-gray-500/20'];
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $s['class'] }}">
                            {{ $s['label'] }}
                        </span>
                    </h1>
                    <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                        Placed on {{ $order->placed_at ? $order->placed_at->format('M d, Y · h:i A') : $order->created_at->format('M d, Y · h:i A') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('orders.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 px-3.5 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </a>
                    <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 rounded-md bg-gray-900 dark:bg-white px-3.5 py-2 text-sm font-medium text-white dark:text-gray-900 shadow-sm hover:bg-gray-700 dark:hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Invoice
                    </a>
                </div>
            </div>

            {{-- ── Flash Messages ──────────────────────────────────── --}}
            @if(session('success'))
                <div class="mb-6 flex items-start gap-3 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 p-4">
                    <svg class="w-4 h-4 mt-0.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-sm text-emerald-700 dark:text-emerald-400 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 flex items-start gap-3 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 p-4">
                    <svg class="w-4 h-4 mt-0.5 text-red-600 dark:text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm text-red-700 dark:text-red-400 font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ── Body Grid ───────────────────────────────────────── --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- ╔══════════════════════════════════╗
                     ║  MAIN COLUMN (2/3)               ║
                     ╚══════════════════════════════════╝ --}}
                <div class="xl:col-span-2 space-y-5">

                    {{-- Order Items Card --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20">
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-primary-a0 tracking-tight">Order Items</h2>
                            <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-surface-tonal-a20">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach($order->items as $item)
                                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-primary-a0">{{ $item->product_name_snapshot }}</div>
                                                @if($item->variant_attributes)
                                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                                        @foreach(json_decode($item->variant_attributes, true) ?? [] as $key => $val)
                                                            <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-medium bg-gray-100 dark:bg-surface-tonal-a20 text-gray-600 dark:text-gray-400 ring-1 ring-inset ring-gray-200 dark:ring-gray-700">
                                                                {{ ucfirst($key) }}: {{ $val }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400 font-mono tabular-nums">
                                                {{ $order->currency }} {{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 dark:bg-surface-tonal-a20 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-primary-a0 font-mono tabular-nums">
                                                {{ $order->currency }} {{ number_format($item->total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Order Summary Footer --}}
                        <div class="border-t border-gray-100 dark:border-surface-tonal-a20 bg-gray-50/60 dark:bg-surface-tonal-a20/40 px-6 py-4 space-y-2">
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span class="font-mono tabular-nums">{{ $order->currency }} {{ number_format($order->subtotal, 2) }}</span>
                            </div>

                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Discount</span>
                                    <span class="font-mono tabular-nums text-red-500 dark:text-red-400">-{{ $order->currency }} {{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            @endif

                            @if($order->shipping_amount > 0)
                                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>Shipping</span>
                                    <span class="font-mono tabular-nums">{{ $order->currency }} {{ number_format($order->shipping_amount, 2) }}</span>
                                </div>
                            @endif

                            @if($order->tax_amount > 0)
                                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>Tax</span>
                                    <span class="font-mono tabular-nums">{{ $order->currency }} {{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center pt-2 mt-2 border-t border-gray-200 dark:border-surface-tonal-a30">
                                <span class="text-sm font-semibold text-gray-900 dark:text-primary-a0">Total</span>
                                <span class="text-base font-bold text-gray-900 dark:text-primary-a0 font-mono tabular-nums">
                                    {{ $order->currency }} {{ number_format($order->total_amount, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Refunds History --}}
                    @if($order->refunds->count() > 0)
                        <div class="rounded-xl border border-red-200 dark:border-red-500/20 bg-white dark:bg-surface-tonal-a10 shadow-sm overflow-hidden">
                            <div class="flex items-center gap-2 px-6 py-4 border-b border-red-100 dark:border-red-500/20 bg-red-50/60 dark:bg-red-500/10">
                                <svg class="w-4 h-4 text-red-500 dark:text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                <h2 class="text-sm font-semibold text-red-700 dark:text-red-400">Refund History</h2>
                            </div>
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-surface-tonal-a20">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Reason</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach($order->refunds as $refund)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors">
                                            <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono whitespace-nowrap">
                                                {{ $refund->refunded_at ? $refund->refunded_at->format('M d, Y') : $refund->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $refund->reason ?: 'No reason provided' }}</td>
                                            <td class="px-6 py-3 text-right text-sm font-semibold text-red-500 dark:text-red-400 font-mono tabular-nums">
                                                -{{ $order->currency }} {{ number_format($refund->amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Issue Refund --}}
                    @if($order->total_amount - $order->refunded_amount > 0)
                        <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-primary-a0">Issue Refund</h2>
                            </div>
                            <form action="{{ route('orders.refund', $order->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                            Amount <span class="normal-case font-normal">({{ $order->currency }})</span>
                                        </label>
                                        <input type="number" step="0.01" name="amount" min="0.01"
                                               max="{{ $order->total_amount - $order->refunded_amount }}"
                                               value="{{ $order->total_amount - $order->refunded_amount }}"
                                               class="block w-full rounded-md border border-gray-200 dark:border-surface-tonal-a30 bg-transparent dark:bg-surface-tonal-a20/50 px-3 py-2 text-sm text-gray-900 dark:text-primary-a0 placeholder:text-gray-400 focus:border-gray-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700 font-mono tabular-nums" required>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                                            Max: {{ $order->currency }} {{ number_format($order->total_amount - $order->refunded_amount, 2) }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Reason</label>
                                        <input type="text" name="reason" placeholder="e.g. Customer request, Damaged item…"
                                               class="block w-full rounded-md border border-gray-200 dark:border-surface-tonal-a30 bg-transparent dark:bg-surface-tonal-a20/50 px-3 py-2 text-sm text-gray-900 dark:text-primary-a0 placeholder:text-gray-400 focus:border-gray-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700">
                                    </div>
                                </div>
                                <button type="submit"
                                        onclick="return confirm('Confirm refund? This cannot be undone.')"
                                        class="inline-flex items-center gap-2 rounded-md bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    Process Refund
                                </button>
                            </form>
                        </div>
                    @endif

                </div>{{-- /main --}}

                {{-- ╔══════════════════════════════════╗
                     ║  SIDEBAR (1/3)                   ║
                     ╚══════════════════════════════════╝ --}}
                <div class="space-y-5">

                    {{-- Update Status --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm p-5">
                        <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Update Status</h3>
                        <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                    class="block w-full rounded-md border border-gray-200 dark:border-surface-tonal-a30 bg-transparent dark:bg-surface-tonal-a20/50 px-3 py-2 text-sm text-gray-900 dark:text-primary-a0 focus:border-gray-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700">
                                @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'] as $st)
                                    <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="w-full rounded-md bg-gray-900 dark:bg-white hover:bg-gray-700 dark:hover:bg-gray-100 px-4 py-2 text-sm font-medium text-white dark:text-gray-900 shadow-sm transition-colors">
                                Save Status
                            </button>
                        </form>
                    </div>

                    {{-- Customer Information --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm p-5">
                        <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Customer</h3>
                        <div class="space-y-3">
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Name</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-primary-a0 flex items-center gap-2">
                                    {{ $order->customer_name }}
                                    @if($order->customer_id)
                                        <a href="{{ route('customers.show', $order->customer_id) }}"
                                           class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 underline underline-offset-2 transition-colors">
                                            View
                                        </a>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Email</dt>
                                <dd class="text-sm font-medium">
                                    <a href="mailto:{{ $order->customer_email }}"
                                       class="text-gray-900 dark:text-primary-a0 hover:text-gray-600 dark:hover:text-gray-300 underline underline-offset-2 transition-colors truncate block">
                                        {{ $order->customer_email }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Phone</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-primary-a0">{{ $order->customer_phone ?? '—' }}</dd>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Address --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm p-5">
                        <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Shipping Address</h3>
                        @if($order->shippingAddress)
                            <address class="not-italic text-sm text-gray-700 dark:text-gray-300 space-y-1 leading-relaxed">
                                <p class="font-semibold text-gray-900 dark:text-primary-a0">
                                    {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}
                                </p>
                                <p>{{ $order->shippingAddress->address_line_1 }}</p>
                                @if($order->shippingAddress->address_line_2)
                                    <p>{{ $order->shippingAddress->address_line_2 }}</p>
                                @endif
                                <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                                <p>{{ $order->shippingAddress->country }}</p>
                                @if($order->shippingAddress->phone)
                                    <p class="pt-1 text-gray-500 dark:text-gray-400">
                                        <span class="text-xs">Phone:</span> {{ $order->shippingAddress->phone }}
                                    </p>
                                @endif
                            </address>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500 italic">No shipping address provided.</p>
                        @endif
                    </div>

                    {{-- Internal Notes --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm p-5">
                        <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Internal Notes</h3>
                        <form action="{{ route('orders.notes.add', $order->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <textarea name="internal_notes" rows="4"
                                      placeholder="Add private notes about this order…"
                                      class="block w-full rounded-md border border-gray-200 dark:border-surface-tonal-a30 bg-transparent dark:bg-surface-tonal-a20/50 px-3 py-2 text-sm text-gray-900 dark:text-primary-a0 placeholder:text-gray-400 focus:border-gray-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700 resize-none">{{ $order->internal_notes }}</textarea>
                            <button type="submit"
                                    class="w-full rounded-md border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                                Update Notes
                            </button>
                        </form>
                    </div>

                </div>{{-- /sidebar --}}
            </div>
        </div>
@endsection
