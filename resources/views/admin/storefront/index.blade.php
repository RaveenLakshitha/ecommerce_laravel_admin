@extends('layouts.app')

@section('title', 'Storefront Customization')

@section('content')
    <div class="px-4 sm:px-6 lg:px-4 pb-4 sm:py-12 pt-20">
        <div class=" mb-8">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-primary-a0">Storefront Customization</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage the content displayed on your customer site.</p>
        </div>

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
                    
                    <!-- Contact Info Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Support Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" maxlength="20" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Support Email</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email) }}" maxlength="255" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    <!-- Offers Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">Promotional Offer Bar</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Offer Text <span class="text-xs text-gray-400 font-normal ml-2">(Max 50 characters)</span></label>
                                <input type="text" name="storefront_offer_text" value="{{ old('storefront_offer_text', $setting->storefront_offer_text) }}" maxlength="50" placeholder="e.g., Free shipping on orders over $100!" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Offer Link (Optional)</label>
                                <input type="text" name="storefront_offer_link" value="{{ old('storefront_offer_link', $setting->storefront_offer_link) }}" maxlength="255" placeholder="e.g., /collections/sale" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">
                            </div>
                        </div>
                    </div>

                    <!-- About Us Section -->
                    <div class="border-b border-gray-200 dark:border-surface-tonal-a30 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0 mb-6">About Us (Footer)</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Short Description <span class="text-xs text-gray-400 font-normal ml-2">(Max 250 characters)</span></label>
                            <textarea name="storefront_about_us" rows="4" maxlength="250" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-500 focus:border-transparent dark:bg-transparent dark:text-primary-a0 transition-shadow">{{ old('storefront_about_us', $setting->storefront_about_us) }}</textarea>
                        </div>
                    </div>

                    <!-- Banners Section -->
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-primary-a0">Hero Banners / Sliders</h2>
                            <button type="button" id="add-banner-btn" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-medium rounded hover:bg-gray-200 dark:bg-transparent dark:hover:bg-gray-700 transition">
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
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-white dark:text-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200">
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
    </script>
@endsection
