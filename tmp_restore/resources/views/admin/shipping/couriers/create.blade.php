@extends('layouts.app')

@section('title', 'Add Courier')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('shipping.couriers.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Provider Directory</a>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Onboard Delivery Partner</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium italic underline decoration-indigo-500/20 underline-offset-4">Register new logistics providers and configure their operational API parameters.</p>
                </div>
            </div>

            <form action="{{ route('shipping.couriers.store') }}" method="POST">
                @csrf

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
                                    <input type="text" name="name" required placeholder="e.g. DOMEX Logistics Solutions"
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Operational Overview</label>
                                    <textarea name="description" rows="3" placeholder="Define the utility and regional specializations of this partner..."
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- API Integration --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Interface Configuration</h2>
                                <span class="text-[10px] font-black text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded border border-amber-500/20 uppercase tracking-widest">Protocol Required</span>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Primary Endpoint URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        </div>
                                        <input type="url" name="base_url" placeholder="https://api.logistics-hub.com/v1/"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 pl-11 pr-4 py-3 text-sm font-mono text-indigo-600 dark:text-indigo-400 focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Access Key</label>
                                        <input type="text" name="api_key" placeholder="Enter identification key"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Secret Token</label>
                                        <input type="password" name="api_secret" placeholder="••••••••••••••••"
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
                                    <input type="checkbox" name="supports_tracking" value="1"
                                        class="mt-1 h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Sync Tracking</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Automated status updates via API</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-3 rounded-xl border border-gray-50 dark:border-surface-tonal-a10 hover:bg-gray-50 dark:hover:bg-surface-tonal-a10/40 transition-all cursor-pointer">
                                    <input type="checkbox" name="supports_label_generation" value="1"
                                        class="mt-1 h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Waybill Printing</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Generate shipping labels on demand</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-3 rounded-xl border border-gray-50 dark:border-surface-tonal-a10 hover:bg-gray-50 dark:hover:bg-surface-tonal-a10/40 transition-all cursor-pointer">
                                    <input type="checkbox" name="supports_cod" value="1"
                                        class="mt-1 h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Financial Collection</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Supports Cash on Delivery processing</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-3 rounded-xl border border-indigo-50 dark:border-indigo-500/10 bg-indigo-50/30 dark:bg-indigo-500/5 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all cursor-pointer">
                                    <input type="checkbox" name="default_for_cod" value="1"
                                        class="mt-1 h-5 w-5 rounded-md border-indigo-200 dark:border-indigo-500/30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-indigo-900 dark:text-indigo-400 leading-tight">Primary COD Routing</p>
                                        <p class="text-[10px] text-indigo-600/60 dark:text-indigo-400/60 mt-0.5 uppercase font-black tracking-tighter">Automatic assignment priority</p>
                                    </div>
                                </label>

                                <div class="pt-4 mt-2 border-t border-gray-100 dark:border-surface-tonal-a30">
                                    <label class="flex items-center p-3 rounded-xl hover:bg-emerald-50/50 dark:hover:bg-emerald-500/5 transition-all cursor-pointer group">
                                        <input type="checkbox" name="is_active" value="1" checked
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
                                    Architect Provider
                                </button>
                                <a href="{{ route('shipping.couriers.index') }}"
                                    class="w-full flex items-center justify-center px-6 py-3 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-transparent text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                    Discard Configuration
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
