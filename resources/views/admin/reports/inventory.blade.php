@extends('layouts.app')

@section('title', __('file.inventory_reports'))

@section('content')
    <div class="admin-page">
        <div class="admin-page-inner">

            {{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
            <div class="admin-page-header">
                <div>
                    <nav class="admin-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">{{ __('file.dashboard') }}</a>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="active">{{ __('file.inventory_reports') }}</span>
                    </nav>
                    <h1 class="admin-page-title">{{ __('file.inventory_reports') }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.inventory_report_subtitle') }}</p>
                </div>
                <a href="{{ route('inventory.index') }}" class="admin-btn-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    {{ __('file.manage_inventory') }}
                </a>
            </div>

            {{-- ── KPI CARDS ──────────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">

                {{-- Total Variants --}}
                <div class="admin-card p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.total_variants') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-gray-50 dark:bg-surface-tonal-a30 flex items-center justify-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-gray-900 dark:text-white tabular-nums">
                        {{ number_format($totalVariants) }}</p>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('file.across_all_products') }}</span>
                </div>

                {{-- In Stock --}}
                <div class="admin-card p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.in_stock') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">
                        {{ number_format($inStockCount) }}</p>
                    <span
                        class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">{{ __('file.healthy_stock') }}</span>
                </div>

                {{-- Low Stock --}}
                <div class="admin-card p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.low_stock') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400 tabular-nums">
                        {{ number_format($lowStockCount) }}</p>
                    <span
                        class="text-xs text-amber-600 dark:text-amber-400 font-semibold">{{ __('file.needs_attention') }}</span>
                </div>

                {{-- Out of Stock --}}
                <div class="admin-card p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.out_of_stock') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-rose-600 dark:text-rose-400 tabular-nums">
                        {{ number_format($outOfStockCount) }}</p>
                    <span
                        class="text-xs text-rose-600 dark:text-rose-400 font-semibold">{{ __('file.needs_restocking') }}</span>
                </div>

                {{-- Inventory Value --}}
                <div class="admin-card p-5 flex flex-col gap-3 hover:shadow-md transition-shadow col-span-2 xl:col-span-1">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ __('file.inventory_value') }}</span>
                        <div
                            class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-gray-900 dark:text-white tabular-nums">
                        ${{ number_format($totalInventoryValue, 0) }}</p>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('file.total_stock_value') }}</span>
                </div>
            </div>

            {{-- ── MAIN GRID ──────────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

                {{-- LEFT: Low Stock + Out of Stock --}}
                <div class="xl:col-span-8 space-y-6">

                    {{-- Low Stock Items --}}
                    <div class="admin-card overflow-hidden">
                        <div class="admin-card-header flex items-center justify-between">
                            <div>
                                <h2>{{ __('file.low_stock_alerts') }}</h2>
                                <p>{{ __('file.products_needing_restock') }}</p>
                            </div>
                            @if($lowStockCount > 0)
                                <span class="admin-badge admin-badge-warning">{{ $lowStockCount }} {{ __('file.items') }}</span>
                            @endif
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class="bg-gray-100/100 dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.product') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.sku') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                                            {{ __('file.stock') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                                            {{ __('file.threshold') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($lowStockItems as $variant)
                                        <tr class="hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors group">
                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 border border-gray-200 dark:border-surface-tonal-a40 flex items-center justify-center overflow-hidden shrink-0">
                                                        @if($variant->product?->primaryImage)
                                                            <img src="{{ asset('storage/' . $variant->product->primaryImage->file_path) }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $variant->product?->name ?? 'Deleted Product' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3.5 text-xs font-mono text-gray-500 dark:text-gray-400">
                                                {{ $variant->sku ?? '—' }}</td>
                                            <td class="px-5 py-3.5 text-center">
                                                <span
                                                    class="admin-badge admin-badge-warning">{{ $variant->stock_quantity }}</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-center text-xs text-gray-500 dark:text-gray-400">
                                                {{ $variant->low_stock_threshold ?? 5 }}</td>
                                            <td class="px-5 py-3.5 text-right">
                                                <a href="{{ route('products.edit', $variant->product_id) }}"
                                                    class="px-3 py-1.5 rounded-lg bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all">
                                                    {{ __('file.restock') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-10 text-center">
                                                <div class="flex flex-col items-center gap-2">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm text-gray-400 dark:text-gray-500">
                                                        {{ __('file.inventory_levels_healthy') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Out of Stock Items --}}
                    <div class="admin-card overflow-hidden">
                        <div class="admin-card-header flex items-center justify-between">
                            <div>
                                <h2>{{ __('file.out_of_stock') }}</h2>
                                <p>{{ __('file.items_needing_immediate_action') }}</p>
                            </div>
                            @if($outOfStockCount > 0)
                                <span class="admin-badge admin-badge-danger">{{ $outOfStockCount }}
                                    {{ __('file.items') }}</span>
                            @endif
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class="bg-gray-100/100 dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.product') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.sku') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.price') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($outOfStockItems as $variant)
                                        <tr class="hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors group">
                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 border border-gray-200 dark:border-surface-tonal-a40 flex items-center justify-center overflow-hidden shrink-0">
                                                        @if($variant->product?->primaryImage)
                                                            <img src="{{ asset('storage/' . $variant->product->primaryImage->file_path) }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $variant->product?->name ?? 'Deleted Product' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3.5 text-xs font-mono text-gray-500 dark:text-gray-400">
                                                {{ $variant->sku ?? '—' }}</td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                ${{ number_format($variant->price, 2) }}</td>
                                            <td class="px-5 py-3.5 text-right">
                                                <a href="{{ route('products.edit', $variant->product_id) }}"
                                                    class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all">
                                                    {{ __('file.restock') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                                {{ __('file.all_items_in_stock') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: Stock distribution + Top stocked --}}
                <div class="xl:col-span-4 space-y-6">

                    {{-- Stock Distribution Chart --}}
                    <div class="admin-card p-6">
                        <h2 class="text-sm font-black text-black dark:text-white uppercase tracking-wider mb-1">
                            {{ __('file.stock_distribution') }}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">{{ __('file.inventory_status_overview') }}
                        </p>
                        <canvas id="stockChart" height="160"></canvas>
                        <div class="mt-5 space-y-2.5">
                            @php
                                $dist = [
                                    ['label' => __('file.in_stock'), 'val' => $stockDistribution['in_stock'], 'color' => 'bg-emerald-500'],
                                    ['label' => __('file.low_stock'), 'val' => $stockDistribution['low_stock'], 'color' => 'bg-amber-500'],
                                    ['label' => __('file.out_of_stock'), 'val' => $stockDistribution['out_stock'], 'color' => 'bg-rose-500'],
                                ];
                            @endphp
                            @foreach($dist as $d)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $d['color'] }} shrink-0"></span>
                                        <span
                                            class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $d['label'] }}</span>
                                    </div>
                                    <span
                                        class="text-xs font-bold text-gray-900 dark:text-white tabular-nums">{{ number_format($d['val']) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Top Stocked Items --}}
                    <div class="admin-card p-6">
                        <h2 class="text-sm font-black text-black dark:text-white uppercase tracking-wider mb-1">
                            {{ __('file.top_stocked') }}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">{{ __('file.highest_inventory_items') }}
                        </p>
                        <div class="space-y-3">
                            @forelse($topStockedItems as $variant)
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 border border-gray-200 dark:border-surface-tonal-a40 flex items-center justify-center overflow-hidden shrink-0">
                                        @if($variant->product?->primaryImage)
                                            <img src="{{ asset('storage/' . $variant->product->primaryImage->file_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">
                                            {{ $variant->product?->name ?? 'Deleted Product' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <div
                                                class="flex-1 h-1.5 bg-gray-100 dark:bg-surface-tonal-a30 rounded-full overflow-hidden">
                                                @php $maxStock = $topStockedItems->first()->stock_quantity ?: 1; @endphp
                                                <div class="h-full bg-indigo-500/60 rounded-full"
                                                    style="width:{{ min(100, ($variant->stock_quantity / $maxStock) * 100) }}%">
                                                </div>
                                            </div>
                                            <span
                                                class="text-xs font-bold text-gray-900 dark:text-white tabular-nums w-8 text-right">{{ $variant->stock_quantity }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">
                                    {{ __('file.no_items_found') }}</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const ctx = document.getElementById('stockChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ __("file.in_stock") }}', '{{ __("file.low_stock") }}', '{{ __("file.out_of_stock") }}'],
                    datasets: [{
                        data: [{{ $stockDistribution['in_stock'] }}, {{ $stockDistribution['low_stock'] }}, {{ $stockDistribution['out_stock'] }}],
                        backgroundColor: isDark
                            ? ['rgba(52,211,153,0.7)', 'rgba(251,191,36,0.7)', 'rgba(248,113,113,0.7)']
                            : ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.raw} variants`
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush