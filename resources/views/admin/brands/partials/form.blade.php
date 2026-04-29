<form id="brand-drawer-form" action="{{ $brand ? route('brands.update', $brand->id) : route('brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($brand)
        @method('PUT')
    @endif

    <div class="space-y-4">
        {{-- Brand Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.brand_name') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="drawer_name" value="{{ $brand ? $brand->name : old('name') }}" required
                placeholder="{{ __('file.eg_brands') }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        {{-- Slug --}}
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.slug') }}
            </label>
            <input type="text" name="slug" id="drawer_slug" value="{{ $brand ? $brand->slug : old('slug') }}"
                placeholder="{{ __('file.auto_generated') }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 font-mono">
        </div>

        {{-- Website URL --}}
        <div>
            <label for="website_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.website_url') }}
            </label>
            <input type="url" name="website_url" id="drawer_website_url" value="{{ $brand ? $brand->website_url : old('website_url') }}"
                placeholder="https://example.com"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        {{-- Sort Order --}}
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.sort_order') }}
            </label>
            <input type="number" name="sort_order" id="drawer_sort_order" value="{{ $brand ? $brand->sort_order : old('sort_order', 0) }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.description') }}
            </label>
            <textarea name="description" id="drawer_description" rows="3"
                placeholder="{{ __('file.brief_brand_story') }}…"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 resize-y">{{ $brand ? $brand->description : old('description') }}</textarea>
        </div>

        {{-- Featured --}}
        <div>
            <label class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_featured" value="1" {{ ($brand && $brand->is_featured) || old('is_featured') ? 'checked' : '' }}
                    class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.featured_brand') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('file.highlight_on_storefront') }}</p>
                </div>
            </label>
        </div>

        {{-- Logo Upload --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('file.brand_logo') }}
            </label>

            {{-- Upload Zone --}}
            <div class="relative w-full rounded-xl overflow-hidden border-2 border-dashed border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-surface-tonal-a10 cursor-pointer group/upload hover:border-primary transition-all"
                style="min-height: 140px;"
                onclick="document.getElementById('drawer-logo-input').click()">

                {{-- Preview image (hidden until selected or if brand has a logo) --}}
                <img id="drawer-logo-preview"
                    src="{{ $brand && $brand->logo_url ? $brand->logo_url : '' }}"
                    class="w-full h-full object-contain p-4 {{ $brand && $brand->logo_url ? 'block' : 'hidden' }}"
                    style="max-height: 160px;"
                    alt="Brand Logo Preview">

                {{-- Placeholder (shown when no logo) --}}
                <div id="drawer-logo-placeholder"
                    class="absolute inset-0 flex flex-col items-center justify-center gap-2 {{ $brand && $brand->logo_url ? 'hidden' : '' }}">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 group-hover/upload:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm font-medium text-gray-400 group-hover/upload:text-primary transition-colors">
                        {{ __('file.upload_brand_logo') }}
                    </p>
                    <p class="text-[9px] text-gray-300 dark:text-gray-600">PNG, JPG, SVG, WEBP</p>
                </div>

                {{-- Hover overlay when image is present --}}
                <div id="drawer-logo-hover"
                    class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-1 opacity-0 group-hover/upload:opacity-100 transition-opacity {{ $brand && $brand->logo_url ? '' : 'hidden' }}">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-medium text-white uppercase tracking-widest">{{ __('file.change_logo') }}</span>
                </div>

                <input type="file" name="logo" id="drawer-logo-input" class="hidden" accept="image/*" onchange="previewDrawerLogo(this)">
            </div>

            @if($brand && $brand->logo_path)
                <div class="mt-2 flex items-center gap-2">
                    <label class="flex items-center gap-2 text-xs font-medium text-red-500 cursor-pointer">
                        <input type="checkbox" name="remove_logo" value="1" class="h-4 w-4 rounded border-red-300 text-red-600 focus:ring-red-500">
                        {{ __('file.remove_existing_logo') }}
                    </label>
                </div>
            @endif
        </div>

        {{-- SEO Settings (Optional in Drawer - maybe keep simple?) --}}
        <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-400">{{ __('file.seo_settings') }}</h3>
            <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.meta_title') }}</label>
                <input type="text" name="meta_title" id="drawer_meta_title" value="{{ $brand ? $brand->meta_title : old('meta_title') }}"
                    placeholder="{{ __('file.seo_title_search') }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
            </div>
            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.meta_description') }}</label>
                <textarea name="meta_description" id="drawer_meta_description" rows="2"
                    placeholder="{{ __('file.brief_summary_seo') }}…"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 resize-y">{{ $brand ? $brand->meta_description : old('meta_description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closeBrandDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="brand-drawer-save-text">{{ $brand ? __('file.save_changes') : __('file.save_brand') }}</span>
            <div id="brand-drawer-loader" class="hidden w-4 h-4 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>

<script>
    document.getElementById('drawer_name').addEventListener('input', function () {
        let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        document.getElementById('drawer_slug').value = slug;
    });

    function previewDrawerLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('drawer-logo-preview');
                const placeholder = document.getElementById('drawer-logo-placeholder');
                const hover = document.getElementById('drawer-logo-hover');

                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    preview.classList.add('block');
                }

                if (placeholder) {
                    placeholder.classList.add('hidden');
                    placeholder.classList.remove('flex');
                }

                if (hover) {
                    hover.classList.remove('hidden');
                    hover.classList.add('flex'); // Ensure it's flex for centering content
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
