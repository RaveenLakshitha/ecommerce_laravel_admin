@extends('layouts.app')

@section('title', __('file.customers') ?? 'Customers')

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
                <span class="active">{{ __('file.customers') }}</span>
            </nav>

            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.customers') }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.manage_customers') }}</p>
                </div>
            </div>

            <div class="admin-card">
                <div class="overflow-x-auto">
                    <table id="application-table" class="w-full" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __('file.customer') }}</th>
                                <th>{{ __('file.email') }}</th>
                                <th>{{ __('file.orders') }}</th>
                                <th>{{ __('file.total_spent') }}</th>
                                <th class="!text-center">{{ __('file.status') }}</th>
                                <th class="!text-right">{{ __('file.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const table = $('#application-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ route('customers.datatable') }}'
                    },
                    order: [[1, 'asc']],
                    columnDefs: [
                        { targets: 5, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'name_html', name: 'first_name',
                            render: function (data, type, row) {
                                return `
                                                <div class="flex items-center gap-3 py-1">
                                                    <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-semibold border border-indigo-100 dark:border-indigo-500/20 flex-shrink-0">
                                                        ${row.first_name ? row.first_name.charAt(0) : 'C'}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">${data}</span>
                                                        <span class="text-[10px] text-gray-400 font-medium tracking-tight">{{ __('file.verified_identity') }}</span>
                                                    </div>
                                                </div>`;
                            }
                        },
                        {
                            data: 'email', name: 'email',
                            render: data => `<span class="text-xs font-bold text-gray-600 dark:text-gray-400 tracking-tight">${data}</span>`
                        },
                        {
                            data: 'orders_count', name: 'orders_count',
                            render: data => `<span class="text-sm font-medium text-gray-900 dark:text-white">${data || 0}</span>`
                        },
                        {
                            data: 'total_spent_html', name: 'total_spent',
                            render: data => `<span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">${data}</span>`
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
                            data: null,
                            className: 'text-right whitespace-nowrap',
                            render: function (data, type, row) {
                                return `
                                                <div class="flex items-center justify-end gap-1.5 px-3">
                                                    <a href="${row.show_url}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition-all" title="View">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </a>
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
                                    text: "{{ __('file.export') }}",
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
                        searchPlaceholder: "{{ __('file.search_customers') }}",
                        lengthMenu: "_MENU_",
                        info: "{{ __('file.showing_customers') }}",
                        infoEmpty: "{{ __('file.no_customers_found') }}",
                        emptyTable: "{{ __('file.no_customers_found') }}.",
                        processing: '<div class="admin-loader"></div>',
                        paginate: {
                            next: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                            previous: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>'
                        }
                    },
                    autoWidth: false,
                    scrollX: false
                });

            });
        </script>
    @endpush
@endsection
