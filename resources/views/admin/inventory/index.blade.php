@extends('layouts.app')

@section('title', 'Inventory Overview')

@section('content')
    <div x-data="inventory()" class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Inventory Management</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Monitor stock levels, track out-of-stock items, and
                    adjust inventory manually.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="bulk-delete-form" class="hidden mb-6">
            <form method="POST" action="{{ route('inventory.bulkDelete') }}" id="bulk-delete-form-el"
                class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 rounded-lg p-4 flex justify-between items-center">
                @csrf
                <div id="bulk-ids-container"></div>
                <span class="text-sm font-medium text-red-800 dark:text-red-300">
                    <span id="selected-count">0</span> variants selected
                </span>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                    Delete Selected
                </button>
            </form>
        </div>

        <div
            class="bg-white dark:bg-surface-tonal-a20 rounded-xl shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="application-table" class="w-full divide-y divide-gray-200 dark:divide-gray-700 display nowrap"
                    style="width:100%">
                    <thead class="bg-gray-50 dark:bg-surface-tonal-a10">
                        <tr>
                            <th class="px-6 py-4 text-left" style="width: 50px;">
                                <input type="checkbox" id="select-all"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                                Product</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                                SKU</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                                Available</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-surface-tonal-a20 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Adjust Stock Modal -->
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="isModalOpen" x-transition.opacity
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-surface-tonal-a20 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                    <form :action="adjustUrl" method="POST">
                        @csrf
                        <div
                            class="bg-white dark:bg-surface-tonal-a20 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b dark:border-surface-tonal-a30">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-primary-a0"
                                        id="modal-title">
                                        Adjust Stock: <span x-text="variantName" class="font-bold"></span>
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Current Stock: <span
                                                class="font-semibold text-gray-900 dark:text-primary-a0"
                                                x-text="currentQuantity"></span></p>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adjustment
                                                Type</label>
                                            <select name="adjustment_type" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                                <option value="add">Add stock (+)</option>
                                                <option value="subtract">Subtract stock (-)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                            <input type="number" name="quantity" min="1" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0"
                                                placeholder="e.g. 5">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason
                                                / Notes (Optional)</label>
                                            <input type="text" name="notes"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0"
                                                placeholder="e.g. Damaged goods, found in warehouse...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-surface-tonal-a30 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Save Adjustment
                            </button>
                            <button type="button" @click="isModalOpen = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-surface-tonal-a20 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Dispatch Alpine events
            function openAdjustModal(id, name, qty) {
                window.dispatchEvent(new CustomEvent('open-adjust-modal', {
                    detail: {
                        id: id,
                        name: name,
                        qty: qty
                    }
                }));
            }

            document.addEventListener('alpine:init', () => {
                Alpine.data('inventory', () => ({
                    isModalOpen: false,
                    variantId: null,
                    variantName: '',
                    currentQuantity: 0,
                    baseUrl: '{{ route("inventory.adjust", ":id") }}',

                    get adjustUrl() {
                        return this.baseUrl.replace(':id', this.variantId);
                    },

                    init() {
                        window.addEventListener('open-adjust-modal', (e) => {
                            this.variantId = e.detail.id;
                            this.variantName = e.detail.name;
                            this.currentQuantity = e.detail.qty;
                            this.isModalOpen = true;
                        });
                    }
                }));
            });

            document.addEventListener('DOMContentLoaded', function () {
                $('#application-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: '{{ route('inventory.datatable') }}',
                    order: [[1, 'desc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false },
                        { targets: -1, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'id',
                            name: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">`,
                            className: 'px-6 py-4 text-left',
                            orderable: false
                        },
                        { data: 'product_html', name: 'product.name' },
                        { data: 'sku_html', name: 'sku' },
                        { data: 'status_html', name: 'status', searchable: false, orderable: false },
                        { data: 'available_html', name: 'stock_quantity' },
                        { data: 'actions_html', orderable: false, searchable: false }
                    ],
                    language: {
                        search: "",
                        searchPlaceholder: "Search items..."
                    },
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
                            $('#application-table').DataTable().draw(false);
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
                            $('#application-table').DataTable().draw(false);
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