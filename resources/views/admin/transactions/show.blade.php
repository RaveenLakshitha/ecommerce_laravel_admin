@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2 uppercase">
                <a href="{{ route('transactions.index') }}" class="hover:text-gray-600 transition-colors">Transactions</a>
                <span>/</span>
                <span class="text-gray-600 dark:text-gray-300">#{{ $transaction->id }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0 flex items-center gap-3">
                Transaction #{{ $transaction->id }}
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                </span>
            </h1>
            <p class="mt-1 text-sm text-gray-500">Processed on {{ $transaction->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('transactions.index') }}" class="px-3 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-surface-tonal-a20 dark:border-surface-tonal-a30 dark:text-gray-300 dark:hover:bg-gray-700">Back</a>
            @if(in_array($transaction->status, ['pending', 'failed']))
                <form action="{{ route('transactions.mark-as-paid', $transaction->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" onclick="return confirm('Mark this transaction as paid visually? This updates the respective order to paid.');" class="px-3 py-2 bg-indigo-600 rounded-md text-sm font-medium text-white hover:bg-indigo-700">Mark as Paid</button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4"><p class="text-sm text-green-700">{{ session('success') }}</p></div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4"><p class="text-sm text-red-700">{{ session('error') }}</p></div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Payment Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Amount</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Gateway</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">{{ ucfirst($transaction->gateway) }}</dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Transaction ID</dt>
                    <dd class="font-mono text-gray-900 dark:text-primary-a0">{{ $transaction->transaction_id ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Payment Type</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">{{ ucfirst($transaction->payment_type) }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Order & Customer</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Order Reference</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">
                        @if($transaction->order)
                            <a href="{{ route('orders.show', $transaction->order->id) }}" class="text-indigo-600 hover:text-indigo-500">#{{ $transaction->order->order_number }}</a>
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Customer</dt>
                    <dd class="font-medium text-gray-900 dark:text-primary-a0">
                        @if($transaction->customer)
                            {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }} 
                            (<a href="mailto:{{ $transaction->customer->email }}" class="text-indigo-600 hover:text-indigo-500">{{ $transaction->customer->email }}</a>)
                        @else
                            Guest
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between border-b border-gray-100 dark:border-surface-tonal-a30 pb-2">
                    <dt class="text-gray-500">Notes / Failure Reason</dt>
                    <dd class="font-medium text-red-500">{{ $transaction->failure_reason ?: ($transaction->notes ?: 'None') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection

