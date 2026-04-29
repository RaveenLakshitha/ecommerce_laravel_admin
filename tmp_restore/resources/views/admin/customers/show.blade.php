@extends('layouts.app')

@section('title', $customer->first_name . ' ' . $customer->last_name . ' - Profile')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('customers.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Customers</a>
                    <div class="flex items-center gap-4 mt-2">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $customer->first_name }} {{ $customer->last_name }}
                        </h1>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 uppercase tracking-widest border border-emerald-200 dark:border-emerald-500/20">
                            {{ $customer->status ?? 'Active' }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        {{ $customer->email }} <span class="mx-2 opacity-30">•</span>
                        {{ $customer->phone ?? 'No phone provided' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Left Column: Stats & Tags --}}
                <div class="space-y-6">

                    {{-- Customer Overview Card --}}
                    <div
                        class="bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-500/20 p-6 text-white overflow-hidden relative group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-6">Financial Summary</h3>
                        <div class="space-y-6 relative z-10">
                            <div class="flex items-end justify-between">
                                <span class="text-xs font-bold opacity-80 uppercase">Total Spent</span>
                                <span class="text-2xl font-black">${{ number_format($customer->total_spent, 2) }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10">
                                <div>
                                    <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider">Orders</p>
                                    <p class="text-lg font-black">{{ $customer->total_orders }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider">LTV</p>
                                    <p class="text-lg font-black">${{ number_format($customer->lifetime_value, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Manage Tags Card --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="font-semibold text-gray-900 dark:text-white">Customer Tags</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-wrap gap-1.5 mb-6">
                                @forelse($customer->tags as $tag)
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 text-gray-700 dark:text-gray-300 text-xs font-bold border border-gray-200 dark:border-surface-tonal-a30">
                                        {{ $tag->name }}
                                    </span>
                                @empty
                                    <p class="text-[10px] text-gray-400 font-medium italic">No tags assigned</p>
                                @endforelse
                            </div>

                            <form action="{{ route('customers.tags.sync', $customer->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Update
                                        Tags</label>
                                    <select name="tags[]" multiple
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all h-32 custom-scrollbar">
                                        @foreach($allTags as $tag)
                                            <option value="{{ $tag->id }}" {{ $customer->tags->contains($tag->id) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-widest hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-95">
                                    Sync Tags
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Notes & Orders --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Internal Notes Section --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900 dark:text-white">Internal Correspondence</h2>
                            <span
                                class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $customer->notes()->count() }}
                                Notes</span>
                        </div>

                        {{-- New Note Form --}}
                        <div
                            class="p-6 bg-indigo-50/30 dark:bg-indigo-900/10 border-b border-gray-100 dark:border-surface-tonal-a30">
                            <form action="{{ route('customers.notes.add', $customer->id) }}" method="POST"
                                class="space-y-3">
                                @csrf
                                <textarea name="content" rows="3" required
                                    placeholder="Add a private note about this customer..."
                                    class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition-all resize-none"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-md active:scale-95 transition-all">
                                        Post Note
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Notes List --}}
                        <div
                            class="divide-y divide-gray-50 dark:divide-surface-tonal-a30 max-h-[400px] overflow-y-auto custom-scrollbar">
                            @forelse($customer->notes()->latest()->get() as $note)
                                <div class="p-6 hover:bg-gray-100/50 dark:hover:bg-surface-tonal-a30/10 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-[10px] font-bold text-indigo-600 dark:text-indigo-400">
                                                {{ strtoupper(substr($note->author->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-xs font-bold text-gray-900 dark:text-white">{{ $note->author->name ?? 'System' }}</span>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $note->created_at->format('M d, Y • H:i') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed pl-8">
                                        {!! nl2br(e($note->content)) !!}
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center text-gray-400">
                                    <p class="text-sm font-medium italic">No internal observations recorded.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent Orders Table --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="font-semibold text-gray-900 dark:text-white">Order History</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-gray-100/50 dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            Order #</th>
                                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            Date</th>
                                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($customer->orders as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/20 transition-colors group">
                                            <td class="px-6 py-4">
                                                <span
                                                    class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">{{ $order->number }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                                                {{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-gray-100 dark:bg-surface-tonal-a30 text-gray-600 dark:text-gray-400 text-[10px] font-bold tracking-tighter uppercase">{{ $order->status }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($order->grand_total, 2) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-12 text-center text-xs font-medium text-gray-400 uppercase tracking-widest">
                                                No transaction history</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
@endsection