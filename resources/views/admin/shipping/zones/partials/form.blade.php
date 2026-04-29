<form action="{{ $zone ? route('shipping.zones.update', $zone->id) : route('shipping.zones.store') }}" method="POST" id="zone-drawer-form">
    @csrf
    @if($zone)
        @method('PUT')
    @endif

    <div class="space-y-6">
        {{-- Basic Information --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('file.Zone Name') }} <span class="text-error">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $zone ? $zone->name : '') }}" required
                    placeholder="e.g. Island-Wide (Sri Lanka)"
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-normal text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('file.Country Code') }} <span class="text-error">*</span>
                    </label>
                    <input type="text" name="country_code" value="{{ old('country_code', $zone ? $zone->country_code : '') }}" required
                        placeholder="e.g. LK" maxlength="2"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-normal text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('file.Region / State') }}
                    </label>
                    <input type="text" name="region" value="{{ old('region', $zone ? $zone->region : '') }}"
                        placeholder="e.g. Western Province"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-normal text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5">
            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $zone ? $zone->is_active : true)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors">Active Zone</span>
            </label>
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="button" onclick="closeZoneDrawer()"
            class="flex-1 px-6 py-3.5 rounded-xl border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 text-sm font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
            Cancel
        </button>
        <button type="submit"
            class="flex-[2] relative group overflow-hidden px-6 py-3.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold shadow-xl shadow-gray-900/10 dark:shadow-white/5 active:scale-[0.98] transition-all">
            <span id="zone-drawer-save-text" class="relative z-10">Save Shipping Zone</span>
            <div id="zone-drawer-loader" class="hidden absolute inset-0 items-center justify-center bg-inherit z-20">
                <div class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
            </div>
        </button>
    </div>
</form>
