@extends('layouts.app')

@section('title', 'Shipping Zones')

@section('content')
    <div x-data="zonesManager()"
        @edit-zone.window="openEditModal($event.detail.id, $event.detail.name, $event.detail.country_code, $event.detail.region || '', $event.detail.is_active)"
        class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-primary-a0">Shipping Zones</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Define geographical regions to assign specific
                    shipping rates to them.</p>
            </div>
            <div>
                <button @click="openCreateModal()"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition translate-y-0 hover:-translate-y-px">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Zone
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
            <form method="POST" action="{{ route('shipping.zones.bulkDelete') }}" id="bulk-delete-form-el"
                class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 rounded-lg p-4 flex justify-between items-center">
                @csrf
                <div id="bulk-ids-container"></div>
                <span class="text-sm font-medium text-red-800 dark:text-red-300">
                    <span id="selected-count">0</span> zones selected
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
                                    Code</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                    Zone</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-primary-a0">
                                    Status</th>
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
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="isModalOpen" x-transition
                    class="inline-block align-bottom bg-white dark:bg-surface-tonal-a20 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form :action="formUrl" method="POST">
                        @csrf
                        <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">

                        <div
                            class="bg-white dark:bg-surface-tonal-a20 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b dark:border-surface-tonal-a30">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-primary-a0"
                                x-text="isEditing ? 'Edit Zone' : 'Add New Zone'"></h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Zone Name
                                        *</label>
                                    <input type="text" name="name" x-model="form.name" required
                                        class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 flex-1 dark:text-primary-a0 dark:border-gray-600">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country
                                            Code * (e.g. LK)</label>
                                        <input type="text" name="country_code" x-model="form.country_code" required
                                            class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0 dark:border-gray-600">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Region /
                                            State</label>
                                        <input type="text" name="region" x-model="form.region" placeholder="Optional"
                                            class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-surface-tonal-a30 dark:text-primary-a0 dark:border-gray-600">
                                    </div>
                                </div>

                                <div class="flex items-center mt-4">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                        x-model="form.is_active"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Zone
                                        is active</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-surface-tonal-a30 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Save
                                Zone</button>
                            <button type="button" @click="isModalOpen = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-surface-tonal-a20 dark:text-gray-300 dark:border-gray-600">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('zonesManager', () => ({
                    isModalOpen: false,
                    isEditing: false,
                    storeUrl: '{{ route("shipping.zones.store") }}',
                    updateBaseUrl: '{{ route("shipping.zones.update", ":id") }}',
                    form: { id: null, name: '', country_code: 'LK', region: '', is_active: true },

                    get formUrl() { return this.isEditing ? this.updateBaseUrl.replace(':id', this.form.id) : this.storeUrl; },

                    openCreateModal() {
                        this.isEditing = false;
                        this.form = { id: null, name: '', country_code: 'LK', region: '', is_active: true };
                        this.isModalOpen = true;
                    },

                    openEditModal(id, name, country, region, active) {
                        this.isEditing = true;
                        this.form = { id: id, name: name, country_code: country, region: region, is_active: active };
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
                        url: '{{ route('shipping.zones.datatable') }}'
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
                        { data: 'country_html', name: 'country_code', className: 'whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-gray-900 dark:text-primary-a0 sm:pl-6' },
                        { data: 'zone_html', name: 'name', className: 'px-3 py-4 text-sm text-gray-500 dark:text-gray-400' },
                        { data: 'status_html', name: 'is_active', className: 'whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400' },
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
                        searchPlaceholder: "Search zones...",
                        lengthMenu: "Show _MENU_",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No zones found",
                        emptyTable: "No shipping zones configured.",
                        processing: false,
                    },
                    autoWidth: false,
                    scrollX: false
                });

                $('#application-table').on('click', '.edit-btn', function () {
                    var rowData = table.row($(this).closest('tr')).data();
                    window.dispatchEvent(new CustomEvent('edit-zone', { detail: rowData.raw_data }));
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
                    if (!confirm('Are you sure you want to completely remove this zone?')) return;

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