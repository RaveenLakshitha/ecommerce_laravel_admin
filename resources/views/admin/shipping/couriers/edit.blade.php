@extends('layouts.app')

@section('title', 'Edit Courier')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2 uppercase">
                <a href="{{ route('shipping.couriers.index') }}" class="hover:text-gray-600 transition-colors">Couriers</a>
                <span>/</span>
                <span class="text-gray-600 dark:text-gray-300">{{ $courier->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Edit Provider</h1>
        </div>
        
        <form action="{{ route('shipping.couriers.destroy', $courier) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this courier?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-medium">Delete Provider</button>
        </form>
    </div>

    <form action="{{ route('shipping.couriers.update', $courier) }}" method="POST" class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6 max-w-3xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provider Name *</label>
                <div class="mt-1">
                    <input type="text" name="name" value="{{ $courier->name }}" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <div class="mt-1">
                    <textarea name="description" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0">{{ $courier->description }}</textarea>
                </div>
            </div>

            <div class="sm:col-span-2">
                <hr class="border-gray-200 dark:border-surface-tonal-a30 my-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">API Configuration (Optional)</h3>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Base API Endpoint URL</label>
                <div class="mt-1">
                    <input type="url" name="base_url" value="{{ $courier->base_url }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Key</label>
                <div class="mt-1">
                    <input type="text" name="api_key" value="{{ $courier->api_key }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Secret</label>
                <div class="mt-1">
                    <input type="password" name="api_secret" value="{{ $courier->api_secret }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:border-gray-600 dark:text-primary-a0">
                </div>
            </div>

            <div class="sm:col-span-2">
                <hr class="border-gray-200 dark:border-surface-tonal-a30 my-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Capabilities</h3>
            </div>

            <div class="space-y-4 sm:col-span-2">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="supports_tracking" name="supports_tracking" type="checkbox" value="1" {{ $courier->supports_tracking ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="supports_tracking" class="font-medium text-gray-700 dark:text-gray-300">Supports Tracking Integration</label>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="supports_label_generation" name="supports_label_generation" type="checkbox" value="1" {{ $courier->supports_label_generation ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="supports_label_generation" class="font-medium text-gray-700 dark:text-gray-300">Supports Shipping Labels</label>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="supports_cod" name="supports_cod" type="checkbox" value="1" {{ $courier->supports_cod ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="supports_cod" class="font-medium text-gray-700 dark:text-gray-300">Supports Cash on Delivery (COD)</label>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="default_for_cod" name="default_for_cod" type="checkbox" value="1" {{ $courier->default_for_cod ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="default_for_cod" class="font-medium text-gray-700 dark:text-gray-300">Set as Primary COD Courier</label>
                    </div>
                </div>

                <div class="flex items-start mt-6">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ $courier->is_active ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">Active Provider</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t border-gray-200 dark:border-surface-tonal-a30 pt-5">
            <div class="flex justify-end">
                <a href="{{ route('shipping.couriers.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-surface-tonal-a20 dark:border-gray-600 dark:text-gray-300">Cancel</a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Provider
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

