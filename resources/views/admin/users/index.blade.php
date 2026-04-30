@extends('layouts.app')

@section('title', __('file.user_management') ?? 'User Management')

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <nav class="admin-breadcrumb mt-6" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('file.dashboard') }}
                </a>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span class="active">{{ __('file.user_management') }}</span>
            </nav>

            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.user_management') }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.manage_users') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="filter-button" class="admin-btn-outline">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filters
                        <span id="filter-count"
                            class="hidden ml-1 px-1.5 py-0.5 text-[10px] rounded-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold"></span>
                    </button>
                    <a href="{{ route('users.create') }}" class="admin-btn-add">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('file.add_user') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="admin-alert-success animate-fade-in-scale">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div id="bulk-delete-form" class="hidden animate-fade-in-scale sticky top-20 z-30 mb-6">
                <form method="POST" action="{{ route('users.bulkDelete') }}" id="bulk-delete-form-el"
                    class="admin-bulk-bar">
                    @csrf
                    <div id="bulk-ids-container" class="hidden"></div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="selection-count px-3 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 font-bold rounded-lg text-sm tabular-nums" id="selected-count">0</span>
                            <span
                                class="text-sm font-medium text-red-900 dark:text-red-100 whitespace-nowrap">{{ __('file.users_selected') ?? 'Users Selected' }}</span>
                        </div>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-red-600/20 active:scale-95 whitespace-nowrap border border-red-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('file.delete_selected') }}
                    </button>
                </form>
            </div>

            <div class="admin-card">
                <div class="overflow-x-auto">
                    <table id="application-table" class="w-full" style="width:100%">
                        <thead>
                            <tr>
                                <th class="!text-center !px-4" style="width: 50px; min-width: 50px;">
                                    <input type="checkbox" id="select-all"
                                        class="w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">
                                </th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th class="!text-center">Status</th>
                                <th>Joined</th>
                                <th class="!text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Filter Drawer --}}
    <div id="filter-drawer" class="fixed inset-0 z-[100] hidden overflow-hidden transition-all duration-500">
        <div id="filter-overlay"
            class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"
            onclick="closeFilterDrawer()"></div>
        <div id="filter-panel"
            class="absolute inset-y-0 right-0 w-full md:max-w-lg bg-white dark:bg-surface-tonal-a20 shadow-2xl transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col">
            <div class="flex items-center justify-between px-8 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Active Filters</h3>
                    <p class="text-sm font-medium text-primary-a0 mt-1">Refine
                        your search criteria</p>
                </div>
                <button onclick="closeFilterDrawer()"
                    class="p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar">
                <div class="space-y-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">System
                            Role</label>
                        <select id="filter-role"
                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 cursor-pointer">
                            <option value="">All Access Levels</option>
                            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Account
                            Lifecycle</label>
                        <select id="filter-status"
                            class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5 cursor-pointer">
                            <option value="">Any Status</option>
                            <option value="1">Operational</option>
                            <option value="0">Suspended / Pending</option>
                        </select>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-white/5">
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">
                            Provisioning Window</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Archive
                                    Start</label>
                                <input type="date" id="filter-from"
                                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Archive
                                    Termination</label>
                                <input type="date" id="filter-to"
                                    class="block w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-surface-tonal-a20 px-4 py-2.5 text-sm font-normal shadow-sm text-gray-900 dark:text-white outline-none transition-all focus:bg-white dark:focus:bg-surface-tonal-a30 focus:border-primary/50 focus:ring-4 focus:ring-primary/5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="px-8 py-6 bg-gray-100/50 dark:bg-surface-tonal-a10 border-t border-gray-100 dark:border-white/5 flex gap-3">
                <button id="clear-filters"
                    class="flex-1 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all active:scale-[0.98]">
                    Reset
                </button>
                <button id="apply-filters"
                    class="flex-[1.5] flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                    Apply Search
                </button>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            const filterDrawer = document.getElementById('filter-drawer');
            const filterOverlay = document.getElementById('filter-overlay');
            const filterPanel = document.getElementById('filter-panel');

            window.openFilterDrawer = () => {
                filterDrawer.classList.remove('hidden');
                setTimeout(() => {
                    filterOverlay.classList.add('opacity-100');
                    filterPanel.classList.remove('translate-x-full');
                }, 10);
                document.body.style.overflow = 'hidden';
            };

            window.closeFilterDrawer = () => {
                filterOverlay.classList.remove('opacity-100');
                filterPanel.classList.add('translate-x-full');
                document.body.style.overflow = '';
                setTimeout(() => filterDrawer.classList.add('hidden'), 500);
            };

            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('filter-button').addEventListener('click', openFilterDrawer);

                const table = $('#application-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ route('users.datatable') }}',
                        data: d => {
                            d.role = $('#filter-role').val();
                            d.status = $('#filter-status').val();
                            d.from = $('#filter-from').val();
                            d.to = $('#filter-to').val();
                        }
                    },
                    order: [[1, 'asc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false },
                        { targets: 6, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">`,
                            className: 'text-center',
                        },
                        {
                            data: 'name',
                            render: function (data, type, row) {
                                return `
                                                        <div class="flex items-center gap-3 py-1">
                                                            <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-semibold border border-indigo-100 dark:border-indigo-500/20 flex-shrink-0">
                                                                ${data.charAt(0)}
                                                            </div>
                                                            <div class="flex flex-col">
                                                                <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">${data}</span>
                                                                <span class="text-xs text-gray-400 font-medium tracking-tight">System Identity</span>
                                                            </div>
                                                        </div>`;
                            }
                        },
                        {
                            data: 'email',
                            render: data => `<span class="text-xs font-bold text-gray-600 dark:text-gray-400 tracking-tight">${data}</span>`
                        },
                        {
                            data: 'roles',
                            render: data => data.map(r => `<span class="inline-flex px-2.5 py-1 text-[10px] font-bold bg-gray-100 dark:bg-surface-tonal-a30 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg mr-1 uppercase tracking-wider">${r}</span>`).join('') || '<span class="text-xs text-gray-300 italic">No Role</span>'
                        },
                        {
                            data: 'is_active',
                            render: function (data) {
                                const s = data ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20';
                                return `<span class="admin-badge ${s} text-[10px] font-bold uppercase tracking-wider">${data ? 'Operational' : 'Suspended'}</span>`;
                            },
                            className: 'text-center'
                        },
                        {
                            data: 'created_at',
                            render: d => `<span class="text-xs text-gray-400 font-medium">${d}</span>`
                        },
                        {
                            data: null,
                            className: 'text-right whitespace-nowrap',
                            render: function (data, type, row) {
                                return `
                                                        <div class="flex items-center justify-end gap-1.5 px-3">
                                                            <a href="${row.edit_url}" class="p-1.5 rounded-lg text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all" title="Edit">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                            </a>
                                                            <button type="button" onclick="deleteUser(${row.id}, '${row.name.replace(/'/g, "\\'")}')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all" title="Delete">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>`;
                            }
                        }
                    ],
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: 'pageLength', className: 'dt-button' },
                                {
                                    extend: 'collection',
                                    text: "Export",
                                    className: 'dt-button',
                                    buttons: [
                                        { extend: 'copy', className: 'dt-button' },
                                        { extend: 'excel', className: 'dt-button' },
                                        { extend: 'csv', className: 'dt-button' },
                                        { extend: 'pdf', className: 'dt-button' },
                                        { extend: 'print', className: 'dt-button' }
                                    ]
                                }
                            ]
                        },
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging'
                    },
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "",
                        searchPlaceholder: "Search Users...",
                        lengthMenu: "_MENU_",
                        info: "Showing _START_ to _END_ of _TOTAL_ Users",
                        infoEmpty: "No users found",
                        emptyTable: "No users found.",
                        processing: '<div class="admin-loader"></div>',
                        paginate: {
                            next: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                            previous: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>'
                        }
                    },
                    autoWidth: false,
                    scrollX: false
                });

                $('#apply-filters').on('click', function () {
                    table.draw();
                    closeFilterDrawer();
                    updateFilterCount();
                });

                $('#clear-filters').on('click', function () {
                    $('#filter-role, #filter-status, #filter-from, #filter-to').val('');
                    table.draw();
                    updateFilterCount();
                    closeFilterDrawer();
                });

                function updateFilterCount() {
                    const activeCount = [$('#filter-role').val(), $('#filter-status').val(), $('#filter-from').val(), $('#filter-to').val()].filter(f => f).length;
                    const badge = document.getElementById('filter-count');
                    badge.textContent = activeCount;
                    badge.classList.toggle('hidden', activeCount === 0);
                }

                $('#select-all').on('change', function () {
                    $('.row-checkbox').prop('checked', this.checked);
                    updateBulkDelete();
                });
                $(document).on('change', '.row-checkbox', updateBulkDelete);

                function updateBulkDelete() {
                    const checked = $('.row-checkbox:checked');
                    const count = checked.length;
                    $('#bulk-delete-form').toggleClass('hidden', count === 0);
                    $('#selected-count').text(count);

                    const container = document.getElementById('bulk-ids-container');
                    container.innerHTML = '';
                    checked.each(function () {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = this.value;
                        container.appendChild(input);
                    });
                }

                $('#bulk-delete-form-el').on('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('Execute deauthorization of selected personnel records?')) return;

                    $.ajax({
                        url: this.action,
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function (response) {
                            table.draw(false);
                            updateBulkDelete();
                            $('#select-all').prop('checked', false);
                            if (response.success) {
                                if (typeof showNotification === 'function') showNotification('Success', response.message, 'success');
                            }
                        }
                    });
                });

                window.deleteUser = function (id, name) {
                    if (!confirm(`Permanently obliterate administrative identity of ${name}?`)) return;

                    $.ajax({
                        url: `{{ route('users.index') }}/${id}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            table.draw(false);
                            updateBulkDelete();
                            if (response.success) {
                                if (typeof showNotification === 'function') showNotification('Success', response.message, 'success');
                            }
                        }
                    });
                };
            });
        </script>
    @endpush
@endsection