@extends('layouts.app')

@section('title', __('file.customers') ?? 'Customers')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                {{ __('file.customers') ?? 'Customers Management' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('file.manage_customers') ?? 'Monitor customer registered metrics and lifecycle engagement' }}
            </p>
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
        <form method="POST" action="{{ route('customers.bulkDelete') }}" id="bulk-delete-form-el"
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">Customer</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Email</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Orders</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Total Spent</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-surface-tonal-a10 divide-y divide-gray-200 dark:divide-surface-tonal-a30 [&>tr]:group transition-all">
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
                url: '{{ route('customers.datatable') }}'
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
                    data: 'name_html', name: 'first_name',
                    render: function(data, type, row) {
                        return `
                        <div class="flex items-center gap-3 py-1">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-tighter border border-indigo-100 dark:border-indigo-500/20 flex-shrink-0">
                                ${row.first_name ? row.first_name.charAt(0) : 'C'}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight uppercase">${data}</span>
                                <span class="text-[10px] text-gray-400 font-medium tracking-tight">Verified Identity</span>
                            </div>
                        </div>`;
                    }
                },
                { 
                    data: 'email', name: 'email',
                    render: data => `<span class="text-xs font-mono text-gray-700 dark:text-gray-300 italic underline decoration-indigo-500/10">${data}</span>`
                },
                { 
                    data: 'orders_count', name: 'orders_count',
                    render: data => `<span class="text-sm font-medium text-gray-900 dark:text-white">${data || 0}</span>`
                },
                { 
                    data: 'total_spent_html', name: 'total_spent',
                    render: data => `<span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 italic">${data}</span>`
                },
                { data: 'status_html', name: 'status', className: 'text-center' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-right whitespace-nowrap',
                    render: function (data, type, row) {
                        return `
                        <div class="flex items-center justify-end gap-3 transition-opacity">
                            <a href="${row.show_url}" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 p-1.5 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20" title="Inspect">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <button type="button" onclick="confirmDelete('${row.delete_url}')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete">
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
                searchPlaceholder: "Search customers...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No items found",
                emptyTable: "No customers found.",
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