<form action="{{ $courier ? route('shipping.couriers.update', $courier->id) : route('shipping.couriers.store') }}" method="POST" id="courier-drawer-form">
    @csrf
    @if($courier)
        @method('PUT')
    @endif

    <div class="space-y-6">
        {{-- Basic Information --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5 space-y-5">
            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Courier Name') }} <span class="text-error">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $courier ? $courier->name : '') }}" required
                    placeholder="e.g. DHL Express"
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Description') }}
                </label>
                <textarea name="description" rows="3"
                    placeholder="Brief details about this courier service..."
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">{{ old('description', $courier ? $courier->description : '') }}</textarea>
            </div>
        </div>

        {{-- API / Connection Settings --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5 space-y-5">
            <h4 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">API & Tracking Configuration</h4>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                    {{ __('file.Base URL') }}
                </label>
                <input type="url" name="base_url" value="{{ old('base_url', $courier ? $courier->base_url : '') }}"
                    placeholder="https://api.courier.com/v1"
                    class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.API Key') }}
                    </label>
                    <input type="text" name="api_key" value="{{ old('api_key', $courier ? $courier->api_key : '') }}"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
                        {{ __('file.API Secret') }}
                    </label>
                    <input type="password" name="api_secret" value="{{ old('api_secret', $courier ? $courier->api_secret : '') }}"
                        class="block w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-surface-tonal-a10 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
            </div>
        </div>

        {{-- Capabilities & Status --}}
        <div class="bg-gray-50/50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5 space-y-6">
            <h4 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Service Capabilities</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="supports_tracking" value="1" {{ (old('supports_tracking', $courier ? $courier->supports_tracking : false)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors uppercase tracking-widest">Tracking</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="supports_label_generation" value="1" {{ (old('supports_label_generation', $courier ? $courier->supports_label_generation : false)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors uppercase tracking-widest">Labels</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="supports_cod" value="1" {{ (old('supports_cod', $courier ? $courier->supports_cod : false)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors uppercase tracking-widest">COD Support</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="default_for_cod" value="1" {{ (old('default_for_cod', $courier ? $courier->default_for_cod : false)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors uppercase tracking-widest">Default COD</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-white/5">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $courier ? $courier->is_active : true)) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-gray-200 dark:border-white/10 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                    <span class="text-sm font-black text-gray-900 dark:text-white group-hover:text-primary transition-colors uppercase tracking-[0.1em]">Active Provider</span>
                </label>
            </div>
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="button" onclick="closeCourierDrawer()"
            class="flex-1 px-6 py-3.5 rounded-xl border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 text-xs font-black uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
            Cancel
        </button>
        <button type="submit"
            class="flex-[2] relative group overflow-hidden px-6 py-3.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-gray-900/10 dark:shadow-white/5 active:scale-[0.98] transition-all">
            <span id="courier-drawer-save-text" class="relative z-10">Save Courier Provider</span>
            <div id="courier-drawer-loader" class="hidden absolute inset-0 items-center justify-center bg-inherit z-20">
                <div class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
            </div>
        </button>
    </div>
</form>
