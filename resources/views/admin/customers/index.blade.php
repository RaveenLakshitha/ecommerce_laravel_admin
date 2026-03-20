@extends('layouts.app')

@section('title', __('file.customers') ?? 'Customers')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-primary-a0">Customers Management</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">A list of all registered customers including their
                    email, total spent, and lifetime value.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div id="bulk-delete-form" class="hidden mb-6">
            <form method="POST" action="{{ route('customers.bulkDelete') }}" id="bulk-delete-form-el"
                class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 rounded-lg p-4 flex justify-between items-center">
                @csrf
                <div id="bulk-ids-container"></div>
                <span class="text-sm font-medium text-red-800 dark:text-red-300">
                    <span id="selected-count">0</span> customers selected
                </span>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                    Delete Selected
                </button>
            </form>
        </div>

        <div class="-mx-4 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
                <div
                    class="shadow-sm ring-1 ring-black ring-opacity-5 sm:rounded-lg overflow-hidden border border-gray-200 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                    <div class="overflow-x-auto">
                        <table id="application-table" class="min-w-full divide-y divide-gray-300 dark:divide-gray-700"
                            style="width: 100%">
                            <thead class="bg-gray-50 dark:bg-surface-tonal-a10">
                                <tr>
                                    <th class="py-3.5 pl-4 pr-3 text-left all sm:pl-6" style="width: 50px;">
                                        <input type="checkbox" id="select-all"
                                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </th>
                                    <th
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0 sm:pl-6">
                                        Name</th>
                                    <th
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                        Email</th>
                                    <th
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                        Orders</th>
                                    <th
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                        Total Spent</th>
                                    <th
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                        Status</th>
                                    <th
                                        class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-surface-tonal-a10">
                            </tbody>
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
                    order: [[0, 'desc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false },
                        { targets: -1, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">`,
                            className: 'pl-4 sm:pl-6 text-left',
                            orderable: false
                        },
                        { data: 'name_html', name: 'first_name', className: 'py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-primary-a0 sm:pl-6' },
                        { data: 'email', name: 'email', className: 'px-3 py-4 text-sm text-gray-500 dark:text-gray-400' },
                        { data: 'orders_count', name: 'orders_count', className: 'px-3 py-4 text-sm text-gray-500 dark:text-gray-400', searchable: false },
                        { data: 'total_spent_html', name: 'total_spent', className: 'px-3 py-4 text-sm font-medium text-gray-900 dark:text-primary-a0', searchable: false },
                        { data: 'status_html', name: 'status', className: 'px-3 py-4 text-sm text-gray-500 dark:text-gray-400' },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6',
                            render: function (data, type, row) {
                                return `
                                                        <div class="flex items-center justify-end gap-3 transition-opacity">
                                                            <a href="${row.show_url}" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 p-1.5 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20" title="View Customer">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            </a>
                                                            <button type="button" onclick="confirmDelete('${row.delete_url}')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    `;
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
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "",
                        searchPlaceholder: "Search customers...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No items found",
                        emptyTable: "No customers found matching your criteria.",
                        processing: false,
                    },
                    autoWidth: false,
                    scrollX: false
                });

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
                    if (!confirm('Are you sure you want to delete the selected items?')) return;

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
                            } else {
                                if (typeof showNotification === 'function') showNotification('Error', response.message, 'error');
                                else alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Delete failed.';
                            if (typeof showNotification === 'function') showNotification('Error', msg, 'error');
                            else alert(msg);
                        }
                    });
                });

                window.confirmDelete = function (url) {
                    if (!confirm('Are you sure you want to completely remove this item?')) return;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            table.draw(false);
                            $('.row-checkbox').prop('checked', false);
                            $('#select-all').prop('checked', false);
                            updateBulkDelete();
                            if (response.success) {
                                if (typeof showNotification === 'function') showNotification('Success', response.message, 'success');
                            } else {
                                if (typeof showNotification === 'function') showNotification('Error', response.message, 'error');
                                else alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Delete failed.';
                            if (typeof showNotification === 'function') showNotification('Error', msg, 'error');
                            else alert(msg);
                        }
                    });
                };
            });
        </script>
    @endpush
@endsection