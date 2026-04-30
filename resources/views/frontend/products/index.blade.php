@extends('frontend.layouts.app')@section('title', 'Shop All Products | ' . ($store_name ?? 'Karbnzol'))@section('content')

    <style>
        :root {
            --bg: #1a1a1a;
            --bg-2: #222222;
            --bg-3: #2a2a2a;
            --bg-4: #333333;
            --white: #ffffff;
            --off-white: #f0f0f0;
            --silver: #d1d5db;
            --dim: #a1a1aa;
            --gold: #c8a96e;
            --gold-bg: rgba(200, 169, 110, 0.1);
            --red: #cc3333;
            --font-display: 'Oswald', 'Arial Narrow', sans-serif;
            --font-body: 'Barlow', sans-serif;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --sidebar-w: 230px;
        }

        /* ════════════════════════════════════════════════
           PAGE BREADCRUMB
        ════════════════════════════════════════════════ */
        .breadcrumb-bar {
            background: var(--bg-2);
            border-bottom: 1px solid var(--bg-4);
            padding: 0.75rem 2rem;
        }

        .breadcrumb-inner {
            max-width: 1600px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: var(--font-display);
            font-size: 0.6rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--dim);
        }

        .breadcrumb-inner a {
            color: var(--dim);
            transition: color 0.2s;
        }

        .breadcrumb-inner a:hover {
            color: var(--gold);
        }

        .breadcrumb-inner .sep {
            color: var(--bg-4);
        }

        .breadcrumb-inner .current {
            color: var(--silver);
        }

        /* ════════════════════════════════════════════════
           SHOP LAYOUT: SIDEBAR + MAIN
        ════════════════════════════════════════════════ */
        .shop-page {
            max-width: 1600px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: var(--sidebar-w) 1fr;
            min-height: 80vh;
            border-left: 1px solid var(--bg-4);
            border-right: 1px solid var(--bg-4);
        }

        /* ════════════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════════════ */
        .sidebar {
            background: var(--bg-2);
            border-right: 1px solid var(--bg-4);
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--bg-4) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--bg-4);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--silver);
        }

        /* Filter group */
        .filter-grp {
            border-bottom: 1px solid var(--bg-4);
        }

        .filter-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.95rem 1.25rem;
            background: none;
            border: none;
            cursor: pointer;
            font-family: var(--font-display);
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--off-white);
            transition: background 0.2s, color 0.2s;
        }

        .filter-btn:hover {
            background: var(--bg-3);
        }

        .filter-btn svg {
            flex-shrink: 0;
            color: var(--dim);
            transition: transform 0.25s;
        }

        .filter-btn.open svg {
            transform: rotate(180deg);
        }

        .filter-body {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.35s var(--ease-out), padding 0.3s;
            padding: 0 1.25rem;
        }

        .filter-body.open {
            max-height: 400px;
            padding: 0 1.25rem 1rem;
        }

        /* Checkbox option */
        .f-opt {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.35rem 0;
            cursor: pointer;
            user-select: none;
        }

        .f-opt:hover .f-label {
            color: var(--off-white);
        }

        .f-cb {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            border: 1px solid var(--bg-4);
            background: var(--bg-3);
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            position: relative;
            transition: background 0.15s, border-color 0.15s;
        }

        .f-cb:checked {
            background: var(--gold);
            border-color: var(--gold);
        }

        .f-cb:checked::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='10' height='8' viewBox='0 0 10 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 4L3.5 6.5L9 1' stroke='%231a1a1a' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 10px;
        }

        .f-label {
            font-size: 0.78rem;
            color: var(--silver);
            flex: 1;
        }

        .f-ct {
            font-family: var(--font-display);
            font-size: 0.52rem;
            letter-spacing: 0.08em;
            color: var(--dim);
        }

        /* Color swatches */
        .color-swatches {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding: 0.25rem 0 0.5rem;
        }

        .cf-sw {
            width: 22px;
            height: 22px;
            cursor: pointer;
            border: 2px solid var(--bg-4);
            transition: border-color 0.2s, transform 0.2s;
            position: relative;
        }

        .cf-sw:hover {
            border-color: var(--silver);
            transform: scale(1.15);
        }

        .cf-sw.on {
            border-color: var(--gold);
        }

        .cf-sw.on::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='10' viewBox='0 0 12 10' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 5L4.5 8.5L11 1' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 10px;
            filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.6));
        }

        /* Size buttons */
        .size-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            padding: 0.25rem 0 0.5rem;
        }

        .sz-btn {
            min-width: 36px;
            padding: 0.28rem 0.5rem;
            background: var(--bg-3);
            border: 1px solid var(--bg-4);
            font-family: var(--font-display);
            font-size: 0.58rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            color: var(--silver);
            cursor: pointer;
            transition: all 0.15s;
            text-align: center;
        }

        .sz-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .sz-btn.on {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--bg);
        }

        /* Price range */
        .price-wrap {
            padding: 0.25rem 0 0.75rem;
        }

        .price-vals {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-family: var(--font-display);
            font-size: 0.6rem;
            letter-spacing: 0.1em;
            color: var(--silver);
        }

        input.price-range {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            height: 3px;
            background: var(--bg-4);
            outline: none;
            cursor: pointer;
        }

        input.price-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            background: var(--gold);
            border: 2px solid var(--bg-2);
            cursor: pointer;
            border-radius: 0;
        }

        input.price-range::-moz-range-thumb {
            width: 14px;
            height: 14px;
            background: var(--gold);
            border: 2px solid var(--bg-2);
            cursor: pointer;
            border-radius: 0;
            border: none;
        }

        /* Clear all */
        .sidebar-clear-all {
            width: 100%;
            padding: 0.85rem 1.25rem;
            background: none;
            border: none;
            border-top: 1px solid var(--bg-4);
            font-family: var(--font-display);
            font-size: 0.58rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--dim);
            cursor: pointer;
            text-align: left;
            transition: color 0.2s, background 0.2s;
        }

        .sidebar-clear-all:hover {
            color: var(--gold);
            background: var(--gold-bg);
        }

        /* Active filter count badge */
        .sidebar-head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--bg-4);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-head-title {
            font-family: var(--font-display);
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--off-white);
        }

        .filter-active-count {
            background: var(--gold);
            color: var(--bg);
            font-family: var(--font-display);
            font-size: 0.5rem;
            font-weight: 700;
            padding: 0.12rem 0.4rem;
            border-radius: 2px;
            display: none;
        }

        .filter-active-count.visible {
            display: inline-block;
        }

        /* ════════════════════════════════════════════════
           MAIN PRODUCT AREA
        ════════════════════════════════════════════════ */
        .products-main {
            background: var(--bg);
            display: flex;
            flex-direction: column;
        }

        /* Top toolbar — exactly like reference */
        .products-toolbar {
            background: var(--bg-2);
            border-bottom: 1px solid var(--bg-4);
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            position: sticky;
            top: 64px;
            z-index: 20;
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Mobile filter toggle */
        .filter-mobile-btn {
            display: none;
            background: var(--bg-3);
            border: 1px solid var(--bg-4);
            color: var(--silver);
            padding: 0.45rem 0.875rem;
            font-family: var(--font-display);
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-mobile-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .product-total {
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--silver);
        }

        .product-total strong {
            color: var(--off-white);
        }

        /* View toggles */
        .view-switcher {
            display: flex;
        }

        .v-btn {
            width: 34px;
            height: 34px;
            background: none;
            border: 1px solid var(--bg-4);
            color: var(--dim);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .v-btn+.v-btn {
            border-left: none;
        }

        .v-btn:hover {
            color: var(--silver);
            background: var(--bg-3);
        }

        .v-btn.active {
            background: var(--gold);
            color: var(--bg);
            border-color: var(--gold);
        }

        /* Sort dropdown */
        .sort-dd {
            background: var(--bg-3);
            border: 1px solid var(--bg-4);
            color: var(--silver);
            padding: 0.5rem 2.25rem 0.5rem 0.75rem;
            font-family: var(--font-display);
            font-size: 0.63rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            outline: none;
            background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%23666' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
            transition: border-color 0.2s;
        }

        .sort-dd:focus {
            border-color: var(--gold);
            color: var(--off-white);
        }

        /* Active filters chips row */
        .active-filters {
            background: var(--bg);
            border-bottom: 1px solid var(--bg-4);
            padding: 0.6rem 1.5rem;
            display: none;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .active-filters.show {
            display: flex;
        }

        .af-label {
            font-family: var(--font-display);
            font-size: 0.55rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--dim);
        }

        .af-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: var(--bg-3);
            border: 1px solid var(--bg-4);
            padding: 0.2rem 0.6rem;
            font-family: var(--font-display);
            font-size: 0.56rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--silver);
        }

        .af-chip button {
            background: none;
            border: none;
            color: var(--dim);
            cursor: pointer;
            font-size: 0.7rem;
            line-height: 1;
            transition: color 0.2s;
        }

        .af-chip button:hover {
            color: var(--gold);
        }

        .af-clear-all {
            margin-left: auto;
            background: none;
            border: none;
            font-family: var(--font-display);
            font-size: 0.55rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--dim);
            cursor: pointer;
            transition: color 0.2s;
        }

        .af-clear-all:hover {
            color: var(--gold);
        }

        /* ════════════════════════════════════════════════
           PRODUCT GRID
        ════════════════════════════════════════════════ */
        .prod-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            flex: 1;
        }

        .prod-grid.g3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .prod-grid.g2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .prod-grid.list {
            grid-template-columns: 1fr;
        }

        /* Card */
        .p-card {
            border-right: 1px solid var(--bg-4);
            border-bottom: 1px solid var(--bg-4);
            background: var(--bg-2);
            position: relative;
            transition: background 0.25s;
            overflow: hidden;
        }

        .prod-grid .p-card:nth-child(4n) {
            border-right: none;
        }

        .prod-grid.g3 .p-card:nth-child(4n) {
            border-right: 1px solid var(--bg-4);
        }

        .prod-grid.g3 .p-card:nth-child(3n) {
            border-right: none;
        }

        .prod-grid.g2 .p-card:nth-child(3n),
        .prod-grid.g3 .p-card:nth-child(4n) {
            border-right: 1px solid var(--bg-4);
        }

        .prod-grid.g2 .p-card:nth-child(2n) {
            border-right: none;
        }

        .prod-grid.list .p-card {
            border-right: none;
            display: flex;
        }

        .p-card:hover {
            background: var(--bg-3);
        }

        /* Image */
        .p-img {
            position: relative;
            aspect-ratio: 3/4;
            overflow: hidden;
            background: #1c1c1c;
        }

        .prod-grid.list .p-img {
            width: 180px;
            min-width: 180px;
            aspect-ratio: auto;
            height: 240px;
        }

        .p-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            transition: transform 0.55s var(--ease-out), filter 0.4s;
            filter: brightness(0.9);
        }

        .p-card:hover .p-img img {
            transform: scale(1.05);
            filter: brightness(1);
        }

        /* Ribbon badge */
        .p-badge {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            background: rgba(26, 26, 26, 0.85);
            backdrop-filter: blur(4px);
            font-family: var(--font-display);
            font-size: 0.52rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--off-white);
            padding: 0.35rem 0.75rem;
        }

        .p-badge.sale {
            background: var(--red);
        }

        .p-badge.top {
            background: rgba(200, 169, 110, 0.9);
            color: var(--bg);
        }

        /* Side actions */
        .p-side {
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            flex-direction: column;
            transform: translateX(110%);
            transition: transform 0.3s var(--ease-out);
            z-index: 3;
        }

        .p-card:hover .p-side {
            transform: translateX(0);
        }

        .p-side-btn {
            width: 38px;
            height: 38px;
            background: rgba(26, 26, 26, 0.88);
            border: none;
            border-bottom: 1px solid var(--bg-4);
            color: var(--silver);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .p-side-btn:hover {
            background: var(--gold);
            color: var(--bg);
        }

        /* Quick add */
        .p-add {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--gold);
            color: var(--bg);
            border: none;
            cursor: pointer;
            font-family: var(--font-display);
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 0.7rem;
            text-align: center;
            transform: translateY(100%);
            transition: transform 0.3s var(--ease-out);
        }

        .p-add:hover {
            background: var(--white);
        }

        .p-card:hover .p-add {
            transform: translateY(0);
        }

        /* Info */
        .p-info {
            padding: 0.875rem 1rem 1rem;
            border-top: 1px solid var(--bg-4);
            flex: 1;
        }

        .prod-grid.list .p-info {
            border-top: none;
            border-left: 1px solid var(--bg-4);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .p-brand {
            font-family: var(--font-display);
            font-size: 0.52rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--dim);
            margin-bottom: 0.22rem;
        }

        .p-name {
            font-family: var(--font-display);
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--off-white);
            line-height: 1.35;
            margin-bottom: 0.45rem;
        }

        .p-price-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.35rem;
        }

        .p-price {
            font-family: var(--font-display);
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            color: var(--off-white);
        }

        .p-was {
            font-family: var(--font-display);
            font-size: 0.72rem;
            color: var(--dim);
            text-decoration: line-through;
        }

        .p-install {
            font-size: 0.68rem;
            color: var(--dim);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .p-install strong {
            color: var(--silver);
        }

        .p-install .cb {
            color: var(--gold);
            font-weight: 600;
        }

        .mintpay {
            display: inline-flex;
            align-items: center;
            background: #1a2e45;
            color: #5aadff;
            font-size: 0.46rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.1rem 0.3rem;
            border-radius: 2px;
            vertical-align: middle;
            margin-left: 0.2rem;
        }

        .p-swatches {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .p-sw {
            width: 16px;
            height: 16px;
            border: 1.5px solid var(--bg-4);
            cursor: pointer;
            transition: border-color 0.2s, transform 0.2s;
        }

        .p-sw:hover {
            border-color: var(--silver);
            transform: scale(1.15);
        }

        .p-sw.active {
            border-color: var(--gold);
        }

        /* List view specifics */
        .prod-grid.list .p-add,
        .prod-grid.list .p-side {
            display: none;
        }

        .prod-grid.list .p-info .list-cta {
            margin-top: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gold);
            color: var(--bg);
            padding: 0.6rem 1.25rem;
            font-family: var(--font-display);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            transition: background 0.2s;
        }

        .prod-grid.list .p-info .list-cta:hover {
            background: var(--white);
        }

        .prod-grid:not(.list) .list-cta {
            display: none;
        }

        /* ════════════════════════════════════════════════
   SHOP BANNER SLIDER
════════════════════════════════════════════════ */
.shop-banner {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: var(--bg-2);
    border-bottom: 1px solid var(--bg-4);
}

.banner-track {
    display: flex;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.banner-slide {
    min-width: 100%;
    position: relative;
    height: clamp(300px, 32vw, 520px);
    overflow: hidden;
    display: flex;
    align-items: center;
}

.banner-content-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
    padding: 0 2rem;
    pointer-events: none;
}

.banner-slide .slide-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center center;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.banner-slide .slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(26,26,26,.88) 38%, rgba(26,26,26,.1));
}

