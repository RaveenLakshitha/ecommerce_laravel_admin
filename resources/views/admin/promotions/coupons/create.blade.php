@extends('layouts.app')

@section('title', 'Create Coupon')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Create Coupon</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new discount code for your customers.</p>
        </div>
        <a href="{{ route('coupons.index') }}" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            &larr; Back to Coupons
        </a>
    </div>

    <form action="{{ route('coupons.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">General Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coupon Code *</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0 uppercase" placeholder="e.g. SUMMER20">
                    @error('code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="mt-4 flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-surface-tonal-a10">
                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-primary-a0">Active (can be used immediately)</label>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Discount Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Type *</label>
                    <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                    </select>
                    @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Value *</label>
                    <input type="number" step="0.01" min="0" name="value" id="value" value="{{ old('value') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('value') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="max_discount_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Discount Amount (Optional cap for %)</label>
                    <input type="number" step="0.01" min="0" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('max_discount_amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Requirements & Limits</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="min_order_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Order Amount</label>
                    <input type="number" step="0.01" min="0" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('min_order_amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="applies_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Applies To *</label>
                    <select name="applies_to" id="applies_to" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                        <option value="all" {{ old('applies_to') == 'all' ? 'selected' : '' }}>Entire Order</option>
                        <option value="specific_products" {{ old('applies_to') == 'specific_products' ? 'selected' : '' }}>Specific Products</option>
                        <option value="specific_categories" {{ old('applies_to') == 'specific_categories' ? 'selected' : '' }}>Specific Categories</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Note: Attaching specific products/categories can be done after creation.</p>
                    @error('applies_to') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="usage_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0" placeholder="Leave blank for unlimited">
                    @error('usage_limit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="usage_per_user" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Limit Per User</label>
                    <input type="number" min="1" name="usage_per_user" id="usage_per_user" value="{{ old('usage_per_user') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0" placeholder="Leave blank for unlimited">
                    @error('usage_per_user') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Active Dates</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Starts At</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('starts_at') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expires At</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('expires_at') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('coupons.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-surface-tonal-a20 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Save Coupon
            </button>
        </div>
    </form>
</div>
@endsection

