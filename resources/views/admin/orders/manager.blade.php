@extends('layouts.manager')

@section('title', __('file.order_manager'))

@section('content')
    <div class="flex flex-col h-full">

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div x-data="orderManager({{ Js::from($orders) }})" class="flex flex-1 min-h-0 overflow-hidden flex-col md:flex-row"
            x-cloak>

            <!-- Left Pane: Order List -->
            <div
                class="border-b md:border-b-0 md:border-r border-gray-200 dark:border-surface-tonal-a20 flex flex-col w-full md:w-80 lg:w-96 md:shrink-0 bg-white dark:bg-surface-tonal-a0 h-[40vh] md:h-auto overflow-hidden md:max-h-none">
                <div
                    class="p-3 sm:p-4 border-b border-gray-200 dark:border-surface-tonal-a20 space-y-3 bg-gray-50 dark:bg-surface-tonal-a10 shrink-0">
                    <div class="flex items-center gap-2">
                        <div
                            class="flex-1 flex items-center gap-2 bg-white dark:bg-surface-tonal-a0 rounded-lg px-3 py-2 border border-gray-200 dark:border-surface-tonal-a20 shadow-sm focus-within:ring-2 focus-within:ring-accent/50 transition-shadow">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                class="text-gray-400 dark:text-gray-500 shrink-0" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                            <input x-model="search"
                                class="bg-transparent text-sm text-gray-900 dark:text-primary-a0 outline-none flex-1 placeholder:text-gray-400 dark:placeholder:text-gray-500 w-full border-0 focus:ring-0 p-0"
                                placeholder="{{ __('file.search_orders') }}..." type="text">
                        </div>
                    </div>
                    <div class="flex gap-1.5 flex-wrap">
                        <template x-for="status in ['all', 'pending', 'processing', 'shipped', 'delivered', 'cancelled']">
                            <button @click="filterStatus = status"
                                :class="filterStatus === status ? 'bg-gray-200 dark:bg-surface-tonal-a30 text-gray-900 dark:text-primary-a0 font-medium shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800'"
                                class="text-xs px-2.5 py-1.5 rounded-md capitalize transition-colors" x-text="translations[status]">
                            </button>
                        </template>
                    </div>
                </div>

                <div
                    class="flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800/60 bg-white dark:bg-surface-tonal-a0">
                    <template x-if="filteredOrders.length === 0">
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('file.no_orders_found') }}.</div>
                    </template>
                    <template x-for="order in filteredOrders" :key="order.id">
                        <div @click="selectedOrder = order; $nextTick(() => { if(window.innerWidth < 768) { document.getElementById('order-detail-pane').scrollIntoView({behavior:'smooth'}) } })"
                            :class="selectedOrder && selectedOrder.id === order.id ? 'bg-accent/5 dark:bg-accent/10 border-l-2 border-accent' : 'border-l-2 border-transparent hover:bg-gray-50 dark:hover:bg-surface-tonal-a30'"
                            class="px-4 py-3 flex gap-3 cursor-pointer transition-colors">

                            <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-surface-tonal-a20 flex items-center justify-center text-xs font-semibold shrink-0 text-gray-600 dark:text-gray-300"
                                x-text="getInitials(order.customer_name)"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-1 mb-0.5">
                                    <span class="text-sm font-medium text-gray-900 dark:text-primary-a0 truncate"
                                        x-text="order.customer_name || '{{ __('file.guest_user') }}'"></span>
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-medium ring-1 ring-inset shrink-0"
                                        :class="getStatusBadgeClass(order.status)" x-text="translations[order.status]"></span>
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider"
                                    x-text="order.order_number">
                                </div>
                                <div class="flex items-center justify-between mt-1.5">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 truncate"
                                        style="max-width: 150px;" x-text="getOrderSummary(order)"></span>
                                    <span
                                        class="text-xs font-bold text-gray-900 dark:text-primary-a0 tabular-nums"
                                        x-text="formatMoney(order.total_amount, order.currency)"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Pane: Details -->
            <div id="order-detail-pane"
                class="flex flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50 dark:bg-surface-tonal-a10 flex-col relative w-full md:w-auto min-w-0">
                <template x-if="!selectedOrder">
                    <div
                        class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-600 py-16 md:py-0">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            class="mb-4">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                        <p class="text-base font-medium text-gray-500 dark:text-gray-400">{{ __('file.select_order_to_view_details') }}
                        </p>
                    </div>
                </template>

                <template x-if="selectedOrder">
                    <div class="max-w-7xl mx-auto w-full space-y-4 sm:space-y-6 pb-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                            <div>
                                <div
                                    class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1.5 font-bold tracking-wider uppercase">
                                    <span>{{ __('file.order') }}</span>
                                    <span>/</span>
                                    <span class="text-gray-700 dark:text-gray-300"
                                        x-text="selectedOrder.order_number"></span>
                                </div>
                                <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-primary-a0 tracking-tight"
                                    x-text="selectedOrder.order_number"></h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                    x-text="translations['placed_on'] + ' ' + formatDate(selectedOrder.placed_at || selectedOrder.created_at) + ' · ' + (selectedOrder.payment_method || '{{ __('file.none') }}').toUpperCase()">
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                <button @click="printInvoice()"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-surface-tonal-a0 border border-gray-200 dark:border-surface-tonal-a30 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-accent/50">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="6 9 6 2 18 2 18 9" />
                                        <path
                                            d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                        <rect x="6" y="14" width="12" height="8" />
                                    </svg>
                                    {{ __('file.print_invoice') }}
                                </button>
                                <a :href="'{{ route('orders.show', ['order' => ':id']) }}'.replace(':id', selectedOrder.id)"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-900 dark:text-gray-900 bg-gray-200 dark:bg-gray-200 border border-transparent rounded-md shadow-sm hover:bg-gray-300 dark:hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-accent/50">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                    {{ __('file.full_view') }}
                                </a>
                            </div>
                        </div>

                        <!-- Stepper & Actions -->
                        <div
                            class="bg-white dark:bg-surface-tonal-a0 border border-gray-200 dark:border-surface-tonal-a20 rounded-xl p-4 sm:p-5 shadow-sm">

                            <!-- Stepper -->
                            <div class="relative flex items-start justify-between px-2 sm:px-6">
                                <!-- Background connector line -->
                                <div
                                    class="absolute top-4 left-[calc(12.5%+8px)] right-[calc(12.5%+8px)] h-[2px] bg-gray-200 dark:bg-surface-tonal-a20 z-0">
                                </div>

                                <template x-for="(step, index) in steps" :key="step.id">
                                    <div class="flex-1 flex flex-col items-center text-center relative z-10">
                                        <!-- Active/completed line segment overlay -->
                                        <template x-if="index < steps.length - 1">
                                            <div class="absolute top-4 left-1/2 w-full h-[2px] z-0 transition-colors duration-500"
                                                :class="isStepComplete(index) ? 'bg-accent dark:bg-accent/80' : 'bg-gray-200 dark:bg-surface-tonal-a20'">
                                            </div>
                                        </template>

                                        <!-- Step circle with inline SVG icon -->
                                        <div class="relative z-10 w-8 h-8 rounded-full border-2 flex items-center justify-center mb-2 transition-all duration-300 shrink-0"
                                            :class="getStepClass(index)">
                                            <!-- Placed icon -->
                                            <template x-if="step.id === 'pending'">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </template>
                                            <!-- Processing icon -->
                                            <template x-if="step.id === 'processing'">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path
                                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                </svg>
                                            </template>
                                            <!-- Shipped icon -->
                                            <template x-if="step.id === 'shipped'">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <rect x="1" y="3" width="15" height="13" />
                                                    <path d="M16 8h4l3 3v5h-7V8z" />
                                                    <circle cx="5.5" cy="18.5" r="2.5" />
                                                    <circle cx="18.5" cy="18.5" r="2.5" />
                                                </svg>
                                            </template>
                                            <!-- Delivered icon -->
                                            <template x-if="step.id === 'delivered'">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                    <polyline points="9 22 9 12 15 12 15 22" />
                                                </svg>
                                            </template>
                                        </div>

                                        <div class="text-[10px] sm:text-[11px] font-semibold text-gray-900 dark:text-primary-a0 uppercase tracking-wider leading-tight"
                                            x-text="step.label"></div>
                                    </div>
                                </template>
                            </div>

                            <div
                                class="mt-5 pt-4 border-t border-gray-100 dark:border-surface-tonal-a20 flex items-center gap-2 sm:gap-3 flex-wrap">
                                <template x-if="selectedOrder.status === 'pending'">
                                    <button @click="updateOrderStatus('processing')"
                                        class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-accent text-gray-900 font-medium text-sm rounded-lg hover:bg-accent-dim transition shadow-sm focus:ring-2 focus:ring-accent/50 outline-none">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        {{ __('file.accept_order') }}
                                    </button>
                                </template>
                                <template x-if="selectedOrder.status === 'processing'">
                                    <button @click="updateOrderStatus('shipped')"
                                        class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition shadow-sm focus:ring-2 focus:ring-blue-500/50 outline-none">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <rect x="1" y="3" width="15" height="13" />
                                            <path d="M16 8h4l3 3v5h-7V8z" />
                                            <circle cx="5.5" cy="18.5" r="2.5" />
                                            <circle cx="18.5" cy="18.5" r="2.5" />
                                        </svg>
                                        {{ __('file.mark_shipped') }}
                                    </button>
                                </template>
                                <template x-if="selectedOrder.status === 'shipped'">
                                    <button @click="updateOrderStatus('delivered')"
                                        class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-emerald-600 text-white font-medium text-sm rounded-lg hover:bg-emerald-700 transition shadow-sm focus:ring-2 focus:ring-emerald-500/50 outline-none">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                            <polyline points="9 22 9 12 15 12 15 22" />
                                        </svg>
                                        {{ __('file.mark_delivered') }}
                                    </button>
                                </template>
                                <template x-if="!['cancelled', 'delivered', 'returned'].includes(selectedOrder.status)">
                                    <button @click="updateOrderStatus('cancelled')"
                                        class="ml-auto inline-flex items-center gap-2 px-3 sm:px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition focus:ring-2 focus:ring-red-500/50 outline-none">
                                        {{ __('file.cancel_order') }}
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-5 gap-4 sm:gap-6 flex-1">

                            <!-- Order Items Column (3/5) -->
                            <div
                                class="xl:col-span-3 bg-white dark:bg-surface-tonal-a0 border border-gray-200 dark:border-surface-tonal-a20 rounded-xl flex flex-col shadow-sm">
                                <div
                                    class="px-4 sm:px-5 py-4 border-b border-gray-100 dark:border-surface-tonal-a20 flex items-center justify-between">
                                    <h3
                                        class="text-sm font-semibold text-gray-900 dark:text-primary-a0 flex items-center gap-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" class="text-gray-400 dark:text-gray-500 shrink-0">
                                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                            <line x1="3" y1="6" x2="21" y2="6" />
                                            <path d="M16 10a4 4 0 0 1-8 0" />
                                        </svg>
                                        {{ __('file.order_items') }} (<span
                                            x-text="selectedOrder.items ? selectedOrder.items.length : 0"></span>)
                                    </h3>
                                </div>
                                <div class="px-4 sm:px-5 py-4 space-y-3 flex-1 max-h-[400px] overflow-y-auto">
                                    <template x-for="item in selectedOrder.items || []" :key="item.id">
                                        <div
                                            class="flex items-start gap-3 sm:gap-4 p-3 rounded-lg border border-gray-100 dark:border-surface-tonal-a30 transition hover:bg-gray-50 dark:hover:bg-surface-tonal-a20">
                                            <div
                                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-md bg-gray-100 dark:bg-surface-tonal-a20 flex items-center justify-center text-2xl shrink-0 overflow-hidden border border-gray-200 dark:border-surface-tonal-a30">
                                                <template
                                                    x-if="item.variant && item.variant.product && item.variant.product.primary_image">
                                                    <img :src="item.variant.product.primary_image"
                                                        class="w-full h-full object-cover">
                                                </template>
                                                <template
                                                    x-if="!(item.variant && item.variant.product && item.variant.product.primary_image)">
                                                    <span class="text-gray-400 dark:text-gray-500">📦</span>
                                                </template>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 dark:text-primary-a0 truncate"
                                                    x-text="item.product_name_snapshot || (item.variant && item.variant.product ? item.variant.product.name : '{{ __('file.unknown_product') }}')">
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex flex-wrap items-center gap-1.5">
                                                    <template x-if="item.variant_attributes">
                                                        <span x-text="formatAttributes(item.variant_attributes)"></span>
                                                    </template>
                                                    <span class="font-medium text-gray-900 dark:text-gray-300"
                                                        x-text="'× ' + item.quantity"></span>
                                                </div>
                                                <div class="flex gap-2 mt-2">
                                                    <template x-if="item.variant && item.variant.sku">
                                                        <span
                                                            class="inline-flex items-center rounded bg-gray-50 dark:bg-surface-tonal-a20 px-1.5 py-0.5 text-[10px] font-bold text-gray-600 dark:text-gray-400 ring-1 ring-inset ring-gray-500/10 dark:ring-gray-400/20 tracking-wider"
                                                            x-text="'SKU: ' + item.variant.sku"></span>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-primary-a0 shrink-0 tabular-nums"
                                                x-text="formatMoney((item.unit_price * item.quantity), selectedOrder.currency)">
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div
                                    class="px-4 sm:px-5 py-4 border-t border-gray-100 dark:border-surface-tonal-a20 bg-gray-50 dark:bg-surface-tonal-a0 space-y-2 mt-auto rounded-b-xl">
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ __('file.subtotal') }}</span>
                                        <span class="text-gray-900 dark:text-primary-a0 font-bold tabular-nums"
                                            x-text="formatMoney(selectedOrder.subtotal, selectedOrder.currency)"></span>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ __('file.shipping') }}</span>
                                        <span class="font-bold tabular-nums"
                                            :class="selectedOrder.shipping_amount > 0 ? 'text-gray-900 dark:text-primary-a0' : 'text-emerald-600 dark:text-emerald-400'"
                                            x-text="selectedOrder.shipping_amount > 0 ? formatMoney(selectedOrder.shipping_amount, selectedOrder.currency) : '{{ __('file.free') }}'"></span>
                                    </div>
                                    <template x-if="selectedOrder.discount_amount > 0">
                                        <div class="flex justify-between text-xs text-red-600 dark:text-red-400">
                                            <span>{{ __('file.discount') }}</span>
                                            <span class="font-bold tabular-nums"
                                                x-text="'-' + formatMoney(selectedOrder.discount_amount, selectedOrder.currency)"></span>
                                        </div>
                                    </template>
                                    <template x-if="selectedOrder.tax_amount > 0">
                                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ __('file.tax') }}</span>
                                            <span class="text-gray-900 dark:text-primary-a0 font-bold tabular-nums"
                                                x-text="formatMoney(selectedOrder.tax_amount, selectedOrder.currency)"></span>
                                        </div>
                                    </template>
                                    <div
                                        class="flex justify-between text-sm font-bold text-gray-900 dark:text-primary-a0 pt-3 border-t border-gray-200 dark:border-surface-tonal-a30 mt-2">
                                        <span>{{ __('file.total') }}</span>
                                        <span class="font-bold tabular-nums"
                                            x-text="formatMoney(selectedOrder.total_amount, selectedOrder.currency)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar Details Column (2/5) -->
                            <div class="xl:col-span-2 space-y-4">
                                <div
                                    class="bg-white dark:bg-surface-tonal-a0 border border-gray-200 dark:border-surface-tonal-a20 rounded-xl p-4 sm:p-5 shadow-sm">
                                    <h3
                                        class="text-sm font-semibold text-gray-900 dark:text-primary-a0 mb-4 flex items-center gap-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" class="text-gray-400 dark:text-gray-500 shrink-0">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        {{ __('file.customer_details') }}
                                    </h3>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-surface-tonal-a20 flex items-center justify-center text-sm font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-surface-tonal-a30 shadow-sm shrink-0"
                                            x-text="getInitials(selectedOrder.customer_name)"></div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-primary-a0 truncate"
                                                x-text="selectedOrder.customer_name || '{{ __('file.guest_user') }}'"></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate"
                                                x-text="selectedOrder.customer_email || '{{ __('file.no_email_provided') }}'"></div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-2 text-xs">
                                        <div
                                            class="border border-gray-200 dark:border-surface-tonal-a20 rounded-lg p-3 bg-gray-50 dark:bg-surface-tonal-a20">
                                            <span
                                                class="text-gray-500 dark:text-gray-400 block mb-1 uppercase tracking-wider text-[10px] font-semibold">{{ __('file.phone') }}</span>
                                            <span class="text-gray-900 dark:text-primary-a0 font-bold uppercase tracking-wider"
                                                x-text="selectedOrder.customer_phone || 'N/A'"></span>
                                        </div>
                                        <template x-if="selectedOrder.customer">
                                            <div
                                                class="border border-gray-200 dark:border-surface-tonal-a20 rounded-lg p-3 bg-gray-50 dark:bg-surface-tonal-a20 flex justify-between items-center">
                                                <div>
                                                    <span
                                                        class="text-gray-500 dark:text-gray-400 block mb-1 uppercase tracking-wider text-[10px] font-semibold">{{ __('file.account_info') }}</span>
                                                    <span class="text-gray-700 dark:text-gray-300 text-xs">{{ __('file.internal_customer') }}</span>
                                                </div>
                                                <a :href="'{{ route('customers.show', ['customer' => ':id']) }}'.replace(':id', selectedOrder.customer_id)"
                                                    class="text-accent dark:text-accent hover:underline text-xs font-medium">{{ __('file.view') }}
                                                    &rarr;</a>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div
                                    class="bg-white dark:bg-surface-tonal-a0 border border-gray-200 dark:border-surface-tonal-a20 rounded-xl p-4 sm:p-5 shadow-sm">
                                    <h3
                                        class="text-sm font-semibold text-gray-900 dark:text-primary-a0 mb-4 flex items-center gap-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" class="text-gray-400 dark:text-gray-500 shrink-0">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        {{ __('file.delivery_information') }}
                                    </h3>
                                    <div
                                        class="bg-gray-50 dark:bg-surface-tonal-a20 p-4 rounded-lg border border-gray-200 dark:border-surface-tonal-a20 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        <template x-if="selectedOrder.shipping_address">
                                            <address class="not-italic">
                                                <div class="font-semibold text-gray-900 dark:text-primary-a0 mb-1"
                                                    x-text="selectedOrder.shipping_address.first_name + ' ' + selectedOrder.shipping_address.last_name">
                                                </div>
                                                <div x-text="selectedOrder.shipping_address.address_line_1"></div>
                                                <template x-if="selectedOrder.shipping_address.address_line_2">
                                                    <div x-text="selectedOrder.shipping_address.address_line_2"></div>
                                                </template>
                                                <div
                                                    x-text="selectedOrder.shipping_address.city + ', ' + selectedOrder.shipping_address.state + ' ' + selectedOrder.shipping_address.postal_code">
                                                </div>
                                                <div class="mt-2 text-gray-500 dark:text-gray-400 text-xs"
                                                    x-text="selectedOrder.shipping_address.country"></div>
                                            </address>
                                        </template>
                                        <template x-if="!selectedOrder.shipping_address">
                                            <div class="text-gray-500 dark:text-gray-400 italic text-sm py-2 text-center">{{ __('file.no_shipping_address_specified') }}</div>
                                        </template>
                                    </div>

                                    <div
                                        class="mt-4 pt-4 border-t border-gray-100 dark:border-surface-tonal-a20 flex justify-between items-center text-xs gap-2 flex-wrap">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium shrink-0">{{ __('file.payment_configuration') }}</span>
                                        <span
                                            class="px-2.5 py-1 rounded-md border text-[10px] font-semibold uppercase tracking-wider"
                                            :class="getPaymentBadgeClass(selectedOrder.payment_status)"
                                            x-text="(selectedOrder.payment_method || '{{ __('file.none') }}') + ' · ' + (translations[selectedOrder.payment_status] || selectedOrder.payment_status || '{{ __('file.none') }}')"></span>
                                    </div>
                                </div>

                                <template x-if="selectedOrder.notes">
                                    <div
                                        class="bg-amber-50 dark:bg-amber-500/5 border border-amber-200 dark:border-amber-500/20 rounded-xl p-4 shadow-sm">
                                        <h3
                                            class="text-xs font-semibold text-amber-800 dark:text-amber-500 mb-2 flex items-center gap-1.5 uppercase tracking-wider">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" class="shrink-0">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <polyline points="14 2 14 8 20 8" />
                                                <line x1="16" y1="13" x2="8" y2="13" />
                                                <line x1="16" y1="17" x2="8" y2="17" />
                                                <polyline points="10 9 9 9 8 9" />
                                            </svg>
                                            {{ __('file.customer_notes') }}
                                        </h3>
                                        <p class="text-sm text-amber-900 dark:text-amber-400/80 leading-relaxed"
                                            x-text="selectedOrder.notes"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <!-- Hidden iframe for printing -->
    <iframe id="print-iframe" class="hidden" style="display:none;"></iframe>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderManager', (initialOrders) => ({
                orders: initialOrders || [],
                search: '',
                filterStatus: 'all',
                selectedOrder: null,

                translations: {
                    'all': '{{ __('file.all') }}',
                    'pending': '{{ __('file.pending') }}',
                    'processing': '{{ __('file.processing') }}',
                    'shipped': '{{ __('file.shipped') }}',
                    'delivered': '{{ __('file.delivered') }}',
                    'cancelled': '{{ __('file.cancelled') }}',
                    'returned': '{{ __('file.returned') }}',
                    'paid': '{{ __('file.paid') }}',
                    'failed': '{{ __('file.failed') }}',
                    'refunded': '{{ __('file.refunded') }}',
                    'partially_refunded': '{{ __('file.partially_refunded') }}',
                    'placed_on': '{{ __('file.placed_on') }}',
                    'no_items': '{{ __('file.no_items') }}',
                    'and': '{{ __('file.and') }}',
                    'more_item': '{{ __('file.more_item') }}',
                    'more_items': '{{ __('file.more_items') }}',
                    'unknown_item': '{{ __('file.unknown_product') }}',
                    'confirm_cancel': '{{ __('file.confirm_cancel_order') }}',
                    'status_updated': '{{ __('file.status_updated') }}',
                    'failed_to_update': '{{ __('file.failed_to_update_status') }}',
                    'error_occurred': '{{ __('file.error_occurred_status_update') }}',
                },
                steps: [
                    { id: 'pending', label: '{{ __('file.placed_on') }}' },
                    { id: 'processing', label: '{{ __('file.processing') }}' },
                    { id: 'shipped', label: '{{ __('file.shipped') }}' },
                    { id: 'delivered', label: '{{ __('file.delivered') }}' }
                ],

                init() {
                    if (window.location.hash) {
                        const id = parseInt(window.location.hash.replace('#', ''));
                        const found = this.orders.find(o => o.id === id);
                        if (found) this.selectedOrder = found;
                    }

                    if (!this.selectedOrder && this.orders.length > 0) {
                        this.selectedOrder = this.orders[0];
                    }

                    window.addEventListener('hashchange', () => {
                        if (window.location.hash) {
                            const id = parseInt(window.location.hash.replace('#', ''));
                            const found = this.orders.find(o => o.id === id);
                            if (found) this.selectedOrder = found;
                        }
                    });
                },

                get filteredOrders() {
                    return this.orders.filter(o => {
                        let matchesStatus = this.filterStatus === 'all' || o.status === this.filterStatus;

                        let matchesSearch = true;
                        if (this.search.trim() !== '') {
                            let query = this.search.toLowerCase();
                            matchesSearch = (o.order_number && o.order_number.toLowerCase().includes(query)) ||
                                (o.customer_name && o.customer_name.toLowerCase().includes(query)) ||
                                (o.customer_email && o.customer_email.toLowerCase().includes(query)) ||
                                (o.customer_phone && o.customer_phone.toLowerCase().includes(query));
                        }

                        return matchesStatus && matchesSearch;
                    });
                },

                getInitials(name) {
                    if (!name) return '??';
                    let parts = name.trim().split(' ');
                    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
                    return name.substring(0, 2).toUpperCase();
                },

                capitalize(str) {
                    if (!str) return '';
                    return str.charAt(0).toUpperCase() + str.slice(1).replace(/_/g, ' ');
                },

                getStatusBadgeClass(status) {
                    const map = {
                        'pending': 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 ring-amber-600/20 dark:ring-amber-500/20',
                        'processing': 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 ring-blue-600/20 dark:ring-blue-500/20',
                        'shipped': 'bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400 ring-violet-600/20 dark:ring-violet-500/20',
                        'delivered': 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 ring-emerald-600/20 dark:ring-emerald-500/20',
                        'cancelled': 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 ring-red-600/20 dark:ring-red-500/20',
                        'returned': 'bg-gray-50 dark:bg-gray-500/10 text-gray-700 dark:text-gray-400 ring-gray-600/20 dark:ring-gray-500/20'
                    };
                    return map[status] || 'bg-gray-50 dark:bg-surface-tonal-a20 text-gray-600 dark:text-gray-400 ring-gray-500/20';
                },

                getPaymentBadgeClass(status) {
                    const map = {
                        'pending': 'bg-amber-50 dark:bg-amber-500/5 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                        'paid': 'bg-emerald-50 dark:bg-emerald-500/5 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                        'failed': 'bg-red-50 dark:bg-red-500/5 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20',
                        'refunded': 'bg-gray-50 dark:bg-surface-tonal-a20 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30',
                        'partially_refunded': 'bg-orange-50 dark:bg-orange-500/5 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-500/20'
                    };
                    return map[status] || 'bg-gray-50 dark:bg-surface-tonal-a20 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-surface-tonal-a30';
                },

                formatMoney(amount, currency = '{{ $currency_symbol }}') {
                    return (currency || '{{ $currency_symbol }}') + ' ' + parseFloat(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    return new Date(dateString).toLocaleDateString('{{ str_replace('_', '-', app()->getLocale()) }}', {
                        day: 'numeric', month: 'short', year: 'numeric',
                        hour: '2-digit', minute: '2-digit'
                    }).replace(',', '');
                },

                getOrderSummary(order) {
                    if (!order.items || order.items.length === 0) return this.translations['no_items'];
                    let firstItemName = order.items[0].product_name_snapshot || (order.items[0].variant && order.items[0].variant.product ? order.items[0].variant.product.name : this.translations['unknown_item']);
                    if (order.items.length === 1) return firstItemName + ' × ' + order.items[0].quantity;
                    return firstItemName + ' ' + this.translations['and'] + ' ' + (order.items.length - 1) + ' ' + (order.items.length > 2 ? this.translations['more_items'] : this.translations['more_item']);
                },

                formatAttributes(attrs) {
                    if (!attrs) return '';
                    if (typeof attrs === 'string') {
                        try { attrs = JSON.parse(attrs); } catch (e) { return attrs; }
                    }
                    if (typeof attrs !== 'object') return '';
                    return Object.values(attrs).join(' · ');
                },

                isStepComplete(index) {
                    if (!this.selectedOrder || !this.selectedOrder.status) return false;
                    const statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
                    const currentIdx = statusOrder.indexOf(this.selectedOrder.status);
                    if (currentIdx === -1) return false;
                    return index <= currentIdx;
                },

                getStepClass(index) {
                    if (!this.selectedOrder || !this.selectedOrder.status) return 'border-gray-200 dark:border-surface-tonal-a20 text-gray-400 dark:text-gray-600 bg-white dark:bg-surface-tonal-a0';
                    const statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
                    const currentIdx = statusOrder.indexOf(this.selectedOrder.status);
                    if (currentIdx === -1) return 'border-gray-200 dark:border-surface-tonal-a20 text-gray-400 dark:text-gray-600 bg-white dark:bg-surface-tonal-a0';
                    if (index < currentIdx) return 'border-accent bg-accent text-gray-900 shadow-sm dark:bg-accent dark:text-gray-900';
                    if (index === currentIdx) return 'border-accent bg-accent/10 dark:bg-accent/20 text-accent bg-white dark:bg-surface-tonal-a0';
                    return 'border-gray-200 dark:border-surface-tonal-a20 text-gray-400 dark:text-gray-500 bg-white dark:bg-surface-tonal-a0';
                },

                async updateOrderStatus(newStatus) {
                    if (!this.selectedOrder) return;

                    if (newStatus === 'cancelled' && !confirm(this.translations['confirm_cancel'])) {
                        return;
                    }

                    try {
                        const url = "{{ route('orders.update-status', ['order' => ':id']) }}".replace(':id', this.selectedOrder.id);
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: newStatus })
                        });

                        if (response.ok) {
                            this.selectedOrder.status = newStatus;

                            if (typeof window.showNotification === 'function') {
                                let msg = '{{ __('file.status_marked_as') }}';
                                msg = msg.replace(':order', this.selectedOrder.order_number).replace(':status', this.translations[newStatus]);
                                window.showNotification(this.translations['status_updated'], msg, 'success');
                            }
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification('{{ __('file.error') }}', this.translations['failed_to_update'], 'error');
                            } else {
                                alert(this.translations['failed_to_update']);
                            }
                        }
                    } catch (error) {
                        console.error('Error updating status:', error);
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('{{ __('file.error') }}', this.translations['error_occurred'], 'error');
                        }
                    }
                },

                printInvoice() {
                    if (!this.selectedOrder) return;
                    const url = "{{ route('orders.invoice', ['order' => ':id']) }}".replace(':id', this.selectedOrder.id);
                    const iframe = document.getElementById('print-iframe');
                    iframe.src = url;
                    iframe.onload = () => {
                        setTimeout(() => {
                            iframe.contentWindow.focus();
                            iframe.contentWindow.print();
                        }, 500);
                    };
                }
            }));
        });
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
