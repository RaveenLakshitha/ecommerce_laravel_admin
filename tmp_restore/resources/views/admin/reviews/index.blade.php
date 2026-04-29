@extends('layouts.app')

@section('title', __('file.product_reviews') ?? 'Product Reviews')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                    {{ __('file.product_reviews') ?? 'Product Reviews' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('file.manage_reviews') ?? 'Moderate and curate customer feedback and sentiment' }}
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex text-green-700">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div id="bulk-delete-form" class="hidden mb-6">
            <form method="POST" action="{{ route('reviews.bulkDelete') }}" id="bulk-delete-form-el"
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

        <div
            class="bg-white dark:bg-surface-tonal-a10 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="application-table" class="w-full divide-y divide-gray-200 dark:divide-surface-tonal-a30 nowrap"
                    style="width:100%">
                    <thead
                        class="bg-gray-50 dark:bg-surface-tonal-a10 border-b border-gray-200 dark:border-surface-tonal-a30">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-right" style="width: 80px; min-width: 80px;">
                                <input type="checkbox" id="select-all"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Review</th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Customer</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white dark:bg-surface-tonal-a10 divide-y divide-gray-200 dark:divide-surface-tonal-a30 [&>tr]:group transition-all">
                    </tbody>
                </table>
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
                        url: '{{ route('reviews.datatable') }}'
                    },
                    order: [[4, 'desc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false },
                        { targets: 2, orderable: false, searchable: false },
                        { targets: -1, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">`,
                            className: 'text-center',
                            orderable: false
                        },
                        {
                            data: 'review_html', name: 'title',
                            render: function (data, type, row) {
                                return `
                                    <div class="max-w-md py-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2">${data}</div>
                                        <div class="flex items-center gap-0.5 mt-1">
                                            ${Array.from({ length: 5 }, (_, i) => `<svg class="w-3 h-3 ${i < row.rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200 dark:text-gray-700'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`).join('')}
                                        </div>
                                    </div>`;
                            }
                        },
                        {
                            data: 'status', name: 'status', className: 'text-center',
                            render: function (data) {
                                const colors = {
                                    'approved': 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                    'pending': 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                    'rejected': 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400'
                                };
                                const color = colors[data] || 'bg-gray-100 text-gray-700';
                                return `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${color}">${data}</span>`;
                            }
                        },
                        { data: 'customer_html', name: 'customer.first_name' },
                        { data: 'date', name: 'created_at' },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'text-right whitespace-nowrap',
                            render: function (data, type, row) {
                                let approveBtn = row.status !== 'approved' ? `<button type="button" onclick="updateStatus('${row.update_url}', 'approved')" class="text-green-600 hover:text-green-900 dark:text-green-400 p-1.5 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20" title="Approve"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>` : '';
                                let rejectBtn = row.status !== 'rejected' ? `<button type="button" onclick="updateStatus('${row.update_url}', 'rejected')" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 p-1.5 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20" title="Reject"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg></button>` : '';

                                return `
                                <div class="flex items-center justify-end gap-3 transition-opacity">
                                    ${approveBtn}
                                    ${rejectBtn}
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
                        searchPlaceholder: "Search reviews...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No items found",
                        emptyTable: "No reviews found.",
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

                window.updateStatus = function (url, status) {
                    $.ajax({
                        url: url, method: 'POST',
                        data: { _token: '{{ csrf_token() }}', _method: 'PATCH', status: status },
                        success: (res) => { if (res.success) { if (typeof showNotification === 'function') showNotification('Success', res.message, 'success'); table.draw(false); } }
                    });
                };

                $('#bulk-delete-form-el').on('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('{{ __("file.confirm_bulk_delete") ?? "Are you sure you want to delete the selected items?" }}')) return;

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

                window.confirmDelete = function (url) {
                    if (!confirm('{{ __("file.confirm_delete") ?? "Are you sure you want to delete this item?" }}')) return;

                    $.ajax({
                        url: url,
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