<form action="{{ $zone ? route('shipping.zones.update', $zone->id) : route('shipping.zones.store') }}" method="POST" id="zone-drawer-form" class="space-y-6">
    @csrf
    @if($zone)
        @method('PUT')
    @endif

    <div class="space-y-4">
        {{-- Basic Information --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.Zone Name') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $zone ? $zone->name : '') }}" required
                placeholder="{{ __('file.eg_island_wide') ?? 'e.g. Island-Wide (Sri Lanka)' }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Country Code') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="country_code" value="{{ old('country_code', $zone ? $zone->country_code : '') }}" required
                    placeholder="{{ __('file.eg_lk') ?? 'e.g. LK' }}" maxlength="2"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Region / State') }}
                </label>
                <input type="text" name="region" value="{{ old('region', $zone ? $zone->region : '') }}"
                    placeholder="{{ __('file.eg_western_province') ?? 'e.g. Western Province' }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $zone ? $zone->is_active : true)) ? 'checked' : '' }}
                    class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.Active Zone') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.enable_zone_note') }}</p>
                </div>
            </label>
        </div>
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closeZoneDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="zone-drawer-save-text">{{ $zone ? __('file.save_changes') : __('file.save_shipping_zone') ?? 'Save Shipping Zone' }}</span>
            <div id="zone-drawer-loader" class="hidden w-4 h-4 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>
