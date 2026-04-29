@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20 transition-all duration-300">
        <div class="max-w-5xl mx-auto">

            {{-- Header Area --}}
            <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 animate-fade-in-up">
                <div>
                    <nav class="flex items-center gap-2 mb-4 group/nav">
                        <a href="{{ route('transactions.index') }}"
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Fiscal
                            Grid</a>
                        <svg class="w-3 h-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                        <span
                            class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest italic animate-pulse tracking-widest">Trace
                            #{{ $transaction->id }}</span>
                    </nav>
                    <div class="flex items-center gap-4">
                        <h1
                            class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter decoration-indigo-500/30 underline underline-offset-8">
                            Flow Inspection</h1>
                        <div
                            class="px-4 py-1.5 rounded-full border border-gray-100 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 shadow-sm flex items-center gap-2">
                            @php
                                $statusColors = [
                                    'paid' => 'bg-emerald-500',
                                    'pending' => 'bg-amber-500',
                                    'failed' => 'bg-rose-500',
                                    'refunded' => 'bg-indigo-500',
                                ];
                                $dotColor = $statusColors[$transaction->status] ?? 'bg-gray-500';
                            @endphp
                            <span class="w-2 h-2 rounded-full {{ $dotColor }} shadow-[0_0_8px_rgba(0,0,0,0.2)]"></span>
                            <span
                                class="text-[10px] font-black text-gray-600 dark:text-gray-300 uppercase tracking-widest">{{ $transaction->status }}
                                protocol</span>
                        </div>
                    </div>
                    <p class="mt-6 text-sm text-gray-400 dark:text-gray-500 font-medium italic">Validated temporal marker:
                        <span
                            class="font-black text-gray-600 dark:text-gray-400">{{ $transaction->created_at->format('M d, Y') }}
                            at {{ $transaction->created_at->format('H:i:s') }} UTC</span></p>
                </div>
                <div class="flex items-center gap-3 animate-fade-in-up delay-100">
                    <a href="{{ route('transactions.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        Grid Return
                    </a>
                    @if(in_array($transaction->status, ['pending', 'failed']))
                        <form action="{{ route('transactions.mark-as-paid', $transaction->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                onclick="return confirm('Execute manual fiscal override? This protocol force-updates the order state to paid.');"
                                class="px-8 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98]">
                                Commit Fiscal Force
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success') || session('error'))
                <div class="mb-8 animate-fade-in-scale">
                    @if(session('success'))
                        <div
                            class="p-4 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-[11px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest">
                                {{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error'))
                        <div
                            class="p-4 rounded-2xl bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center text-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <p class="text-[11px] font-black text-rose-800 dark:text-rose-400 uppercase tracking-widest">
                                {{ session('error') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in-scale">
                {{-- Column 1: Financial Telemetry --}}
                <div class="space-y-8">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30">
                            <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                Financial Telemetry</h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="flex items-center justify-between group/audit">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Net Payload
                                    Magnitude</span>
                                <span
                                    class="text-2xl font-black text-gray-900 dark:text-white italic tracking-tighter decoration-indigo-500/10 underline underline-offset-4">{{ $transaction->currency }}
                                    {{ number_format($transaction->amount, 2) }}</span>
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <span
                                        class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block mb-2">Gateway
                                        Protocol</span>
                                    <div
                                        class="px-3 py-1.5 rounded-xl bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 inline-flex items-center gap-2">
                                        <span
                                            class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $transaction->gateway }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span
                                        class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block mb-2">Instrument
                                        Class</span>
                                    <div
                                        class="px-3 py-1.5 rounded-xl bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 inline-flex items-center gap-2 text-indigo-500">
                                        <span
                                            class="text-[11px] font-black uppercase tracking-tighter">{{ $transaction->payment_type }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="space-y-2">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block">System
                                    Identifier (Reference)</span>
                                <div
                                    class="p-4 rounded-2xl bg-gray-900 dark:bg-surface-tonal-a30 border border-gray-800/50 dark:border-surface-tonal-a40 shadow-inner group/code">
                                    <span
                                        class="font-mono text-[11px] font-black text-indigo-400 select-all cursor-copy">{{ $transaction->transaction_id ?? 'NO_TELEMETRY_ID' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 2: Relational Integrity --}}
                <div class="space-y-8">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30">
                            <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                Relational Integrity</h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Base Order
                                    Anchor</span>
                                @if($transaction->order)
                                    <a href="{{ route('orders.show', $transaction->order->id) }}"
                                        class="inline-flex items-center gap-2 group/ref">
                                        <span
                                            class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase underline decoration-indigo-500/20 underline-offset-4 group-hover/ref:decoration-indigo-500 transition-all font-mono">#{{ $transaction->order->order_number }}</span>
                                        <svg class="w-4 h-4 text-indigo-500 group-hover/ref:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="text-[11px] font-black text-rose-500 uppercase tracking-widest italic">ORPHANED_RELATION</span>
                                @endif
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="space-y-4">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block">Fiscal
                                    Principal (Customer)</span>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-surface-tonal-a30 border border-indigo-100 dark:border-surface-tonal-a40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-[12px] uppercase">
                                        {{ substr($transaction->customer->first_name ?? 'GU', 0, 1) }}{{ substr($transaction->customer->last_name ?? 'ST', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-none">{{ $transaction->customer ? $transaction->customer->first_name . ' ' . $transaction->customer->last_name : 'Guest Participant' }}</span>
                                        @if($transaction->customer)
                                            <a href="mailto:{{ $transaction->customer->email }}"
                                                class="text-[10px] font-bold text-gray-400 uppercase italic hover:text-indigo-500 transition-colors mt-1 select-all">{{ $transaction->customer->email }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($transaction->failure_reason || $transaction->notes)
                                <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                                <div class="space-y-2">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block">Audit
                                        Exceptions / Narrative</span>
                                    <p
                                        class="text-[11px] font-bold text-rose-500 dark:text-rose-400 italic leading-relaxed bg-rose-50/30 dark:bg-rose-950/20 p-4 rounded-2xl border border-rose-100/50 dark:border-rose-900/30 shadow-inner">
                                        {{ $transaction->failure_reason ?: ($transaction->notes ?: 'No explanatory telemetry captured.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-scale {
            from {
                opacity: 0;
                transform: scale(0.97);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-fade-in-scale {
            animation: fade-in-scale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }
    </style>
@endsection