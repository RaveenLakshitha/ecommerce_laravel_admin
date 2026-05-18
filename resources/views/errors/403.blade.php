<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gray-100">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            <div>
                <h1 class="text-9xl font-bold text-red-600">403</h1>
                <p class="mt-4 text-3xl font-semibold text-gray-800">{{ __('file.error_403_title') }}</p>
                <p class="mt-2 text-lg text-gray-600">
                    {{ __('file.error_403_message') }}
                </p>
            </div>
            <div>
                @php
                    $isAdmin = request()->is('admin*') || request()->getHost() === 'admin.karbnzol.com' || request()->routeIs('admin.*') || request()->segment(1) === 'admin';
                @endphp
                <a href="{{ $isAdmin ? (auth('admin')->check() ? route('admin.dashboard') : route('admin.login')) : (auth('web')->check() ? route('account.dashboard') : route('login')) }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    {{ $isAdmin ? (auth('admin')->check() ? (__('file.go_to_dashboard') ?? 'Go to Dashboard') : (__('file.go_to_login') ?? 'Go to Login')) : (auth('web')->check() ? (__('file.go_to_dashboard') ?? 'Go to Dashboard') : (__('file.go_to_login') ?? 'Go to Login')) }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>

