<form action="{{ $location ? route('shipping.pickups.update', $location->id) : route('shipping.pickups.store') }}" method="POST" id="pickup-drawer-form">
    @csrf
    @if($location)
        @method('PUT')
    @endif

    <div class="space-y-6">
        {{-- Basic Information --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5 space-y-5">
            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Location Name') }} <span class="text-error">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $location ? $location->name : '') }}" required
                    placeholder="e.g. Main Showroom - Colombo"
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.Phone Number') }}
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $location ? $location->phone : '') }}"
                        placeholder="+94 11 234 5678"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.Email Address') }}
                    </label>
                    <input type="email" name="email" value="{{ old('email', $location ? $location->email : '') }}"
                        placeholder="pickup@example.com"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
            </div>
        </div>

        {{-- Address Details --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5 space-y-5">
            <h4 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Geographic Information</h4>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Address Line 1') }} <span class="text-error">*</span>
                </label>
                <input type="text" name="address_line_1" value="{{ old('address_line_1', $location ? $location->address_line_1 : '') }}" required
                    placeholder="Street name and number"
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Address Line 2') }}
                </label>
                <input type="text" name="address_line_2" value="{{ old('address_line_2', $location ? $location->address_line_2 : '') }}"
                    placeholder="Apartment, suite, etc."
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.City') }} <span class="text-error">*</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city', $location ? $location->city : '') }}" required
                        placeholder="e.g. Colombo"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.State / Province') }}
                    </label>
                    <input type="text" name="state" value="{{ old('state', $location ? $location->state : '') }}"
                        placeholder="e.g. Western"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.Postal Code') }}
                    </label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $location ? $location->postal_code : '') }}"
                        placeholder="e.g. 00100"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5">
            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $location ? $location->is_active : true)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                </div>
                <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors uppercase tracking-widest">Active operational status</span>
            </label>
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="button" onclick="closePickupDrawer()"
            class="flex-1 px-6 py-3.5 rounded-xl border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 text-xs font-black uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
            Cancel
        </button>
        <button type="submit"
            class="flex-[2] relative group overflow-hidden px-6 py-3.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-gray-900/10 dark:shadow-white/5 active:scale-[0.98] transition-all">
            <span id="pickup-drawer-save-text" class="relative z-10">Save Pickup Location</span>
            <div id="pickup-drawer-loader" class="hidden absolute inset-0 items-center justify-center bg-inherit z-20">
                <div class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
            </div>
        </button>
    </div>
</form>
