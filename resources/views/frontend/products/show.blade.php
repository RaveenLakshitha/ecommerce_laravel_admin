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
        --ink:   #1a1612;
        --rust:  #c4602a;
        --sand:  #ede9e1;
        --mink:  #7a6f66;
        --sage:  #7a9e7e;
        --white: var(--bg-creamy, #faf9f7);
        --pd-font-display: 'Cormorant Garamond', Georgia, serif;
        --pd-font-body:    'Jost', sans-serif;
        --nav-h: 80px;
        --ease:  cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    .pd-section { font-family: var(--pd-font-body); background: var(--white); color: var(--ink); }
    a { text-decoration: none; color: inherit; }

    /* ── SECTION ──────────────────────────────────────────── */
    .pd-section { padding: 3rem 0 7rem; }

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
    .pd-breadcrumbs a:hover { color: var(--rust); }

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
    .pd-main-img-wrap.zoomed { cursor: zoom-out; }

    .pd-main-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.35s var(--ease), transform 0.5s var(--ease);
        transform-origin: var(--zoom-x, 50%) var(--zoom-y, 50%);
    }
    .pd-main-img-wrap.zoomed .pd-main-img { transform: scale(2); }


    /* Wishlist heart */
    .pd-wishlist-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255,255,255,0.9);
        border: none;
        width: 40px; height: 40px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        z-index: 2;
        backdrop-filter: blur(4px);
        transition: background 0.2s, transform 0.2s;
    }
    .pd-wishlist-btn:hover { background: #fff; transform: scale(1.1); }
    .pd-wishlist-btn svg { transition: fill 0.25s; }
    .pd-wishlist-btn.active svg { fill: #c4602a; stroke: #c4602a; }

    /* Thumbnails */
    .pd-thumbs {
        display: flex;
        gap: 0.6rem;
        margin-top: 0.75rem;
        overflow-x: auto;
        padding-bottom: 0.25rem;
        scrollbar-width: none;
    }
    .pd-thumbs::-webkit-scrollbar { display: none; }

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
    .pd-thumb-btn:hover { opacity: 1; border-color: var(--rust); }
    .pd-thumb-btn img { width: 100%; height: 100%; object-fit: cover; }



    /* ══════════════════════════════════════════════════════
       INFO COLUMN
       ══════════════════════════════════════════════════════ */
    .pd-info { padding-top: 0.5rem; }

    .pd-title {
        font-family: var(--pd-font-display);
        font-size: clamp(2rem, 3.2vw, 2.8rem);
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
    .pd-stars { display: flex; gap: 2px; }
    .pd-stars svg { width: 14px; height: 14px; }
    .pd-rating-count {
        font-size: 0.78rem;
        color: var(--mink);
        border-bottom: 1px solid var(--mink);
    }
    .pd-rating-count:hover { color: var(--rust); border-color: var(--rust); }

    /* Price */
    .pd-price-wrap {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    .pd-price { font-size: 1.5rem; font-weight: 500; color: var(--ink); }
    .pd-price-orig {
        font-size: 1rem;
        color: var(--mink);
        text-decoration: line-through;
    }
    .pd-price-sale { font-size: 1.5rem; font-weight: 500; color: var(--rust); }
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
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--sage);
    }
    .pd-stock-dot.low { background: #e8a030; }
    .pd-stock-dot.out { background: #c4402a; }
    .pd-stock.in-stock .pd-stock-label { color: var(--sage); }
    .pd-stock.low-stock .pd-stock-label { color: #e8a030; }
    .pd-stock.out-stock .pd-stock-label { color: #c4402a; }

    .pd-short-desc {
        font-size: 0.9375rem;
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
        width: 32px; height: 32px;
        border-radius: 50%;
        border: 2px solid transparent;
        padding: 2px;
        cursor: pointer;
        background: transparent;
        transition: border-color 0.2s;
        position: relative;
    }
    .pd-swatch-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
    }
    .pd-swatch.active, .pd-swatch:hover { border-color: var(--ink); }
    .pd-swatch[data-sold-out]::after {
        content: '';
        position: absolute;
        inset: 4px;
        border-radius: 50%;
        background: linear-gradient(135deg, transparent 45%, rgba(255,255,255,0.7) 46%, rgba(255,255,255,0.7) 54%, transparent 55%);
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
    .pd-size-btn:hover { border-color: var(--ink); }
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
    .pd-size-chart-link:hover { color: var(--rust); border-bottom-color: var(--rust); }

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
    .pd-qty-selector:hover, .pd-qty-selector:focus-within {
        border-color: var(--ink);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
    .pd-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .pd-add-btn {
        flex: 1;
        background: var(--ink);
        color: var(--white);
        border: none;
        font-family: var(--pd-font-body);
        font-weight: 600;
        font-size: 0.75rem;
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
        bottom: 2rem; right: 2rem;
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
    .pd-toast.show { transform: translateY(0); }
    .pd-toast-icon { color: var(--sage); font-size: 1.1rem; }

    /* ── ACCORDION (Description / Care / Delivery) ─────── */
    .pd-accordions { border-top: 1px solid var(--sand); margin-top: 2rem; }
    .pd-acc-item { border-bottom: 1px solid var(--sand); }
    .pd-acc-btn {
        width: 100%;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 0;
        font-family: var(--pd-font-body);
        font-size: 0.72rem;
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
    .pd-acc-item.open .pd-acc-icon { transform: rotate(45deg); }
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
    .pd-acc-body-inner li { margin-bottom: 0.3rem; }

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
        border-bottom: 1px solid rgba(0,0,0,0.05);
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
        position: fixed; inset: 0;
        background: rgba(26,22,18,0.55);
        z-index: 7000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.open { display: flex; }
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
        top: 1.25rem; right: 1.25rem;
        background: none; border: none;
        font-size: 1.75rem;
        line-height: 1;
        cursor: pointer;
        color: var(--mink);
    }
    .modal-close:hover { color: var(--ink); }
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
    .size-chart-table tr:hover td { background: rgba(237,233,225,0.4); }
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
    .fit-guide strong { color: var(--ink); }

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
    .pd-rating-info { display: flex; flex-direction: column; gap: 0.3rem; }

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
    .pd-write-review-btn:hover { background: var(--ink); color: #fff; }

    /* Rating bars */
    .pd-rating-breakdown {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 2.5rem;
        max-width: 340px;
    }
    .pd-rb-row { display: flex; align-items: center; gap: 0.6rem; }
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
    .pd-review-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
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
        background: rgba(122,158,126,0.1);
        padding: 0.2rem 0.5rem;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .pd-review-stars { margin-bottom: 0.5rem; }
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
        width: 64px; height: 64px;
        object-fit: cover;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .pd-review-photo:hover { opacity: 0.8; }
    .pd-review-meta {
        margin-top: 0.75rem;
        font-size: 0.75rem;
        color: var(--mink);
        border-top: 1px solid var(--sand);
        padding-top: 0.6rem;
        display: flex;
        gap: 1rem;
    }
    .pd-review-helpful { cursor: pointer; }
    .pd-review-helpful:hover { color: var(--ink); }

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
    .pd-load-more-btn:hover { background: var(--ink); color: #fff; }

    /* Write review modal */
    .review-form { display: flex; flex-direction: column; gap: 1.25rem; }
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
    .review-form textarea:focus { border-color: var(--ink); }
    .review-form textarea { resize: vertical; min-height: 100px; }

    .star-picker { display: flex; gap: 0.4rem; }
    .star-picker-star {
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--sand);
        transition: color 0.15s;
    }
    .star-picker-star.lit { color: var(--rust); }

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
    .review-submit-btn:hover { background: var(--rust); }

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
    .pd-related-link:hover { color: var(--rust); border-color: var(--rust); }

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
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.6s var(--ease);
    }
    .pd-product-card:hover .pd-product-card-img img { transform: scale(1.04); }

    .pd-card-wishlist {
        position: absolute;
        top: 0.75rem; right: 0.75rem;
        background: rgba(255,255,255,0.85);
        border: none;
        width: 34px; height: 34px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        backdrop-filter: blur(4px);
        transition: background 0.2s, transform 0.2s;
        opacity: 0;
    }
    .pd-product-card:hover .pd-card-wishlist { opacity: 1; }
    .pd-card-wishlist:hover { background: #fff; transform: scale(1.1); }

    .pd-card-badge {
        position: absolute;
        top: 0.75rem; left: 0.75rem;
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
    .pd-card-price-sale { color: var(--rust); }
    .pd-card-price-orig { text-decoration: line-through; font-size: 0.75rem; }
    .pd-card-stars { display: flex; gap: 2px; margin-top: 0.25rem; }
    .pd-card-stars svg { width: 11px; height: 11px; }

    /* ── ANIMATIONS ───────────────────────────────────────── */
    .fade-up {
        opacity: 0;
        transform: translateY(24px);
        animation: fadeUpAnim 0.8s var(--ease) forwards;
    }
    @keyframes fadeUpAnim { to { opacity: 1; transform: translateY(0); } }

    /* ── RESPONSIVE ───────────────────────────────────────── */
    @media (max-width: 1100px) {
        .pd-related-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 900px) {
        .pd-container { grid-template-columns: 1fr; gap: 2.5rem; }
        .pd-gallery { position: static; }
        .pd-related-grid { grid-template-columns: repeat(2, 1fr); }
        .pd-delivery-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 550px) {
        .pd-related-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .pd-reviews-grid { grid-template-columns: 1fr; }
    }
    
    /* ── 3D CUSTOMIZER ────────────────────────────────────── */
    .pd-3d-section {
        max-width: 1340px;
        margin: 0 auto;
        padding: 4rem 2rem;
        border-top: 1px solid var(--sand);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .pd-3d-title {
        font-family: var(--pd-font-display);
        font-size: clamp(2rem, 3.2vw, 2.8rem);
        font-weight: 300;
        color: var(--ink);
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .pd-3d-subtitle {
        color: var(--mink);
        margin-bottom: 2.5rem;
        text-align: center;
        font-size: 0.95rem;
    }
    .pd-3d-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 900px;
        background: var(--sand);
        padding: 1.5rem;
        border-radius: 4px;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.02);
    }
    #canvas-container {
        width: 100%;
        height: 500px;
        background: #e0dcd3;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 2rem;
        cursor: grab;
        border: 1px solid rgba(0,0,0,0.05);
    }
    #canvas-container:active { cursor: grabbing; }
    .pd-3d-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        justify-content: center;
        background: var(--white);
        padding: 1rem 2rem;
        border-radius: 40px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .pd-3d-color-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        padding: 3px;
        background-clip: content-box;
        transition: transform 0.2s, border-color 0.2s;
    }
    .pd-3d-color-btn:hover {
        transform: scale(1.1);
    }
    .pd-3d-color-btn.active {
        border-color: var(--ink);
        transform: scale(1.1);
    }
    </style>

    {{-- ════════════════════════════════════════════════════
         TOAST NOTIFICATION
         ════════════════════════════════════════════════════ --}}
    <div class="pd-toast" id="pdToast">
        <span class="pd-toast-icon">✓</span>
        <span id="pdToastMsg">Item added to cart</span>
    </div>

    {{-- ════════════════════════════════════════════════════
         MAIN PRODUCT SECTION
         ════════════════════════════════════════════════════ --}}
    <section class="pd-section">
        <div class="pd-container">

            {{-- ── LEFT: GALLERY ── --}}
            <div class="pd-gallery fade-up">
                <div class="pd-main-img-wrap" id="mainImgWrap">
                    {{-- Wishlist --}}
                    <button class="pd-wishlist-btn" id="wishlistBtnMain" onclick="toggleWishlist(this)" aria-label="Add to wishlist">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1a1612" stroke-width="1.5">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>


                    {{-- Main image --}}
                    @if($product->primaryImage)
                        <img id="mainProductImage" class="pd-main-img"
                             src="{{ $product->primaryImage->url }}"
                             alt="{{ $product->name }}">
                    @elseif($product->images && $product->images->count() > 0)
                        <img id="mainProductImage" class="pd-main-img"
                             src="{{ $product->images->first()->url }}"
                             alt="{{ $product->name }}">
                    @else
                        <img id="mainProductImage" class="pd-main-img"
                             src="{{ asset('images/placeholder.jpg') }}"
                             alt="{{ $product->name }}">
                    @endif
                </div>

                {{-- Thumbnails --}}
                <div class="pd-thumbs" id="thumbsContainer">
                    @if($product->images && $product->images->count() > 0)
                        @foreach($product->images as $index => $image)
                            <button class="pd-thumb-btn {{ $index === 0 ? 'active' : '' }}"
                                    onclick="switchImage('{{ $image->url }}', this)"
                                    aria-label="View image {{ $index + 1 }}">
                                <img src="{{ $image->url }}" alt="{{ $product->name }} {{ $index + 1 }}">
                            </button>
                        @endforeach
                    @else
                        <button class="pd-thumb-btn active">
                            <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder">
                        </button>
                    @endif
                </div>
            </div>

            {{-- ── RIGHT: INFO ── --}}
            <div class="pd-info fade-up" style="animation-delay:0.15s;">

                {{-- Breadcrumb --}}
                <div class="pd-breadcrumbs">
                    <a href="{{ route('home') }}">Home</a>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    <a href="{{ route('frontend.products.index') }}">Shop</a>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    <span>{{ $product->category->name ?? 'Product' }}</span>
                </div>

                <h1 class="pd-title">{{ $product->name }}</h1>

                {{-- Star Rating --}}
                <div class="pd-rating-bar" onclick="document.getElementById('reviewsSection').scrollIntoView({behavior:'smooth'})">
                    <div class="pd-stars" id="headerStars">
                        @php $avgRating = $product->reviews->count() > 0 ? round($product->reviews->avg('rating'), 1) : 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <svg viewBox="0 0 24 24" fill="{{ $i <= round($avgRating) ? '#c4602a' : 'none' }}" stroke="#c4602a" stroke-width="1.5">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="pd-rating-count">{{ $avgRating > 0 ? $avgRating : 'No' }} ({{ $product->reviews->count() }} reviews)</span>
                </div>

                {{-- Price --}}
                <div class="pd-price-wrap" id="pdPriceWrap">
                    @if($product->sale_price && $product->sale_price < $product->base_price)
                        <span class="pd-price-orig">@price($product->base_price)</span>
                        <span class="pd-price-sale" id="pdSalePrice">@price($product->sale_price)</span>
                        @php $discount = round((($product->base_price - $product->sale_price) / $product->base_price) * 100); @endphp
                        <span class="pd-price-badge" id="pdPriceBadge">−{{ $discount }}%</span>
                    @else
                        <span class="pd-price" id="pdBasePrice">@price($product->base_price)</span>
                    @endif
                </div>

                {{-- Stock status --}}
                @php
                    $totalStock = $product->variants->sum('stock_quantity') ?? 0;
                    $stockClass = $totalStock > 10 ? 'in-stock' : ($totalStock > 0 ? 'low-stock' : 'out-stock');
                    $stockDot = $totalStock > 10 ? '' : ($totalStock > 0 ? 'low' : 'out');
                    $stockText = $totalStock > 10 ? 'In Stock' : ($totalStock > 0 ? "Only {$totalStock} left" : 'Out of Stock');
                @endphp
                <div class="pd-stock {{ $stockClass }}" id="pdStockWrap">
                    <span class="pd-stock-dot {{ $stockDot }}" id="pdStockDot"></span>
                    <span class="pd-stock-label" id="pdStockLabel">{{ $stockText }}</span>
                </div>

                <p class="pd-short-desc">
                    {{ $product->short_description ?? 'A modern classic tailored for everyday elegance — crafted in breathable natural fibres, finished with precision.' }}
                </p>

                {{-- ── DYNAMIC ATTRIBUTE SELECTORS ── --}}
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
                            <div class="pd-attribute-group" data-attr-id="{{ $attr->id }}" data-attr-index="{{ $index }}" style="margin-bottom:1.5rem;">
                                <div class="pd-option-label">
                                    {{ $attr->name }} — <span class="selected-value-label" id="label-attr-{{ $attr->id }}">Select a {{ strtolower($attr->name) }}</span>
                                </div>
                                <div class="{{ $isColor ? 'pd-swatches' : 'pd-sizes' }}">
                                    @foreach($values as $val)
                                        @if($isColor)
                                            <button type="button" class="pd-swatch attr-btn"
                                                    data-attr-id="{{ $attr->id }}"
                                                    data-val-id="{{ $val->id }}"
                                                    data-label="{{ $val->value }}"
                                                    title="{{ $val->value }}"
                                                    onclick="handleAttributeSelect(this, {{ $attr->id }}, {{ $val->id }}, '{{ addslashes($val->value) }}')"
                                                    style="border-color: transparent;">
                                                <div class="pd-swatch-inner" style="background:{{ $val->color_hex ?? '#ccc' }};"></div>
                                            </button>
                                        @else
                                            <button type="button" class="pd-size-btn attr-btn"
                                                    data-attr-id="{{ $attr->id }}"
                                                    data-val-id="{{ $val->id }}"
                                                    data-label="{{ $val->value }}"
                                                    onclick="handleAttributeSelect(this, {{ $attr->id }}, {{ $val->id }}, '{{ addslashes($val->value) }}')">
                                                {{ strtoupper($val->value) }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                                @if(strtolower($attr->name) === 'size')
                                    <button type="button" class="pd-size-chart-link" style="margin-top:0.5rem;" onclick="openSizeChart()">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                                        Size guide &amp; fit chart
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- ── FORM ── --}}
                <form id="addToCartForm"
                      action="{{ route('cart.add', $product->variants->first()?->id ?? 0) }}"
                      method="POST">
                    @csrf

                    <div class="pd-qty-add">
                        <div class="pd-qty-selector">
                            <button type="button" class="pd-qty-btn" onclick="decrementQty()">−</button>
                            <input type="number" id="quantityInput" name="quantity" value="1" min="1" class="pd-qty-input" required>
                            <button type="button" class="pd-qty-btn" onclick="incrementQty()">+</button>
                        </div>
                        <button type="submit" class="pd-add-btn"
                                id="addToCartBtn"
                                @if($product->variants->isEmpty()) disabled @endif
                                onclick="handleAddToCart(event)">
                            Add to Bag
                        </button>
                    </div>

                    <button type="button" class="pd-buy-btn"
                            @if($product->variants->isEmpty()) disabled @endif
                            onclick="handleBuyNow()">
                        Buy Now
                    </button>

                    @if($product->variants->isEmpty())
                        <p style="color:var(--rust);font-size:0.78rem;margin-top:-1rem;margin-bottom:1rem;letter-spacing:0.05em;">
                            Currently out of stock — join the waitlist below.
                        </p>
                    @endif
                </form>

                {{-- Session messages --}}
                @if(session('success'))
                    <div style="margin-bottom:1.5rem;padding:0.9rem 1rem;background:rgba(122,158,126,0.1);border:1px solid var(--sage);color:var(--sage);font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
                        ✓ {{ session('success') }} <a href="{{ route('cart.index') }}" style="font-weight:600;text-decoration:underline;color:var(--sage);">View Cart →</a>
                    </div>
                @endif
                @if(session('error'))
                    <div style="margin-bottom:1.5rem;padding:0.9rem 1rem;background:rgba(196,96,42,0.1);border:1px solid var(--rust);color:var(--rust);font-size:0.85rem;">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- ── ACCORDIONS ── --}}
                <div class="pd-accordions">

                    {{-- Description --}}
                    <div class="pd-acc-item open" id="acc-desc">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-desc')">
                            <span>Description</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-desc-body">
                            <div class="pd-acc-body-inner">
                                {!! nl2br(e($product->description ?? 'No detailed description available.')) !!}
                            </div>
                        </div>
                    </div>

                    {{-- Fabric & Care --}}
                    <div class="pd-acc-item" id="acc-care">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-care')">
                            <span>Fabric &amp; Care</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-care-body">
                            <div class="pd-acc-body-inner">
                                @if($product->fabric_details ?? false)
                                    {!! nl2br(e($product->fabric_details)) !!}
                                @else
                                    <ul>
                                        <li>100% Organic Cotton — breathable and lightweight</li>
                                        <li>Lining: 100% ECOVERO™ Viscose</li>
                                        <li>Machine wash cold, delicate cycle</li>
                                        <li>Do not tumble dry — lay flat to dry</li>
                                        <li>Warm iron on reverse side</li>
                                        <li>Do not dry clean</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Measurements --}}
                    <div class="pd-acc-item" id="acc-measure">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-measure')">
                            <span>Measurements</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-measure-body">
                            <div class="pd-acc-body-inner">
                                <p style="margin-bottom:0.5rem;font-size:0.82rem;">Measurements taken on size S. Add 5cm per size.</p>
                                <table class="pd-measure-table">
                                    <thead>
                                        <tr>
                                            <th>Measurement</th>
                                            <th>cm</th>
                                            <th>inches</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Total length</td><td>118</td><td>46.5"</td></tr>
                                        <tr><td>Bust</td><td>92</td><td>36.2"</td></tr>
                                        <tr><td>Waist</td><td>76</td><td>29.9"</td></tr>
                                        <tr><td>Hem</td><td>152</td><td>59.8"</td></tr>
                                        <tr><td>Sleeve length</td><td>62</td><td>24.4"</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Delivery & Returns --}}
                    <div class="pd-acc-item" id="acc-delivery">
                        <button class="pd-acc-btn" onclick="toggleAcc('acc-delivery')">
                            <span>Delivery &amp; Returns</span>
                            <span class="pd-acc-icon">+</span>
                        </button>
                        <div class="pd-acc-body" id="acc-delivery-body">
                            <div class="pd-acc-body-inner">
                                <div class="pd-delivery-grid">
                                    <div class="pd-del-item">
                                        <span class="pd-del-icon">🚚</span>
                                        <div>
                                            <div class="pd-del-title">Standard Delivery</div>
                                            <div class="pd-del-sub">@price($shipping_cost_per_order) · 3–5 business days<br>Free on orders over @price($free_shipping_threshold)</div>
                                        </div>
                                    </div>
                                    <div class="pd-del-item">
                                        <span class="pd-del-icon">⚡</span>
                                        <div>
                                            <div class="pd-del-title">Express Delivery</div>
                                            <div class="pd-del-sub">@price(650) · Next business day<br>Order before 1 PM</div>
                                        </div>
                                    </div>
                                    <div class="pd-del-item">
                                        <span class="pd-del-icon">↩️</span>
                                        <div>
                                            <div class="pd-del-title">Free Returns</div>
                                            <div class="pd-del-sub">30 days · Unworn &amp; with tags<br>Initiate via My Orders</div>
                                        </div>
                                    </div>
                                    <div class="pd-del-item">
                                        <span class="pd-del-icon">🏪</span>
                                        <div>
                                            <div class="pd-del-title">In-Store Pickup</div>
                                            <div class="pd-del-sub">Colombo &amp; Kandy<br>Ready in 2–3 hours</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- end accordions --}}

            </div>{{-- end pd-info --}}
        </div>{{-- end pd-container --}}
    </section>

    {{-- ════════════════════════════════════════════════════
         3D CUSTOMIZE SECTION
         ════════════════════════════════════════════════════ --}}
    <section class="pd-3d-section" id="customizeSection">
        <h2 class="pd-3d-title">3D Customizer</h2>
        <p class="pd-3d-subtitle">Interact with the model to see your chosen tone from every angle.</p>
        
        <div class="pd-3d-container">
            <div id="canvas-container"></div>
            <div class="pd-3d-controls" id="colorControls">
                <!-- Hardcoded colors matching the theme -->
                <button class="pd-3d-color-btn active" style="background-color: #8ad1c4;" data-color="0x8ad1c4" title="Mint Green"></button>
                <button class="pd-3d-color-btn" style="background-color: #faf9f7;" data-color="0xfaf9f7" title="White"></button>
                <button class="pd-3d-color-btn" style="background-color: #1a1612;" data-color="0x1a1612" title="Ink Black"></button>
                <button class="pd-3d-color-btn" style="background-color: #c4602a;" data-color="0xc4602a" title="Rust"></button>
                <button class="pd-3d-color-btn" style="background-color: #7a9e7e;" data-color="0x7a9e7e" title="Sage"></button>
                <button class="pd-3d-color-btn" style="background-color: #4a5d82;" data-color="0x4a5d82" title="Navy"></button>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════
         REVIEWS SECTION
         ════════════════════════════════════════════════════ --}}
    <section class="pd-reviews-section" id="reviewsSection">
        <div class="pd-reviews-header">
            <div>
                <div class="pd-reviews-title">Customer Reviews</div>
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
                                <span class="pd-rb-label">{{ $star }} star</span>
                                <div class="pd-rb-bar">
                                    <div class="pd-rb-fill" style="width:{{ $totalReviews > 0 ? ($ratingDist[$star] / $totalReviews) * 100 : 0 }}%;"></div>
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= round($avgRating) ? '#c4602a' : 'none' }}" stroke="#c4602a" stroke-width="1.5">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @endfor
                        </div>
                        <div style="font-size:0.78rem;color:var(--mink);">{{ $totalReviews }} reviews</div>
                    </div>
                </div>
                <button class="pd-write-review-btn" onclick="openReviewModal()">Write a Review</button>
            </div>
        </div>

        {{-- Review Cards --}}
        <div class="pd-reviews-grid" id="reviewsGrid">

            @forelse($product->reviews as $review)
                <div class="pd-review-card">
                    <div class="pd-review-top">
                        <div>
                            <div class="pd-reviewer-name">{{ $review->is_anonymous ? 'Anonymous' : ($review->customer->first_name ?? 'Customer') }}</div>
                            <div class="pd-review-date">{{ $review->created_at->format('F Y') }}</div>
                        </div>
                        <span class="pd-verified">✓ Verified</span>
                    </div>

                    <div class="pd-review-stars pd-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? '#c4602a' : 'none' }}" stroke="#c4602a" stroke-width="1.5">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        @endfor
                    </div>

                    <div class="pd-review-title">{{ $review->title }}</div>
                    <p class="pd-review-body">{{ $review->content }}</p>

                    <div class="pd-review-meta">
                        <span class="pd-review-helpful" onclick="this.textContent='Thanks!'">👍 Helpful ({{ $review->helpful_count ?? 0 }})</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; color: var(--mink); font-style: italic;">
                    No reviews yet. Be the first to review this product!
                </div>
            @endforelse

        </div>

        <div class="pd-load-more">
            <button class="pd-load-more-btn">Load More Reviews</button>
        </div>
    </section>


    {{-- ════════════════════════════════════════════════════
         RELATED PRODUCTS
         ════════════════════════════════════════════════════ --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="pd-related-section">
            <div class="pd-related-inner">
                <div class="pd-related-header">
                    <div class="pd-related-title">You may also like</div>
                    <a href="{{ route('frontend.products.index') }}" class="pd-related-link">View all →</a>
                </div>

                <div class="pd-related-grid">
                    @foreach($relatedProducts->take(4) as $rp)
                        <a href="{{ route('frontend.products.show', $rp->slug ?? $rp->id) }}" class="pd-product-card">
                            <div class="pd-product-card-img">
                                @if($rp->sale_price && $rp->sale_price < $rp->base_price)
                                    <span class="pd-card-badge">Sale</span>
                                @endif
                                <button class="pd-card-wishlist" onclick="event.preventDefault(); toggleWishlist(this)" aria-label="Wishlist">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1a1612" stroke-width="1.5">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                </button>
                                @if($rp->primaryImage)
                                    <img src="{{ $rp->primaryImage->url }}" alt="{{ $rp->name }}" loading="lazy">
                                @elseif($rp->images && $rp->images->count() > 0)
                                    <img src="{{ $rp->images->first()->url }}" alt="{{ $rp->name }}" loading="lazy">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $rp->name }}" loading="lazy">
                                @endif
                            </div>
                            <div class="pd-card-name">{{ $rp->name }}</div>
                            <div class="pd-card-price">
                                @if($rp->sale_price && $rp->sale_price < $rp->base_price)
                                    <span class="pd-card-price-sale">@price($rp->sale_price)</span>
                                    <span class="pd-card-price-orig">@price($rp->base_price)</span>
                                @else
                                    <span>@price($rp->base_price)</span>
                                @endif
                            </div>
                            <div class="pd-card-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg viewBox="0 0 24 24" fill="{{ $i <= 4 ? '#c4602a' : 'none' }}" stroke="#c4602a" stroke-width="1.5">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                @endfor
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ════════════════════════════════════════════════════
         SIZE CHART MODAL
         ════════════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="sizeChartModal" onclick="closeSizeChart(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeSizeChartBtn()">×</button>
            <div class="modal-title">Size Guide</div>

            <table class="size-chart-table">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Bust (cm)</th>
                        <th>Waist (cm)</th>
                        <th>Hip (cm)</th>
                        <th>UK/EU</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>XS</td><td>80–84</td><td>62–66</td><td>88–92</td><td>6 / 34</td></tr>
                    <tr><td>S</td><td>84–88</td><td>66–70</td><td>92–96</td><td>8 / 36</td></tr>
                    <tr><td>M</td><td>88–92</td><td>70–74</td><td>96–100</td><td>10 / 38</td></tr>
                    <tr><td>L</td><td>92–98</td><td>74–80</td><td>100–106</td><td>12 / 40</td></tr>
                    <tr><td>XL</td><td>98–104</td><td>80–86</td><td>106–112</td><td>14 / 42</td></tr>
                    <tr><td>XXL</td><td>104–112</td><td>86–94</td><td>112–120</td><td>16 / 44</td></tr>
                </tbody>
            </table>
            <p class="size-chart-note">All measurements in centimetres. If between sizes, size up for a relaxed fit.</p>

            <div class="fit-guide" style="margin-top:1.5rem;">
                <strong>How to measure:</strong><br>
                <strong>Bust</strong> — measure around the fullest part of your chest, keeping the tape horizontal.<br>
                <strong>Waist</strong> — measure around the narrowest part of your natural waist.<br>
                <strong>Hip</strong> — measure around the fullest part of your hips, about 20cm below your waist.
            </div>

            <div style="margin-top:1.5rem;padding:1rem;background:var(--sand);font-size:0.82rem;color:var(--mink);border-left:3px solid var(--rust);">
                <strong style="color:var(--ink);">Fit note for this style:</strong>
                This piece is cut in a relaxed silhouette with a slightly dropped shoulder.
                Our model is 175cm and wears a size S.
            </div>
        </div>
    </div>




    {{-- ════════════════════════════════════════════════════
         WRITE REVIEW MODAL
         ════════════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="reviewModal" onclick="closeReviewModal(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeReviewModalBtn()">×</button>
            <div class="modal-title">Write a Review</div>

            <form class="review-form" onsubmit="submitReview(event)">
                @csrf
                <div>
                    <label>Your Rating</label>
                    <div class="star-picker" id="starPicker">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star-picker-star" data-val="{{ $i }}"
                                  onmouseover="hoverStars({{ $i }})"
                                  onmouseout="resetStars()"
                                  onclick="setRating({{ $i }})">★</span>
                        @endfor
                    </div>
                    <input type="hidden" id="ratingInput" name="rating" value="0">
                </div>
                <div>
                    <label>Your Name</label>
                    <input type="text" name="name" placeholder="e.g. Anika S." required>
                </div>
                <div>
                    <label>Review Title</label>
                    <input type="text" name="title" placeholder="e.g. Stunning quality" required>
                </div>
                <div>
                    <label>Your Review</label>
                    <textarea name="body" placeholder="Tell others about your experience with this product..." required></textarea>
                </div>
                <div>
                    <label>Add Photos (optional)</label>
                    <input type="file" name="photos[]" accept="image/*" multiple>
                </div>
                <button type="submit" class="review-submit-btn">Submit Review</button>
            </form>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════
         LIGHTBOX (review photos)
         ════════════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="lightboxModal" onclick="closeLightbox()" style="z-index:9999;">
        <div style="position:relative;max-width:90vw;max-height:90vh;">
            <button class="modal-close" style="top:0;right:0;color:#fff;" onclick="closeLightbox()">×</button>
            <img id="lightboxImg" src="" alt="Review photo" style="max-width:90vw;max-height:85vh;object-fit:contain;">
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════
         JAVASCRIPT
         ════════════════════════════════════════════════════ --}}
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
    document.getElementById('mainImgWrap').addEventListener('click', function(e) {
        this.classList.toggle('zoomed');
        const rect = this.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top)  / rect.height) * 100;
        document.getElementById('mainProductImage').style.transformOrigin = `${x}% ${y}%`;
    });

    /* ── QTY ───────────────────────────────────────── */
    function incrementQty() {
        const i = document.getElementById('quantityInput');
        i.value = parseInt(i.value) + 1;
    }
    function decrementQty() {
        const i = document.getElementById('quantityInput');
        if (parseInt(i.value) > 1) i.value = parseInt(i.value) - 1;
    }

    /* ── CART ──────────────────────────────────────── */
    function handleAddToCart(e) {
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
        if (v.attribute_values) {
            v.attribute_values.forEach(av => {
                attrs[av.attribute_id] = av.id;
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

    const productPrimaryImage = @json($product->primaryImage ? $product->primaryImage->url : ($product->images->first() ? $product->images->first()->url : asset('images/placeholder.jpg')));

    const numRequiredAttributes = @json(isset($productAttributes) ? $productAttributes->count() : 0);
    // Disable add to cart button initially if there are attributes needed
    if (numRequiredAttributes > 0) {
        addToCartBtn.disabled = true;
        addToCartBtn.textContent = 'Select Options';
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
            document.getElementById('label-attr-' + attrId).textContent = "Select an option";
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
                 if (v.attributes[attrId]) validValsForThisAttr.add(v.attributes[attrId]);
            });

            group.querySelectorAll('.attr-btn').forEach(btn => {
                const valId = parseInt(btn.dataset.valId);
                if (validValsForThisAttr.has(valId)) {
                    btn.classList.remove('soldout');
                    btn.disabled = false;
                } else {
                    btn.classList.add('soldout');
                    btn.disabled = true;
                    // Auto-deselect if it becomes invalid and was selected
                    if (selectedAttributes[attrId] === valId) {
                        delete selectedAttributes[attrId];
                        btn.classList.remove('active');
                        if (btn.classList.contains('pd-swatch')) btn.style.borderColor = 'transparent';
                        document.getElementById('label-attr-' + attrId).textContent = "Select an option";
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

        if (exactMatch && Object.keys(selectedAttributes).length === numRequiredAttributes) {
            // Update Add to Cart Form
            addToCartForm.action = addToCartForm.action.replace(/\/\d+$/, '/' + exactMatch.id);
            const inStock = exactMatch.stock > 0;
            addToCartBtn.disabled = !inStock;
            addToCartBtn.textContent = inStock ? 'Add to Bag' : 'Out of Stock';

            // Update Price
            const basePriceFmt = new Intl.NumberFormat('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(exactMatch.price);
            if (exactMatch.sale_price && exactMatch.sale_price < exactMatch.price) {
                const salePriceFmt = new Intl.NumberFormat('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(exactMatch.sale_price);
                const discount = Math.round(((exactMatch.price - exactMatch.sale_price) / exactMatch.price) * 100);
                document.getElementById('pdPriceWrap').innerHTML = `
                    <span class="pd-price-orig">{{ $currency_symbol }} ${basePriceFmt}</span>
                    <span class="pd-price-sale" id="pdSalePrice">{{ $currency_symbol }} ${salePriceFmt}</span>
                    <span class="pd-price-badge" id="pdPriceBadge">−${discount}%</span>
                `;
            } else {
                document.getElementById('pdPriceWrap').innerHTML = `
                    <span class="pd-price" id="pdBasePrice">{{ $currency_symbol }} ${basePriceFmt}</span>
                `;
            }

            // Update Stock
            const stockClass = exactMatch.stock > 10 ? 'in-stock' : (exactMatch.stock > 0 ? 'low-stock' : 'out-stock');
            const stockDot = exactMatch.stock > 10 ? '' : (exactMatch.stock > 0 ? 'low' : 'out');
            const stockText = exactMatch.stock > 10 ? 'In Stock' : (exactMatch.stock > 0 ? `Only ${exactMatch.stock} left` : 'Out of Stock');
            
            const stockWrap = document.getElementById('pdStockWrap');
            stockWrap.className = 'pd-stock ' + stockClass;
            stockWrap.innerHTML = `
                <span class="pd-stock-dot ${stockDot}" id="pdStockDot"></span>
                <span class="pd-stock-label" id="pdStockLabel">${stockText}</span>
            `;

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
            // Not a complete match yet or variant not found
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = 'Select Options';
            
            // Revert stock to "Select options" state
            const stockWrap = document.getElementById('pdStockWrap');
            stockWrap.className = 'pd-stock in-stock';
            stockWrap.innerHTML = `
                <span class="pd-stock-dot" style="background:#ccc;"></span>
                <span class="pd-stock-label" style="color:#777;">Please select all options</span>
            `;
        }
    }

    // Run once on load to dim truly unavailable starting attributes
    document.addEventListener('DOMContentLoaded', updateAttributeAvailability);

    /* ── WISHLIST ──────────────────────────────────── */
    function toggleWishlist(btn) {
        btn.classList.toggle('active');
        showToast(btn.classList.contains('active') ? '❤ Saved to wishlist' : 'Removed from wishlist');
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

    // Open first accordion on load
    document.addEventListener('DOMContentLoaded', () => {
        const first = document.querySelector('.pd-acc-item.open .pd-acc-body');
        if (first) first.style.maxHeight = first.scrollHeight + 'px';
    });

    /* ── SIZE CHART ────────────────────────────────── */
    function openSizeChart()     { document.getElementById('sizeChartModal').classList.add('open'); document.body.style.overflow='hidden'; }
    function closeSizeChartBtn() { document.getElementById('sizeChartModal').classList.remove('open'); document.body.style.overflow=''; }
    function closeSizeChart(e)   { if (e.target === e.currentTarget) closeSizeChartBtn(); }


    /* ── REVIEW MODAL ──────────────────────────────── */
    let currentRating = 0;

    function openReviewModal()    { document.getElementById('reviewModal').classList.add('open'); document.body.style.overflow='hidden'; }
    function closeReviewModalBtn(){ document.getElementById('reviewModal').classList.remove('open'); document.body.style.overflow=''; }
    function closeReviewModal(e)  { if (e.target === e.currentTarget) closeReviewModalBtn(); }

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
        closeReviewModalBtn();
        showToast('✓ Review submitted — thank you!');
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('canvas-container');
        if (!container || typeof THREE === 'undefined') return;

        // Scene
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xe0dcd3);

        // Camera
        const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
        camera.position.set(0, 0, 1.2); // Frame the shirt properly

        // Renderer
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, preserveDrawingBuffer:true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        renderer.outputEncoding = THREE.sRGBEncoding;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        container.appendChild(renderer.domElement);

        // Controls
        const controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.minDistance = 0.5;
        controls.maxDistance = 3;
        controls.enablePan = false;

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);

        const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
        dirLight.position.set(5, 5, 5);
        dirLight.castShadow = true;
        scene.add(dirLight);

        // T-Shirt Loaders
        const loader = new THREE.GLTFLoader();
        let shirtMesh = null;
        const initialColor = new THREE.Color(0x8ad1c4);

        loader.load(
            '{{ asset("models/shirt_baked.glb") }}',
            function (gltf) {
                const model = gltf.scene;

                // Configure materials on the mesh
                model.traverse((child) => {
                    if (child.isMesh) {
                        child.castShadow = true;
                        child.receiveShadow = true;
                        shirtMesh = child;
                        
                        // We duplicate the material to modify color without altering original 
                        shirtMesh.material = new THREE.MeshStandardMaterial({
                            color: initialColor,
                            roughness: 0.9,
                            metalness: 0.1,
                            map: child.material.map,             // Retain ambient occlusion map
                            side: THREE.DoubleSide
                        });
                        
                        // Add a simple stylized plane as logo 
                        // You can adjust position coordinates below according to the exact model
                        const textureLoader = new THREE.TextureLoader();
                        textureLoader.load('{{ asset("models/github-logo.png") }}', function(tex) {
                            tex.anisotropy = renderer.capabilities.getMaxAnisotropy();
                            const logoGeo = new THREE.PlaneGeometry(0.1, 0.1); // Slightly larger logo
                            const logoMat = new THREE.MeshBasicMaterial({
                                map: tex,
                                transparent: true,
                                depthTest: false,
                                depthWrite: false
                            });
                            const logoMesh = new THREE.Mesh(logoGeo, logoMat);
                            // Position on left chest relative to `shirtMesh` local coords
                            logoMesh.position.set(0.04, 0.04, 0.155); 
                            logoMesh.rotation.set(0, 0, 0); 
                            shirtMesh.add(logoMesh);
                        });
                    }
                });

                // Adjust Scale and Position 
                // The baked shirt from the popular tutorial usually needs specific scaling
                model.scale.set(1.5, 1.5, 1.5);
                model.position.set(0, -0.1, 0); // Keep it centered in the camera
                scene.add(model);
            },
            undefined,
            function (error) {
                console.error('Error loading T-Shirt model:', error);
            }
        );

        // Animation Loop
        let targetColor = initialColor.clone();

        function animate() {
            requestAnimationFrame(animate);
            controls.update();

            // Smooth color interpolation
            if (shirtMesh && shirtMesh.material && !shirtMesh.material.color.equals(targetColor)) {
                shirtMesh.material.color.lerp(targetColor, 0.05);
            }

            renderer.render(scene, camera);
        }
        animate();

        // Responsive
        window.addEventListener('resize', () => {
            if (!container) return;
            camera.aspect = container.clientWidth / container.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(container.clientWidth, container.clientHeight);
        });

        // Color Control Logic
        const colorBtns = document.querySelectorAll('.pd-3d-color-btn');
        colorBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active class
                colorBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Apply color target
                const newColorStr = this.getAttribute('data-color');
                targetColor = new THREE.Color(parseInt(newColorStr, 16));
            });
        });
    });
    </script>
@endsection
