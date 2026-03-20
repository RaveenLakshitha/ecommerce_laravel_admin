@extends('layouts.app')

@section('title', 'Payment Gateway Settings')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Payment Gateways</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure your store's payment methods and API credentials.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <ul class="list-disc list-inside text-sm text-red-700 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        @foreach($settings as $gw => $setting)
        <div class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border {{ $setting->is_active ? 'border-indigo-500 dark:border-indigo-500' : 'border-gray-200 dark:border-surface-tonal-a30' }} overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a10/50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 flex items-center gap-2">
                    {{ $setting->display_name ?? ucfirst($gw) }}
                    @if($setting->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">Active</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">Inactive</span>
                    @endif
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('settings.payment-gateways.update', $gw) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="flex items-center gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active_{{ $gw }}" value="1" {{ $setting->is_active ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-surface-tonal-a30 dark:border-gray-600">
                            <label for="is_active_{{ $gw }}" class="ml-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Enable {{ $setting->display_name ?? ucfirst($gw) }}</label>
                        </div>
                    </div>

                    @if(!in_array($gw, ['cod', 'bank']))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Environment</label>
                                <select name="environment" class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:text-primary-a0 sm:text-sm">
                                    <option value="sandbox" {{ $setting->environment === 'sandbox' ? 'selected' : '' }}>Sandbox / Test</option>
                                    <option value="live" {{ $setting->environment === 'live' ? 'selected' : '' }}>Live / Production</option>
                                </select>
                            </div>
                            
                            @if($gw === 'payhere')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Merchant ID</label>
                                    <input type="text" name="merchant_id" value="{{ old('merchant_id', $setting->merchant_id) }}" class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:text-primary-a0 sm:text-sm">
                                </div>
                            @endif

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Public Key / Client ID</label>
                                <input type="text" name="public_key" value="{{ old('public_key', $setting->public_key) }}" class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:text-primary-a0 sm:text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secret Key / Client Secret</label>
                                <input type="password" name="secret_key" value="{{ old('secret_key', $setting->secret_key) }}" class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:text-primary-a0 sm:text-sm">
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instructions / Description</label>
                            <textarea name="description" rows="3" class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-surface-tonal-a30 dark:text-primary-a0 sm:text-sm">{{ old('description', $setting->description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Displayed to customers during checkout.</p>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-surface-tonal-a30">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition shadow-sm">
                            Save {{ ucfirst($gw) }} Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

