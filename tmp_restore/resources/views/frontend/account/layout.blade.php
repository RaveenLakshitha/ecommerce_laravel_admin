@extends('frontend.layouts.app')

@section('title', 'My Account')

@section('content')
    <div class="bg-gray-50 dark:bg-surface-tonal-a10 min-h-screen py-10" x-data="accountTabs('{{ $activeTab ?? 'dashboard' }}')">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="lg:grid lg:grid-cols-12 lg:gap-8">

                {{-- SIDEBAR --}}
                <aside class="hidden lg:block lg:col-span-3">
                    <nav
                        class="space-y-1 bg-white dark:bg-surface-tonal-a20 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30">

                        {{-- User Preview --}}
                        <div class="flex items-center space-x-3 mb-6 pb-6 border-b border-gray-100 dark:border-surface-tonal-a30">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full ring-2 ring-primary-100 dark:ring-primary-900 object-cover"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=E0E7FF&color=4F46E5"
                                    alt="">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-primary-a0">Welcome back,</p>
                                <p class="text-base font-bold text-gray-900 dark:text-primary-a0 truncate">
                                    {{ $customer->first_name ?? $user->name }}</p>
                            </div>
                        </div>

                        {{-- Navigation Links --}}
                        @php
                            $navItems = [
                                ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
                                ['id' => 'orders', 'label' => 'Order History', 'icon' => 'shopping-bag'],
                                ['id' => 'addresses', 'label' => 'Addresses', 'icon' => 'map-pin'],
                                ['id' => 'profile', 'label' => 'Account Info', 'icon' => 'user'],
                                ['id' => 'wishlist', 'label' => 'Wishlist', 'icon' => 'heart'],
                                ['id' => 'returns', 'label' => 'Returns', 'icon' => 'refresh-cw'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            <button @click="switchTab('{{ $item['id'] }}')"
                                :class="{ 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-400': activeTab === '{{ $item['id'] }}', 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white': activeTab !== '{{ $item['id'] }}' }"
                                class="w-full group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors mb-1">
                                <i data-feather="{{ $item['icon'] }}" class="flex-shrink-0 -ml-1 mr-3 h-5 w-5"
                                    :class="{ 'text-primary-500': activeTab === '{{ $item['id'] }}', 'text-gray-400 group-hover:text-gray-500': activeTab !== '{{ $item['id'] }}' }"></i>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </button>
                        @endforeach

                        {{-- Logout --}}
                        <div class="pt-6 mt-6 border-t border-gray-100 dark:border-surface-tonal-a30">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i data-feather="log-out"
                                        class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 text-red-500 dark:text-red-400"></i>
                                    <span class="truncate">Sign out</span>
                                </button>
                            </form>
                        </div>

                    </nav>
                </aside>

                {{-- MAIN CONTENT AREA --}}
                <main class="lg:col-span-9 mt-6 lg:mt-0">
                    <div
                        class="bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-xl border border-gray-100 dark:border-surface-tonal-a30 min-h-[500px]">

                        {{-- Mobile view tab selector --}}
                        <div class="lg:hidden border-b border-gray-200 dark:border-surface-tonal-a30 p-4">
                            <label for="mobile-tabs" class="sr-only">Select a tab</label>
                            <select id="mobile-tabs" x-model="activeTab"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 py-2 pl-3 pr-10 text-base focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                                @foreach($navItems as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dynamic Tab Content Container --}}
                        <div class="p-6 sm:p-8">

                            <div x-show="activeTab === 'dashboard'" x-cloak x-transition.opacity.duration.300ms>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-primary-a0 mb-2">Hello,
                                        {{ $customer->first_name ?? $user->name }}!
                                    </h2>
                                    <p class="text-gray-600 dark:text-gray-400 mb-8">From your account dashboard you can
                                        view your recent orders, manage
                                        your shipping and billing addresses, and edit your password and account details.</p>

                                    {{-- Quick Stats Grid --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

                                        {{-- Total Orders --}}
                                        <div
                                            class="bg-gray-50 dark:bg-surface-tonal-a30/50 rounded-xl p-6 border border-gray-100 dark:border-surface-tonal-a30 flex items-center shadow-sm">
                                            <div
                                                class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 mr-4">
                                                <i data-feather="shopping-bag" class="h-6 w-6"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders
                                                </p>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0">
                                                    {{ $totalOrders }}</p>
                                            </div>
                                        </div>

                                        {{-- Wishlist Items --}}
                                        <div
                                            class="bg-gray-50 dark:bg-surface-tonal-a30/50 rounded-xl p-6 border border-gray-100 dark:border-surface-tonal-a30 flex items-center shadow-sm">
                                            <div
                                                class="p-3 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400 mr-4">
                                                <i data-feather="heart" class="h-6 w-6"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Wishlist
                                                    Items</p>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0">
                                                    {{ $wishlistItems }}</p>
                                            </div>
                                        </div>

                                        {{-- Addresses --}}
                                        <div
                                            class="bg-gray-50 dark:bg-surface-tonal-a30/50 rounded-xl p-6 border border-gray-100 dark:border-surface-tonal-a30 flex items-center shadow-sm">
                                            <div
                                                class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 mr-4">
                                                <i data-feather="map-pin" class="h-6 w-6"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saved
                                                    Addresses</p>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-primary-a0">
                                                    {{ $user->addresses->count() }}</p>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- Recent Orders Preview --}}
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-primary-a0 mb-4">Recent Orders</h3>

                                    @if($allOrders->isEmpty())
                                        <div
                                            class="text-center py-10 bg-gray-50 dark:bg-surface-tonal-a30/30 rounded-xl border border-dashed border-gray-200 dark:border-surface-tonal-a30">
                                            <i data-feather="shopping-cart" class="h-10 w-10 text-gray-400 mx-auto mb-3"></i>
                                            <p class="text-gray-500 dark:text-gray-400">You haven't placed any orders yet.</p>
                                            <a href="{{ route('frontend.products.index') }}"
                                                class="mt-4 inline-flex items-center text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
                                                Start shopping <i data-feather="arrow-right" class="ml-1 w-4 h-4"></i>
                                            </a>
                                        </div>
                                    @else
                                        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-surface-tonal-a30">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-surface-tonal-a20">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Order #</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Date</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Status</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-surface-tonal-a10 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($allOrders->take(3) as $order)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-primary-a0">
                                                                {{ $order->uuid ?? '#' . $order->id }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $order->created_at->format('M d, Y') }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if(strtolower($order->status) == 'delivered') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                        @elseif(strtolower($order->status) == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                                                                    {{ ucfirst($order->status) }}
                                                                </span>
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-primary-a0">
                                                                ${{ number_format($order->grand_total ?? 0, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-4 text-right">
                                            <button @click="switchTab('orders')"
                                                class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                View All Orders &rarr;
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div x-show="activeTab === 'orders'" x-cloak x-transition.opacity.duration.300ms>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-primary-a0 mb-6">Order History</h2>

                                    <!-- Sub-tabs for All vs Active Orders -->
                                    <div x-data="{ orderTab: 'all' }" class="mb-8">
                                        <div class="border-b border-gray-200 dark:border-surface-tonal-a30 mb-6">
                                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                                <button @click="orderTab = 'all'"
                                                    :class="{'border-primary-500 text-primary-600 dark:text-primary-400': orderTab === 'all', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': orderTab !== 'all'}"
                                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                    All Orders ({{ $allOrders->count() }})
                                                </button>
                                                <button @click="orderTab = 'active'"
                                                    :class="{'border-primary-500 text-primary-600 dark:text-primary-400': orderTab === 'active', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': orderTab !== 'active'}"
                                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                                                    Active Orders
                                                    @if($activeOrders->count() > 0)
                                                        <span
                                                            class="ml-2 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 py-0.5 px-2.5 rounded-full text-xs shrink-0">{{ $activeOrders->count() }}</span>
                                                    @endif
                                                </button>
                                            </nav>
                                        </div>

                                        <!-- All Orders -->
                                        <div x-show="orderTab === 'all'" x-cloak>
                                            @if($allOrders->isEmpty())
                                                <div
                                                    class="text-center py-12 bg-gray-50 dark:bg-surface-tonal-a20/50 rounded-xl border border-dashed border-gray-200 dark:border-surface-tonal-a30">
                                                    <i data-feather="package" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-2">No orders
                                                        found</h3>
                                                    <p class="text-gray-500 dark:text-gray-400">You haven't placed any orders
                                                        with us yet.</p>
                                                </div>
                                            @else
                                                <div class="space-y-4">
                                                    @foreach($allOrders as $order)
                                                        @include('frontend.account.partials.order-card', ['order' => $order])
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Active Orders -->
                                        <div x-show="orderTab === 'active'" x-cloak>
                                            @if($activeOrders->isEmpty())
                                                <div
                                                    class="text-center py-12 bg-gray-50 dark:bg-surface-tonal-a20/50 rounded-xl border border-dashed border-gray-200 dark:border-surface-tonal-a30">
                                                    <i data-feather="truck" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-2">No active
                                                        orders</h3>
                                                    <p class="text-gray-500 dark:text-gray-400">You don't have any pending or
                                                        currently shipping orders.</p>
                                                </div>
                                            @else
                                                <div class="space-y-4">
                                                    @foreach($activeOrders as $order)
                                                        @include('frontend.account.partials.order-card', ['order' => $order])
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="activeTab === 'addresses'" x-cloak x-transition.opacity.duration.300ms>
                                <div x-data="{ showAddForm: false }">
                                    <div class="flex items-center justify-between mb-6">
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-primary-a0">Shipping Addresses</h2>
                                        <button @click="showAddForm = true"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <i data-feather="plus" class="w-4 h-4 mr-2"></i> Add New Address
                                        </button>
                                    </div>

                                    <!-- Add New Address Form Modal/Section -->
                                    <div x-show="showAddForm" x-transition.opacity x-cloak
                                        class="mb-8 bg-gray-50 dark:bg-surface-tonal-a20/50 rounded-xl border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                                        <div
                                            class="px-6 py-5 border-b border-gray-200 dark:border-surface-tonal-a30 flex justify-between items-center bg-white dark:bg-surface-tonal-a20">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Add New Address
                                            </h3>
                                            <button @click="showAddForm = false"
                                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                                <i data-feather="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                        <div class="p-6">
                                            <form action="{{ route('account.addresses.store') }}" method="POST">
                                                @csrf
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                                    <div>
                                                        <label for="type"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address
                                                            Type</label>
                                                        <select id="type" name="type" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                            <option value="shipping">Shipping Address</option>
                                                            <option value="billing">Billing Address</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="address_line_1"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address
                                                            Line 1</label>
                                                        <input type="text" name="address_line_1" id="address_line_1"
                                                            required placeholder="Street address, P.O. box, etc."
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="address_line_2"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address
                                                            Line 2 (Optional)</label>
                                                        <input type="text" name="address_line_2" id="address_line_2"
                                                            placeholder="Apartment, suite, unit, etc."
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="city"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                                                        <input type="text" name="city" id="city" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="state"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State
                                                            / Province</label>
                                                        <input type="text" name="state" id="state" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="postal_code"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Postal
                                                            / Zip Code</label>
                                                        <input type="text" name="postal_code" id="postal_code" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="country"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country</label>
                                                        <input type="text" name="country" id="country" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>

                                                    <div class="sm:col-span-2 flex items-center mt-2">
                                                        <input id="is_default" name="is_default" type="checkbox" value="1"
                                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:bg-surface-tonal-a30 dark:border-gray-600">
                                                        <label for="is_default"
                                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                                            Set as my default address
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="flex justify-end space-x-3">
                                                    <button type="button" @click="showAddForm = false"
                                                        class="bg-white dark:bg-surface-tonal-a20 text-gray-700 dark:text-gray-300 px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-800 transition-colors">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                        class="bg-primary-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-700 focus:ring-4 focus:ring-primary-500/30 transition-colors">
                                                        Save Address
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Address Grid -->
                                    @if($user->addresses->isEmpty())
                                        <div
                                            class="text-center py-16 bg-gray-50 dark:bg-surface-tonal-a20/50 rounded-xl border border-dashed border-gray-200 dark:border-surface-tonal-a30">
                                            <i data-feather="map" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0 mb-2">No addresses
                                                saved</h3>
                                            <p class="text-gray-500 dark:text-gray-400">Save a shipping address to make checkout
                                                faster next time.</p>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            @foreach($user->addresses as $address)
                                                <div
                                                    class="relative bg-white dark:bg-surface-tonal-a20 border {{ $address->is_default ? 'border-primary-500 ring-1 ring-primary-500' : 'border-gray-200 dark:border-surface-tonal-a30' }} rounded-xl shadow-sm p-6">

                                                    @if($address->is_default)
                                                        <span
                                                            class="absolute top-0 right-0 transform translate-x-2 -translate-y-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 shadow-sm border border-primary-200 dark:border-primary-800">
                                                            Default
                                                        </span>
                                                    @endif

                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300 mb-3 capitalize">
                                                                {{ $address->type }}
                                                            </span>
                                                            <address
                                                                class="not-italic text-sm text-gray-600 dark:text-gray-400 leading-relaxed shadow-sm">
                                                                <span
                                                                    class="block font-medium text-gray-900 dark:text-primary-a0 mb-1">{{ $address->first_name }}
                                                                    {{ $address->last_name }}</span>
                                                                {{ $address->address_line1 }}<br>
                                                                @if($address->address_line2) {{ $address->address_line2 }}<br>
                                                                @endif
                                                                {{ $address->city }}, {{ $address->province }}
                                                                {{ $address->postal_code }}<br>
                                                                {{ $address->country }}
                                                            </address>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="mt-6 pt-6 border-t border-gray-100 dark:border-surface-tonal-a30 flex items-center justify-between">
                                                        <div class="flex space-x-3 text-sm">
                                                            <button
                                                                class="text-primary-600 dark:text-primary-400 font-medium hover:text-primary-500 transition-colors">Edit</button>
                                                            <span class="text-gray-300 dark:text-gray-600">|</span>
                                                            <form action="{{ route('account.addresses.destroy', $address->id) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 font-medium hover:text-red-500 transition-colors">Delete</button>
                                                            </form>
                                                        </div>

                                                        @if(!$address->is_default)
                                                            <form action="{{ route('account.addresses.set-default', $address->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="text-sm text-gray-500 font-medium dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                                                                    Set as default
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div x-show="activeTab === 'profile'" x-cloak x-transition.opacity.duration.300ms>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-primary-a0 mb-6">Account Information
                                    </h2>

                                    <div
                                        class="bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 rounded-xl shadow-sm overflow-hidden mb-8">
                                        <div
                                            class="px-6 py-5 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20/50">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Profile Details
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your account's
                                                profile information and email address.</p>
                                        </div>

                                        <div class="p-6">
                                            <form action="{{ route('account.profile.update') }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                    <div>
                                                        <label for="first_name"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First
                                                            Name</label>
                                                        <input type="text" name="first_name" id="first_name"
                                                            value="{{ old('first_name', $customer->first_name) }}" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="last_name"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last
                                                            Name</label>
                                                        <input type="text" name="last_name" id="last_name"
                                                            value="{{ old('last_name', $customer->last_name) }}"
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <label for="email"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email
                                                            Address</label>
                                                        <input type="email" name="email" id="email"
                                                            value="{{ old('email', $user->email) }}" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="phone"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone
                                                            Number</label>
                                                        <input type="text" name="phone" id="phone"
                                                            value="{{ old('phone', $customer->phone) }}"
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="gender"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                                        <select id="gender" name="gender"
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                            <option value="">Prefer not to say</option>
                                                            <option value="male" {{ (old('gender', $customer->gender) == 'male') ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="female" {{ (old('gender', $customer->gender) == 'female') ? 'selected' : '' }}>Female
                                                            </option>
                                                            <option value="other" {{ (old('gender', $customer->gender) == 'other') ? 'selected' : '' }}>Other
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="flex justify-end">
                                                    <button type="submit"
                                                        class="bg-primary-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-700 focus:ring-4 focus:ring-primary-500/30 transition-colors">
                                                        Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    <div
                                        class="bg-white dark:bg-surface-tonal-a20 border border-gray-100 dark:border-surface-tonal-a30 rounded-xl shadow-sm overflow-hidden">
                                        <div
                                            class="px-6 py-5 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20/50">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-primary-a0">Update Password
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ensure your account is
                                                using a long, random password to stay secure.</p>
                                        </div>

                                        <div class="p-6">
                                            <form action="{{ route('account.password.update') }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="space-y-6 mb-6">
                                                    <div>
                                                        <label for="current_password"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current
                                                            Password</label>
                                                        <input type="password" name="current_password" id="current_password"
                                                            required
                                                            class="w-full md:w-1/2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="password"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New
                                                            Password</label>
                                                        <input type="password" name="password" id="password" required
                                                            class="w-full md:w-1/2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="password_confirmation"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm
                                                            Password</label>
                                                        <input type="password" name="password_confirmation"
                                                            id="password_confirmation" required
                                                            class="w-full md:w-1/2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-surface-tonal-a30 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    </div>
                                                </div>

                                                <div class="flex justify-start">
                                                    <button type="submit"
                                                        class="bg-gray-900 dark:bg-surface-tonal-a30 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-800 transition-colors">
                                                        Update Password
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="activeTab === 'wishlist'" x-cloak x-transition.opacity.duration.300ms>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-primary-a0 mb-6">My Wishlist</h2>

                                    {{-- Empty State (Assuming no wishlist items for now) --}}
                                    <div
                                        class="text-center py-16 bg-gray-50 dark:bg-surface-tonal-a20/50 rounded-xl border border-dashed border-gray-200 dark:border-surface-tonal-a30">
                                        <div
                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-pink-100 dark:bg-pink-900/30 mb-4">
                                            <i data-feather="heart" class="h-8 w-8 text-pink-600 dark:text-pink-400"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-primary-a0 mb-2">Your wishlist is
                                            empty</h3>
                                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                                            Find something you love but aren't ready to buy yet? Save it here for later.
                                        </p>
                                        <a href="{{ route('frontend.products.index') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            Browse Products
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div x-show="activeTab === 'returns'" x-cloak x-transition.opacity.duration.300ms>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-primary-a0 mb-6">Returns & Exchanges
                                    </h2>

                                    <div
                                        class="text-center py-16 bg-gray-50 dark:bg-surface-tonal-a20/50 rounded-xl border border-dashed border-gray-200 dark:border-surface-tonal-a30">
                                        <div
                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                                            <i data-feather="refresh-cw"
                                                class="h-8 w-8 text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-primary-a0 mb-2">Coming Soon</h3>
                                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                                            We are currently building out a robust self-service returns platform. For now,
                                            please contact our support team.
                                        </p>
                                        <a href="#"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 dark:bg-surface-tonal-a30 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                            Contact Support
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('accountTabs', (initialTab) => ({
                activeTab: initialTab,

                switchTab(tab) {
                    this.activeTab = tab;
                    // Optional: Update URL without reloading
                    window.history.pushState({}, '', '?tab=' + tab);

                    // Re-initialize feather icons within the unhidden content if necessary
                    setTimeout(() => {
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    }, 50);
                }
            }));
        });
    </script>
@endsection

