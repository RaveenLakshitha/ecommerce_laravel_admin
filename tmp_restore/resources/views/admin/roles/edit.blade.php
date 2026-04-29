@extends('layouts.app')

@section('title', 'Edit Role: ' . $role->name)

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="max-w-[1400px] mx-auto">

            @php
                $roleLabelKey = 'file.role_' . $role->name;
                $roleLabel = __($roleLabelKey) !== $roleLabelKey
                    ? __($roleLabelKey)
                    : ucfirst(str_replace('_', ' ', $role->name));
            @endphp

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <a href="{{ route('roles.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">&larr;
                        Back to Privilege Matrix</a>
                    <div class="flex items-center gap-4 mt-2">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Profile Modification
                        </h1>
                        @if($isSystemRole)
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 uppercase tracking-widest">
                                System Immutable
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">Updating authority parameters for
                        <span
                            class="text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-tighter">{{ $roleLabel }}</span>
                    </p>
                </div>
            </div>

            @if($isSystemRole)
                <div
                    class="mb-8 p-4 rounded-2xl bg-amber-50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10 flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200">System Integrity Protection</h3>
                        <p class="text-xs text-amber-700/70 dark:text-amber-400/60 mt-0.5">This role is hardcoded into the
                            system core. You can modify its permissions context, but the identity label is locked to maintain
                            application stability.</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Role Identity --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                        <h2 class="font-semibold text-gray-900 dark:text-white">Role Identity</h2>
                    </div>
                    <div class="p-6 font-medium">
                        <div class="max-w-md space-y-1.5">
                            <label for="role_name"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Authority Label <span
                                    class="text-red-500">*</span></label>
                            @if($isSystemRole)
                                <div
                                    class="flex items-center justify-between px-4 py-3 bg-gray-100/50 dark:bg-surface-tonal-a10 border border-gray-100 dark:border-surface-tonal-a30 rounded-xl opacity-80 cursor-not-allowed">
                                    <span
                                        class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $roleLabel }}</span>
                                    <span class="text-[10px] text-gray-400 font-mono tracking-widest uppercase">LOCKED</span>
                                </div>
                            @else
                                <input type="text" name="name" id="role_name" value="{{ old('name', $role->name) }}" required
                                    class="block w-full rounded-xl border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm uppercase tracking-tighter">
                            @endif
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Permission Matrix --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900 dark:text-white">Privilege Entitlements</h2>
                        <div class="flex items-center gap-6">
                            <button type="button" onclick="toggleAllMatrix(true)"
                                class="text-[10px] font-black uppercase tracking-widest text-emerald-500 hover:text-emerald-600 transition-colors">Grant
                                All</button>
                            <button type="button" onclick="toggleAllMatrix(false)"
                                class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-600 transition-colors">Revoke
                                All</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50/30 dark:bg-surface-tonal-a10/30 border-b border-gray-50 dark:border-surface-tonal-a30">
                                    <th
                                        class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-64">
                                        Domain Namespace</th>
                                    @foreach($allowedActions as $action)
                                        <th
                                            class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <span>{{ $action }}</span>
                                                <button type="button" onclick="toggleColumn('{{ $action }}')"
                                                    class="p-1 rounded-md text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                        Batch</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                @foreach($permissionsMatrix as $resource => $actions)
                                    @php
                                        $groupKey = 'file.perm_group_' . str_replace(['-', '.'], '_', $resource);
                                        $groupLabel = __($groupKey) !== $groupKey ? __($groupKey) : ucwords(str_replace(['-', '_'], ' ', $resource));
                                    @endphp
                                    <tr class="hover:bg-gray-100/50 dark:hover:bg-indigo-900/5 transition-colors group">
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ $groupLabel }}</span>
                                        </td>
                                        @foreach($allowedActions as $action)
                                            <td class="px-4 py-4 text-center">
                                                @if(isset($actions[$action]))
                                                    <label
                                                        class="inline-flex items-center justify-center cursor-pointer p-1 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $actions[$action]->name }}" data-action="{{ $action }}"
                                                            data-resource="{{ $resource }}" {{ $role->hasPermissionTo($actions[$action]) ? 'checked' : '' }}
                                                            class="perm-cb h-5 w-5 rounded-md border-gray-300 dark:border-surface-tonal-a30 text-indigo-500 focus:ring-indigo-500 transition-all">
                                                    </label>
                                                @else
                                                    <div class="flex justify-center">
                                                        <div
                                                            class="h-1 w-3 bg-gray-100 dark:bg-surface-tonal-a30 rounded-full opacity-40">
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" onclick="toggleRow('{{ $resource }}')"
                                                class="p-2 rounded-xl text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M4 6h16M4 12h16M4 18h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center gap-4 border-t border-gray-100 dark:border-surface-tonal-a30 pt-8 mt-10">
                    <button type="submit"
                        class="h-14 px-12 flex items-center justify-center rounded-xl bg-gray-900 dark:bg-white text-[10px] font-black text-white dark:text-gray-900 uppercase tracking-[0.2em] hover:bg-black dark:hover:bg-gray-100 transition-all shadow-2xl shadow-gray-200 dark:shadow-none active:scale-[0.98]">
                        Commit Paradigm
                    </button>
                    <a href="{{ route('roles.index') }}"
                        class="px-8 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-red-500 transition-all">
                        Abandon Modifications
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleAllMatrix(state) {
                document.querySelectorAll('.perm-cb').forEach(cb => cb.checked = state);
            }
            function toggleRow(resource) {
                const cbs = document.querySelectorAll(`.perm-cb[data-resource="${resource}"]`);
                const anyUnchecked = Array.from(cbs).some(cb => !cb.checked);
                cbs.forEach(cb => cb.checked = anyUnchecked);
            }
            function toggleColumn(action) {
                const cbs = document.querySelectorAll(`.perm-cb[data-action="${action}"]`);
                const anyUnchecked = Array.from(cbs).some(cb => !cb.checked);
                cbs.forEach(cb => cb.checked = anyUnchecked);
            }
        </script>
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #E5E7EB;
                border-radius: 10px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #334155;
            }
        </style>
    @endpush
@endsection