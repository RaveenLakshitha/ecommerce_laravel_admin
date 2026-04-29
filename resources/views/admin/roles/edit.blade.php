@extends('layouts.app')

@section('title', __('file.edit_role') . ': ' . $role->name)

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            @php
                $roleLabelKey = 'file.role_' . $role->name;
                $roleLabel = __($roleLabelKey) !== $roleLabelKey
                    ? __($roleLabelKey)
                    : ucfirst(str_replace('_', ' ', $role->name));
            @endphp

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
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ __('file.edit_role') }}</h1>
                        @if($isSystemRole)
                            <span
                                class="px-2 py-0.5 rounded-full text-[8px] font-black bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 uppercase tracking-widest">
                                {{ __('file.system_immutable') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('file.updating_authority_parameters_for') }} <span
                            class="text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-tighter">{{ $roleLabel }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="edit-role-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        {{ __('file.update_role') }}
                    </button>
                </div>
            </div>

            @if($isSystemRole)
                <div
                    class="mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10 flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-amber-900 dark:text-amber-200 uppercase tracking-wider">
                            {{ __('file.system_integrity_protection') }}</h3>
                        <p
                            class="text-[10px] text-amber-700/70 dark:text-amber-400/60 mt-1 uppercase tracking-widest leading-relaxed">
                            {{ __('file.system_role_locked_warning') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('roles.update', $role) }}" method="POST" id="edit-role-form" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Role Identity --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.role_identity') }}</h2>
                    </div>
                    <div class="p-4">
                        <div class="max-w-md">
                            <label for="role_name"
                                class="block text-[10px] font-black text-black dark:text-white uppercase tracking-widest mb-1">{{ __('file.authority_label') }}
                                <span class="text-red-500">*</span></label>
                            @if($isSystemRole)
                                <div
                                    class="flex items-center justify-between px-3 py-2 bg-gray-100/50 dark:bg-surface-tonal-a10 border border-gray-100 dark:border-surface-tonal-a30 rounded-md opacity-80 cursor-not-allowed">
                                    <span
                                        class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $roleLabel }}</span>
                                    <span
                                        class="text-[8px] text-gray-400 font-mono tracking-widest uppercase">{{ __('file.locked') }}</span>
                                </div>
                            @else
                                <input type="text" name="name" id="role_name" value="{{ old('name', $role->name) }}" required
                                    class="block w-full rounded-md border border-gray-100/50 dark:border-white/5 bg-gray-50/30 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-300 text-black dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 uppercase tracking-tighter">
                            @endif
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
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.privilege_entitlements') }}
                        </h2>
                        <div class="flex items-center gap-4">
                            <button type="button" onclick="toggleAllMatrix(true)"
                                class="text-[8px] font-black uppercase tracking-widest text-emerald-500 hover:text-emerald-600 transition-colors">{{ __('file.grant_all') }}</button>
                            <button type="button" onclick="toggleAllMatrix(false)"
                                class="text-[8px] font-black uppercase tracking-widest text-red-500 hover:text-red-600 transition-colors">{{ __('file.revoke_all') }}</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50/30 dark:bg-surface-tonal-a10/30 border-b border-gray-100 dark:border-surface-tonal-a30">
                                    <th
                                        class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest w-64">
                                        {{ __('file.domain_namespace') }}</th>
                                    @foreach($allowedActions as $action)
                                        <th
                                            class="px-3 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <span>{{ $action }}</span>
                                                <button type="button" onclick="toggleColumn('{{ $action }}')"
                                                    class="p-1 rounded text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                        {{ __('file.batch') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                                @foreach($permissionsMatrix as $resource => $actions)
                                    @php
                                        $groupKey = 'file.perm_group_' . str_replace(['-', '.'], '_', $resource);
                                        $groupLabel = __($groupKey) !== $groupKey ? __($groupKey) : ucwords(str_replace(['-', '_'], ' ', $resource));
                                    @endphp
                                    <tr class="hover:bg-gray-100/50 dark:hover:bg-indigo-900/5 transition-colors group">
                                        <td class="px-4 py-3">
                                            <span
                                                class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ $groupLabel }}</span>
                                        </td>
                                        @foreach($allowedActions as $action)
                                            <td class="px-3 py-3 text-center">
                                                @if(isset($actions[$action]))
                                                    <label
                                                        class="inline-flex items-center justify-center cursor-pointer p-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $actions[$action]->name }}" data-action="{{ $action }}"
                                                            data-resource="{{ $resource }}" {{ $role->hasPermissionTo($actions[$action]) ? 'checked' : '' }}
                                                            class="perm-cb h-4 w-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                    </label>
                                                @else
                                                    <div class="flex justify-center">
                                                        <div
                                                            class="h-1 w-2 bg-gray-100 dark:bg-surface-tonal-a30 rounded-full opacity-40">
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-3 text-right">
                                            <button type="button" onclick="toggleRow('{{ $resource }}')"
                                                class="p-1.5 rounded text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                    <button type="submit" form="edit-role-form"
                        class="w-full sm:w-auto px-10 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-xl transition-all shadow-lg active:scale-[0.98]">
                        {{ __('file.update_role') }}
                    </button>
                    <a href="{{ route('roles.index') }}"
                        class="w-full sm:w-auto px-10 py-3 border border-gray-200 dark:border-surface-tonal-a30 text-gray-500 text-sm font-bold rounded-xl text-center hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                        {{ __('file.cancel') }}
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