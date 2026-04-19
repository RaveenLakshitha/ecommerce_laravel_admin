@extends('layouts.app')

@section('title', 'Add Attribute')

@section('content')
<div class="admin-page">
    <div class="admin-page-inner" style="max-width: 900px;">

        {{-- Header --}}
        <div class="admin-page-header">
            <div>
                <a href="{{ route('attributes.index') }}" class="admin-breadcrumb">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Attributes
                </a>
                <h1 class="admin-page-title">Add New Attribute</h1>
                <p class="admin-page-subtitle">Create a new product property such as Size, Color, or Material.</p>
            </div>
        </div>

        <form action="{{ route('attributes.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Column --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h2>Attribute Details</h2>
                        </div>
                        <div class="admin-card-body space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="fi-label fi-label-required">Attribute Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Size, Color" class="fi">
                                    @error('name') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="slug" class="fi-label fi-label-required">Slug</label>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required placeholder="auto-generated" class="fi" style="font-family: 'DM Mono', monospace;">
                                    @error('slug') <p class="fi-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-blue-50/50 dark:bg-surface-tonal-a30/30 border border-blue-100 dark:border-surface-tonal-a30">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                                        Attributes are master templates for property groups. Once created, you can define individual values (e.g., XL, Red, Cotton) in the edit page.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="admin-card sticky top-24">
                        <div class="admin-card-header">
                            <h2>Configuration</h2>
                        </div>
                        <div class="admin-card-body space-y-5">
                            <div>
                                <label for="type" class="fi-label fi-label-required">Display Type</label>
                                <select name="type" id="type" required class="fi">
                                    <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>Dropdown Selector</option>
                                    <option value="color_swatch" {{ old('type') == 'color_swatch' ? 'selected' : '' }}>Color Swatch</option>
                                    <option value="image_swatch" {{ old('type') == 'image_swatch' ? 'selected' : '' }}>Image Swatch</option>
                                    <option value="radio" {{ old('type') == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                </select>
                                @error('type') <p class="fi-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="sort_order" class="fi-label">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" required min="0" class="fi">
                                @error('sort_order') <p class="fi-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-2 flex flex-col gap-3">
                                <button type="submit" class="admin-btn-primary">Create Attribute</button>
                                <a href="{{ route('attributes.index') }}" class="admin-btn-secondary">Cancel</a>
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
    document.getElementById('name').addEventListener('input', function() {
        document.getElementById('slug').value = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });
</script>
@endpush
@endsection
