@extends('layouts.app')

@section('title', __('file.application_settings'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 mt-10">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.application_settings') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.configure_global_parameters') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-settings-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_settings') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data"
                id="edit-settings-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Clinic Information --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.clinic_information') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="site_name"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.clinic_name') }}</label>
                                        <input type="text" name="site_name" id="site_name"
                                            value="{{ old('site_name', $setting->site_name) }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                    <div>
                                        <label for="site_id"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.registration_number') }}</label>
                                        <input type="text" name="site_id" id="site_id"
                                            value="{{ old('site_id', $setting->site_id) }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                    <div>
                                        <label for="email"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.email_address') }}</label>
                                        <input type="email" name="email" id="email"
                                            value="{{ old('email', $setting->email) }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                    <div>
                                        <label for="phone"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.phone_number') }}</label>
                                        <input type="text" name="phone" id="phone"
                                            value="{{ old('phone', $setting->phone) }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                </div>
                                <div>
                                    <label for="address"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.address') }}</label>
                                    <textarea name="address" id="address" rows="3"
                                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 resize-y">{{ old('address', $setting->address) }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="website"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.website') }}</label>
                                        <input type="text" name="website" id="website"
                                            value="{{ old('website', $setting->website) }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                    <div>
                                        <label for="tax_id"
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.tax_id') }}</label>
                                        <input type="text" name="tax_id" id="tax_id"
                                            value="{{ old('tax_id', $setting->tax_id) }}"
                                            class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Operating Hours --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.operating_hours') }}
                                </h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.weekdays') }}</label>
                                        <div class="flex items-center gap-2">
                                            <input type="time" name="weekday_open"
                                                value="{{ $setting->operating_hours['weekdays'][0] ?? '08:00' }}" required
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-black dark:text-white outline-none focus:bg-white">
                                            <span
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.to') }}</span>
                                            <input type="time" name="weekday_close"
                                                value="{{ $setting->operating_hours['weekdays'][1] ?? '18:00' }}" required
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-black dark:text-white outline-none focus:bg-white">
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.weekends') }}</label>
                                        <div class="flex items-center gap-2">
                                            <input type="time" name="weekend_open"
                                                value="{{ $setting->operating_hours['weekends'][0] === 'closed' ? '' : $setting->operating_hours['weekends'][0] }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-black dark:text-white outline-none focus:bg-white">
                                            <span
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.to') }}</span>
                                            <input type="time" name="weekend_close"
                                                value="{{ $setting->operating_hours['weekends'][1] === 'closed' ? '' : $setting->operating_hours['weekends'][1] }}"
                                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-black dark:text-white outline-none focus:bg-white">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">

                        {{-- Branding & Appearance --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.branding_and_appearance') }}</h2>
                            </div>
                            <div class="p-4 space-y-6">
                                <div class="space-y-2">
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.clinic_logo') }}
                                        (512x512px)</label>
                                    <div class="aspect-square admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all overflow-hidden relative group"
                                        onclick="document.getElementById('logo-input').click()">
                                        <img id="logo-preview"
                                            src="{{ $setting->logo_path ? asset('storage/' . $setting->logo_path) : '' }}"
                                            class="absolute inset-0 w-full h-full object-contain {{ $setting->logo_path ? '' : 'hidden' }} z-10 p-4">
                                        <div id="logo-placeholder"
                                            class="flex flex-col items-center justify-center gap-1 {{ $setting->logo_path ? 'hidden' : '' }}">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                {{ __('file.change_logo') }}</p>
                                        </div>
                                        <input type="file" name="logo" id="logo-input" class="hidden" accept="image/*"
                                            onchange="previewImage(this, 'logo-preview', 'logo-placeholder')">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.favicon') }}
                                        (32x32px)</label>
                                    <div class="w-20 h-20 admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-indigo-400 transition-all overflow-hidden relative group"
                                        onclick="document.getElementById('favicon-input').click()">
                                        <img id="favicon-preview"
                                            src="{{ $setting->favicon_path ? asset('storage/' . $setting->favicon_path) : '' }}"
                                            class="absolute inset-0 w-full h-full object-contain {{ $setting->favicon_path ? '' : 'hidden' }} z-10 p-2">
                                        <div id="favicon-placeholder"
                                            class="flex flex-col items-center justify-center gap-1 {{ $setting->favicon_path ? 'hidden' : '' }}">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="file" name="favicon" id="favicon-input" class="hidden" accept="image/*"
                                            onchange="previewImage(this, 'favicon-preview', 'favicon-placeholder')">
                                    </div>
                                </div>

                                <div>
                                    <label for="primary_color"
                                        class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.primary_color') }}</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" name="primary_color" id="primary_color"
                                            value="{{ old('primary_color', $setting->primary_color) }}"
                                            class="h-10 w-20 rounded border border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/30 cursor-pointer">
                                        <span
                                            class="text-xs font-mono font-bold text-gray-500 uppercase tracking-widest">{{ old('primary_color', $setting->primary_color) }}</span>
                                    </div>
                                </div>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" form="edit-settings-form"
                                        class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.save_settings') }}
                                    </button>
                                    <a href="{{ url()->previous() }}"
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

    @push('scripts')
        <script>
            function previewImage(input, previewId, placeholderId) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById(previewId).src = e.target.result;
                        document.getElementById(previewId).classList.remove('hidden');
                        document.getElementById(placeholderId).classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection