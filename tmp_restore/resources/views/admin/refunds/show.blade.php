@extends('layouts.app')

@section('title', 'Refund Details')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20 transition-all duration-300">
        <div class="max-w-5xl mx-auto">

            {{-- Header Area --}}
            <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 animate-fade-in-up">
                <div>
                    <nav class="flex items-center gap-2 mb-4 group/nav">
                        <a href="{{ route('refunds.index') }}"
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Clawback
                            Grid</a>
                        <svg class="w-3 h-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                        <span
                            class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest italic animate-pulse">Trace
                            #{{ str_pad($refund->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </nav>
                    <div class="flex items-center gap-4">
                        <h1
                            class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter decoration-indigo-500/30 underline underline-offset-8">
                            Reversal Inspection</h1>
                        <div
                            class="px-4 py-1.5 rounded-full border border-gray-100 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 shadow-sm flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]"></span>
                            <span
                                class="text-[10px] font-black text-gray-600 dark:text-gray-300 uppercase tracking-widest">{{ $refund->status }}
                                protocol</span>
                        </div>
                    </div>
                    <p class="mt-6 text-sm text-gray-400 dark:text-gray-500 font-medium italic">Validated temporal marker:
                        <span
                            class="font-black text-gray-600 dark:text-gray-400">{{ $refund->created_at->format('M d, Y') }}
                            at {{ $refund->created_at->format('H:i:s') }} UTC</span></p>
                </div>
                <div class="flex items-center gap-3 animate-fade-in-up delay-100">
                    <a href="{{ route('refunds.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        Grid Return
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in-scale">
                {{-- Column 1: Refund Particulars --}}
                <div class="space-y-8">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30">
                            <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                Financial Restitution</h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount
                                    Disbursed</span>
                                <span
                                    class="text-2xl font-black text-rose-600 dark:text-rose-400 italic tracking-tighter decoration-rose-500/10 underline underline-offset-4">{{ $refund->currency }}
                                    {{ number_format($refund->amount, 2) }}</span>
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="space-y-2">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block">Gateway
                                    Reference</span>
                                <div
                                    class="p-4 rounded-2xl bg-gray-900 dark:bg-surface-tonal-a30 border border-gray-800/50 dark:border-surface-tonal-a40 shadow-inner">
                                    <span
                                        class="font-mono text-[11px] font-black text-indigo-400 select-all cursor-copy">{{ $refund->refund_id ?? 'PENDING_SETTLEMENT' }}</span>
                                </div>
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="space-y-2">
                                <span
                                    class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block">Restitution
                                    Narrative</span>
                                <p
                                    class="text-[11px] font-bold text-gray-700 dark:text-gray-300 italic leading-relaxed bg-gray-100/50 dark:bg-surface-tonal-a30/50 p-4 rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 shadow-sm">
                                    {{ $refund->reason ?: 'No justification telemetry recorded.' }}
                                </p>
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Source
                                    Initiator</span>
                                <span
                                    class="px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest border border-indigo-100 dark:border-indigo-500/20">{{ $refund->requested_by }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 2: Structural Alignment --}}
                <div class="space-y-8">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30">
                            <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                Structural Alignment</h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Order
                                    Reference</span>
                                @if($refund->order)
                                    <a href="{{ route('orders.show', $refund->order->id) }}"
                                        class="inline-flex items-center gap-2 group/ref">
                                        <span
                                            class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase underline decoration-indigo-500/20 underline-offset-4 group-hover/ref:decoration-indigo-500 transition-all font-mono">#{{ $refund->order->order_number }}</span>
                                        <svg class="w-4 h-4 text-indigo-500 group-hover/ref:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="text-[11px] font-black text-gray-400 uppercase tracking-widest italic">NULL_RELATION</span>
                                @endif
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Base
                                    Transaction</span>
                                @if($refund->transaction)
                                    <a href="{{ route('transactions.show', $refund->transaction->id) }}"
                                        class="inline-flex items-center gap-2 group/txn">
                                        <span
                                            class="text-[11px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-tighter truncate max-w-[120px] group-hover/txn:text-indigo-500 transition-colors font-mono">{{ $refund->transaction->transaction_id ?? 'Txn-' . $refund->transaction->id }}</span>
                                        <svg class="w-3 h-3 text-gray-400 group-hover/txn:text-indigo-500 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="text-[11px] font-black text-gray-400 uppercase tracking-widest italic font-mono">N/A</span>
                                @endif
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="flex flex-col gap-4">
                                <span
                                    class="text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] block">Authorization
                                    Nexus</span>
                                <div
                                    class="p-4 rounded-2xl bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 flex items-center justify-center text-gray-400 font-black text-[10px] uppercase shadow-sm">
                                        {{ substr($refund->approver ? $refund->approver->name : 'SYS', 0, 2) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-none">{{ $refund->approver ? $refund->approver->name : 'Autonomous System' }}</span>
                                        <span
                                            class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1 italic">Validation
                                            Layer: Master Admin</span>
                                    </div>
                                </div>
                            </div>
                            <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Approval
                                    Magnitude</span>
                                <span
                                    class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ $refund->created_at->format('M d, Y') }}
                                    at {{ $refund->created_at->format('h:i A') }}</span>
                            </div>
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