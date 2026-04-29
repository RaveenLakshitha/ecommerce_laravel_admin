<section class="space-y-6">
    <header>
        <h2 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('file.identification_profile') }}</h2>
        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('file.update_profile_info_text') }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.full_legal_name') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 uppercase tracking-tighter">
                <x-input-error class="text-[10px] font-bold text-rose-500 mt-1" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.corporate_email') }} <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                <x-input-error class="text-[10px] font-bold text-rose-500 mt-1" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-500/10 rounded-lg border border-amber-100 dark:border-amber-500/20">
                        <p class="text-[10px] font-bold text-amber-800 dark:text-amber-400 uppercase tracking-widest leading-relaxed">
                            {{ __('file.email_unverified') }}
                            <button form="send-verification" class="ml-1 underline text-amber-600 dark:text-amber-300 hover:text-amber-700">
                                {{ __('file.resend_verification_token') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">
                                {{ __('file.verification_link_sent') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-95 group">
                {{ __('file.commit_identity') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest italic animate-pulse">
                    {{ __('file.sync_complete') }}
                </p>
            @endif
        </div>
    </form>
</section>
