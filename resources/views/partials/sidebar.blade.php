<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 h-screen bg-white dark:bg-surface-tonal-a20 shadow-lg border-r border-gray-200 dark:border-surface-tonal-a30 transition-all duration-300 z-50 flex flex-col lg:translate-x-0 -translate-x-full"
style="width: 16rem; max-height: 100vh;">

    <!-- Logo -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-200 dark:border-surface-tonal-a30 flex-shrink-0">
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}" class="flex items-center space-x-3">
            @if(isset($site_logo) && $site_logo)
                <img src="{{ $site_logo }}" alt="Site Logo" class="sidebar-text h-9 w-9 rounded-lg object-cover ring-2 ring-green-500/20">
            @else
                <div class="sidebar-text h-9 w-9 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5"/>
                    </svg>
                </div>
            @endif
            <span class="text-xl font-bold sidebar-text truncate text-accent">
                {{ $site_name ?? 'App' }}
            </span>
        </a>
        <button id="toggle-sidebar" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-surface-3 transition-colors flex-shrink-0" aria-label="Toggle sidebar">
            <svg id="icon-expanded" class="w-5 h-5 text-gray-600 dark:text-gray-200 opacity-100"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <svg id="icon-collapsed" class="w-5 h-5 text-gray-600 dark:text-gray-200 opacity-0 absolute"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav x-data="{ 
            activeGroup: '{{ 
                request()->routeIs('orders.*') ? 'orders' : (
        request()->routeIs('transactions.*', 'refunds.*', 'settings.payment-gateways') ? 'finances' : (
            request()->routeIs('inventory.*') ? 'inventory' : (
                request()->routeIs('shipping.*') ? 'shipping' : (
                    request()->routeIs('coupons.*', 'discount-rules.*') ? 'promotions' : (
                        request()->routeIs('invoices.*', 'cash-registers.*') ? 'billing' : (
                            request()->routeIs('categories.*', 'brands.*', 'attributes.*', 'products.*', 'collections.*') ? 'catalog' : (
                                request()->routeIs('customers.*', 'subscribers.*', 'reviews.*') ? 'crm' : (
                                    request()->routeIs('reports.*') ? 'reports' : (
                                        request()->routeIs('users.*', 'roles.*', 'settings.*', 'dropdowns.*', 'admin.storefront.*') ? 'admin' : 'none'
                                    ))))))))) }}'
        }" 
        class="p-4 space-y-1 overflow-y-auto overflow-x-hidden flex-1 pb-10">
        @auth

            {{-- ── DASHBOARD ──────────────────────────────────────────────────────── --}}
            @include('partials.sidebar-item', [
                'route' => 'admin.dashboard',
                'active' => request()->routeIs('admin.dashboard'),
                'label' => __('file.dashboard') ?? 'Dashboard',
                'tooltip' => __('file.dashboard') ?? 'Dashboard',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            ])


            @if(auth('admin')->check() || (auth()->check() && auth()->user()->hasRole('admin')))

                {{-- ──────────────── STORE MANAGEMENT ──────────────── --}}
                <div class="px-4 py-2 mt-4 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest sidebar-text">
                    {{ __('file.store_management') }}
                </div>

                {{-- Orders --}}
                <div x-data="{ group: 'orders' }">
                    @include('partials.sidebar-group-btn', [
                        'name' => 'orders',
                        'label' => __('file.orders') ?? 'Orders',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                    ])
                    <div x-show="activeGroup === 'orders'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                        @include('partials.sidebar-sub-item', ['route' => 'orders.index', 'active' => request()->routeIs('orders.*') && !request()->routeIs('orders.manager'), 'label' => __('file.orders_list') ?? 'All Orders'])
                    </div>
                </div>

                {{-- Catalog --}}
                @canany(['categories.index', 'brands.index', 'attributes.index', 'products.index', 'collections.index'])
                    <div x-data="{ group: 'catalog' }">
                        @include('partials.sidebar-group-btn', [
                            'name' => 'catalog',
                            'label' => __('file.catalog') ?? 'Catalog',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
                        ])
                        <div x-show="activeGroup === 'catalog'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                            @include('partials.sidebar-sub-item', ['route' => 'products.index', 'active' => request()->routeIs('products.*'), 'label' => __('file.products') ?? 'Products'])
                            @can('categories.index')
                                @include('partials.sidebar-sub-item', ['route' => 'categories.index', 'active' => request()->routeIs('categories.*'), 'label' => __('file.categories') ?? 'Categories'])
                            @endcan
                            @can('collections.index')
                                @include('partials.sidebar-sub-item', ['route' => 'collections.index', 'active' => request()->routeIs('collections.*'), 'label' => __('file.collections')])
                            @endcan
                            @can('brands.index')
                                @include('partials.sidebar-sub-item', ['route' => 'brands.index', 'active' => request()->routeIs('brands.*'), 'label' => __('file.brands') ?? 'Brands'])
                            @endcan
                            @can('attributes.index')
                                @include('partials.sidebar-sub-item', ['route' => 'attributes.index', 'active' => request()->routeIs('attributes.*'), 'label' => __('file.attributes') ?? 'Attributes'])
                            @endcan
                        </div>
                    </div>
                @endcanany

                {{-- Inventory --}}
                <div x-data="{ group: 'inventory' }">
                    @include('partials.sidebar-group-btn', [
                        'name' => 'inventory',
                        'label' => __('file.inventory'),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                    ])
                    <div x-show="activeGroup === 'inventory'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                        @include('partials.sidebar-sub-item', ['route' => 'inventory.index', 'active' => request()->routeIs('inventory.*'), 'label' => __('file.stock_status')])
                    </div>
                </div>

                {{-- Shipping & Delivery --}}
                <div x-data="{ group: 'shipping' }">
                    @include('partials.sidebar-group-btn', [
                        'name' => 'shipping',
                        'label' => __('file.shipping'),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>',
                    ])
                    <div x-show="activeGroup === 'shipping'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                        @include('partials.sidebar-sub-item', ['route' => 'shipping.shipments.index', 'active' => request()->routeIs('shipping.shipments.*'), 'label' => __('file.fulfillment')])
                        @include('partials.sidebar-sub-item', ['route' => 'shipping.rates.index', 'active' => request()->routeIs('shipping.rates.*'), 'label' => __('file.shipping_rates')])
                        @include('partials.sidebar-sub-item', ['route' => 'shipping.zones.index', 'active' => request()->routeIs('shipping.zones.*'), 'label' => __('file.shipping_zones')])
                        @include('partials.sidebar-sub-item', ['route' => 'shipping.couriers.index', 'active' => request()->routeIs('shipping.couriers.*'), 'label' => __('file.local_couriers')])
                        @include('partials.sidebar-sub-item', ['route' => 'shipping.pickups.index', 'active' => request()->routeIs('shipping.pickups.*'), 'label' => __('file.pickup_locations')])
                    </div>
                </div>


                {{-- ──────────────── CUSTOMERS & MARKETING ──────────────── --}}
                <div class="px-4 py-2 mt-6 border-t border-gray-100 dark:border-surface-tonal-a30 pt-4 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest sidebar-text">
                    {{ __('file.customers_marketing') }}
                </div>

                {{-- CRM & Customers --}}
                <div x-data="{ group: 'crm' }">
                    @include('partials.sidebar-group-btn', [
                        'name' => 'crm',
                        'label' => __('file.crm') ?? 'Customers & CRM',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
                    ])
                    <div x-show="activeGroup === 'crm'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                        @include('partials.sidebar-sub-item', ['route' => 'customers.index', 'active' => request()->routeIs('customers.*'), 'label' => __('file.customers') ?? 'Customers List'])
                        @include('partials.sidebar-sub-item', ['route' => 'subscribers.index', 'active' => request()->routeIs('subscribers.*'), 'label' => __('file.subscribers') ?? 'Newsletters'])
                        @include('partials.sidebar-sub-item', ['route' => 'reviews.index', 'active' => request()->routeIs('reviews.*'), 'label' => __('file.reviews') ?? 'Product Reviews'])
                    </div>
                </div>

                {{-- Discounts & Promotions --}}
                <div x-data="{ group: 'promotions' }">
                    @include('partials.sidebar-group-btn', [
                        'name' => 'promotions',
                        'label' => __('file.promotion'),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />',
                    ])
                    <div x-show="activeGroup === 'promotions'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                        @include('partials.sidebar-sub-item', ['route' => 'coupons.index', 'active' => request()->routeIs('coupons.*'), 'label' => __('file.add_coupon')])
                        @include('partials.sidebar-sub-item', ['route' => 'discount-rules.index', 'active' => request()->routeIs('discount-rules.*'), 'label' => __('file.manage_discount_rules')])
                    </div>
                </div>


                {{-- ──────────────── FINANCE & SYSTEM ──────────────── --}}
                <div class="px-4 py-2 mt-6 border-t border-gray-100 dark:border-surface-tonal-a30 pt-4 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest sidebar-text">
                    {{ __('file.system_settings') }}
                </div>

                {{-- Payments & Finances --}}
                <div x-data="{ group: 'finances' }">
                    @include('partials.sidebar-group-btn', [
                        'name' => 'finances',
                        'label' => __('file.finances'),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    ])
                    <div x-show="activeGroup === 'finances'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                        @include('partials.sidebar-sub-item', ['route' => 'transactions.index', 'active' => request()->routeIs('transactions.*'), 'label' => __('file.transactions')])
                        @include('partials.sidebar-sub-item', ['route' => 'refunds.index', 'active' => request()->routeIs('refunds.*'), 'label' => __('file.refunds')])
                        @include('partials.sidebar-sub-item', ['route' => 'settings.payment-gateways', 'active' => request()->routeIs('settings.payment-gateways'), 'label' => __('file.gateways')])
                    </div>
                </div>

                {{-- Reports --}}
                @can('reports.index')
                    <div x-data="{ group: 'reports' }">
                        @include('partials.sidebar-group-btn', [
                            'name' => 'reports',
                            'label' => __('file.reports') ?? 'Reports',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                        ])
                        <div x-show="activeGroup === 'reports'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                            @include('partials.sidebar-sub-item', ['route' => 'reports.financial', 'active' => request()->routeIs('reports.financial*'), 'label' => __('file.financial_reports') ?? 'Financial'])
                            @include('partials.sidebar-sub-item', ['route' => 'reports.inventory', 'active' => request()->routeIs('reports.inventory*'), 'label' => __('file.inventory_reports') ?? 'Inventory'])
                        </div>
                    </div>
                @endcan

                {{-- Users, Roles & Settings --}}
                @canany(['users.index', 'roles.index', 'settings.index'])
                    <div x-data="{ group: 'admin' }">
                        @include('partials.sidebar-group-btn', [
                            'name' => 'admin',
                            'label' => __('file.administration') ?? 'Settings',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        ])
                        <div x-show="activeGroup === 'admin'" x-transition x-cloak class="ml-8 space-y-1 mt-1">
                            @can('users.index')
                                @include('partials.sidebar-sub-item', ['route' => 'users.index', 'active' => request()->routeIs('users.*'), 'label' => __('file.user_management') ?? 'Users'])
                            @endcan
                            @can('roles.index')
                                @include('partials.sidebar-sub-item', ['route' => 'roles.index', 'active' => request()->routeIs('roles.*'), 'label' => __('file.roles_management') ?? 'Roles'])
                            @endcan
                            @can('settings.index')
                                @include('partials.sidebar-sub-item', ['route' => 'settings.general', 'active' => request()->routeIs('settings.*'), 'label' => __('file.general_settings')])
                                @include('partials.sidebar-sub-item', ['route' => 'admin.storefront.index', 'active' => request()->routeIs('admin.storefront.*'), 'label' => __('file.storefront')])
                            @endcan

                        </div>
                    </div>
                @endcanany

            @endif
        @else
            <a href="{{ Route::has('admin.login') ? route('admin.login') : (Route::has('login') ? route('login') : '#') }}"
               class="group flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-800 hover:text-green-600 dark:hover:text-green-400">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16v-4m0 0V8m0 4h4m-4 0H7"/>
                </svg>
                <span class="ml-3 sidebar-text">{{ __('file.log_in') ?? 'Log in' }}</span>
            </a>
        @endauth
    </nav>
    
    {{-- ── USER PROFILE BUTTON (REPLACES LOGOUT) ──────────────────────────────────────────────────────────── --}}
    @auth
        @php
            $user = auth('admin')->check() ? auth('admin')->user() : auth()->user();
            $name = $user->name ?? 'User';
            $email = $user->email ?? '';
        @endphp
        <div class="mt-auto border-t border-gray-200 dark:border-surface-tonal-a30 p-4 bg-gray-100/50 dark:bg-surface-tonal-a20 flex-shrink-0 w-full">
            <div class="flex items-center space-x-3 w-full">
                <img class="h-10 w-10 min-w-[40px] rounded-lg object-cover ring-2 ring-gray-200 dark:ring-surface-tonal-a40" 
                        src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random' }}" 
                        alt="Avatar">
                <div class="sidebar-text truncate flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate w-full" title="{{ $name }}">
                        {{ $name }}
                    </p>
                    @if($email)
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate w-full" title="{{ $email }}">
                            {{ $email }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endauth

</aside>
