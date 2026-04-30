<form action="{{ $location ? route('shipping.pickups.update', $location->id) : route('shipping.pickups.store') }}" method="POST" id="pickup-drawer-form" class="space-y-6">
    @csrf
    @if($location)
        @method('PUT')
    @endif

    <div class="space-y-4">
        {{-- Basic Information --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.Location Name') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $location ? $location->name : '') }}" required
                placeholder="{{ __('file.eg_main_showroom') ?? 'e.g. Main Showroom - Colombo' }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Phone Number') }}
                </label>
                <input type="text" name="phone" value="{{ old('phone', $location ? $location->phone : '') }}"
                    placeholder="{{ __('file.eg_phone_number') ?? '+94 11 234 5678' }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Email Address') }}
                </label>
                <input type="email" name="email" value="{{ old('email', $location ? $location->email : '') }}"
                    placeholder="{{ __('file.eg_email_address') ?? 'pickup@example.com' }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>
        </div>

        {{-- Address Details --}}
        <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-400">{{ __('file.geographic_information') }}</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Address Line 1') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="address_line_1" value="{{ old('address_line_1', $location ? $location->address_line_1 : '') }}" required
                placeholder="{{ __('file.eg_street_name') ?? 'Street name and number' }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Address Line 2') }}
                </label>
                <input type="text" name="address_line_2" value="{{ old('address_line_2', $location ? $location->address_line_2 : '') }}"
                placeholder="{{ __('file.eg_apartment') ?? 'Apartment, suite, etc.' }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('file.City') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city', $location ? $location->city : '') }}" required
                    placeholder="{{ __('file.eg_colombo') ?? 'e.g. Colombo' }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('file.State / Province') }}
                    </label>
                    <input type="text" name="state" value="{{ old('state', $location ? $location->state : '') }}"
                    placeholder="{{ __('file.eg_western_province') ?? 'e.g. Western' }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('file.Postal Code') }}
                    </label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $location ? $location->postal_code : '') }}"
                    placeholder="{{ __('file.eg_postal_code') ?? 'e.g. 00100' }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $location ? $location->is_active : true)) ? 'checked' : '' }}
                    class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.Active operational status') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.enable_location_note') }}</p>
                </div>
            </label>
        </div>
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closePickupDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="pickup-drawer-save-text">{{ $location ? __('file.save_changes') : __('file.save_pickup_location') ?? 'Save Pickup Location' }}</span>
            <div id="pickup-drawer-loader" class="hidden w-4 h-4 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>
