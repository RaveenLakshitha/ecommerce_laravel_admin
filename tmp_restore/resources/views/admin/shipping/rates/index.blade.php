@extends('layouts.app')

@section('title', __('file.shipping_rates') ?? 'Shipping Rates')

@section('content')
    <div x-data="ratesManager()" @edit-rate.window="openEditModal($event.detail)"
        class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                    {{ __('file.shipping_rates') ?? 'Shipping Rates' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('file.manage_shipping_rates') ?? 'Configure dynamic delivery pricing based on weight and destination' }}
                </p>
            </div>
            <div>
                <button @click="openCreateModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('file.add_rate') ?? 'Add Rate' }}
                </button>
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
            <form method="POST" action="{{ route('shipping.rates.bulkDelete') }}" id="bulk-delete-form-el"
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
                                Rate Name</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Zone</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Amount</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Conditions</th>
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

        {{-- Modal --}}
        <div x-show="isModalOpen" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="isModalOpen = false">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-surface-tonal-a10 rounded-2xl shadow-xl border border-gray-200 dark:border-surface-tonal-a30"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <form :action="formUrl" method="POST">
                        @csrf
                        <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">

                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white"
                                x-text="isEditing ? 'Edit Shipping Rate' : 'Add Shipping Rate'"></h3>
                            <button type="button" @click="isModalOpen = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-5">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="form.name" required
                                    placeholder="e.g. Standard Shipping"
                                    class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Zone
                                        <span class="text-red-500">*</span></label>
                                    <select name="shipping_zone_id" x-model="form.shipping_zone_id" required
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                                        <option value="">Select Zone</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Courier</label>
                                    <select name="courier_id" x-model="form.courier_id"
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                                        <option value="">Any Courier</option>
                                        @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate Amount (Rs.)
                                    <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="rate_amount" x-model="form.rate_amount" required
                                    class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                            </div>

                            <div class="pt-4 border-t border-gray-100 dark:border-surface-tonal-a30">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Conditions
                                    (Optional)</h4>
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-gray-500 mb-1">Min Weight (kg)</label>
                                            <input type="number" step="0.01" name="min_weight" x-model="form.min_weight"
                                                class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                                        </div>
                                        <div>
                                            <label class="block text-gray-500 mb-1">Max Weight (kg)</label>
                                            <input type="number" step="0.01" name="max_weight" x-model="form.max_weight"
                                                class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-gray-500 mb-1">Min Order Price (Rs.)</label>
                                            <input type="number" step="0.01" name="min_price" x-model="form.min_price"
                                                class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                                        </div>
                                        <div>
                                            <label class="block text-gray-500 mb-1">Max Order Price (Rs.)</label>
                                            <input type="number" step="0.01" name="max_price" x-model="form.max_price"
                                                class="w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                                <label class="block text-sm font-bold text-emerald-800 dark:text-emerald-400 mb-1">Free
                                    Shipping Threshold (Rs.)</label>
                                <input type="number" step="0.01" name="free_shipping_threshold"
                                    x-model="form.free_shipping_threshold" placeholder="Value for free shipping"
                                    class="block w-full rounded-lg border-emerald-200 dark:border-emerald-800/50 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-emerald-500">
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="is_active" name="is_active" value="1" x-model="form.is_active"
                                    class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                                <label for="is_active"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 text-sm">Active</label>
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-surface-tonal-a10 border-t border-gray-200 dark:border-surface-tonal-a30 flex gap-3">
                            <button type="button" @click="isModalOpen = false"
                                class="flex-1 px-4 py-2 bg-white dark:bg-surface-tonal-a10 border border-gray-300 dark:border-surface-tonal-a30 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Cancel</button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg transition hover:bg-indigo-700">Save
                                Rate</button>
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
                    isModalOpen: false, isEditing: false, storeUrl: '{{ route("shipping.rates.store") }}', updateBaseUrl: '{{ route("shipping.rates.update", ":id") }}',
                    form: { id: null, shipping_zone_id: '', courier_id: '', name: '', rate_amount: '', min_weight: '', max_weight: '', min_price: '', max_price: '', free_shipping_threshold: '', is_active: true },
                    get formUrl() { return this.isEditing ? this.updateBaseUrl.replace(':id', this.form.id) : this.storeUrl; },
                    openCreateModal() { this.isEditing = false; this.form = { id: null, shipping_zone_id: '', courier_id: '', name: '', rate_amount: '', min_weight: '', max_weight: '', min_price: '', max_price: '', free_shipping_threshold: '', is_active: true }; this.isModalOpen = true; },
                    openEditModal(rate) { this.isEditing = true; this.form = { ...rate }; this.form.is_active = rate.is_active == 1; this.isModalOpen = true; }
                }));
            });

            document.addEventListener('DOMContentLoaded', function () {
                const table = $('#application-table').DataTable({
                    processing: true, serverSide: true, responsive: false,
                    ajax: { url: '{{ route('shipping.rates.datatable') }}' },
                    order: [[1, 'asc']],
                    columnDefs: [{ targets: [0, 5], orderable: false, searchable: false }],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">`,
                            className: 'text-center'
                        },
                        {
                            data: 'rate_html', name: 'name',
                            render: (data, type, row) => `
                                <div class="flex flex-col py-1">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tighter leading-tight">${data}</span>
                                    <span class="text-[10px] text-gray-400 font-medium italic">${row.raw_data.courier ? row.raw_data.courier.name : 'Internal Logic'}</span>
                                </div>
                            `
                        },
                        {
                            data: 'zone_html', name: 'shipping_zone_id',
                            render: data => `<span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">${data}</span>`
                        },
                        {
                            data: 'amount_html', name: 'rate_amount',
                            render: data => `<span class="text-sm font-black text-gray-900 dark:text-white">${data}</span>`
                        },
                        {
                            data: 'conditions_html', searchable: false, orderable: false,
                            render: function (data) {
                                return `<div class="flex flex-wrap gap-1 max-w-md py-1">${data || '<span class="text-[10px] text-gray-400 italic">No Conditions</span>'}</div>`;
                            }
                        },
                        {
                            data: null, className: 'text-right whitespace-nowrap px-6 py-4',
                            render: (data, type, row) => `
                                <div class="flex items-center justify-end gap-3 transition-opacity">
                                    <button type="button" class="edit-btn text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20" title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
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
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "", searchPlaceholder: "Search rates...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No items found",
                        emptyTable: "No rates found.",
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