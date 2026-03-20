@extends('layouts.app')

@section('title', 'Inventory History')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2 uppercase">
                <a href="{{ route('inventory.index') }}" class="hover:text-gray-600 transition-colors">Inventory</a>
                <span>/</span>
                <span class="text-gray-600 dark:text-gray-300">Variant #{{ $variant->id }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                History: {{ $variant->product->name }} {{ $variant->sku ? '('.$variant->sku.')' : '' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Current available stock: <strong>{{ $variant->available_quantity }}</strong></p>
        </div>
        <div>
            <a href="{{ route('inventory.index') }}" class="px-3 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-surface-tonal-a20 dark:border-surface-tonal-a30 dark:text-gray-300 dark:hover:bg-gray-700">Back to Inventory</a>
        </div>
    </div>

    <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-surface-tonal-a10">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Change</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Notes / Reference</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Performed By</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-surface-tonal-a20 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $transaction->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    'sale' => 'bg-blue-100 text-blue-800',
                                    'restock' => 'bg-green-100 text-green-800',
                                    'return' => 'bg-purple-100 text-purple-800',
                                    'adjustment' => 'bg-orange-100 text-orange-800',
                                    'damage' => 'bg-red-100 text-red-800',
                                    'cancellation' => 'bg-gray-100 text-gray-800',
                                ];
                                $color = $typeColors[$transaction->type] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($transaction->quantity_change > 0)
                                <span class="text-green-600">+{{ $transaction->quantity_change }}</span>
                            @else
                                <span class="text-red-600">{{ $transaction->quantity_change }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $transaction->notes ?: 'N/A' }} 
                            @if($transaction->reference_id)
                                (Ref: {{ class_basename($transaction->reference_type) }} #{{ $transaction->reference_id }})
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $transaction->user ? $transaction->user->name : 'System' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No inventory history recorded yet for this variant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-surface-tonal-a30">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection

