@extends('layouts.app')

@section('title', __('file.add_new_attribute'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <div class="mb-4 mt-10">
                <a href="{{ route('attributes.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_attributes') }}
                </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.add_new_attribute') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.create_new_attribute_entry') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-attribute-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_attribute') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('attributes.store') }}" method="POST" id="create-attribute-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- LEFT COLUMN - Wider --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Attribute Details --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.attribute_details') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.attribute_name') }}</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            placeholder="{{ __('file.eg_attributes') }}"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                        @error('name')
                                            <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.slug') }}</label>
                                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                            placeholder="{{ __('file.auto_generated') }}"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 font-mono">
                                        @error('slug')
                                            <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div
                                    class="p-4 rounded-xl bg-blue-50/50 dark:bg-indigo-500/5 border border-blue-100 dark:border-indigo-500/10">
                                    <div class="flex gap-3">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                                            {{ __('file.attribute_helper_text') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="lg:col-span-1 space-y-4">
                        {{-- Configuration --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.configuration') }}
                                </h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.display_type') }}</label>
                                    <select name="type" id="type" required
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 cursor-pointer">
                                        <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>{{ __('file.dropdown_selector') }}</option>
                                        <option value="color_swatch" {{ old('type') == 'color_swatch' ? 'selected' : '' }}>
                                            {{ __('file.color_swatch') }}</option>
                                        <option value="image_swatch" {{ old('type') == 'image_swatch' ? 'selected' : '' }}>
                                            {{ __('file.image_swatch') }}</option>
                                        <option value="radio" {{ old('type') == 'radio' ? 'selected' : '' }}>{{ __('file.radio_buttons') }}
                                        </option>
                                    </select>
                                    @error('type')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.sort_order') }}</label>
                                    <input type="number" name="sort_order" id="sort_order"
                                        value="{{ old('sort_order', 0) }}" required min="0"
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                    @error('sort_order')
                                        <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('name').addEventListener('input', function () {
                document.getElementById('slug').value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });
        </script>
    @endpush
@endsection