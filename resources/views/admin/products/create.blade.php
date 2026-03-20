@extends('layouts.app')

@section('title', __('file.add_product') ?? 'Add Product')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-950 px-4 pb-10 pt-20 sm:px-6 lg:px-8">

    {{-- Page Header --}}
    <div class="max-w-5xl mx-auto mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-1">
                Product Management
            </p>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-primary-a0 tracking-tight">
                {{ __('file.add_product') ?? 'Add Product' }}
            </h1>
        </div>
        <a href="{{ route('products.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a10 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('file.back_to_list') ?? 'Back to list' }}
        </a>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="max-w-5xl mx-auto mb-6">
        <div class="flex gap-3 rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/50 p-4">
            <div class="flex-shrink-0 mt-0.5">
                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/60 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 3h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-red-800 dark:text-red-300 mb-1">Please fix the following errors</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-700 dark:text-red-400">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Form Card --}}
    <div class="max-w-5xl mx-auto">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">

                {{-- Section: Basic Info --}}
                <div class="rounded-2xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Basic Information</h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Name --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Wireless Headphones"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 text-gray-900 dark:text-primary-a0 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        {{-- Slug --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="e.g. wireless-headphones"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 text-gray-900 dark:text-primary-a0 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                        </div>

                        {{-- Base Price --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Base Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 dark:text-gray-500 text-sm font-medium pointer-events-none">$</span>
                                <input type="number" step="0.01" name="base_price" value="{{ old('base_price', '0.00') }}" required
                                    class="w-full pl-8 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 text-gray-900 dark:text-primary-a0 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>

                        {{-- Brand --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Brand</label>
                            <div class="relative">
                                <select name="brand_id"
                                    class="w-full appearance-none px-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 text-gray-900 dark:text-primary-a0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    <option value="">No Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section: Categories --}}
                <div class="rounded-2xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Categories</h2>
                    </div>

                    <div class="p-6">
                        <select name="categories[]" multiple
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 text-gray-900 dark:text-primary-a0 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition" size="5">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Hold <kbd class="px-1.5 py-0.5 text-xs font-mono bg-gray-100 dark:bg-surface-tonal-a30 rounded border border-gray-300 dark:border-gray-600">Ctrl</kbd> to select multiple categories
                        </p>
                    </div>
                </div>

                {{-- Section: Descriptions --}}
                <div class="rounded-2xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h12"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Content</h2>
                    </div>

                    <div class="p-6 flex flex-col gap-5">

                        {{-- Short Description --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Short Description</label>
                            <textarea name="short_description" rows="3" placeholder="A brief, compelling summary shown in listings…"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 text-gray-900 dark:text-primary-a0 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none">{{ old('short_description') }}</textarea>
                        </div>

                        {{-- Full Description --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Description</label>
                            <textarea name="description" rows="7" placeholder="Detailed product description, features, specifications…"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20 text-gray-900 dark:text-primary-a0 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-y">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Section: Images --}}
                <div class="rounded-2xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Product Images</h2>
                    </div>

                    <div class="p-6">
                        <label class="flex flex-col items-center justify-center w-full min-h-36 rounded-xl border-2 border-dashed border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a20/50 cursor-pointer hover:border-amber-400 dark:hover:border-amber-600 hover:bg-amber-50/30 dark:hover:bg-amber-950/20 transition-all duration-200 group">
                            <div class="flex flex-col items-center gap-2 pointer-events-none py-6">
                                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <span class="text-amber-600 dark:text-amber-400">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">PNG, JPG, GIF, WEBP — max 2MB each</p>
                                </div>
                            </div>
                            <input type="file" name="images[]" multiple accept="image/*" class="sr-only">
                        </label>
                    </div>
                </div>

                {{-- Section: Visibility --}}
                <div class="rounded-2xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a20 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950 flex items-center justify-center">
                            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">Visibility & Status</h2>
                    </div>

                    <div class="p-6 flex flex-col sm:flex-row gap-6">

                        {{-- Is Visible --}}
                        <label class="flex items-start gap-3 cursor-pointer group flex-1">
                            <div class="relative mt-0.5">
                                <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}
                                    class="peer sr-only">
                                <div class="w-10 h-6 rounded-full bg-gray-200 dark:bg-surface-tonal-a30 peer-checked:bg-sky-500 dark:peer-checked:bg-sky-600 transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow-sm peer-checked:translate-x-4 transition-transform"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Visible to customers</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Product will appear in your storefront</p>
                            </div>
                        </label>

                        {{-- Is Featured --}}
                        <label class="flex items-start gap-3 cursor-pointer group flex-1">
                            <div class="relative mt-0.5">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                    class="peer sr-only">
                                <div class="w-10 h-6 rounded-full bg-gray-200 dark:bg-surface-tonal-a30 peer-checked:bg-indigo-500 dark:peer-checked:bg-indigo-600 transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow-sm peer-checked:translate-x-4 transition-transform"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Featured product</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Highlight on homepage and featured sections</p>
                            </div>
                        </label>

                    </div>
                </div>

            </div>

            {{-- Action Footer --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-2xl border border-gray-200 dark:border-surface-tonal-a20 bg-white dark:bg-surface-tonal-a10 px-6 py-4 shadow-sm">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Fields marked with <span class="text-red-500 font-semibold">*</span> are required
                </p>
                <div class="flex gap-3 w-full sm:w-auto">
                    <a href="{{ route('products.index') }}"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-surface-tonal-a30 text-gray-600 dark:text-gray-300 bg-white dark:bg-surface-tonal-a20 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white shadow-sm hover:shadow-indigo-200 dark:hover:shadow-indigo-900 shadow-md transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Product
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

@endsection
