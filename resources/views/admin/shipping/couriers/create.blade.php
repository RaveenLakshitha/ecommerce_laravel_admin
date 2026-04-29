@extends('layouts.app')

@section('title', __('file.add_courier'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('shipping.couriers.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_provider_directory') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('file.add_courier') }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.add_courier_helper') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-courier-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_courier') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('shipping.couriers.store') }}" method="POST" id="create-courier-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Provider Identity --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.provider_identity') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.corporate_designation') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" required
                                        placeholder="e.g. DOMEX Logistics Solutions"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
                                    @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.operational_overview') }}</label>
                                    <textarea name="description" id="description" rows="3"
                                        placeholder="Define the utility and regional specializations of this partner..."
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md resize-y">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- API Integration --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.interface_configuration') }}</h2>
                                <span
                                    class="text-[9px] font-black text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded border border-amber-500/20 uppercase tracking-widest">{{ __('file.protocol_required') }}</span>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="base_url"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.primary_endpoint_url') }}</label>
                                    <div class="relative">
                                        <input type="url" name="base_url" id="base_url"
                                            placeholder="https://api.logistics-hub.com/v1/"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 pl-9 py-2 text-xs font-bold shadow-sm text-indigo-600 dark:text-indigo-400 outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-gray-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="api_key"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.access_key') }}</label>
                                        <input type="text" name="api_key" id="api_key"
                                            placeholder="Enter identification key"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                    <div>
                                        <label for="api_secret"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.secret_token') }}</label>
                                        <input type="password" name="api_secret" id="api_secret"
                                            placeholder="••••••••••••••••"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">
                        {{-- Service Capabilities --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.service_capabilities') }}</h2>
                            </div>
                            <div class="p-4 space-y-3">
                                <label
                                    class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                    <input type="checkbox" name="supports_tracking" value="1"
                                        class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                            {{ __('file.sync_tracking') }}</h3>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 uppercase tracking-widest">
                                            {{ __('file.automated_status_updates_via_api') }}</p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                    <input type="checkbox" name="supports_label_generation" value="1"
                                        class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                            {{ __('file.waybill_printing') }}</h3>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 uppercase tracking-widest">
                                            {{ __('file.generate_shipping_labels_on_demand') }}</p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                    <input type="checkbox" name="supports_cod" value="1"
                                        class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                            {{ __('file.financial_collection') }}</h3>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 uppercase tracking-widest">
                                            {{ __('file.supports_cash_on_delivery_processing') }}</p>
                                    </div>
                                </label>

                                <div class="pt-2">
                                    <label
                                        class="flex items-start p-3 rounded-xl border border-indigo-100 dark:border-indigo-500/20 bg-indigo-50/30 dark:bg-indigo-500/5 cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                        <input type="checkbox" name="default_for_cod" value="1"
                                            class="mt-1 h-4 w-4 rounded border-indigo-200 dark:border-indigo-500/30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                        <div class="ml-3">
                                            <h3
                                                class="text-xs font-bold text-indigo-900 dark:text-indigo-400 uppercase tracking-wider">
                                                {{ __('file.primary_cod_routing') }}</h3>
                                            <p
                                                class="text-[10px] text-indigo-600/60 dark:text-indigo-400/60 mt-0.5 uppercase font-black tracking-widest">
                                                {{ __('file.automatic_assignment_priority') }}</p>
                                        </div>
                                    </label>
                                </div>

                                <hr class="border-gray-100 dark:border-surface-tonal-a30">

                                <label
                                    class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-emerald-50/50 dark:hover:bg-emerald-500/5 transition-colors group">
                                    <input type="checkbox" name="is_active" value="1" checked
                                        class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-emerald-500 focus:ring-emerald-500 transition-all">
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition-colors uppercase tracking-wider">
                                            {{ __('file.operational_status') }}</h3>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            {{ __('file.available_for_logic_routing') }}</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div class="p-4 space-y-3">
                                <button type="submit" form="create-courier-form"
                                    class="w-full py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-[0.98]">
                                    {{ __('file.save_courier') }}
                                </button>
                                <a href="{{ route('shipping.couriers.index') }}"
                                    class="w-full flex items-center justify-center py-2.5 border border-gray-200 dark:border-surface-tonal-a30 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest rounded-xl hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                    {{ __('file.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection