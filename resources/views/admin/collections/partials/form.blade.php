<form id="collection-drawer-form" action="{{ $collection ? route('collections.update', $collection->id) : route('collections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($collection)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 gap-6">
        {{-- General Information --}}
        <div class="space-y-4">
            <div>
                <label for="drawer_col_name" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                    {{ __('file.collection_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="drawer_col_name" value="{{ $collection ? $collection->name : old('name') }}" required
                    placeholder="{{ __('file.eg_summer_essentials') }}"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
            </div>

            <div>
                <label for="drawer_col_slug" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                    {{ __('file.slug') }}
                </label>
                <input type="text" name="slug" id="drawer_col_slug" value="{{ $collection ? $collection->slug : old('slug') }}"
                    placeholder="{{ __('file.auto_generated') }}"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none font-mono">
            </div>

            <div>
                <label for="drawer_col_desc" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                    {{ __('file.description') }}
                </label>
                <textarea name="description" id="drawer_col_desc" rows="3"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none resize-none">{{ $collection ? $collection->description : old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="drawer_col_start" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                        {{ __('file.start_date') }}
                    </label>
                    <input type="date" name="start_date" id="drawer_col_start" value="{{ ($collection && $collection->start_date) ? $collection->start_date->format('Y-m-d') : old('start_date') }}"
                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none">
                </div>
                <div>
                    <label for="drawer_col_end" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                        {{ __('file.end_date') }}
                    </label>
                    <input type="date" name="end_date" id="drawer_col_end" value="{{ ($collection && $collection->end_date) ? $collection->end_date->format('Y-m-d') : old('end_date') }}"
                        class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none">
                </div>
            </div>
        </div>

        {{-- Banner Image --}}
        <div class="space-y-2">
            <label class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                {{ __('file.banner_image') }}
            </label>
            <div class="aspect-[21/9] admin-upload-zone rounded-xl border-dashed border-2 border-gray-200 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a30/20 flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-primary transition-all overflow-hidden relative"
                onclick="document.getElementById('drawer-banner-input').click()">
                <img id="drawer-banner-preview" src="{{ $collection ? $collection->banner_url : '' }}"
                    class="absolute inset-0 w-full h-full object-cover {{ $collection && $collection->banner_url ? '' : 'hidden' }} z-10">
                <div id="drawer-banner-placeholder"
                    class="flex flex-col items-center justify-center gap-1 {{ $collection && $collection->banner_url ? 'hidden' : '' }}">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('file.upload_banner') }}</p>
                </div>
                <input type="file" name="banner_image" id="drawer-banner-input" class="hidden" accept="image/*" onchange="previewDrawerBanner(this)">
            </div>
        </div>

        {{-- Status and Featured --}}
        <div class="grid grid-cols-2 gap-4">
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ ($collection ? $collection->is_active : true) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-primary focus:ring-primary">
                <span class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest">{{ __('file.active') }}</span>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                <input type="checkbox" name="is_featured" value="1" {{ ($collection && $collection->is_featured) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-primary focus:ring-primary">
                <span class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest">{{ __('file.featured') }}</span>
            </label>
        </div>
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closeCollectionDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="collection-drawer-save-text">{{ $collection ? __('file.save_changes') : __('file.save_collection') }}</span>
            <div id="collection-drawer-loader" class="hidden w-4 h-4 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>

<script>
    function previewDrawerBanner(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('drawer-banner-preview').src = e.target.result;
                document.getElementById('drawer-banner-preview').classList.remove('hidden');
                document.getElementById('drawer-banner-placeholder').classList.add('hidden');
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
