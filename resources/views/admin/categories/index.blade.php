@extends('layouts.app')

@section('title', __('file.categories') ?? 'Categories')

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-600 dark:hover:text-gray-300 transition-colors">{{ __('file.dashboard') }}</a>
                <svg class="w-3 h-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-500">{{ __('file.categories') }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('file.categories') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('file.manage_categories') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('categories.tree') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-white/10 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Tree View
                    </a>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('file.add_category') }}
                    </a>
                </div>
            </div>

            {{-- Success Alert --}}
            @if(session('success'))
                <div class="admin-alert-success animate-fade-in-scale">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Bulk Actions Bar --}}
            <div id="bulk-delete-form" class="hidden animate-fade-in-scale sticky top-20 z-30">
                <form method="POST" action="{{ route('categories.bulkDelete') }}" id="bulk-delete-form-el" class="admin-bulk-bar">
                    @csrf
                    <div id="bulk-ids-container"></div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="selection-count" id="selected-count">0</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ __('file.category_selected') ?? 'Categories Selected' }}</span>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-md shadow-red-500/20 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('file.delete_selected') }}
                    </button>
                </form>
            </div>

            {{-- Data Table --}}
            <div class="admin-card">
                <div class="overflow-x-auto">
                    <table id="application-table" class="w-full" style="width:100%">
                        <thead>
                            <tr>
                                <th class="!text-center !px-4" style="width: 50px; min-width: 50px;">
                                    <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">
                                </th>
                                <th>Category Name</th>
                                <th>Parent</th>
                                <th class="!text-center">Status</th>
                                <th class="!text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = $('#application-table').DataTable({
                processing: true, serverSide: true,
                ajax: { url: '{{ route('categories.datatable') }}' },
                order: [[1, 'asc']],
                columnDefs: [
                    { targets: 0, orderable: false, searchable: false },
                    { targets: -1, orderable: false, searchable: false }
                ],
                columns: [
                    {
                        data: 'id',
                        render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">`,
                        className: 'text-center',
                    },
                    {
                        data: 'name',
                        render: (data, type, row) => `
                            <div class="flex flex-col py-0.5">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white capitalize">${data || '-'}</span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest">${row.parent_id === null ? 'Root' : 'Sub-Category'}</span>
                            </div>`
                    },
                    {
                        data: 'parent_name',
                        render: data => `<span class="text-xs text-gray-500 dark:text-gray-400 font-medium">${data || '—'}</span>`
                    },
                    { 
                        data: 'status_html', 
                        className: 'text-center',
                        render: function(data, type, row) {
                            let status = data.toLowerCase().trim();
                            let cls = 'admin-badge-info';
                            if (status.includes('active')) cls = 'admin-badge-success';
                            if (status.includes('inactive')) cls = 'admin-badge-danger';
                            return `<span class="admin-badge ${cls}">${data}</span>`;
                        }
                    },
                    {
                        data: null, className: 'text-right whitespace-nowrap',
                        render: (data, type, row) => `
                            <div class="flex items-center justify-end gap-1.5 px-3">
                                <a href="${row.edit_url}" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button type="button" onclick="confirmDelete('${row.delete_url}')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>`
                    }
                ],
                layout: {
                    topStart: { buttons: [
                        { extend: 'pageLength', className: 'dt-button' },
                        { extend: 'collection', text: "Export", className: 'dt-button', buttons: ['copy', 'excel', 'csv', 'pdf', 'print'] }
                    ]},
                    topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging'
                },
                pageLength: 25, lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "", searchPlaceholder: "Search categories...",
                    lengthMenu: "Show _MENU_ entries", info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No items found", emptyTable: "No categories found.", processing: false,
                },
                autoWidth: false, scrollX: false
            });
            window.table = table;



            $('#select-all').on('change', function () { $('.row-checkbox').prop('checked', this.checked); updateBulkDelete(); });
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
                    input.type = 'hidden'; input.name = 'ids[]'; input.value = this.value;
                    container.appendChild(input);
                });
            }

            $('#bulk-delete-form-el').on('submit', function (e) {
                e.preventDefault();
                if (!confirm('{{ __("file.confirm_delete_selected_items") }}')) return;
                $.ajax({
                    url: this.action, method: 'POST', data: $(this).serialize(),
                    success: function (response) {
                        table.draw(false); updateBulkDelete(); $('#select-all').prop('checked', false);
                        if (response.success && typeof showNotification === 'function') showNotification('Success', response.message, 'success');
                    }
                });
            });

            window.confirmDelete = function (url) {
                if (!confirm('{{ __("file.confirm_delete_item") }}')) return;
                $.post(url, { _token: '{{ csrf_token() }}', _method: 'DELETE' }, function(resp) {
                    table.draw(false); updateBulkDelete();
                    if (typeof showNotification === 'function') showNotification('Success', resp.message, 'success');
                });
            };
        });
    </script>
    @endpush
@endsection
