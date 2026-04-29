@extends('layouts.app')

@section('title', __('file.transaction_details'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('transactions.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_fiscal_grid') }}
                </a>
            </div>

            {{-- Header Area --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-4">
                        <h1
                            class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter">
                            {{ __('file.flow_inspection') }}</h1>
                        @php
                            $statusColors = [
                                'paid' => ['dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20'],
                                'pending' => ['dot' => 'bg-amber-500', 'bg' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'],
                                'failed' => ['dot' => 'bg-rose-500', 'bg' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border-rose-200 dark:border-rose-500/20'],
                                'refunded' => ['dot' => 'bg-indigo-500', 'bg' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20'],
                            ];
                            $s = $statusColors[$transaction->status] ?? ['dot' => 'bg-gray-500', 'bg' => 'bg-gray-100 text-gray-700 dark:bg-surface-tonal-a30 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30'];
                        @endphp
                        <div
                            class="px-2.5 py-0.5 rounded-md text-[10px] font-bold border flex items-center gap-1.5 uppercase tracking-wider {{ $s['bg'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                            {{ $transaction->status }}
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-semibold">
                        {{ __('file.trace') }} #{{ $transaction->id }} <span class="mx-2 opacity-30">•</span>
                        {{ $transaction->created_at->format('M d, Y H:i:s') }} UTC</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('transactions.index') }}"
                        class="px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('file.grid_return') }}
                    </a>
                    @if(in_array($transaction->status, ['pending', 'failed']))
                        <form action="{{ route('transactions.mark-as-paid', $transaction->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('{{ __('file.confirm_fiscal_override') }}');"
                                class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 shadow-md transition-all active:scale-95">
                                {{ __('file.commit_fiscal_force') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success') || session('error'))
                <div class="mb-6">
                    @if(session('success'))
                        <div
                            class="p-3 rounded-lg bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <p class="text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest">
                                {{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error'))
                        <div
                            class="p-3 rounded-lg bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 flex items-center gap-3">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <p class="text-[10px] font-black text-rose-800 dark:text-rose-400 uppercase tracking-widest">
                                {{ session('error') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                {{-- Left Column: Financial Telemetry --}}
                <div class="lg:col-span-2 space-y-4">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">
                                {{ __('file.financial_telemetry') }}</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('file.net_payload_magnitude') }}</span>
                                <span
                                    class="text-2xl font-black text-gray-900 dark:text-white italic tracking-tighter">@price($transaction->amount)</span>
                            </div>

                            <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span
                                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">{{ __('file.gateway_protocol') }}</span>
                                    <div
                                        class="px-3 py-1.5 rounded-md bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 inline-flex items-center gap-2">
                                        <span
                                            class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $transaction->gateway }}</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">{{ __('file.instrument_class') }}</span>
                                    <div
                                        class="px-3 py-1.5 rounded-md bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 inline-flex items-center gap-2 text-indigo-500">
                                        <span
                                            class="text-[11px] font-black uppercase tracking-tighter">{{ $transaction->payment_type }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>

                            <div class="space-y-2">
                                <span
                                    class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('file.system_identifier_reference') }}</span>
                                <div
                                    class="p-4 rounded-xl bg-gray-900 dark:bg-surface-tonal-a30 border border-gray-800/50 dark:border-surface-tonal-a40 shadow-inner group/code">
                                    <span
                                        class="font-mono text-xs font-black text-indigo-400 select-all cursor-copy">{{ $transaction->transaction_id ?? 'NO_TELEMETRY_ID' }}</span>
                                </div>
                            </div>

                            @if($transaction->failure_reason || $transaction->notes)
                                <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>
                                <div class="space-y-2">
                                    <span
                                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('file.audit_exceptions_narrative') }}</span>
                                    <p
                                        class="text-xs font-bold text-rose-500 dark:text-rose-400 italic leading-relaxed bg-rose-50/30 dark:bg-rose-950/20 p-4 rounded-xl border border-rose-100/50 dark:border-rose-900/30 shadow-sm">
                                        {{ $transaction->failure_reason ?: ($transaction->notes ?: __('file.no_explanatory_telemetry_captured')) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Relational Integrity --}}
                <div class="col-span-1 space-y-4">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">
                                {{ __('file.relational_integrity') }}</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('file.base_order_anchor') }}</span>
                                @if($transaction->order)
                                    <a href="{{ route('orders.show', $transaction->order->id) }}"
                                        class="inline-flex items-center gap-2 group">
                                        <span
                                            class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase underline decoration-indigo-500/20 underline-offset-4 group-hover:decoration-indigo-500 transition-all font-mono">#{{ $transaction->order->order_number }}</span>
                                        <svg class="w-4 h-4 text-indigo-500 group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="text-xs font-black text-rose-500 uppercase tracking-widest italic font-mono">ORPHANED_RELATION</span>
                                @endif
                            </div>

                            <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>

                            <div class="space-y-4">
                                <span
                                    class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">{{ __('file.fiscal_principal_customer') }}</span>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-surface-tonal-a30 border border-indigo-100 dark:border-surface-tonal-a40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase shadow-sm">
                                        {{ substr($transaction->customer->first_name ?? 'GU', 0, 1) }}{{ substr($transaction->customer->last_name ?? 'ST', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-tighter truncate leading-none">{{ $transaction->customer ? $transaction->customer->first_name . ' ' . $transaction->customer->last_name : __('file.guest_participant') }}</span>
                                        @if($transaction->customer)
                                            <a href="mailto:{{ $transaction->customer->email }}"
                                                class="text-[9px] font-bold text-gray-400 uppercase italic hover:text-indigo-500 transition-colors mt-1 truncate">{{ $transaction->customer->email }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>

                            <div class="flex justify-between items-center">
                                <span
                                    class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('file.temporal_validation') }}</span>
                                <span
                                    class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $transaction->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection