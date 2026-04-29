@extends('layouts.app')

@section('title', __('file.edit_user') . ': ' . $user->name)

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('users.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_users') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('file.edit_user') }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('file.updating_administrative_record_for') }} <span
                            class="text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-tighter">{{ $user->name }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-user-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.update_user') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST" id="edit-user-form">
                @csrf
                @method('PUT')

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
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.full_legal_name') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                            required
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                        @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.corporate_email') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                            required
                                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                        @error('email') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.contact_number') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                        required
                                        class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                                    @error('phone') <p class="text-xs text-red-500 mt-1 font-medium px-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Authentication Credentials --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ __('file.credential_rotation') }}</h2>
                            </div>
                            <div class="p-4 space-y-4">
                                <p class="text-xs text-gray-400 font-medium italic">
                                    {{ __('file.leave_password_blank_if_no_rotation') }}</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="relative">
                                        <label for="password"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.new_password') }}</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="password" minlength="8"
                                                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 pr-10">
                                            <button type="button" onclick="togglePassword('password', 'eyePassword')"
                                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-500 transition-colors">
                                                <svg id="eyePassword" class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password') <p class="text-xs text-red-500 mt-1 font-medium px-1">
                                        {{ $message }}</p> @enderror
                                    </div>
                                    <div class="relative">
                                        <label for="password_confirmation"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('file.confirm_rotation') }}</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 pr-10">
                                            <button type="button"
                                                onclick="togglePassword('password_confirmation', 'eyeConfirm')"
                                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-500 transition-colors">
                                                <svg id="eyeConfirm" class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
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
                                            class="relative flex items-center p-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 cursor-pointer transition group">
                                            <input type="radio" name="role" value="{{ $role->name }}" required {{ ($currentRole ?? old('role')) == $role->name ? 'checked' : '' }}
                                                class="h-4 w-4 rounded-full border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500">
                                            <div class="ml-3">
                                                <p
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $role->name }}</p>
                                                <p
                                                    class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mt-0.5">
                                                    {{ __('file.active_privileges') }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('role') <p class="text-xs text-red-500 mt-2 font-medium px-1">{{ $message }}</p> @enderror
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
                                    class="flex items-start py-2.5 px-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition cursor-pointer group">
                                    <div class="mt-0.5">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-emerald-500 focus:ring-emerald-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-semibold text-gray-900 dark:text-white leading-none">
                                            {{ __('file.account_enabled') }}</h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1">
                                            {{ __('file.permit_login_activities') }}</p>
                                    </div>
                                </label>

                                <div class="pt-2 flex flex-col gap-3">
                                    <button type="submit" form="edit-user-form"
                                        class="px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                                        {{ __('file.update_user') }}
                                    </button>
                                    <a href="{{ route('users.index') }}"
                                        class="px-6 py-3 border border-gray-200 dark:border-surface-tonal-a30 text-gray-500 text-sm font-bold rounded-xl text-center hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
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

    @push('scripts')
        <script>
            function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>`;
                } else {
                    input.type = 'password';
                    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
                }
            }
        </script>
    @endpush
@endsection