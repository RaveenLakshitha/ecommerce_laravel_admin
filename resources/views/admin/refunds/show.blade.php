@extends('layouts.app')

@section('title', __('file.refund_details'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('refunds.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_refunds') }}
                </a>
            </div>

            {{-- Header Area --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-4">
                        <h1
                            class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter">
                            {{ __('file.reversal_inspection') }}</h1>
                        @php
                            $statusColors = [
                                'processed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                                'failed' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border-rose-200 dark:border-rose-500/20',
                            ];
                            $statusColor = $statusColors[$refund->status] ?? 'bg-gray-100 text-gray-700 dark:bg-surface-tonal-a30 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30';
                        @endphp
                        <div
                            class="px-2.5 py-0.5 rounded-md text-[10px] font-black {{ $statusColor }} uppercase tracking-widest border flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $refund->status === 'processed' ? 'bg-emerald-500' : ($refund->status === 'failed' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                            {{ $refund->status }} protocol
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-widest font-bold">
                        {{ __('file.trace') }} #{{ str_pad($refund->id, 5, '0', STR_PAD_LEFT) }} <span
                            class="mx-2 opacity-30">•</span> {{ $refund->created_at->format('M d, Y H:i:s') }} UTC</p>
                </div>
                <div class="flex items-center gap-3">
                    @if($refund->status === 'pending')
                        <form action="{{ route('refunds.approve', $refund->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Approve this refund?')"
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all shadow-md active:scale-95">
                                {{ __('file.approve_restitution') }}
                            </button>
                        </form>
                        <form action="{{ route('refunds.reject', $refund->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Reject this refund?')"
                                class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all shadow-md active:scale-95">
                                {{ __('file.reject_restitution') }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('refunds.index') }}"
                        class="px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('file.grid_return') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                {{-- Left Column: Refund Particulars --}}
                <div class="lg:col-span-2 space-y-4">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">
                                {{ __('file.financial_restitution') }}</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.amount_disbursed') }}</span>
                                    <p class="text-2xl font-black text-rose-600 dark:text-rose-400 italic tracking-tighter">
                                        @price($refund->amount)</p>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.status') }}</span>
                                    <div class="pt-1">
                                        <span
                                            class="px-3 py-1 rounded-md {{ $statusColor }} text-[10px] font-black uppercase tracking-widest border">{{ $refund->status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <span
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.gateway_reference') }}</span>
                                <div
                                    class="p-4 rounded-xl bg-gray-900 dark:bg-surface-tonal-a30 border border-gray-800/50 dark:border-surface-tonal-a40 shadow-inner">
                                    <span
                                        class="font-mono text-xs font-black text-indigo-400 select-all cursor-copy">{{ $refund->transaction_id ?? 'PENDING_SETTLEMENT' }}</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <span
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.restitution_narrative') }}</span>
                                <p
                                    class="text-xs font-bold text-gray-700 dark:text-gray-300 italic leading-relaxed bg-gray-100/50 dark:bg-surface-tonal-a30/50 p-4 rounded-xl border border-gray-100 dark:border-surface-tonal-a30 shadow-sm">
                                    {{ $refund->reason ?: __('file.no_justification_telemetry_recorded') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Structural Alignment --}}
                <div class="col-span-1 space-y-4">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">
                                {{ __('file.structural_alignment') }}</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.order_reference') }}</span>
                                @if($refund->order)
                                    <a href="{{ route('orders.show', $refund->order->id) }}"
                                        class="inline-flex items-center gap-2 group">
                                        <span
                                            class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase underline decoration-indigo-500/20 underline-offset-4 group-hover:decoration-indigo-500 transition-all font-mono">#{{ $refund->order->order_number }}</span>
                                        <svg class="w-4 h-4 text-indigo-500 group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="text-xs font-black text-gray-400 uppercase tracking-widest italic font-mono">NULL_RELATION</span>
                                @endif
                            </div>

                            <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>

                            <div class="space-y-3">
                                <span
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">{{ __('file.authorization_nexus') }}</span>
                                <div
                                    class="p-3 rounded-xl bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 flex items-center justify-center text-gray-400 font-black text-[10px] uppercase shadow-sm">
                                        {{ substr($refund->performedBy ? $refund->performedBy->name : 'SYS', 0, 2) }}
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-tighter truncate">{{ $refund->performedBy ? $refund->performedBy->name : __('file.autonomous_system') }}</span>
                                        <span
                                            class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic truncate">{{ __('file.validation_layer') }}:
                                            {{ $refund->performedBy ? 'Admin' : 'System' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>

                            <div class="flex justify-between items-center">
                                <span
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.approval_magnitude') }}</span>
                                <span
                                    class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ $refund->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection