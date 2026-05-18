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
           SHOP LAYOUT
        ════════════════════════════════════════════════ */
        .shop-page {
            max-width: 1600px;
            margin: 0 auto;
            min-height: 80vh;
            border-left: 1px solid var(--bg-4);
            border-right: 1px solid var(--bg-4);
        }

        .shop-layout {
            display: flex;
            align-items: flex-start;
        }

        /* Filter Sidebar */
        .shop-sidebar {
            width: 280px;
            min-width: 280px;
            background: var(--bg-2);
            border-right: 1px solid var(--bg-4);
            padding: 1.5rem;
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
        }

        .filter-group {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--bg-4);
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-family: var(--font-display);
            font-size: 0.85rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--off-white);
            margin-bottom: 1rem;
            font-weight: 600;
            user-select: none;
        }

        .filter-header .chevron {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-right: 1.5px solid var(--dim);
            border-bottom: 1.5px solid var(--dim);
            transform: rotate(45deg);
            transition: transform 0.2s ease;
        }

        .filter-header.collapsed .chevron {
            transform: rotate(-45deg);
        }

        .filter-content {
            transition: max-height 0.25s ease-out, opacity 0.25s ease-out;
            overflow: hidden;
            max-height: 500px;
            opacity: 1;
        }

        .filter-header.collapsed + .filter-content {
            max-height: 0;
            opacity: 0;
            pointer-events: none;
        }

        /* Double Range Slider Styles */
        .range-slider-container {
            position: relative;
            width: 100%;
            height: 5px;
            background: var(--bg-4);
            border-radius: 3px;
            margin: 1.2rem 0 1.2rem 0;
        }

        .range-slider-track {
            position: absolute;
            height: 100%;
            background: var(--gold);
            border-radius: 3px;
            left: 0%;
            right: 0%;
        }

        .range-slider-container input[type="range"] {
            position: absolute;
            width: 100%;
            height: 0;
            background: none;
            pointer-events: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            outline: none;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            padding: 0;
        }

        /* Webkit Thumb */
        .range-slider-container input[type="range"]::-webkit-slider-thumb {
            height: 14px;
            width: 14px;
            border-radius: 50%;
            background: var(--gold);
            border: 2px solid var(--bg-2);
            cursor: pointer;
            pointer-events: auto;
            -webkit-appearance: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.5);
            transition: transform 0.1s;
        }

        .range-slider-container input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        /* Firefox Thumb */
        .range-slider-container input[type="range"]::-moz-range-thumb {
            height: 14px;
            width: 14px;
            border-radius: 50%;
            background: var(--gold);
            border: 2px solid var(--bg-2);
            cursor: pointer;
            pointer-events: auto;
            -moz-appearance: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.5);
            transition: transform 0.1s;
        }

        .range-slider-container input[type="range"]::-moz-range-thumb:hover {
            transform: scale(1.2);
        }

        .price-inputs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .price-inputs input {
            width: 100%;
            background: var(--bg-3);
            border: 1px solid var(--bg-4);
            color: var(--off-white);
            padding: 0.5rem;
            font-family: var(--font-body);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .price-inputs input:focus {
            border-color: var(--gold);
        }

        .price-inputs span {
            color: var(--dim);
        }

        .color-swatches {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .color-swatch-wrapper {
            padding: 2px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: border-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .color-swatch-wrapper.selected {
            border-color: var(--gold);
        }

        .color-swatch {
            width: 24px;
            height: 24px;
            display: block;
            border: 1px solid var(--bg-4);
        }

        .btn-filter, .btn-reset {
            width: 100%;
            padding: 0.75rem;
            font-family: var(--font-display);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            transition: background 0.2s, color 0.2s;
        }

        .btn-filter {
            background: var(--gold);
            color: var(--bg);
            margin-bottom: 0.5rem;
        }

        .btn-filter:hover {
            background: var(--white);
        }

        .btn-reset {
            background: transparent;
            color: var(--dim);
            border: 1px solid var(--bg-4);
        }

        .btn-reset:hover {
            color: var(--off-white);
            background: var(--bg-3);
        }

        .filter-list {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dim);
            font-size: 0.85rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .filter-label:hover {
            color: var(--off-white);
        }

        .filter-label input[type="checkbox"] {
            accent-color: var(--gold);
            cursor: pointer;
            width: 16px;
            height: 16px;
        }

        /* ════════════════════════════════════════════════
           MAIN PRODUCT AREA
        ════════════════════════════════════════════════ */
        .products-main {
            background: var(--bg);
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        /* Top toolbar */
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
            font-size: var(--fs-p-badge);
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

        .p-side-btn.active {
            color: var(--gold);
        }

        .p-side-btn.active svg {
            fill: var(--gold);
            stroke: var(--gold);
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
            font-size: var(--fs-p-cat);
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--dim);
            margin-bottom: 0.22rem;
        }

        .p-name {
            font-family: var(--font-display);
            font-size: var(--fs-p-name);
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
            font-size: var(--fs-p-price);
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
        .custom-pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border-top: 1px solid var(--bg-4);
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .page-item {
            margin: 0;
        }

        .page-link, .page-item span.page-link {
            width: 40px;
            height: 40px;
            background: none;
            border: 1px solid var(--bg-4);
            color: var(--dim);
            cursor: pointer;
            font-family: var(--font-display);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
        }

        .page-item:not(:first-child) .page-link, .page-item:not(:first-child) span.page-link {
            border-left: none;
        }

        .page-link:hover, .page-item span.page-link:hover {
            background: var(--bg-3);
            color: var(--silver);
            text-decoration: none;
        }

        .page-item.active .page-link, .page-item.active span.page-link {
            background: var(--gold);
            color: var(--bg);
            border-color: var(--gold);
        }

        .page-item.disabled .page-link, .page-item.disabled span.page-link {
            color: var(--bg-4);
            cursor: not-allowed;
            background: none;
        }

        /* ════════════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════════════ */
        @media (max-width: 1100px) {
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

            .prod-grid.list .p-img {
                width: 130px;
                min-width: 130px;
                height: 180px;
            }
        }
    </style>

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
                        'image_url' => \Illuminate\Support\Facades\Blade::render("@placeholder(100)"),
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
        <div class="shop-layout">
            
            {{-- Filter Sidebar --}}
            <aside class="shop-sidebar">
                <form id="filterForm" onsubmit="event.preventDefault(); applyFilters();">
                    <div class="filter-group">
                        <h4 class="filter-header collapsed" onclick="toggleCollapse(this)">
                            <span>Price Range</span>
                            <span class="chevron"></span>
                        </h4>
                        <div class="filter-content">
                            <div class="range-slider-container">
                                <div class="range-slider-track"></div>
                                <input type="range" id="min_price_range" min="0" max="{{ ceil($maxPrice) }}" value="{{ request('min_price', 0) }}" step="1" oninput="updatePriceSlider()" onchange="applyFilters()">
                                <input type="range" id="max_price_range" min="0" max="{{ ceil($maxPrice) }}" value="{{ request('max_price', ceil($maxPrice)) }}" step="1" oninput="updatePriceSlider()" onchange="applyFilters()">
                            </div>
                            <div class="price-inputs">
                                <input type="number" id="min_price" name="min_price" value="{{ request('min_price', 0) }}" placeholder="Min" min="0" max="{{ ceil($maxPrice) }}" oninput="updatePriceInputs()" onchange="applyFilters()">
                                <span>-</span>
                                <input type="number" id="max_price" name="max_price" value="{{ request('max_price', ceil($maxPrice)) }}" placeholder="Max" min="0" max="{{ ceil($maxPrice) }}" oninput="updatePriceInputs()" onchange="applyFilters()">
                            </div>
                        </div>
                    </div>

                    @if(isset($availableCategories) && $availableCategories->count() > 0 && !isset($currentCategory))
                    <div class="filter-group">
                        <h4 class="filter-header collapsed" onclick="toggleCollapse(this)">
                            <span>Categories</span>
                            <span class="chevron"></span>
                        </h4>
                        <div class="filter-content">
                            <div class="filter-list">
                                @php $selectedCats = request('filter_category_id') ? (is_array(request('filter_category_id')) ? request('filter_category_id') : explode(',', request('filter_category_id'))) : []; @endphp
                                @foreach($availableCategories as $cat)
                                    <label class="filter-label">
                                        <input type="checkbox" name="filter_category_id[]" value="{{ $cat->id }}" onchange="handleCategoryChange(this)" {{ in_array($cat->id, $selectedCats) ? 'checked' : '' }}>
                                        <span>{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($availableBrands) && $availableBrands->count() > 0)
                    <div class="filter-group">
                        <h4 class="filter-header collapsed" onclick="toggleCollapse(this)">
                            <span>Brands</span>
                            <span class="chevron"></span>
                        </h4>
                        <div class="filter-content">
                            <div class="filter-list">
                                @php $selectedBrands = request('brand_id') ? (is_array(request('brand_id')) ? request('brand_id') : explode(',', request('brand_id'))) : []; @endphp
                                @foreach($availableBrands as $brand)
                                    <label class="filter-label">
                                        <input type="checkbox" name="brand_id[]" value="{{ $brand->id }}" onchange="applyFilters()" {{ in_array($brand->id, $selectedBrands) ? 'checked' : '' }}>
                                        <span>{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($availableAttributes) && $availableAttributes->count() > 0)
                        @foreach($availableAttributes as $attribute)
                            <div class="filter-group">
                                <h4 class="filter-header collapsed" onclick="toggleCollapse(this)">
                                    <span>{{ $attribute->name }}</span>
                                    <span class="chevron"></span>
                                </h4>
                                <div class="filter-content">
                                    @if($attribute->slug === 'color')
                                        <div class="color-swatches">
                                            @php $selectedAttrValues = request('attributes.'.$attribute->slug) ? (is_array(request('attributes.'.$attribute->slug)) ? request('attributes.'.$attribute->slug) : explode(',', request('attributes.'.$attribute->slug))) : []; @endphp
                                            @foreach($attribute->values as $val)
                                                <div class="color-swatch-wrapper {{ in_array($val->value, $selectedAttrValues) ? 'selected' : '' }}" 
                                                     data-val="{{ $val->value }}"
                                                     data-slug="{{ $attribute->slug }}"
                                                     onclick="toggleAttr(this)"
                                                     title="{{ $val->value }}">
                                                    <span class="color-swatch" style="background-color: {{ $val->value }}"></span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="attributes[{{ $attribute->slug }}]" class="attr-hidden-input" data-slug="{{ $attribute->slug }}" value="{{ request('attributes.'.$attribute->slug) ? (is_array(request('attributes.'.$attribute->slug)) ? implode(',', request('attributes.'.$attribute->slug)) : request('attributes.'.$attribute->slug)) : '' }}">
                                    @else
                                        <div class="filter-list">
                                            @php $selectedAttrValues = request('attributes.'.$attribute->slug) ? (is_array(request('attributes.'.$attribute->slug)) ? request('attributes.'.$attribute->slug) : explode(',', request('attributes.'.$attribute->slug))) : []; @endphp
                                            @foreach($attribute->values as $val)
                                                <label class="filter-label">
                                                    <input type="checkbox" name="attributes[{{ $attribute->slug }}][]" value="{{ $val->value }}" onchange="applyFilters()" {{ in_array($val->value, $selectedAttrValues) ? 'checked' : '' }}>
                                                    <span>{{ $val->value }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <button type="button" class="btn-filter" onclick="applyFilters()">Apply Filters</button>
                    <button type="button" class="btn-reset" onclick="resetFilters()">Reset</button>
                </form>
            </aside>

            <div class="products-main">

            {{-- Toolbar --}}
            <div class="products-toolbar">
                <div class="toolbar-left">
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
                
                {{-- Sorting dropdown with elegant page-reload --}}
                <select class="sort-dd" name="sort" aria-label="Sort products" onchange="const url = new URL(window.location.href); url.searchParams.set('sort', this.value); window.location.href = url.toString();">
                    <option value="">Sort By</option>
                    <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Name: A–Z</option>
                    <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Name: Z–A</option>
                    <option value="lh" {{ request('sort') == 'lh' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="hl" {{ request('sort') == 'hl' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>Newest First</option>
                    <option value="bs" {{ request('sort') == 'bs' ? 'selected' : '' }}>Best Sellers</option>
                    <option value="top" {{ request('sort') == 'top' ? 'selected' : '' }}>Top Rated</option>
                </select>
            </div>

            {{-- Product Grid --}}
            <div id="gridWrapper">
                @include('frontend.products.partials.grid')
            </div>
        </div>
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

        /* ── Swatch active toggle ─────────────────────── */
        function bindSwatchToggle() {
            document.querySelectorAll('.p-sw').forEach(sw => {
                sw.addEventListener('click', function () {
                    this.closest('.p-swatches')?.querySelectorAll('.p-sw').forEach(s => s.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }

        /* ── Price Range Slider Helpers ── */
        function updatePriceSlider() {
            const minRange = document.getElementById('min_price_range');
            const maxRange = document.getElementById('max_price_range');
            const minInput = document.getElementById('min_price');
            const maxInput = document.getElementById('max_price');
            const track = document.querySelector('.range-slider-track');
            if (!minRange || !maxRange || !minInput || !maxInput || !track) return;
            const maxVal = parseFloat(minRange.max);
            
            if (parseFloat(minRange.value) > parseFloat(maxRange.value)) {
                let temp = minRange.value;
                minRange.value = maxRange.value;
                maxRange.value = temp;
            }
            
            minInput.value = minRange.value;
            maxInput.value = maxRange.value;
            
            const leftPercent = (minRange.value / maxVal) * 100;
            const rightPercent = 100 - (maxRange.value / maxVal) * 100;
            track.style.left = leftPercent + '%';
            track.style.right = rightPercent + '%';
        }

        function updatePriceInputs() {
            const minRange = document.getElementById('min_price_range');
            const maxRange = document.getElementById('max_price_range');
            const minInput = document.getElementById('min_price');
            const maxInput = document.getElementById('max_price');
            const track = document.querySelector('.range-slider-track');
            if (!minRange || !maxRange || !minInput || !maxInput || !track) return;
            const maxVal = parseFloat(minRange.max);
            
            let minVal = parseFloat(minInput.value) || 0;
            let maxValInput = parseFloat(maxInput.value) || maxVal;
            
            if (minVal > maxValInput) {
                minVal = maxValInput;
                minInput.value = minVal;
            }
            if (maxValInput > maxVal) {
                maxValInput = maxVal;
                maxInput.value = maxVal;
            }
            
            minRange.value = minVal;
            maxRange.value = maxValInput;
            
            const leftPercent = (minVal / maxVal) * 100;
            const rightPercent = 100 - (maxValInput / maxVal) * 100;
            track.style.left = leftPercent + '%';
            track.style.right = rightPercent + '%';
        }

        document.addEventListener('DOMContentLoaded', () => {
            bindSwatchToggle();
            if (document.getElementById('min_price_range')) {
                updatePriceSlider();
            }
        });

        /* ── Stagger Animation helper ── */
        function initCardAnimations() {
            if (typeof gsap === 'undefined') return;
            const cards = document.querySelectorAll('.p-card');
            gsap.fromTo(cards, 
                { opacity: 0, y: 20 },
                { opacity: 1, y: 0, duration: 0.65, ease: 'power3.out', stagger: 0.07 }
            );
        }

        /* ── Collapsible Filters ── */
        function toggleCollapse(header) {
            header.classList.toggle('collapsed');
        }

        /* ── Reset other filters when category is loaded/selected ── */
        function handleCategoryChange(cb) {
            if (cb.checked) {
                // Clear other filters
                document.getElementById('min_price').value = '';
                document.getElementById('max_price').value = '';
                
                // Clear brands
                document.querySelectorAll('input[name="brand_id[]"]').forEach(el => el.checked = false);
                
                // Clear attributes checkboxes
                document.querySelectorAll('input[name^="attributes["]').forEach(el => el.checked = false);
                
                // Clear color swatches
                document.querySelectorAll('.color-swatch-wrapper').forEach(el => el.classList.remove('selected'));
                document.querySelectorAll('.attr-hidden-input').forEach(el => el.value = '');
            }
            applyFilters();
        }

        /* ── AJAX Filtering ──────────────────────────────── */
        function toggleAttr(el) {
            el.classList.toggle('selected');
            const slug = el.getAttribute('data-slug');
            
            // Find the hidden input for this slug
            const hiddenInput = document.querySelector(`.attr-hidden-input[data-slug="${slug}"]`);
            if (hiddenInput) {
                const selected = Array.from(el.parentElement.querySelectorAll('.color-swatch-wrapper.selected'))
                                      .map(e => e.getAttribute('data-val'));
                hiddenInput.value = selected.join(',');
            }
            applyFilters();
        }

        function resetFilters() {
            const minInput = document.getElementById('min_price');
            const maxInput = document.getElementById('max_price');
            const maxSlider = document.getElementById('max_price_range');
            
            if (minInput && maxInput && maxSlider) {
                minInput.value = 0;
                maxInput.value = maxSlider.max;
                updatePriceInputs();
            } else {
                if (minInput) minInput.value = '';
                if (maxInput) maxInput.value = '';
            }
            
            // Reset swatches
            document.querySelectorAll('.color-swatch-wrapper').forEach(el => el.classList.remove('selected'));
            document.querySelectorAll('.attr-hidden-input').forEach(el => el.value = '');
            
            // Reset checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(el => el.checked = false);
            
            applyFilters();
        }

        function applyFilters(url = null) {
            let fetchUrl = new URL(url || window.location.href);
            
            if (!url) {
                // If url is not provided (meaning it's a form change, not a pagination click), 
                // we build the search params from the form.
                const minPrice = document.getElementById('min_price')?.value;
                const maxPrice = document.getElementById('max_price')?.value;
                const sort = document.querySelector('.sort-dd')?.value;
                
                // Read base parameters to preserve them
                const category = fetchUrl.searchParams.get('category');
                const collection = fetchUrl.searchParams.get('collection');
                const search = fetchUrl.searchParams.get('search');
                
                // Clear existing params to rebuild
                fetchUrl.search = '';
                
                // Restore base parameters
                if (category) fetchUrl.searchParams.set('category', category);
                if (collection) fetchUrl.searchParams.set('collection', collection);
                if (search) fetchUrl.searchParams.set('search', search);
                
                const maxRange = document.getElementById('max_price_range');
                const maxVal = maxRange ? parseFloat(maxRange.max) : null;
                
                if (minPrice && parseFloat(minPrice) > 0) {
                    fetchUrl.searchParams.set('min_price', minPrice);
                }
                if (maxPrice && maxVal && parseFloat(maxPrice) < maxVal) {
                    fetchUrl.searchParams.set('max_price', maxPrice);
                } else if (maxPrice && !maxVal) {
                    fetchUrl.searchParams.set('max_price', maxPrice);
                }
                
                if (sort) fetchUrl.searchParams.set('sort', sort);
                
                // Categories
                const cats = Array.from(document.querySelectorAll('input[name="filter_category_id[]"]:checked')).map(cb => cb.value);
                if (cats.length) fetchUrl.searchParams.set('filter_category_id', cats.join(','));
                
                // Brands
                const brands = Array.from(document.querySelectorAll('input[name="brand_id[]"]:checked')).map(cb => cb.value);
                if (brands.length) fetchUrl.searchParams.set('brand_id', brands.join(','));
                
                // Attributes
                // Hidden inputs (e.g. colors)
                document.querySelectorAll('.attr-hidden-input').forEach(input => {
                    if (input.value) {
                        fetchUrl.searchParams.set(`attributes[${input.getAttribute('data-slug')}]`, input.value);
                    }
                });
                // Checkboxes
                const attrCheckboxes = document.querySelectorAll('input[name^="attributes["]:checked');
                const attrMap = {};
                attrCheckboxes.forEach(cb => {
                    const name = cb.getAttribute('name'); // e.g. attributes[size][]
                    const match = name.match(/attributes\[([^\]]+)\]/);
                    if (match) {
                        const slug = match[1];
                        if (!attrMap[slug]) attrMap[slug] = [];
                        attrMap[slug].push(cb.value);
                    }
                });
                for (const slug in attrMap) {
                    fetchUrl.searchParams.set(`attributes[${slug}]`, attrMap[slug].join(','));
                }
            }
            
            // Push state for history
            window.history.pushState({}, '', fetchUrl);
            
            // Fetch via AJAX
            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('gridWrapper').innerHTML = html;
                bindSwatchToggle();
                initCardAnimations();
                bindPaginationAjax();
            })
            .catch(err => console.error('Filter error:', err));
        }

        function updateProductCount() {
            // Usually we'd parse the count from the response, but for now we can let it be or update it if we send it back.
            // A simple way is to wrap the count in a span inside the partial, but it's okay for now.
        }

        function bindPaginationAjax() {
            document.querySelectorAll('#gridWrapper .pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    applyFilters(this.href);
                });
            });
        }

        // Sort DD override
        document.querySelector('.sort-dd').addEventListener('change', function() {
            applyFilters();
        });

        /* ── GSAP Animations ─────────────────────────────────────── */
        window.addEventListener('load', () => {
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            /* Stagger cards in */
            initCardAnimations();

            /* Toolbar entrance */
            gsap.from('.products-toolbar', {
                opacity: 0, y: -10, duration: 0.5, ease: 'power3.out', delay: 0.3
            });
            
            gsap.from('.shop-sidebar', {
                opacity: 0, x: -20, duration: 0.5, ease: 'power3.out', delay: 0.3
            });
            
            bindPaginationAjax();
        });
    </script>

@endsection
