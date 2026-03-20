@extends('layouts.app')

@section('title', __('file.orders') ?? 'Orders')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                    {{ __('file.orders') ?? 'Orders' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage store orders and fulfillment
                </p>
            </div>
            <button onclick="openFilterDrawer()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-surface-tonal-a0 border border-gray-300 dark:border-surface-tonal-a30 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a10 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                Filters
            </button>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div id="bulk-delete-form" class="hidden mb-6">
            <form method="POST" action="{{ route('orders.bulkDelete') }}" id="bulk-delete-form-el"
                class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 rounded-lg p-4 flex justify-between items-center">
                @csrf
                <div id="bulk-ids-container"></div>
                <span class="text-sm font-medium text-red-800 dark:text-red-300">
                    <span id="selected-count">0</span> {{ __('file.orders_selected') ?? 'orders selected' }}
                </span>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                    {{ __('file.delete_selected') ?? 'Delete Selected' }}
                </button>
            </form>
        </div>

        <div
            class="bg-white dark:bg-surface-tonal-a10 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="application-table" class="w-full divide-y divide-gray-200 dark:divide-surface-tonal-a30 nowrap"
                    style="width:100%">
                    <thead
                        class="bg-gray-50 dark:bg-surface-tonal-a10 border-b border-gray-200 dark:border-surface-tonal-a30">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-right all pr-6" style="width: 80px; min-width: 80px;">
                                <input type="checkbox" id="select-all"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Order</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Date</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Customer</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Status</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Payment</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Total</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white dark:bg-surface-tonal-a10 divide-y divide-gray-200 dark:divide-surface-tonal-a30 [&>tr]:group">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter Drawer -->
    <div id="filter-drawer" class="fixed inset-0 z-50 hidden overflow-hidden">
        <div id="filter-overlay" class="absolute inset-0 bg-gray-900/60 dark:bg-black/80 backdrop-blur-sm"
            onclick="closeFilterDrawer()"></div>
        <div id="filter-panel"
            class="absolute inset-x-0 bottom-0 md:inset-y-0 md:right-0 md:left-auto w-full md:max-w-md bg-white dark:bg-surface-tonal-a0 shadow-2xl flex flex-col h-[90vh] md:h-full rounded-t-3xl md:rounded-none">
            <div class="md:hidden flex justify-center pt-4 pb-2">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
            </div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-surface-tonal-a20">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-primary-a0">Filter Orders</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Refine the data table</p>
                </div>
                <button onclick="closeFilterDrawer()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto overscroll-contain px-5 py-5 text-sm">
                <form id="filter-form" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order Status</label>
                        <select id="filter-status"
                            class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-surface-tonal-a30 rounded-lg focus:ring-2 focus:ring-accent dark:focus:ring-accent/50 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0 transition-shadow outline-none">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment
                            Status</label>
                        <select id="filter-payment"
                            class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-surface-tonal-a30 rounded-lg focus:ring-2 focus:ring-accent dark:focus:ring-accent/50 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0 transition-shadow outline-none">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">From</label>
                                <input type="date" id="filter-date-from"
                                    class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-surface-tonal-a30 rounded-lg focus:ring-2 focus:ring-accent dark:focus:ring-accent/50 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0 transition-shadow outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">To</label>
                                <input type="date" id="filter-date-to"
                                    class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-surface-tonal-a30 rounded-lg focus:ring-2 focus:ring-accent dark:focus:ring-accent/50 focus:border-transparent dark:bg-surface-tonal-a10 dark:text-primary-a0 transition-shadow outline-none">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div
                class="px-5 py-3 bg-gray-50 dark:bg-surface-tonal-a10 border-t border-gray-200 dark:border-surface-tonal-a20">
                <div class="flex gap-3">
                    <button type="button" id="clear-filters"
                        class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-surface-tonal-a20 dark:hover:bg-surface-tonal-a30 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-lg transition">
                        Clear all
                    </button>
                    <button type="submit" form="filter-form"
                        class="flex-1 px-4 py-2 bg-accent hover:opacity-90 dark:bg-accent text-gray-900 dark:text-gray-900 text-sm font-medium rounded-lg transition shadow-sm focus:ring-2 focus:ring-accent/50 outline-none">
                        Apply Filters
                    </button>
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
                        url: '{{ route('orders.datatable') }}',
                        data: function (d) {
                            d.status = $('#filter-status').val();
                            d.payment_status = $('#filter-payment').val();
                            d.date_from = $('#filter-date-from').val();
                            d.date_to = $('#filter-date-to').val();
                        }
                    },
                    order: [[2, 'desc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false },
                        { targets: -1, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">`,
                            className: 'text-center',
                            orderable: false
                        },
                        { data: 'order_number_html', name: 'order_number' },
                        { data: 'date_html', name: 'placed_at' },
                        { data: 'customer_html', name: 'customer_name' },
                        { data: 'status_html', name: 'status' },
                        { data: 'payment_html', name: 'payment_status' },
                        { data: 'total_amount_html', name: 'total_amount', className: 'text-right' },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'text-right whitespace-nowrap',
                            render: function (data, type, row) {
                                return `
                                                                    <div class="flex items-center justify-end gap-3 transition-opacity">
                                                                        <a href="${row.show_url}" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 p-1.5 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20" title="View Order">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                                        </a>
                                                                        <a href="${row.invoice_url}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20" title="Print Invoice">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                                        </a>
                                                                        ${row.delete_url ? `
                                                                            <button type="button" onclick="confirmDelete('${row.delete_url}')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete Order">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                                </svg>
                                                                            </button>
                                                                        ` : ''}
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
                        searchPlaceholder: "Search orders...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No items found",
                        emptyTable: "No orders found matching your criteria.",
                        processing: false,
                    },
                    autoWidth: false,
                    scrollX: false
                });

                const filterDrawer = document.getElementById('filter-drawer');
                let bodyScrollPos = 0;

                window.openFilterDrawer = function () {
                    bodyScrollPos = window.pageYOffset;
                    document.body.style.position = 'fixed';
                    document.body.style.top = `-${bodyScrollPos}px`;
                    document.body.style.width = '100%';
                    filterDrawer.classList.remove('hidden');
                };

                window.closeFilterDrawer = function () {
                    filterDrawer.classList.add('hidden');
                    document.body.style.position = '';
                    document.body.style.top = '';
                    document.body.style.width = '';
                    window.scrollTo(0, bodyScrollPos);
                };

                // Close on escape
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !filterDrawer.classList.contains('hidden')) {
                        closeFilterDrawer();
                    }
                });

                // Attach custom filter form submission straight to DataTables draw
                $('#filter-form').on('submit', function (e) {
                    e.preventDefault();
                    table.draw();
                    closeFilterDrawer();
                });

                // Handle clear filters
                $('#clear-filters').on('click', function () {
                    $('#filter-form')[0].reset();
                    table.search('').draw(); // also clear DataTables default search
                    closeFilterDrawer();
                });

                // Checkbox utilities
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
                    if (!confirm('{{ __("file.confirm_delete_selected_items") ?? "Are you sure you want to delete the selected items?" }}')) return;

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
                                else alert(response.message);
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
                    if (!confirm('{{ __("file.confirm_delete_item") ?? "Are you sure you want to delete this item?" }}')) return;

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
                                else alert(response.message);
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