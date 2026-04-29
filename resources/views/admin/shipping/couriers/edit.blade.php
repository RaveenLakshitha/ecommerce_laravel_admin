@extends('layouts.app')

@section('title', __('file.edit_courier') . ': ' . $courier->name)

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('shipping.couriers.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_couriers') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.edit_courier') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('file.updating_logistics_parameters_for') }} <span
                            class="text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-tighter">{{ $courier->name }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('shipping.couriers.destroy', $courier) }}" method="POST"
                        onsubmit="return confirm('{{ __('file.confirm_delete_courier') }}');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-600 transition-colors flex items-center gap-2 px-4 py-2.5 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('file.terminate_partnership') }}
                        </button>
                    </form>
                    <button type="submit" form="edit-courier-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.update_courier') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('shipping.couriers.update', $courier) }}" method="POST" id="edit-courier-form">
                @csrf
                @method('PUT')

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
                                    <input type="text" name="name" id="name" value="{{ old('name', $courier->name) }}"
                                        required
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                </div>
                                <div>
                                    <label for="description"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.operational_overview') }}</label>
                                    <textarea name="description" id="description" rows="4"
                                        placeholder="{{ __('file.define_utility_specializations') }}"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 resize-y">{{ old('description', $courier->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Interface Configuration --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.interface_configuration') }}</h2>
                                <span
                                    class="text-[8px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20 uppercase tracking-widest italic">{{ __('file.encrypted_secure_tunnel') }}</span>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="base_url"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.primary_endpoint_url') }}</label>
                                    <div class="relative">
                                        <input type="url" name="base_url" id="base_url"
                                            value="{{ old('base_url', $courier->base_url) }}"
                                            placeholder="https://api.logistics-hub.com/v1/"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono text-indigo-600 dark:text-indigo-400 outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="api_key"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.access_key') }}</label>
                                        <input type="text" name="api_key" id="api_key"
                                            value="{{ old('api_key', $courier->api_key) }}" placeholder="Redacted"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                    <div>
                                        <label for="api_secret"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.secret_token') }}</label>
                                        <input type="password" name="api_secret" id="api_secret"
                                            value="{{ old('api_secret', $courier->api_secret) }}"
                                            placeholder="••••••••••••••••"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">

                        {{-- Service Capabilities --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.service_capabilities') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">

                                <label
                                    class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="supports_tracking" value="1" {{ old('supports_tracking', $courier->supports_tracking) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white leading-none uppercase tracking-wider">
                                            {{ __('file.sync_tracking') }}</h3>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                            {{ __('file.automated_status_updates') }}</p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="supports_label_generation" value="1" {{ old('supports_label_generation', $courier->supports_label_generation) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white leading-none uppercase tracking-wider">
                                            {{ __('file.waybill_printing') }}</h3>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                            {{ __('file.generate_shipping_labels') }}</p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="supports_cod" value="1" {{ old('supports_cod', $courier->supports_cod) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-gray-900 dark:text-white leading-none uppercase tracking-wider">
                                            {{ __('file.financial_collection') }}</h3>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                            {{ __('file.supports_cod_processing') }}</p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-start py-2.5 px-3 rounded-lg border border-indigo-100 dark:border-indigo-500/20 bg-indigo-50/30 dark:bg-indigo-500/5 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="default_for_cod" value="1" {{ old('default_for_cod', $courier->default_for_cod) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-indigo-200 dark:border-indigo-500/30 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-xs font-bold text-indigo-900 dark:text-indigo-400 leading-none uppercase tracking-wider">
                                            {{ __('file.primary_cod_routing') }}</h3>
                                        <p
                                            class="text-[10px] text-indigo-600/60 dark:text-indigo-400/60 font-black mt-1 uppercase tracking-tighter">
                                            {{ __('file.automatic_assignment_priority') }}</p>
                                    </div>
                                </label>

                                <div class="pt-4 border-t border-gray-50 dark:border-surface-tonal-a30">
                                    <label
                                        class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition cursor-pointer group">
                                        <div class="mt-0.5">
                                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $courier->is_active) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-emerald-500 focus:ring-emerald-500 transition-all cursor-pointer">
                                        </div>
                                        <div class="ml-3">
                                            <h3
                                                class="text-xs font-bold text-gray-900 dark:text-white leading-none uppercase tracking-wider group-hover:text-emerald-600 transition-colors">
                                                {{ __('file.operational_status') }}</h3>
                                            <p
                                                class="text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">
                                                {{ __('file.available_for_logic_routing') }}</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" form="edit-courier-form"
                                        class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.update_courier') }}
                                    </button>
                                    <a href="{{ route('shipping.couriers.index') }}"
                                        class="px-6 py-3 border border-gray-200 dark:border-surface-tonal-a30 text-gray-500 text-sm font-bold rounded-xl text-center hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                        {{ __('file.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection