@extends('layouts.app')

@section('title', 'Site Settings')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        /* Hide scrollbar on tab nav */
        .tab-scroll::-webkit-scrollbar { display: none; }
        .tab-scroll { -ms-overflow-style: none; scrollbar-style: none; }

        /* Active tab underline */
        .tab-btn { position: relative; white-space: nowrap; }
        .tab-btn.is-active { color: #111827 !important; }
        .dark .tab-btn.is-active { color: #f9fafb !important; }
        .tab-btn.is-active::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 2px;
            background: #111827;
            border-radius: 2px 2px 0 0;
        }
        .dark .tab-btn.is-active::after { background: #f9fafb; }

        /* Tab panels */
        .tab-panel { display: none; }
        .tab-panel.is-active { display: block; }

        /* Sidebar active link */
        .sidebar-link.is-active {
            background: #f3f4f6;
            color: #111827;
            font-weight: 600;
        }
        .dark .sidebar-link.is-active {
            background: rgba(255,255,255,0.06);
            color: #f9fafb;
        }

        /* Remove number spinners */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
        input[type=number] { -moz-appearance: textfield; }

        /* Unified field style */
        .fi {
            display: block; width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            color: #111827;
            transition: border-color 0.15s, box-shadow 0.15s;
            appearance: none;
        }
        .fi::placeholder { color: #9ca3af; }
        .fi:focus { outline: none; border-color: #9ca3af; box-shadow: 0 0 0 3px rgba(17,24,39,0.07); }
        .dark .fi { border-color: #374151; background: #1f2937; color: #f9fafb; }
        .dark .fi:focus { border-color: #4b5563; box-shadow: 0 0 0 3px rgba(255,255,255,0.06); }

        /* Toggle */
        .tgl { position: relative; display: inline-flex; align-items: center; cursor: pointer; flex-shrink: 0; }
        .tgl input { position: absolute; opacity: 0; width: 0; height: 0; }
        .tgl-track {
            width: 40px; height: 22px;
            background: #d1d5db;
            border-radius: 999px;
            position: relative;
            transition: background 0.2s;
            pointer-events: none;
        }
        .tgl-track::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 16px; height: 16px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,.15);
        }
        .tgl input:checked ~ .tgl-track { background: #111827; }
        .tgl input:checked ~ .tgl-track::after { transform: translateX(18px); }
        .dark .tgl input:checked ~ .tgl-track { background: #e5e7eb; }
        .dark .tgl input:checked ~ .tgl-track::after { background: #111827; }

        /* Check card */
        .ck-card {
            display: flex; align-items: flex-start; gap: 0.75rem;
            padding: 1rem; border-radius: 0.875rem;
            border: 1px solid #e5e7eb;
            background: #f9fafb; cursor: pointer;
            transition: border-color 0.15s;
        }
        .ck-card:hover { border-color: #9ca3af; background: #fff; }
        .dark .ck-card { border-color: #374151; background: #1f2937; }
        .dark .ck-card:hover { border-color: #4b5563; background: #1f2937; }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-16">

        {{-- Page Header --}}
        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors mb-4 uppercase tracking-widest">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Dashboard
            </a>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Settings</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your store settings and preferences.</p>
        </div>

        {{-- Success alert --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-white dark:bg-gray-900 border border-emerald-200 dark:border-emerald-800/40 rounded-xl px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400 shadow-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error alert --}}
        @if($errors->any())
            <div class="mb-6 bg-white dark:bg-gray-900 border border-red-200 dark:border-red-800/40 rounded-xl px-4 py-3 shadow-sm">
                <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Please fix the following errors:</p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2 text-xs text-red-600 dark:text-red-400">
                            <span class="w-1 h-1 rounded-full bg-red-400 shrink-0 inline-block"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── Tab Bar ─────────────────────────────────────────────────────── --}}
        @php
            $tabs = [
                'general' => 'General',
                'store' => 'Store Info',
                'appearance' => 'Appearance',
                'currency' => 'Currency',
                'seo' => 'SEO & Meta',
                'shipping' => 'Shipping',
                'tax' => 'Tax',
                'payments' => 'Payments',
                'inventory' => 'Inventory',
                'customers' => 'Customers',
                'features' => 'Features',
                'marketing' => 'Marketing',
                'social' => 'Social',
                'analytics' => 'Analytics',
                'email' => 'Email',
                'maintenance' => 'Maintenance',
            ];
        @endphp



        {{-- ── Main Form ───────────────────────────────────────────────────── --}}
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ── Left: Section Nav & Global Actions ────────────────────── --}}
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <div class="sticky top-24 space-y-6">
                        
                        {{-- Save / Discard --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden animate-fade-in-up">
                            <div class="px-5 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a10/30">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Global Governance</p>
                            </div>
                            <div class="p-4 space-y-3">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gray-950 dark:bg-white text-sm font-bold text-white dark:text-gray-900 hover:bg-black dark:hover:bg-gray-100 transition-all active:scale-[0.98] shadow-lg group">
                                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Commit Sync
                                </button>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="w-full flex items-center justify-center px-4 py-2.5 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a10 transition-all text-center">
                                    Abort Session
                                </a>
                            </div>
                        </div>

                        {{-- Section Quick-Nav --}}
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden animate-fade-in-up delay-100">
                            <div class="px-5 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">System Modules</p>
                            </div>
                            <div class="p-2.5 space-y-1">
                                @foreach($tabs as $key => $label)
                                    <button type="button" onclick="switchTab('{{ $key }}')" id="sidebar-{{ $key }}"
                                        class="sidebar-link w-full text-left px-4 py-3 rounded-xl text-xs font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-50/80 dark:hover:bg-surface-tonal-a30/50 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-20"></span>
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Right: Content Panels ────────────────────────────────── --}}
                <div class="lg:col-span-2 order-1 lg:order-2">
                    
                    {{-- General thru Payments (Already Inlined but now inside this col) --}}
                    {{-- Inventory thru Maintenance (Already Inlined but now inside this col) --}}
                    {{-- I will re-insert them correctly --}}
                    
                    {{-- ══════════════ GENERAL ══════════════ --}}
                    <div class="tab-panel" id="panel-general">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">General Settings</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Basic site identity and brand assets.</p>
                            </div>
                            <div class="p-6 space-y-5">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            Site Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="site_name" id="site_name"
                                            value="{{ old('site_name', $setting->site_name) }}"
                                            placeholder="My Awesome Store" class="fi">
                                        @error('site_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="site_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Site Title Tag</label>
                                        <input type="text" name="site_title" id="site_title"
                                            value="{{ old('site_title', $setting->site_title) }}"
                                            placeholder="Trendy Fashion for Everyone" class="fi">
                                        @error('site_title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="site_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Short Description</label>
                                    <textarea name="site_description" id="site_description" rows="3"
                                        placeholder="A brief overview of your business." class="fi resize-none">{{ old('site_description', $setting->site_description) }}</textarea>
                                    <p class="text-xs text-gray-400 mt-1">Used as the meta description for SEO.</p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Brand Assets</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        {{-- Logo --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Logo</label>
                                            <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-surface-tonal-a10 p-4 flex flex-col items-center gap-3 text-center">
                                                <div class="w-full h-20 rounded-lg bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 flex items-center justify-center overflow-hidden">
                                                    <img id="logo_preview"
                                                        src="{{ $setting->site_logo ? Storage::url($setting->site_logo) : asset('images/default-logo.png') }}"
                                                        class="max-h-14 w-auto object-contain" alt="Logo">
                                                </div>
                                                <input type="file" name="site_logo" id="site_logo" class="hidden" accept="image/*"
                                                    onchange="previewImage(this,'logo_preview')">
                                                <label for="site_logo" class="text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 cursor-pointer transition">Upload Logo</label>
                                                <p class="text-[10px] text-gray-400">250×80px · PNG, SVG, JPG</p>
                                            </div>
                                            @error('site_logo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        {{-- Favicon --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                                            <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-surface-tonal-a10 p-4 flex flex-col items-center gap-3 text-center">
                                                <div class="w-full h-20 rounded-lg bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 flex items-center justify-center overflow-hidden">
                                                    <img id="favicon_preview"
                                                        src="{{ $setting->site_favicon ? Storage::url($setting->site_favicon) : asset('images/favicon.ico') }}"
                                                        class="h-10 w-10 object-contain" alt="Favicon">
                                                </div>
                                                <input type="file" name="site_favicon" id="site_favicon" class="hidden" accept="image/*,.ico"
                                                    onchange="previewImage(this,'favicon_preview')">
                                                <label for="site_favicon" class="text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 cursor-pointer transition">Upload Favicon</label>
                                                <p class="text-[10px] text-gray-400">32×32 or 64×64px · ICO, PNG</p>
                                            </div>
                                            @error('site_favicon')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ STORE INFO ══════════════ --}}
                    <div class="tab-panel" id="panel-store">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Store Information</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Contact details and physical address.</p>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Contact Email <span class="text-red-500">*</span></label>
                                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $setting->contact_email) }}" placeholder="hello@store.com" class="fi">
                                    </div>
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Contact Phone <span class="text-red-500">*</span></label>
                                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $setting->contact_phone) }}" placeholder="+1 555-0123" class="fi">
                                    </div>
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Address <span class="text-red-500">*</span></label>
                                    <textarea name="address" id="address" rows="2" placeholder="Street, Building, Suite" class="fi resize-none">{{ old('address', $setting->address) }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">City</label>
                                        <input type="text" name="city" id="city" value="{{ old('city', $setting->city) }}" placeholder="New York" class="fi">
                                    </div>
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">State / Province</label>
                                        <input type="text" name="state" id="state" value="{{ old('state', $setting->state) }}" placeholder="NY" class="fi">
                                    </div>
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Country</label>
                                        <input type="text" name="country" id="country" value="{{ old('country', $setting->country) }}" placeholder="United States" class="fi">
                                    </div>
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Postal / Zip Code</label>
                                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $setting->postal_code) }}" placeholder="10001" class="fi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ APPEARANCE ══════════════ --}}
                    <div class="tab-panel" id="panel-appearance">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Appearance Settings</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Colors, theme and layout preferences.</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Brand Colors</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                        @foreach([['primary_color', 'Primary', '#e8c547'], ['secondary_color', 'Secondary', '#ffffff'], ['accent_color', 'Accent', '#000000']] as [$cn, $cl, $cd])
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ $cl }}</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="color" id="{{ $cn }}_picker"
                                                        value="{{ old($cn, $setting->{$cn} ?? $cd) }}"
                                                        class="h-10 w-12 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 p-0.5 bg-white dark:bg-surface-tonal-a20 cursor-pointer"
                                                        oninput="document.getElementById('{{ $cn }}').value=this.value">
                                                    <input type="text" name="{{ $cn }}" id="{{ $cn }}"
                                                        value="{{ old($cn, $setting->{$cn} ?? $cd) }}"
                                                        placeholder="#HEX" class="fi font-mono"
                                                        oninput="document.getElementById('{{ $cn }}_picker').value=this.value">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <div>
                                        <label for="theme_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Theme Mode</label>
                                        <select name="theme_mode" id="theme_mode" class="fi">
                                            <option value="light" @selected(old('theme_mode', $setting->theme_mode) == 'light')>Light</option>
                                            <option value="dark" @selected(old('theme_mode', $setting->theme_mode) == 'dark')>Dark</option>
                                            <option value="auto" @selected(old('theme_mode', $setting->theme_mode) == 'auto')>System Auto</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="header_style" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Header Style</label>
                                        <select name="header_style" id="header_style" class="fi">
                                            <option value="minimal" @selected(old('header_style', $setting->header_style) == 'minimal')>Minimalist</option>
                                            <option value="centered" @selected(old('header_style', $setting->header_style) == 'centered')>Centered Logo</option>
                                            <option value="expanded" @selected(old('header_style', $setting->header_style) == 'expanded')>Expanded</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="footer_style" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Footer Style</label>
                                        <select name="footer_style" id="footer_style" class="fi">
                                            <option value="simple" @selected(old('footer_style', $setting->footer_style) == 'simple')>Simple Links</option>
                                            <option value="multi-column" @selected(old('footer_style', $setting->footer_style) == 'multi-column')>Multi-column</option>
                                            <option value="noir" @selected(old('footer_style', $setting->footer_style) == 'noir')>Noir</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ CURRENCY ══════════════ --}}
                    <div class="tab-panel" id="panel-currency">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Currency & Pricing</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Configure how prices are displayed across your store.</p>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Store Currency <span class="text-red-500">*</span></label>
                                        <select name="currency" id="currency" class="fi">
                                            <option value="USD" @selected(old('currency', $setting->currency) == 'USD')>USD – US Dollar</option>
                                            <option value="LKR" @selected(old('currency', $setting->currency) == 'LKR')>LKR – Sri Lankan Rupee</option>
                                            <option value="EUR" @selected(old('currency', $setting->currency) == 'EUR')>EUR – Euro</option>
                                            <option value="GBP" @selected(old('currency', $setting->currency) == 'GBP')>GBP – British Pound</option>
                                        </select>
                                        @error('currency')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="currency_symbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Currency Symbol</label>
                                        <input type="text" name="currency_symbol" id="currency_symbol" value="{{ old('currency_symbol', $setting->currency_symbol) }}" placeholder="$, Rs., £" class="fi">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="currency_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Symbol Position</label>
                                        <select name="currency_position" id="currency_position" class="fi">
                                            <option value="left" @selected(old('currency_position', $setting->currency_position) == 'left')>Left ($100)</option>
                                            <option value="right" @selected(old('currency_position', $setting->currency_position) == 'right')>Right (100$)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="number_of_decimals" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Decimal Points</label>
                                        <input type="number" name="number_of_decimals" id="number_of_decimals" value="{{ old('number_of_decimals', $setting->number_of_decimals ?? 2) }}" placeholder="2" min="0" max="4" class="fi">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="decimal_separator" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Decimal Separator</label>
                                        <input type="text" name="decimal_separator" id="decimal_separator" value="{{ old('decimal_separator', $setting->decimal_separator ?? '.') }}" maxlength="1" class="fi">
                                    </div>
                                    <div>
                                        <label for="thousands_separator" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Thousands Separator</label>
                                        <input type="text" name="thousands_separator" id="thousands_separator" value="{{ old('thousands_separator', $setting->thousands_separator ?? ',') }}" maxlength="1" class="fi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ SEO ══════════════ --}}
                    <div class="tab-panel" id="panel-seo">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">SEO & Social Meta</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Search engine and social sharing metadata.</p>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Global Meta Title</label>
                                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $setting->meta_title) }}" placeholder="My Store – Trendy Fashion Online" class="fi">
                                </div>
                                <div>
                                    <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Global Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" rows="3" placeholder="What users will see in Google search." class="fi resize-none">{{ old('meta_description', $setting->meta_description) }}</textarea>
                                </div>
                                <div>
                                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $setting->meta_keywords) }}" placeholder="clothing, fashion, online store" class="fi">
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Open Graph / Social Image</p>
                                    <div class="rounded-xl border border-dashed border-gray-300 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a10 p-4 flex flex-col items-center gap-3 text-center">
                                        <div class="w-full h-36 rounded-lg bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden">
                                            <img id="og_image_preview" src="{{ $setting->og_image ? Storage::url($setting->og_image) : asset('images/default-og-image.png') }}" class="h-full w-full object-cover" alt="OG">
                                        </div>
                                        <input type="file" name="og_image" id="og_image" class="hidden" accept="image/*" onchange="previewImage(this,'og_image_preview')">
                                        <label for="og_image" class="text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 cursor-pointer transition">Upload Share Image</label>
                                        <p class="text-[10px] text-gray-400">Recommended 1200×630px · PNG, JPG</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ SHIPPING ══════════════ --}}
                    <div class="tab-panel" id="panel-shipping">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Shipping & Delivery</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Configure shipping methods and rates.</p>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a10/30">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Enable Global Shipping</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Toggle shipping features across the entire store.</p>
                                    </div>
                                    <label class="tgl">
                                        <input type="checkbox" name="enable_shipping" value="1" {{ $setting->enable_shipping ? 'checked' : '' }}>
                                        <div class="tgl-track"></div>
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="default_shipping_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Default Shipping Method</label>
                                        <input type="text" name="default_shipping_method" id="default_shipping_method" value="{{ old('default_shipping_method', $setting->default_shipping_method) }}" placeholder="Standard Delivery" class="fi">
                                    </div>
                                    <div>
                                        <label for="estimated_delivery_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Est. Delivery Time</label>
                                        <input type="text" name="estimated_delivery_days" id="estimated_delivery_days" value="{{ old('estimated_delivery_days', $setting->estimated_delivery_days) }}" placeholder="3-5 Business Days" class="fi">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <div>
                                        <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Free Shipping Over</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3.5 flex items-center text-xs font-semibold text-gray-500 dark:text-gray-400 pointer-events-none">{{ $setting->currency_symbol }}</span>
                                            <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" value="{{ old('free_shipping_threshold', $setting->free_shipping_threshold) }}" step="0.01" class="fi pl-7">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="shipping_cost_per_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Rate Per Order</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3.5 flex items-center text-xs font-semibold text-gray-500 dark:text-gray-400 pointer-events-none">{{ $setting->currency_symbol }}</span>
                                            <input type="number" name="shipping_cost_per_order" id="shipping_cost_per_order" value="{{ old('shipping_cost_per_order', $setting->shipping_cost_per_order) }}" step="0.01" class="fi pl-7">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="shipping_cost_per_item" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Rate Per Item</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3.5 flex items-center text-xs font-semibold text-gray-500 dark:text-gray-400 pointer-events-none">{{ $setting->currency_symbol }}</span>
                                            <input type="number" name="shipping_cost_per_item" id="shipping_cost_per_item" value="{{ old('shipping_cost_per_item', $setting->shipping_cost_per_item) }}" step="0.01" class="fi pl-7">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ TAX ══════════════ --}}
                    <div class="tab-panel" id="panel-tax">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Tax Configuration</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">VAT, GST and tax display rules.</p>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a10/30">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Enable Tax Calculations</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Enable or disable VAT/GST across the store.</p>
                                    </div>
                                    <label class="tgl">
                                        <input type="checkbox" name="tax_enabled" value="1" {{ $setting->tax_enabled ? 'checked' : '' }}>
                                        <div class="tgl-track"></div>
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="default_tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Default Tax Rate (%)</label>
                                        <input type="number" name="default_tax_rate" id="default_tax_rate" value="{{ old('default_tax_rate', $setting->default_tax_rate) }}" step="0.01" placeholder="15.00" class="fi">
                                        <p class="text-xs text-gray-400 mt-1">Percentage applied at checkout.</p>
                                    </div>
                                    <div>
                                        <label for="tax_inclusive" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Price Inclusion</label>
                                        <select name="tax_inclusive" id="tax_inclusive" class="fi">
                                            <option value="1" @selected($setting->tax_inclusive)>Prices include tax</option>
                                            <option value="0" @selected(!$setting->tax_inclusive)>Tax added at checkout</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ PAYMENTS ══════════════ --}}
                    <div class="tab-panel" id="panel-payments">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payments & Checkout</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Payment methods and checkout preferences.</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Manual Payment Methods</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label class="ck-card">
                                            <input type="checkbox" name="cash_on_delivery_enabled" value="1" class="w-4 h-4 rounded border-gray-300 text-gray-900 shrink-0 mt-0.5 cursor-pointer" {{ $setting->cash_on_delivery_enabled ? 'checked' : '' }}>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Cash on Delivery</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Enable COD for local orders</p>
                                            </div>
                                        </label>
                                        <label class="ck-card">
                                            <input type="checkbox" name="bank_transfer_enabled" value="1" class="w-4 h-4 rounded border-gray-300 text-gray-900 shrink-0 mt-0.5 cursor-pointer" {{ $setting->bank_transfer_enabled ? 'checked' : '' }}>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Bank Transfer</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Enable direct bank deposit</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Checkout Preferences</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label class="ck-card">
                                            <input type="checkbox" name="guest_checkout_enabled" value="1" class="w-4 h-4 rounded border-gray-300 text-gray-900 shrink-0 mt-0.5 cursor-pointer" {{ $setting->guest_checkout_enabled ? 'checked' : '' }}>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Guest Checkout</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Allow orders without accounts</p>
                                            </div>
                                        </label>
                                        <label class="ck-card">
                                            <input type="checkbox" name="require_account_for_checkout" value="1" class="w-4 h-4 rounded border-gray-300 text-gray-900 shrink-0 mt-0.5 cursor-pointer" {{ $setting->require_account_for_checkout ? 'checked' : '' }}>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Require Account</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mandatory registration at checkout</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ INVENTORY ══════════════ --}}
                    <div class="tab-panel" id="panel-inventory">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Inventory & Order Management</h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Low Stock Threshold</label>
                                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $setting->low_stock_threshold) }}" class="fi" placeholder="5">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Order Prefix</label>
                                        <input type="text" name="order_prefix" value="{{ old('order_prefix', $setting->order_prefix) }}" class="fi" placeholder="ORD-">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="ck-card">
                                        <input type="checkbox" name="allow_backorders" value="1" class="w-4 h-4" {{ $setting->allow_backorders ? 'checked' : '' }}>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Allow Backorders</p>
                                            <p class="text-xs text-gray-500 mt-1">Enable customers to purchase out-of-stock items</p>
                                        </div>
                                    </label>
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Out of Stock Behavior</label>
                                        <select name="out_of_stock_behavior" class="fi">
                                            <option value="hide" @selected($setting->out_of_stock_behavior == 'hide')>Hide from store</option>
                                            <option value="show" @selected($setting->out_of_stock_behavior == 'show')>Show with message</option>
                                            <option value="allow_backorder" @selected($setting->out_of_stock_behavior == 'allow_backorder')>Allow Backorder</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ CUSTOMERS ══════════════ --}}
                    <div class="tab-panel" id="panel-customers">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Customer Preferences</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="ck-card">
                                        <input type="checkbox" name="newsletter_enabled" value="1" class="w-4 h-4" {{ $setting->newsletter_enabled ? 'checked' : '' }}>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Enable Newsletters</p>
                                            <p class="text-xs text-gray-500 mt-1">Show newsletter signup fields</p>
                                        </div>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="wishlist_enabled" value="1" class="w-4 h-4" {{ $setting->wishlist_enabled ? 'checked' : '' }}>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Enable Wishlist</p>
                                            <p class="text-xs text-gray-500 mt-1">Allow customers to save favorite items</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ FEATURES ══════════════ --}}
                    <div class="tab-panel" id="panel-features">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Fashion & Store Features</h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="ck-card">
                                        <input type="checkbox" name="size_chart_enabled" value="1" class="w-4 h-4" {{ $setting->size_chart_enabled ? 'checked' : '' }}>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Size Chart</p>
                                            <p class="text-xs text-gray-500 mt-1">Global size charts for products</p>
                                        </div>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="color_swatches_enabled" value="1" class="w-4 h-4" {{ $setting->color_swatches_enabled ? 'checked' : '' }}>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Visual Color Swatches</p>
                                            <p class="text-xs text-gray-500 mt-1">Show colors as swatches instead of text</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 border-t border-gray-50 dark:border-surface-tonal-a30 pt-6">
                                    <label class="ck-card">
                                        <input type="checkbox" name="enable_product_quick_view" value="1" class="w-4 h-4" {{ $setting->enable_product_quick_view ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Quick View</p>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="enable_size_filter" value="1" class="w-4 h-4" {{ $setting->enable_size_filter ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Size Filter</p>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="enable_color_filter" value="1" class="w-4 h-4" {{ $setting->enable_color_filter ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Color Filter</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ MARKETING ══════════════ --}}
                    <div class="tab-panel" id="panel-marketing">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Marketing & Performance</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <label class="ck-card">
                                        <input type="checkbox" name="enable_discounts" value="1" class="w-4 h-4" {{ $setting->enable_discounts ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Discounts</p>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="enable_coupons" value="1" class="w-4 h-4" {{ $setting->enable_coupons ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Coupons</p>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="enable_flash_sales" value="1" class="w-4 h-4" {{ $setting->enable_flash_sales ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Flash Sales</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ SOCIAL ══════════════ --}}
                    <div class="tab-panel" id="panel-social">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Social Connectivity</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Instagram URL</label>
                                        <input type="text" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}" class="fi font-mono" placeholder="https://instagram.com/store">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Facebook URL</label>
                                        <input type="text" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}" class="fi font-mono" placeholder="https://facebook.com/store">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ ANALYTICS ══════════════ --}}
                    <div class="tab-panel" id="panel-analytics">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Tracking & Intelligence</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Google Analytics ID</label>
                                        <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $setting->google_analytics_id) }}" class="fi" placeholder="G-XXXXXXXXXX">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Facebook Pixel ID</label>
                                        <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $setting->facebook_pixel_id) }}" class="fi" placeholder="ID123456789">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ EMAIL ══════════════ --}}
                    <div class="tab-panel" id="panel-email">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Email Configuration</h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">From Name</label>
                                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $setting->mail_from_name) }}" class="fi">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">From Address</label>
                                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $setting->mail_from_address) }}" class="fi">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="ck-card">
                                        <input type="checkbox" name="order_confirmation_email_enabled" value="1" class="w-4 h-4" {{ $setting->order_confirmation_email_enabled ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Order Confirmations</p>
                                    </label>
                                    <label class="ck-card">
                                        <input type="checkbox" name="shipping_notification_enabled" value="1" class="w-4 h-4" {{ $setting->shipping_notification_enabled ? 'checked' : '' }}>
                                        <p class="text-xs font-semibold">Shipping Alerts</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════ MAINTENANCE ══════════════ --}}
                    <div class="tab-panel" id="panel-maintenance">
                        <div class="bg-white dark:bg-surface-tonal-a20 rounded-2xl border border-gray-200 dark:border-surface-tonal-a30 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">System Governance</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a10/30">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Maintenance Mode</p>
                                        <p class="text-xs text-gray-500 mt-1">Restrict public access to the storefront</p>
                                    </div>
                                    <label class="tgl">
                                        <input type="checkbox" name="site_maintenance_mode" value="1" {{ $setting->site_maintenance_mode ? 'checked' : '' }}>
                                        <div class="tgl-track"></div>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Maintenance Message</label>
                                    <textarea name="maintenance_message" rows="3" class="fi resize-none">{{ old('maintenance_message', $setting->maintenance_message) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById(previewId).src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

        function switchTab(key) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('is-active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('is-active'));
            document.querySelectorAll('.sidebar-link').forEach(b => b.classList.remove('is-active'));

            const panel = document.getElementById('panel-' + key);
            if (panel) panel.classList.add('is-active');

            const btn = document.getElementById('tab-' + key);
            if (btn) {
                btn.classList.add('is-active');
                btn.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
            }

            const side = document.getElementById('sidebar-' + key);
            if (side) side.classList.add('is-active');

            localStorage.setItem('settingTab', key);
        }

        // Init
        switchTab(localStorage.getItem('settingTab') || 'general');
    </script>
@endpush