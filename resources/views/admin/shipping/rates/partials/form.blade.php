<form action="{{ $rate ? route('shipping.rates.update', $rate->id) : route('shipping.rates.store') }}" method="POST" id="rate-drawer-form" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($rate)
        @method('PUT')
    @endif

    <div class="space-y-4">
        {{-- Basic Information --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.Rate Name') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $rate ? $rate->name : '') }}" required
                placeholder="{{ __('file.eg_standard_shipping') ?? 'e.g. Standard Shipping' }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Shipping Zone') }} <span class="text-red-500">*</span>
                </label>
                <select name="shipping_zone_id" required
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                    <option value="">{{ __('file.select_zone') ?? 'Select Zone' }}</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ (old('shipping_zone_id', $rate ? $rate->shipping_zone_id : '') == $zone->id) ? 'selected' : '' }}>
                            {{ $zone->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Courier') }}
                </label>
                <select name="courier_id"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                    <option value="">{{ __('file.any_courier') ?? 'Any Courier' }}</option>
                    @foreach($couriers as $courier)
                        <option value="{{ $courier->id }}" {{ (old('courier_id', $rate ? $rate->courier_id : '') == $courier->id) ? 'selected' : '' }}>
                            {{ $courier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.Rate Amount') }} (Rs.) <span class="text-red-500">*</span>
            </label>
            <input type="number" step="0.01" name="rate_amount" value="{{ old('rate_amount', $rate ? $rate->rate_amount : '') }}" required
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        {{-- Conditions --}}
        <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-400">{{ __('file.Conditions') }} (Optional)</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.Min Weight') }} (kg)</label>
                    <input type="number" step="0.01" name="min_weight" value="{{ old('min_weight', $rate ? $rate->min_weight : '') }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.Max Weight') }} (kg)</label>
                    <input type="number" step="0.01" name="max_weight" value="{{ old('max_weight', $rate ? $rate->max_weight : '') }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.Min Price') }} (Rs.)</label>
                    <input type="number" step="0.01" name="min_price" value="{{ old('min_price', $rate ? $rate->min_price : '') }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.Max Price') }} (Rs.)</label>
                    <input type="number" step="0.01" name="max_price" value="{{ old('max_price', $rate ? $rate->max_price : '') }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-sm font-medium text-emerald-600 dark:text-emerald-400 mb-1.5">{{ __('file.Free Shipping Threshold') }} (Rs.)</label>
                <input type="number" step="0.01" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $rate ? $rate->free_shipping_threshold : '') }}"
                    placeholder="{{ __('file.value_for_free_shipping') ?? 'Value for free shipping' }}"
                    class="block w-full rounded-xl border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50/30 dark:bg-emerald-500/5 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-emerald-300 dark:placeholder:text-emerald-500/50 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10">
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $rate ? $rate->is_active : true)) ? 'checked' : '' }}
                    class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.Active Rate') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.enable_rate_customer_note') }}</p>
                </div>
            </label>
        </div>
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closeRateDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="rate-drawer-save-text">{{ $rate ? __('file.save_changes') : __('file.save_shipping_rate') ?? 'Save Shipping Rate' }}</span>
            <div id="rate-drawer-loader" class="hidden w-4 h-4 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>
