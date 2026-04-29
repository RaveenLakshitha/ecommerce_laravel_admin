@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('users.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to User Directory</a>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Provision New User</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Grant access and define permissions for a new
                        administrative account.</p>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column: Identity & Access --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Personnel Identity --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Personnel Identity</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label for="name"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Full Legal
                                            Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            placeholder="e.g. Alexander Sterling"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="email"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Corporate
                                            Email <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                            placeholder="user@enterprise.com"
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Authentication Credentials --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Security Credentials</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label for="password"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Temporary
                                            Password <span class="text-red-500">*</span></label>
                                        <input type="password" name="password" id="password" required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="password_confirmation"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Verify
                                            Password <span class="text-red-500">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Role Architecture --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Inherited Authority</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($roles as $role)
                                        <label
                                            class="relative flex items-center p-4 rounded-2xl border border-gray-100 dark:border-surface-tonal-a30 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 cursor-pointer transition group">
                                            <input type="radio" name="role_id" value="{{ $role->id }}" required {{ old('role_id') == $role->id ? 'checked' : '' }}
                                                class="h-5 w-5 rounded-full border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500">
                                            <div class="ml-4">
                                                <p
                                                    class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                                    {{ $role->name }}</p>
                                                <p class="text-[10px] text-gray-400 font-medium">Full System Access</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('role_id') <p class="text-xs text-red-500 mt-4">{{ $message }}</p> @enderror
                            </div>
                        </div>

                    </div>

                    {{-- Right Column: Status Card --}}
                    <div class="lg:col-span-1 space-y-6">

                        {{-- account health --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Account Disposition</h2>
                            </div>
                            <div class="p-6 space-y-6">

                                <label
                                    class="flex items-center p-3 rounded-xl border border-gray-100 dark:border-surface-tonal-a30 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/50 transition cursor-pointer">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                        class="h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-emerald-500 focus:ring-emerald-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">Account Enabled</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Permit login activities</p>
                                    </div>
                                </label>

                                <div class="pt-4 flex flex-col gap-3">
                                    <button type="submit"
                                        class="w-full h-12 flex items-center justify-center rounded-xl bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-widest hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl shadow-gray-200 dark:shadow-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                                        Provision Account
                                    </button>
                                    <a href="{{ route('users.index') }}"
                                        class="w-full flex items-center justify-center px-6 py-3 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 bg-transparent text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/50 transition-all">
                                        Cancel Protocol
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection