<section class="space-y-10">
    <header>
        <h2 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2">
            Identification Profile</h2>
        <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Update your
            account's profile information and primary communication endpoint.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div
            class="space-y-3 p-6 bg-gray-100/50 dark:bg-surface-tonal-a10/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 shadow-inner">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Name') }} <span
                    class="text-rose-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus
                autocomplete="name"
                class="block w-full px-5 py-4 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
            <x-input-error class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 italic"
                :messages="$errors->get('name')" />
        </div>

        <div
            class="space-y-3 p-6 bg-gray-100/50 dark:bg-surface-tonal-a10/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 shadow-inner">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Email') }} <span
                    class="text-rose-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                autocomplete="username"
                class="block w-full px-5 py-4 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-bold text-gray-700 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
            <x-input-error class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 italic"
                :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div
                    class="mt-4 p-4 bg-amber-50 dark:bg-amber-500/10 rounded-2xl border border-amber-100 dark:border-amber-500/20">
                    <p
                        class="text-[10px] font-black text-amber-800 dark:text-amber-400 uppercase tracking-widest leading-relaxed italic">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification"
                            class="ml-2 underline text-amber-600 dark:text-amber-300 hover:text-amber-700">
                            {{ __('Re-send Verification Token') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit"
                class="inline-flex items-center gap-2 px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-xl active:scale-95 group">
                Commit Identity
                <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-emerald-500 uppercase tracking-widest italic animate-pulse">
                    Sync Complete.
                </p>
            @endif
        </div>
    </form>
</section>