@extends('layouts.app')

@section('title', 'Order Fulfillment Manager')

@section('content')
    <style>
        /* ── shadcn/ui CSS variable tokens ── */
        :root {
            --background: 0 0% 100%;
            --foreground: 240 10% 3.9%;
            --card: 0 0% 100%;
            --card-foreground: 240 10% 3.9%;
            --popover: 0 0% 100%;
            --popover-foreground: 240 10% 3.9%;
            --primary: 240 5.9% 10%;
            --primary-foreground: 0 0% 98%;
            --secondary: 240 4.8% 95.9%;
            --secondary-foreground: 240 5.9% 10%;
            --muted: 240 4.8% 95.9%;
            --muted-foreground: 240 3.8% 46.1%;
            --accent: 240 4.8% 95.9%;
            --accent-foreground: 240 5.9% 10%;
            --destructive: 0 84.2% 60.2%;
            --destructive-foreground: 0 0% 98%;
            --border: 240 5.9% 90%;
            --input: 240 5.9% 90%;
            --ring: 240 5.9% 10%;
            --radius: 0.5rem;
            --sidebar: 240 4.8% 97%;
            --font-sans: 'Geist', 'Inter', ui-sans-serif, system-ui, sans-serif;
            --font-mono: 'Geist Mono', 'JetBrains Mono', ui-monospace, monospace;
        }

        .dark {
            --background: 240 10% 3.9%;
            --foreground: 0 0% 98%;
            --card: 240 10% 3.9%;
            --card-foreground: 0 0% 98%;
            --muted: 240 3.7% 15.9%;
            --muted-foreground: 240 5% 64.9%;
            --border: 240 3.7% 15.9%;
            --input: 240 3.7% 15.9%;
            --secondary: 240 3.7% 15.9%;
            --secondary-foreground: 0 0% 98%;
            --accent: 240 3.7% 15.9%;
            --accent-foreground: 0 0% 98%;
            --sidebar: 240 6% 7%;
        }

        @import url('https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap');

        * {
            box-sizing: border-box;
        }

        .ofm-wrap {
            font-family: var(--font-sans);
            display: flex;
            flex-direction: column;
            height: calc(100vh - 4rem);
            padding: 1.5rem 1.5rem 1rem;
            background: hsl(var(--background));
            color: hsl(var(--foreground));
            overflow: hidden;
        }

        /* ── Header ── */
        .ofm-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            flex-shrink: 0;
        }

        .ofm-header-left h1 {
            font-size: 1.375rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.2;
            color: hsl(var(--foreground));
            margin: 0 0 0.2rem;
        }

        .ofm-header-left p {
            font-size: 0.8125rem;
            color: hsl(var(--muted-foreground));
            margin: 0;
        }

        .ofm-header-right {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            flex-wrap: wrap;
        }

        /* shadcn Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid transparent;
            line-height: 1;
        }

        .badge-outline {
            border-color: hsl(var(--border));
            color: hsl(var(--muted-foreground));
            background: transparent;
        }

        .badge-live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, .25);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.45;
            }
        }

        /* shadcn Button */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: 0.4375rem 0.875rem;
            border-radius: var(--radius);
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1;
            cursor: pointer;
            transition: background 120ms, opacity 120ms, box-shadow 120ms;
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-outline {
            background: hsl(var(--background));
            border-color: hsl(var(--border));
            color: hsl(var(--foreground));
        }

        .btn-outline:hover {
            background: hsl(var(--accent));
        }

        .btn-primary {
            background: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
        }

        .btn-primary:hover {
            opacity: 0.88;
        }

        .btn-secondary {
            background: hsl(var(--secondary));
            color: hsl(var(--secondary-foreground));
            border-color: hsl(var(--border));
        }

        .btn-secondary:hover {
            background: hsl(var(--muted));
        }

        .btn-ghost {
            background: transparent;
            color: hsl(var(--muted-foreground));
            border-color: transparent;
        }

        .btn-ghost:hover {
            background: hsl(var(--accent));
            color: hsl(var(--accent-foreground));
        }

        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            border-radius: calc(var(--radius) - 2px);
        }

        /* ── Kanban ── */
        .ofm-board {
            flex: 1;
            overflow-x: auto;
            overflow-y: hidden;
            /* custom scrollbar */
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--border)) transparent;
        }

        .ofm-board::-webkit-scrollbar {
            height: 5px;
        }

        .ofm-board::-webkit-scrollbar-track {
            background: transparent;
        }

        .ofm-board::-webkit-scrollbar-thumb {
            background: hsl(var(--border));
            border-radius: 99px;
        }

        .ofm-columns {
            display: flex;
            gap: 0.875rem;
            height: 100%;
            min-width: 900px;
        }

        /* ── Column ── */
        .kanban-col {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 220px;
            background: hsl(var(--sidebar));
            border: 1px solid hsl(var(--border));
            border-radius: calc(var(--radius) + 2px);
            overflow: hidden;
        }

        .col-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0.875rem;
            background: hsl(var(--card));
            border-bottom: 1px solid hsl(var(--border));
            flex-shrink: 0;
            border-top: 3px solid var(--col-accent);
        }

        .col-header-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .col-icon {
            width: 1rem;
            height: 1rem;
            color: var(--col-accent);
            flex-shrink: 0;
        }

        .col-title {
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: hsl(var(--foreground));
        }

        .col-count {
            font-size: 0.6875rem;
            font-weight: 500;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            background: hsl(var(--muted));
            color: hsl(var(--muted-foreground));
            border: 1px solid hsl(var(--border));
        }

        .col-body {
            flex: 1;
            overflow-y: auto;
            padding: 0.625rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--border)) transparent;
        }

        .col-body::-webkit-scrollbar {
            width: 4px;
        }

        .col-body::-webkit-scrollbar-thumb {
            background: hsl(var(--border));
            border-radius: 99px;
        }

        /* ── Order Card ── */
        .order-card {
            background: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            padding: 0.75rem;
            cursor: grab;
            transition: box-shadow 150ms, transform 150ms, border-color 150ms;
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--col-accent);
            border-radius: 0 2px 2px 0;
            opacity: 0;
            transition: opacity 150ms;
        }

        .order-card:hover {
            box-shadow: 0 4px 16px -4px rgba(0, 0, 0, .12);
            transform: translateY(-1px);
            border-color: hsl(var(--ring) / .3);
        }

        .order-card:hover::before {
            opacity: 1;
        }

        .order-card:active {
            cursor: grabbing;
        }

        .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .card-order-id {
            font-size: 0.8rem;
            font-weight: 600;
            color: hsl(var(--foreground));
            text-decoration: none;
            font-family: var(--font-mono);
            letter-spacing: -0.01em;
        }

        .card-order-id:hover {
            text-decoration: underline;
        }

        .card-time {
            font-size: 0.6875rem;
            color: hsl(var(--muted-foreground));
        }

        .card-name {
            font-size: 0.8125rem;
            font-weight: 500;
            color: hsl(var(--foreground));
            line-height: 1.3;
            margin: 0 0 0.2rem;
        }

        .card-meta {
            font-size: 0.6875rem;
            color: hsl(var(--muted-foreground));
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin: 0;
        }

        .card-meta svg {
            width: 0.7rem;
            height: 0.7rem;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.625rem;
            padding-top: 0.5rem;
            border-top: 1px solid hsl(var(--border));
        }

        /* shadcn-style status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.175rem 0.5rem;
            border-radius: calc(var(--radius) - 2px);
            font-size: 0.6875rem;
            font-weight: 500;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .dark .status-paid {
            background: rgba(22, 101, 52, .2);
            color: #86efac;
        }

        /* Tracking bar */
        .tracking-wrap {
            margin-top: 0.5rem;
        }

        .tracking-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.625rem;
            color: hsl(var(--muted-foreground));
            margin-bottom: 0.25rem;
        }

        .tracking-bar {
            height: 3px;
            background: hsl(var(--border));
            border-radius: 99px;
            overflow: hidden;
        }

        .tracking-fill {
            height: 100%;
            background: var(--col-accent);
            border-radius: 99px;
            transition: width 600ms ease;
        }

        /* Tracking number blur */
        .trk-num {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.15rem;
            filter: blur(3px);
            transition: filter 200ms;
            cursor: pointer;
        }

        .trk-num:hover {
            filter: blur(0);
        }

        /* Carrier chip */
        .carrier-chip {
            font-size: 0.6875rem;
            font-weight: 600;
            font-family: var(--font-mono);
            letter-spacing: 0.03em;
            color: hsl(var(--muted-foreground));
            background: hsl(var(--muted));
            padding: 0.15rem 0.45rem;
            border-radius: calc(var(--radius) - 4px);
            border: 1px solid hsl(var(--border));
        }

        /* Delivered card dimmed */
        .card-delivered {
            opacity: 0.6;
            transition: opacity 150ms;
        }

        .card-delivered:hover {
            opacity: 1;
        }

        .delivered-check {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.6875rem;
            font-weight: 500;
            color: #16a34a;
        }

        .delivered-check svg {
            width: 0.7rem;
            height: 0.7rem;
        }

        /* ── Responsive tweaks ── */
        @media (max-width: 768px) {
            .ofm-wrap {
                padding: 1rem 0.75rem 0.75rem;
                height: auto;
                overflow: visible;
            }

            .ofm-columns {
                min-width: 680px;
            }

            .ofm-header-left h1 {
                font-size: 1.125rem;
            }
        }

        @media (max-width: 480px) {
            .ofm-wrap {
                padding: 0.75rem 0.625rem;
            }

            .ofm-header-right .badge {
                display: none;
            }
        }
    </style>

    <div class="ofm-wrap">

        {{-- ── Header ── --}}
        <div class="ofm-header">
            <div class="ofm-header-left">
                <h1>Order Manager</h1>
                <p>Manage orders across their fulfillment lifecycle</p>
            </div>
            <div class="ofm-header-right">
                <span class="badge badge-outline">
                    <span class="badge-live-dot"></span>
                    Live
                </span>
                <a href="{{ route('orders.index') }}" class="btn btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                    </svg>
                    All Orders
                </a>
            </div>
        </div>

        {{-- ── Board ── --}}
        <div class="ofm-board">
            <div class="ofm-columns">

                {{-- ── Column 1: New / Pending ── --}}
                <div class="kanban-col" style="--col-accent:#f59e0b;">
                    <div class="col-header">
                        <div class="col-header-left">
                            <svg class="col-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <span class="col-title">New Orders</span>
                        </div>
                        <span class="col-count">3</span>
                    </div>
                    <div class="col-body">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="order-card" style="--col-accent:#f59e0b;">
                                <div class="card-top">
                                    <a href="#" class="card-order-id">#ORD-{{ 5040 + $i }}</a>
                                    <span class="card-time">2 min ago</span>
                                </div>
                                <p class="card-name">John Doe {{ $i }}</p>
                                <p class="card-meta">3 items &middot; $145.00</p>
                                <div class="card-footer">
                                    <span class="status-badge status-paid">Paid</span>
                                    <button class="btn btn-primary btn-sm">Accept</button>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ── Column 2: Packing ── --}}
                <div class="kanban-col" style="--col-accent:#3b82f6;">
                    <div class="col-header">
                        <div class="col-header-left">
                            <svg class="col-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span class="col-title">Packing</span>
                        </div>
                        <span class="col-count">4</span>
                    </div>
                    <div class="col-body">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="order-card" style="--col-accent:#3b82f6;">
                                <div class="card-top">
                                    <a href="#" class="card-order-id">#ORD-{{ 4010 + $i }}</a>
                                    <span class="card-time">1 hr ago</span>
                                </div>
                                <p class="card-name">Sarah Smith {{ $i }}</p>
                                <p class="card-meta">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <circle cx="12" cy="11" r="3" />
                                    </svg>
                                    New York, NY
                                </p>
                                <div class="card-footer">
                                    <button class="btn btn-secondary btn-sm">Print Label</button>
                                    <button class="btn btn-primary btn-sm">Ready to Ship</button>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ── Column 3: In Transit ── --}}
                <div class="kanban-col" style="--col-accent:#6366f1;">
                    <div class="col-header">
                        <div class="col-header-left">
                            <svg class="col-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13" rx="1" />
                                <path d="M16 8h4l3 5v3h-7V8z" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                            <span class="col-title">In Transit</span>
                        </div>
                        <span class="col-count">2</span>
                    </div>
                    <div class="col-body">
                        @for($i = 1; $i <= 2; $i++)
                            <div class="order-card" style="--col-accent:#6366f1;">
                                <div class="card-top">
                                    <a href="#" class="card-order-id">#ORD-{{ 3020 + $i }}</a>
                                    <span class="carrier-chip">FedEx</span>
                                </div>
                                <p class="card-name">Michael Brown {{ $i }}</p>
                                <p class="trk-num" title="Click to reveal">TRK-99283{{ $i }}</p>
                                <div class="tracking-wrap">
                                    <div class="tracking-labels">
                                        <span>Shipped</span>
                                        <span>Out for delivery</span>
                                    </div>
                                    <div class="tracking-bar">
                                        <div class="tracking-fill" style="width:75%"></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <span></span>
                                    <button class="btn btn-outline btn-sm">Mark Delivered</button>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ── Column 4: Delivered ── --}}
                <div class="kanban-col" style="--col-accent:#22c55e;">
                    <div class="col-header">
                        <div class="col-header-left">
                            <svg class="col-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            <span class="col-title">Delivered</span>
                        </div>
                        <span class="col-count">12+</span>
                    </div>
                    <div class="col-body">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="order-card card-delivered" style="--col-accent:#22c55e;">
                                <div class="card-top">
                                    <a href="#" class="card-order-id">#ORD-{{ 2050 + $i }}</a>
                                    <span class="delivered-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        Complete
                                    </span>
                                </div>
                                <p class="card-name">Emily White {{ $i }}</p>
                                <p class="card-meta">Delivered today, 10:45 AM</p>
                            </div>
                        @endfor
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection