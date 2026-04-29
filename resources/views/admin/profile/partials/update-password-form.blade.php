<section class="space-y-6">
    <header>
        <h2 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('file.security_recalibration') }}</h2>
        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('file.rotate_credentials_text') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="current_password" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.current_password') }}</label>
                <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                <x-input-error class="text-[10px] font-bold text-rose-500 mt-1" :messages="$errors->updatePassword->get('current_password')" />
            </div>

            <div class="hidden md:block"></div>

            <div>
                <label for="password" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.new_password') }}</label>
                <input type="password" name="password" id="password" autocomplete="new-password"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                <x-input-error class="text-[10px] font-bold text-rose-500 mt-1" :messages="$errors->updatePassword->get('password')" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.confirm_password') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                <x-input-error class="text-[10px] font-bold text-rose-500 mt-1" :messages="$errors->updatePassword->get('password_confirmation')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-95 group">
                {{ __('file.rotate_credentials') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest italic animate-pulse">
                    {{ __('file.security_layer_hardened') }}
                </p>
            @endif
        </div>
    </form>
</section>
