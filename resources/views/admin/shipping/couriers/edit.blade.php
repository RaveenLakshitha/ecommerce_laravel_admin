@extends('layouts.app')

@section('title', 'Edit Courier: ' . $courier->name)

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('shipping.couriers.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Provider Directory</a>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Provider Modification</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">Updating logistics parameters for <span class="text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-tighter">{{ $courier->name }}</span></p>
                </div>
                <div>
                    <form action="{{ route('shipping.couriers.destroy', $courier) }}" method="POST" onsubmit="return confirm('Obliterate this provider from the logistics matrix?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-600 transition-colors flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Terminate Partnership
                        </button>
                    </form>
                </div>
            </div>

            <form action="{{ route('shipping.couriers.update', $courier) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column: Primary Config --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Provider Identity --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Provider Identity</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Corporate Designation <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $courier->name) }}" required 
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Operational Overview</label>
                                    <textarea name="description" rows="4" placeholder="Define the utility and regional specializations..."
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">{{ old('description', $courier->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- API Integration --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Interface Configuration</h2>
                                <span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20 uppercase tracking-widest italic">Encrypted Secure Tunnel</span>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Primary Endpoint URL</label>
                                    <div class="relative flex items-center">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        </div>
                                        <input type="url" name="base_url" value="{{ old('base_url', $courier->base_url) }}" placeholder="https://api.logistics-hub.com/v1/"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 pl-11 pr-4 py-3 text-sm font-mono text-indigo-600 dark:text-indigo-400 focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Access Key</label>
                                        <input type="text" name="api_key" value="{{ old('api_key', $courier->api_key) }}" placeholder="Redacted"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Secret Token</label>
                                        <input type="password" name="api_secret" value="{{ old('api_secret', $courier->api_secret) }}" placeholder="••••••••••••••••"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Right Column: Status & Capabilities --}}
                    <div class="lg:col-span-1 space-y-6">

                        {{-- Service Capabilities --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Service Capabilities</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                
                                <label class="flex items-start p-3 rounded-xl border border-gray-50 dark:border-surface-tonal-a10 hover:bg-gray-50 dark:hover:bg-surface-tonal-a10/40 transition-all cursor-pointer">
                                    <input type="checkbox" name="supports_tracking" value="1" {{ old('supports_tracking', $courier->supports_tracking) ? 'checked' : '' }}
                                        class="mt-1 h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Sync Tracking</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Automated status updates via API</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-3 rounded-xl border border-gray-50 dark:border-surface-tonal-a10 hover:bg-gray-50 dark:hover:bg-surface-tonal-a10/40 transition-all cursor-pointer">
                                    <input type="checkbox" name="supports_label_generation" value="1" {{ old('supports_label_generation', $courier->supports_label_generation) ? 'checked' : '' }}
                                        class="mt-1 h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Waybill Printing</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Generate shipping labels on demand</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-3 rounded-xl border border-gray-50 dark:border-surface-tonal-a10 hover:bg-gray-50 dark:hover:bg-surface-tonal-a10/40 transition-all cursor-pointer">
                                    <input type="checkbox" name="supports_cod" value="1" {{ old('supports_cod', $courier->supports_cod) ? 'checked' : '' }}
                                        class="mt-1 h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Financial Collection</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Supports Cash on Delivery processing</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-3 rounded-xl border border-indigo-50 dark:border-indigo-500/10 bg-indigo-50/30 dark:bg-indigo-500/5 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all cursor-pointer">
                                    <input type="checkbox" name="default_for_cod" value="1" {{ old('default_for_cod', $courier->default_for_cod) ? 'checked' : '' }}
                                        class="mt-1 h-5 w-5 rounded-md border-indigo-200 dark:border-indigo-500/30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-indigo-900 dark:text-indigo-400 leading-tight">Primary COD Routing</p>
                                        <p class="text-[10px] text-indigo-600/60 dark:text-indigo-400/60 mt-0.5 uppercase font-black tracking-tighter">Automatic assignment priority</p>
                                    </div>
                                </label>

                                <div class="pt-4 mt-2 border-t border-gray-100 dark:border-surface-tonal-a30">
                                    <label class="flex items-center p-3 rounded-xl hover:bg-emerald-50/50 dark:hover:bg-emerald-500/5 transition-all cursor-pointer group">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $courier->is_active) ? 'checked' : '' }}
                                            class="h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-emerald-500 focus:ring-emerald-500 transition-all">
                                        <div class="ml-4">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition-colors">Operational Status</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Available for logic routing</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div class="p-6 space-y-3">
                                <button type="submit"
                                    class="w-full h-12 flex items-center justify-center rounded-xl bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-widest hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl shadow-gray-200 dark:shadow-none active:scale-[0.98]">
                                    Commit Paradigm
                                </button>
                                <a href="{{ route('shipping.couriers.index') }}"
                                    class="w-full flex items-center justify-center px-6 py-3 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-transparent text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                    Abandon Modifications
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
