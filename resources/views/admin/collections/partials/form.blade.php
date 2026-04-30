<form id="collection-drawer-form" action="{{ $collection ? route('collections.update', $collection->id) : route('collections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($collection)
        @method('PUT')
    @endif

    <div class="space-y-4">
        {{-- General Information --}}
        <div>
            <label for="drawer_col_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.collection_name') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="drawer_col_name" value="{{ $collection ? $collection->name : old('name') }}" required
                placeholder="{{ __('file.eg_summer_essentials') }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
        </div>

        <div>
            <label for="drawer_col_slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.slug') }}
            </label>
            <input type="text" name="slug" id="drawer_col_slug" value="{{ $collection ? $collection->slug : old('slug') }}"
                placeholder="{{ __('file.auto_generated') }}"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 font-mono">
        </div>

        <div>
            <label for="drawer_col_desc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('file.description') }}
            </label>
            <textarea name="description" id="drawer_col_desc" rows="3"
                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 resize-y">{{ $collection ? $collection->description : old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="drawer_col_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.start_date') }}
                </label>
                <input type="date" name="start_date" id="drawer_col_start" value="{{ ($collection && $collection->start_date) ? $collection->start_date->format('Y-m-d') : old('start_date') }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>
            <div>
                <label for="drawer_col_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('file.end_date') }}
                </label>
                <input type="date" name="end_date" id="drawer_col_end" value="{{ ($collection && $collection->end_date) ? $collection->end_date->format('Y-m-d') : old('end_date') }}"
                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
            </div>
        </div>

        {{-- Banner Image --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('file.banner_image') }}
            </label>
            
            <div class="relative w-full rounded-xl overflow-hidden border-2 border-dashed border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-surface-tonal-a10 cursor-pointer group/upload hover:border-primary transition-all aspect-[21/9]"
                onclick="document.getElementById('drawer-banner-input').click()">
                <img id="drawer-banner-preview" src="{{ $collection ? $collection->banner_url : '' }}"
                    class="absolute inset-0 w-full h-full object-cover {{ $collection && $collection->banner_url ? '' : 'hidden' }} z-10">
                
                <div id="drawer-banner-placeholder"
                    class="absolute inset-0 flex flex-col items-center justify-center gap-2 {{ $collection && $collection->banner_url ? 'hidden' : '' }}">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 group-hover/upload:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm font-medium text-gray-400 group-hover/upload:text-primary transition-colors">
                        {{ __('file.upload_banner') }}
                    </p>
                    <p class="text-[9px] text-gray-300 dark:text-gray-600">PNG, JPG, SVG, WEBP</p>
                </div>
                
                <div id="drawer-banner-hover"
                    class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-1 opacity-0 group-hover/upload:opacity-100 transition-opacity z-20 {{ $collection && $collection->banner_url ? '' : 'hidden' }}">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-medium text-white uppercase tracking-widest">Change Banner</span>
                </div>
                
                <input type="file" name="banner_image" id="drawer-banner-input" class="hidden" accept="image/*" onchange="previewDrawerBanner(this)">
            </div>
            
            @if($collection && $collection->banner_image)
                <div class="mt-2 flex items-center gap-2">
                    <label class="flex items-center gap-2 text-xs font-medium text-red-500 cursor-pointer">
                        <input type="checkbox" name="remove_banner" value="1" class="h-4 w-4 rounded border-red-300 text-red-600 focus:ring-red-500">
                        Remove existing banner
                    </label>
                </div>
            @endif
        </div>

        {{-- Status and Featured --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ ($collection ? $collection->is_active : true) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.active') }}</span>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_featured" value="1" {{ ($collection && $collection->is_featured) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('file.featured') }}</span>
            </label>
        </div>
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closeCollectionDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="collection-drawer-save-text">{{ $collection ? __('file.save_changes') : __('file.save_collection') }}</span>
            <div id="collection-drawer-loader" class="hidden w-4 h-4 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>

<script>
    function previewDrawerBanner(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('drawer-banner-preview');
                const placeholder = document.getElementById('drawer-banner-placeholder');
                const hover = document.getElementById('drawer-banner-hover');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
                
                if (hover) {
                    hover.classList.remove('hidden');
                    hover.classList.add('flex');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('drawer_col_name').addEventListener('input', function () {
        document.getElementById('drawer_col_slug').value = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });
</script>
