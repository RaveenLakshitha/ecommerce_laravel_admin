<form id="attribute-drawer-form" action="{{ $attribute ? route('attributes.update', $attribute->id) : route('attributes.store') }}" method="POST" class="space-y-6">
    @csrf
    @if($attribute)
        @method('PUT')
    @endif

    <div class="space-y-4">
        {{-- Attribute Name --}}
        <div>
            <label for="drawer_attr_name" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                {{ __('file.attribute_name') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="drawer_attr_name" value="{{ $attribute ? $attribute->name : old('name') }}" required
                placeholder="{{ __('file.eg_attributes') }}"
                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md">
        </div>

        {{-- Slug --}}
        <div>
            <label for="drawer_attr_slug" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                {{ __('file.slug') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" name="slug" id="drawer_attr_slug" value="{{ $attribute ? $attribute->slug : old('slug') }}" required
                placeholder="{{ __('file.auto_generated') }}"
                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 font-mono">
        </div>

        {{-- Display Type --}}
        <div>
            <label for="drawer_attr_type" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                {{ __('file.display_type') }} <span class="text-red-500">*</span>
            </label>
            <select name="type" id="drawer_attr_type" required
                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md cursor-pointer">
                <option value="select" {{ ($attribute && $attribute->type == 'select') || old('type') == 'select' ? 'selected' : '' }}>{{ __('file.dropdown_selector') }}</option>
                <option value="color_swatch" {{ ($attribute && $attribute->type == 'color_swatch') || old('type') == 'color_swatch' ? 'selected' : '' }}>{{ __('file.color_swatch') }}</option>
                <option value="image_swatch" {{ ($attribute && $attribute->type == 'image_swatch') || old('type') == 'image_swatch' ? 'selected' : '' }}>{{ __('file.image_swatch') }}</option>
                <option value="radio" {{ ($attribute && $attribute->type == 'radio') || old('type') == 'radio' ? 'selected' : '' }}>{{ __('file.radio_buttons') }}</option>
            </select>
        </div>

        {{-- Sort Order --}}
        <div>
            <label for="drawer_attr_sort" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">
                {{ __('file.sort_order') }}
            </label>
            <input type="number" name="sort_order" id="drawer_attr_sort" value="{{ $attribute ? $attribute->sort_order : old('sort_order', 0) }}" required min="0"
                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
        </div>

        <div class="p-4 rounded-xl bg-blue-50/50 dark:bg-indigo-500/5 border border-blue-100 dark:border-indigo-500/10">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-[10px] text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                    {{ __('file.attribute_helper_text') }}
                </p>
            </div>
        </div>

        @if($attribute)
            <div class="pt-4 border-t border-gray-100 dark:border-white/5">
                <a href="{{ route('attributes.edit', $attribute->id) }}" 
                   class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:underline text-xs font-bold uppercase tracking-widest">
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                   </svg>
                   {{ __('file.manage_values') ?? 'Manage Values' }}
                </a>
            </div>
        @endif
    </div>

    <div class="flex gap-3 pt-6 border-t border-gray-100 dark:border-white/5">
        <button type="button" onclick="closeAttributeDrawer()"
            class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-95">
            {{ __('file.cancel') }}
        </button>
        <button type="submit"
            class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
            <span id="attribute-drawer-save-text">{{ $attribute ? __('file.save_changes') : __('file.save_attribute') }}</span>
            <div id="attribute-drawer-loader" class="hidden w-4 h-4 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </button>
    </div>
</form>

<script>
    document.getElementById('drawer_attr_name').addEventListener('input', function () {
        document.getElementById('drawer_attr_slug').value = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });
</script>
