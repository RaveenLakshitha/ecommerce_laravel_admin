@extends('layouts.app')

@section('title', __('file.payment_gateways'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('settings.general') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_settings') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('file.payment_gateways') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure discrete payment protocols, calibrate API credentials, and manage transaction environments.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 flex items-center gap-3 animate-fade-in-up">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($settings as $gw => $setting)
                    <div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border {{ $setting->is_active ? 'border-indigo-500/30' : 'border-gray-200 dark:border-surface-tonal-a30' }} overflow-hidden group/gw flex flex-col">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $setting->is_active ? 'bg-indigo-600' : 'bg-gray-100 dark:bg-surface-tonal-a30' }} flex items-center justify-center text-white transition-transform group-hover/gw:scale-110">
                                    @if($gw === 'payhere')
                                        <svg class="w-4 h-4 {{ $setting->is_active ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @elseif($gw === 'stripe')
                                        <svg class="w-4 h-4 {{ $setting->is_active ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 {{ $setting->is_active ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    @endif
                                </div>
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ $setting->display_name ?? ucfirst($gw) }}</h2>
                            </div>
                            @if($setting->is_active)
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 uppercase tracking-wider">{{ __('file.active') }}</span>
                            @else
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-surface-tonal-a30 dark:text-gray-400 border border-gray-200 dark:border-surface-tonal-a30 uppercase tracking-wider">{{ __('file.inactive') }}</span>
                            @endif
                        </div>

                        <div class="p-6 flex-grow">
                            <form action="{{ route('settings.payment-gateways.update', $gw) }}" method="POST" class="space-y-6">
                                @csrf @method('PATCH')

                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('file.status_visibility') }}</label>
                                    <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                        <input type="checkbox" name="is_active" id="is_active_{{ $gw }}" value="1" {{ $setting->is_active ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-10 h-5 bg-gray-200 dark:bg-surface-tonal-a30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>

                                <div class="h-px bg-gray-100 dark:bg-surface-tonal-a30"></div>

                                @if(!in_array($gw, ['cod', 'bank']))
                                    <div class="space-y-4">
                                        <div class="space-y-1.5">
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Environment</label>
                                            <select name="environment" class="block w-full px-4 py-2.5 bg-gray-50 dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-medium text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                                <option value="sandbox" {{ $setting->environment === 'sandbox' ? 'selected' : '' }}>Sandbox / Development</option>
                                                <option value="live" {{ $setting->environment === 'live' ? 'selected' : '' }}>Production / Live</option>
                                            </select>
                                        </div>

                                        @if($gw === 'payhere')
                                            <div class="space-y-1.5">
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Merchant ID</label>
                                                <input type="text" name="merchant_id" value="{{ old('merchant_id', $setting->merchant_id) }}" class="block w-full px-4 py-2.5 bg-gray-50 dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-mono text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                            </div>
                                        @endif

                                        <div class="space-y-1.5">
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Public Key / Client ID</label>
                                            <input type="text" name="public_key" value="{{ old('public_key', $setting->public_key) }}" class="block w-full px-4 py-2.5 bg-gray-50 dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-mono text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Secret Key</label>
                                            <div class="relative group/secret">
                                                <input type="password" name="secret_key" value="{{ old('secret_key', $setting->secret_key) }}" class="block w-full px-4 py-2.5 bg-gray-50 dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-mono text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('file.description') }}</label>
                                        <textarea name="description" rows="4" class="block w-full px-4 py-2.5 bg-gray-50 dark:bg-surface-tonal-a10 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all resize-none">{{ old('description', $setting->description) }}</textarea>
                                    </div>
                                @endif

                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-lg hover:bg-black dark:hover:bg-gray-100 shadow-sm transition-all active:scale-[0.98] flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        Update Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection