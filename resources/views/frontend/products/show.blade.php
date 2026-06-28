@extends('frontend.layouts.app')

@section('title', $product->name . ' | Karbnzol')
@section('body_class', 'light-page')

@section('content')

    <style>
        /* ══════════════════════════════════════════════════════════════
           KARBNZOL — PRODUCT DETAIL PAGE
           Aesthetic: Refined editorial luxury. Clean serif structure,
           rich sand-and-ink palette, deliberate whitespace.
           ══════════════════════════════════════════════════════════════ */

        /* Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap');

        :root {
            --ink: #1a1612;
            --rust: #c4602a;
            --sand: #ede9e1;
            --mink: #7a6f66;
            --sage: #7a9e7e;
            --white: var(--bg-creamy, #faf9f7);
            --pd-font-display: 'Cormorant Garamond', Georgia, serif;
            --pd-font-body: 'Jost', sans-serif;
            --nav-h: 80px;
            --ease: cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .pd-section {
            font-family: var(--pd-font-body);
            background: var(--white);
            color: var(--ink);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ── SECTION ──────────────────────────────────────────── */
        .pd-section {
            padding: 3rem 0 7rem;
        }

        .pd-container {
            max-width: 1340px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: start;
        }

        /* ── BREADCRUMB ───────────────────────────────────────── */
        .pd-breadcrumbs {
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--mink);
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pd-breadcrumbs a:hover {
            color: var(--rust);
        }

        /* ══════════════════════════════════════════════════════
           GALLERY
           ══════════════════════════════════════════════════════ */
        .pd-gallery {
            position: sticky;
            top: calc(var(--nav-h) + 2rem);
        }

        /* Zoom container */
        .pd-main-img-wrap {
            position: relative;
            background: var(--sand);
            aspect-ratio: 3/4;
            overflow: hidden;
            cursor: zoom-in;
        }

        .pd-main-img-wrap.zoomed {
            cursor: zoom-out;
        }

        .pd-main-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.35s var(--ease), transform 0.5s var(--ease);
            transform-origin: var(--zoom-x, 50%) var(--zoom-y, 50%);
        }

        .pd-main-img-wrap.zoomed .pd-main-img {
            transform: scale(2);
        }


        /* Wishlist heart */
        .pd-wishlist-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
            backdrop-filter: blur(4px);
            transition: background 0.2s, transform 0.2s;
        }

        .pd-wishlist-btn:hover {
            background: #fff;
            transform: scale(1.1);
        }

        .pd-wishlist-btn svg {
            transition: fill 0.25s;
        }

        .pd-wishlist-btn.active svg {
            fill: #c4602a;
            stroke: #c4602a;
        }

        /* Thumbnails */
        .pd-thumbs {
            display: flex;
            gap: 0.6rem;
            margin-top: 0.75rem;
            overflow-x: auto;
            padding-bottom: 0.25rem;
            scrollbar-width: none;
        }

        .pd-thumbs::-webkit-scrollbar {
            display: none;
        }

        .pd-thumb-btn {
            flex-shrink: 0;
            width: 70px;
            aspect-ratio: 3/4;
            border: 2px solid transparent;
            padding: 0;
            cursor: pointer;
            overflow: hidden;
            background: var(--sand);
            opacity: 0.6;
            transition: opacity 0.25s, border-color 0.25s;
        }

        .pd-thumb-btn.active,
        .pd-thumb-btn:hover {
            opacity: 1;
            border-color: var(--rust);
        }

        .pd-thumb-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }



        /* ══════════════════════════════════════════════════════
           INFO COLUMN
           ══════════════════════════════════════════════════════ */
        .pd-info {
            padding-top: 0.5rem;
        }

        .pd-title {
            font-family: var(--pd-font-display);
            font-size: var(--fs-detail-title);
            font-weight: 300;
            line-height: 1.08;
            letter-spacing: -0.01em;
            color: var(--ink);
            margin-bottom: 0.5rem;
        }

        /* Rating bar */
        .pd-rating-bar {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
            cursor: pointer;
        }

        .pd-stars {
            display: flex;
            gap: 2px;
        }

        .pd-stars svg {
            width: 14px;
            height: 14px;
        }

        .pd-rating-count {
            font-size: 0.78rem;
            color: var(--mink);
            border-bottom: 1px solid var(--mink);
        }

        .pd-rating-count:hover {
            color: var(--rust);
            border-color: var(--rust);
        }

        /* Price */
        .pd-price-wrap {
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .pd-price {
            font-size: var(--fs-detail-price);
            font-weight: 600;
            color: var(--ink);
        }

        .pd-price-orig {
            font-size: 1rem;
            color: var(--mink);
            text-decoration: line-through;
        }

        .pd-price-sale {
            font-size: var(--fs-detail-price);
            font-weight: 600;
            color: var(--rust);
        }

        .pd-price-badge {
            background: var(--rust);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            padding: 0.2rem 0.5rem;
            border-radius: 2px;
            text-transform: uppercase;
        }

        /* Stock */
        .pd-stock {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .pd-stock-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--sage);
        }

        .pd-stock-dot.low {
            background: #e8a030;
        }

        .pd-stock-dot.out {
            background: #c4402a;
        }

        .pd-stock.in-stock .pd-stock-label {
            color: var(--sage);
        }

        .pd-stock.low-stock .pd-stock-label {
            color: #e8a030;
        }

        .pd-stock.out-stock .pd-stock-label {
            color: #c4402a;
        }

        .pd-short-desc {
            font-size: var(--fs-detail-desc);
            color: var(--mink);
            line-height: 1.7;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--sand);
            padding-bottom: 2rem;
        }

        /* ── COLOR SWATCHES ───────────────────────────────────── */
        .pd-option-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--ink);
            margin-bottom: 0.6rem;
        }

        .pd-option-label span {
            font-weight: 300;
            font-style: italic;
            color: var(--mink);
            text-transform: none;
            letter-spacing: 0;
            font-size: 0.8rem;
        }

        .pd-swatches {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .pd-swatch {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid transparent;
            padding: 2px;
            cursor: pointer;
            background: transparent;
            transition: border-color 0.2s;
            position: relative;
        }

        .pd-swatch-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .pd-swatch.active,
        .pd-swatch:hover {
            border-color: var(--ink);
        }

        .pd-swatch[data-sold-out]::after {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 50%;
            background: linear-gradient(135deg, transparent 45%, rgba(255, 255, 255, 0.7) 46%, rgba(255, 255, 255, 0.7) 54%, transparent 55%);
            pointer-events: none;
        }

        /* ── SIZE SELECTOR ────────────────────────────────────── */
        .pd-sizes {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.75rem;
        }

        .pd-size-btn {
            min-width: 46px;
            height: 46px;
            padding: 0 0.75rem;
            border: 1px solid var(--sand);
            background: transparent;
            font-family: var(--pd-font-body);
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--ink);
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s, color 0.2s;
            letter-spacing: 0.05em;
        }

        .pd-size-btn:hover {
            border-color: var(--ink);
        }

        .pd-size-btn.active {
            background: var(--ink);
            color: var(--white);
            border-color: var(--ink);
        }

        .pd-size-btn.soldout {
            opacity: 0.35;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        /* Size chart link */
        .pd-size-chart-link {
            font-size: 0.75rem;
            color: var(--mink);
            border-bottom: 1px solid var(--mink);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-bottom: 1.75rem;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
            padding: 0;
            font-family: var(--pd-font-body);
        }

        .pd-size-chart-link:hover {
            color: var(--rust);
            border-bottom-color: var(--rust);
        }

        /* ── QTY + CTA ────────────────────────────────────────── */
        .pd-qty-add {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            height: 56px;
        }

        .pd-qty-selector {
            display: flex;
            align-items: center;
            border: 1.5px solid var(--sand);
            border-radius: 100px;
            width: 135px;
            flex-shrink: 0;
            background: var(--white);
            transition: border-color 0.3s var(--ease), box-shadow 0.3s var(--ease);
            overflow: hidden;
        }

        .pd-qty-selector:hover,
        .pd-qty-selector:focus-within {
            border-color: var(--ink);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .pd-qty-btn {
            background: transparent;
            border: none;
            width: 44px;
            height: 100%;
            font-size: 1.25rem;
            font-weight: 300;
            cursor: pointer;
            color: var(--ink);
            transition: background 0.25s, color 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pd-qty-btn:hover {
            background: var(--sand);
        }

        .pd-qty-btn:active {
            background: var(--ink);
            color: var(--white);
        }

        .pd-qty-input {
            flex: 1;
            width: 47px;
            text-align: center;
            border: none;
            background: transparent;
            font-family: var(--pd-font-body);
            font-size: 1rem;
            font-weight: 600;
            color: var(--ink);
            outline: none;
            padding: 0;
            -moz-appearance: textfield;
        }

        .pd-qty-input::-webkit-outer-spin-button,
        .pd-qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .pd-add-btn {
            flex: 1;
            background: var(--ink);
            color: var(--white);
            border: none;
            font-family: var(--pd-font-body);
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .pd-add-btn:hover:not(:disabled) {
            background: var(--mink);
            transform: translateY(-2px);
        }

        .pd-add-btn:disabled {
            background: var(--sand);
            color: var(--mink);
            cursor: not-allowed;
        }

        .pd-buy-btn {
            width: 100%;
            height: 52px;
            background: transparent;
            color: var(--ink);
            border: 1.5px solid var(--ink);
            font-family: var(--pd-font-body);
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
            margin-bottom: 1.5rem;
            transition: background 0.3s, color 0.3s, transform 0.2s;
        }

        .pd-buy-btn:hover:not(:disabled) {
            background: var(--ink);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Toast */
        .pd-toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--ink);
            color: var(--white);
            padding: 0.9rem 1.5rem;
            font-size: 0.8125rem;
            letter-spacing: 0.05em;
            z-index: 8000;
            transform: translateY(120%);
            transition: transform 0.4s var(--ease);
            pointer-events: none;
            max-width: 320px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pd-toast.show {
            transform: translateY(0);
        }

        .pd-toast-icon {
            color: var(--sage);
            font-size: 1.1rem;
        }

        /* ── ACCORDION (Description / Care / Delivery) ─────── */
        .pd-accordions {
            border-top: 1px solid var(--sand);
            margin-top: 2rem;
        }

        .pd-acc-item {
            border-bottom: 1px solid var(--sand);
        }

        .pd-acc-btn {
            width: 100%;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 0;
            font-family: var(--pd-font-body);
            font-size: var(--fs-section-title);
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--ink);
            cursor: pointer;
        }

        .pd-acc-icon {
            font-size: 1.2rem;
            line-height: 1;
            transition: transform 0.3s;
            color: var(--mink);
        }

        .pd-acc-item.open .pd-acc-icon {
            transform: rotate(45deg);
        }

        .pd-acc-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s var(--ease);
        }

        .pd-acc-body-inner {
            padding-bottom: 1.25rem;
            font-size: 0.9rem;
            color: var(--mink);
            line-height: 1.75;
        }

        .pd-acc-body-inner ul {
            padding-left: 1.25rem;
            margin-top: 0.5rem;
        }

        .pd-acc-body-inner li {
            margin-bottom: 0.3rem;
        }

        /* Measurements table */
        .pd-measure-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            margin-top: 0.75rem;
        }

        .pd-measure-table th {
            text-align: left;
            font-weight: 600;
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--sand);
            color: var(--ink);
        }

        .pd-measure-table td {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: var(--mink);
        }

        /* Delivery */
        .pd-delivery-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .pd-del-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .pd-del-icon {
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 0.15rem;
        }

        .pd-del-title {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: var(--ink);
            margin-bottom: 0.2rem;
        }

        .pd-del-sub {
            font-size: 0.78rem;
            color: var(--mink);
            line-height: 1.5;
        }

        /* ══════════════════════════════════════════════════════
           SIZE CHART MODAL
           ══════════════════════════════════════════════════════ */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(26, 22, 18, 0.55);
            z-index: 7000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: var(--white);
            max-width: 680px;
            width: 92vw;
            max-height: 85vh;
            overflow-y: auto;
            padding: 2.5rem;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            background: none;
            border: none;
            font-size: 1.75rem;
            line-height: 1;
            cursor: pointer;
            color: var(--mink);
        }

        .modal-close:hover {
            color: var(--ink);
        }

        .modal-title {
            font-family: var(--pd-font-display);
            font-size: 1.6rem;
            font-weight: 300;
            margin-bottom: 1.5rem;
            color: var(--ink);
        }

        .size-chart-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .size-chart-table th {
            background: var(--sand);
            padding: 0.6rem 0.8rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--ink);
        }

        .size-chart-table td {
            padding: 0.6rem 0.8rem;
            text-align: center;
            border-bottom: 1px solid var(--sand);
            color: var(--mink);
        }

        .size-chart-table tr:hover td {
            background: rgba(237, 233, 225, 0.4);
        }

        .size-chart-note {
            font-size: 0.8rem;
            color: var(--mink);
            margin-top: 1rem;
            font-style: italic;
        }

        .fit-guide {
            margin-top: 1.5rem;
            padding: 1rem;
            background: var(--sand);
            font-size: 0.85rem;
            color: var(--mink);
            line-height: 1.65;
        }

        .fit-guide strong {
            color: var(--ink);
        }

        /* ══════════════════════════════════════════════════════
           REVIEWS SECTION
           ══════════════════════════════════════════════════════ */
        .pd-reviews-section {
            max-width: 1340px;
            margin: 0 auto;
            padding: 0 2rem 5rem;
            border-top: 1px solid var(--sand);
            margin-top: 4rem;
            padding-top: 4rem;
        }

        .pd-reviews-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pd-reviews-title {
            font-family: var(--pd-font-display);
            font-size: clamp(1.6rem, 2.5vw, 2.2rem);
            font-weight: 300;
            color: var(--ink);
        }

        .pd-overall-rating {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pd-rating-big {
            font-family: var(--pd-font-display);
            font-size: 3.5rem;
            font-weight: 300;
            line-height: 1;
            color: var(--ink);
        }

        .pd-rating-info {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .pd-write-review-btn {
            background: none;
            border: 1.5px solid var(--ink);
            padding: 0.65rem 1.5rem;
            font-family: var(--pd-font-body);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            color: var(--ink);
            transition: background 0.3s, color 0.3s;
        }

        .pd-write-review-btn:hover {
            background: var(--ink);
            color: #fff;
        }

        /* Rating bars */
        .pd-rating-breakdown {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-bottom: 2.5rem;
            max-width: 340px;
        }

        .pd-rb-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .pd-rb-label {
            font-size: 0.78rem;
            color: var(--mink);
            width: 45px;
            flex-shrink: 0;
        }

        .pd-rb-bar {
            flex: 1;
            height: 4px;
            background: var(--sand);
            border-radius: 2px;
            overflow: hidden;
        }

        .pd-rb-fill {
            height: 100%;
            background: var(--rust);
            border-radius: 2px;
            transition: width 0.8s var(--ease);
        }

        .pd-rb-count {
            font-size: 0.78rem;
            color: var(--mink);
            width: 24px;
            text-align: right;
            flex-shrink: 0;
        }

        /* Review cards */
        .pd-reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .pd-review-card {
            border: 1px solid var(--sand);
            padding: 1.5rem;
            transition: box-shadow 0.3s;
        }

        .pd-review-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        }

        .pd-review-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .pd-reviewer-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--ink);
        }

        .pd-review-date {
            font-size: 0.75rem;
            color: var(--mink);
            margin-top: 0.15rem;
        }

        .pd-verified {
            font-size: 0.65rem;
            color: var(--sage);
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            background: rgba(122, 158, 126, 0.1);
            padding: 0.2rem 0.5rem;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .pd-review-stars {
            margin-bottom: 0.5rem;
        }

        .pd-review-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.4rem;
        }

        .pd-review-body {
            font-size: 0.875rem;
            color: var(--mink);
            line-height: 1.65;
            margin-bottom: 0.75rem;
        }

        .pd-review-photos {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pd-review-photo {
            width: 64px;
            height: 64px;
            object-fit: cover;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .pd-review-photo:hover {
            opacity: 0.8;
        }

        .pd-review-meta {
            margin-top: 0.75rem;
            font-size: 0.75rem;
            color: var(--mink);
            border-top: 1px solid var(--sand);
            padding-top: 0.6rem;
            display: flex;
            gap: 1rem;
        }

        .pd-review-helpful {
            cursor: pointer;
        }

        .pd-review-helpful:hover {
            color: var(--ink);
        }

        /* Load more */
        .pd-load-more {
            text-align: center;
            margin-top: 2.5rem;
        }

        .pd-load-more-btn {
            background: none;
            border: 1.5px solid var(--ink);
            padding: 0.7rem 2.5rem;
            font-family: var(--pd-font-body);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            color: var(--ink);
            transition: background 0.3s, color 0.3s;
        }

        .pd-load-more-btn:hover {
            background: var(--ink);
            color: #fff;
        }

        /* Write review modal */
        .review-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .review-form label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--ink);
            display: block;
            margin-bottom: 0.4rem;
        }

        .review-form input,
        .review-form textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--sand);
            background: transparent;
            font-family: var(--pd-font-body);
            font-size: 0.875rem;
            color: var(--ink);
            outline: none;
            transition: border-color 0.2s;
        }

        .review-form input:focus,
        .review-form textarea:focus {
            border-color: var(--ink);
        }

        .review-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .star-picker {
            display: flex;
            gap: 0.4rem;
        }

        .star-picker-star {
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--sand);
            transition: color 0.15s;
        }

        .star-picker-star.lit {
            color: var(--rust);
        }

        .review-submit-btn {
            background: var(--ink);
            color: var(--white);
            border: none;
            padding: 0.9rem;
            font-family: var(--pd-font-body);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s;
        }

        .review-submit-btn:hover {
            background: var(--rust);
        }

        /* ══════════════════════════════════════════════════════
           RELATED PRODUCTS
           ══════════════════════════════════════════════════════ */
        .pd-related-section {
            background: var(--sand);
            padding: 5rem 2rem;
        }

        .pd-related-inner {
            max-width: 1340px;
            margin: 0 auto;
        }

        .pd-related-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pd-related-title {
            font-family: var(--pd-font-display);
            font-size: clamp(1.6rem, 2.5vw, 2rem);
            font-weight: 300;
            font-style: italic;
            color: var(--ink);
        }

        .pd-related-link {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--mink);
            border-bottom: 1px solid var(--mink);
            transition: color 0.2s, border-color 0.2s;
        }

        .pd-related-link:hover {
            color: var(--rust);
            border-color: var(--rust);
        }

        .pd-related-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .pd-product-card {
            position: relative;
            cursor: pointer;
        }

        .pd-product-card-img {
            aspect-ratio: 3/4;
            background: var(--white);
            overflow: hidden;
            margin-bottom: 0.75rem;
        }

        .pd-product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s var(--ease);
        }

        .pd-product-card:hover .pd-product-card-img img {
            transform: scale(1.04);
        }

        .pd-card-wishlist {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: rgba(255, 255, 255, 0.85);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(4px);
            transition: background 0.2s, transform 0.2s;
            opacity: 0;
        }

        .pd-product-card:hover .pd-card-wishlist {
            opacity: 1;
        }

        .pd-card-wishlist:hover {
            background: #fff;
            transform: scale(1.1);
        }

        .pd-card-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: var(--rust);
            color: #fff;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.2rem 0.5rem;
        }

        .pd-card-name {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ink);
            margin-bottom: 0.25rem;
        }

        .pd-card-price {
            font-size: 0.82rem;
            color: var(--mink);
            display: flex;
            gap: 0.5rem;
            align-items: baseline;
        }

        .pd-card-price-sale {
            color: var(--rust);
        }

        .pd-card-price-orig {
            text-decoration: line-through;
            font-size: 0.75rem;
        }

        .pd-card-stars {
            display: flex;
            gap: 2px;
            margin-top: 0.25rem;
        }

        .pd-card-stars svg {
            width: 11px;
            height: 11px;
        }

        /* ── ANIMATIONS ───────────────────────────────────────── */
        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            animation: fadeUpAnim 0.8s var(--ease) forwards;
        }

        @keyframes fadeUpAnim {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── RESPONSIVE ───────────────────────────────────────── */
        @media (max-width: 1100px) {
            .pd-related-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {
            .pd-container {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .pd-gallery {
                position: static;
            }

            .pd-related-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pd-delivery-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 550px) {
            .pd-related-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .pd-reviews-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dynamic Selection Styling and Shake Animations */
        @keyframes pd-shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        
        .pd-attribute-group {
            transition: border-color 0.3s ease, background-color 0.3s ease, padding 0.3s ease;
            border: 1px solid transparent;
            border-radius: 6px;
            padding: 0.5rem;
            margin: -0.5rem;
        }
        
        .pd-attribute-group.pd-highlight-error {
            border-color: rgba(196, 96, 42, 0.4);
            background-color: rgba(196, 96, 42, 0.03);
            animation: pd-shake 0.4s ease-in-out;
        }

    </style>

    
    <div class="pd-toast" id="pdToast">
        <span class="pd-toast-icon">✓</span>
        <span id="pdToastMsg">{{ __('product.item_added_to_cart') }}</span>
    </div>

    
    <section class="pd-section">
        <div class="pd-container">

            
            <div class="pd-gallery fade-up">
                <div class="pd-main-img-wrap" id="mainImgWrap">
                    @php
                        $inWishlist = auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                    @endphp
                    
                    <button class="pd-wishlist-btn {{ $inWishlist ? 'active' : '' }}" id="wishlistBtnMain"
                        onclick="event.stopPropagation(); toggleWishlist(this, {{ $product->id }})" aria-label="Add to wishlist">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1a1612" stroke-width="1.5">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                    </button>


                    
                    @if($product->primaryImage)
                        <img id="mainProductImage" class="pd-main-img" src="{{ $product->primaryImage->url }}"
                            alt="{{ $product->name }}">
                    @elseif($product->images && $product->images->count() > 0)
                        <img id="mainProductImage" class="pd-main-img" src="{{ $product->images->first()->url }}"
                            alt="{{ $product->name }}">
                    @else
                        <img id="mainProductImage" class="pd-main-img" src="@placeholder($product->id)"
                            alt="{{ $product->name }}">
                    @endif
                </div>

                
                <div class="pd-thumbs" id="thumbsContainer">
                    @if($product->images && $product->images->count() > 0)
                        @foreach($product->images as $index => $image)
                            <button class="pd-thumb-btn {{ $index === 0 ? 'active' : '' }}"
                                onclick="switchImage('{{ $image->url }}', this)" aria-label="View image {{ $index + 1 }}">
                                <img src="{{ $image->url }}" alt="{{ $product->name }} {{ $index + 1 }}">
                            </button>
                        @endforeach
                    @else
                        <button class="pd-thumb-btn active">
                            <img src="@placeholder($product->id)" alt="Placeholder">
                        </button>
                    @endif
                </div>
            </div>

            
            <div class="pd-info fade-up" style="animation-delay:0.15s;">

                
                <div class="pd-breadcrumbs">
                    <a href="{{ route('home') }}">{{ __('product.home') }}</a>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                    <a href="{{ route('frontend.products.index') }}">{{ __('product.shop') }}</a>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                    <span>{{ $product->category->name ?? __('product.shop') }}</span>
                </div>

                <h1 class="pd-title">{{ $product->name }}</h1>

                
                <div class="pd-rating-bar"
                    onclick="document.getElementById('reviewsSection').scrollIntoView({behavior:'smooth'})">
                    <div class="pd-stars" id="headerStars">
                        @php $avgRating = $product->reviews->count() > 0 ? round($product->reviews->avg('rating'), 1) : 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <svg viewBox="0 0 24 24" fill="{{ $i <= round($avgRating) ? '#c4602a' : 'none' }}" stroke="#c4602a"
                                stroke-width="1.5">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                        @endfor
                    </div>
                    <span class="pd-rating-count">{{ $avgRating > 0 ? $avgRating : __('product.no_reviews') }} ({{ $product->reviews->count() }}
                        {{ __('product.reviews') }})</span>
                </div>

                
                <div class="pd-price-wrap" id="pdPriceWrap">
                    @php
                        $defaultVariant = $product->variants->where('is_default', true)->first() ?? $product->variants->first();
                        $displayPrice = $defaultVariant ? ($defaultVariant->sale_price ?? $defaultVariant->price) : $product->base_price;
                        $originalPrice = ($defaultVariant && $defaultVariant->sale_price) ? $defaultVariant->price : null;
                    @endphp
                    @if($originalPrice)
                        <span class="pd-price" id="pdBasePrice" style="color:var(--emerald);">@price($displayPrice)</span>
                        <span class="pd-price"
                            style="text-decoration:line-through; color:var(--dim); font-size:0.8em; margin-left:0.5rem;">@price($originalPrice)</span>
                    @else
                        <span class="pd-price" id="pdBasePrice">@price($displayPrice)</span>
                    @endif
                </div>

                
                @php
                    $totalStock = $product->variants->sum('stock_quantity') ?? 0;
                    $stockClass = $totalStock > 10 ? 'in-stock' : ($totalStock > 0 ? 'low-stock' : 'out-stock');
                    $stockDot = $totalStock > 10 ? '' : ($totalStock > 0 ? 'low' : 'out');
                    $stockText = $totalStock > 10 ? __('product.in_stock') : ($totalStock > 0 ? __('product.only_left', ['count' => $totalStock]) : __('product.out_of_stock'));
                @endphp
                <div class="pd-stock {{ $stockClass }}" id="pdStockWrap">
                    <span class="pd-stock-dot {{ $stockDot }}" id="pdStockDot"></span>
                    <span class="pd-stock-label" id="pdStockLabel">{{ $stockText }}</span>
                </div>

                <p class="pd-short-desc">
                    {{ $product->short_description ?? 'A modern classic tailored for everyday elegance — crafted in breathable natural fibres, finished with precision.' }}
                </p>

                
                @php
                    $productAttributes = collect();
                    foreach ($product->variants as $variant) {
                        foreach ($variant->attributeValues as $av) {
                            if (!$productAttributes->has($av->attribute->id)) {
                                $productAttributes->put($av->attribute->id, [
                                    'attribute' => $av->attribute,
                                    'values' => collect()
                                ]);
                            }
                            if (!$productAttributes[$av->attribute->id]['values']->contains('id', $av->id)) {
                                $productAttributes[$av->attribute->id]['values']->push($av);
                            }
                        }
                    }
                    $productAttributes = $productAttributes->sortBy('attribute.sort_order')->values();
                    $productAttributes->transform(function ($item) {
                        $item['values'] = $item['values']->sortBy('sort_order')->values();
                        return $item;
                    });
                @endphp

                @if($productAttributes->isNotEmpty())
                    <div id="dynamic-attributes-container">
                        @foreach($productAttributes as $index => $attrGroup)
                            @php
                                $attr = $attrGroup['attribute'];
                                $values = $attrGroup['values'];
                                $isColor = strtolower($attr->type) === 'color_swatch' || in_array(strtolower($attr->name), ['color', 'colour']);
                            @endphp
                            <div class="pd-attribute-group" data-attr-id="{{ $attr->id }}" data-attr-index="{{ $index }}"
                                style="margin-bottom:1.5rem;">
                                <div class="pd-option-label">
                                    {{ $attr->name }} — <span class="selected-value-label" id="label-attr-{{ $attr->id }}">{{ __('product.select_attribute', ['name' => strtolower($attr->name)]) }}</span>
                                </div>
                                <div class="{{ $isColor ? 'pd-swatches' : 'pd-sizes' }}">
                                    @foreach($values as $val)
                                        @if($isColor)
                                            <button type="button" class="pd-swatch attr-btn" data-attr-id="{{ $attr->id }}"
                                                data-val-id="{{ $val->id }}" data-label="{{ $val->value }}" title="{{ $val->value }}"
                                                onclick="handleAttributeSelect(this, {{ $attr->id }}, {{ $val->id }}, '{{ addslashes($val->value) }}')"
                                                style="border-color: transparent;">
                                                <div class="pd-swatch-inner" style="background:{{ $val->color_hex ?? '#ccc' }};"></div>
                                            </button>
                                        @else
                                            <button type="button" class="pd-size-btn attr-btn" data-attr-id="{{ $attr->id }}"
                                                data-val-id="{{ $val->id }}" data-label="{{ $val->value }}"
                                                onclick="handleAttributeSelect(this, {{ $attr->id }}, {{ $val->id }}, '{{ addslashes($val->value) }}')">
                                                {{ strtoupper($val->value) }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                                @if(strtolower($attr->name) === 'size' && ($storefront_size_guide_show ?? true))
                                    <button type="button" class="pd-size-chart-link" style="margin-top:0.5rem;"
                                        onclick="openSizeChart()">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <line x1="8" y1="6" x2="21" y2="6" />
                                            <line x1="8" y1="12" x2="21" y2="12" />
                                            <line x1="8" y1="18" x2="21" y2="18" />
                                            <line x1="3" y1="6" x2="3.01" y2="6" />
                                            <line x1="3" y1="12" x2="3.01" y2="12" />
                                            <line x1="3" y1="18" x2="3.01" y2="18" />
                                        </svg>
                                        {{ __('product.size_guide_fit_chart') }}
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                
                <form id="addToCartForm" action="{{ route('cart.add', $product->variants->first()?->id ?? 0) }}"
                    method="POST">
                    @csrf

                    <div class="pd-qty-add">
                        <div class="pd-qty-selector">
                            <button type="button" class="pd-qty-btn" onclick="decrementQty()">−</button>
                            <input type="number" id="quantityInput" name="quantity" value="1" min="1" class="pd-qty-input"
                                required>
                            <button type="button" class="pd-qty-btn" onclick="incrementQty()">+</button>
                        </div>
                        <button type="submit" class="pd-add-btn" id="addToCartBtn" @if($product->variants->isEmpty())
                        disabled @endif onclick="handleAddToCart(event)">
                            {{ __('product.add_to_cart') }}
                        </button>
                    </div>

                    <button type="button" class="pd-buy-btn" @if($product->variants->isEmpty()) disabled @endif
                        onclick="handleBuyNow()">
                        {{ __('product.buy_now') }}
                    </button>

                    @if($product->variants->isEmpty())
                        <p
                            style="color:var(--rust);font-size:0.78rem;margin-top:-1rem;margin-bottom:1rem;letter-spacing:0.05em;">
                            {{ __('product.out_of_stock_waitlist') }}
                        </p>
                    @endif
                </form>

                
                @if(session('success'))
                    <div
                        style="margin-bottom:1.5rem;padding:0.9rem 1rem;background:rgba(122,158,126,0.1);border:1px solid var(--sage);color:var(--sage);font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
                        ✓ {{ session('success') }} <a href="{{ route('cart.index') }}"
                            style="font-weight:600;text-decoration:underline;color:var(--sage);">{{ __('product.view_all') }} →</a>
                    </div>
                @endif
                @if(session('error'))
                    <div
                        style="margin-bottom:1.5rem;padding:0.9rem 1rem;background:rgba(196,96,42,0.1);border:1px solid var(--rust);color:var(--rust);font-size:0.85rem;">
                        {{ session('error') }}
                    </div>
                @endif

                
                <div class="pd-accordions">

                    
                    <div class="pd-acc-item open" id="acc-desc">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-desc')">
                            <span>{{ __('product.description') }}</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-desc-body">
                            <div class="pd-acc-body-inner">
                                {!! nl2br(e($product->description ?? __('product.no_description'))) !!}
                            </div>
                        </div>
                    </div>

                    
                    <div class="pd-acc-item" id="acc-care">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-care')">
                            <span>{{ __('product.fabric_care') }}</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-care-body">
                            <div class="pd-acc-body-inner">
                                @if($product->fabric_details ?? false)
                                    {!! nl2br(e($product->fabric_details)) !!}
                                @else
                                    <ul>
                                        <li>{{ __('product.fabric_care_1') }}</li>
                                        <li>{{ __('product.fabric_care_2') }}</li>
                                        <li>{{ __('product.fabric_care_3') }}</li>
                                        <li>{{ __('product.fabric_care_4') }}</li>
                                        <li>{{ __('product.fabric_care_5') }}</li>
                                        <li>{{ __('product.fabric_care_6') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($storefront_measure_show ?? true)
                    
                    <div class="pd-acc-item" id="acc-measure">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-measure')">
                            <span>{{ __('product.measurements') }}</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-measure-body">
                            <div class="pd-acc-body-inner">
                                <p style="margin-bottom:0.5rem;font-size:0.82rem;">{{ ($storefront_measure_note === 'Measurements taken on size S. Add 5cm per size.') ? __('product.measurements_note') : ($storefront_measure_note ?? __('product.measurements_note')) }}</p>
                                <table class="pd-measure-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('product.measurement') }}</th>
                                            <th>{{ __('product.cm') }}</th>
                                            <th>{{ __('product.inches') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $measures = is_array($storefront_measure_items) ? $storefront_measure_items : [
                                                ['label' => 'Total length', 'cm' => '118', 'inches' => '46.5"'],
                                                ['label' => 'Bust', 'cm' => '92', 'inches' => '36.2"'],
                                                ['label' => 'Waist', 'cm' => '76', 'inches' => '29.9"'],
                                                ['label' => 'Hem', 'cm' => '152', 'inches' => '59.8"'],
                                                ['label' => 'Sleeve length', 'cm' => '62', 'inches' => '24.4"']
                                            ];
                                        @endphp
                                        @foreach($measures as $measure)
                                            <tr>
                                                <td>
                                                    @if(isset($measure['label']))
                                                        @if($measure['label'] === 'Total length')
                                                            {{ __('product.total_length') }}
                                                        @elseif($measure['label'] === 'Bust')
                                                            {{ __('product.bust_label') }}
                                                        @elseif($measure['label'] === 'Waist')
                                                            {{ __('product.waist_label') }}
                                                        @elseif($measure['label'] === 'Hem')
                                                            {{ __('product.hem_label') }}
                                                        @elseif($measure['label'] === 'Sleeve length')
                                                            {{ __('product.sleeve_length') }}
                                                        @else
                                                            {{ $measure['label'] }}
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $measure['cm'] ?? '' }}</td>
                                                <td>{{ $measure['inches'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($storefront_delivery_show ?? true)
                    
                    <div class="pd-acc-item" id="acc-delivery">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-delivery')">
                            <span>{{ __('product.delivery_returns') }}</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-delivery-body">
                            <div class="pd-acc-body-inner">
                                <div class="pd-delivery-grid">
                                    @php
                                        $deliveries = is_array($storefront_delivery_items) ? $storefront_delivery_items : [
                                            [
                                                'title' => __('product.standard_delivery'),
                                                'subtitle' => \App\Models\Setting::formatPrice($shipping_cost_per_order ?? 0) . ' · ' . __('product.standard_delivery_days') . '<br>' . __('product.free_on_orders_over') . ' ' . \App\Models\Setting::formatPrice($free_shipping_threshold ?? 5000),
                                                'svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'
                                            ],
                                            [
                                                'title' => __('product.express_delivery'),
                                                'subtitle' => \App\Models\Setting::formatPrice(650) . ' · ' . __('product.express_delivery_days') . '<br>' . __('product.order_before_1pm'),
                                                'svg' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'
                                            ],
                                            [
                                                'title' => __('product.free_returns'),
                                                'subtitle' => __('product.free_returns_days') . ' · ' . __('product.unworn_with_tags') . '<br>' . __('product.initiate_via_orders'),
                                                'svg' => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>'
                                            ],
                                            [
                                                'title' => __('product.in_store_pickup'),
                                                'subtitle' => __('product.pickup_locations') . '<br>' . __('product.ready_in_hours'),
                                                'svg' => '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>'
                                            ]
                                        ];
                                    @endphp
                                    @foreach($deliveries as $delivery)
                                        <div class="pd-del-item">
                                            <span class="pd-del-icon" style="display: inline-flex; align-items: center; color: var(--ink);">
                                                @if(isset($delivery['svg']) && !empty($delivery['svg']))
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                        {!! $delivery['svg'] !!}
                                                    </svg>
                                                @else
                                                    🚚
                                                @endif
                                            </span>
                                            <div>
                                                <div class="pd-del-title">{{ $delivery['title'] ?? '' }}</div>
                                                <div class="pd-del-sub">{!! $delivery['subtitle'] ?? '' !!}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </section>



    
    <section class="pd-reviews-section" id="reviewsSection">
        <div class="pd-reviews-header">
            <div>
                <div class="pd-reviews-title">{{ __('product.customer_reviews') }}</div>
                <div style="margin-top:0.75rem;">
                    <div class="pd-rating-breakdown">
                        @php
                            $totalReviews = $product->reviews->count();
                            $ratingDist = [
                                5 => $product->reviews->where('rating', 5)->count(),
                                4 => $product->reviews->where('rating', 4)->count(),
                                3 => $product->reviews->where('rating', 3)->count(),
                                2 => $product->reviews->where('rating', 2)->count(),
                                1 => $product->reviews->where('rating', 1)->count(),
                            ];
                        @endphp
                        @foreach([5, 4, 3, 2, 1] as $star)
                            <div class="pd-rb-row">
                                <span class="pd-rb-label">{{ $star }} {{ __('product.star') }}</span>
                                <div class="pd-rb-bar">
                                    <div class="pd-rb-fill"
                                        style="width:{{ $totalReviews > 0 ? ($ratingDist[$star] / $totalReviews) * 100 : 0 }}%;">
                                    </div>
                                </div>
                                <span class="pd-rb-count">{{ $ratingDist[$star] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:1rem;">
                <div class="pd-overall-rating">
                    <div class="pd-rating-big">{{ $avgRating }}</div>
                    <div class="pd-rating-info">
                        <div class="pd-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <svg width="16" height="16" viewBox="0 0 24 24"
                                    fill="{{ $i <= round($avgRating) ? '#c4602a' : 'none' }}" stroke="#c4602a"
                                    stroke-width="1.5">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                            @endfor
                        </div>
                        <div style="font-size:0.78rem;color:var(--mink);">{{ $totalReviews }} {{ __('product.reviews') }}</div>
                    </div>
                </div>
                <button class="pd-write-review-btn" onclick="openReviewModal()">{{ __('product.write_review') }}</button>
            </div>
        </div>

        
        <div class="pd-reviews-grid" id="reviewsGrid">

            @forelse($product->reviews as $review)
                <div class="pd-review-card">
                    <div class="pd-review-top">
                        <div>
                            <div class="pd-reviewer-name">
                                {{ $review->is_anonymous ? __('product.anonymous') : ($review->customer->first_name ?? __('product.anonymous')) }}</div>
                            <div class="pd-review-date">
                                {{ app()->getLocale() === 'es' ? $review->created_at->translatedFormat('F Y') : $review->created_at->format('F Y') }}
                            </div>
                        </div>
                        <span class="pd-verified">✓ {{ __('product.verified') }}</span>
                    </div>

                    <div class="pd-review-stars pd-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? '#c4602a' : 'none' }}"
                                stroke="#c4602a" stroke-width="1.5">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                        @endfor
                    </div>

                    <div class="pd-review-title">{{ $review->title }}</div>
                    <p class="pd-review-body">{{ $review->content }}</p>

                    <div class="pd-review-meta">
                        <span class="pd-review-helpful" onclick="markHelpful({{ $review->id }}, this)">
                            👍 <span class="helpful-label">{{ __('product.helpful') }}</span> (<span
                                class="helpful-count">{{ $review->helpful_count ?? 0 }}</span>)
                        </span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; color: var(--mink); font-style: italic;">
                    {{ __('product.no_reviews_yet') }}
                </div>
            @endforelse

        </div>

        <div class="pd-load-more">
            <button class="pd-load-more-btn">{{ __('product.load_more_reviews') }}</button>
        </div>
    </section>


    
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="pd-related-section">
            <div class="pd-related-inner">
                <div class="pd-related-header">
                    <div class="pd-related-title">{{ __('product.you_may_also_like') }}</div>
                    <a href="{{ route('frontend.products.index') }}" class="pd-related-link">{{ __('product.view_all') }} →</a>
                </div>

                <div class="pd-related-grid">
                    @foreach($relatedProducts->take(4) as $rp)
                        <a href="{{ route('frontend.products.show', $rp->slug ?? $rp->id) }}" class="pd-product-card">
                            <div class="pd-product-card-img">
                                @php
                                    $rpDefaultVariant = $rp->variants->where('is_default', true)->first() ?? $rp->variants->first();
                                    $rpDisplayPrice = $rpDefaultVariant ? ($rpDefaultVariant->sale_price ?? $rpDefaultVariant->price) : $rp->base_price;
                                    $rpOriginalPrice = ($rpDefaultVariant && $rpDefaultVariant->sale_price) ? $rpDefaultVariant->price : null;
                                @endphp
                                @if($rpOriginalPrice)
                                    <span class="pd-card-badge">{{ __('product.sale') }}</span>
                                @endif
                                @php
                                    $rpInWishlist = auth()->check() && auth()->user()->wishlists()->where('product_id', $rp->id)->exists();
                                @endphp
                                <button class="pd-card-wishlist {{ $rpInWishlist ? 'active' : '' }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this, {{ $rp->id }})" aria-label="Wishlist">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1a1612" stroke-width="1.5">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg>
                                </button>
                                @if($rp->primaryImage)
                                    <img src="{{ $rp->primaryImage->url }}" alt="{{ $rp->name }}" loading="lazy">
                                @elseif($rp->images && $rp->images->count() > 0)
                                    <img src="{{ $rp->images->first()->url }}" alt="{{ $rp->name }}" loading="lazy">
                                @else
                                    <img src="@placeholder($rp->id)" alt="{{ $rp->name }}" loading="lazy">
                                @endif
                            </div>
                            <div class="pd-card-name">{{ $rp->name }}</div>
                            <div class="pd-card-price">
                                @if($rpOriginalPrice)
                                    <span class="pd-card-price-sale">@price($rpDisplayPrice)</span>
                                    <span class="pd-card-price-orig">@price($rpOriginalPrice)</span>
                                @else
                                    <span>@price($rpDisplayPrice)</span>
                                @endif
                            </div>
                            <div class="pd-card-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg viewBox="0 0 24 24" fill="{{ $i <= 4 ? '#c4602a' : 'none' }}" stroke="#c4602a"
                                        stroke-width="1.5">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                @endfor
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    
    
    @if($storefront_size_guide_show ?? true)
        @php
            $sgTitle = ($storefront_size_guide_title === 'Size Guide') ? __('product.size_guide_fit_chart') : ($storefront_size_guide_title ?? __('product.size_guide_fit_chart'));
            $sgHeaders = is_array($storefront_size_guide_headers) ? $storefront_size_guide_headers : [
                __('product.size'),
                __('product.bust_cm'),
                __('product.waist_cm'),
                __('product.hip_cm'),
                __('product.uk_eu')
            ];
            $sgRows = is_array($storefront_size_guide_rows) ? $storefront_size_guide_rows : [
                ['XS', '80–84', '62–66', '88–92', '6 / 34'],
                ['S', '84–88', '66–70', '92–96', '8 / 36'],
                ['M', '88–92', '70–74', '96–100', '10 / 38'],
                ['L', '92–98', '74–80', '100–106', '12 / 40'],
                ['XL', '98–104', '80–86', '106–112', '14 / 42'],
                ['XXL', '104–112', '86–94', '112–120', '16 / 44']
            ];
            $sgNote = ($storefront_size_guide_note === 'All measurements in centimetres. If between sizes, size up for a relaxed fit.') ? __('product.size_guide_note_desc') : ($storefront_size_guide_note ?? __('product.size_guide_note_desc'));
            $sgBust = ($storefront_size_guide_bust_desc === 'measure around the fullest part of your chest, keeping the tape horizontal.') ? __('product.bust_desc') : ($storefront_size_guide_bust_desc ?? __('product.bust_desc'));
            $sgWaist = ($storefront_size_guide_waist_desc === 'measure around the narrowest part of your natural waist.') ? __('product.waist_desc') : ($storefront_size_guide_waist_desc ?? __('product.waist_desc'));
            $sgHip = ($storefront_size_guide_hip_desc === 'measure around the fullest part of your hips, about 20cm below your waist.') ? __('product.hip_desc') : ($storefront_size_guide_hip_desc ?? __('product.hip_desc'));
            $sgFit = ($storefront_size_guide_fit_note === 'This piece is cut in a relaxed silhouette with a slightly dropped shoulder. Our model is 175cm and wears a size S.') ? __('product.fit_note_desc') : ($storefront_size_guide_fit_note ?? __('product.fit_note_desc'));
        @endphp
        <div class="modal-overlay" id="sizeChartModal" onclick="closeSizeChart(event)">
            <div class="modal-box">
                <button class="modal-close" onclick="closeSizeChartBtn()">×</button>
                <div class="modal-title">{{ $sgTitle }}</div>

                <table class="size-chart-table">
                    <thead>
                        <tr>
                            @foreach($sgHeaders as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sgRows as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="size-chart-note">{{ $sgNote }}</p>

                <div class="fit-guide" style="margin-top:1.5rem;">
                    <strong>{{ __('product.how_to_measure') }}</strong><br>
                    <strong>{{ __('product.bust') }}</strong> — {{ $sgBust }}<br>
                    <strong>{{ __('product.waist') }}</strong> — {{ $sgWaist }}<br>
                    <strong>{{ __('product.hip') }}</strong> — {{ $sgHip }}
                </div>

                @if($sgFit)
                    <div style="margin-top:1.5rem;padding:1rem;background:var(--sand);font-size:0.82rem;color:var(--mink);border-left:3px solid var(--rust);">
                        <strong style="color:var(--ink);">{{ __('product.fit_note_style') }}</strong>
                        {{ $sgFit }}
                    </div>
                @endif
            </div>
        </div>
    @endif




    
    <div class="modal-overlay" id="reviewModal" onclick="closeReviewModal(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeReviewModalBtn()">×</button>
            <div class="modal-title">{{ __('product.write_review') }}</div>

            <form class="review-form" id="actualReviewForm" onsubmit="submitReview(event)">
                @csrf
                <div>
                    <label>{{ __('product.your_rating') }}</label>
                    <div class="star-picker" id="starPicker">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star-picker-star" data-val="{{ $i }}" onmouseover="hoverStars({{ $i }})"
                                onmouseout="resetStars()" onclick="setRating({{ $i }})">★</span>
                        @endfor
                    </div>
                    <input type="hidden" id="ratingInput" name="rating" value="0">
                </div>
                <div>
                    <label>{{ __('product.review_title') }}</label>
                    <input type="text" name="title" placeholder="{{ app()->getLocale() === 'es' ? 'ej. Excelente calidad' : 'e.g. Stunning quality' }}" required>
                </div>
                <div>
                    <label>{{ __('product.your_review') }}</label>
                    <textarea name="content" placeholder="{{ app()->getLocale() === 'es' ? 'Cuéntale a otros sobre tu experiencia con este producto...' : 'Tell others about your experience with this product...' }}"
                        required></textarea>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem; margin-top:-0.5rem;">
                    <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1" style="width:auto; margin:0;">
                    <label for="is_anonymous"
                        style="margin:0; text-transform:none; font-weight:400; font-style:italic;">{{ __('product.post_anonymously') }}</label>
                </div>
                <button type="submit" class="review-submit-btn" id="reviewSubmitBtn">{{ __('product.submit_review') }}</button>
            </form>
        </div>
    </div>


    
    <div class="modal-overlay" id="lightboxModal" onclick="closeLightbox()" style="z-index:9999;">
        <div style="position:relative;max-width:90vw;max-height:90vh;">
            <button class="modal-close" style="top:0;right:0;color:#fff;" onclick="closeLightbox()">×</button>
            <img id="lightboxImg" src="" alt="Review photo" style="max-width:90vw;max-height:85vh;object-fit:contain;">
        </div>
    </div>


    
    <script>
        /* ── IMAGE GALLERY ─────────────────────────────── */
        function switchImage(src, btn) {
            const mainImg = document.getElementById('mainProductImage');
            mainImg.style.opacity = '0';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
            }, 200);
            document.querySelectorAll('.pd-thumb-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        /* Zoom on click */
        document.getElementById('mainImgWrap').addEventListener('click', function (e) {
            if (e.target.closest('#wishlistBtnMain')) {
                return;
            }
            this.classList.toggle('zoomed');
            const rect = this.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            document.getElementById('mainProductImage').style.transformOrigin = `${x}% ${y}%`;
        });

        /* ── QTY ───────────────────────────────────────── */
        function updateQtyButtonStates() {
            const qtyInput = document.getElementById('quantityInput');
            if (!qtyInput) return;
            const decBtn = qtyInput.previousElementSibling;
            const incBtn = qtyInput.nextElementSibling;
            const val = parseInt(qtyInput.value) || 1;
            const max = parseInt(qtyInput.getAttribute('max')) || 9999;
            
            const isVariantSelected = Object.keys(selectedAttributes).length === numRequiredAttributes;
            
            if (decBtn) {
                decBtn.disabled = !isVariantSelected || (val <= 1);
                decBtn.style.opacity = decBtn.disabled ? '0.3' : '1';
                decBtn.style.pointerEvents = decBtn.disabled ? 'none' : 'auto';
            }
            if (incBtn) {
                incBtn.disabled = !isVariantSelected || (val >= max);
                incBtn.style.opacity = incBtn.disabled ? '0.3' : '1';
                incBtn.style.pointerEvents = incBtn.disabled ? 'none' : 'auto';
            }
        }

        function incrementQty() {
            if (Object.keys(selectedAttributes).length < numRequiredAttributes) {
                validateOptionsSelected();
                return;
            }
            const i = document.getElementById('quantityInput');
            const max = parseInt(i.getAttribute('max')) || 9999;
            const val = parseInt(i.value) || 1;
            if (val < max) {
                i.value = val + 1;
            }
            updateQtyButtonStates();
        }
        
        function decrementQty() {
            if (Object.keys(selectedAttributes).length < numRequiredAttributes) {
                validateOptionsSelected();
                return;
            }
            const i = document.getElementById('quantityInput');
            const val = parseInt(i.value) || 1;
            if (val > 1) {
                i.value = val - 1;
            }
            updateQtyButtonStates();
        }

        /* ── OPTIONS VALIDATION ─────────────────────────── */
        function validateOptionsSelected() {
            if (Object.keys(selectedAttributes).length < numRequiredAttributes) {
                // Find and highlight/shake missing options
                document.querySelectorAll('.pd-attribute-group').forEach(group => {
                    const attrId = parseInt(group.dataset.attrId);
                    if (!selectedAttributes[attrId]) {
                        group.classList.add('pd-highlight-error');
                        setTimeout(() => {
                            group.classList.remove('pd-highlight-error');
                        }, 500);
                    }
                });
                
                // Show toast notification
                showToast(@json(__('product.select_all_options')));
                return false;
            }
            return true;
        }

        function validateQuantity() {
            const qtyInput = document.getElementById('quantityInput');
            if (qtyInput) {
                const val = parseInt(qtyInput.value) || 1;
                const max = parseInt(qtyInput.getAttribute('max'));
                if (max !== null && !isNaN(max) && val > max) {
                    showToast(@json(__('product.out_of_stock')) || 'Selected quantity exceeds available stock.');
                    qtyInput.value = max;
                    updateQtyButtonStates();
                    return false;
                }
            }
            return true;
        }

        /* ── CART ──────────────────────────────────────── */
        function handleAddToCart(e) {
            if (!validateOptionsSelected() || !validateQuantity()) {
                e.preventDefault();
                return;
            }
            /* Allow the form to POST normally — just show a toast on success page reload.
               Or intercept with fetch for AJAX. */
            /* Uncomment below for AJAX (requires a JSON response from your controller): */
            /*
            e.preventDefault();
            const form = document.getElementById('addToCartForm');
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json()).then(data => {
                showToast(data.message || 'Added to bag');
            });
            */
        }

        function handleBuyNow() {
            if (!validateOptionsSelected() || !validateQuantity()) {
                return;
            }
            const form = document.getElementById('addToCartForm');
            // Could redirect to checkout directly
            form.action = form.action.replace('cart/add', 'checkout/quick');
            form.submit();
        }

        function showToast(msg) {
            const t = document.getElementById('pdToast');
            document.getElementById('pdToastMsg').textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3500);
        }

        /* ── DYNAMIC VARIANT / ATTRIBUTE SELECTION ────────────────────── */
        const rawVariants = @json($product->variants);
        let selectedAttributes = {};
        const addToCartForm = document.getElementById('addToCartForm');
        const addToCartBtn = document.getElementById('addToCartBtn');

        // Convert array structure for easy filtering
        const variants = rawVariants.map(v => {
            const attrs = {};
            const attrValues = v.attribute_values || v.attributeValues;
            if (attrValues) {
                attrValues.forEach(av => {
                    const attrId = av.attribute_id !== undefined ? av.attribute_id : av.attributeId;
                    attrs[attrId] = av.id;
                });
            }

            // Get the first image assigned specifically to this variant, 
            // or null if none assigned.
            let variantImg = null;
            if (v.images && v.images.length > 0) {
                variantImg = v.images[0].url;
            }

            return {
                id: v.id,
                price: parseFloat(v.price),
                sale_price: v.sale_price ? parseFloat(v.sale_price) : null,
                stock: v.stock_quantity,
                attributes: attrs,
                image_url: variantImg
            };
        });

        const productPrimaryImage = @json($product->primaryImage ? $product->primaryImage->url : ($product->images->first() ? $product->images->first()->url : null)) || "@placeholder($product->id)";

        const numRequiredAttributes = @json(isset($productAttributes) ? $productAttributes->count() : 0);
        
        // Keep buttons enabled on load unless the entire product is out of stock
        const totalProductStock = @json($product->variants->sum('stock_quantity') ?? 0);
        if (totalProductStock === 0) {
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = @json(__('product.out_of_stock'));
            const buyNowBtn = document.querySelector('.pd-buy-btn');
            if (buyNowBtn) buyNowBtn.disabled = true;
        } else {
            addToCartBtn.disabled = false;
            addToCartBtn.textContent = @json(__('product.add_to_cart'));
        }

        function getMatchingVariants(selections) {
            return variants.filter(v => {
                for (let attrId in selections) {
                    if (v.attributes[attrId] !== selections[attrId]) return false;
                }
                return true;
            });
        }

        function handleAttributeSelect(btn, attrId, valId, label) {
            // Toggle if already selected
            if (selectedAttributes[attrId] === valId) {
                delete selectedAttributes[attrId];
                btn.classList.remove('active');
                if (btn.classList.contains('pd-swatch')) btn.style.borderColor = 'transparent';
                document.getElementById('label-attr-' + attrId).textContent = @json(__('product.select_an_option'));
            } else {
                selectedAttributes[attrId] = valId;
                // Remove active from peers in the DOM
                const group = document.querySelector(`.pd-attribute-group[data-attr-id="${attrId}"]`);
                group.querySelectorAll('.attr-btn').forEach(b => {
                    b.classList.remove('active');
                    if (b.classList.contains('pd-swatch')) b.style.borderColor = 'transparent';
                });
                btn.classList.add('active');
                if (btn.classList.contains('pd-swatch')) btn.style.borderColor = 'var(--ink)';
                document.getElementById('label-attr-' + attrId).textContent = label;
            }

            updateAttributeAvailability();
            resolveVariant();
        }

        function updateAttributeAvailability() {
            document.querySelectorAll('.pd-attribute-group').forEach(group => {
                const attrId = parseInt(group.dataset.attrId);

                const otherSelections = { ...selectedAttributes };
                delete otherSelections[attrId];
                const matchesIgnoreThisAttr = getMatchingVariants(otherSelections);
                const validValsForThisAttr = new Set();
                matchesIgnoreThisAttr.forEach(v => {
                    // Limit availability only to options that have stock available
                    if (v.attributes[attrId] && v.stock > 0) {
                        validValsForThisAttr.add(v.attributes[attrId]);
                    }
                });

                group.querySelectorAll('.attr-btn').forEach(btn => {
                    const valId = parseInt(btn.dataset.valId);
                    if (validValsForThisAttr.has(valId)) {
                        btn.classList.remove('soldout');
                        btn.removeAttribute('data-sold-out');
                        btn.disabled = false;
                    } else {
                        btn.classList.add('soldout');
                        btn.setAttribute('data-sold-out', 'true');
                        btn.disabled = true;
                        // Auto-deselect if it becomes invalid and was selected
                        if (selectedAttributes[attrId] === valId) {
                            delete selectedAttributes[attrId];
                            btn.classList.remove('active');
                            if (btn.classList.contains('pd-swatch')) btn.style.borderColor = 'transparent';
                            document.getElementById('label-attr-' + attrId).textContent = @json(__('product.select_an_option'));
                        }
                    }
                });
            });
        }

        function resolveVariant() {
            const exactMatch = variants.find(v => {
                return Object.keys(v.attributes).length === numRequiredAttributes &&
                    Object.keys(v.attributes).every(k => v.attributes[k] === selectedAttributes[k]);
            });

            const buyNowBtn = document.querySelector('.pd-buy-btn');

            if (exactMatch && Object.keys(selectedAttributes).length === numRequiredAttributes) {
                // Update Add to Cart Form
                addToCartForm.action = addToCartForm.action.replace(/\/\d+$/, '/' + exactMatch.id);
                const inStock = exactMatch.stock > 0;
                addToCartBtn.disabled = !inStock;
                addToCartBtn.textContent = inStock ? @json(__('product.add_to_cart')) : @json(__('product.out_of_stock'));
                if (buyNowBtn) {
                    buyNowBtn.disabled = !inStock;
                }

                // Update Price
                const basePriceFmt = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(exactMatch.price);
                let priceHtml = `<span class="pd-price" id="pdBasePrice">{{ $currency_symbol }}${basePriceFmt}</span>`;
                if (exactMatch.sale_price && exactMatch.sale_price < exactMatch.price) {
                    const salePriceFmt = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(exactMatch.sale_price);
                    priceHtml = `
                        <span class="pd-price" id="pdBasePrice" style="color:var(--emerald);">{{ $currency_symbol }}${salePriceFmt}</span>
                        <span class="pd-price" style="text-decoration:line-through; color:var(--dim); font-size:0.8em; margin-left:0.5rem;">{{ $currency_symbol }}${basePriceFmt}</span>
                    `;
                }
                document.getElementById('pdPriceWrap').innerHTML = priceHtml;

                // Update Stock availability and display
                const transInStock = @json(__('product.in_stock'));
                const transOutOfStock = @json(__('product.out_of_stock'));
                const transOnlyLeft = @json(__('product.only_left', ['count' => '__COUNT__']));

                const stockClass = exactMatch.stock > 10 ? 'in-stock' : (exactMatch.stock > 0 ? 'low-stock' : 'out-stock');
                const stockDot = exactMatch.stock > 10 ? '' : (exactMatch.stock > 0 ? 'low' : 'out');
                const stockText = exactMatch.stock > 10 
                    ? `${transInStock} (${exactMatch.stock} available)` 
                    : (exactMatch.stock > 0 ? transOnlyLeft.replace('__COUNT__', exactMatch.stock) : transOutOfStock);

                const stockWrap = document.getElementById('pdStockWrap');
                stockWrap.className = 'pd-stock ' + stockClass;
                stockWrap.innerHTML = `
                    <span class="pd-stock-dot ${stockDot}" id="pdStockDot"></span>
                    <span class="pd-stock-label" id="pdStockLabel">${stockText}</span>
                `;

                // Set max attribute on quantity selector to match stock
                const qtyInput = document.getElementById('quantityInput');
                if (qtyInput) {
                    qtyInput.removeAttribute('disabled');
                    qtyInput.setAttribute('max', exactMatch.stock);
                    const maxStock = exactMatch.stock > 0 ? exactMatch.stock : 1;
                    if (parseInt(qtyInput.value) > maxStock) {
                        qtyInput.value = maxStock;
                    }
                    updateQtyButtonStates();
                }

                // Update Image
                const targetImageUrl = exactMatch.image_url || productPrimaryImage;
                if (targetImageUrl) {
                    const mainImg = document.getElementById('mainProductImage');
                    // Use a safe comparison (comparing last parts of the URL if needed, 
                    // but checking full string for now)
                    if (!mainImg.src.endsWith(targetImageUrl) && mainImg.src !== targetImageUrl) {
                        mainImg.style.opacity = '0';
                        setTimeout(() => {
                            mainImg.src = targetImageUrl;
                            mainImg.style.opacity = '1';
                        }, 200);
                    }
                }
            } else {
                // Not a complete match yet or variant not found, keep button enabled for validation
                addToCartBtn.disabled = false;
                addToCartBtn.textContent = @json(__('product.add_to_cart'));
                if (buyNowBtn) {
                    buyNowBtn.disabled = false;
                }

                // Reset max attribute on quantity selector and disable it
                const qtyInput = document.getElementById('quantityInput');
                if (qtyInput) {
                    qtyInput.setAttribute('disabled', 'true');
                    qtyInput.removeAttribute('max');
                    qtyInput.value = 1;
                    updateQtyButtonStates();
                }

                // Revert stock to "Select options" state
                const stockWrap = document.getElementById('pdStockWrap');
                stockWrap.className = 'pd-stock in-stock';
                stockWrap.innerHTML = `
                    <span class="pd-stock-dot" style="background:#ccc;"></span>
                    <span class="pd-stock-label" style="color:#777;">${@json(__('product.select_all_options'))}</span>
                `;
            }
        }

        // Run once on load to dim truly unavailable starting attributes and set correct initial disabled states
        document.addEventListener('DOMContentLoaded', () => {
            updateAttributeAvailability();
            resolveVariant();
        });

        /* ── WISHLIST ──────────────────────────────────── */
        function toggleWishlist(btn, productId) {
            @if(!auth()->check())
                window.location.href = "{{ route('login') }}";
                return;
            @endif

            // Optimistic UI update
            btn.classList.toggle('active');

            fetch(`/wishlist/${productId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        btn.classList.add('active');
                        showToast('❤ ' + @json(__('product.saved_to_wishlist')));
                    } else if (data.status === 'removed') {
                        btn.classList.remove('active');
                        showToast(@json(__('product.removed_from_wishlist')));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert optimistic UI update
                    btn.classList.toggle('active');
                    showToast(@json(__('product.error_wishlist')));
                });
        }

        /* ── ACCORDION ─────────────────────────────────── */
        function toggleAcc(id) {
            const item = document.getElementById(id);
            const body = document.getElementById(id + '-body');
            const isOpen = item.classList.contains('open');
            // Close all
            document.querySelectorAll('.pd-acc-item').forEach(i => {
                i.classList.remove('open');
                i.querySelector('.pd-acc-body').style.maxHeight = '0';
            });
            if (!isOpen) {
                item.classList.add('open');
                body.style.maxHeight = body.scrollHeight + 'px';
            }
        }

        // Open first accordion on load and check for review hash
        document.addEventListener('DOMContentLoaded', () => {
            const first = document.querySelector('.pd-acc-item.open .pd-acc-body');
            if (first) first.style.maxHeight = first.scrollHeight + 'px';

            // Auto-open review modal if coming from orders
            if (window.location.hash === '#write-review') {
                setTimeout(openReviewModal, 500);
                // Scroll to reviews section
                const reviewsSection = document.getElementById('reviewsSection');
                if (reviewsSection) reviewsSection.scrollIntoView({ behavior: 'smooth' });
            }

            // Setup manual quantity inputs validation and update button states
            const qtyInput = document.getElementById('quantityInput');
            if (qtyInput) {
                const validateQty = function() {
                    let val = parseInt(qtyInput.value) || 1;
                    let max = parseInt(qtyInput.getAttribute('max')) || 9999;
                    if (val > max) val = max;
                    if (val < 1) val = 1;
                    qtyInput.value = val;
                    updateQtyButtonStates();
                };
                qtyInput.addEventListener('input', validateQty);
                qtyInput.addEventListener('change', validateQty);
                updateQtyButtonStates();
            }

            // Capture clicks on quantity selector before variant is chosen to trigger option validation
            const qtySelector = document.querySelector('.pd-qty-selector');
            if (qtySelector) {
                qtySelector.addEventListener('click', function(e) {
                    if (Object.keys(selectedAttributes).length < numRequiredAttributes) {
                        validateOptionsSelected();
                    }
                }, true); // Capture phase click interception
            }
        });

        /* ── SIZE CHART ────────────────────────────────── */
        function openSizeChart() { document.getElementById('sizeChartModal').classList.add('open'); document.body.style.overflow = 'hidden'; }
        function closeSizeChartBtn() { document.getElementById('sizeChartModal').classList.remove('open'); document.body.style.overflow = ''; }
        function closeSizeChart(e) { if (e.target === e.currentTarget) closeSizeChartBtn(); }


        /* ── REVIEW MODAL ──────────────────────────────── */
        let currentRating = 0;

        function openReviewModal() {
            @if(!auth()->check())
                if (typeof openAuthModal === 'function') {
                    openAuthModal('login');
                } else {
                    window.location.href = "{{ route('login') }}";
                }
                return;
            @endif
            document.getElementById('reviewModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeReviewModalBtn() { document.getElementById('reviewModal').classList.remove('open'); document.body.style.overflow = ''; }
        function closeReviewModal(e) { if (e.target === e.currentTarget) closeReviewModalBtn(); }

        function hoverStars(val) {
            document.querySelectorAll('.star-picker-star').forEach((s, i) => {
                s.classList.toggle('lit', i < val);
            });
        }
        function resetStars() { hoverStars(currentRating); }
        function setRating(val) {
            currentRating = val;
            document.getElementById('ratingInput').value = val;
            resetStars();
        }
        function submitReview(e) {
            e.preventDefault();

            const rating = document.getElementById('ratingInput').value;
            if (rating == 0) {
                showToast(@json(__('product.please_select_rating')));
                return;
            }

            const btn = document.getElementById('reviewSubmitBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = @json(__('product.submitting'));

            const formData = new FormData(e.target);

            fetch("{{ route('reviews.store', $product->id) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeReviewModalBtn();
                        showToast('✓ ' + data.message);
                        e.target.reset();
                        currentRating = 0;
                        resetStars();
                    } else {
                        showToast('✕ ' + (data.message || 'Error submitting review'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('✕ ' + @json(__('product.network_error')));
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        function markHelpful(reviewId, el) {
            @if(!auth()->check())
                window.location.href = "{{ route('login') }}";
                return;
            @endif

            const label = el.querySelector('.helpful-label');
            const count = el.querySelector('.helpful-count');

            if (el.classList.contains('active')) return;

            fetch(`/reviews/${reviewId}/helpful`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        el.classList.add('active');
                        label.textContent = @json(__('product.thanks'));
                        count.textContent = data.helpful_count;
                        el.style.color = 'var(--ink)';
                        el.style.pointerEvents = 'none';
                    }
                });
        }

        /* ── LIGHTBOX ──────────────────────────────────── */
        function openLightbox(src) {
            document.getElementById('lightboxImg').src = src;
            document.getElementById('lightboxModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightboxModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        /* ── Show toast on session success ── */
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => showToast('{{ session("success") }}'));
        @endif
    </script>


@endsection