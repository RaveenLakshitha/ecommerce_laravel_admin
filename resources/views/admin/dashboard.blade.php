@extends('layouts.app')

@section('title', __('file.dashboard'))

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-10 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- ── PAGE HEADER ────────────────────────────────────────────────────── --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('file.dashboard') }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('file.dashboard_overview') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('file.live') }}
                    </div>
                    <a href="{{ route('orders.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold hover:bg-black dark:hover:bg-gray-100 transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.view_all_orders') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- ── KPI CARDS ───────────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">

                {{-- Today's Sales --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.todays_sales') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums">@price($todaysSales)
                    </p>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('file.revenue_today') }}</span>
                </div>

                {{-- Orders Today --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.orders_today') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums">
                        {{ number_format($ordersTodayCount) }}
                    </p>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">{{ __('file.active_fulfillment') }}</span>
                </div>

                {{-- Pending Orders --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.pending_orders_card') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums">
                        {{ number_format($pendingOrdersCount) }}
                    </p>
                    <div class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">{{ __('file.awaiting_process') }}</span>
                    </div>
                </div>

                {{-- Low Stock --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.low_stock_items') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums">
                        {{ number_format($lowStockCount) }}
                    </p>
                    <span class="text-xs text-rose-600 dark:text-rose-400 font-semibold">{{ __('file.needs_restocking') }}</span>
                </div>

                {{-- COD Pending --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.cod_pending') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center text-violet-600 dark:text-violet-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums">
                        {{ number_format($codPendingCount) }}
                    </p>
                    <span class="text-xs text-violet-600 dark:text-violet-400 font-semibold">{{ __('file.cash_on_delivery') }}</span>
                </div>

                {{-- Monthly Revenue --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.monthly_revenue') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums">@price($thisMonthRevenue)</p>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('file.this_month') }}</span>
                </div>

            </div>

            {{-- ── MAIN GRID ───────────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

                {{-- LEFT: Recent Orders + Low Stock + Top Products --}}
                <div class="xl:col-span-8 space-y-6">

                    {{-- Recent Orders Table --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <div>
                                <h2 class="font-semibold text-gray-900 dark:text-white">{{ __('file.recent_orders') }}</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.latest_orders_note') }}</p>
                            </div>
                            <a href="{{ route('orders.index') }}"
                                class="flex items-center gap-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                {{ __('file.view_all') }}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class="bg-gray-100/50 dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.order_num_alias') }}</th>
                                        <th
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.customer') }}</th>
                                        <th
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.status') }}</th>
                                        <th
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.payment') }}</th>
                                        <th
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                            {{ __('file.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($recentOrders as $order)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                            <td class="px-6 py-4">
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4 text-sm">
                                                    #{{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</span>
                                                    <span
                                                        class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-100 dark:border-amber-500/20',
                                                        'processing' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border-blue-100 dark:border-blue-500/20',
                                                        'shipped' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border-indigo-100 dark:border-indigo-500/20',
                                                        'delivered' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
                                                        'cancelled' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border-rose-100 dark:border-rose-500/20',
                                                    ];
                                                    $statusClass = $statusColors[$order->status] ?? 'bg-gray-50 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400 border-gray-100 dark:border-gray-500/20';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold {{ $statusClass }}">
                                                    {{ __('file.' . $order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-1.5">
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">{{ __('file.' . strtolower($order->payment_method)) }}</span>
                                                    @if($order->payment_status === 'paid')
                                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">@price($order->total_amount)</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                                {{ __('file.no_orders_found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Low Stock + Top Products --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Low Stock Items --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <div>
                                    <h2 class="font-semibold text-gray-900 dark:text-white">{{ __('file.low_stock_alerts') }}</h2>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.products_needing_restock') }}</p>
                                </div>
                                @if($lowStockCount > 0)
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 text-xs font-semibold border border-rose-100 dark:border-rose-500/20">
                                        {{ __('file.items_count', ['count' => $lowStockCount]) }}
                                    </span>
                                @endif
                            </div>
                            <div class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                @forelse($lowStockAlerts as $variant)
                                    <div
                                        class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-surface-tonal-a30 border border-gray-200 dark:border-surface-tonal-a40 flex items-center justify-center shrink-0 overflow-hidden">
                                                @if($variant->product->primaryImage)
                                                    <img src="{{ asset('storage/' . $variant->product->primaryImage->file_path) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $variant->product->name }}
                                                </p>
                                                <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-0.5">
                                                    {{ $variant->stock_quantity }} {{ __('file.left_threshold') }}
                                                    {{ $variant->low_stock_threshold ?? 5 }}
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ route('products.edit', $variant->product_id) }}"
                                            class="px-3 py-1.5 rounded-lg bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-semibold hover:bg-black dark:hover:bg-gray-100 transition-all opacity-0 group-hover:opacity-100 shrink-0">
                                            {{ __('file.restock') }}
                                        </a>
                                    </div>
                                @empty
                                    <div class="px-6 py-10 text-center">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 flex items-center justify-center mx-auto mb-3 text-emerald-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('file.inventory_levels_healthy') }}
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Top Selling Products --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <div>
                                    <h2 class="font-semibold text-gray-900 dark:text-white">{{ __('file.top_selling_products') }}</h2>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.best_performers_period') }}
                                    </p>
                                </div>
                                <span
                                    class="px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 text-xs font-semibold border border-indigo-100 dark:border-indigo-500/20">
                                    {{ __('file.top_5') }}
                                </span>
                            </div>
                            <div class="p-6 space-y-4">
                                @forelse($topSellingProducts as $index => $product)
                                    <div class="flex items-center gap-4 group">
                                        <div
                                            class="w-6 h-6 rounded-lg bg-gray-50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a40 flex items-center justify-center shrink-0">
                                            <span
                                                class="text-xs font-bold tabular-nums
                                                                    {{ $index === 0 ? 'text-amber-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-orange-400' : 'text-gray-400')) }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </div>
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-surface-tonal-a30 border border-gray-200 dark:border-surface-tonal-a30 flex items-center justify-center shrink-0 overflow-hidden transition-transform group-hover:scale-105">
                                            @if($product->primaryImage)
                                                <img src="{{ asset('storage/' . $product->primaryImage->file_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-sm font-semibold text-gray-800 dark:text-gray-200 block truncate hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                {{ $product->name }}
                                            </a>
                                            <div class="flex items-center gap-3 mt-1">
                                                    {{ __('file.orders_count', ['count' => $product->order_items_count]) }}</span>
                                                <div
                                                    class="flex-1 h-1 bg-gray-100 dark:bg-surface-tonal-a30 rounded-full overflow-hidden">
                                                    <div class="h-full bg-indigo-500/50 rounded-full"
                                                        style="width: {{ min(100, $product->order_items_count * 5) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center text-sm text-gray-400 dark:text-gray-500">{{ __('file.no_sales_data_available') }}</div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

                {{-- RIGHT: Quick Actions + Pending Tasks --}}
                <div class="xl:col-span-4 space-y-6">

                    {{-- Quick Actions --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="font-semibold text-gray-900 dark:text-white">{{ __('file.quick_actions') }}</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.common_tasks_glance') }}</p>
                        </div>
                        <div class="p-5 grid grid-cols-2 gap-3">
                            <a href="{{ route('products.create') }}"
                                class="flex flex-col items-start gap-3 p-4 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all group">
                                <div
                                    class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <span
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors leading-tight">{{ __('file.add_product') }}</span>
                            </a>
                            <a href="{{ route('coupons.create') }}"
                                class="flex flex-col items-start gap-3 p-4 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/50 hover:bg-amber-50 dark:hover:bg-amber-500/10 hover:border-amber-200 dark:hover:border-amber-500/30 transition-all group">
                                <div
                                    class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-amber-700 dark:group-hover:text-amber-300 transition-colors leading-tight">{{ __('file.add_coupon') }}</span>
                            </a>
                            <a href="{{ route('shipping.shipments.datatable') }}"
                                class="flex flex-col items-start gap-3 p-4 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/50 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:border-blue-200 dark:hover:border-blue-500/30 transition-all group">
                                <div
                                    class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <span
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors leading-tight">{{ __('file.manage_shipments') }}</span>
                            </a>
                            <a href="{{ route('customers.index') }}"
                                class="flex flex-col items-start gap-3 p-4 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/50 hover:bg-violet-50 dark:hover:bg-violet-500/10 hover:border-violet-200 dark:hover:border-violet-500/30 transition-all group">
                                <div
                                    class="w-9 h-9 rounded-xl bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center text-violet-600 dark:text-violet-400 transition-transform group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-violet-700 dark:group-hover:text-violet-300 transition-colors leading-tight">{{ __('file.crm') }}</span>
                            </a>
                        </div>
                    </div>

                    {{-- Pending Tasks --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="font-semibold text-gray-900 dark:text-white">{{ __('file.pending_tasks') }}</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.items_requiring_attention') }}</p>
                        </div>
                        <div class="p-4 space-y-3">

                            {{-- Pending Returns --}}
                            <div
                                class="flex items-center gap-4 p-4 rounded-xl bg-orange-50/50 dark:bg-orange-500/5 border border-orange-100 dark:border-orange-500/20 hover:bg-orange-50 dark:hover:bg-orange-500/10 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white dark:bg-surface-tonal-a20 border border-orange-100 dark:border-orange-500/30 flex items-center justify-center text-orange-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4 2 4-2 4 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('file.pending_returns') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.awaiting_approval') }}</p>
                                </div>
                                <span
                                    class="w-9 h-9 rounded-xl bg-white dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 flex items-center justify-center text-sm font-bold text-orange-600 dark:text-orange-400 tabular-nums shrink-0">
                                    {{ $pendingReturns }}
                                </span>
                            </div>

                            {{-- Pending Shipments --}}
                            <div
                                class="flex items-center gap-4 p-4 rounded-xl bg-blue-50/50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/20 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white dark:bg-surface-tonal-a20 border border-blue-100 dark:border-blue-500/30 flex items-center justify-center text-blue-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('file.courier_dispatch') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.awaiting_shipment') }}</p>
                                </div>
                                <span
                                    class="w-9 h-9 rounded-xl bg-white dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 flex items-center justify-center text-sm font-bold text-blue-600 dark:text-blue-400 tabular-nums shrink-0">
                                    {{ $pendingShipments }}
                                </span>
                            </div>

                            {{-- Pending Reviews --}}
                            <div
                                class="flex items-center gap-4 p-4 rounded-xl bg-violet-50/50 dark:bg-violet-500/5 border border-violet-100 dark:border-violet-500/20 hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white dark:bg-surface-tonal-a20 border border-violet-100 dark:border-violet-500/30 flex items-center justify-center text-violet-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('file.reviews_to_moderate') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.pending_moderation') }}</p>
                                </div>
                                <span
                                    class="w-9 h-9 rounded-xl bg-white dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 flex items-center justify-center text-sm font-bold text-violet-600 dark:text-violet-400 tabular-nums shrink-0">
                                    {{ $pendingReviews }}
                                </span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection