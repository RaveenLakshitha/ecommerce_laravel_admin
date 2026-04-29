@extends('layouts.app')

@section('title', 'Inventory History')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20 transition-all duration-300">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header Area --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="animate-fade-in-up">
                    <nav class="flex items-center gap-2 mb-4 group/nav">
                        <a href="{{ route('inventory.index') }}"
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Digital
                            Warehouse</a>
                        <svg class="w-3 h-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                        <span
                            class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest italic animate-pulse">Trace
                            #{{ $variant->id }}</span>
                    </nav>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Audit Log: <span
                            class="decoration-indigo-500/30 underline underline-offset-8">{{ $variant->product->name }}
                            {{ $variant->sku ? '(' . $variant->sku . ')' : '' }}</span></h1>
                    <div class="mt-4 flex items-center gap-3">
                        <div
                            class="px-3 py-1 rounded-full bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></span>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Stock
                                Reservoir: <span
                                    class="text-gray-900 dark:text-white italic">{{ $variant->available_quantity }}
                                    units</span></p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 animate-fade-in-up delay-100">
                    <a href="{{ route('inventory.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        Return to Grid
                    </a>
                </div>
            </div>

            {{-- Table Card --}}
            <div
                class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden animate-fade-in-scale">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="bg-gray-100/50 dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                            <tr>
                                <th scope="col"
                                    class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    Temporal Marker</th>
                                <th scope="col"
                                    class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                    Protocol Type</th>
                                <th scope="col"
                                    class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                    Flux Magnitude</th>
                                <th scope="col"
                                    class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Context
                                    / Reference</th>
                                <th scope="col"
                                    class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                    Executor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                            @forelse($transactions as $transaction)
                                <tr class="group hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-8 py-5">
                                        <div
                                            class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                            {{ $transaction->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase italic">
                                            {{ $transaction->created_at->format('H:i:s') }} UTC
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @php
                                            $typeColors = [
                                                'sale' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200/50 dark:border-blue-900/30',
                                                'restock' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200/50 dark:border-emerald-900/30',
                                                'return' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-200/50 dark:border-purple-900/30',
                                                'adjustment' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200/50 dark:border-amber-900/30',
                                                'damage' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200/50 dark:border-rose-900/30',
                                                'cancellation' => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-200/50 dark:border-slate-900/30',
                                            ];
                                            $color = $typeColors[$transaction->type] ?? 'bg-gray-500/10 text-gray-600 border-gray-200';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $color }} transition-all group-hover:scale-105">
                                            {{ $transaction->type }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center font-mono">
                                        @if($transaction->quantity_change > 0)
                                            <div
                                                class="flex items-center justify-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-black">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 15l7-7 7 7" />
                                                </svg>
                                                <span class="text-sm">+{{ $transaction->quantity_change }}</span>
                                            </div>
                                        @else
                                            <div
                                                class="flex items-center justify-center gap-1.5 text-rose-600 dark:text-rose-400 font-black">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <span class="text-sm">{{ $transaction->quantity_change }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5">
                                        <div
                                            class="text-[11px] font-bold text-gray-700 dark:text-gray-300 italic leading-relaxed max-w-xs">
                                            {{ $transaction->notes ?: 'No explanatory telemetry provided.' }}
                                        </div>
                                        @if($transaction->reference_id)
                                            <div
                                                class="mt-1 inline-flex items-center gap-1 text-[9px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest group/ref cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span
                                                    class="group-hover/ref:underline underline-offset-2">{{ class_basename($transaction->reference_type) }}
                                                    #{{ $transaction->reference_id }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center j-end gap-3 justify-end group/user">
                                            <div class="text-right">
                                                <div
                                                    class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                                    {{ $transaction->user ? $transaction->user->name : 'Autonomous System' }}
                                                </div>
                                                <div
                                                    class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic opacity-0 group-hover/user:opacity-100 transition-opacity">
                                                    {{ $transaction->user ? 'Level: Admin' : 'System Script' }}
                                                </div>
                                            </div>
                                            <div
                                                class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-surface-tonal-a30 border border-gray-200 dark:border-surface-tonal-a30 flex items-center justify-center text-gray-400 dark:text-gray-500 group-hover/user:bg-indigo-50 dark:group-hover/user:bg-indigo-500/10 transition-all font-black text-[10px] uppercase">
                                                {{ substr($transaction->user->name ?? 'SYS', 0, 2) }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center">
                                        <div
                                            class="mx-auto w-12 h-12 bg-gray-50 dark:bg-surface-tonal-a30 rounded-2xl flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 scale-150 opacity-20 transform -rotate-12">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Zero Trace
                                            Integrity</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 italic">No inventory flux has
                                            been recorded for this variant.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div
                        class="px-8 py-6 border-t border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/20 dark:bg-surface-tonal-a10/20">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-scale {
            from {
                opacity: 0;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.4s ease-out forwards;
        }

        .animate-fade-in-scale {
            animation: fade-in-scale 0.5s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }
    </style>
@endsection