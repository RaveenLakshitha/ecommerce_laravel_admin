<form action="{{ $rate ? route('shipping.rates.update', $rate->id) : route('shipping.rates.store') }}" method="POST" id="rate-drawer-form" enctype="multipart/form-data">
    @csrf
    @if($rate)
        @method('PUT')
    @endif

    <div class="space-y-6">
        {{-- Basic Information --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5 space-y-5">
            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Rate Name') }} <span class="text-error">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $rate ? $rate->name : '') }}" required
                    placeholder="e.g. Standard Shipping"
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.Shipping Zone') }} <span class="text-error">*</span>
                    </label>
                    <select name="shipping_zone_id" required
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="">Select Zone</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ (old('shipping_zone_id', $rate ? $rate->shipping_zone_id : '') == $zone->id) ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.Courier') }}
                    </label>
                    <select name="courier_id"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="">Any Courier</option>
                        @foreach($couriers as $courier)
                            <option value="{{ $courier->id }}" {{ (old('courier_id', $rate ? $rate->courier_id : '') == $courier->id) ? 'selected' : '' }}>
                                {{ $courier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Rate Amount') }} (Rs.) <span class="text-error">*</span>
                </label>
                <input type="number" step="0.01" name="rate_amount" value="{{ old('rate_amount', $rate ? $rate->rate_amount : '') }}" required
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        {{-- Conditions --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5">
            <h4 class="text-[11px] font-black text-primary uppercase tracking-[0.25em] mb-6 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                {{ __('file.Conditions') }} (Optional)
            </h4>
            
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Min Weight (kg)</label>
                            <input type="number" step="0.01" name="min_weight" value="{{ old('min_weight', $rate ? $rate->min_weight : '') }}"
                                class="w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-2.5 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Max Weight (kg)</label>
                            <input type="number" step="0.01" name="max_weight" value="{{ old('max_weight', $rate ? $rate->max_weight : '') }}"
                                class="w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-2.5 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Min Price (Rs.)</label>
                            <input type="number" step="0.01" name="min_price" value="{{ old('min_price', $rate ? $rate->min_price : '') }}"
                                class="w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-2.5 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Max Price (Rs.)</label>
                            <input type="number" step="0.01" name="max_price" value="{{ old('max_price', $rate ? $rate->max_price : '') }}"
                                class="w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-2.5 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-white/5">
                    <div class="p-5 bg-emerald-50/50 dark:bg-emerald-500/5 rounded-2xl border border-emerald-100/50 dark:border-emerald-500/10">
                        <label class="block text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-[0.2em] mb-2">
                            Free Shipping Threshold (Rs.)
                        </label>
                        <input type="number" step="0.01" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $rate ? $rate->free_shipping_threshold : '') }}"
                            placeholder="Value for free shipping"
                            class="block w-full rounded-xl border-emerald-200/50 dark:border-emerald-500/20 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5">
            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $rate ? $rate->is_active : true)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                </div>
                <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors uppercase tracking-widest">Active Rate</span>
            </label>
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="button" onclick="closeRateDrawer()"
            class="flex-1 px-6 py-3.5 rounded-xl border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 text-xs font-black uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
            Cancel
        </button>
        <button type="submit"
            class="flex-[2] relative group overflow-hidden px-6 py-3.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-gray-900/10 dark:shadow-white/5 active:scale-[0.98] transition-all">
            <span id="rate-drawer-save-text" class="relative z-10">Save Shipping Rate</span>
            <div id="rate-drawer-loader" class="hidden absolute inset-0 items-center justify-center bg-inherit z-20">
                <div class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
            </div>
        </button>
    </div>
</form>