.banner-slide .slide-content {
    position: relative;
    max-width: 600px;
    pointer-events: auto;
}

.slide-eyebrow {
    font-family: var(--font-display);
    font-size: 0.55rem;
    font-weight: 600;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 0.6rem;
}

.slide-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4.5vw, 3.2rem);
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--off-white);
    line-height: 1.05;
    margin-bottom: 0.75rem;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.slide-sub {
    font-family: var(--font-display);
    font-size: 0.68rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--silver);
    margin-bottom: 1.5rem;
}


.slide-badge {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    z-index: 3;
    background: rgba(26,26,26,.85);
    backdrop-filter: blur(4px);
    color: var(--off-white);
    font-family: var(--font-display);
    font-size: 0.48rem;
    font-weight: 700;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    padding: 0.35rem 0.8rem;
}

.slide-badge--red { background: var(--red); }

/* Prev / Next arrows */
.banner-prev, .banner-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(26,26,26,.65);
    border: 1px solid var(--bg-4);
    color: var(--silver);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
}
.banner-prev { left: 1.25rem; }
.banner-next { right: 1.25rem; }
.banner-prev:hover, .banner-next:hover {
    background: var(--gold);
    color: var(--bg);
    border-color: var(--gold);
}

/* Dot indicators */
.banner-dots {
    position: absolute;
    bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 5px;
        z-index: 10;
    }
    .b-dot {
        width: 20px;
        height: 3px;
        background: rgba(255,255,255,.25);
        cursor: pointer;
        transition: background 0.25s, width 0.25s;
    }
    .b-dot.active { background: var(--gold); width: 34px; }

    /* Auto-play progress bar */
    .slide-timer {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2px;
        background: var(--gold);
        width: 0;
        z-index: 10;
    }

    @media (max-width: 768px) {
        .banner-slide { height: 260px; }
        .slide-title { font-size: 1.6rem; }
        .banner-slide .slide-content { padding: 0 1.5rem; }
        .slide-overlay { background: linear-gradient(180deg, rgba(26,26,26,.5) 0%, rgba(26,26,26,.85) 100%); }
    }

    @media (max-width: 480px) {
        .banner-slide { height: 220px; }
        .slide-title { font-size: 1.3rem; }
        .banner-prev, .banner-next { width: 32px; height: 32px; }
    }

        /* ════════════════════════════════════════════════
           PAGINATION
        ════════════════════════════════════════════════ */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            padding: 2rem;
            border-top: 1px solid var(--bg-4);
        }

        .page-btn {
            width: 40px;
            height: 40px;
            background: none;
            border: 1px solid var(--bg-4);
            color: var(--dim);
            cursor: pointer;
            font-family: var(--font-display);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .page-btn+.page-btn {
            border-left: none;
        }

        .page-btn:hover {
            background: var(--bg-3);
            color: var(--silver);
        }

        .page-btn.active {
            background: var(--gold);
            color: var(--bg);
            border-color: var(--gold);
        }

        .page-btn.arrow {
            color: var(--silver);
        }

        .page-btn.arrow:hover {
            color: var(--gold);
            border-color: var(--gold);
        }

        /* ════════════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════════════ */
        @media (max-width: 1100px) {
            .shop-page {
                grid-template-columns: 200px 1fr;
            }

            .prod-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .prod-grid .p-card:nth-child(4n) {
                border-right: 1px solid var(--bg-4);
            }

            .prod-grid .p-card:nth-child(3n) {
                border-right: none;
            }
        }

        @media (max-width: 900px) {
            .shop-page {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                top: 64px;
                left: -100%;
                height: calc(100vh - 64px);
                width: 280px;
                z-index: 300;
                border-right: 1px solid var(--bg-4);
                transition: left 0.4s var(--ease-out);
                box-shadow: 4px 0 32px rgba(0, 0, 0, 0.4);
            }

            .sidebar.open {
                left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 299;
                display: none;
                backdrop-filter: blur(2px);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .filter-mobile-btn {
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .prod-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .prod-grid .p-card:nth-child(3n) {
                border-right: 1px solid var(--bg-4);
            }

            .prod-grid .p-card:nth-child(2n) {
                border-right: none;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-bar {
                padding: 0.75rem 1rem;
            }

            .products-toolbar {
                padding: 0.75rem 1rem;
            }

            .active-filters {
                padding: 0.5rem 1rem;
            }

            .prod-grid.list .p-img {
                width: 130px;
                min-width: 130px;
                height: 180px;
            }
        }
    </style>

    {{-- Sidebar overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- ── BREADCRUMB ──────────────────────────────────────── --}}
    <div class="breadcrumb-bar">
        <div class="breadcrumb-inner">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            @if(isset($currentCategory))
                <a href="{{ route('frontend.products.index') }}">All Products</a>
                <span class="sep">›</span>
                <span class="current">{{ $currentCategory->name }}</span>
            @elseif(isset($currentCollection))
                <a href="{{ route('frontend.products.index') }}">All Products</a>
                <span class="sep">›</span>
                <span class="current">{{ $currentCollection->name }}</span>
            @else
                <span class="current">All Products</span>
            @endif
        </div>
    </div>

    {{-- ── SHOP BANNER SLIDER ────────────────────────────── --}}
<div class="shop-banner" id="shopBanner">
    <div class="banner-track" id="bannerTrack">
        @php
            $displayBanners = !empty($banners) ? $banners : [
                [
                    'image_url' => asset('images/logo-main.jpg'),
                    'title' => "Luxury Craft.<br>Modern Detail.",
                    'description' => 'Elegance in every piece · Modern luxury',
                    'eyebrow' => 'Premium Collection',
                    'badge' => 'New'
                ]
            ];
        @endphp

        @foreach($displayBanners as $banner)
            <div class="banner-slide">
                <div class="slide-bg" style="background-image:url('{{ $banner['image_url'] }}');"></div>
                <div class="slide-overlay"></div>
                <div class="banner-content-container">
                    <div class="slide-content">
                        <p class="slide-eyebrow">{{ $banner['eyebrow'] ?? ($currentCategory->name ?? ($currentCollection->name ?? 'Karbnzol')) }}</p>
                        <h2 class="slide-title">{!! $banner['title'] ?? ($currentCategory->name ?? ($currentCollection->name ?? '')) !!}</h2>
                        <p class="slide-sub">{{ $banner['description'] ?? ($currentCollection->description ?? '') }}</p>
                    </div>
                </div>
                @if(!empty($banner['badge']))
                    <span class="slide-badge {{ $banner['badge_class'] ?? '' }}">{{ $banner['badge'] }}</span>
                @endif
            </div>
        @endforeach
    </div>

    @if(count($displayBanners) > 1)
        <button class="banner-prev" id="bPrev" aria-label="Previous slide">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="banner-next" id="bNext" aria-label="Next slide">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>

        <div class="banner-dots" id="bDots"></div>
        <div class="slide-timer" id="slideTimer"></div>
    @endif
</div>

    {{-- ── SHOP PAGE ───────────────────────────────────────── --}}
    <div class="shop-page">

        {{-- ════════ SIDEBAR ════════ --}}
        <aside class="sidebar" id="filterSidebar" aria-label="Product filters">
            <div class="sidebar-head">
                <span class="sidebar-head-title">Filters</span>
                <span class="filter-active-count" id="activeCount">0</span>
            </div>

            {{-- Availability --}}
            <div class="filter-grp">
                <button class="filter-btn open" onclick="toggleFilter(this)">
                    Availability
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="filter-body open">
                    <label class="f-opt"><input class="f-cb" type="checkbox" onchange="updateFilters()" checked><span
                            class="f-label">In Stock</span><span class="f-ct">(28)</span></label>
                    <label class="f-opt"><input class="f-cb" type="checkbox" onchange="updateFilters()"><span
                            class="f-label">Out of Stock</span><span class="f-ct">(6)</span></label>
                    <label class="f-opt"><input class="f-cb" type="checkbox" onchange="updateFilters()"><span
                            class="f-label">Pre-Order</span><span class="f-ct">(3)</span></label>
                </div>
            </div>

            {{-- Price --}}
            <div class="filter-grp">
                <button class="filter-btn open" onclick="toggleFilter(this)">
                    Price
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="filter-body open">
                    <div class="price-wrap">
                        <div class="price-vals">
                            <span>{{ $currency_symbol }} 500</span>
                            <span id="priceDisplay">{{ $currency_symbol }} 15,000</span>
                        </div>
                        <input class="price-range" type="range" min="500" max="15000" value="15000" step="100"
                            oninput="document.getElementById('priceDisplay').textContent='{{ $currency_symbol }} '+parseInt(this.value).toLocaleString()">
                    </div>
                </div>
            </div>

            {{-- Color --}}
            <div class="filter-grp">
                <button class="filter-btn open" onclick="toggleFilter(this)">
                    Color
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="filter-body open">
                    <div class="color-swatches">
                        @foreach($colors as $colorMap)
                            @php
                                $sc = $colorMap->value ?? '#000000';
                            @endphp
                            <div class="cf-sw" style="background:{{ $sc }};"
                                onclick="this.classList.toggle('on'); updateFilters();" title="{{ $colorMap->name }}"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Size --}}
            <div class="filter-grp">
                <button class="filter-btn open" onclick="toggleFilter(this)">
                    Size
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="filter-body open">
                    <div class="size-grid">
                        @foreach($sizes as $sz)
                            <button class="sz-btn" onclick="this.classList.toggle('on'); updateFilters();">{{ $sz->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Product Type --}}
            <div class="filter-grp">
                <button class="filter-btn" onclick="toggleFilter(this)">
                    Product Type
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="filter-body">
                    @foreach(['T-Shirts' => 18, 'Polo Shirts' => 8, 'Jeans' => 10, 'Chinos' => 7, 'Activewear' => 9, 'Shirts' => 6] as $type
                        => $cnt)
                        <label class="f-opt"><input class="f-cb" type="checkbox" onchange="updateFilters()"><span
                                class="f-label">{{ $type }}</span><span class="f-ct">({{ $cnt }})</span></label>
                    @endforeach
                </div>
            </div>

            {{-- Category --}}
            <div class="filter-grp">
                <button class="filter-btn" onclick="toggleFilter(this)">
                    Category
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="filter-body">
                    @foreach($categories as $catObj)
                        <label class="f-opt">
                            <input class="f-cb" type="checkbox" onchange="window.location.href='{{ route('frontend.products.index', ['category' => $catObj->slug]) }}'" {{ request('category') == $catObj->slug ? 'checked' : '' }}>
                            <span class="f-label">{{ $catObj->name }}</span>
                            <span class="f-ct">({{ $catObj->products()->count() }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Clothing Features --}}
            <div class="filter-grp">
                <button class="filter-btn" onclick="toggleFilter(this)">
                    Clothing Features
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="filter-body">
                    @foreach([
                            'Slim Fit',
                            'Regular Fit',
                            'Moisture Wicking',
                            'Anti-Odour',
                            'Stretch',
                            '100% Cotton', 'UV
                                        Protection'
                        ] as $feat)
                        <label class="f-opt"><input class="f-cb" type="checkbox" onchange="updateFilters()"><span
                                class="f-label">{{ $feat }}</span></label>
                    @endforeach
                </div>
            </div>

            <button class="sidebar-clear-all" onclick="clearAll()">✕ &nbsp;Clear All Filters</button>
        </aside>

        {{-- ════════ PRODUCTS MAIN ════════ --}}
        <div class="products-main">

            {{-- Toolbar --}}
            <div class="products-toolbar">
                <div class="toolbar-left">
                    <button class="filter-mobile-btn" id="filterOpenBtn" onclick="openSidebar()">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" y1="6" x2="20" y2="6" />
                            <line x1="8" y1="12" x2="16" y2="12" />
                            <line x1="10" y1="18" x2="14" y2="18" />
                        </svg>
                        Filters
                    </button>
                    <p class="product-total"><strong id="prodCount">{{ $products->total() }}</strong> &nbsp;Products</p>
                    <div class="view-switcher" role="group" aria-label="View layout">
                        <button class="v-btn active" id="v4" onclick="setGrid(4,this)" aria-label="4-column grid"
                            title="4 columns">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="0" y="0" width="6" height="6" />
                                <rect x="10" y="0" width="6" height="6" />
                                <rect x="0" y="10" width="6" height="6" />
                                <rect x="10" y="10" width="6" height="6" />
                            </svg>
                        </button>
                        <button class="v-btn" id="v3" onclick="setGrid(3,this)" aria-label="3-column grid"
                            title="3 columns">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="0" y="0" width="4" height="16" />
                                <rect x="6" y="0" width="4" height="16" />
                                <rect x="12" y="0" width="4" height="16" />
                            </svg>
                        </button>
                        <button class="v-btn" id="v2" onclick="setGrid(2,this)" aria-label="2-column grid"
                            title="2 columns">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="0" y="0" width="6" height="16" />
                                <rect x="10" y="0" width="6" height="16" />
                            </svg>
                        </button>
                        <button class="v-btn" id="vl" onclick="setGrid('list',this)" aria-label="List view" title="List">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="0" y="0" width="16" height="3" />
                                <rect x="0" y="6" width="16" height="3" />
                                <rect x="0" y="12" width="16" height="3" />
                            </svg>
                        </button>
                    </div>
                </div>
                <select class="sort-dd" aria-label="Sort products">
                    <option value="">Sort By</option>
                    <option value="az">Name: A–Z</option>
                    <option value="za">Name: Z–A</option>
                    <option value="lh">Price: Low to High</option>
                    <option value="hl">Price: High to Low</option>
                    <option value="new">Newest First</option>
                    <option value="bs">Best Sellers</option>
                    <option value="top">Top Rated</option>
                </select>
            </div>

            {{-- Active filter chips --}}
            <div class="active-filters" id="activeFilters">
                <span class="af-label">Active:</span>
                <span class="af-chip">In Stock <button onclick="this.parentElement.remove()">×</button></span>
                <button class="af-clear-all" onclick="clearAll()">Clear All</button>
            </div>

            {{-- Product Grid --}}
            <div class="prod-grid" id="prodGrid">

                @foreach($products as $i => $p)
                    <div class="p-card" style="opacity:0;transform:translateY(20px);" data-index="{{ $i }}">
                        <a href="{{ route('frontend.products.show', $p->slug) }}" style="text-decoration:none; color:inherit;">
                            <div class="p-img">
                                @if($p->is_featured)<span class="p-badge top">Top</span>@endif
                                <div class="p-side">
                                    <button class="p-side-btn" aria-label="Wishlist" onclick="event.preventDefault();">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                    </button>
                                </div>
                                <img src="{{ $p->primaryImage ? $p->primaryImage->url : asset('images/logo-main.jpg') }}" alt="{{ $p->name }}" loading="{{ $i < 4 ? 'eager' : 'lazy' }}">
                                <button class="p-add" onclick="event.preventDefault(); window.location='{{ route('frontend.products.show', $p->slug) }}'">+ Add to Bag</button>
                            </div>
                            <div class="p-info">
                                <p class="p-brand">{{ $p->brand->name ?? 'Karbnzol' }}</p>
                                <p class="p-name">{{ $p->name }}</p>
                                <div class="p-price-row">
                                    <span class="p-price">@price($p->sale_price ? $p->sale_price : $p->base_price)</span>
                                    @if($p->sale_price && $p->sale_price < $p->base_price)
                                        <span class="p-was">@price($p->base_price)</span>
                                    @endif
                                </div>
                                <div class="p-swatches">
                                    @php
                                        $varColors = $p->variants->flatMap(function($var) {
                                            return $var->attributeValues->filter(function($av) {
                                                return optional($av->attribute)->slug === 'color';
                                            });
                                        })->unique('id');
                                    @endphp
                                    @foreach($varColors as $c)
                                        <span class="p-sw" style="background:{{ $c->value ?? '#ccc' }};" title="{{ $c->name }}"></span>
                                    @endforeach
                                </div>
                                <span class="list-cta">
                                    View Product
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <nav class="pagination" aria-label="Page navigation" style="display:block;">
                {{ $products->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>

    <script>

        /* ── Banner Slider ────────────────────────────────── */
(function () {
    const track = document.getElementById('bannerTrack');
    if (!track) return;
    const slides = track.querySelectorAll('.banner-slide');
    const dotsEl = document.getElementById('bDots');
    const timerBar = document.getElementById('slideTimer');
    let cur = 0, total = slides.length, autoTimer = null;

    slides.forEach((_, i) => {
        if (!dotsEl) return;
        const d = document.createElement('div');
        d.className = 'b-dot' + (i === 0 ? ' active' : '');
        d.onclick = () => goTo(i);
        dotsEl.appendChild(d);
    });

    function goTo(n) {
        if (!track) return;
        cur = (n + total) % total;
        track.style.transform = `translateX(-${cur * 100}%)`;
        if (dotsEl) {
            dotsEl.querySelectorAll('.b-dot').forEach((d, i) => d.classList.toggle('active', i === cur));
        }
        resetTimer();
    }

    function resetTimer() {
        if (!timerBar) return;
        clearTimeout(autoTimer);
        timerBar.style.transition = 'none';
        timerBar.style.width = '0%';
        void timerBar.offsetWidth;
        timerBar.style.transition = 'width 5s linear';
        timerBar.style.width = '100%';
        autoTimer = setTimeout(() => goTo(cur + 1), 5000);
    }

    const btnPrev = document.getElementById('bPrev');
    const btnNext = document.getElementById('bNext');
    if (btnPrev) btnPrev.onclick = () => goTo(cur - 1);
    if (btnNext) btnNext.onclick = () => goTo(cur + 1);

    let touchStartX = 0;
    track.addEventListener('touchstart', e => touchStartX = e.touches[0].clientX, { passive: true });
    track.addEventListener('touchend', e => {
        const dx = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(dx) > 40) goTo(cur + (dx < 0 ? 1 : -1));
    }, { passive: true });

    resetTimer();
})();

        /* ── Filter accordion ─────────────────────────── */
        function toggleFilter(btn) {
            btn.classList.toggle('open');
            const body = btn.nextElementSibling;
            body.classList.toggle('open');
        }

        /* ── Grid view switcher ───────────────────────── */
        function setGrid(cols, btn) {
            const grid = document.getElementById('prodGrid');
            grid.classList.remove('g3', 'g2', 'list');
            if (cols === 3) grid.classList.add('g3');
            else if (cols === 2) grid.classList.add('g2');
            else if (cols === 'list') grid.classList.add('list');
            document.querySelectorAll('.v-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        /* ── Mobile sidebar ───────────────────────────── */
        function openSidebar() {
            document.getElementById('filterSidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('filterSidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
            document.body.style.overflow = '';
        }

        /* ── Update filter state ──────────────────────── */
        function updateFilters() {
            const checked = document.querySelectorAll('.f-cb:checked').length;
            const sizeOn = document.querySelectorAll('.sz-btn.on').length;
            const colorOn = document.querySelectorAll('.cf-sw.on').length;
            const total = checked + sizeOn + colorOn;
            const badge = document.getElementById('activeCount');
            if (badge) {
                badge.textContent = total;
                badge.classList.toggle('visible', total > 0);
            }
            const af = document.getElementById('activeFilters');
            if (af) af.classList.toggle('show', total > 0);
        }

        /* ── Clear all ────────────────────────────────── */
        function clearAll() {
            document.querySelectorAll('.f-cb').forEach(c => c.checked = false);
            document.querySelectorAll('.sz-btn.on').forEach(b => b.classList.remove('on'));
            document.querySelectorAll('.cf-sw.on').forEach(s => s.classList.remove('on'));
            updateFilters();
        }

        /* ── Swatch active toggle ─────────────────────── */
        document.querySelectorAll('.p-sw').forEach(sw => {
            sw.addEventListener('click', function () {
                this.closest('.p-swatches').querySelectorAll('.p-sw').forEach(s => s.classList.remove('active'));
                this.classList.add('active');
            });
        });

        /* ── Responsive: show filter btn on mobile ────── */
        function checkMobile() {
            const btn = document.getElementById('filterOpenBtn');
            if (btn) btn.style.display = window.innerWidth <= 900 ? 'flex' : 'none';
        }
        window.addEventListener('resize', checkMobile, { passive: true });
        checkMobile();

        /* ── Pagination ───────────────────────────────── */
        document.querySelectorAll('.page-btn:not(.arrow):not([style*="cursor:default"])').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        /* ── GSAP ─────────────────────────────────────── */
        window.addEventListener('load', () => {
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            /* Stagger cards in */
            const cards = document.querySelectorAll('.p-card');
            gsap.to(cards, {
                opacity: 1, y: 0,
                duration: 0.65, ease: 'power3.out', stagger: 0.07, delay: 0.4
            });

            /* Filter groups slide in from left */
            gsap.from('.filter-grp', {
                opacity: 0, x: -16,
                duration: 0.5, ease: 'power3.out', stagger: 0.06, delay: 0.5
            });

            /* Toolbar entrance */
            gsap.from('.products-toolbar', {
                opacity: 0, y: -10, duration: 0.5, ease: 'power3.out', delay: 0.3
            });
        });
    </script>

@endsection
