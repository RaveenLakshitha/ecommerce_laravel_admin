@extends('layouts.app')

@section('title', __('file.add_new_role'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            
            <div class="mb-4 mt-10">
                <a href="{{ route('roles.index') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors uppercase tracking-wider inline-block">
                    &larr; {{ __('file.back_to_roles') }}
                </a>
            </div>

            
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

                
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a10">
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

                
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50 dark:bg-surface-tonal-a10 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.permission_matrix') }}</h2>
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
                                    <tr class="hover:bg-gray-50 dark:bg-surface-tonal-a10 dark:hover:bg-indigo-900/5 transition-colors group">
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
                                                            data-resource="{{ $resource }}"
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