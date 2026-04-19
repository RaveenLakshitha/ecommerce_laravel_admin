<section class="space-y-10">
    <header>
        <h2 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">Security Recalibration</h2>
        <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Ensure your account remains secure by rotating your access credentials regularly.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        <div class="space-y-3 p-6 bg-gray-50/50 dark:bg-surface-tonal-a10/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 shadow-inner">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Current Password') }}</label>
            <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                class="block w-full px-5 py-4 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
            <x-input-error class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 italic" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="space-y-3 p-6 bg-gray-50/50 dark:bg-surface-tonal-a10/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 shadow-inner">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('New Password') }}</label>
            <input type="password" name="password" id="password" autocomplete="new-password"
                class="block w-full px-5 py-4 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
            <x-input-error class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 italic" :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="space-y-3 p-6 bg-gray-50/50 dark:bg-surface-tonal-a10/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 shadow-inner">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                class="block w-full px-5 py-4 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
            <x-input-error class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 italic" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl active:scale-95 group">
                Rotate Credentials
                <svg class="w-4 h-4 ml-2 transition-transform group-hover:rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-emerald-500 uppercase tracking-widest italic animate-pulse">
                    Security Layer Hardened.
                </p>
            @endif
        </div>
    </form>
</section>
