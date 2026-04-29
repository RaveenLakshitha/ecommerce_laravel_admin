@extends('layouts.app')

@section('title', __('file.pickup_locations') ?? 'Pickup Locations')

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">
            <nav class="admin-breadcrumb mt-6" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('file.dashboard') }}
                </a>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span class="active">{{ __('file.pickup_locations') ?? 'Pickup Locations' }}</span>
            </nav>

            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.pickup_locations') ?? 'Pickup Locations' }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.manage_pickup_locations') ?? 'Manage localized pickup points for click-and-collect fulfillment' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openPickupDrawer()" class="admin-btn-add">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('file.add_location') ?? 'Add Location' }}
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="admin-alert-success animate-fade-in-scale">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div id="bulk-delete-form" class="hidden animate-fade-in-scale sticky top-20 z-30 mb-6">
                <form method="POST" action="{{ route('shipping.pickups.bulkDelete') }}" id="bulk-delete-form-el"
                    class="admin-bulk-bar">
                    @csrf
                    <div id="bulk-ids-container" class="hidden"></div>
                    <div class="flex items-center gap-3">
                        <div class="selection-count" id="selected-count">0</div>
                        <span>{{ __('file.locations_selected') ?? 'Locations Selected' }}</span>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-red-600/20 active:scale-95 whitespace-nowrap border border-red-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('file.delete_selected') ?? 'Delete Selected' }}
                    </button>
                </form>
            </div>

            <div class="admin-card">
                <div class="overflow-x-auto">
                    <table id="application-table" class="w-full" style="width:100%">
                        <thead>
                            <tr>
                                <th class="!text-center !px-4" style="width: 50px; min-width: 50px;">
                                    <input type="checkbox" id="select-all"
                                        class="w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">
                                </th>
                                <th>{{ __('file.Location Name') }}</th>
                                <th>{{ __('file.Address') }}</th>
                                <th>{{ __('file.Contact') }}</th>
                                <th>{{ __('file.Status') }}</th>
                                <th class="!text-right">{{ __('file.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('drawers')
        {{-- Pickup Location Drawer --}}
        <div id="pickup-drawer" class="fixed inset-0 z-[9999] hidden overflow-hidden">
            <div id="pickup-drawer-overlay"
                class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"
                onclick="closePickupDrawer()"></div>
            <div id="pickup-drawer-panel"
                class="absolute inset-y-0 right-0 w-full md:max-w-lg bg-white dark:bg-surface-tonal-a20 shadow-2xl transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col">
                
                <div class="flex items-center justify-between px-8 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                    <div>
                        <h3 id="pickup-drawer-title" class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ __('file.add_new_location') ?? 'Add New Pickup Location' }}
                        </h3>
                        <p id="pickup-drawer-subtitle" class="text-sm font-medium text-primary mt-1">
                            {{ __('file.manage_localized_pickup_points') ?? 'Manage localized pickup points for click-and-collect' }}
                        </p>
                    </div>
                    <button type="button" onclick="closePickupDrawer()"
                        class="p-2.5 rounded-xl hover:bg-white dark:hover:bg-white/10 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="pickup-drawer-content" class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <div class="flex items-center justify-center h-full">
                        <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script type="module">
            const pickupDrawer = document.getElementById('pickup-drawer');
            const pickupOverlay = document.getElementById('pickup-drawer-overlay');
            const pickupPanel = document.getElementById('pickup-drawer-panel');
            const pickupContent = document.getElementById('pickup-drawer-content');

            window.openPickupDrawer = (url = null) => {
                const isEdit = url && !url.includes('create');
                const titleEl = document.getElementById('pickup-drawer-title');
                const subtitleEl = document.getElementById('pickup-drawer-subtitle');
                
                titleEl.textContent = isEdit ? '{{ __("file.edit_pickup_location") ?? "Edit Pickup Location" }}' : '{{ __("file.add_new_location") ?? "Add New Pickup Location" }}';
                subtitleEl.textContent = isEdit ? '{{ __("file.update_location_details") ?? "Update localized pickup point details" }}' : '{{ __("file.create_new_location_entry") ?? "Create a new localized pickup point" }}';

                pickupDrawer.classList.remove('hidden');
                pickupContent.innerHTML = '<div class="flex items-center justify-center h-full"><div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div></div>';
                
                setTimeout(() => {
                    pickupOverlay.classList.replace('opacity-0', 'opacity-100');
                    pickupPanel.classList.remove('translate-x-full');
                }, 10);
                
                document.body.style.overflow = 'hidden';

                const fetchUrl = url || '{{ route("shipping.pickups.create") }}';
                fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(html => {
                        pickupContent.innerHTML = html;
                        setupPickupFormHandler();
                    })
                    .catch(err => {
                        pickupContent.innerHTML = `<div class="p-4 text-red-500 text-center">${err.message}</div>`;
                    });
            };

            window.closePickupDrawer = () => {
                pickupOverlay.classList.remove('opacity-100');
                pickupPanel.classList.add('translate-x-full');
                document.body.style.overflow = '';
                setTimeout(() => pickupDrawer.classList.add('hidden'), 500);
            };

            function setupPickupFormHandler() {
                const form = document.getElementById('pickup-drawer-form');
                if (!form) return;

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const loader = document.getElementById('pickup-drawer-loader');
                    const saveText = document.getElementById('pickup-drawer-save-text');

                    submitBtn.disabled = true;
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                    saveText.classList.add('invisible');

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            closePickupDrawer();
                            if (window.pickupTable) window.pickupTable.draw(false);
                            if (typeof showNotification === 'function') showNotification('{{ __("file.Success") }}', res.message, 'success');
                        } else {
                            if (typeof showNotification === 'function') showNotification('{{ __("file.Error") }}', res.message || 'Something went wrong', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                        saveText.classList.remove('invisible');
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                if (!window.jQuery) return;
                const $ = window.jQuery;
                
                const table = $('#application-table').DataTable({
                    processing: true, serverSide: true,
                    ajax: { url: '{{ route('shipping.pickups.datatable') }}' },
                    order: [[1, 'asc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false },
                        { targets: -1, orderable: false, searchable: false }
                    ],
                    columns: [
                        {
                            data: 'id',
                            render: data => `<input type="checkbox" name="ids[]" value="${data}" class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-surface-tonal-a30 text-gray-900 focus:ring-gray-300">`,
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
                            data: null, 
                            className: 'text-right whitespace-nowrap !px-4',
                            render: function (data, type, row) {
                                return `
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick="openPickupDrawer('{{ route('shipping.pickups.edit', ':id') }}'.replace(':id', row.id))" 
                                            class="p-2 rounded-xl text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-primary/10 transition-all group/btn" 
                                            title="{{ __('file.edit') }}">
                                            <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="confirmDelete('${row.delete_url}')" 
                                            class="p-2 rounded-xl text-gray-400 hover:text-error dark:hover:text-error hover:bg-error/10 transition-all group/btn" 
                                            title="{{ __('file.delete') }}">
                                            <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>`;
                            }
                        }
                    ],
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: 'pageLength', className: 'dt-button' },
                                {
                                    extend: 'collection',
                                    text: "{{ __('file.Export') }}",
                                    className: 'dt-button',
                                    buttons: [
                                        { extend: 'copy', className: 'dt-button' },
                                        { extend: 'excel', className: 'dt-button' },
                                        { extend: 'csv', className: 'dt-button' },
                                        { extend: 'pdf', className: 'dt-button' },
                                        { extend: 'print', className: 'dt-button' }
                                    ]
                                }
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
                        searchPlaceholder: "{{ __('file.search_locations') ?? 'Search locations' }}...",
                        lengthMenu: "{{ __('file.show_entries') }}",
                        info: "{{ __('file.showing_entries') }}",
                        infoEmpty: "{{ __('file.no_items_found') }}",
                        emptyTable: "{{ __('file.no_locations_found') }}",
                        processing: '<div class="admin-loader"></div>',
                        paginate: {
                            next: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                            previous: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>'
                        }
                    },
                    autoWidth: false,
                    scrollX: false
                });

                window.pickupTable = table;

                $('#select-all').on('change', function () { $('.row-checkbox').prop('checked', this.checked); updateBulkDelete(); });
                $(document).on('change', '.row-checkbox', updateBulkDelete);

                function updateBulkDelete() {
                    const checked = $('.row-checkbox:checked');
                    const count = checked.length;
                    $('#bulk-delete-form').toggleClass('hidden', count === 0);
                    $('#selected-count').text(count);
                    const container = document.getElementById('bulk-ids-container');
                    if (container) {
                        container.innerHTML = '';
                        checked.each(function () {
                            const input = document.createElement('input');
                            input.type = 'hidden'; input.name = 'ids[]'; input.value = this.value;
                            container.appendChild(input);
                        });
                    }
                }

                $('#bulk-delete-form-el').on('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('{{ __("file.confirm_delete_selected_items") }}')) return;
                    $.ajax({
                        url: this.action, method: 'POST', data: $(this).serialize(),
                        success: function (response) {
                            table.draw(false); updateBulkDelete(); $('#select-all').prop('checked', false);
                            if (response.success && typeof showNotification === 'function') showNotification('{{ __('file.Success') }}', response.message, 'success');
                        }
                    });
                });

                window.confirmDelete = function (url) {
                    if (!confirm('{{ __("file.confirm_delete_item") }}')) return;
                    $.post(url, { _token: '{{ csrf_token() }}', _method: 'DELETE' }, function (resp) {
                        table.draw(false); updateBulkDelete();
                        if (typeof showNotification === 'function') showNotification('{{ __('file.Success') }}', resp.message, 'success');
                    });
                };
            });
        </script>
    @endpush
    </div>
@endsection
