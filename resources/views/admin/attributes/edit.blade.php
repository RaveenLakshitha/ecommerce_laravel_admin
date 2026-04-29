@extends('layouts.app')

@section('title', __('file.edit_attribute'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('attributes.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_attributes') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.edit_attribute') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.updating') }}: <span
                            class="font-bold text-gray-900 dark:text-white">{{ $attribute->name }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-attribute-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_changes') }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left Column: Core Definition --}}
                <div class="lg:col-span-1 space-y-4">
                    <form id="edit-attribute-form" action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.core_parameters') }}
                                </h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.attribute_name') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $attribute->name) }}"
                                        required
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                    @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div>
                                    <label for="slug"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.slug') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug', $attribute->slug) }}"
                                        required
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 font-mono">
                                    @error('slug') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div>
                                    <label for="type"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.display_type') }}
                                        <span class="text-red-500">*</span></label>
                                    <select name="type" id="type" required
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 cursor-pointer">
                                        <option value="select" {{ old('type', $attribute->type) == 'select' ? 'selected' : '' }}>{{ __('file.dropdown_selector') }}</option>
                                        <option value="color_swatch" {{ old('type', $attribute->type) == 'color_swatch' ? 'selected' : '' }}>{{ __('file.color_swatch') }}</option>
                                        <option value="image_swatch" {{ old('type', $attribute->type) == 'image_swatch' ? 'selected' : '' }}>{{ __('file.image_swatch') }}</option>
                                        <option value="radio" {{ old('type', $attribute->type) == 'radio' ? 'selected' : '' }}>{{ __('file.radio_buttons') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="sort_order"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.sort_order') }}</label>
                                    <input type="number" name="sort_order" id="sort_order"
                                        value="{{ old('sort_order', $attribute->sort_order) }}" required min="0"
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                                </div>

                                <div class="pt-2">
                                    <button type="submit"
                                        class="w-full py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.save_changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Right Column: Attribute Values --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Existing Values Table --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-5 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.attribute_values') }}
                            </h2>
                             <span
                                class="text-xs font-medium text-indigo-500">{{ $attribute->values->count() }}
                                {{ __('file.values_defined') }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-gray-100/50 dark:bg-surface-tonal-a10/50 border-b border-gray-100 dark:border-surface-tonal-a30">
                                        <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('file.value') }}</th>
                                        <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('file.slug') }}</th>
                                        @if($attribute->type === 'color_swatch')
                                            <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                                                {{ __('file.color') }}</th>
                                        @endif
                                        <th
                                            class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">
                                            {{ __('file.order') }}</th>
                                        <th
                                            class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400 text-right">
                                            {{ __('file.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                    @forelse($attribute->values as $value)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                                            <td class="px-6 py-4">
                                                <span
                                                    class="text-sm font-bold text-gray-700 dark:text-white">{{ $value->value }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="text-[10px] font-bold font-mono text-gray-400 dark:text-gray-500">{{ $value->slug }}</span>
                                            </td>
                                            @if($attribute->type === 'color_swatch')
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-5 h-5 rounded border border-gray-200 dark:border-surface-tonal-a30 shadow-sm"
                                                            style="background-color: {{ $value->color_hex ?? '#000000' }}"></div>
                                                        <span
                                                            class="text-[10px] font-bold font-mono text-gray-400 uppercase tracking-widest">{{ $value->color_hex }}</span>
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $value->sort_order }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form
                                                    action="{{ route('attributes.values.destroy', [$attribute->id, $value->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ __('file.confirm_delete_value') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all opacity-0 group-hover:opacity-100">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-16 text-center">
                                                <div
                                                    class="mx-auto w-10 h-10 bg-gray-50 dark:bg-surface-tonal-a30 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                </div>
                                                <p
                                                    class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                                    {{ __('file.no_values_defined') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Add New Value --}}
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.add_new_value') }}</h2>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('attributes.values.store', $attribute->id) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    <div class="md:col-span-1">
                                        <label for="val_value"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.value') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="value" id="val_value" required
                                            placeholder="{{ __('file.eg_attribute_values') }}..."
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="val_slug"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.slug') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="slug" id="val_slug" required placeholder="{{ __('file.auto_generated') }}"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 font-mono">
                                    </div>

                                    @if($attribute->type === 'color_swatch')
                                        <div class="md:col-span-1">
                                            <label for="val_color"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.color_hex') }}</label>
                                            <div class="flex gap-2">
                                                <input type="color" id="color_picker"
                                                    class="w-10 h-[34px] rounded border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 p-0.5 cursor-pointer shrink-0"
                                                    oninput="document.getElementById('val_color').value = this.value">
                                                <input type="text" name="color_hex" id="val_color" placeholder="#000000"
                                                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 font-mono">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="md:col-span-1">
                                        <label for="val_sort"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.sort_order') }}</label>
                                        <input type="number" name="sort_order" id="val_sort" value="0" min="0" required
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                    </div>

                                    <div class="md:col-span-4">
                                        <button type="submit"
                                            class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-md active:scale-[0.98]">
                                            {{ __('file.add_value') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('name')?.addEventListener('input', function () {
                document.getElementById('slug').value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });

            document.getElementById('val_value')?.addEventListener('input', function () {
                document.getElementById('val_slug').value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });
        </script>
    @endpush
@endsection