@extends('layouts.app')

@section('title', $customer->first_name . ' ' . $customer->last_name . ' - ' . __('file.profile'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('customers.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_customers') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $customer->first_name }} {{ $customer->last_name }}</h1>
                        <span
                            class="px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                            {{ $customer->status ?? __('file.active') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $customer->email }} <span
                            class="mx-2 opacity-30">•</span> {{ $customer->phone ?? __('file.no_phone_provided') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customers.edit', $customer->id) }}"
                        class="px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.edit_customer') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Internal Notes Section --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                             <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ __('file.internal_correspondence') }}</h2>
                             <span
                                class="text-xs font-medium text-indigo-500">{{ $customer->notes()->count() }}
                                {{ __('file.notes') }}</span>
                        </div>

                        {{-- New Note Form --}}
                        <div
                            class="p-4 bg-indigo-50/30 dark:bg-indigo-900/10 border-b border-gray-100 dark:border-surface-tonal-a30">
                            <form action="{{ route('customers.notes.add', $customer->id) }}" method="POST"
                                class="space-y-3">
                                @csrf
                                 <textarea name="content" rows="3" required
                                    placeholder="{{ __('file.add_private_note_placeholder') }}"
                                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a20 px-4 py-3 text-sm font-normal text-gray-700 dark:text-gray-200 outline-none focus:ring-4 focus:ring-primary/5 transition-all resize-none shadow-sm"></textarea>
                                <div class="flex justify-end">
                                     <button type="submit"
                                        class="px-5 py-2.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 active:scale-95 transition-all">
                                        {{ __('file.post_note') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Notes List --}}
                        <div
                            class="divide-y divide-gray-50 dark:divide-surface-tonal-a30 max-h-[400px] overflow-y-auto custom-scrollbar">
                            @forelse($customer->notes()->latest()->get() as $note)
                                <div class="p-4 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 rounded-md bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-[10px] font-bold text-indigo-600 dark:text-indigo-400">
                                                {{ strtoupper(substr($note->author->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-xs font-bold text-gray-900 dark:text-white">{{ $note->author->name ?? __('file.system') }}</span>
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
                                    <p class="text-sm font-medium italic">{{ __('file.no_internal_observations_recorded') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent Orders Table --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                             <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ __('file.order_history') }}</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-gray-50 dark:bg-surface-tonal-a20 border-b border-gray-100 dark:border-surface-tonal-a30">
                                         <th class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('file.order_hash') }}</th>
                                        <th class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('file.date') }}</th>
                                        <th class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('file.status') }}</th>
                                        <th
                                            class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400 text-right">
                                            {{ __('file.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($customer->orders as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-colors">
                                            <td class="px-6 py-4">
                                                <a href="{{ route('orders.show', $order->id) }}"
                                                    class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    {{ $order->order_number ?? $order->number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">
                                                {{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4">
                                                 <span
                                                    class="px-2 py-0.5 rounded-md bg-gray-100 dark:bg-surface-tonal-a30 text-gray-600 dark:text-gray-400 text-[10px] font-bold tracking-tighter">{{ __('file.' . strtolower($order->status)) }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="text-sm font-bold text-gray-900 dark:text-white tabular-nums">@price($order->grand_total ?? $order->total_amount)</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-12 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                                                {{ __('file.no_transaction_history') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="col-span-1 space-y-4">

                    {{-- Financial Summary Card --}}
                    <div class="bg-indigo-600 rounded-lg shadow-lg p-5 text-white overflow-hidden relative group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                         <h3 class="text-xs font-bold uppercase tracking-wider opacity-60 mb-4">
                            {{ __('file.financial_summary') }}</h3>
                        <div class="space-y-6 relative z-10">
                            <div class="flex items-end justify-between">
                                 <span
                                    class="text-xs font-bold opacity-80 uppercase tracking-wider">{{ __('file.total_spent') }}</span>
                                <span class="text-2xl font-black tabular-nums">@price($customer->total_spent)</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10">
                                <div>
                                    <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest">
                                        {{ __('file.orders') }}</p>
                                    <p class="text-lg font-black">{{ $customer->total_orders }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest">
                                        {{ __('file.ltv') }}</p>
                                    <p class="text-lg font-black tabular-nums">@price($customer->lifetime_value)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Tags Card --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                             <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ __('file.customer_tags') }}</h2>
                        </div>
                        <div class="p-4">
                            <div class="flex flex-wrap gap-1.5 mb-6">
                                @forelse($customer->tags as $tag)
                                     <span
                                        class="px-2 py-0.5 rounded-md bg-gray-100 dark:bg-surface-tonal-a30 text-gray-700 dark:text-gray-300 text-[10px] font-bold border border-gray-200 dark:border-surface-tonal-a30">
                                        {{ $tag->name }}
                                    </span>
                                @empty
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">
                                        {{ __('file.no_tags_assigned') }}</p>
                                @endforelse
                            </div>

                            <form action="{{ route('customers.tags.sync', $customer->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                <div>
                                     <label
                                        class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">{{ __('file.update_tags') }}</label>
                                    <select name="tags[]" multiple
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-3 text-sm font-normal text-gray-700 dark:text-gray-300 outline-none focus:ring-4 focus:ring-primary/5 transition-all h-32 custom-scrollbar cursor-pointer">
                                        @foreach($allTags as $tag)
                                            <option value="{{ $tag->id }}" {{ $customer->tags->contains($tag->id) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                 <button type="submit"
                                    class="w-full py-3 bg-gray-900 dark:bg-white text-xs font-bold text-white dark:text-gray-900 uppercase tracking-wider rounded-xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-95">
                                    {{ __('file.sync_tags') }}
                                </button>
                            </form>
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