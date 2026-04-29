@extends('layouts.app')

@section('title', __('file.attributes'))

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
                <span>{{ __('file.attributes') }}</span>
            </nav>

            {{-- Header --}}
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.attributes') }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.manage_attributes') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('attributes.create') }}" class="admin-btn-add">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('file.add_attribute') }}
                    </a>
                </div>
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
                <form method="POST" action="{{ route('attributes.bulkDelete') }}" id="bulk-delete-form-el"
                    class="admin-bulk-bar">
                    @csrf
                    <div id="bulk-ids-container"></div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="selection-count px-3 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 font-bold rounded-lg text-sm tabular-nums" id="selected-count">0</span>
                            <span
                                class="text-sm font-medium text-red-900 dark:text-red-100 whitespace-nowrap">{{ __('file.attributes_selected') }}</span>
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
                                <th>ID</th>
                                <th>{{ __('file.name') }}</th>
                                <th>{{ __('file.type') }}</th>
                                <th class="!text-center">{{ __('file.values_count') }}</th>
                                <th class="!text-right">{{ __('file.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        const initTable = () => {
            if (!window.jQuery) {
                setTimeout(initTable, 50);
                return;
            }
            const $ = window.jQuery;
            $(function() {
                const table = $('#application-table').DataTable({
                    processing: true, serverSide: true,
                    ajax: { url: '{{ route('attributes.datatable') }}' },
                    order: [[1, 'asc']],
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
                            data: 'id', name: 'id',
                            render: data => `<span class="text-xs font-mono text-gray-400">#${data}</span>`
                        },
                        {
                            data: 'name_html', name: 'name',
                            render: data => data
                        },
                        {
                            data: 'type_html', name: 'type',
                            render: data => data
                        },
                        {
                            data: 'values_count', name: 'values_count', className: 'text-center',
                            render: data => `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">${data}</span>`
                        },
                        {
                            data: null,
                            orderable: false,
                            className: 'text-right whitespace-nowrap !px-4',
                            render: function (data, type, row) {
                                return `
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="${row.edit_url}" 
                                            class="p-2 rounded-xl text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-primary/10 transition-all group/btn" 
                                            title="{{ __('file.edit') }}">
                                            <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button type="button" onclick="confirmDelete('${row.delete_url}')" 
                                            class="p-2 rounded-xl text-gray-400 hover:text-error dark:hover:text-error hover:bg-error/10 transition-all group/btn" 
                                            title="{{ __('file.delete') }}">
                                            <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>`;
                            }
                        }
                    ],
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: 'pageLength', className: 'dt-button' },
                                { extend: 'collection', text: "{{ __('file.Export') }}", className: 'dt-button', buttons: ['copy', 'excel', 'csv', 'pdf', 'print'] }
                            ]
                        },
                        topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging'
                    },
                    pageLength: 10, lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "", searchPlaceholder: "{{ __('file.search_attributes') }}...",
                        lengthMenu: "{{ __('file.show_entries') }}", info: "{{ __('file.showing_entries') }}",
                        infoEmpty: "{{ __('file.no_items_found') }}", emptyTable: "{{ __('file.no_attributes_found') }}", processing: false,
                    },
                    autoWidth: false, scrollX: false
                });

                window.attrTable = table;

                $('#select-all').on('change', function () { $('.row-checkbox').prop('checked', this.checked); updateBulkDelete(); });
                $(document).on('change', '.row-checkbox', updateBulkDelete);

                function updateBulkDelete() {
                    const checked = $('.row-checkbox:checked');
                    const count = checked.length;
                    $('#bulk-delete-form').toggleClass('hidden', count === 0);
                    $('#selected-count').text(count);
                    const container = document.getElementById('bulk-ids-container');
                    if (container) {
                        container.innerHTML = '';
                        checked.each(function () {
                            const input = document.createElement('input');
                            input.type = 'hidden'; input.name = 'ids[]'; input.value = this.value;
                            container.appendChild(input);
                        });
                    }
                }

                $('#bulk-delete-form-el').on('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('{{ __("file.confirm_delete_selected_items") }}')) return;
                    $.ajax({
                        url: this.action, method: 'POST', data: $(this).serialize(),
                        success: function (r) { table.draw(false); updateBulkDelete(); $('#select-all').prop('checked', false); if (r.success && typeof showNotification === 'function') showNotification('{{ __('file.Success') }}', r.message, 'success'); }
                    });
                });

                window.confirmDelete = function (url) {
                    if (!confirm('{{ __("file.confirm_delete_item") }}')) return;
                    $.post(url, { _token: '{{ csrf_token() }}', _method: 'DELETE' }, function (resp) {
                        table.draw(false); updateBulkDelete();
                        if (typeof showNotification === 'function') showNotification('{{ __('file.Success') }}', resp.message, 'success');
                    });
                };
            });
        };
        initTable();
    </script>
@endpush