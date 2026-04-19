@extends('layouts.app')

@section('title', __('file.discount_rules') ?? 'Discount Rules')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                {{ __('file.discount_rules') ?? 'Discount Rules' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('file.manage_discount_rules') ?? 'Configure automatic pricing rules and flash sales' }}
            </p>
        </div>
        <a href="{{ route('discount-rules.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('file.add_rule') ?? 'Add Rule' }}
        </a>
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
        <form method="POST" action="{{ route('discount-rules.bulkDelete') }}" id="bulk-delete-form-el"
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">Rule Information</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Promotion</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Priority</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Dates</th>
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
                url: '{{ route('discount-rules.datatable') }}'
            },
            order: [[4, 'desc']],
            columnDefs: [
                { targets: 0, orderable: false, searchable: false },
                { targets: -1, orderable: false, searchable: false }
            ],
            columns: [
                {
                    data: 'id',
                    render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">`,
                    className: 'text-center',
                    orderable: false
                },
                { data: 'status_html', name: 'is_active', searchable: false },
                { data: 'name_html', name: 'name' },
                { data: 'type_html', name: 'type', searchable: false },
                { data: 'priority', name: 'priority', className: 'text-center' },
                { data: 'dates_html', name: 'starts_at', searchable: false },
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
                            <button type="button" onclick="duplicateRule('${row.duplicate_url}')" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Duplicate">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                            </button>
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
                searchPlaceholder: "Search rules...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No items found",
                emptyTable: "No discount rules found.",
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

        window.duplicateRule = function (url) {
            if (!confirm('{{ __("file.confirm_duplicate") ?? "Create a copy of this rule?" }}')) return;
            $.ajax({ url: url, method: 'POST', data: { _token: '{{ csrf_token() }}' }, success: (res) => { if (res.redirect) window.location = res.redirect; else table.draw(false); } });
        };

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