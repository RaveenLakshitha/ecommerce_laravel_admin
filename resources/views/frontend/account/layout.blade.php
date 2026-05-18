@extends('frontend.layouts.app')

@section('title', __('file.my_account'))
@section('body_class', 'light-page')

@section('content')
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        :root {
            --gold: #c8a96e;
            --gold-light: #dfcc9c;
            --gold-muted: rgba(200, 169, 110, 0.12);
            --bg-page: #f5f5f3;
            --bg-white: #ffffff;
            --bg-sidebar: #ffffff;
            --text-primary: #111111;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border: rgba(0, 0, 0, 0.07);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --sidebar-w: 260px;

            /* ── Two main font sizes ── */
            --fs-ui: 11px;
            /* labels, badges, nav, meta — uppercase only */
            --fs-body: 13px;
            /* all readable body copy, table values, form fields */
            --fs-stat: 22px;
            /* stat numbers */
            --fs-heading: 18px;
            /* section headings */

            --fw-regular: 400;
            --fw-medium: 500;
            --fw-bold: 700;

            --font-sans: 'Barlow', system-ui, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        .account-wrap {
            background: var(--bg-page);
            min-height: calc(100vh - 80px);
            font-family: var(--font-sans);
            color: var(--text-primary);
        }

        /* ════════════════════════════════
                           SIDEBAR
                        ════════════════════════════════ */
        .acc-sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            min-height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
            padding: 28px 16px 24px;
            flex-shrink: 0;
            position: sticky;
            top: 80px;
            height: calc(100vh - 80px);
            border-right: 1px solid var(--border);
        }

        .acc-sidebar__profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 24px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 24px;
        }

        .acc-sidebar__avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .acc-sidebar__avatar {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            display: block;
        }

        .acc-sidebar__online {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background: #22c55e;
            border: 2px solid var(--bg-sidebar);
            border-radius: 50%;
        }

        .acc-sidebar__name-block {
            overflow: hidden;
            flex: 1;
        }

        .acc-sidebar__greeting {
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            display: block;
            margin-bottom: 3px;
        }

        .acc-sidebar__name {
            font-size: var(--fs-body);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .acc-sidebar__nav-label {
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            padding: 0 8px;
            margin-bottom: 6px;
        }

        .acc-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            color: var(--text-secondary);
            background: transparent;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
            letter-spacing: 0.01em;
        }

        .acc-nav-item:hover {
            background: rgba(0, 0, 0, 0.03);
            color: var(--text-primary);
        }

        .acc-nav-item.active {
            background: var(--gold);
            color: #ffffff;
        }

        .acc-nav-item.active .acc-nav-icon {
            color: #ffffff;
            opacity: 1;
        }

        .acc-nav-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.6;
        }

        .acc-nav-item.active .acc-nav-icon {
            opacity: 1;
        }

        .acc-sidebar__logout {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .acc-sidebar__logout .acc-nav-item:hover {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
        }

        /* ════════════════════════════════
                           MAIN CONTENT
                        ════════════════════════════════ */
        .acc-main {
            flex: 1;
            min-width: 0;
            padding: 36px 40px;
        }

        @media (max-width: 1024px) {
            .acc-main {
                padding: 24px 16px;
            }
        }

        .acc-page-title {
            font-size: var(--fs-heading);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            letter-spacing: -0.01em;
            margin: 0 0 4px;
        }

        .acc-page-sub {
            font-size: var(--fs-body);
            color: var(--text-muted);
            margin: 0 0 28px;
        }

        /* ── Cards ── */
        .acc-card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
        }

        .acc-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .acc-card-header-title {
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--text-primary);
        }

        .acc-card-link {
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold);
            text-decoration: none;
        }

        .acc-card-link:hover {
            text-decoration: underline;
        }

        /* ── Stat cards ── */
        .acc-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        @media (max-width: 640px) {
            .acc-stat-grid {
                grid-template-columns: 1fr;
            }
        }

        .acc-stat {
            background: var(--bg-white);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .acc-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .acc-stat-label {
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            display: block;
            margin-bottom: 4px;
        }

        .acc-stat-num {
            font-size: var(--fs-stat);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            line-height: 1;
        }

        /* ── Order rows ── */
        .acc-order-row {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }

        .acc-order-row:last-child {
            border-bottom: none;
        }

        .acc-order-row:hover {
            background: #fafafa;
        }

        .acc-order-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            flex-shrink: 0;
            margin-right: 14px;
        }

        .acc-order-id {
            font-size: var(--fs-body);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .acc-order-date {
            font-size: var(--fs-ui);
            font-weight: var(--fw-medium);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .acc-order-total {
            font-size: var(--fs-body);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            text-align: right;
            margin-bottom: 4px;
        }

        .acc-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 2px 8px;
            border-radius: 100px;
        }

        .acc-badge--green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .acc-badge--red {
            background: #fef2f2;
            color: #dc2626;
        }

        .acc-badge--amber {
            background: #fffbeb;
            color: #d97706;
        }

        /* ── Tabs ── */
        .acc-tabs {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .acc-tab {
            padding: 10px 0;
            margin-right: 28px;
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            border: none;
            background: transparent;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: color 0.15s, border-color 0.15s;
        }

        .acc-tab.active,
        .acc-tab[aria-selected="true"] {
            color: var(--text-primary);
            border-bottom-color: var(--gold);
        }

        /* ── Form controls ── */
        .acc-form-label {
            display: block;
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .acc-input {
            width: 100%;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-sm);
            padding: 9px 14px;
            font-size: var(--fs-body);
            font-family: var(--font-sans);
            color: var(--text-primary);
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }

        .acc-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-muted);
        }

        .acc-select {
            width: 100%;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-sm);
            padding: 9px 14px;
            font-size: var(--fs-body);
            font-family: var(--font-sans);
            color: var(--text-primary);
            outline: none;
            cursor: pointer;
        }

        /* ── Buttons ── */
        .acc-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: var(--radius-sm);
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border: none;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }

        .acc-btn--dark {
            background: var(--bg-sidebar);
            color: #fff;
        }

        .acc-btn--dark:hover {
            background: var(--gold);
            color: #fff;
        }

        .acc-btn--gold {
            background: var(--gold);
            color: #fff;
        }

        .acc-btn--gold:hover {
            background: var(--bg-sidebar);
        }

        .acc-btn--ghost {
            background: transparent;
            color: var(--text-muted);
            padding: 8px 0;
        }

        .acc-btn--ghost:hover {
            color: var(--text-primary);
        }

        /* ── Address cards ── */
        .acc-address-card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: border-color 0.15s;
        }

        .acc-address-card--default {
            border-color: var(--gold);
        }

        .acc-address-type {
            display: inline-block;
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            background: #f3f4f6;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .acc-address-name {
            font-size: var(--fs-body);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            margin: 10px 0 6px;
        }

        .acc-address-lines {
            font-size: var(--fs-body);
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .acc-address-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            margin-top: 14px;
            border-top: 1px solid var(--border);
        }

        .acc-address-action {
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.15s;
            padding: 0;
        }

        .acc-address-action:hover {
            color: var(--text-primary);
        }

        .acc-address-action--delete:hover {
            color: #dc2626;
        }

        .acc-address-action--gold {
            color: var(--gold);
        }

        .acc-address-action--gold:hover {
            color: var(--bg-sidebar);
        }

        .acc-default-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold);
        }

        /* ── Profile layout ── */
        .acc-profile-avatar-card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 28px 20px;
            text-align: center;
        }

        .acc-profile-avatar-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 16px;
        }

        .acc-profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-md);
            object-fit: cover;
            border: 3px solid var(--bg-white);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            display: block;
        }

        .acc-profile-camera {
            position: absolute;
            bottom: -6px;
            right: -6px;
            background: var(--bg-sidebar);
            color: #fff;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            border: 2px solid var(--bg-white);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s;
        }

        .acc-profile-camera:hover {
            background: var(--gold);
        }

        .acc-profile-name {
            font-size: var(--fs-heading);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .acc-profile-email {
            font-size: var(--fs-body);
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .acc-profile-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0 0 16px;
        }

        .acc-profile-stats {
            display: flex;
            justify-content: center;
            gap: 0;
        }

        .acc-profile-stat {
            flex: 1;
            text-align: center;
            padding: 0 12px;
            border-right: 1px solid var(--border);
        }

        .acc-profile-stat:last-child {
            border-right: none;
        }

        .acc-profile-stat-label {
            font-size: var(--fs-ui);
            font-weight: var(--fw-bold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            display: block;
            margin-bottom: 4px;
        }

        .acc-profile-stat-num {
            font-size: var(--fs-heading);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
        }

        /* ── Empty states ── */
        .acc-empty {
            padding: 56px 20px;
            text-align: center;
        }

        .acc-empty-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .acc-empty-title {
            font-size: var(--fs-body);
            font-weight: var(--fw-bold);
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .acc-empty-sub {
            font-size: var(--fs-body);
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        /* ── Mobile nav ── */
        .acc-mobile-nav {
            display: none;
            margin-bottom: 24px;
        }

        @media (max-width: 1024px) {
            .acc-mobile-nav {
                display: block;
            }

            .acc-sidebar {
                display: none;
            }
        }

        [x-cloak] {
            display: none !important;
        }

        @media (max-width: 640px) {
            .acc-main {
                padding: 20px 14px;
            }
        }
    </style>

    @php $activeTab = request()->get('tab', 'dashboard'); @endphp

    <div class="account-wrap">
        <div class="max-w-6xl mx-auto flex">

            {{-- ══ SIDEBAR ══ --}}
            <aside class="acc-sidebar">

                {{-- Profile block --}}
                <div class="acc-sidebar__profile">
                    <div class="acc-sidebar__avatar-wrap">
                        <img class="acc-sidebar__avatar"
                            src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=C8A96E&color=FFF' }}"
                            alt="{{ $user->name }}">
                        <div class="acc-sidebar__online"></div>
                    </div>
                    <div class="acc-sidebar__name-block">
                        <span class="acc-sidebar__greeting">{{ __('file.welcome_back') }}</span>
                        <span class="acc-sidebar__name">{{ $customer->first_name ?? explode(' ', $user->name)[0] }}</span>
                    </div>
                </div>

                {{-- Nav group --}}
                <p class="acc-sidebar__nav-label">Menu</p>

                @php
                    $navItems = [
                        ['id' => 'dashboard', 'label' => __('file.dashboard'), 'icon' => 'grid'],
                        ['id' => 'orders', 'label' => __('file.order_history'), 'icon' => 'shopping-bag'],
                        ['id' => 'addresses', 'label' => __('file.addresses'), 'icon' => 'map-pin'],
                        ['id' => 'profile', 'label' => __('file.account_info'), 'icon' => 'user'],
                        ['id' => 'wishlist', 'label' => __('file.wishlist'), 'icon' => 'heart'],
                        ['id' => 'returns', 'label' => __('file.returns'), 'icon' => 'refresh-cw'],
                    ];
                @endphp

                <nav>
                    @foreach($navItems as $item)
                        <a href="{{ route('account.dashboard') }}?tab={{ $item['id'] }}"
                            class="acc-nav-item {{ $activeTab === $item['id'] ? 'active' : '' }}">
                            <i data-feather="{{ $item['icon'] }}" class="acc-nav-icon"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

            </aside>

            {{-- ══ MAIN ══ --}}
            <main class="acc-main">

                {{-- Mobile nav dropdown --}}
                <div class="acc-mobile-nav" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between bg-white px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        @php $currentNav = collect($navItems)->firstWhere('id', $activeTab) ?? $navItems[0]; @endphp
                        <div class="flex items-center gap-3">
                            <i data-feather="{{ $currentNav['icon'] }}" class="w-4 h-4" style="color: var(--gold)"></i>
                            <span
                                style="font-size:var(--fs-ui); font-weight:var(--fw-bold); text-transform:uppercase; letter-spacing:0.1em; color: var(--text-primary)">
                                {{ $currentNav['label'] }}
                            </span>
                        </div>
                        <i data-feather="chevron-down" class="w-3.5 h-3.5 transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak x-transition
                        class="absolute left-4 right-4 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        @foreach($navItems as $item)
                            <a href="{{ route('account.dashboard') }}?tab={{ $item['id'] }}"
                                class="flex items-center gap-3 px-4 py-3 border-b border-gray-50 last:border-0 transition-colors
                                                            {{ $activeTab === $item['id'] ? 'text-gray-900 bg-gray-50' : 'text-gray-500 hover:bg-gray-50' }}">
                                <i data-feather="{{ $item['icon'] }}"
                                    class="w-4 h-4 {{ $activeTab === $item['id'] ? 'text-gold' : 'text-gray-400' }}"></i>
                                <span
                                    style="font-size:var(--fs-ui); font-weight:var(--fw-bold); text-transform:uppercase; letter-spacing:0.1em">
                                    {{ $item['label'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- ══ DASHBOARD ══ --}}
                @if($activeTab === 'dashboard')
                    <div>
                        <h1 class="acc-page-title">{{ __('file.hello') }}, {{ $customer->first_name ?? $user->name }}</h1>
                        <p class="acc-page-sub">{{ __('file.account_dashboard_note') }}</p>

                        <div class="acc-stat-grid">
                            <div class="acc-stat">
                                <div class="acc-stat-icon" style="background:#eff6ff; color:#3b82f6">
                                    <i data-feather="shopping-bag" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="acc-stat-label">{{ __('file.total_orders') }}</span>
                                    <span class="acc-stat-num">{{ $totalOrders }}</span>
                                </div>
                            </div>
                            <div class="acc-stat">
                                <div class="acc-stat-icon" style="background:#fdf2f8; color:#ec4899">
                                    <i data-feather="heart" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="acc-stat-label">{{ __('file.wishlist_items') }}</span>
                                    <span class="acc-stat-num">{{ $wishlistItems }}</span>
                                </div>
                            </div>
                            <div class="acc-stat">
                                <div class="acc-stat-icon" style="background:#f5f3ff; color:#8b5cf6">
                                    <i data-feather="map-pin" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="acc-stat-label">{{ __('file.addresses') }}</span>
                                    <span class="acc-stat-num">{{ $user->addresses->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="acc-card">
                            <div class="acc-card-header">
                                <span class="acc-card-header-title">{{ __('file.recent_orders') }}</span>
                                <a href="{{ route('account.dashboard') }}?tab=orders"
                                    class="acc-card-link">{{ __('file.view_all') }}</a>
                            </div>
                            <div>
                                @forelse($allOrders->take(3) as $order)
                                    <div class="acc-order-row">
                                        <div class="flex items-center">
                                            <div class="acc-order-icon">
                                                <i data-feather="package" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <div class="acc-order-id">#{{ $order->id }}</div>
                                                <div class="acc-order-date">{{ $order->created_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="acc-order-total">@price($order->grand_total ?? 0)</div>
                                            <span class="acc-badge
                                                                                @if(strtolower($order->status) == 'delivered') acc-badge--green
                                                                                @elseif(strtolower($order->status) == 'cancelled') acc-badge--red
                                                                                @else acc-badge--amber @endif">
                                                {{ $order->status }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="acc-empty">
                                        <i data-feather="inbox" class="w-6 h-6 mx-auto mb-2" style="color:#d1d5db"></i>
                                        <p class="acc-empty-title">{{ __('file.no_orders_found') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ══ ORDERS ══ --}}
                @if($activeTab === 'orders')
                    <div>
                        <h2 class="acc-page-title" style="margin-bottom: 20px">{{ __('file.order_history') }}</h2>

                        <div x-data="{ orderTab: 'all' }">
                            <div class="acc-tabs">
                                <button @click="orderTab = 'all'" :class="{ 'active': orderTab === 'all' }" class="acc-tab">
                                    {{ __('file.all_orders') }} ({{ $allOrders->count() }})
                                </button>
                                <button @click="orderTab = 'active'" :class="{ 'active': orderTab === 'active' }" class="acc-tab">
                                    {{ __('file.active_orders') }} ({{ $activeOrders->count() }})
                                </button>
                            </div>

                            <div x-show="orderTab === 'all'" class="space-y-3">
                                @forelse($allOrders as $order)
                                    @include('frontend.account.partials.order-card', ['order' => $order])
                                @empty
                                    <div class="acc-card">
                                        <div class="acc-empty">
                                            <p class="acc-empty-title">{{ __('file.no_orders_found') }}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <div x-show="orderTab === 'active'" class="space-y-3" x-cloak>
                                @forelse($activeOrders as $order)
                                    @include('frontend.account.partials.order-card', ['order' => $order])
                                @empty
                                    <div class="acc-card">
                                        <div class="acc-empty">
                                            <p class="acc-empty-title">{{ __('file.no_active_orders') }}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ══ ADDRESSES ══ --}}
                @if($activeTab === 'addresses')
                    <div x-data="{ showAddForm: false }">
                        <div class="flex items-center justify-between" style="margin-bottom: 20px">
                            <h2 class="acc-page-title">{{ __('file.addresses') }}</h2>
                            <button @click="showAddForm = !showAddForm" class="acc-btn acc-btn--dark">
                                <i data-feather="plus" class="w-3.5 h-3.5"></i>
                                {{ __('file.add_new') }}
                            </button>
                        </div>

                        {{-- Add form --}}
                        <div x-show="showAddForm" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" style="margin-bottom: 24px">
                            <form action="{{ route('account.addresses.store') }}" method="POST" class="acc-card">
                                @csrf
                                <div class="acc-card-header">
                                    <span class="acc-card-header-title">{{ __('file.add_new_address') }}</span>
                                </div>
                                <div style="padding: 20px">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="acc-form-label">{{ __('file.address_type') }}</label>
                                            <select name="type" required class="acc-select">
                                                <option value="shipping">{{ __('file.shipping') }}</option>
                                                <option value="billing">{{ __('file.billing') }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="acc-form-label">{{ __('file.first_name') }}</label>
                                            <input type="text" name="first_name" required class="acc-input">
                                        </div>
                                        <div>
                                            <label class="acc-form-label">{{ __('file.last_name') }}</label>
                                            <input type="text" name="last_name" required class="acc-input">
                                        </div>
                                        <div>
                                            <label class="acc-form-label">{{ __('file.phone_number') }}</label>
                                            <input type="text" name="phone" required class="acc-input">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="acc-form-label">{{ __('file.address_line_1') }}</label>
                                            <input type="text" name="address_line1" required class="acc-input">
                                        </div>
                                        <div>
                                            <label class="acc-form-label">{{ __('file.city') }}</label>
                                            <input type="text" name="city" required class="acc-input">
                                        </div>
                                        <div>
                                            <label class="acc-form-label">{{ __('file.postal_code') }}</label>
                                            <input type="text" name="postal_code" required class="acc-input">
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between" style="margin-top: 20px">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="is_default" value="1"
                                                class="w-4 h-4 rounded border-gray-300" style="accent-color: var(--gold)">
                                            <span
                                                style="font-size:var(--fs-ui); font-weight:var(--fw-bold); text-transform:uppercase; letter-spacing:0.1em; color:var(--text-muted)">
                                                {{ __('file.set_as_default') }}
                                            </span>
                                        </label>
                                        <div class="flex gap-3 items-center">
                                            <button type="button" @click="showAddForm = false" class="acc-btn acc-btn--ghost">
                                                {{ __('file.cancel') }}
                                            </button>
                                            <button type="submit" class="acc-btn acc-btn--gold">
                                                {{ __('file.save_address') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($user->addresses as $address)
                                <div class="acc-address-card {{ $address->is_default ? 'acc-address-card--default' : '' }}">
                                    <div>
                                        <div class="flex items-center justify-between" style="margin-bottom: 10px">
                                            <span class="acc-address-type">{{ $address->type }}</span>
                                            @if($address->is_default)
                                                <span class="acc-default-badge">
                                                    <i data-feather="check-circle" class="w-3 h-3"></i>
                                                    {{ __('file.default') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="acc-address-name">{{ $address->first_name }} {{ $address->last_name }}</p>
                                        <div class="acc-address-lines">
                                            <p>{{ $address->address_line1 }}</p>
                                            @if($address->address_line2)
                                            <p>{{ $address->address_line2 }}</p>@endif
                                            <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                            <p>{{ $address->country }}</p>
                                        </div>
                                    </div>
                                    <div class="acc-address-footer">
                                        <div class="flex gap-4">
                                            <button class="acc-address-action">{{ __('file.edit') }}</button>
                                            <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST"
                                                onsubmit="return confirm('{{ __('file.confirm_delete') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="acc-address-action acc-address-action--delete">
                                                    {{ __('file.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                        @if(!$address->is_default)
                                            <form action="{{ route('account.addresses.set-default', $address->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="acc-address-action acc-address-action--gold">
                                                    {{ __('file.set_default') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="md:col-span-2 acc-card">
                                    <div class="acc-empty">
                                        <div class="acc-empty-icon" style="background:#f3f4f6; color:#9ca3af">
                                            <i data-feather="map" class="w-5 h-5"></i>
                                        </div>
                                        <p class="acc-empty-title">{{ __('file.no_addresses_saved') }}</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                {{-- ══ PROFILE ══ --}}
                @if($activeTab === 'profile')
                    <div>
                        <h2 class="acc-page-title" style="margin-bottom: 24px">{{ __('file.account_info') }}</h2>

                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                            {{-- Avatar card --}}
                            <div class="xl:col-span-1">
                                <div class="acc-profile-avatar-card">
                                    <div class="acc-profile-avatar-wrap">
                                        <img class="acc-profile-avatar"
                                            src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=C8A96E&color=FFF' }}"
                                            alt="{{ $user->name }}">
                                        <button class="acc-profile-camera">
                                            <i data-feather="camera" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                    <p class="acc-profile-name">{{ $user->name }}</p>
                                    <p class="acc-profile-email">{{ $user->email }}</p>
                                    <hr class="acc-profile-divider">
                                    <div class="acc-profile-stats">
                                        <div class="acc-profile-stat">
                                            <span class="acc-profile-stat-label">{{ __('file.orders') }}</span>
                                            <span class="acc-profile-stat-num">{{ $totalOrders }}</span>
                                        </div>
                                        <div class="acc-profile-stat">
                                            <span class="acc-profile-stat-label">{{ __('file.wishlist') }}</span>
                                            <span class="acc-profile-stat-num">{{ $wishlistItems }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Forms --}}
                            <div class="xl:col-span-2" style="display:flex; flex-direction:column; gap:20px">
                                <form action="{{ route('account.profile.update') }}" method="POST" class="acc-card">
                                    @csrf @method('PUT')
                                    <div class="acc-card-header">
                                        <span class="acc-card-header-title">{{ __('file.personal_details') }}</span>
                                    </div>
                                    <div style="padding: 20px">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="acc-form-label">{{ __('file.first_name') }}</label>
                                                <input type="text" name="first_name"
                                                    value="{{ old('first_name', $customer->first_name) }}" required
                                                    class="acc-input">
                                            </div>
                                            <div>
                                                <label class="acc-form-label">{{ __('file.last_name') }}</label>
                                                <input type="text" name="last_name"
                                                    value="{{ old('last_name', $customer->last_name) }}" class="acc-input">
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="acc-form-label">{{ __('file.email_address') }}</label>
                                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                                    required class="acc-input">
                                            </div>
                                        </div>
                                        <div class="flex justify-end" style="margin-top:16px">
                                            <button type="submit"
                                                class="acc-btn acc-btn--dark">{{ __('file.save_changes') }}</button>
                                        </div>
                                    </div>
                                </form>

                                <form action="{{ route('account.password.update') }}" method="POST" class="acc-card">
                                    @csrf @method('PUT')
                                    <div class="acc-card-header">
                                        <span class="acc-card-header-title">{{ __('file.update_password') }}</span>
                                    </div>
                                    <div style="padding: 20px">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="sm:col-span-2">
                                                <label class="acc-form-label">{{ __('file.current_password') }}</label>
                                                <input type="password" name="current_password" required class="acc-input">
                                            </div>
                                            <div>
                                                <label class="acc-form-label">{{ __('file.new_password') }}</label>
                                                <input type="password" name="password" required class="acc-input">
                                            </div>
                                            <div>
                                                <label class="acc-form-label">{{ __('file.confirm_password') }}</label>
                                                <input type="password" name="password_confirmation" required class="acc-input">
                                            </div>
                                        </div>
                                        <div class="flex justify-end" style="margin-top:16px">
                                            <button type="submit"
                                                class="acc-btn acc-btn--dark">{{ __('file.update_password') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ══ WISHLIST ══ --}}
                @if($activeTab === 'wishlist')
                    <div>
                        <h2 class="acc-page-title" style="margin-bottom: 24px">{{ __('file.my_wishlist') }}</h2>
                        
                        @if($user->wishlists->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                                @foreach($user->wishlists as $wishlist)
                                    @php $product = $wishlist->product; @endphp
                                    <div class="acc-card relative group flex flex-col justify-between" style="padding: 16px; transition: box-shadow 0.2s; overflow: hidden;">
                                        <form action="{{ route('wishlist.destroy', $product->id) }}" method="POST" class="absolute top-2 right-2 z-10">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-400 hover:text-red-500 hover:border-red-100 hover:bg-red-50 transition-all shadow-sm" title="Remove from wishlist">
                                                <i data-feather="x" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('frontend.products.show', $product->slug) }}" class="block text-center mb-4 mt-2">
                                            <img src="{{ $product->primaryImage ? $product->primaryImage->url : 'https://placehold.co/400?text=No+Image' }}" alt="{{ $product->name }}" class="w-full h-36 object-contain mb-3">
                                            <h3 class="text-sm font-bold text-gray-900 line-clamp-2 leading-tight" style="font-family: var(--font-sans)">{{ $product->name }}</h3>
                                        </a>
                                        
                                        <div class="mt-auto">
                                            <div class="flex items-center justify-between mb-3">
                                                @php
                                                    $defaultVariant = $product->variants->where('is_default', true)->first() ?? $product->variants->first();
                                                    $displayPrice = $defaultVariant ? ($defaultVariant->sale_price ?? $defaultVariant->price) : $product->base_price;
                                                @endphp
                                                <span class="font-bold text-gray-900" style="font-size: 15px">@price($displayPrice)</span>
                                            </div>
                                            <a href="{{ route('frontend.products.show', $product->slug) }}" class="acc-btn acc-btn--dark w-full justify-center" style="padding: 8px; font-size: 11px;">
                                                <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                                {{ __('file.view_details') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="acc-card">
                                <div class="acc-empty">
                                    <div class="acc-empty-icon" style="background:#fdf2f8; color:#ec4899">
                                        <i data-feather="heart" class="w-5 h-5"></i>
                                    </div>
                                    <p class="acc-empty-title">{{ __('file.your_wishlist_is_empty') }}</p>
                                    <p class="acc-empty-sub">{{ __('file.wishlist_empty_note') }}</p>
                                    <a href="{{ route('frontend.products.index') }}" class="acc-btn acc-btn--dark">
                                        {{ __('file.start_shopping') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ══ RETURNS ══ --}}
                @if($activeTab === 'returns')
                    <div>
                        <h2 class="acc-page-title" style="margin-bottom: 24px">{{ __('file.returns') }}</h2>
                        <div class="acc-card">
                            <div class="acc-empty">
                                <div class="acc-empty-icon" style="background:#eff6ff; color:#3b82f6">
                                    <i data-feather="refresh-cw" class="w-5 h-5"></i>
                                </div>
                                <p class="acc-empty-title">{{ __('file.coming_soon') }}</p>
                                <p class="acc-empty-sub">{{ __('file.returns_coming_soon_note') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            else setTimeout(() => { if (window.feather) feather.replace(); }, 600);
        });
    </script>
@endsection