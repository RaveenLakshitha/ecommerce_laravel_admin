@extends('layouts.app')

@section('title', 'Storefront Customization')

@section('content')
    <div class="px-4 sm:px-6 lg:px-4 pb-4 sm:py-12 pt-20">
        <div class=" mb-8">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-primary-a0">Storefront Customization</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage the content displayed on your customer site.</p>
        </div>

        <style>
            .toggle-switch {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 24px;
            }

            .toggle-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .toggle-slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #e5e7eb;
                transition: .4s;
                border-radius: 24px;
            }

            .toggle-slider:before {
                position: absolute;
                content: "";
                height: 18px;
                width: 18px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            input:checked+.toggle-slider {
                background-color: #111827;
            }

            input:checked+.toggle-slider:before {
                transform: translateX(26px);
            }

            .dark .toggle-slider {
                background-color: #374151;
            }

            .dark input:checked+.toggle-slider {
                background-color: #f3f4f6;
            }

            .dark input:checked+.toggle-slider:before {
                background-color: #111827;
            }
        </style>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.storefront.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-12">
            @csrf
            @method('PUT')

            <div
                class="bg-white dark:bg-transparent rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                <div class="p-6 space-y-12">

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Site Branding (Header Logo)</h2>
                            <div class="flex items-center gap-3" id="logo-type-toggle">
                                <span class="text-xs font-medium {{ !($setting->storefront_use_logo_text ?? false) ? 'text-gray-900 dark:text-primary-a0' : 'text-gray-500 dark:text-gray-400' }}">Use General Logo</span>
                                <label class="toggle-switch">
                                    <input type="hidden" name="storefront_use_logo_text" value="0">
                                    <input type="checkbox" name="storefront_use_logo_text" value="1" {{ ($setting->storefront_use_logo_text ?? false) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="text-xs font-medium {{ ($setting->storefront_use_logo_text ?? false) ? 'text-gray-900 dark:text-primary-a0' : 'text-gray-500 dark:text-gray-400' }}">Use Logo Text</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Main
                                    Text <span class="text-xs text-gray-400 font-normal ml-2"></span></label>
                                <input type="text" name="storefront_logo_text"
                                    value="{{ old('storefront_logo_text', $setting->storefront_logo_text ?? '') }}"
                                    maxlength="50" placeholder="e.g., KARBNZOL"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Subtext
                                    <span class="text-xs text-gray-400 font-normal ml-2">(Displayed below
                                        logo)</span></label>
                                <input type="text" name="storefront_logo_subtext"
                                    value="{{ old('storefront_logo_subtext', $setting->storefront_logo_subtext ?? '') }}"
                                    maxlength="100" placeholder="e.g., Premium Menswear"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Support Phone
                                    Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" maxlength="20"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Support
                                    Email</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email) }}" maxlength="255"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Announcement Bar (Global
                            Top Bar)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Announcement
                                    Text <span class="text-xs text-gray-400 font-normal ml-2">(Repeats in
                                        marquee)</span></label>
                                <input type="text" name="storefront_offer_text"
                                    value="{{ old('storefront_offer_text', $setting->storefront_offer_text ?? '') }}"
                                    maxlength="50"
                                    placeholder="e.g., FREE SHIPPING ON ORDERS OVER {{ $currency_symbol }} {{ number_format($free_shipping_threshold) }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Announcement
                                    Link (Optional)</label>
                                <input type="text" name="storefront_offer_link"
                                    value="{{ old('storefront_offer_link', $setting->storefront_offer_link ?? '') }}"
                                    maxlength="255" placeholder="e.g., /collections/sale"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Home Page Marquee Bar</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Marquee Text
                                    <span class="text-xs text-gray-400 font-normal ml-2">(Use '|' to separate multiple
                                        messages)</span></label>
                                <input type="text" name="storefront_marquee_text"
                                    value="{{ old('storefront_marquee_text', $setting->storefront_marquee_text ?? '') }}"
                                    maxlength="255" placeholder="e.g., Free Delivery | New Arrivals | MintPay Available"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Marquee Link
                                    (Optional)</label>
                                <input type="text" name="storefront_marquee_link"
                                    value="{{ old('storefront_marquee_link', $setting->storefront_marquee_link ?? '') }}"
                                    maxlength="255" placeholder="e.g., /collections/new-arrivals"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">About Us Section
                            (Footer/General)</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Short About
                                    Us Text <span class="text-xs text-gray-400 font-normal ml-2">(Displayed in
                                        footer)</span></label>
                                <textarea name="storefront_about_us" rows="2"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">{{ old('storefront_about_us', $setting->storefront_about_us ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">About Us Page
                                    Content <span class="text-xs text-gray-400 font-normal ml-2">(Optional fallback for
                                        About page)</span></label>
                                <textarea name="storefront_about_us_content" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">{{ old('storefront_about_us_content', $setting->storefront_about_us_content ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Our Story (About Page)</h2>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Hidden</span>
                                <label class="toggle-switch">
                                    <input type="hidden" name="storefront_our_story_show" value="0">
                                    <input type="checkbox" name="storefront_our_story_show" value="1" {{ ($setting->storefront_our_story_show ?? true) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="text-xs font-medium text-gray-900 dark:text-primary-a0">Visible</span>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Our Story
                                        Title</label>
                                    <input type="text" name="storefront_our_story_title"
                                        value="{{ old('storefront_our_story_title', $setting->storefront_our_story_title ?? '') }}"
                                        maxlength="100" placeholder="e.g., Our Story"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Story
                                        Image</label>
                                    @if($setting->storefront_our_story_image)
                                        <div class="mb-2 flex items-center gap-2">
                                            <img src="{{ asset('storage/' . $setting->storefront_our_story_image) }}"
                                                class="h-12 w-auto object-cover rounded">
                                            <label class="text-xs text-red-500 flex items-center gap-1 cursor-pointer">
                                                <input type="checkbox" name="remove_our_story_image" value="1"> Remove
                                            </label>
                                        </div>
                                    @endif
                                    <input type="file" name="storefront_our_story_image" accept="image/*"
                                        class="w-full text-sm text-gray-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Our Story
                                    Content</label>
                                <textarea name="storefront_our_story_content" rows="6"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">{{ old('storefront_our_story_content', $setting->storefront_our_story_content ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Performance Stats (Home
                                    Page)</h2>
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_stats_show" value="0">
                                        <input type="checkbox" name="storefront_stats_show" value="1" {{ ($setting->storefront_stats_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span
                                        class="text-[10px] uppercase font-bold {{ ($setting->storefront_stats_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ ($setting->storefront_stats_show ?? true) ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div id="stats-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @php
                                $stats = is_array($setting->storefront_stats) ? $setting->storefront_stats : [
                                    ['number' => '12K+', 'label' => 'Orders Shipped'],
                                    ['number' => '500+', 'label' => 'Styles in Stock'],
                                    ['number' => '98%', 'label' => '5-Star Reviews'],
                                    ['number' => '48H', 'label' => 'Island Delivery']
                                ];
                            @endphp
                            @foreach($stats as $index => $stat)
                                <div
                                    class="stat-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs">×</button>
                                    <div class="space-y-3">
                                        <input type="text" name="storefront_stats[{{$index}}][number]"
                                            value="{{ $stat['number'] ?? '' }}" placeholder="Value (e.g. 12K+)"
                                            class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        <input type="text" name="storefront_stats[{{$index}}][label]"
                                            value="{{ $stat['label'] ?? '' }}" placeholder="Label"
                                            class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Trust Signals (Footer
                                    Bar)</h2>
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_trust_show" value="0">
                                        <input type="checkbox" name="storefront_trust_show" value="1" {{ ($setting->storefront_trust_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span
                                        class="text-[10px] uppercase font-bold {{ ($setting->storefront_trust_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ ($setting->storefront_trust_show ?? true) ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div id="trust-container" class="space-y-4">
                            @php
                                $trusts = is_array($setting->storefront_trust_items) ? $setting->storefront_trust_items : [
                                    ['title' => 'Free Delivery', 'subtitle' => 'On orders over ' . $currency_symbol . ' ' . number_format($free_shipping_threshold), 'svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'],
                                    ['title' => 'Secure Payments', 'subtitle' => 'MintPay & Stripe Integration', 'svg' => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>'],
                                    ['title' => 'Easy Returns', 'subtitle' => '14-day exchange policy', 'svg' => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>'],
                                    ['title' => 'Premium Quality', 'subtitle' => 'Hand-picked fabrics only', 'svg' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>']
                                ];
                            @endphp
                            @foreach($trusts as $index => $trust)
                                <div
                                    class="trust-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs">×</button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-span-1">
                                            <label
                                                class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Title</label>
                                            <input type="text" name="storefront_trust_items[{{$index}}][title]"
                                                value="{{ $trust['title'] ?? '' }}" placeholder="Title"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                            <label
                                                class="block text-[10px] uppercase font-bold text-gray-400 mt-2 mb-1">Subtitle</label>
                                            <input type="text" name="storefront_trust_items[{{$index}}][subtitle]"
                                                value="{{ $trust['subtitle'] ?? '' }}" placeholder="Subtitle"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-2">Select
                                                Icon</label>
                                            <input type="hidden" name="storefront_trust_items[{{$index}}][svg]"
                                                value="{{ $trust['svg'] ?? '' }}">
                                            <div class="flex flex-wrap gap-2 icon-grid"
                                                data-selected="{{ $trust['svg'] ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Delivery & Returns (Product Page Accordion)</h2>
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_delivery_show" value="0">
                                        <input type="checkbox" name="storefront_delivery_show" value="1" {{ ($setting->storefront_delivery_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span
                                        class="text-[10px] uppercase font-bold {{ ($setting->storefront_delivery_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ ($setting->storefront_delivery_show ?? true) ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" id="add-delivery-btn"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-200 dark:bg-transparent dark:hover:bg-gray-700 transition">
                                + Add Delivery Detail
                            </button>
                        </div>
                        <div id="delivery-container" class="space-y-4">
                            @php
                                $deliveries = is_array($setting->storefront_delivery_items) ? $setting->storefront_delivery_items : [
                                    ['title' => 'Standard Delivery', 'subtitle' => ($currency_symbol ?? '$') . number_format($shipping_cost_per_order ?? 0) . ' · 3–5 business days | Free on orders over ' . ($currency_symbol ?? '$') . number_format($free_shipping_threshold ?? 5000), 'svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'],
                                    ['title' => 'Express Delivery', 'subtitle' => ($currency_symbol ?? '$') . '650.00 · Next business day | Order before 1 PM', 'svg' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
                                    ['title' => 'Free Returns', 'subtitle' => '30 days · Unworn & with tags | Initiate via My Orders', 'svg' => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>'],
                                    ['title' => 'In-Store Pickup', 'subtitle' => 'Colombo & Kandy · Ready in 2–3 hours', 'svg' => '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>']
                                ];
                            @endphp
                            @foreach($deliveries as $index => $delivery)
                                <div
                                    class="delivery-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs">×</button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-span-1">
                                            <label
                                                class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Title</label>
                                            <input type="text" name="storefront_delivery_items[{{$index}}][title]"
                                                value="{{ $delivery['title'] ?? '' }}" placeholder="Title"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                            <label
                                                class="block text-[10px] uppercase font-bold text-gray-400 mt-2 mb-1">Subtitle</label>
                                            <input type="text" name="storefront_delivery_items[{{$index}}][subtitle]"
                                                value="{{ $delivery['subtitle'] ?? '' }}" placeholder="Subtitle"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-2">Select
                                                Icon</label>
                                            <input type="hidden" name="storefront_delivery_items[{{$index}}][svg]"
                                                value="{{ $delivery['svg'] ?? '' }}">
                                            <div class="flex flex-wrap gap-2 icon-grid"
                                                data-selected="{{ $delivery['svg'] ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Measurements (Product Page Accordion)</h2>
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_measure_show" value="0">
                                        <input type="checkbox" name="storefront_measure_show" value="1" {{ ($setting->storefront_measure_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span
                                        class="text-[10px] uppercase font-bold {{ ($setting->storefront_measure_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ ($setting->storefront_measure_show ?? true) ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" id="add-measure-btn"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-200 dark:bg-transparent dark:hover:bg-gray-700 transition">
                                + Add Measurement Row
                            </button>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Measurement Note</label>
                            <input type="text" name="storefront_measure_note"
                                value="{{ old('storefront_measure_note', $setting->storefront_measure_note ?? 'Measurements taken on size S. Add 5cm per size.') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                        </div>
                        <div id="measure-container" class="space-y-4">
                            @php
                                $measures = is_array($setting->storefront_measure_items) ? $setting->storefront_measure_items : [
                                    ['label' => 'Total length', 'cm' => '118', 'inches' => '46.5"'],
                                    ['label' => 'Bust', 'cm' => '92', 'inches' => '36.2"'],
                                    ['label' => 'Waist', 'cm' => '76', 'inches' => '29.9"'],
                                    ['label' => 'Hem', 'cm' => '152', 'inches' => '59.8"'],
                                    ['label' => 'Sleeve length', 'cm' => '62', 'inches' => '24.4"']
                                ];
                            @endphp
                            @foreach($measures as $index => $measure)
                                <div
                                    class="measure-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs">×</button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Measurement Label</label>
                                            <input type="text" name="storefront_measure_items[{{$index}}][label]"
                                                value="{{ $measure['label'] ?? '' }}" placeholder="e.g., Total length"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Value (cm)</label>
                                            <input type="text" name="storefront_measure_items[{{$index}}][cm]"
                                                value="{{ $measure['cm'] ?? '' }}" placeholder="e.g., 118"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Value (inches)</label>
                                            <input type="text" name="storefront_measure_items[{{$index}}][inches]"
                                                value="{{ $measure['inches'] ?? '' }}" placeholder="e.g., 46.5\""
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Size Guide (Product Detail Modal)</h2>
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_size_guide_show" value="0">
                                        <input type="checkbox" name="storefront_size_guide_show" value="1" {{ ($setting->storefront_size_guide_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span
                                        class="text-[10px] uppercase font-bold {{ ($setting->storefront_size_guide_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ ($setting->storefront_size_guide_show ?? true) ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Size Guide Modal Title</label>
                                <input type="text" name="storefront_size_guide_title"
                                    value="{{ old('storefront_size_guide_title', $setting->storefront_size_guide_title ?? 'Size Guide') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Footnote / Note</label>
                                <input type="text" name="storefront_size_guide_note"
                                    value="{{ old('storefront_size_guide_note', $setting->storefront_size_guide_note ?? 'All measurements in centimetres. If between sizes, size up for a relaxed fit.') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>

                        @php
                            $headers = is_array($setting->storefront_size_guide_headers) ? $setting->storefront_size_guide_headers : ['SIZE', 'BUST (CM)', 'WAIST (CM)', 'HIP (CM)', 'UK/EU'];
                            $rows = is_array($setting->storefront_size_guide_rows) ? $setting->storefront_size_guide_rows : [
                                ['XS', '80–84', '62–66', '88–92', '6 / 34'],
                                ['S', '84–88', '66–70', '92–96', '8 / 36'],
                                ['M', '88–92', '70–74', '96–100', '10 / 38'],
                                ['L', '92–98', '74–80', '100–106', '12 / 40'],
                                ['XL', '98–104', '80–86', '106–112', '14 / 42'],
                                ['XXL', '104–112', '86–94', '112–120', '16 / 44']
                            ];
                        @endphp

                        
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Table Column Headers (5 Columns)</h3>
                            <div class="grid grid-cols-5 gap-2">
                                <div>
                                    <label class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Col 1</label>
                                    <input type="text" name="storefront_size_guide_headers[]" value="{{ $headers[0] ?? 'SIZE' }}" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Col 2</label>
                                    <input type="text" name="storefront_size_guide_headers[]" value="{{ $headers[1] ?? 'BUST (CM)' }}" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Col 3</label>
                                    <input type="text" name="storefront_size_guide_headers[]" value="{{ $headers[2] ?? 'WAIST (CM)' }}" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Col 4</label>
                                    <input type="text" name="storefront_size_guide_headers[]" value="{{ $headers[3] ?? 'HIP (CM)' }}" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Col 5</label>
                                    <input type="text" name="storefront_size_guide_headers[]" value="{{ $headers[4] ?? 'UK/EU' }}" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                </div>
                            </div>
                        </div>

                        
                        <div class="mb-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Size Chart Rows</h3>
                                <button type="button" id="add-size-row-btn" class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-100 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg hover:bg-gray-200 dark:bg-transparent dark:hover:bg-gray-700 transition">
                                    + Add Size Row
                                </button>
                            </div>
                            <div id="size-rows-container" class="space-y-2">
                                @foreach($rows as $rIdx => $row)
                                    <div class="size-row-item p-3 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10 flex items-center gap-2 flex-wrap sm:flex-nowrap">
                                        <input type="text" name="storefront_size_guide_rows[{{$rIdx}}][]" value="{{ $row[0] ?? '' }}" placeholder="Col 1" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
                                        <input type="text" name="storefront_size_guide_rows[{{$rIdx}}][]" value="{{ $row[1] ?? '' }}" placeholder="Col 2" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
                                        <input type="text" name="storefront_size_guide_rows[{{$rIdx}}][]" value="{{ $row[2] ?? '' }}" placeholder="Col 3" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
                                        <input type="text" name="storefront_size_guide_rows[{{$rIdx}}][]" value="{{ $row[3] ?? '' }}" placeholder="Col 4" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
                                        <input type="text" name="storefront_size_guide_rows[{{$rIdx}}][]" value="{{ $row[4] ?? '' }}" placeholder="Col 5" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
                                        <button type="button" class="text-red-500 hover:text-red-700 font-bold px-2 remove-size-row-btn">×</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        
                        <div class="mb-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">How to Measure Descriptions</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Bust Description</label>
                                    <input type="text" name="storefront_size_guide_bust_desc"
                                        value="{{ old('storefront_size_guide_bust_desc', $setting->storefront_size_guide_bust_desc ?? 'measure around the fullest part of your chest, keeping the tape horizontal.') }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Waist Description</label>
                                    <input type="text" name="storefront_size_guide_waist_desc"
                                        value="{{ old('storefront_size_guide_waist_desc', $setting->storefront_size_guide_waist_desc ?? 'measure around the narrowest part of your natural waist.') }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Hip Description</label>
                                    <input type="text" name="storefront_size_guide_hip_desc"
                                        value="{{ old('storefront_size_guide_hip_desc', $setting->storefront_size_guide_hip_desc ?? 'measure around the fullest part of your hips, about 20cm below your waist.') }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                                </div>
                            </div>
                        </div>

                        
                        <div class="mb-2 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fit Note for this style</label>
                            <textarea name="storefront_size_guide_fit_note" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow"
                                placeholder="e.g. This piece is cut in a relaxed silhouette with a slightly dropped shoulder. Our model is 175cm and wears a size S.">{{ old('storefront_size_guide_fit_note', $setting->storefront_size_guide_fit_note ?? 'This piece is cut in a relaxed silhouette with a slightly dropped shoulder. Our model is 175cm and wears a size S.') }}</textarea>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Navbar Category Order</h2>
                        <div class="bg-gray-50 dark:bg-surface-tonal-a10 rounded-xl p-4 border border-gray-100 dark:border-surface-tonal-a20">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Set the display order for parent categories in the storefront navbar. Lower numbers appear first.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($categories as $category)
                                    <div class="flex items-center gap-3 p-3 bg-white dark:bg-surface-tonal-a20 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                                            <span class="text-xs font-bold text-gray-500">{{ $loop->iteration }}</span>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-primary-a0 truncate">{{ $category->name }}</p>
                                        </div>
                                        <div class="flex-shrink-0 w-16">
                                            <input type="number" name="category_order[{{ $category->id }}]" 
                                                value="{{ $category->order ?? 0 }}" 
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-gray-900 dark:focus:ring-gray-500 dark:bg-transparent dark:text-primary-a0"
                                                min="0">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Video Section
                                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">(Home Page — before Featured Collections)</span>
                                </h2>
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_video_show" value="0">
                                        <input type="checkbox" name="storefront_video_show" value="1" {{ ($setting->storefront_video_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span
                                        class="text-[10px] uppercase font-bold {{ ($setting->storefront_video_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ ($setting->storefront_video_show ?? true) ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 dark:bg-surface-tonal-a10 rounded-xl p-4 border border-blue-100 dark:border-surface-tonal-a20 mb-4">
                            <p class="text-xs text-blue-700 dark:text-gray-400">
                                <strong>Upload a video file</strong> (MP4, WebM or OGG — max 50 MB). The video plays
                                automatically, muted and looped in a full-width cinematic strip directly before the
                                Featured Collections grid. Uploading a file avoids browser download prompts.
                            </p>
                        </div>

                        
                        @if($setting->storefront_video_url)
                            <div class="mb-4 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600 bg-black" style="max-height:220px;">
                                <video src="{{ asset('storage/' . $setting->storefront_video_url) }}"
                                    class="w-full" style="max-height:220px;object-fit:cover;"
                                    muted playsinline controls>
                                </video>
                            </div>
                            <label class="inline-flex items-center gap-2 text-xs text-red-500 cursor-pointer mb-4">
                                <input type="checkbox" name="remove_storefront_video" value="1">
                                Remove current video
                            </label>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Upload Video File
                                    <span class="text-xs text-gray-400 font-normal ml-2">(MP4 / WebM / OGG — max 50 MB)</span>
                                </label>
                                <input type="file" name="storefront_video_file"
                                    accept="video/mp4,video/webm,video/ogg"
                                    class="w-full text-sm text-gray-500 dark:text-gray-400
                                           file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0
                                           file:text-xs file:font-semibold file:bg-gray-100 dark:file:bg-surface-tonal-a20
                                           file:text-gray-700 dark:file:text-gray-300
                                           hover:file:bg-gray-200 dark:hover:file:bg-surface-tonal-a30 cursor-pointer">
                                <p class="text-[11px] text-gray-400 mt-1">Recommended: compress your video to under 10 MB for faster page loads. Use tools like <em>Handbrake</em> or <em>ffmpeg</em>.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section
                                    Title
                                    <span class="text-xs text-gray-400 font-normal ml-2">(Optional overlay text)</span>
                                </label>
                                <input type="text" name="storefront_video_title"
                                    value="{{ old('storefront_video_title', $setting->storefront_video_title ?? '') }}"
                                    maxlength="100"
                                    placeholder="e.g., See Our Story"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section
                                    Subtitle
                                    <span class="text-xs text-gray-400 font-normal ml-2">(Optional)</span>
                                </label>
                                <input type="text" name="storefront_video_subtitle"
                                    value="{{ old('storefront_video_subtitle', $setting->storefront_video_subtitle ?? '') }}"
                                    maxlength="200"
                                    placeholder="e.g., Craftsmanship, quality, and style — in motion."
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Hero Banners / Sliders</h2>
                            <button type="button" id="add-banner-btn"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-200 dark:bg-transparent dark:hover:bg-gray-700 transition">
                                + Add Banner
                            </button>
                        </div>

                        <div id="banners-container" class="space-y-6">
                            @php
                                $banners = is_array($setting->storefront_banners) ? $setting->storefront_banners : [];
                            @endphp

                            @if(count($banners) === 0)
                                
                                <div
                                    class="banner-item border border-gray-200 dark:border-gray-600 p-4 rounded-lg relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button"
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-banner-btn">Remove</button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tag
                                                <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25
                                                    chars)</span></label>
                                            <input type="text" name="banners[0][tag]" placeholder="e.g. SS 2025 — New Drop"
                                                maxlength="25"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Title
                                                <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25
                                                    chars)</span></label>
                                            <input type="text" name="banners[0][title]" placeholder="Line 1" maxlength="25"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle
                                                <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 120
                                                    chars)</span></label>
                                            <input type="text" name="banners[0][subtitle]" placeholder="Description"
                                                maxlength="120"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Link
                                                URL</label>
                                            <input type="text" name="banners[0][link]" placeholder="/products" maxlength="255"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Banner
                                                Image</label>
                                            <input type="file" name="banners[0][image]" accept="image/*"
                                                class="w-full text-sm text-gray-500">
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach($banners as $index => $banner)
                                    <div
                                        class="banner-item border border-gray-200 dark:border-gray-600 p-4 rounded-lg relative bg-gray-50 dark:bg-surface-tonal-a10">
                                        <button type="button"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-banner-btn">Remove</button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tag
                                                    <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25
                                                        chars)</span></label>
                                                <input type="text" name="banners[{{$index}}][tag]"
                                                    value="{{ $banner['tag'] ?? '' }}" maxlength="25"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Title
                                                    <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25
                                                        chars)</span></label>
                                                <input type="text" name="banners[{{$index}}][title]"
                                                    value="{{ $banner['title'] ?? '' }}" maxlength="25"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle
                                                    <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 120
                                                        chars)</span></label>
                                                <input type="text" name="banners[{{$index}}][subtitle]"
                                                    value="{{ $banner['subtitle'] ?? '' }}" maxlength="120"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Link
                                                    URL</label>
                                                <input type="text" name="banners[{{$index}}][link]"
                                                    value="{{ $banner['link'] ?? '' }}" maxlength="255"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Banner
                                                    Image</label>
                                                @if(!empty($banner['image']))
                                                    <div class="mb-2 flex items-center gap-2">
                                                        <img src="{{ asset('storage/' . $banner['image']) }}"
                                                            class="h-10 w-auto object-cover rounded">
                                                        <label class="text-xs text-red-500 flex items-center gap-1 cursor-pointer">
                                                            <input type="checkbox" name="banners[{{$index}}][remove_image]" value="1">
                                                            Remove
                                                        </label>
                                                    </div>
                                                @endif
                                                <input type="file" name="banners[{{$index}}][image]" accept="image/*"
                                                    class="w-full text-sm text-gray-500">
                                                <input type="hidden" name="banners[{{$index}}][existing_image]"
                                                    value="{{ $banner['image'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-white dark:text-gray-500 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    
    <template id="banner-template">
        <div
            class="banner-item border border-gray-200 dark:border-gray-600 p-4 rounded-lg relative bg-gray-50 dark:bg-surface-tonal-a10">
            <button type="button"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-banner-btn">Remove</button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tag <span
                            class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                    <input type="text" name="banners[__INDEX__][tag]" placeholder="e.g. SS 2025 — New Drop" maxlength="25"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span
                            class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                    <input type="text" name="banners[__INDEX__][title]" placeholder="Line 1" maxlength="25"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle <span
                            class="text-[10px] text-gray-400 font-normal ml-1">(Max 120 chars)</span></label>
                    <input type="text" name="banners[__INDEX__][subtitle]" placeholder="Description" maxlength="120"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Link URL</label>
                    <input type="text" name="banners[__INDEX__][link]" placeholder="/products" maxlength="255"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Banner Image</label>
                    <input type="file" name="banners[__INDEX__][image]" accept="image/*"
                        class="w-full text-sm text-gray-500 p-1">
                </div>
            </div>
        </div>
    </template>

    
    <template id="delivery-template">
        <div
            class="delivery-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
            <button type="button" class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs remove-delivery-btn">×</button>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1">
                    <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Title</label>
                    <input type="text" name="storefront_delivery_items[__INDEX__][title]" placeholder="Title"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                    <label class="block text-[10px] uppercase font-bold text-gray-400 mt-2 mb-1">Subtitle</label>
                    <input type="text" name="storefront_delivery_items[__INDEX__][subtitle]" placeholder="Subtitle"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] uppercase font-bold text-gray-400 mb-2">Select Icon</label>
                    <input type="hidden" name="storefront_delivery_items[__INDEX__][svg]" value="">
                    <div class="flex flex-wrap gap-2 icon-grid" data-selected=""></div>
                </div>
            </div>
        </div>
    </template>

    
    <template id="measure-template">
        <div
            class="measure-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
            <button type="button" class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs remove-measure-btn">×</button>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Measurement Label</label>
                    <input type="text" name="storefront_measure_items[__INDEX__][label]" placeholder="e.g., Total length"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Value (cm)</label>
                    <input type="text" name="storefront_measure_items[__INDEX__][cm]" placeholder="e.g., 118"
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Value (inches)</label>
                    <input type="text" name="storefront_measure_items[__INDEX__][inches]" placeholder="e.g., 46.5\""
                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
            </div>
        </div>
    </template>

    
    <template id="size-row-template">
        <div class="size-row-item p-3 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10 flex items-center gap-2 flex-wrap sm:flex-nowrap">
            <input type="text" name="storefront_size_guide_rows[__INDEX__][]" placeholder="Col 1" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
            <input type="text" name="storefront_size_guide_rows[__INDEX__][]" placeholder="Col 2" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
            <input type="text" name="storefront_size_guide_rows[__INDEX__][]" placeholder="Col 3" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
            <input type="text" name="storefront_size_guide_rows[__INDEX__][]" placeholder="Col 4" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
            <input type="text" name="storefront_size_guide_rows[__INDEX__][]" placeholder="Col 5" class="w-full sm:w-1/5 px-2 py-1 text-sm border rounded dark:bg-transparent dark:text-primary-a0">
            <button type="button" class="text-red-500 hover:text-red-700 font-bold px-2 remove-size-row-btn">×</button>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Banners Handlers
            const bannerContainer = document.getElementById('banners-container');
            const addBannerBtn = document.getElementById('add-banner-btn');
            const bannerTemplate = document.getElementById('banner-template');

            if (addBannerBtn && bannerContainer && bannerTemplate) {
                let bannerIndex = document.querySelectorAll('.banner-item').length;

                addBannerBtn.addEventListener('click', function () {
                    const clone = bannerTemplate.content.cloneNode(true);
                    const html = clone.querySelector('.banner-item').outerHTML.replace(/__INDEX__/g, bannerIndex);
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newElem = temp.firstElementChild;

                    bannerContainer.appendChild(newElem);
                    bannerIndex++;
                    attachRemoveEvent(newElem.querySelector('.remove-banner-btn'));
                });
            }

            function attachRemoveEvent(btn) {
                if (!btn) return;
                btn.addEventListener('click', function () {
                    const item = this.closest('.banner-item');
                    if (item) item.remove();
                });
            }

            document.querySelectorAll('.remove-banner-btn').forEach(btn => {
                attachRemoveEvent(btn);
            });

            // Delivery Details Handlers
            const deliveryContainer = document.getElementById('delivery-container');
            const addDeliveryBtn = document.getElementById('add-delivery-btn');
            const deliveryTemplate = document.getElementById('delivery-template');

            if (addDeliveryBtn && deliveryContainer && deliveryTemplate) {
                let deliveryIndex = document.querySelectorAll('.delivery-item').length;

                addDeliveryBtn.addEventListener('click', function () {
                    const clone = deliveryTemplate.content.cloneNode(true);
                    const html = clone.querySelector('.delivery-item').outerHTML.replace(/__INDEX__/g, deliveryIndex);
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newElem = temp.firstElementChild;

                    deliveryContainer.appendChild(newElem);
                    
                    // Initialize the icon grid inside this new delivery element
                    initIconGridForContainer(newElem.querySelector('.icon-grid'));

                    deliveryIndex++;
                    attachRemoveEventDelivery(newElem.querySelector('.remove-delivery-btn'));
                });
            }

            function attachRemoveEventDelivery(btn) {
                if (!btn) return;
                btn.addEventListener('click', function () {
                    const item = this.closest('.delivery-item');
                    if (item) item.remove();
                });
            }

            document.querySelectorAll('.remove-delivery-btn').forEach(btn => {
                attachRemoveEventDelivery(btn);
            });

            // Measurements Handlers
            const measureContainer = document.getElementById('measure-container');
            const addMeasureBtn = document.getElementById('add-measure-btn');
            const measureTemplate = document.getElementById('measure-template');

            if (addMeasureBtn && measureContainer && measureTemplate) {
                let measureIndex = document.querySelectorAll('.measure-item').length;

                addMeasureBtn.addEventListener('click', function () {
                    const clone = measureTemplate.content.cloneNode(true);
                    const html = clone.querySelector('.measure-item').outerHTML.replace(/__INDEX__/g, measureIndex);
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newElem = temp.firstElementChild;

                    measureContainer.appendChild(newElem);
                    measureIndex++;
                    attachRemoveEventMeasure(newElem.querySelector('.remove-measure-btn'));
                });
            }

            function attachRemoveEventMeasure(btn) {
                if (!btn) return;
                btn.addEventListener('click', function () {
                    const item = this.closest('.measure-item');
                    if (item) item.remove();
                });
            }

            document.querySelectorAll('.remove-measure-btn').forEach(btn => {
                attachRemoveEventMeasure(btn);
            });

            // Size Rows Handlers
            const sizeRowsContainer = document.getElementById('size-rows-container');
            const addSizeRowBtn = document.getElementById('add-size-row-btn');
            const sizeRowTemplate = document.getElementById('size-row-template');

            if (addSizeRowBtn && sizeRowsContainer && sizeRowTemplate) {
                let sizeRowIndex = document.querySelectorAll('.size-row-item').length;

                addSizeRowBtn.addEventListener('click', function () {
                    const clone = sizeRowTemplate.content.cloneNode(true);
                    const html = clone.querySelector('.size-row-item').outerHTML.replace(/__INDEX__/g, sizeRowIndex);
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newElem = temp.firstElementChild;

                    sizeRowsContainer.appendChild(newElem);
                    sizeRowIndex++;
                    attachRemoveEventSizeRow(newElem.querySelector('.remove-size-row-btn'));
                });
            }

            function attachRemoveEventSizeRow(btn) {
                if (!btn) return;
                btn.addEventListener('click', function () {
                    const item = this.closest('.size-row-item');
                    if (item) item.remove();
                });
            }

            document.querySelectorAll('.remove-size-row-btn').forEach(btn => {
                attachRemoveEventSizeRow(btn);
            });
        });

        const FEATHER_ICONS = {
            'Truck': '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
            'Shield': '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            'Lock': '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'Refresh': '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>',
            'Star': '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
            'Phone': '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.41 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.52 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.16 6.16l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
            'Credit Card': '<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
            'Package': '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
            'Zap': '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
            'Heart': '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
            'Smile': '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>'
        };

        function initIconGridForContainer(container) {
            if (!container) return;
            const selectedPath = container.dataset.selected;
            container.innerHTML = '';
            Object.entries(FEATHER_ICONS).forEach(([name, path]) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                const isSelected = selectedPath === path;
                btn.className = `p-2 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition ${isSelected ? 'border-gray-900 dark:border-white bg-gray-100 dark:bg-gray-700' : 'border-gray-200 dark:border-gray-600'}`;
                btn.title = name;
                btn.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 dark:text-gray-300">${path}</svg>`;
                btn.onclick = () => {
                    container.parentElement.querySelector('input[type="hidden"]').value = path;
                    container.querySelectorAll('button').forEach(b => b.classList.remove('border-gray-900', 'dark:border-white', 'bg-gray-100', 'dark:bg-gray-700'));
                    container.querySelectorAll('button').forEach(b => b.classList.add('border-gray-200', 'dark:border-gray-600'));
                    btn.classList.add('border-gray-900', 'dark:border-white', 'bg-gray-100', 'dark:bg-gray-700');
                    btn.classList.remove('border-gray-200', 'dark:border-gray-600');
                };
                container.appendChild(btn);
            });
        }

        function initIconGrids() {
            document.querySelectorAll('.icon-grid').forEach(container => {
                initIconGridForContainer(container);
            });
        }

        document.addEventListener('DOMContentLoaded', initIconGrids);

        function addStat() {
            // function kept empty or removed as button is gone
        }

        function addTrust() {
            // function kept empty or removed as button is gone
        }

        // Handle dynamic toggle label updates
        document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const container = this.closest('.flex');
                
                // Special handling for logo type toggle
                if (container.id === 'logo-type-toggle') {
                    const labels = container.querySelectorAll('span.text-xs');
                    if (this.checked) {
                        labels[0].className = 'text-xs font-medium text-gray-500 dark:text-gray-400';
                        labels[1].className = 'text-xs font-medium text-gray-900 dark:text-primary-a0';
                    } else {
                        labels[0].className = 'text-xs font-medium text-gray-900 dark:text-primary-a0';
                        labels[1].className = 'text-xs font-medium text-gray-500 dark:text-gray-400';
                    }
                    return;
                }

                const label = container.querySelector('span:last-child');
                if (label && label.classList.contains('uppercase')) {
                    if (this.checked) {
                        label.textContent = 'Visible';
                        label.classList.remove('text-gray-400');
                        label.classList.add('text-green-600');
                    } else {
                        label.textContent = 'Hidden';
                        label.classList.remove('text-green-600');
                        label.classList.add('text-gray-400');
                    }
                }
            });
        });
    </script>
@endsection