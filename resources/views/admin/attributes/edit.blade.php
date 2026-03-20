@extends('layouts.app')

@section('title', 'Edit Attribute')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-primary-a0">Edit Attribute: {{ $attribute->name }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update attribute details and manage its values.</p>
        </div>
        <a href="{{ route('attributes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            &larr; Back to Attributes
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors with your submission:</h3>
            <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Edit Attribute Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-primary-a0 mb-4">Attribute Details</h2>
                <form action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $attribute->name) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $attribute->slug) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select name="type" id="type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="select" {{ old('type', $attribute->type) == 'select' ? 'selected' : '' }}>Select / Dropdown</option>
                                <option value="color_swatch" {{ old('type', $attribute->type) == 'color_swatch' ? 'selected' : '' }}>Color Swatch</option>
                                <option value="image_swatch" {{ old('type', $attribute->type) == 'image_swatch' ? 'selected' : '' }}>Image Swatch</option>
                                <option value="radio" {{ old('type', $attribute->type) == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $attribute->sort_order) }}" required min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Attribute
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attribute Values Management -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- List Values -->
            <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-primary-a0">Attribute Values</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-surface-tonal-a10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slug</th>
                                @if($attribute->type === 'color_swatch')
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Color</th>
                                @endif
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Sort Order</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-surface-tonal-a20 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attribute->values as $value)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-primary-a0">
                                        {{ $value->value }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $value->slug }}
                                    </td>
                                    @if($attribute->type === 'color_swatch')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center gap-2">
                                                <span class="w-6 h-6 rounded border shadow-sm" style="background-color: {{ $value->color_hex ?? '#000000' }}"></span>
                                                <span>{{ $value->color_hex }}</span>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ $value->sort_order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('attributes.values.destroy', [$attribute->id, $value->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this value?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-md transition-colors">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $attribute->type === 'color_swatch' ? 5 : 4 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No values added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add New Value Form -->
            <div class="bg-gray-50 dark:bg-surface-tonal-a20/50 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
                <h3 class="text-md font-medium text-gray-900 dark:text-primary-a0 mb-4">Add New Value</h3>
                <form action="{{ route('attributes.values.store', $attribute->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 {{ $attribute->type === 'color_swatch' ? 'md:grid-cols-4' : 'md:grid-cols-3' }} gap-4 items-end">
                        
                        <div>
                            <label for="val_value" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Value (e.g. Small, Red)</label>
                            <input type="text" name="value" id="val_value" required placeholder="Value" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="val_slug" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Slug (e.g. small, red)</label>
                            <input type="text" name="slug" id="val_slug" required placeholder="slug"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        @if($attribute->type === 'color_swatch')
                            <div>
                                <label for="val_color" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Color Hex</label>
                                <div class="mt-1 flex items-center">
                                    <input type="color" id="color_picker" class="h-9 w-9 rounded-md border-gray-300 cursor-pointer p-0 border-0" oninput="document.getElementById('val_color').value = this.value">
                                    <input type="text" name="color_hex" id="val_color" placeholder="#000000" class="ml-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="val_sort" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                            <input type="number" name="sort_order" id="val_sort" value="0" min="0" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 dark:text-primary-a0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        
                        <div class="sm:col-span-2 md:col-span-1">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Add Value
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const valueInput = document.getElementById('val_value');
        const slugInput = document.getElementById('val_slug');

        valueInput.addEventListener('input', function() {
            let slug = this.value.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        });
    });
</script>
@endsection

