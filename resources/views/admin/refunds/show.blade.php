@extends('layouts.app')

@section('title', 'Refund Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2 uppercase">
                <a href="{{ route('refunds.index') }}" class="hover:text-gray-600 transition-colors">Refunds</a>
                <span>/</span>
                <span class="text-gray-600 dark:text-gray-300">#{{ str_pad($refund->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0 flex items-center gap-3">
                Refund #{{ str_pad($refund->id, 5, '0', STR_PAD_LEFT) }}
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ ucfirst($refund->status) }}
                </span>
            </h1>
            <p class="mt-1 text-sm text-gray-500">Processed on {{ $refund->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('refunds.index') }}" class="px-3 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-surface-tonal-a20 dark:border-surface-tonal-a30 dark:text-gray-300 dark:hover:bg-gray-700">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Refund Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Amount</dt>
                    <dd class="font-medium text-red-600 dark:text-red-400">{{ $refund->currency }} {{ number_format($refund->amount, 2) }}</dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Gateway Refund ID</dt>
                    <dd class="font-mono text-gray-900 dark:text-primary-a0">{{ $refund->refund_id ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Reason</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">{{ $refund->reason ?: 'No reason provided' }}</dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Requested By</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">{{ ucfirst($refund->requested_by) }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Order & Transaction</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Order Reference</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">
                        @if($refund->order)
                            <a href="{{ route('orders.show', $refund->order->id) }}" class="text-indigo-600 hover:text-indigo-500">#{{ $refund->order->order_number }}</a>
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Original Transaction</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">
                        @if($refund->transaction)
                            <a href="{{ route('transactions.show', $refund->transaction->id) }}" class="text-indigo-600 hover:text-indigo-500">{{ $refund->transaction->transaction_id ?? 'Txn #'.$refund->transaction->id }}</a>
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Approved By</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">{{ $refund->approver ? $refund->approver->name : 'System' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection

