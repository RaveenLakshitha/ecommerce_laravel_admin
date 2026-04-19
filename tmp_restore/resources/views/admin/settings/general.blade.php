@extends('layouts.app')

@section('title', __('file.clinic_settings'))

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20 transition-all duration-300">
    <div class="max-w-5xl mx-auto">
        
        {{-- Header Area --}}
        <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 animate-fade-in-up">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter decoration-indigo-500/30 underline underline-offset-8">System Parametrics</h1>
                <p class="mt-6 text-sm text-gray-400 dark:text-gray-500 font-medium italic underline decoration-indigo-500/10 underline-offset-4">Calibrate core store identity, regional localization, and architectural branding assets.</p>
            </div>
            <div class="flex items-center gap-3 animate-fade-in-up delay-100">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Center Return
                </a>
            </div>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="animate-fade-in-scale space-y-12">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            {{-- Section 1: Identity Matrix --}}
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30">
                    <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Identity Matrix</h3>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.site_name') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name) }}" required 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                            @error('site_name') <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.site_id') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="site_id" value="{{ old('site_id', $setting->site_id) }}" required 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-gray-500 dark:text-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.email_address') }} <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $setting->email) }}" required 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-bold text-gray-700 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.phone_number') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" required 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-black text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.address') }} <span class="text-rose-500">*</span></label>
                        <textarea name="address" rows="3" required 
                            class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-medium text-gray-700 dark:text-gray-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner resize-none">{{ old('address', $setting->address) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.website') }}</label>
                            <input type="url" name="website" value="{{ old('website', $setting->website) }}" 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-bold text-indigo-600 dark:text-indigo-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.tax_id') }}</label>
                            <input type="text" name="tax_id" value="{{ old('tax_id', $setting->tax_id) }}" 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-gray-500 dark:text-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Regional Calibration --}}
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30">
                    <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Regional Calibration</h3>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.timezone') }} <span class="text-rose-500">*</span></label>
                            <select name="timezone" required 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner appearance-none cursor-pointer">
                                @foreach(\DateTimeZone::listIdentifiers() as $tz)
                                    <option value="{{ $tz }}" {{ $setting->timezone == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.date_format') }}</label>
                            <select name="date_format" required 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner appearance-none cursor-pointer">
                                <option value="MM/DD/YYYY" {{ $setting->date_format == 'MM/DD/YYYY' ? 'selected' : '' }}>MM / DD / YYYY</option>
                                <option value="DD/MM/YYYY" {{ $setting->date_format == 'DD/MM/YYYY' ? 'selected' : '' }}>DD / MM / YYYY</option>
                                <option value="YYYY-MM-DD" {{ $setting->date_format == 'YYYY-MM-DD' ? 'selected' : '' }}>YYYY - MM - DD</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.time_format') }}</label>
                            <select name="time_format" required 
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner appearance-none cursor-pointer">
                                <option value="12-hour" {{ $setting->time_format == '12-hour' ? 'selected' : '' }}>12 - HOUR (AM/PM)</option>
                                <option value="24-hour" {{ $setting->time_format == '24-hour' ? 'selected' : '' }}>24 - HOUR (MILITARY)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3" x-data="{
                            currencies: [
                                { code: 'USD', symbol: '$', name: 'US Dollar' },
                                { code: 'EUR', symbol: '€', name: 'Euro' },
                                { code: 'GBP', symbol: '£', name: 'British Pound' },
                                { code: 'JPY', symbol: '¥', name: 'Japanese Yen' },
                                { code: 'CNY', symbol: '¥', name: 'Chinese Yuan' },
                                { code: 'INR', symbol: '₹', name: 'Indian Rupee' },
                                { code: 'LKR', symbol: 'Rs.', name: 'Sri Lankan Rupee' },
                                { code: 'AUD', symbol: 'A$', name: 'Australian Dollar' },
                                { code: 'CAD', symbol: 'C$', name: 'Canadian Dollar' },
                                { code: 'CHF', symbol: 'CHF', name: 'Swiss Franc' },
                                { code: 'MXN', symbol: '$', name: 'Mexican Peso' },
                                { code: 'BRL', symbol: 'R$', name: 'Brazilian Real' },
                                { code: 'KRW', symbol: '₩', name: 'South Korean Won' },
                                { code: 'SGD', symbol: 'S$', name: 'Singapore Dollar' },
                                { code: 'HKD', symbol: 'HK$', name: 'Hong Kong Dollar' },
                                { code: 'SEK', symbol: 'kr', name: 'Swedish Krona' },
                                { code: 'NOK', symbol: 'kr', name: 'Norwegian Krone' },
                                { code: 'DKK', symbol: 'kr', name: 'Danish Krone' },
                                { code: 'NZD', symbol: 'NZ$', name: 'New Zealand Dollar' },
                                { code: 'ZAR', symbol: 'R', name: 'South African Rand' },
                                { code: 'THB', symbol: '฿', name: 'Thai Baht' },
                                { code: 'PHP', symbol: '₱', name: 'Philippine Peso' },
                                { code: 'MYR', symbol: 'RM', name: 'Malaysian Ringgit' },
                                { code: 'IDR', symbol: 'Rp', name: 'Indonesian Rupiah' },
                                { code: 'TWD', symbol: 'NT$', name: 'Taiwan Dollar' },
                                { code: 'AED', symbol: 'د.إ', name: 'UAE Dirham' },
                                { code: 'SAR', symbol: '﷼', name: 'Saudi Riyal' },
                                { code: 'TRY', symbol: '₺', name: 'Turkish Lira' },
                                { code: 'PLN', symbol: 'zł', name: 'Polish Zloty' },
                                { code: 'RUB', symbol: '₽', name: 'Russian Ruble' },
                                { code: 'EGP', symbol: 'E£', name: 'Egyptian Pound' },
                                { code: 'NGN', symbol: '₦', name: 'Nigerian Naira' },
                                { code: 'PKR', symbol: '₨', name: 'Pakistani Rupee' },
                                { code: 'BDT', symbol: '৳', name: 'Bangladeshi Taka' },
                                { code: 'VND', symbol: '₫', name: 'Vietnamese Dong' },
                                { code: 'COP', symbol: '$', name: 'Colombian Peso' },
                                { code: 'ARS', symbol: '$', name: 'Argentine Peso' },
                                { code: 'CLP', symbol: '$', name: 'Chilean Peso' },
                                { code: 'PEN', symbol: 'S/.', name: 'Peruvian Sol' },
                            ],
                            selected: '{{ old('currency', $setting->currency ?? 'USD') }}',
                            get selectedCurrency() {
                                return this.currencies.find(c => c.code === this.selected) || { code: this.selected, symbol: '$', name: '' };
                            },
                            updateSymbol() {
                                const c = this.selectedCurrency;
                                document.getElementById('currency_symbol_input').value = c.symbol;
                            }
                        }" x-init="updateSymbol()">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.currency_code') }} <span class="text-rose-500">*</span></label>
                            <select name="currency" required x-model="selected" @change="updateSymbol()"
                                class="block w-full px-5 py-4 bg-gray-50/50 dark:bg-surface-tonal-a30/50 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-bold text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner appearance-none cursor-pointer">
                                <template x-for="c in currencies" :key="c.code">
                                    <option :value="c.code" :selected="c.code === selected" x-text="c.code + ' (' + c.symbol + ') — ' + c.name"></option>
                                </template>
                            </select>
                            <input type="hidden" name="currency_symbol" id="currency_symbol_input" value="{{ old('currency_symbol', $setting->currency_symbol ?? '$') }}">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                                Preview: <span class="font-bold text-gray-700 dark:text-gray-300" x-text="selectedCurrency.symbol + ' 1,234.00'"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Branding Interface --}}
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-surface-tonal-a30 bg-gray-50/30 dark:bg-surface-tonal-a10/30">
                    <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Branding Interface</h3>
                </div>
                <div class="p-8 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.site_logo') }}</label>
                            <div class="flex flex-col gap-6">
                                @if($setting->logo_path)
                                    <div class="p-6 bg-gray-50/50 dark:bg-surface-tonal-a30/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 flex items-center justify-center shadow-inner group/logo">
                                        <img src="{{ asset('storage/' . $setting->logo_path) }}" class="h-24 w-auto object-contain drop-shadow-md group-hover/logo:scale-105 transition-transform" alt="Current Logo">
                                    </div>
                                @endif
                                <div class="relative group/upload">
                                    <input type="file" name="logo" accept=".png,.jpg,.jpeg,.svg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="w-full px-6 py-4 bg-white dark:bg-surface-tonal-a10 border-2 border-dashed border-gray-200 dark:border-surface-tonal-a30 rounded-2xl flex items-center justify-center gap-3 group-hover/upload:border-indigo-500 dark:group-hover/upload:border-indigo-500 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 group-hover/upload:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span class="text-[10px] font-black text-gray-400 group-hover/upload:text-indigo-600 dark:group-hover/upload:text-indigo-400 uppercase tracking-widest transition-colors">Overwrite Master Asset</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.primary_color') }}</label>
                            <div class="p-8 bg-gray-50/50 dark:bg-surface-tonal-a30/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 space-y-6 shadow-inner">
                                <div class="flex items-center gap-6">
                                    <div class="relative group/color">
                                        <input type="color" name="primary_color" value="{{ old('primary_color', $setting->primary_color) }}" required 
                                            class="w-20 h-20 rounded-2xl border-0 p-0 cursor-pointer overflow-hidden shadow-lg rotate-3 group-hover/color:rotate-0 transition-transform">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <input type="text" id="color_hex_display" value="{{ old('primary_color', $setting->primary_color) }}" readonly 
                                            class="w-32 px-4 py-2 bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 rounded-xl text-xs font-mono font-black text-gray-700 dark:text-white uppercase text-center shadow-sm">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Chromatic ID</span>
                                    </div>
                                </div>
                                <p class="text-[11px] font-bold text-gray-400 leading-relaxed italic border-l-2 border-indigo-500/20 pl-4">System-wide UI saturation level. Impacts primary interactive elements and visual highlights.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Governance --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-6 animate-fade-in-up delay-200">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-10 py-5 bg-indigo-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-3xl hover:bg-indigo-700 shadow-2xl shadow-indigo-500/30 transition-all active:scale-[0.98] group">
                    Commit Changes
                    <svg class="w-4 h-4 ml-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-10 py-5 bg-transparent border border-gray-200 dark:border-surface-tonal-a30 rounded-3xl text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">Abat Protocol</a>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in-scale {
        from { opacity: 0; transform: scale(0.98); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in-scale { animation: fade-in-scale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const colorInput = document.querySelector('input[name="primary_color"]');
        const colorDisplay = document.getElementById('color_hex_display');
        if (colorInput && colorDisplay) {
            colorInput.addEventListener('input', function () {
                colorDisplay.value = this.value.toUpperCase();
            });
        }
    });
</script>
@endsection
