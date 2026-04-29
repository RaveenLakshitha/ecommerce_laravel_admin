@extends('layouts.app')

@section('title', __('file.brands') ?? 'Brands')

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            <nav class="admin-breadcrumb mt-6" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}">{{ __('file.dashboard') }}</a>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ __('file.brands') }}</span>
            </nav>

            {{-- Header --}}
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.brands') }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.manage_brands') }}</p>
                </div>
                <a href="{{ route('brands.create') }}" class="admin-btn-accent">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('file.add_brand') }}
                </a>
            </div>

            {{-- Success Alert --}}
            @if(session('success'))
                <div class="admin-alert-success animate-fade-in-scale">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Bulk Delete --}}
            <div id="bulk-delete-form" class="hidden animate-fade-in-scale">
                <form method="POST" action="{{ route('brands.bulkDelete') }}" id="bulk-delete-form-el"
                    class="admin-bulk-bar">
                    @csrf
                    <div id="bulk-ids-container"></div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>
                            <strong id="selected-count" class="font-bold">0</strong> brands selected
                        </span>
                    </div>
                    <button type="submit" class="admin-btn-danger">{{ __('file.delete_selected') }}</button>
                </form>
            </div>

            {{-- Data Table --}}
            <div class="admin-card">
                <div class="overflow-x-auto">
                    <table id="application-table" class="w-full" style="width:100%">
                        <thead>
                            <tr>
                                <th class="!text-center !px-4" style="width: 50px; min-width: 50px;">
                                    <input type="checkbox" id="select-all"
                                        class="w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">
                                </th>
                                <th>Logo</th>
                                <th>Brand Name</th>
                                <th>Sort Order</th>
                                <th>Products</th>
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
            document.addEventListener('DOMContentLoaded', function () {
                const table = $('#application-table').DataTable({
                    processing: true, serverSide: true,
                    ajax: { url: '{{ route('brands.datatable') }}' },
                    order: [[2, 'asc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false },
                        { targets: -1, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">`,
                            className: 'text-center'
                        },
                        {
                            data: 'logo_html', name: 'logo',
                            render: data => `<div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-surface-tonal-a30 flex items-center justify-center p-1 shadow-sm">${data}</div>`
                        },
                        {
                            data: 'name_html', name: 'name',
                            render: data => `<span class="text-sm font-semibold text-gray-900 dark:text-white capitalize">${data}</span>`
                        },
                        {
                            data: 'sort_order', name: 'sort_order',
                            render: data => `<span class="text-xs font-mono text-gray-400">#${data}</span>`
                        },
                        {
                            data: 'products_html', name: 'products_count',
                            render: data => `<span class="text-xs font-medium text-gray-500 dark:text-gray-400">${data} items</span>`
                        },
                        {
                            data: 'status_html', name: 'status', className: 'text-center',
                            render: function (data, type, row) {
                                let status = (data || '').toLowerCase();
                                let cls = 'admin-badge-info';
                                if (status.includes('active')) cls = 'admin-badge-success';
                                if (status.includes('inactive')) cls = 'admin-badge-danger';
                                return `<span class="admin-badge ${cls}">${data}</span>`;
                            }
                        },
                        {
                            data: null, className: 'text-right whitespace-nowrap',
                            render: function (data, type, row) {
                                return `
                                            <div class="flex items-center justify-end gap-1.5 px-3">
                                                <a href="${row.edit_url}" class="p-1.5 rounded-lg text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                ${row.delete_url ? `
                                                <button type="button" onclick="confirmDelete('${row.delete_url}')" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>` : ''}
                                            </div>`;
                            }
                        }
                    ],
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: 'pageLength', className: 'dt-button' },
                                { extend: 'collection', text: "Export", className: 'dt-button', buttons: ['copy', 'excel', 'csv', 'pdf', 'print'] }
                            ]
                        },
                        topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging'
                    },
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "", searchPlaceholder: "Search brands...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No items found", emptyTable: "No brands found.", processing: false,
                    },
                    autoWidth: false, scrollX: false
                });

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
                    $.post(url, { _token: '{{ csrf_token() }}', _method: 'DELETE' }, function (resp) {
                        table.draw(false); updateBulkDelete();
                        if (typeof showNotification === 'function') showNotification('Success', resp.message, 'success');
                    });
                };
            });
        </script>
    @endpush
@endsection