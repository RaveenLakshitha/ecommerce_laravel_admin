<form action="{{ $courier ? route('shipping.couriers.update', $courier->id) : route('shipping.couriers.store') }}" method="POST" id="courier-drawer-form" class="space-y-6">
    @csrf
    @if($courier)
        @method('PUT')
    @endif

    <div class="space-y-4">
        {{-- Basic Information --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.Courier Name') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $courier ? $courier->name : '') }}" required
                placeholder="{{ __('file.eg_dhl_express') ?? 'e.g. DHL Express' }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.Description') }}
            </label>
            <textarea name="description" rows="3"
                placeholder="{{ __('file.brief_details_courier') ?? 'Brief details about this courier service...' }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 resize-y">{{ old('description', $courier ? $courier->description : '') }}</textarea>
        </div>

        {{-- API / Connection Settings --}}
        <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-400">API & Tracking Configuration</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.Base URL') }}
                </label>
                <input type="url" name="base_url" value="{{ old('base_url', $courier ? $courier->base_url : '') }}"
                    placeholder="{{ __('file.eg_api_url') ?? 'https://api.courier.com/v1' }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('file.API Key') }}
                    </label>
                    <input type="text" name="api_key" value="{{ old('api_key', $courier ? $courier->api_key : '') }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('file.API Secret') }}
                    </label>
                    <input type="password" name="api_secret" value="{{ old('api_secret', $courier ? $courier->api_secret : '') }}"
                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                </div>
            </div>
        </div>

        {{-- Capabilities & Status --}}
        <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-400">Service Capabilities</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                    <input type="checkbox" name="supports_tracking" value="1" {{ (old('supports_tracking', $courier ? $courier->supports_tracking : false)) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.Tracking') }}</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                    <input type="checkbox" name="supports_label_generation" value="1" {{ (old('supports_label_generation', $courier ? $courier->supports_label_generation : false)) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.Labels') }}</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                    <input type="checkbox" name="supports_cod" value="1" {{ (old('supports_cod', $courier ? $courier->supports_cod : false)) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.COD Support') }}</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                    <input type="checkbox" name="default_for_cod" value="1" {{ (old('default_for_cod', $courier ? $courier->default_for_cod : false)) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.Default COD') }}</span>
                </label>
            </div>
        </div>

        <div class="pt-2">
            <label class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $courier ? $courier->is_active : true)) ? 'checked' : '' }}
                    class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.Active Provider') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.enable_provider_note') }}</p>
                </div>
            </label>
        </div>
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closeCourierDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="courier-drawer-save-text">{{ $courier ? __('file.save_changes') : __('file.save_provider') ?? 'Save Courier Provider' }}</span>
            <div id="courier-drawer-loader" class="hidden w-4 h-4 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>
