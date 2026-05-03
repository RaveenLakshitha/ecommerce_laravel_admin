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
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            input:checked + .toggle-slider {
                background-color: #111827;
            }
            input:checked + .toggle-slider:before {
                transform: translateX(26px);
            }
            .dark .toggle-slider {
                background-color: #374151;
            }
            .dark input:checked + .toggle-slider {
                background-color: #f3f4f6;
            }
            .dark input:checked + .toggle-slider:before {
                background-color: #111827;
            }
        </style>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.storefront.update') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-transparent rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                <div class="p-6 space-y-12">
                    
                    <!-- Branding Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Site Branding (Header Logo)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Main Text <span class="text-xs text-gray-400 font-normal ml-2">(Default: KARBNZOL)</span></label>
                                <input type="text" name="storefront_logo_text" value="{{ old('storefront_logo_text', $setting->storefront_logo_text ?? '') }}" maxlength="50" placeholder="e.g., KARBNZOL" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Subtext <span class="text-xs text-gray-400 font-normal ml-2">(Displayed below logo)</span></label>
                                <input type="text" name="storefront_logo_subtext" value="{{ old('storefront_logo_subtext', $setting->storefront_logo_subtext ?? '') }}" maxlength="100" placeholder="e.g., Premium Menswear" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Support Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" maxlength="20" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Support Email</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email) }}" maxlength="255" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    <!-- Offers Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Announcement Bar (Global Top Bar)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Announcement Text <span class="text-xs text-gray-400 font-normal ml-2">(Repeats in marquee)</span></label>
                                <input type="text" name="storefront_offer_text" value="{{ old('storefront_offer_text', $setting->storefront_offer_text ?? '') }}" maxlength="50" placeholder="e.g., FREE SHIPPING ON ORDERS OVER RS. 5,000" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Announcement Link (Optional)</label>
                                <input type="text" name="storefront_offer_link" value="{{ old('storefront_offer_link', $setting->storefront_offer_link ?? '') }}" maxlength="255" placeholder="e.g., /collections/sale" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    <!-- Marquee Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Home Page Marquee Bar</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Marquee Text <span class="text-xs text-gray-400 font-normal ml-2">(Use '|' to separate multiple messages)</span></label>
                                <input type="text" name="storefront_marquee_text" value="{{ old('storefront_marquee_text', $setting->storefront_marquee_text ?? '') }}" maxlength="255" placeholder="e.g., Free Delivery | New Arrivals | MintPay Available" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Marquee Link (Optional)</label>
                                <input type="text" name="storefront_marquee_link" value="{{ old('storefront_marquee_link', $setting->storefront_marquee_link ?? '') }}" maxlength="255" placeholder="e.g., /collections/new-arrivals" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    <!-- About Us Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">About Us Section (Footer/General)</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Short About Us Text <span class="text-xs text-gray-400 font-normal ml-2">(Displayed in footer)</span></label>
                                <textarea name="storefront_about_us" rows="2" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">{{ old('storefront_about_us', $setting->storefront_about_us ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">About Us Page Content <span class="text-xs text-gray-400 font-normal ml-2">(Optional fallback for About page)</span></label>
                                <textarea name="storefront_about_us_content" rows="4" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">{{ old('storefront_about_us_content', $setting->storefront_about_us_content ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Our Story Section (About Page) -->
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
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Our Story Title</label>
                                    <input type="text" name="storefront_our_story_title" value="{{ old('storefront_our_story_title', $setting->storefront_our_story_title ?? '') }}" maxlength="100" placeholder="e.g., Our Story" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Story Image</label>
                                    @if($setting->storefront_our_story_image)
                                        <div class="mb-2 flex items-center gap-2">
                                            <img src="{{ asset('storage/' . $setting->storefront_our_story_image) }}" class="h-12 w-auto object-cover rounded">
                                            <label class="text-xs text-red-500 flex items-center gap-1 cursor-pointer">
                                                <input type="checkbox" name="remove_our_story_image" value="1"> Remove
                                            </label>
                                        </div>
                                    @endif
                                    <input type="file" name="storefront_our_story_image" accept="image/*" class="w-full text-sm text-gray-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Our Story Content</label>
                                <textarea name="storefront_our_story_content" rows="6" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">{{ old('storefront_our_story_content', $setting->storefront_our_story_content ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Performance Stats (Home Page)</h2>
                                <div class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_stats_show" value="0">
                                        <input type="checkbox" name="storefront_stats_show" value="1" {{ ($setting->storefront_stats_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="text-[10px] uppercase font-bold {{ ($setting->storefront_stats_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
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
                                <div class="stat-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs">×</button>
                                    <div class="space-y-3">
                                        <input type="text" name="storefront_stats[{{$index}}][number]" value="{{ $stat['number'] ?? '' }}" placeholder="Value (e.g. 12K+)" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        <input type="text" name="storefront_stats[{{$index}}][label]" value="{{ $stat['label'] ?? '' }}" placeholder="Label" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Trust Items Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Trust Signals (Footer Bar)</h2>
                                <div class="flex items-center gap-2 bg-gray-50 dark:bg-surface-tonal-a10 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-surface-tonal-a20">
                                    <span class="text-[10px] uppercase font-bold text-gray-400">Status:</span>
                                    <label class="toggle-switch" style="transform: scale(0.8); transform-origin: left;">
                                        <input type="hidden" name="storefront_trust_show" value="0">
                                        <input type="checkbox" name="storefront_trust_show" value="1" {{ ($setting->storefront_trust_show ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="text-[10px] uppercase font-bold {{ ($setting->storefront_trust_show ?? true) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ ($setting->storefront_trust_show ?? true) ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div id="trust-container" class="space-y-4">
                            @php
                                $trusts = is_array($setting->storefront_trust_items) ? $setting->storefront_trust_items : [
                                    ['title' => 'Free Delivery', 'subtitle' => 'On orders over Rs. 5,000', 'svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'],
                                    ['title' => 'Secure Payments', 'subtitle' => 'MintPay & Stripe Integration', 'svg' => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>'],
                                    ['title' => 'Easy Returns', 'subtitle' => '14-day exchange policy', 'svg' => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>'],
                                    ['title' => 'Premium Quality', 'subtitle' => 'Hand-picked fabrics only', 'svg' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>']
                                ];
                            @endphp
                            @foreach($trusts as $index => $trust)
                                <div class="trust-item p-4 border border-gray-200 dark:border-gray-600 rounded-xl relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-xs">×</button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-span-1">
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Title</label>
                                            <input type="text" name="storefront_trust_items[{{$index}}][title]" value="{{ $trust['title'] ?? '' }}" placeholder="Title" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mt-2 mb-1">Subtitle</label>
                                            <input type="text" name="storefront_trust_items[{{$index}}][subtitle]" value="{{ $trust['subtitle'] ?? '' }}" placeholder="Subtitle" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-2">Select Icon</label>
                                            <input type="hidden" name="storefront_trust_items[{{$index}}][svg]" value="{{ $trust['svg'] ?? '' }}">
                                            <div class="flex flex-wrap gap-2 icon-grid" data-selected="{{ $trust['svg'] ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Banners Section -->
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Hero Banners / Sliders</h2>
                            <button type="button" id="add-banner-btn" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-200 dark:bg-transparent dark:hover:bg-gray-700 transition">
                                + Add Banner
                            </button>
                        </div>

                        <div id="banners-container" class="space-y-6">
                            @php
                                $banners = is_array($setting->storefront_banners) ? $setting->storefront_banners : [];
                            @endphp
                            
                            @if(count($banners) === 0)
                                <!-- Empty state fallback handled by JS on load if needed, but let's output one blank -->
                                <div class="banner-item border border-gray-200 dark:border-gray-600 p-4 rounded-lg relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-banner-btn">Remove</button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tag <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                                            <input type="text" name="banners[0][tag]" placeholder="e.g. SS 2025 — New Drop" maxlength="25" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                                            <input type="text" name="banners[0][title]" placeholder="Line 1" maxlength="25" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 120 chars)</span></label>
                                            <input type="text" name="banners[0][subtitle]" placeholder="Description" maxlength="120" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Link URL</label>
                                            <input type="text" name="banners[0][link]" placeholder="/products" maxlength="255" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Banner Image</label>
                                            <input type="file" name="banners[0][image]" accept="image/*" class="w-full text-sm text-gray-500">
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach($banners as $index => $banner)
                                <div class="banner-item border border-gray-200 dark:border-gray-600 p-4 rounded-lg relative bg-gray-50 dark:bg-surface-tonal-a10">
                                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-banner-btn">Remove</button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tag <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                                            <input type="text" name="banners[{{$index}}][tag]" value="{{ $banner['tag'] ?? '' }}" maxlength="25" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                                            <input type="text" name="banners[{{$index}}][title]" value="{{ $banner['title'] ?? '' }}" maxlength="25" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 120 chars)</span></label>
                                            <input type="text" name="banners[{{$index}}][subtitle]" value="{{ $banner['subtitle'] ?? '' }}" maxlength="120" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Link URL</label>
                                            <input type="text" name="banners[{{$index}}][link]" value="{{ $banner['link'] ?? '' }}" maxlength="255" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Banner Image</label>
                                            @if(!empty($banner['image']))
                                                <div class="mb-2 flex items-center gap-2">
                                                    <img src="{{ asset('storage/' . $banner['image']) }}" class="h-10 w-auto object-cover rounded">
                                                    <label class="text-xs text-red-500 flex items-center gap-1 cursor-pointer">
                                                        <input type="checkbox" name="banners[{{$index}}][remove_image]" value="1"> Remove
                                                    </label>
                                                </div>
                                            @endif
                                            <input type="file" name="banners[{{$index}}][image]" accept="image/*" class="w-full text-sm text-gray-500">
                                            <input type="hidden" name="banners[{{$index}}][existing_image]" value="{{ $banner['image'] ?? '' }}">
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
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-white dark:text-gray-500 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Banner Template for JS -->
    <template id="banner-template">
        <div class="banner-item border border-gray-200 dark:border-gray-600 p-4 rounded-lg relative bg-gray-50 dark:bg-surface-tonal-a10">
            <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-banner-btn">Remove</button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tag <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                    <input type="text" name="banners[__INDEX__][tag]" placeholder="e.g. SS 2025 — New Drop" maxlength="25" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 25 chars)</span></label>
                    <input type="text" name="banners[__INDEX__][title]" placeholder="Line 1" maxlength="25" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle <span class="text-[10px] text-gray-400 font-normal ml-1">(Max 120 chars)</span></label>
                    <input type="text" name="banners[__INDEX__][subtitle]" placeholder="Description" maxlength="120" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Link URL</label>
                    <input type="text" name="banners[__INDEX__][link]" placeholder="/products" maxlength="255" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-transparent dark:text-primary-a0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Banner Image</label>
                    <input type="file" name="banners[__INDEX__][image]" accept="image/*" class="w-full text-sm text-gray-500 p-1">
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('banners-container');
            const addBtn = document.getElementById('add-banner-btn');
            const template = document.getElementById('banner-template');
            
            // Generate a unique index for new items
            let bannerIndex = document.querySelectorAll('.banner-item').length;

            addBtn.addEventListener('click', function() {
                const clone = template.content.cloneNode(true);
                
                // Replace __INDEX__ with actual index
                const html = clone.querySelector('.banner-item').outerHTML.replace(/__INDEX__/g, bannerIndex);
                
                // Create a temporary div to convert string back to DOM element
                const temp = document.createElement('div');
                temp.innerHTML = html;
                const newElem = temp.firstElementChild;
                
                container.appendChild(newElem);
                bannerIndex++;
                
                attachRemoveEvent(newElem.querySelector('.remove-banner-btn'));
            });

            function attachRemoveEvent(btn) {
                if(!btn) return;
                btn.addEventListener('click', function() {
                    const item = this.closest('.banner-item');
                    if (item) item.remove();
                });
            }

            // Attach remove event to existing buttons
            document.querySelectorAll('.remove-banner-btn').forEach(btn => {
                attachRemoveEvent(btn);
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

        function initIconGrids() {
            document.querySelectorAll('.icon-grid').forEach(container => {
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
            toggle.addEventListener('change', function() {
                const container = this.closest('.flex');
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
