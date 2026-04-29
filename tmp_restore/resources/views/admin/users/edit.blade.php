@extends('layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('users.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to User Directory</a>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Profile Modification
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Updating administrative record for <span
                            class="text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-tighter">{{ $user->name }}</span>
                    </p>
                </div>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

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
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                            required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="email"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Corporate
                                            Email <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                            required
                                            class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="phone"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Contact Number
                                        <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                        required
                                        class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Authentication Credentials --}}
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                                <h2 class="font-semibold text-gray-900 dark:text-white">Credential Rotation</h2>
                            </div>
                            <div class="p-6 space-y-5">
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic">Leave password fields blank if
                                    rotation is not required.</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5 relative">
                                        <label for="password"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">New
                                            Password</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="password" minlength="8"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                            <button type="button" onclick="togglePassword('password', 'eyePassword')"
                                                class="absolute inset-y-0 right-4 flex items-center text-gray-400">
                                                <svg id="eyePassword" class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1.5 relative">
                                        <label for="password_confirmation"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Confirm
                                            Rotation</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                                            <button type="button"
                                                onclick="togglePassword('password_confirmation', 'eyeConfirm')"
                                                class="absolute inset-y-0 right-4 flex items-center text-gray-400">
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
                                            <input type="radio" name="role" value="{{ $role->name }}" required {{ ($currentRole ?? old('role')) == $role->name ? 'checked' : '' }}
                                                class="h-5 w-5 rounded-full border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500">
                                            <div class="ml-4">
                                                <p
                                                    class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                                    {{ $role->name }}</p>
                                                <p
                                                    class="text-[10px] text-gray-400 font-medium italic underline decoration-indigo-500/20 underline-offset-2">
                                                    Active Privileges</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('role') <p class="text-xs text-red-500 mt-4">{{ $message }}</p> @enderror
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
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                        class="h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-emerald-500 focus:ring-emerald-500 transition-all">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">Account Enabled</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Permit login activities</p>
                                    </div>
                                </label>

                                <div class="pt-4 flex flex-col gap-3">
                                    <button type="submit"
                                        class="w-full h-12 flex items-center justify-center rounded-xl bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-widest hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl shadow-gray-200 dark:shadow-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                                        Commit Changes
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
@endsection