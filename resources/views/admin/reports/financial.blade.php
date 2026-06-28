@extends('layouts.app')

@section('title', __('file.financial_reports'))

@section('content')
    <div class="admin-page">
        <div class="admin-page-inner">

            
            <div class="admin-page-header">
                <div>
                    <nav class="admin-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">{{ __('file.dashboard') }}</a>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="active">{{ __('file.financial_reports') }}</span>
                    </nav>
                    <h1 class="admin-page-title">{{ __('file.financial_reports') }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.financial_report_subtitle') }}</p>
                </div>

                
                <form method="GET" action="{{ route('reports.financial') }}" class="flex flex-wrap items-end gap-2">
                    <div class="flex flex-col gap-1">
                        <label class="fi-label">{{ __('file.from') }}</label>
                        <input type="date" name="from" class="fi" value="{{ $from->toDateString() }}">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="fi-label">{{ __('file.to') }}</label>
                        <input type="date" name="to" class="fi" value="{{ $to->toDateString() }}">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="admin-btn-accent">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                            </svg>
                            {{ __('file.apply') }}
                        </button>
                        
                        @foreach([['7', '7d'], ['30', '30d'], ['90', '90d']] as [$v, $l])
                            <a href="{{ route('reports.financial', ['range' => $v]) }}"
                                class="px-3 py-2 rounded-xl border text-xs font-bold transition-all
                                      {{ $range == $v ? 'bg-gray-900 dark:bg-white text-white dark:text-gray-900 border-transparent' : 'border-gray-200 dark:border-surface-tonal-a30 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30' }}">
                                {{ $l }}
                            </a>
                        @endforeach
                    </div>
                </form>
            </div>

            
            <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

                @php
                    $kpis = [
                        ['label' => __('file.total_revenue'), 'value' => number_format($totalRevenue, 2), 'prefix' => true, 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald'],
                        ['label' => __('file.total_orders'), 'value' => number_format($totalOrders), 'prefix' => false, 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'color' => 'blue'],
                        ['label' => __('file.total_refunds'), 'value' => number_format($totalRefunds, 2), 'prefix' => true, 'icon' => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', 'color' => 'rose'],
                        ['label' => __('file.net_revenue'), 'value' => number_format($netRevenue, 2), 'prefix' => true, 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'indigo'],
                    ];
                    $colorMap = [
                        'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                        'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400'],
                        'rose' => ['bg' => 'bg-rose-50 dark:bg-rose-500/10', 'text' => 'text-rose-600 dark:text-rose-400'],
                        'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400'],
                    ];
                @endphp

                @foreach($kpis as $kpi)
                    @php $c = $colorMap[$kpi['color']]; @endphp
                    <div class="admin-card p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</span>
                            <div class="w-8 h-8 rounded-xl {{ $c['bg'] }} flex items-center justify-center {{ $c['text'] }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $kpi['icon'] }}" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-gray-900 dark:text-white tabular-nums">
                            {{ $kpi['prefix'] ? '$' : '' }}{{ $kpi['value'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

                
                <div class="xl:col-span-8 space-y-6">

                    
                    <div class="admin-card">
                        <div class="admin-card-header flex items-center justify-between">
                            <div>
                                <h2>{{ __('file.daily_revenue') }}</h2>
                                <p>{{ $from->format('M d') }} – {{ $to->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <canvas id="revenueChart" height="80"></canvas>
                        </div>
                    </div>

                    
                    <div class="admin-card overflow-hidden">
                        <div class="admin-card-header flex items-center justify-between">
                            <div>
                                <h2>{{ __('file.recent_transactions') }}</h2>
                                <p>{{ __('file.latest_payment_activity') }}</p>
                            </div>
                            <a href="{{ route('transactions.index') }}"
                                class="admin-btn-outline text-xs">{{ __('file.view_all') }}</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class=" dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.order_num_alias') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.customer') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.method') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.status') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                            {{ __('file.amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($recentTransactions as $tx)
                                        <tr class="hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors">
                                            <td class="px-5 py-3.5">
                                                <a href="{{ route('orders.show', $tx->order_id) }}"
                                                    class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">#{{ $tx->order->order_number ?? $tx->order_id }}</a>
                                            </td>
                                            <td class="px-5 py-3.5 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $tx->order->customer_name ?? '—' }}</td>
                                            <td
                                                class="px-5 py-3.5 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                {{ $tx->method ?? '—' }}</td>
                                            <td class="px-5 py-3.5">
                                                @php $isPaid = $tx->status === 'paid'; @endphp
                                                <span
                                                    class="admin-badge {{ $isPaid ? 'admin-badge-success' : 'admin-badge-warning' }}">
                                                    {{ $tx->status }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-5 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                ${{ number_format($tx->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                                {{ __('file.no_items_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="admin-card overflow-hidden">
                        <div class="admin-card-header flex items-center justify-between">
                            <div>
                                <h2>{{ __('file.refund_history') }}</h2>
                                <p>{{ __('file.approved_refunds_period') }}</p>
                            </div>
                            <a href="{{ route('refunds.index') }}"
                                class="admin-btn-outline text-xs">{{ __('file.view_all') }}</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class=" dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.order_num_alias') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.reason') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('file.status') }}</th>
                                        <th
                                            class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                            {{ __('file.amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($recentRefunds as $refund)
                                        <tr class="hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors">
                                            <td class="px-5 py-3.5">
                                                <a href="{{ route('refunds.show', $refund) }}"
                                                    class="text-sm font-semibold text-rose-600 dark:text-rose-400 hover:underline">#{{ $refund->order->order_number ?? $refund->order_id }}</a>
                                            </td>
                                            <td class="px-5 py-3.5 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $refund->reason ?? '—' }}</td>
                                            <td class="px-5 py-3.5">
                                                <span
                                                    class="admin-badge {{ $refund->status === 'approved' ? 'admin-badge-success' : ($refund->status === 'rejected' ? 'admin-badge-danger' : 'admin-badge-warning') }}">
                                                    {{ $refund->status }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-5 py-3.5 text-right text-sm font-semibold text-rose-600 dark:text-rose-400">
                                                -${{ number_format($refund->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                                {{ __('file.no_items_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                
                <div class="xl:col-span-4 space-y-6">

                    
                    <div class="admin-card p-6">
                        <h2 class="text-sm font-black text-black dark:text-white uppercase tracking-wider mb-1">
                            {{ __('file.revenue_by_method') }}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">{{ __('file.payment_method_breakdown') }}
                        </p>

                        @forelse($revenueByMethod as $method)
                            @php
                                $pct = $totalRevenue > 0 ? round(($method->revenue / $totalRevenue) * 100) : 0;
                                $colors = ['COD' => 'bg-violet-500', 'STRIPE' => 'bg-indigo-500', 'BANK' => 'bg-blue-500', 'CASH' => 'bg-emerald-500'];
                                $barColor = $colors[strtoupper($method->payment_method)] ?? 'bg-gray-400';
                            @endphp
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span
                                        class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">{{ $method->payment_method }}</span>
                                    <div class="text-right">
                                        <span
                                            class="text-xs font-bold text-gray-900 dark:text-white">${{ number_format($method->revenue, 0) }}</span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">{{ $pct }}%</span>
                                    </div>
                                </div>
                                <div class="w-full h-2 bg-gray-100 dark:bg-surface-tonal-a30 rounded-full overflow-hidden">
                                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-500"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $method->count }} {{ __('file.orders') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">{{ __('file.no_items_found') }}
                            </p>
                        @endforelse
                    </div>

                    
                    <div class="admin-card p-6">
                        <h2 class="text-sm font-black text-black dark:text-white uppercase tracking-wider mb-1">
                            {{ __('file.orders_by_status') }}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">{{ __('file.order_lifecycle_breakdown') }}
                        </p>

                        @php
                            $statusColors = [
                                'pending' => ['dot' => 'bg-amber-500', 'badge' => 'admin-badge-warning'],
                                'processing' => ['dot' => 'bg-blue-500', 'badge' => 'admin-badge-info'],
                                'shipped' => ['dot' => 'bg-indigo-500', 'badge' => 'admin-badge-info'],
                                'delivered' => ['dot' => 'bg-emerald-500', 'badge' => 'admin-badge-success'],
                                'cancelled' => ['dot' => 'bg-rose-500', 'badge' => 'admin-badge-danger'],
                                'returned' => ['dot' => 'bg-orange-500', 'badge' => 'admin-badge-warning'],
                            ];
                        @endphp

                        <div class="space-y-3">
                            @forelse($ordersByStatus as $s)
                                @php $sc = $statusColors[$s->status] ?? ['dot' => 'bg-gray-400', 'badge' => '']; @endphp
                                <div
                                    class="flex items-center justify-between p-3 rounded-xl  dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $sc['dot'] }} shrink-0"></span>
                                        <span
                                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 capitalize">{{ __('file.' . $s->status) }}</span>
                                    </div>
                                    <span
                                        class="text-sm font-black text-gray-900 dark:text-white tabular-nums">{{ number_format($s->count) }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">
                                    {{ __('file.no_items_found') }}</p>
                            @endforelse
                        </div>
                    </div>

                    
                    <div class="admin-card p-6 text-center">
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">
                            {{ __('file.avg_order_value') }}</p>
                        <p class="text-4xl font-black text-gray-900 dark:text-white tabular-nums">
                            ${{ number_format($avgOrderValue, 2) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ __('file.per_order_avg') }}</p>
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
            const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.04)';
            const textColor = isDark ? '#94a3b8' : '#6b7280';

            const labels = @json($dailyRevenue->pluck('date'));
            const revenue = @json($dailyRevenue->pluck('revenue'));

            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: '{{ __("file.revenue") }}',
                        data: revenue,
                        borderColor: isDark ? '#818cf8' : '#6366f1',
                        backgroundColor: isDark ? 'rgba(129,140,248,0.08)' : 'rgba(99,102,241,0.08)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: isDark ? '#818cf8' : '#6366f1',
                    }]
                },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => '$' + parseFloat(ctx.raw).toFixed(2)
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, maxTicksLimit: 8 }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                callback: v => '$' + v.toLocaleString()
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush