@extends('layouts.app')

@section('title', __('file.inventory') ?? 'Inventory')

@section('content')
    <div x-data="inventory()" class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                    {{ __('file.inventory') ?? 'Inventory' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('file.manage_inventory') ?? 'Monitor stock levels and execute inventory adjustments' }}
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
            <form method="POST" action="{{ route('inventory.bulkDelete') }}" id="bulk-delete-form-el"
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
                                Product / SKU</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Available Stock</th>
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

        {{-- Adjustment Modal --}}
        <div x-show="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
            style="display: none;">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="isModalOpen = false"></div>
            <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="relative w-full max-w-lg bg-white dark:bg-surface-tonal-a20 rounded-2xl shadow-2xl border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">

                <form :action="adjustUrl" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white"
                                    x-text="'Adjust Stock: ' + variantName"></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update quantity for SKU: <span
                                        x-text="variantSku"></span></p>
                            </div>
                            <button type="button" @click="isModalOpen = false"
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div
                                class="p-4 bg-gray-50 dark:bg-surface-tonal-a30 rounded-lg flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Stock</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white"
                                    x-text="currentQuantity"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action</label>
                                    <select name="adjustment_type" required
                                        class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 text-sm focus:ring-indigo-500">
                                        <option value="add">Add (+)</option>
                                        <option value="subtract">Subtract (-)</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                                    <input type="number" name="quantity" min="1" required
                                        class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 text-sm focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason
                                    (Optional)</label>
                                <textarea name="notes" rows="2"
                                    class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a30 text-sm focus:ring-indigo-500 resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-4 bg-gray-50 dark:bg-surface-tonal-a10 border-t border-gray-200 dark:border-surface-tonal-a30 flex justify-end gap-3">
                        <button type="button" @click="isModalOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-surface-tonal-a30 border border-gray-300 dark:border-surface-tonal-a30 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm">
                            Confirm Adjustment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openAdjustModal(id, name, sku, qty) {
                window.dispatchEvent(new CustomEvent('open-adjust-modal', { detail: { id: id, name: name, sku: sku, qty: qty } }));
            }

            document.addEventListener('alpine:init', () => {
                Alpine.data('inventory', () => ({
                    isModalOpen: false, variantId: null, variantName: '', variantSku: '', currentQuantity: 0,
                    baseUrl: '{{ route("inventory.adjust", ":id") }}',
                    get adjustUrl() { return this.baseUrl.replace(':id', this.variantId); },
                    init() {
                        window.addEventListener('open-adjust-modal', (e) => {
                            this.variantId = e.detail.id; this.variantName = e.detail.name;
                            this.variantSku = e.detail.sku; this.currentQuantity = e.detail.qty;
                            this.isModalOpen = true;
                        });
                    }
                }));
            });

            document.addEventListener('DOMContentLoaded', function () {
                const table = $('#application-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ route('inventory.datatable') }}'
                    },
                    order: [[1, 'desc']],
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
                        {
                            data: 'product_html', name: 'product.name',
                            render: (data, type, row) => `
                                <div class="flex flex-col py-1">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white uppercase">${data}</span>
                                    <span class="text-[11px] text-gray-500 font-mono">SKU: ${row.sku_html || 'N/A'}</span>
                                </div>`
                        },
                        {
                            data: 'status_html', name: 'status'
                        },
                        {
                            data: 'available_html', name: 'stock_quantity', className: 'text-center',
                            render: (data) => `<div class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">${data}</div>`
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'text-right whitespace-nowrap',
                            render: function (data, type, row) {
                                return `
                                <div class="flex items-center justify-end gap-3 transition-opacity">
                                    <button onclick="openAdjustModal(${row.id}, '${row.product.name.replace(/'/g, "\\'")}', '${row.sku_html}', ${row.stock_quantity})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20" title="Adjust Stock">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
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
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "",
                        searchPlaceholder: "Search stock...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No items found",
                        emptyTable: "No inventory records found.",
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