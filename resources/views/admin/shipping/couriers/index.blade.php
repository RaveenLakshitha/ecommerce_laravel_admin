@extends('layouts.app')

@section('title', __('file.logistics_providers') ?? 'Logistics Providers')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                {{ __('file.logistics_providers') ?? 'Logistics Providers' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('file.manage_logistics_providers') ?? 'Coordinate delivery partnerships and monitor fulfillment logistics' }}
            </p>
        </div>
        <div>
            <a href="{{ route('shipping.couriers.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('file.add_provider') ?? 'Add Provider' }}
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
        <form method="POST" action="{{ route('shipping.couriers.bulkDelete') }}" id="bulk-delete-form-el"
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">Provider</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">Features</th>
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
            processing: true, serverSide: true, responsive: false,
            ajax: { url: '{{ route('shipping.couriers.datatable') }}' },
            order: [[1, 'asc']],
            columnDefs: [ { targets: [0, 4], orderable: false, searchable: false } ],
            columns: [
                {
                    data: 'id',
                    render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">`,
                    className: 'text-center'
                },
                { 
                    data: 'name', name: 'name',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center gap-3 py-1">
                                <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tighter leading-tight">${data}</span>
                                    <span class="text-[10px] text-gray-400 font-medium italic">Logistics Partner</span>
                                </div>
                            </div>
                        `;
                    }
                },
                { 
                    data: 'is_active', name: 'is_active',
                    render: function(data) {
                        const active = data == 1;
                        return `<span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase ${active ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400'}">${active ? 'Active' : 'Inactive'}</span>`;
                    }
                },
                { 
                    data: 'features_html', searchable: false, orderable: false,
                    render: function(data) {
                        return `<div class="flex flex-wrap gap-1 max-w-md py-1">${data || '<span class="text-[10px] text-gray-400 italic">No Features</span>'}</div>`;
                    } 
                },
                {
                    data: null, className: 'text-right whitespace-nowrap px-6 py-4',
                    render: (data, type, row) => `
                        <div class="flex items-center justify-end gap-3 transition-opacity">
                            <a href="${row.edit_url}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20" title="Edit">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <button type="button" onclick="confirmDelete('${row.delete_url}')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>`
                }
            ],
            layout: {
                topStart: {
                    buttons: [
                        { extend: 'pageLength', className: 'btn btn-sm btn-light' },
                        { extend: 'collection', text: "Export", className: 'btn btn-sm btn-dark', buttons: ['copy', 'excel', 'csv', 'pdf', 'print'] }
                    ]
                },
                topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging'
            },
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "", searchPlaceholder: "Search providers...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No items found",
                emptyTable: "No providers found.",
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