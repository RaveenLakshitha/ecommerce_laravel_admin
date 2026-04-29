@extends('layouts.app')

@section('title', __('file.add_new_user'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('users.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_user_directory') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.add_new_user') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.create_user_helper') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-user-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_user') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" id="create-user-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Left Column --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Personnel Identity --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.personnel_identity') }}</h2>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.full_legal_name') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            placeholder="e.g. Alexander Sterling"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                        @error('name') <p class="text-xs text-red-500 mt-1 font-medium px-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.corporate_email') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                            placeholder="user@enterprise.com"
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                        @error('email') <p class="text-xs text-red-500 mt-1 font-medium px-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Security Credentials --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.security_credentials') }}</h2>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="password"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.temporary_password') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="password" name="password" id="password" required
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                        @error('password') <p class="text-xs text-red-500 mt-1 font-medium px-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.verify_password') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            required
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Inherited Authority --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.inherited_authority') }}</h2>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($roles as $role)
                                        <label
                                            class="relative flex items-center p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors group">
                                            <input type="radio" name="role_id" value="{{ $role->id }}" required {{ old('role_id') == $role->id ? 'checked' : '' }}
                                                class="h-4 w-4 rounded-full border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500">
                                            <div class="ml-3">
                                                <p
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $role->name }}</p>
                                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                                                    {{ __('file.assigned_role') }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('role_id') <p class="text-xs text-red-500 mt-4 font-medium px-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="lg:col-span-1 space-y-4">
                        {{-- Account Disposition --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden sticky top-24">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.account_disposition') }}</h2>
                            </div>
                            <div class="p-4 space-y-6">
                                <label
                                    class="flex items-start p-3 rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a10 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-tonal-a20 transition-colors">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                        class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-emerald-500 focus:ring-emerald-500 transition-all">
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ __('file.account_enabled') }}</h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ __('file.permit_login_activities') }}</p>
                                    </div>
                                </label>

                                <div class="space-y-3">
                                    <button type="submit" form="create-user-form"
                                        class="w-full py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.save_user') }}
                                    </button>
                                    <a href="{{ route('users.index') }}"
                                        class="w-full flex items-center justify-center py-2.5 border border-gray-200 dark:border-surface-tonal-a30 text-sm font-medium text-gray-500 dark:text-gray-400 rounded-xl hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                                        {{ __('file.cancel') }}
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