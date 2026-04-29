@extends('layouts.app')

@section('title', __('file.add_new_role'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Breadcrumbs --}}
            <div class="mb-4 mt-10">
                <a href="{{ route('roles.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_roles') }}
                </a>
            </div>

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('file.add_new_role') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.create_role_helper') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="create-role-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.save_role') }}
                    </button>
                </div>
            </div>

            <form action="{{ route('roles.store') }}" method="POST" id="create-role-form" class="space-y-4">
                @csrf

                {{-- Role Identity --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.role_identity') }}</h2>
                    </div>
                    <div class="p-4">
                        <div class="max-w-md">
                            <label for="name"
                                class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.authority_label') }}
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                placeholder="e.g. Senior Inventory Auditor"
                                class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-indigo-300 dark:focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/5 focus:shadow-md uppercase tracking-tighter">
                            @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold px-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Permission Matrix --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.permission_matrix') }}</h2>
                        <button type="button" onclick="selectAllPermissions()"
                            class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-600 transition-colors">
                            {{ __('file.select_all_privileges') }}
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($permissions->groupBy(function ($p) {
                                return explode('-', $p->name)[0]; }) as $group => $perms)
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center justify-between border-b border-gray-50 dark:border-surface-tonal-a30 pb-2 mb-2">
                                        <h3
                                            class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                            {{ $group }} {{ __('file.module') }}
                                        </h3>
                                        <button type="button" onclick="toggleGroup('{{ $group }}')"
                                            class="text-[9px] font-bold text-indigo-500/70 hover:text-indigo-500 uppercase tracking-widest transition-colors">
                                            {{ __('file.toggle_group') }}
                                        </button>
                                    </div>
                                    <div class="space-y-1">
                                        @foreach($perms as $permission)
                                            <label
                                                class="group flex items-center p-2 rounded-lg border border-transparent hover:bg-gray-100 dark:hover:bg-white/5 cursor-pointer transition-all">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                    data-group="{{ $group }}"
                                                    class="perm-checkbox h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                <span
                                                    class="ml-3 text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-black dark:group-hover:text-white uppercase tracking-tighter transition-colors">
                                                    {{ str_replace('-', ' • ', $permission->name) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="px-8 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-black dark:hover:bg-gray-100 transition-all shadow-lg active:scale-[0.98]">
                        {{ __('file.save_role') }}
                    </button>
                    <a href="{{ route('roles.index') }}"
                        class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">
                        {{ __('file.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function selectAllPermissions() {
                const checkboxes = document.querySelectorAll('.perm-checkbox');
                const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
                checkboxes.forEach(cb => cb.checked = anyUnchecked);
            }

            function toggleGroup(group) {
                const checkboxes = document.querySelectorAll(`.perm-checkbox[data-group="${group}"]`);
                const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
                checkboxes.forEach(cb => cb.checked = anyUnchecked);
            }
        </script>
    @endpush
@endsection