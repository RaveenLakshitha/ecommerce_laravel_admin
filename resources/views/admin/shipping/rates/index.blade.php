@extends('layouts.app')

@section('title', __('file.shipping_rates') ?? 'Shipping Rates')

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
                <span class="active">{{ __('file.shipping_rates') ?? 'Shipping Rates' }}</span>
            </nav>

            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.shipping_rates') ?? 'Shipping Rates' }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.manage_shipping_rates') ?? 'Configure dynamic delivery pricing based on weight and destination' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openRateDrawer()" class="admin-btn-add">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('file.add_rate') ?? 'Add Rate' }}
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
                <form method="POST" action="{{ route('shipping.rates.bulkDelete') }}" id="bulk-delete-form-el"
                    class="admin-bulk-bar">
                    @csrf
                    <div id="bulk-ids-container" class="hidden"></div>
                    <div class="flex items-center gap-3">
                        <div class="selection-count" id="selected-count">0</div>
                        <span>{{ __('file.rates_selected') ?? 'Rates Selected' }}</span>
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
                                <th>{{ __('file.Rate Name') }}</th>
                                <th>{{ __('file.Zone') }}</th>
                                <th>{{ __('file.Amount') }}</th>
                                <th>{{ __('file.Conditions') }}</th>
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
        {{-- Shipping Rate Drawer --}}
        <div id="rate-drawer" class="fixed inset-0 z-[9999] hidden overflow-hidden">
            <div id="rate-drawer-overlay"
                class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"
                onclick="closeRateDrawer()"></div>
            <div id="rate-drawer-panel"
                class="absolute inset-y-0 right-0 w-full md:max-w-lg bg-white dark:bg-surface-tonal-a20 shadow-2xl transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col">
                
                <div class="flex items-center justify-between px-8 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                    <div>
                        <h3 id="rate-drawer-title" class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ __('file.add_new_rate') ?? 'Add New Shipping Rate' }}
                        </h3>
                        <p id="rate-drawer-subtitle" class="text-sm font-medium text-primary mt-1">
                            {{ __('file.configure_delivery_pricing') ?? 'Configure delivery pricing for your store' }}
                        </p>
                    </div>
                    <button type="button" onclick="closeRateDrawer()"
                        class="p-2.5 rounded-xl hover:bg-white dark:hover:bg-white/10 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="rate-drawer-content" class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <div class="flex items-center justify-center h-full">
                        <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script type="module">
            const rateDrawer = document.getElementById('rate-drawer');
            const rateOverlay = document.getElementById('rate-drawer-overlay');
            const ratePanel = document.getElementById('rate-drawer-panel');
            const rateContent = document.getElementById('rate-drawer-content');

            window.openRateDrawer = (url = null) => {
                const isEdit = url && !url.includes('create');
                const titleEl = document.getElementById('rate-drawer-title');
                const subtitleEl = document.getElementById('rate-drawer-subtitle');
                
                titleEl.textContent = isEdit ? '{{ __("file.edit_shipping_rate") ?? "Edit Shipping Rate" }}' : '{{ __("file.add_new_rate") ?? "Add New Shipping Rate" }}';
                subtitleEl.textContent = isEdit ? '{{ __("file.update_rate_details") ?? "Update shipping rate configuration" }}' : '{{ __("file.create_new_rate_entry") ?? "Create a new shipping rate entry" }}';

                rateDrawer.classList.remove('hidden');
                rateContent.innerHTML = '<div class="flex items-center justify-center h-full"><div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div></div>';
                
                setTimeout(() => {
                    rateOverlay.classList.replace('opacity-0', 'opacity-100');
                    ratePanel.classList.remove('translate-x-full');
                }, 10);
                
                document.body.style.overflow = 'hidden';

                const fetchUrl = url || '{{ route("shipping.rates.create") }}';
                fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(html => {
                        rateContent.innerHTML = html;
                        setupRateFormHandler();
                    })
                    .catch(err => {
                        rateContent.innerHTML = `<div class="p-4 text-red-500 text-center">${err.message}</div>`;
                    });
            };

            window.closeRateDrawer = () => {
                rateOverlay.classList.remove('opacity-100');
                ratePanel.classList.add('translate-x-full');
                document.body.style.overflow = '';
                setTimeout(() => rateDrawer.classList.add('hidden'), 500);
            };

            function setupRateFormHandler() {
                const form = document.getElementById('rate-drawer-form');
                if (!form) return;

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const loader = document.getElementById('rate-drawer-loader');
                    const saveText = document.getElementById('rate-drawer-save-text');

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
                            closeRateDrawer();
                            if (window.rateTable) window.rateTable.draw(false);
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
                    ajax: { url: '{{ route('shipping.rates.datatable') }}' },
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
                            data: 'rate_html', name: 'name',
                            render: data => data
                        },
                        {
                            data: 'zone_html', name: 'shipping_zone_id',
                            render: data => `<span class="text-xs font-semibold text-primary uppercase tracking-widest">${data}</span>`
                        },
                        {
                            data: 'amount_html', name: 'rate_amount',
                            render: data => data
                        },
                        {
                            data: 'conditions_html', searchable: false, orderable: false,
                            render: data => data
                        },
                        {
                            data: null, 
                            className: 'text-right whitespace-nowrap !px-4',
                            render: function (data, type, row) {
                                return `
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick="openRateDrawer('${row.edit_url || '{{ route('shipping.rates.edit', ':id') }}'.replace(':id', row.id)}')" 
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
                        searchPlaceholder: "{{ __('file.search_rates') ?? 'Search rates' }}...",
                        lengthMenu: "{{ __('file.show_entries') }}",
                        info: "{{ __('file.showing_entries') }}",
                        infoEmpty: "{{ __('file.no_items_found') }}",
                        emptyTable: "{{ __('file.no_rates_found') }}",
                        processing: '<div class="admin-loader"></div>',
                        paginate: {
                            next: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                            previous: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>'
                        }
                    },
                    autoWidth: false,
                    scrollX: false
                });

                window.rateTable = table;

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
