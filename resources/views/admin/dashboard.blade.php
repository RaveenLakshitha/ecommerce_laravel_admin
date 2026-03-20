@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-surface-tonal-a10">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-20 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-primary-a0">Dashboard</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Welcome back. Here's what's happening with your store today.</p>
                </div>
                <a href="{{ route('orders.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-gray-900 dark:bg-accent text-white dark:text-gray-900 text-sm font-medium hover:bg-gray-800 dark:hover:bg-accent-dim transition-colors shrink-0">
                    View All Orders
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">

                {{-- Today's Sales --}}
                <div class="col-span-1 rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Today's Sales</span>
                        <div class="w-7 h-7 rounded-md bg-blue-50 dark:bg-blue-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0 tabular-nums">{{ $currency_code ?? '$' }}{{ number_format($todaysSales, 2) }}</p>
                </div>

                {{-- Orders Today --}}
                <div class="col-span-1 rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Orders Today</span>
                        <div class="w-7 h-7 rounded-md bg-emerald-50 dark:bg-emerald-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0 tabular-nums">{{ number_format($ordersTodayCount) }}</p>
                </div>

                {{-- Pending Orders --}}
                <div class="col-span-1 rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending</span>
                        <div class="w-7 h-7 rounded-md bg-amber-50 dark:bg-amber-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0 tabular-nums">{{ number_format($pendingOrdersCount) }}</p>
                </div>

                {{-- Low Stock --}}
                <div class="col-span-1 rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Low Stock</span>
                        <div class="w-7 h-7 rounded-md bg-red-50 dark:bg-red-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0 tabular-nums">{{ number_format($lowStockCount) }}</p>
                </div>

                {{-- COD Pending --}}
                <div class="col-span-1 rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">COD Pending</span>
                        <div class="w-7 h-7 rounded-md bg-violet-50 dark:bg-violet-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0 tabular-nums">{{ number_format($codPendingCount) }}</p>
                </div>

                {{-- Monthly Revenue --}}
                <div class="col-span-1 rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Monthly Rev.</span>
                        <div class="w-7 h-7 rounded-md bg-teal-50 dark:bg-teal-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0 tabular-nums">{{ $currency_code ?? '$' }}{{ number_format($thisMonthRevenue, 0) }}</p>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- Left: Main Column --}}
                <div class="xl:col-span-2 space-y-6">

                    {{-- Recent Orders Table --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20">
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-primary-a0">Recent Orders</h2>
                            <a href="{{ route('orders.index') }}" class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-primary-a0 transition-colors">View all →</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-surface-tonal-a20 bg-gray-50 dark:bg-surface-tonal-a20">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-surface-tonal-a20">
                                    @forelse($recentOrders as $order)
                                        <tr class="hover:bg-gray-50/60 dark:hover:bg-surface-tonal-a20/40 transition-colors">
                                            <td class="px-6 py-3.5">
                                                <a href="{{ route('orders.show', $order) }}" class="font-medium text-gray-900 dark:text-primary-a0 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-sm">
                                                    #{{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-3.5">
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $order->customer_name }}</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-6 py-3.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium
                                                    @if($order->status === 'pending') bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-950/50 dark:text-amber-400 dark:ring-amber-800
                                                    @elseif($order->status === 'processing') bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-950/50 dark:text-blue-400 dark:ring-blue-800
                                                    @elseif($order->status === 'shipped') bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-400 dark:ring-indigo-800
                                                    @elseif($order->status === 'delivered') bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-800
                                                    @elseif($order->status === 'cancelled') bg-red-50 text-red-700 ring-1 ring-red-200 dark:bg-red-950/50 dark:text-red-400 dark:ring-red-800
                                                    @else bg-gray-100 text-gray-600 ring-1 ring-gray-200 dark:bg-surface-tonal-a20 dark:text-gray-400 dark:ring-surface-tonal-a30 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">{{ $order->payment_method }}</span>
                                                    @if($order->payment_status === 'paid')
                                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-3.5 text-sm font-semibold text-gray-900 dark:text-primary-a0 tabular-nums">
                                                {{ $order->currency ?? '$' }}{{ number_format($order->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-3.5">
                                                <div class="flex items-center justify-end gap-1">
                                                    @if($order->status === 'pending')
                                                        <form action="{{ route('orders.update-status', $order) }}" method="POST" class="inline">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="processing">
                                                            <button type="submit" title="Accept Order"
                                                                class="p-1.5 rounded-md text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('orders.invoice', $order) }}" target="_blank" title="Print Slip"
                                                        class="p-1.5 rounded-md text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-surface-tonal-a20 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                    </a>
                                                    <a href="{{ route('orders.show', $order) }}" title="View Details"
                                                        class="p-1.5 rounded-md text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-surface-tonal-a20 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                                No recent orders found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Low Stock Alerts --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-primary-a0">Low Stock Alerts</h2>
                                @if($lowStockCount > 0)
                                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400">{{ $lowStockCount }}</span>
                                @endif
                            </div>
                            <a href="{{ route('inventory.index') }}" class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-primary-a0 transition-colors">Manage Inventory →</a>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-surface-tonal-a20">
                            @forelse($lowStockAlerts as $variant)
                                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/60 dark:hover:bg-surface-tonal-a20/40 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-surface-tonal-a20 flex items-center justify-center shrink-0 overflow-hidden">
                                            @if($variant->product->primaryImage)
                                                <img src="{{ asset('storage/' . $variant->product->primaryImage->file_path) }}" alt="{{ $variant->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-primary-a0 truncate">
                                                {{ $variant->product->name }}
                                                @if($variant->sku && $variant->sku !== $variant->product->slug)
                                                    <span class="text-xs text-gray-400 font-normal ml-1">{{ $variant->sku }}</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-red-500 dark:text-red-400 mt-0.5">
                                                {{ $variant->stock_quantity }} remaining · threshold {{ $variant->low_stock_threshold ?? 5 }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('products.edit', $variant->product_id) }}"
                                       class="ml-4 shrink-0 px-3 py-1.5 text-xs font-medium rounded-md border border-gray-200 dark:border-surface-tonal-a30 text-gray-700 dark:text-gray-300 bg-white dark:bg-surface-tonal-a0 hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                        Restock
                                    </a>
                                </div>
                            @empty
                                <div class="px-6 py-10 text-center">
                                    <svg class="w-8 h-8 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">All inventory levels are healthy.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="space-y-6">

                    {{-- Quick Actions --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20">
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-primary-a0">Quick Actions</h2>
                        </div>
                        <div class="p-3 space-y-1">
                            <a href="{{ route('products.create') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors group">
                                <div class="w-8 h-8 rounded-md bg-gray-100 dark:bg-surface-tonal-a20 group-hover:bg-gray-200 dark:group-hover:bg-gray-700 flex items-center justify-center transition-colors shrink-0">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                Add New Product
                                <svg class="w-4 h-4 ml-auto text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            <a href="{{ route('coupons.create') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors group">
                                <div class="w-8 h-8 rounded-md bg-gray-100 dark:bg-surface-tonal-a20 group-hover:bg-gray-200 dark:group-hover:bg-gray-700 flex items-center justify-center transition-colors shrink-0">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                </div>
                                Create Coupon
                                <svg class="w-4 h-4 ml-auto text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            <a href="{{ route('shipping.shipments.datatable') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors group">
                                <div class="w-8 h-8 rounded-md bg-gray-100 dark:bg-surface-tonal-a20 group-hover:bg-gray-200 dark:group-hover:bg-gray-700 flex items-center justify-center transition-colors shrink-0">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </div>
                                Manage Shipping
                                <svg class="w-4 h-4 ml-auto text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            <a href="{{ route('customers.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors group">
                                <div class="w-8 h-8 rounded-md bg-gray-100 dark:bg-surface-tonal-a20 group-hover:bg-gray-200 dark:group-hover:bg-gray-700 flex items-center justify-center transition-colors shrink-0">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                View All Customers
                                <svg class="w-4 h-4 ml-auto text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Pending Tasks --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20">
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-primary-a0">Pending Tasks</h2>
                        </div>
                        <div class="p-4 space-y-3">

                            <div class="flex items-center justify-between p-3.5 rounded-lg bg-gray-50 dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a20">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-950/60 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-primary-a0">New Returns</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Awaiting processing</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold tabular-nums text-gray-900 dark:text-primary-a0 bg-white dark:bg-surface-tonal-a0 px-2.5 py-1 rounded-md border border-gray-200 dark:border-surface-tonal-a30 shadow-sm">{{ $pendingReturns }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3.5 rounded-lg bg-gray-50 dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a20">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-950/60 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-primary-a0">Ready to Ship</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Shipments for courier</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold tabular-nums text-gray-900 dark:text-primary-a0 bg-white dark:bg-surface-tonal-a0 px-2.5 py-1 rounded-md border border-gray-200 dark:border-surface-tonal-a30 shadow-sm">{{ $pendingShipments }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3.5 rounded-lg bg-gray-50 dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a20">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-950/60 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-primary-a0">New Reviews</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Need moderation</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold tabular-nums text-gray-900 dark:text-primary-a0 bg-white dark:bg-surface-tonal-a0 px-2.5 py-1 rounded-md border border-gray-200 dark:border-surface-tonal-a30 shadow-sm">{{ $pendingReviews }}</span>
                            </div>

                        </div>
                    </div>

                    {{-- Top Selling Products --}}
                    <div class="rounded-xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a0 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20">
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-primary-a0">Top Selling Products</h2>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 px-2 py-1 bg-gray-100 dark:bg-surface-tonal-a20 rounded-md">This Week</span>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse($topSellingProducts as $index => $product)
                                <div class="flex items-center gap-3 group">
                                    <span class="text-xs font-bold tabular-nums w-5 text-center shrink-0
                                        @if($index === 0) text-amber-500
                                        @elseif($index === 1) text-gray-400
                                        @elseif($index === 2) text-orange-400
                                        @else text-gray-300 dark:text-gray-600 @endif">
                                        #{{ $index + 1 }}
                                    </span>

                                    <div class="w-9 h-9 rounded-lg border border-gray-100 dark:border-surface-tonal-a20 bg-gray-50 dark:bg-surface-tonal-a20 flex items-center justify-center shrink-0 overflow-hidden">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->file_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate block group-hover:text-gray-600 dark:group-hover:text-gray-400 transition-colors">
                                            {{ $product->name }}
                                        </a>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $product->order_items_count }} sales</p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-6 text-center text-sm text-gray-400 dark:text-gray-500">
                                    No sales data available yet.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection