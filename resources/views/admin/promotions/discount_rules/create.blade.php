@extends('layouts.app')

@section('title', 'Create Discount Rule')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Create Discount Rule</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Set up automatic discounts, BOGO offers, or flash sales.</p>
        </div>
        <a href="{{ route('discount-rules.index') }}" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            &larr; Back to Rules
        </a>
    </div>

    <form action="{{ route('discount-rules.store') }}" method="POST" class="space-y-6" x-data="{ ruleType: '{{ old('type', 'percentage') }}' }">
        @csrf
        
        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">General Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rule Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority (Higher runs first) *</label>
                    <input type="number" min="0" name="priority" id="priority" value="{{ old('priority', 0) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    <p class="mt-1 text-xs text-gray-500">If multiple rules match, highest priority applies first.</p>
                    @error('priority') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="mt-4 flex flex-col space-y-3">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-surface-tonal-a10">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-primary-a0">Active (can be used immediately)</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_flash_sale" id="is_flash_sale" value="1" {{ old('is_flash_sale') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500 dark:border-gray-600 dark:bg-surface-tonal-a10">
                    <label for="is_flash_sale" class="ml-2 block text-sm text-purple-600 dark:text-purple-400 font-medium">Highlight as Flash Sale (Displays badge on frontend)</label>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Promotion Type & Value</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type *</label>
                    <select name="type" id="type" x-model="ruleType" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                        <option value="percentage">Percentage (%) Off</option>
                        <option value="fixed">Fixed Amount ($) Off</option>
                        <option value="bogo">BOGO (Buy One Get One)</option>
                        <option value="buy_x_get_y">Buy X Get Y (Custom)</option>
                    </select>
                    @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div x-show="['percentage', 'fixed'].includes(ruleType)">
                    <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Value</label>
                    <input type="number" step="0.01" min="0" name="value" id="value" value="{{ old('value') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('value') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div x-show="['bogo', 'buy_x_get_y'].includes(ruleType)">
                    <label for="buy_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buy Quantity (X)</label>
                    <input type="number" min="1" name="buy_quantity" id="buy_quantity" value="{{ old('buy_quantity', 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('buy_quantity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div x-show="['bogo', 'buy_x_get_y'].includes(ruleType)">
                    <label for="get_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Get Quantity (Y)</label>
                    <input type="number" min="1" name="get_quantity" id="get_quantity" value="{{ old('get_quantity', 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    <p class="mt-1 text-xs text-gray-500">How many items are free/discounted.</p>
                    @error('get_quantity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-4">Requirements</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="min_order_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Order Amount</label>
                    <input type="number" step="0.01" min="0" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                    @error('min_order_amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="applies_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Applies To *</label>
                    <select name="applies_to" id="applies_to" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-surface-tonal-a10 dark:border-gray-600 dark:text-primary-a0">
                        <option value="all" {{ old('applies_to') == 'all' ? 'selected' : '' }}>Entire Store / All Products</option>
                        <option value="products" {{ old('applies_to') == 'products' ? 'selected' : '' }}>Specific Products</option>
                        <option value="categories" {{ old('applies_to') == 'categories' ? 'selected' : '' }}>Specific Categories</option>
                        <option value="collections" {{ old('applies_to') == 'collections' ? 'selected' : '' }}>Specific Collections</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Note: Attaching specific products/categories can be done after creation.</p>
                    @error('applies_to') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
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
            <a href="{{ route('discount-rules.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-surface-tonal-a20 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Save Rule
            </button>
        </div>
    </form>
</div>
@endsection

