@extends('layouts.app')

@section('title', __('file.user_management') ?? 'User Management')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                {{ __('file.user_management') ?? 'User Management' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('file.manage_users') ?? 'Coordinate access and maintain security for administrative personnel' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" id="filter-button"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filters
                <span id="filter-count" class="hidden ml-1 px-1.5 py-0.5 text-[10px] rounded-full bg-indigo-600 text-white font-bold"></span>
            </button>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('file.add_user') ?? 'Add User' }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex text-green-700">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div id="bulk-delete-form" class="hidden mb-6">
        <form method="POST" action="{{ route('users.bulkDelete') }}" id="bulk-delete-form-el"
            class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 rounded-lg p-4 flex justify-between items-center">
            @csrf
            <div id="bulk-ids-container"></div>
            <span class="text-sm font-medium text-red-800 dark:text-red-300">
                <span id="selected-count">0</span> items selected
            </span>
            <button type="submit"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                {{ __('file.delete_selected') ?? 'Delete Selected' }}
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-surface-tonal-a10 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="application-table" class="w-full divide-y divide-gray-200 dark:divide-surface-tonal-a30 nowrap" style="width:100%">
                <thead class="bg-gray-50 dark:bg-surface-tonal-a10 border-b border-gray-200 dark:border-surface-tonal-a30">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-right" style="width: 80px; min-width: 80px;">
                            <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">User</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Email</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Roles</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Joined</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-surface-tonal-a10 divide-y divide-gray-200 dark:divide-surface-tonal-a30 [&>tr]:group transition-all">
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Filter Drawer --}}
<div id="filter-drawer" class="fixed inset-0 z-[100] hidden overflow-hidden transition-all duration-500">
    <div id="filter-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeFilterDrawer()"></div>
    <div id="filter-panel" class="absolute inset-y-0 right-0 w-full md:max-w-sm bg-white dark:bg-surface-tonal-a10 shadow-2xl transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-surface-tonal-a20">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Active Filters</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Refine your search criteria</p>
            </div>
            <button onclick="closeFilterDrawer()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">System Role</label>
                    <select id="filter-role" class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 text-sm focus:ring-indigo-500">
                        <option value="">All Access Levels</option>
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account Lifecycle</label>
                    <select id="filter-status" class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 text-sm focus:ring-indigo-500">
                        <option value="">Any Status</option>
                        <option value="1">Operational</option>
                        <option value="0">Suspended / Pending</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-surface-tonal-a20">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Provisioning Window</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 ml-1">Archive Start</label>
                            <input type="date" id="filter-from" class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 text-sm focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 ml-1">Archive Termination</label>
                            <input type="date" id="filter-to" class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 text-sm focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-surface-tonal-a10 border-t border-gray-200 dark:border-surface-tonal-a20 flex gap-3">
            <button id="clear-filters"
                class="flex-1 px-4 py-3 border border-gray-300 dark:border-surface-tonal-a30 text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 rounded-xl hover:bg-white transition-all">
                Reset
            </button>
            <button id="apply-filters"
                class="flex-1 px-4 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg">
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
                    render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">`,
                    className: 'text-center',
                    orderable: false
                },
                { 
                    data: 'name',
                    render: function(data, type, row) {
                        return `
                        <div class="flex items-center gap-3 py-1">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-tighter border border-indigo-100 dark:border-indigo-500/20 flex-shrink-0">
                                ${data.charAt(0)}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">${data}</span>
                                <span class="text-[10px] text-gray-400 font-medium tracking-tight">System Identity</span>
                            </div>
                        </div>`;
                    }
                },
                { 
                    data: 'email',
                    render: data => `<span class="text-xs font-mono text-gray-700 dark:text-gray-300 italic underline decoration-indigo-500/10">${data}</span>`
                },
                {
                    data: 'roles',
                    render: data => data.map(r => `<span class="inline-flex px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest bg-gray-100 dark:bg-surface-tonal-a30 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-surface-tonal-a30 rounded-md mr-1">${r}</span>`).join('') || '<span class="text-[10px] text-gray-300 uppercase italic">No Role</span>'
                },
                { 
                    data: 'is_active',
                    render: function(data) {
                        const s = data ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20';
                        return `<span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-widest border ${s}">${data ? 'Operational' : 'Suspended'}</span>`;
                    },
                    className: 'text-center'
                },
                { 
                    data: 'created_at',
                    render: d => `<span class="text-xs text-gray-400 font-medium">${d}</span>`
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-right whitespace-nowrap',
                    render: function (data, type, row) {
                        return `
                        <div class="flex items-center justify-end gap-3 transition-opacity">
                            <a href="${row.edit_url}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button type="button" onclick="deleteUser(${row.id}, '${row.name.replace(/'/g, "\\'")}')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>`;
                    }
                }
            ],
            layout: {
                topStart: {
                    buttons: [
                        { extend: 'pageLength', className: 'btn btn-sm btn-light' },
                        { extend: 'collection', text: "Export", className: 'btn btn-sm btn-dark', buttons: ['copy', 'excel', 'csv', 'pdf', 'print'] }
                    ]
                },
                topEnd: 'search',
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No items found",
                emptyTable: "No users found.",
                processing: false,
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