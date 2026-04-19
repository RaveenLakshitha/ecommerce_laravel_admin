@extends('layouts.app')

@section('title', 'Edit Attribute')

@section('content')
<div class="admin-page">
    <div class="admin-page-inner">

        {{-- Header --}}
        <div class="admin-page-header">
            <div>
                <a href="{{ route('attributes.index') }}" class="admin-breadcrumb">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Attributes
                </a>
                <h1 class="admin-page-title">Edit Attribute</h1>
                <p class="admin-page-subtitle">Modify properties and values for: <span class="font-semibold text-gray-900 dark:text-white">{{ $attribute->name }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Left Column: Core Definition --}}
            <div class="lg:col-span-4 space-y-6">
                <form action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="admin-card sticky top-24">
                        <div class="admin-card-header">
                            <h2>Core Parameters</h2>
                        </div>
                        <div class="admin-card-body space-y-5">
                            <div>
                                <label for="name" class="fi-label fi-label-required">Attribute Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $attribute->name) }}" required class="fi">
                                @error('name') <p class="fi-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="slug" class="fi-label fi-label-required">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $attribute->slug) }}" required class="fi" style="font-family: 'DM Mono', monospace;">
                                @error('slug') <p class="fi-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="type" class="fi-label fi-label-required">Display Type</label>
                                <select name="type" id="type" required class="fi">
                                    <option value="select" {{ old('type', $attribute->type) == 'select' ? 'selected' : '' }}>Dropdown Selector</option>
                                    <option value="color_swatch" {{ old('type', $attribute->type) == 'color_swatch' ? 'selected' : '' }}>Color Swatch</option>
                                    <option value="image_swatch" {{ old('type', $attribute->type) == 'image_swatch' ? 'selected' : '' }}>Image Swatch</option>
                                    <option value="radio" {{ old('type', $attribute->type) == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                </select>
                            </div>

                            <div>
                                <label for="sort_order" class="fi-label">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $attribute->sort_order) }}" required min="0" class="fi">
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="admin-btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Right Column: Attribute Values --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Existing Values Table --}}
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Attribute Values</h2>
                        <p>{{ $attribute->values->count() }} value(s) defined</p>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-surface-tonal-a30">
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug</th>
                                    @if($attribute->type === 'color_swatch')
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Color</th>
                                    @endif
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Order</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                @forelse($attribute->values as $value)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-surface-tonal-a30/10 transition-colors group">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $value->value }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-mono text-gray-400 dark:text-gray-500">{{ $value->slug }}</span>
                                        </td>
                                        @if($attribute->type === 'color_swatch')
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-lg border border-gray-200 dark:border-surface-tonal-a30 shadow-sm" style="background-color: {{ $value->color_hex ?? '#000000' }}"></div>
                                                    <span class="text-xs font-mono text-gray-400">{{ $value->color_hex }}</span>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $value->sort_order }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('attributes.values.destroy', [$attribute->id, $value->id]) }}" method="POST" onsubmit="return confirm('Delete this value?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all opacity-0 group-hover:opacity-100">
                                                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="mx-auto w-12 h-12 bg-gray-50 dark:bg-surface-tonal-a30 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No values defined yet.</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Add values below to make this attribute usable.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Add New Value --}}
                <div class="admin-card">
                    <div class="admin-card-header flex items-center justify-between">
                        <h2>Add New Value</h2>
                    </div>
                    <div class="admin-card-body">
                        <form action="{{ route('attributes.values.store', $attribute->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <label for="val_value" class="fi-label fi-label-required">Value</label>
                                    <input type="text" name="value" id="val_value" required placeholder="Red, XL, Leather..." class="fi">
                                </div>
                                <div>
                                    <label for="val_slug" class="fi-label fi-label-required">Slug</label>
                                    <input type="text" name="slug" id="val_slug" required placeholder="auto-generated" class="fi" style="font-family: 'DM Mono', monospace;">
                                </div>

                                @if($attribute->type === 'color_swatch')
                                    <div>
                                        <label for="val_color" class="fi-label">Color Hex</label>
                                        <div class="flex gap-2">
                                            <input type="color" id="color_picker" class="w-10 h-[38px] rounded-lg border border-gray-200 dark:border-surface-tonal-a30 p-0 cursor-pointer shrink-0"
                                                   oninput="document.getElementById('val_color').value = this.value">
                                            <input type="text" name="color_hex" id="val_color" placeholder="#000000" class="fi">
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label for="val_sort" class="fi-label">Sort Order</label>
                                    <input type="number" name="sort_order" id="val_sort" value="0" min="0" required class="fi">
                                </div>

                                <div class="md:col-span-4 lg:col-span-1">
                                    <button type="submit" class="admin-btn-add !w-full !py-2.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add Value
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
    document.getElementById('name')?.addEventListener('input', function() {
        document.getElementById('slug').value = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });

    document.getElementById('val_value')?.addEventListener('input', function() {
        document.getElementById('val_slug').value = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });
</script>
@endpush
@endsection
