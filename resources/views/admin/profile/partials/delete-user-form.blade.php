<section class="space-y-6">
    <header>
        <h2 class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">{{ __('file.decommission_protocol') }}</h2>
        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tighter leading-relaxed">{{ __('file.deconstruct_account_text') }}</p>
    </header>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 px-6 py-3 bg-rose-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-rose-700 transition-all shadow-lg active:scale-95 group">
        {{ __('file.initialize_deconstruction') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 space-y-6 bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 rounded-2xl">
            @csrf
            @method('delete')

            <div class="space-y-2">
                <h2 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">{{ __('file.confirm_deconstruction_title') }}</h2>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('file.confirm_deconstruction_text') }}</p>
            </div>

            <div>
                <label for="password_del" class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.password') }}</label>
                <input type="password" name="password" id="password_del" placeholder="{{ __('file.root_secret') }}"
                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-mono font-bold text-rose-500 outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30">
                <x-input-error class="text-[10px] font-bold text-rose-500 mt-1" :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close')" class="flex-1 px-6 py-2.5 bg-transparent border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-all">
                    {{ __('file.abort_protocol') }}
                </button>
                <button type="submit" class="flex-1 px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-700 transition-all shadow-lg active:scale-95">
                    {{ __('file.deconstruct_account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
