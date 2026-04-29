@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('roles.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Privilege Matrix</a>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Architect Access Role
                    </h1>
                    <p
                        class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium italic underline decoration-indigo-500/20 underline-offset-4">
                        Define custom authority levels and permission boundaries for system users.</p>
                </div>
            </div>

            <form action="{{ route('roles.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Role Identity --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                        <h2 class="font-semibold text-gray-900 dark:text-white">Role Identity</h2>
                    </div>
                    <div class="p-6">
                        <div class="max-w-md space-y-1.5">
                            <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Authority
                                Label <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                placeholder="e.g. Senior Inventory Auditor"
                                class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm uppercase tracking-tighter">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Permission Matrix --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900 dark:text-white">Permission Matrix</h2>
                        <button type="button" onclick="selectAllPermissions()"
                            class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-600 transition-colors">Select
                            All Privileges</button>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                            @foreach($permissions->groupBy(function ($p) {
                                return explode('-', $p->name)[0]; }) as $group => $perms)
                                <div class="space-y-4">
                                    <h3
                                        class="text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-50 dark:border-surface-tonal-a30 pb-2 flex items-center justify-between">
                                        {{ $group }} Module
                                        <button type="button" onclick="toggleGroup('{{ $group }}')"
                                            class="text-[8px] hover:text-indigo-500 transition-colors">Toggle Group</button>
                                    </h3>
                                    <div class="space-y-2.5">
                                        @foreach($perms as $permission)
                                            <label
                                                class="group flex items-center p-3 rounded-xl border border-transparent hover:border-indigo-100 dark:hover:border-indigo-900/40 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 cursor-pointer transition-all">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                    data-group="{{ $group }}"
                                                    class="perm-checkbox h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                                <span
                                                    class="ml-4 text-xs font-bold text-gray-600 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 uppercase tracking-tighter">
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

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="h-14 px-10 flex items-center justify-center rounded-xl bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-[0.2em] hover:bg-black dark:hover:bg-gray-100 transition-all shadow-2xl shadow-gray-200 dark:shadow-none active:scale-[0.98]">
                        Instantiate Role
                    </button>
                    <a href="{{ route('roles.index') }}"
                        class="px-8 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-red-500 transition-all">
                        Discard Draft
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