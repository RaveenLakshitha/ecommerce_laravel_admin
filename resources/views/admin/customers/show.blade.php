@extends('layouts.app')

@section('title', $customer->first_name . ' ' . $customer->last_name . ' - ' . __('file.customers'))

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('customers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium mb-1 inline-block">&larr; Back to Customers</a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-primary-a0 flex items-center gap-2">
                {{ $customer->first_name }} {{ $customer->last_name }} 
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400 uppercase">
                    {{ $customer->status }}
                </span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $customer->email }} • {{ $customer->phone ?? 'No phone' }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Stats & Tags --}}
        <div class="space-y-6">
            {{-- Stats Card --}}
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Overview</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="border-t border-gray-100 dark:border-surface-tonal-a30 pt-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-primary-a0">{{ $customer->total_orders }}</dd>
                    </div>
                    <div class="border-t border-gray-100 dark:border-surface-tonal-a30 pt-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Spent</dt>
                        <dd class="mt-1 text-2xl font-semibold text-indigo-600 dark:text-indigo-400">${{ number_format($customer->total_spent, 2) }}</dd>
                    </div>
                    <div class="border-t border-gray-100 dark:border-surface-tonal-a30 pt-4 sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lifetime Value</dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900 dark:text-primary-a0">${{ number_format($customer->lifetime_value, 2) }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Tags Card --}}
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Customer Tags</h3>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    @forelse($customer->tags as $tag)
                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-200">
                            {{ $tag->name }}
                        </span>
                    @empty
                        <span class="text-sm text-gray-500 dark:text-gray-400">No tags assigned.</span>
                    @endforelse
                </div>

                <form action="{{ route('customers.tags.sync', $customer->id) }}" method="POST" class="pt-4 border-t border-gray-200 dark:border-surface-tonal-a30">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Manage Tags</label>
                    <select name="tags[]" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-surface-tonal-a30 dark:text-primary-a0 h-32">
                        @foreach($allTags as $tag)
                            <option value="{{ $tag->id }}" {{ $customer->tags->contains($tag->id) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="mt-3 w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Tags
                    </button>
                    <p class="mt-2 text-xs text-gray-500 text-center">Hold Ctrl/Cmd to select multiple</p>
                </form>
            </div>
        </div>

        {{-- Right Column: Notes & Orders --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Notes Section --}}
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Internal Notes</h3>
                </div>
                <div class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a10/50">
                    <form action="{{ route('customers.notes.add', $customer->id) }}" method="POST">
                        @csrf
                        <div>
                            <label for="content" class="sr-only">Add a note</label>
                            <textarea id="content" name="content" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a20 dark:border-surface-tonal-a30 dark:text-primary-a0" placeholder="Add an internal note about this customer..." required></textarea>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add Note
                            </button>
                        </div>
                    </form>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($customer->notes()->latest()->get() as $note)
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-primary-a0">{{ optional($note->author)->name ?? 'System' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $note->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($note->content)) !!}
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            No internal notes for this customer yet.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Recent Orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-surface-tonal-a10/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($customer->orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                        {{ $order->number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-surface-tonal-a20 text-gray-800 dark:text-gray-200">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-primary-a0">
                                        ${{ number_format($order->grand_total, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No orders placed yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

