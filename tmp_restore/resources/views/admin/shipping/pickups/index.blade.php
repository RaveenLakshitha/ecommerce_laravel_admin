@extends('layouts.app')

@section('title', __('file.pickup_locations') ?? 'Pickup Locations')

@section('content')
    <div x-data="pickupManager()" @edit-pickup.window="openEditModal($event.detail)"
        class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">
                    {{ __('file.pickup_locations') ?? 'Pickup Locations' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('file.manage_pickup_locations') ?? 'Manage localized pickup points for click-and-collect fulfillment' }}
                </p>
            </div>
            <div>
                <button @click="openCreateModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('file.add_location') ?? 'Add Location' }}
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
            <form method="POST" action="{{ route('shipping.pickups.bulkDelete') }}" id="bulk-delete-form-el"
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
                                Location Name</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider all">
                                Address</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Contact</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider desktop">
                                Status</th>
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

                <div class="inline-block w-full max-w-xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-surface-tonal-a10 rounded-2xl shadow-xl border border-gray-200 dark:border-surface-tonal-a30"
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
                                x-text="isEditing ? 'Edit Pickup Location' : 'Add Pickup Location'"></h3>
                            <button type="button" @click="isModalOpen = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-4">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location Name
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="form.name" required placeholder="e.g. Main Showroom"
                                    class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 1
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="address_line_1" x-model="form.address_line_1" required
                                        placeholder="Street address"
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line
                                        2</label>
                                    <input type="text" name="address_line_2" x-model="form.address_line_2"
                                        placeholder="Suite, floor, etc."
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-semibold">
                                <div class="sm:col-span-2 space-y-1 text-sm font-medium">
                                    <label class="block text-gray-700 dark:text-gray-300">City <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="city" x-model="form.city" required placeholder="City"
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                                </div>
                                <div class="space-y-1 text-sm font-medium">
                                    <label class="block text-gray-700 dark:text-gray-300">State</label>
                                    <input type="text" name="state" x-model="form.state" placeholder="State"
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                                </div>
                                <div class="space-y-1 text-sm font-medium">
                                    <label class="block text-gray-700 dark:text-gray-300">ZIP</label>
                                    <input type="text" name="postal_code" x-model="form.postal_code" placeholder="Code"
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                    <input type="text" name="phone" x-model="form.phone" placeholder="Contact number"
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <input type="email" name="email" x-model="form.email" placeholder="Contact email"
                                        class="block w-full rounded-lg border-gray-300 dark:border-surface-tonal-a30 bg-white dark:bg-surface-tonal-a20 text-sm focus:ring-indigo-500">
                                </div>
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
                                Location</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('pickupManager', () => ({
                    isModalOpen: false, isEditing: false, storeUrl: '{{ route("shipping.pickups.store") }}', updateBaseUrl: '{{ route("shipping.pickups.update", ":id") }}',
                    form: { id: null, name: '', address_line_1: '', address_line_2: '', city: '', state: '', postal_code: '', country: 'LK', phone: '', email: '', is_active: true },
                    get formUrl() { return this.isEditing ? this.updateBaseUrl.replace(':id', this.form.id) : this.storeUrl; },
                    openCreateModal() { this.isEditing = false; this.form = { id: null, name: '', address_line_1: '', address_line_2: '', city: '', state: '', postal_code: '', country: 'LK', phone: '', email: '', is_active: true }; this.isModalOpen = true; },
                    openEditModal(loc) { this.isEditing = true; this.form = { ...loc }; this.form.is_active = loc.is_active == 1; this.isModalOpen = true; }
                }));
            });

            document.addEventListener('DOMContentLoaded', function () {
                const table = $('#application-table').DataTable({
                    processing: true, serverSide: true, responsive: false,
                    ajax: { url: '{{ route('shipping.pickups.datatable') }}' },
                    order: [[1, 'asc']],
                    columnDefs: [{ targets: [0, 5], orderable: false, searchable: false }],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all">`,
                            className: 'text-center'
                        },
                        {
                            data: 'name', name: 'name',
                            render: (data) => `
                                <div class="flex items-center gap-3 py-1">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tighter leading-tight">${data}</span>
                                        <span class="text-[10px] text-gray-400 font-medium italic">Fulfillment Center</span>
                                    </div>
                                </div>
                            `
                        },
                        {
                            data: 'address_html', searchable: false, orderable: false,
                            className: 'text-xs font-medium text-gray-500 dark:text-gray-400'
                        },
                        {
                            data: 'contact_html', searchable: false, orderable: false,
                            className: 'text-xs font-bold text-gray-900 dark:text-white italic'
                        },
                        {
                            data: 'status_html', name: 'is_active',
                            render: (data, type, row) => {
                                const active = row.raw_data.is_active == 1;
                                return `<span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase ${active ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400'}">${active ? 'Linked' : 'Offline'}</span>`;
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
                        search: "", searchPlaceholder: "Search locations...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No items found",
                        emptyTable: "No locations found.",
                        processing: false,
                    },
                    autoWidth: false,
                    scrollX: false
                });

                $('#application-table').on('click', '.edit-btn', function () {
                    var rowData = table.row($(this).closest('tr')).data();
                    window.dispatchEvent(new CustomEvent('edit-pickup', { detail: rowData.raw_data }));
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