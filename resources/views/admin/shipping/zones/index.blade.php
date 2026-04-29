@extends('layouts.app')

@section('title', __('file.shipping_zones') ?? 'Shipping Zones')

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
                <span class="active">{{ __('file.shipping_zones') ?? 'Shipping Zones' }}</span>
            </nav>

            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.shipping_zones') ?? 'Shipping Zones' }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.manage_shipping_zones') ?? 'Define regional boundaries to apply precision-targeted shipping rate structures' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openZoneDrawer()" class="admin-btn-add">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('file.add_zone') ?? 'Add Zone' }}
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
                <form method="POST" action="{{ route('shipping.zones.bulkDelete') }}" id="bulk-delete-form-el"
                    class="admin-bulk-bar">
                    @csrf
                    <div id="bulk-ids-container" class="hidden"></div>
                    <div class="flex items-center gap-3">
                        <div class="selection-count" id="selected-count">0</div>
                        <span>{{ __('file.zones_selected') ?? 'Zones Selected' }}</span>
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
                                <th>{{ __('file.Country') }}</th>
                                <th>{{ __('file.Zone Name') }}</th>
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
        {{-- Shipping Zone Drawer --}}
        <div id="zone-drawer" class="fixed inset-0 z-[9999] hidden overflow-hidden">
            <div id="zone-drawer-overlay"
                class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"
                onclick="closeZoneDrawer()"></div>
            <div id="zone-drawer-panel"
                class="absolute inset-y-0 right-0 w-full md:max-w-lg bg-white dark:bg-surface-tonal-a20 shadow-2xl transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col">
                
                <div class="flex items-center justify-between px-8 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                    <div>
                        <h3 id="zone-drawer-title" class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ __('file.add_new_zone') ?? 'Add New Shipping Zone' }}
                        </h3>
                        <p id="zone-drawer-subtitle" class="text-sm font-medium text-primary mt-1">
                            {{ __('file.define_regional_boundaries') ?? 'Define regional boundaries for shipping' }}
                        </p>
                    </div>
                    <button type="button" onclick="closeZoneDrawer()"
                        class="p-2.5 rounded-xl hover:bg-white dark:hover:bg-white/10 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="zone-drawer-content" class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <div class="flex items-center justify-center h-full">
                        <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script type="module">
            const zoneDrawer = document.getElementById('zone-drawer');
            const zoneOverlay = document.getElementById('zone-drawer-overlay');
            const zonePanel = document.getElementById('zone-drawer-panel');
            const zoneContent = document.getElementById('zone-drawer-content');

            window.openZoneDrawer = (url = null) => {
                const isEdit = url && !url.includes('create');
                const titleEl = document.getElementById('zone-drawer-title');
                const subtitleEl = document.getElementById('zone-drawer-subtitle');
                
                titleEl.textContent = isEdit ? '{{ __("file.edit_shipping_zone") ?? "Edit Shipping Zone" }}' : '{{ __("file.add_new_zone") ?? "Add New Shipping Zone" }}';
                subtitleEl.textContent = isEdit ? '{{ __("file.update_zone_details") ?? "Update shipping zone configuration" }}' : '{{ __("file.create_new_zone_entry") ?? "Create a new shipping zone entry" }}';

                zoneDrawer.classList.remove('hidden');
                zoneContent.innerHTML = '<div class="flex items-center justify-center h-full"><div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div></div>';
                
                setTimeout(() => {
                    zoneOverlay.classList.replace('opacity-0', 'opacity-100');
                    zonePanel.classList.remove('translate-x-full');
                }, 10);
                
                document.body.style.overflow = 'hidden';

                const fetchUrl = url || '{{ route("shipping.zones.create") }}';
                fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(html => {
                        zoneContent.innerHTML = html;
                        setupZoneFormHandler();
                    })
                    .catch(err => {
                        zoneContent.innerHTML = `<div class="p-4 text-red-500 text-center">${err.message}</div>`;
                    });
            };

            window.closeZoneDrawer = () => {
                zoneOverlay.classList.remove('opacity-100');
                zonePanel.classList.add('translate-x-full');
                document.body.style.overflow = '';
                setTimeout(() => zoneDrawer.classList.add('hidden'), 500);
            };

            function setupZoneFormHandler() {
                const form = document.getElementById('zone-drawer-form');
                if (!form) return;

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const loader = document.getElementById('zone-drawer-loader');
                    const saveText = document.getElementById('zone-drawer-save-text');

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
                            closeZoneDrawer();
                            if (window.zoneTable) window.zoneTable.draw(false);
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
                    ajax: { url: '{{ route('shipping.zones.datatable') }}' },
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
                            data: 'country_html', name: 'country_code',
                            render: data => data
                        },
                        {
                            data: 'zone_html', name: 'name',
                            render: data => data
                        },
                        {
                            data: 'status_html', name: 'is_active',
                            render: data => data
                        },
                        {
                            data: null, 
                            className: 'text-right whitespace-nowrap !px-4',
                            render: function (data, type, row) {
                                return `
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick="openZoneDrawer('${row.edit_url || '{{ route('shipping.zones.edit', ':id') }}'.replace(':id', row.id)}')" 
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
                        searchPlaceholder: "{{ __('file.search_zones') ?? 'Search zones' }}...",
                        lengthMenu: "{{ __('file.show_entries') }}",
                        info: "{{ __('file.showing_entries') }}",
                        infoEmpty: "{{ __('file.no_items_found') }}",
                        emptyTable: "{{ __('file.no_zones_found') }}",
                        processing: '<div class="admin-loader"></div>',
                        paginate: {
                            next: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                            previous: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>'
                        }
                    },
                    autoWidth: false,
                    scrollX: false
                });

                window.zoneTable = table;

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
