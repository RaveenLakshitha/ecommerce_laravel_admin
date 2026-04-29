<section class="space-y-10">
    <header>
        <h2 class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] mb-2">Decommission Protocol</h2>
        <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter italic leading-relaxed">
            Permanently deconstruct your account and associated node metadata. This action is atomic and irreversible.
        </p>
    </header>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 px-8 py-4 bg-rose-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-500/20 active:scale-95 group">
        Initialize Deconstruction
        <svg class="w-4 h-4 ml-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}"
            class="p-10 space-y-10 bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 rounded-[2.5rem]">
            @csrf
            @method('delete')

            <div class="space-y-4">
                <h2
                    class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic underline decoration-rose-500/30 underline-offset-8">
                    Confirm Deconstruction?</h2>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 leading-relaxed">Once your account is
                    deconstructed, all of its assets and metadata will be permanently purged from the matrix. Please
                    enter your root secret to authorize this protocol.</p>
            </div>

            <div
                class="space-y-3 p-6 bg-gray-100/50 dark:bg-surface-tonal-a10/50 rounded-3xl border border-gray-100 dark:border-surface-tonal-a30 shadow-inner">
                <label
                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Password') }}</label>
                <input type="password" name="password" id="password_del" placeholder="{{ __('Root Secret') }}"
                    class="block w-full px-5 py-4 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl text-sm font-mono text-rose-500 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all shadow-sm">
                <x-input-error class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 italic"
                    :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="flex items-center gap-4">
                <button type="button" x-on:click="$dispatch('close')"
                    class="flex-1 px-8 py-4 bg-transparent border border-gray-200 dark:border-surface-tonal-a30 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-all">Abat
                    Protocol</button>
                <button type="submit"
                    class="flex-2 px-10 py-4 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-500/20 active:scale-95">Deconstruct
                    Account</button>
            </div>
        </form>
    </x-modal>
</section>