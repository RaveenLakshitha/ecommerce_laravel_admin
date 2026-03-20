@extends('layouts.app')

@section('title', 'Shipping Rates')

@section('content')
    <div x-data="ratesManager()" @edit-rate.window="openEditModal($event.detail)"
        class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Shipping Rates</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure delivery prices based on zones, weights,
                    or cart subtotals.</p>
            </div>
            <div>
                <button @click="openCreateModal()"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Rate
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div id="bulk-delete-form" class="hidden mb-6">
            <form method="POST" action="{{ route('shipping.rates.bulkDelete') }}" id="bulk-delete-form-el"
                class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 rounded-lg p-4 flex justify-between items-center">
                @csrf
                <div id="bulk-ids-container"></div>
                <span class="text-sm font-medium text-red-800 dark:text-red-300">
                    <span id="selected-count">0</span> rates selected
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
                    class="shadow-sm ring-1 ring-black ring-opacity-5 sm:rounded-lg overflow-hidden border border-gray-200 dark:border-surface-tonal-a30">
                    <table id="application-table" class="min-w-full divide-y divide-gray-300 dark:divide-gray-700"
                        style="width:100%">
                        <thead class="bg-gray-50 dark:bg-surface-tonal-a20">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left all sm:pl-6" style="width: 50px;">
                                    <input type="checkbox" id="select-all"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0 sm:pl-6">
                                    Rate Name</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                    Zone</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                    Amount</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                    Conditions</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-surface-tonal-a10 [&>tr]:group">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="isModalOpen" x-transition
                    class="inline-block align-bottom bg-white dark:bg-surface-tonal-a20 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                    <form :action="formUrl" method="POST">
                        @csrf
                        <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">

                        <div
                            class="bg-white dark:bg-surface-tonal-a20 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b dark:border-surface-tonal-a30">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-primary-a0"
                                x-text="isEditing ? 'Edit Rate' : 'Add New Rate'"></h3>

                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate Name
                                        *</label>
                                    <input type="text" name="name" x-model="form.name" required
                                        placeholder="e.g. Standard Shipping"
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Zone
                                        *</label>
                                    <select name="shipping_zone_id" x-model="form.shipping_zone_id" required
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                        <option value="">Select a Zone</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }} ({{ $zone->country_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Courier
                                        (Optional)</label>
                                    <select name="courier_id" x-model="form.courier_id"
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                        <option value="">Any internal provider</option>
                                        @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Flat Rate
                                        Amount (Rs.) *</label>
                                    <input type="number" step="0.01" name="rate_amount" x-model="form.rate_amount" required
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                </div>

                                <div class="sm:col-span-2 pt-4">
                                    <h4
                                        class="text-sm font-bold text-gray-700 dark:text-gray-300 border-b pb-2 dark:border-gray-600">
                                        Conditions (Optional)</h4>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-400">Min Weight (kg)</label>
                                    <input type="number" step="0.01" name="min_weight" x-model="form.min_weight"
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 text-white">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-400">Max Weight (kg)</label>
                                    <input type="number" step="0.01" name="max_weight" x-model="form.max_weight"
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 text-white">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-400">Min Order Price
                                        (Rs.)</label>
                                    <input type="number" step="0.01" name="min_price" x-model="form.min_price"
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 text-white">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-400">Max Order Price
                                        (Rs.)</label>
                                    <input type="number" step="0.01" name="max_price" x-model="form.max_price"
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 text-white">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-green-600 dark:text-green-500">Free
                                        Shipping Threshold overrides rate (Rs.)</label>
                                    <input type="number" step="0.01" name="free_shipping_threshold"
                                        x-model="form.free_shipping_threshold" placeholder="e.g. 5000"
                                        class="mt-1 block w-full sm:text-sm border-green-300 rounded-md focus:border-green-500 focus:ring-green-500 dark:bg-surface-tonal-a30 dark:text-primary-a0">
                                </div>

                                <div class="sm:col-span-2 mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" x-model="form.is_active"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Rate is active</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 dark:bg-surface-tonal-a30 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t dark:border-gray-600">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Save
                                Rate</button>
                            <button type="button" @click="isModalOpen = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-surface-tonal-a20 dark:text-gray-300">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('ratesManager', () => ({
                    isModalOpen: false,
                    isEditing: false,
                    storeUrl: '{{ route("shipping.rates.store") }}',
                    updateBaseUrl: '{{ route("shipping.rates.update", ":id") }}',
                    form: { id: null, shipping_zone_id: '', courier_id: '', name: '', rate_amount: '', min_weight: '', max_weight: '', min_price: '', max_price: '', free_shipping_threshold: '', is_active: true },

                    get formUrl() { return this.isEditing ? this.updateBaseUrl.replace(':id', this.form.id) : this.storeUrl; },

                    openCreateModal() {
                        this.isEditing = false;
                        this.form = { id: null, shipping_zone_id: '', courier_id: '', name: '', rate_amount: '', min_weight: '', max_weight: '', min_price: '', max_price: '', free_shipping_threshold: '', is_active: true };
                        this.isModalOpen = true;
                    },

                    openEditModal(rate) {
                        this.isEditing = true;
                        this.form = { ...rate };
                        this.form.is_active = rate.is_active == 1; // Parse boolean
                        this.isModalOpen = true;
                    }
                }));
            });

            document.addEventListener('DOMContentLoaded', function () {
                const table = $('#application-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ route('shipping.rates.datatable') }}'
                    },
                    order: [[1, 'asc']],
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
                        { data: 'rate_html', name: 'name', className: 'whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-gray-900 dark:text-primary-a0 sm:pl-6' },
                        { data: 'zone_html', name: 'shipping_zone_id', className: 'px-3 py-4 text-sm text-gray-500 dark:text-gray-400' },
                        { data: 'amount_html', name: 'rate_amount', className: 'whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900 dark:text-primary-a0' },
                        { data: 'conditions_html', searchable: false, orderable: false, className: 'px-3 py-4 text-sm text-gray-500 dark:text-gray-400' },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6',
                            render: function (data, type, row) {
                                return `
                                                    <div class="flex items-center justify-end gap-3 transition-opacity">
                                                        <button type="button" class="edit-btn text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20" title="Edit">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </button>
                                                        <button type="button" onclick="confirmDelete('${row.delete_url}')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
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
                        searchPlaceholder: "Search rates...",
                        lengthMenu: "Show _MENU_",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No rates found",
                        emptyTable: "No shipping rates configured.",
                        processing: false,
                    },
                    autoWidth: false,
                    scrollX: false
                });

                $('#application-table').on('click', '.edit-btn', function () {
                    var rowData = table.row($(this).closest('tr')).data();
                    window.dispatchEvent(new CustomEvent('edit-rate', { detail: rowData.raw_data }));
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
                    if (!confirm('Are you sure you want to completely remove this rate?')) return;

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