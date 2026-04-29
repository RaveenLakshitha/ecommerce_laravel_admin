@extends('layouts.app')

@section('title', 'Payment Gateway Settings')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20 transition-all duration-300">
        <div class="max-w-5xl mx-auto">

            {{-- Header Area --}}
            <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 animate-fade-in-up">
                <div>
                    <h1
                        class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter">
                        Financial Nexus</h1>
                    <p
                        class="mt-6 text-sm text-gray-400 dark:text-gray-500 font-medium">
                        Configure discrete payment protocols, calibrate API credentials, and manage transaction
                        environments.</p>
                </div>
            </div>

            @if(session('success'))
                <div
                    class="mb-8 p-4 rounded-2xl bg-emerald-50/50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 flex items-center gap-3 animate-fade-in-scale">
                    <div
                        class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest">
                        {{ session('success') }}</p>
                </div>
            @endif

            <div class="space-y-10 animate-fade-in-scale">
                @foreach($settings as $gw => $setting)
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border {{ $setting->is_active ? 'border-indigo-500/30 dark:border-indigo-500/30' : 'border-gray-100 dark:border-surface-tonal-a30' }} overflow-hidden group/gw">
                        <div
                            class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30 flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-2xl {{ $setting->is_active ? 'bg-indigo-600 shadow-indigo-500/20' : 'bg-gray-200 dark:bg-surface-tonal-a30' }} flex items-center justify-center text-white shadow-lg transition-transform group-hover/gw:scale-105">
                                    @if($gw === 'payhere')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @elseif($gw === 'stripe')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                    {{ $setting->display_name ?? ucfirst($gw) }}</h3>
                            </div>
                            @if($setting->is_active)
                                <div
                                    class="px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span
                                        class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Nexus
                                        Active</span>
                                </div>
                            @else
                                <div
                                    class="px-3 py-1 rounded-full bg-gray-50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a40 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    <span
                                        class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Offline</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-8">
                            <form action="{{ route('settings.payment-gateways.update', $gw) }}" method="POST" class="space-y-8">
                                @csrf @method('PATCH')

                                <div class="flex items-center">
                                    <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                        <input type="checkbox" name="is_active" id="is_active_{{ $gw }}" value="1" {{ $setting->is_active ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 dark:bg-surface-tonal-a30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                        </div>
                                        <span
                                            class="ml-4 text-[10px] font-black text-gray-400 group-hover/toggle:text-indigo-500 transition-colors uppercase tracking-widest">Authorize
                                            Integration</span>
                                    </label>
                                </div>

                                <div class="h-px bg-gray-50 dark:bg-surface-tonal-a30"></div>

                                @if(!in_array($gw, ['cod', 'bank']))
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-3">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Simulation
                                                Layer (Environment)</label>
                                            <select name="environment"
                                                class="block w-full px-5 py-4 bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner appearance-none cursor-pointer">
                                                <option value="sandbox" {{ $setting->environment === 'sandbox' ? 'selected' : '' }}>
                                                    Sandbox / Development</option>
                                                <option value="live" {{ $setting->environment === 'live' ? 'selected' : '' }}>
                                                    Production / Hardened</option>
                                            </select>
                                        </div>

                                        @if($gw === 'payhere')
                                            <div class="space-y-3">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Corp-ID
                                                    (Merchant)</label>
                                                <input type="text" name="merchant_id"
                                                    value="{{ old('merchant_id', $setting->merchant_id) }}"
                                                    class="block w-full px-5 py-4 bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-gray-500 dark:text-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                                            </div>
                                        @endif

                                        <div class="md:col-span-2 space-y-3">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Public
                                                Cipher (Key / Client ID)</label>
                                            <input type="text" name="public_key"
                                                value="{{ old('public_key', $setting->public_key) }}"
                                                class="block w-full px-5 py-4 bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-gray-500 dark:text-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                                        </div>

                                        <div class="md:col-span-2 space-y-3">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Root
                                                Secret (Locked / Hidden)</label>
                                            <div class="relative group/secret">
                                                <input type="password" name="secret_key"
                                                    value="{{ old('secret_key', $setting->secret_key) }}"
                                                    class="block w-full px-5 py-4 bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                                                <div
                                                    class="absolute right-5 top-1/2 -translate-y-1/2 opacity-0 group-hover/secret:opacity-100 transition-opacity">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Execution
                                            Instructions</label>
                                        <textarea name="description" rows="3"
                                            class="block w-full px-5 py-4 bg-gray-100/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-bold text-gray-700 dark:text-gray-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner resize-none">{{ old('description', $setting->description) }}</textarea>
                                        <p class="text-[9px] font-black text-indigo-400/60 uppercase tracking-widest pl-2">
                                            Customer-facing protocol summary.</p>
                                    </div>
                                @endif

                                <div class="pt-6 flex justify-end">
                                    <button type="submit"
                                        class="px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-black dark:hover:bg-gray-100 shadow-xl transition-all active:scale-95 group">
                                        Sync Integration
                                        <svg class="w-4 h-4 ml-2 inline-block transition-transform group-hover:rotate-12"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-scale {
            from {
                opacity: 0;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-fade-in-scale {
            animation: fade-in-scale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
@endsection