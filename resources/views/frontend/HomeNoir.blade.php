@extends('frontend.layouts.app-noir')

@section('title', $store_name ?? 'KARBNZOL — New Season Drop')

@section('content')

<style>
    /* ── INHERIT LAYOUT VARS ──────────────────────────── */
    :root {
        --void:     #080808;
        --void-2:   #0f0f0f;
        --void-3:   #161616;
        --grid-ln:  #1f1f1f;
        --volt:     #c8ff00;
        --volt-dim: #8cb500;
        --volt-bg:  rgba(200,255,0,0.06);
        --ash:      #888888;
        --smoke:    #444444;
        --bone:     #e8e8e8;
        --white:    #ffffff;
        --font-display: 'Bebas Neue', 'Arial Narrow', sans-serif;
        --font-body:    'Syne', sans-serif;
        --font-mono:    'JetBrains Mono', monospace;
        --ease-harsh: cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* ── HERO ─────────────────────────────────────────── */
    .hero {
        min-height: calc(100vh - 96px);
        display: grid;
        grid-template-columns: 1fr 1fr;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--grid-ln);
    }

    /* Left: big type */
    .hero-left {
        padding: 6rem 3rem 5rem 4rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-right: 1px solid var(--grid-ln);
        position: relative;
        z-index: 2;
    }
    .hero-issue {
        font-family: var(--font-mono);
        font-size: 0.6rem;
        letter-spacing: 0.22em;
        color: var(--volt);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .hero-issue::before {
        content: '';
        width: 20px; height: 1px;
        background: var(--volt);
    }
    .hero-headline {
        font-family: var(--font-display);
        font-size: clamp(5rem, 11vw, 10rem);
        line-height: 0.88;
        letter-spacing: 0.02em;
        color: var(--white);
        margin: auto 0;
    }
    .hero-headline .stroke-text {
        -webkit-text-stroke: 1px var(--white);
        color: transparent;
        display: block;
    }
    .hero-headline .volt-word { color: var(--volt); display: block; }
    .hero-bottom {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 2rem;
    }
    .hero-desc {
        font-size: 0.875rem;
        color: var(--ash);
        line-height: 1.7;
        max-width: 280px;
    }
    .hero-cta-group { display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-end; }
    .btn-volt {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: var(--volt);
        color: var(--void);
        padding: 0.875rem 2rem;
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        transition: background 0.2s, transform 0.15s;
        white-space: nowrap;
    }
    .btn-volt:hover { background: var(--white); transform: translate(-2px, -2px); }
    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: transparent;
        color: var(--bone);
        padding: 0.875rem 2rem;
        border: 1px solid var(--smoke);
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
        white-space: nowrap;
    }
    .btn-outline:hover { border-color: var(--volt); color: var(--volt); background: var(--volt-bg); }

    /* Right: hero image */
    .hero-right {
        position: relative;
        overflow: hidden;
    }
    .hero-right-img {
        width: 100%; height: 100%;
        object-fit: cover;
        filter: grayscale(15%);
        transition: transform 6s ease-out;
    }
    .hero-right::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, var(--void) 0%, transparent 25%, transparent 80%, rgba(8,8,8,0.5) 100%);
    }
    /* Floating stat cards on the image */
    .hero-stat {
        position: absolute;
        z-index: 2;
        background: rgba(8,8,8,0.85);
        backdrop-filter: blur(12px);
        border: 1px solid var(--grid-ln);
        padding: 1rem 1.25rem;
        min-width: 140px;
    }
    .hero-stat-num {
        font-family: var(--font-display);
        font-size: 2.5rem;
        letter-spacing: 0.06em;
        color: var(--volt);
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    .hero-stat-label {
        font-family: var(--font-mono);
        font-size: 0.6rem;
        letter-spacing: 0.16em;
        color: var(--ash);
        text-transform: uppercase;
    }
    .hero-stat-1 { bottom: 3rem; left: -2rem; }
    .hero-stat-2 { top: 3rem; right: 2rem; }

    /* Scroll indicator */
    .hero-scroll-indicator {
        position: absolute;
        bottom: 2.5rem;
        left: 4rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-family: var(--font-mono);
        font-size: 0.6rem;
        letter-spacing: 0.18em;
        color: var(--smoke);
        text-transform: uppercase;
    }
    .hero-scroll-line {
        width: 40px; height: 1px;
        background: var(--smoke);
        position: relative;
        overflow: hidden;
    }
    .hero-scroll-line::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: var(--volt);
        animation: scan-line 2s ease-in-out infinite;
    }
    @keyframes scan-line { to { left: 200%; } }

    /* ── PRODUCT STATS BAR ────────────────────────────── */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-bottom: 1px solid var(--grid-ln);
    }
    .stat-cell {
        padding: 2rem;
        border-right: 1px solid var(--grid-ln);
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .stat-cell:last-child { border-right: none; }
    .stat-num {
        font-family: var(--font-display);
        font-size: 2.5rem;
        letter-spacing: 0.06em;
        color: var(--white);
        line-height: 1;
    }
    .stat-num .volt { color: var(--volt); }
    .stat-desc {
        font-family: var(--font-mono);
        font-size: 0.62rem;
        letter-spacing: 0.14em;
        color: var(--ash);
        text-transform: uppercase;
    }

    /* ── SECTION SHARED ───────────────────────────────── */
    .page-section { padding: 5rem 0; }
    .section-inner { max-width: 1600px; margin: 0 auto; padding: 0 2rem; }
    .section-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--grid-ln);
        gap: 1rem;
    }
    .section-title {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        letter-spacing: 0.06em;
        color: var(--white);
        line-height: 1;
        display: flex;
        align-items: baseline;
        gap: 1rem;
    }
    .section-title-tag {
        font-family: var(--font-mono);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        color: var(--volt);
        text-transform: uppercase;
        padding: 0.3rem 0.75rem;
        border: 1px solid var(--volt);
        align-self: center;
        white-space: nowrap;
    }
    .section-view-all {
        font-family: var(--font-mono);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--ash);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        transition: color 0.2s;
    }
    .section-view-all:hover { color: var(--volt); }
    .section-view-all span {
        display: inline-block;
        transition: transform 0.2s;
    }
    .section-view-all:hover span { transform: translateX(4px); }

    /* ── PRODUCT GRID ─────────────────────────────────── */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        border: 1px solid var(--grid-ln);
    }
    .product-card {
        position: relative;
        border-right: 1px solid var(--grid-ln);
        border-bottom: 1px solid var(--grid-ln);
        overflow: hidden;
        background: var(--void-2);
        transition: background 0.3s;
    }
    .product-card:nth-child(4n) { border-right: none; }
    .product-card:hover { background: var(--void-3); }

    .product-img-wrap {
        aspect-ratio: 2/3;
        overflow: hidden;
        position: relative;
        background: #111;
    }
    .product-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        filter: grayscale(20%);
        transition: filter 0.4s, transform 0.55s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .product-card:hover .product-img-wrap img {
        filter: grayscale(0%);
        transform: scale(1.04);
    }

    /* Badges */
    .p-badge {
        position: absolute;
        top: 0; left: 0;
        background: var(--volt);
        color: var(--void);
        font-family: var(--font-mono);
        font-size: 0.55rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        padding: 0.35rem 0.75rem;
        z-index: 2;
    }
    .p-badge.sale { background: #ff3a3a; color: var(--white); }
    .p-badge.bs   { background: var(--volt); }

    /* Quick add */
    .p-quick {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: var(--volt);
        color: var(--void);
        padding: 0.75rem;
        font-family: var(--font-mono);
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        text-align: center;
        transform: translateY(100%);
        transition: transform 0.3s var(--ease-harsh);
        border: none;
        cursor: none;
    }
    .product-card:hover .p-quick { transform: translateY(0); }

    /* Wishlist */
    .p-wish {
        position: absolute;
        top: 0.75rem; right: 0.75rem;
        width: 32px; height: 32px;
        background: rgba(8,8,8,0.75);
        border: 1px solid var(--smoke);
        display: flex; align-items: center; justify-content: center;
        opacity: 0;
        transform: scale(0.8);
        transition: opacity 0.25s, transform 0.25s, border-color 0.2s;
        z-index: 3;
        cursor: none;
        color: var(--ash);
    }
    .product-card:hover .p-wish { opacity: 1; transform: scale(1); }
    .p-wish:hover { border-color: var(--volt); color: var(--volt); }

    /* Product info */
    .product-info {
        padding: 1rem 1rem 1.25rem;
        border-top: 1px solid var(--grid-ln);
    }
    .p-cat {
        font-family: var(--font-mono);
        font-size: 0.58rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--volt);
        margin-bottom: 0.3rem;
    }
    .p-name {
        font-family: var(--font-body);
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--bone);
        margin-bottom: 0.5rem;
        letter-spacing: 0.01em;
    }
    .p-price-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .p-price {
        font-family: var(--font-mono);
        font-size: 0.8125rem;
        font-weight: 700;
        color: var(--volt);
    }
    .p-price-orig {
        font-family: var(--font-mono);
        font-size: 0.75rem;
        color: var(--smoke);
        text-decoration: line-through;
    }
    .p-swatches {
        display: flex;
        gap: 4px;
        margin-top: 0.6rem;
    }
    .p-swatch {
        width: 12px; height: 12px;
        border: 1px solid var(--smoke);
        cursor: none;
        transition: border-color 0.2s, transform 0.2s;
    }
    .p-swatch:hover { border-color: var(--volt); transform: scale(1.25); }

    /* ── COLLECTIONS — FULL-BLEED GRID ───────────────── */
    .collections-section {
        background: var(--void-2);
        border-top: 1px solid var(--grid-ln);
        border-bottom: 1px solid var(--grid-ln);
        padding: 5rem 0;
    }
    .collection-mosaic {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        grid-template-rows: 320px 220px;
        gap: 1px;
        background: var(--grid-ln);
        overflow: hidden;
    }
    .col-card {
        position: relative;
        overflow: hidden;
        background: #111;
    }
    .col-card:first-child { grid-row: 1 / 3; }
    .col-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: grayscale(30%);
        transition: filter 0.5s, transform 0.65s cubic-bezier(0.25,0.46,0.45,0.94);
    }
    .col-card:hover .col-bg { filter: grayscale(0%); transform: scale(1.05); }
    .col-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(8,8,8,0.9) 0%, rgba(8,8,8,0.2) 50%, transparent 100%);
    }
    .col-content {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 1.5rem;
        z-index: 2;
    }
    .col-num {
        font-family: var(--font-mono);
        font-size: 0.58rem;
        letter-spacing: 0.2em;
        color: var(--volt);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .col-name {
        font-family: var(--font-display);
        font-size: clamp(1.75rem, 3vw, 2.75rem);
        letter-spacing: 0.06em;
        color: var(--white);
        line-height: 0.95;
        margin-bottom: 0.75rem;
    }
    .col-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: var(--font-mono);
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--volt);
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.3s, transform 0.3s;
    }
    .col-card:hover .col-link { opacity: 1; transform: translateY(0); }

    /* ── MARQUEE ──────────────────────────────────────── */
    .marquee-section {
        overflow: hidden;
        border-top: 1px solid var(--grid-ln);
        border-bottom: 1px solid var(--grid-ln);
        padding: 1.25rem 0;
    }
    .marquee-track-noir {
        display: flex;
        animation: marquee-noir 30s linear infinite;
        white-space: nowrap;
    }
    .marquee-track-noir:hover { animation-play-state: paused; }
    .m-item {
        display: inline-flex;
        align-items: center;
        gap: 1.5rem;
        padding: 0 2.5rem;
        font-family: var(--font-display);
        font-size: 2rem;
        letter-spacing: 0.1em;
        color: var(--void-3);
        -webkit-text-stroke: 1px var(--smoke);
        transition: -webkit-text-stroke-color 0.2s;
    }
    .m-item.accent { -webkit-text-stroke-color: var(--volt); color: var(--void-3); }
    .m-sep {
        width: 6px; height: 6px;
        background: var(--volt);
        transform: rotate(45deg);
        flex-shrink: 0;
    }
    @keyframes marquee-noir {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* ── TRUST + NEWSLETTER ───────────────────────────── */
    .trust-nl-section {
        padding: 5rem 0;
        background: var(--void);
    }
    .trust-nl-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        border: 1px solid var(--grid-ln);
    }
    /* Left: trust */
    .trust-panel {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        border-right: 1px solid var(--grid-ln);
    }
    .trust-cell {
        padding: 2.5rem;
        border-bottom: 1px solid var(--grid-ln);
        border-right: 1px solid var(--grid-ln);
        transition: background 0.2s;
    }
    .trust-cell:nth-child(even) { border-right: none; }
    .trust-cell:nth-child(3),
    .trust-cell:nth-child(4) { border-bottom: none; }
    .trust-cell:hover { background: var(--void-3); }
    .trust-icon-box {
        width: 40px; height: 40px;
        border: 1px solid var(--volt);
        display: flex; align-items: center; justify-content: center;
        color: var(--volt);
        margin-bottom: 1.25rem;
    }
    .trust-cell h4 {
        font-family: var(--font-body);
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--bone);
        margin-bottom: 0.4rem;
        letter-spacing: 0.02em;
    }
    .trust-cell p {
        font-size: 0.8rem;
        color: var(--ash);
        line-height: 1.6;
    }

    /* Right: newsletter */
    .newsletter-panel {
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .newsletter-panel::before {
        content: 'SIGN UP';
        position: absolute;
        bottom: -0.1em; right: -0.05em;
        font-family: var(--font-display);
        font-size: 8rem;
        letter-spacing: 0.04em;
        color: transparent;
        -webkit-text-stroke: 1px var(--grid-ln);
        pointer-events: none;
        line-height: 1;
        user-select: none;
    }
    .nl-tag {
        font-family: var(--font-mono);
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.22em;
        color: var(--volt);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1.25rem;
    }
    .nl-tag::before { content: '//'; color: var(--smoke); }
    .nl-title {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        letter-spacing: 0.06em;
        color: var(--white);
        line-height: 0.95;
        margin-bottom: 1rem;
    }
    .nl-title .volt { color: var(--volt); }
    .nl-sub {
        font-size: 0.875rem;
        color: var(--ash);
        line-height: 1.7;
        margin-bottom: 2rem;
        max-width: 320px;
    }
    .nl-form-noir {
        display: flex;
        border: 1px solid var(--smoke);
        position: relative;
        z-index: 1;
        transition: border-color 0.25s;
    }
    .nl-form-noir:focus-within { border-color: var(--volt); }
    .nl-form-noir input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        padding: 1rem 1.25rem;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        color: var(--bone);
        letter-spacing: 0.08em;
    }
    .nl-form-noir input::placeholder { color: var(--smoke); }
    .nl-form-noir button {
        background: var(--volt);
        border: none;
        color: var(--void);
        padding: 1rem 1.5rem;
        font-family: var(--font-mono);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        cursor: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .nl-form-noir button:hover { background: var(--white); }

    /* ── GSAP INIT STATES ─────────────────────────────── */
    .slide-up   { opacity: 0; transform: translateY(40px); }
    .slide-left { opacity: 0; transform: translateX(-40px); }
    .char-anim  { overflow: hidden; }

    /* ── RESPONSIVE ───────────────────────────────────── */
    @media (max-width: 1200px) {
        .hero { grid-template-columns: 1fr; min-height: auto; }
        .hero-right { height: 55vw; }
        .hero-left { padding: 4rem 2rem 3rem; }
        .product-grid { grid-template-columns: repeat(3, 1fr); }
        .product-card:nth-child(4n) { border-right: 1px solid var(--grid-ln); }
        .product-card:nth-child(3n) { border-right: none; }
        .collection-mosaic { grid-template-columns: 1fr 1fr; grid-template-rows: 300px 250px 250px; }
        .col-card:first-child { grid-column: 1 / 3; grid-row: 1; }
    }
    @media (max-width: 900px) {
        .trust-nl-grid { grid-template-columns: 1fr; }
        .trust-panel { border-right: none; border-bottom: 1px solid var(--grid-ln); }
        .stats-bar { grid-template-columns: repeat(2, 1fr); }
        .stat-cell:nth-child(2) { border-right: none; }
        .stat-cell:nth-child(3), .stat-cell:nth-child(4) { border-top: 1px solid var(--grid-ln); }
    }
    @media (max-width: 768px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); }
        .product-card:nth-child(3n) { border-right: 1px solid var(--grid-ln); }
        .product-card:nth-child(2n) { border-right: none; }
        .section-inner { padding: 0 1rem; }
        .newsletter-panel { padding: 2rem; }
        .hero-headline { font-size: 4.5rem; }
        .hero-left { padding: 3rem 1.5rem 2.5rem; }
        .trust-cell { padding: 1.5rem; }
    }
    @media (max-width: 480px) {
        .hero-headline { font-size: 3.5rem; }
        .product-grid { grid-template-columns: 1fr 1fr; }
        .collection-mosaic { grid-template-columns: 1fr; grid-template-rows: 300px 200px 200px; }
        .col-card:first-child { grid-column: 1; grid-row: 1; }
        .stats-bar { grid-template-columns: 1fr 1fr; }
        .section-header { flex-direction: column; align-items: flex-start; }
    }
</style>

{{-- ══════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════ --}}
<section class="hero" id="hero">
    {{-- Left: editorial type --}}
    <div class="hero-left">
        <p class="hero-issue slide-left">SS/2025 — Volume 07</p>

        <h1 class="hero-headline slide-up">
            <span class="stroke-text">WEAR</span>
            YOUR
            <span class="volt-word">EDGE.</span>
        </h1>

        <div class="hero-bottom">
            <p class="hero-desc slide-up">
                Precision cuts. Raw textures. Season-defining silhouettes for those who refuse to blend in.
            </p>
            <div class="hero-cta-group slide-up">
                <a href="{{ route('products.index') }}" class="btn-volt">
                    Shop the Drop
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="#" class="btn-outline">View Lookbook</a>
            </div>
        </div>

        <div class="hero-scroll-indicator" aria-hidden="true">
            <div class="hero-scroll-line"></div>
            Scroll to explore
        </div>
    </div>

    {{-- Right: hero image --}}
    <div class="hero-right">
        <img class="hero-right-img" src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=1200&q=80" alt="New season editorial" id="heroImg">

        {{-- Floating stats --}}
        <div class="hero-stat hero-stat-1 slide-up">
            <div class="hero-stat-num">500+</div>
            <div class="hero-stat-label">New pieces</div>
        </div>
        <div class="hero-stat hero-stat-2 slide-up">
            <div class="hero-stat-num">SS25</div>
            <div class="hero-stat-label">Now live</div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════════════════════ --}}
<div class="stats-bar">
    @php $stats = [
        ['num'=>'12K+','label'=>'Orders Shipped'],
        ['num'=>'98%','label'=>'5-Star Reviews'],
        ['num'=>'48H','label'=>'Island Delivery'],
        ['num'=>'500+','label'=>'Styles In Stock'],
    ]; @endphp
    @foreach($stats as $s)
    <div class="stat-cell slide-up">
        <div class="stat-num">{{ $s['num'] }}</div>
        <div class="stat-desc">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════════════════
     NEW ARRIVALS
══════════════════════════════════════════════════════════ --}}
<section class="page-section" style="background: var(--void);">
    <div class="section-inner">
        <div class="section-header slide-up">
            <h2 class="section-title">
                NEW ARRIVALS
                <span class="section-title-tag">Just Dropped</span>
            </h2>
            <a href="{{ route('products.index') }}" class="section-view-all">
                View All <span>→</span>
            </a>
        </div>

        <div class="product-grid">
            @php
            $newArrivals = [
                ['name'=>'Utility Cargo Pant','cat'=>'Bottoms','price'=>'Rs. 7,400','orig'=>null,'badge'=>'New','badge_cls'=>'','colors'=>['#1a1612','#3d3d3a','#c8ff00'],'img'=>'https://images.unsplash.com/photo-1594938298603-c8148c4b4e3d?w=600&q=80'],
                ['name'=>'Sheer Panel Dress','cat'=>'Women','price'=>'Rs. 5,900','orig'=>'Rs. 8,200','badge'=>'Sale','badge_cls'=>'sale','colors'=>['#080808','#e8e8e8'],'img'=>'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=600&q=80'],
                ['name'=>'Boxy Cord Jacket','cat'=>'Outerwear','price'=>'Rs. 11,500','orig'=>null,'badge'=>'New','badge_cls'=>'','colors'=>['#3d3d3a','#8b7355','#1a1612'],'img'=>'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80'],
                ['name'=>'Structured Tote','cat'=>'Accessories','price'=>'Rs. 9,800','orig'=>null,'badge'=>null,'badge_cls'=>'','colors'=>['#1a1612','#c4602a'],'img'=>'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80'],
            ];
            @endphp

            @foreach($newArrivals as $p)
            <div class="product-card slide-up">
                <div class="product-img-wrap">
                    @if($p['badge'])<span class="p-badge {{ $p['badge_cls'] }}">{{ $p['badge'] }}</span>@endif
                    <button class="p-wish" aria-label="Wishlist">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>
                    <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}" loading="lazy">
                    <button class="p-quick">+ Add to Bag</button>
                </div>
                <div class="product-info">
                    <p class="p-cat">{{ $p['cat'] }}</p>
                    <p class="p-name">{{ $p['name'] }}</p>
                    <div class="p-price-row">
                        <span class="p-price">{{ $p['price'] }}</span>
                        @if($p['orig'])<span class="p-price-orig">{{ $p['orig'] }}</span>@endif
                    </div>
                    <div class="p-swatches">
                        @foreach($p['colors'] as $c)<span class="p-swatch" style="background:{{ $c }};"></span>@endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     MARQUEE
══════════════════════════════════════════════════════════ --}}
<div class="marquee-section" aria-hidden="true">
    <div class="marquee-track-noir">
        @php $words = ['New Season','Limited Drops','Free Shipping','Sri Lanka Made','Bold Cuts','New Season','Limited Drops','Free Shipping','Sri Lanka Made','Bold Cuts','New Season','Limited Drops','Free Shipping','Sri Lanka Made','Bold Cuts','New Season','Limited Drops','Free Shipping','Sri Lanka Made','Bold Cuts']; @endphp
        @foreach($words as $i => $w)
            <span class="m-item {{ $i % 3 === 1 ? 'accent' : '' }}">{{ strtoupper($w)}} <span class="m-sep"></span></span>
        @endforeach
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     COLLECTIONS MOSAIC
══════════════════════════════════════════════════════════ --}}
<section class="collections-section">
    <div class="section-inner">
        <div class="section-header slide-up">
            <h2 class="section-title">
                COLLECTIONS
                <span class="section-title-tag">{{ date('Y') }}</span>
            </h2>
            <a href="#" class="section-view-all">Browse All <span>→</span></a>
        </div>
    </div>
    <div style="max-width: 1600px; margin: 0 auto; padding: 0 2rem;">
        <div class="collection-mosaic">
            @php
            $cols = [
                ['name'=>'The Dark Edit','num'=>'Col. 01','slug'=>'dark-edit','img'=>'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=900&q=80'],
                ['name'=>'Utility Works','num'=>'Col. 02','slug'=>'utility','img'=>'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=600&q=80'],
                ['name'=>'After Hours','num'=>'Col. 03','slug'=>'after-hours','img'=>'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=600&q=80'],
                ['name'=>'Street Ready','num'=>'Col. 04','slug'=>'street','img'=>'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80'],
            ];
            @endphp
            @foreach($cols as $c)
            <div class="col-card slide-up">
                <div class="col-bg" style="background-image: url('{{ $c['img'] }}');"></div>
                <div class="col-overlay"></div>
                <div class="col-content">
                    <p class="col-num">{{ $c['num'] }}</p>
                    <h3 class="col-name">{{ strtoupper($c['name']) }}</h3>
                    <a href="{{ route('collections.show', $c['slug']) }}" class="col-link">
                        Shop Collection
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     BEST SELLERS
══════════════════════════════════════════════════════════ --}}
<section class="page-section" style="background: var(--void-2); border-top: 1px solid var(--grid-ln);">
    <div class="section-inner">
        <div class="section-header slide-up">
            <h2 class="section-title">
                BEST SELLERS
                <span class="section-title-tag">Top Rated</span>
            </h2>
            <a href="{{ route('products.index') }}" class="section-view-all">View All <span>→</span></a>
        </div>

        <div class="product-grid">
            @php
            $bestSellers = [
                ['name'=>'Wide Leg Trousers','cat'=>'Women','price'=>'Rs. 6,400','orig'=>null,'badge'=>'Best Seller','badge_cls'=>'bs','colors'=>['#1a1612','#3d3d3a','#e8e8e8'],'img'=>'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600&q=80'],
                ['name'=>'Classic Poplin Shirt','cat'=>'Men','price'=>'Rs. 3,800','orig'=>null,'badge'=>'Best Seller','badge_cls'=>'bs','colors'=>['#ffffff','#c4d6e0','#1a1612'],'img'=>'https://images.unsplash.com/photo-1581824043583-6904b080a19c?w=600&q=80'],
                ['name'=>'Oversized Bomber','cat'=>'Outerwear','price'=>'Rs. 13,200','orig'=>'Rs. 16,000','badge'=>'Sale','badge_cls'=>'sale','colors'=>['#1a1612','#6b5e52'],'img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80'],
                ['name'=>'Chain Shoulder Bag','cat'=>'Accessories','price'=>'Rs. 8,500','orig'=>null,'badge'=>null,'badge_cls'=>'','colors'=>['#1a1612','#888888'],'img'=>'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80'],
            ];
            @endphp

            @foreach($bestSellers as $p)
            <div class="product-card slide-up">
                <div class="product-img-wrap">
                    @if($p['badge'])<span class="p-badge {{ $p['badge_cls'] }}">{{ $p['badge'] }}</span>@endif
                    <button class="p-wish" aria-label="Wishlist">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>
                    <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}" loading="lazy">
                    <button class="p-quick">+ Add to Bag</button>
                </div>
                <div class="product-info">
                    <p class="p-cat">{{ $p['cat'] }}</p>
                    <p class="p-name">{{ $p['name'] }}</p>
                    <div class="p-price-row">
                        <span class="p-price">{{ $p['price'] }}</span>
                        @if($p['orig'])<span class="p-price-orig">{{ $p['orig'] }}</span>@endif
                    </div>
                    <div class="p-swatches">
                        @foreach($p['colors'] as $c)<span class="p-swatch" style="background:{{ $c }};"></span>@endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     TRUST + NEWSLETTER
══════════════════════════════════════════════════════════ --}}
<section class="trust-nl-section">
    <div class="section-inner">
        <div class="trust-nl-grid">

            {{-- Trust cells --}}
            <div class="trust-panel">
                @php $trusts = [
                    ['icon'=>'<path d="M5 12h14M12 5l7 7-7 7"/>','title'=>'Free Shipping','desc'=>'Island-wide on orders over Rs. 5,000.'],
                    ['icon'=>'<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>','title'=>'Easy Returns','desc'=>'14-day returns, no questions asked.'],
                    ['icon'=>'<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>','title'=>'Secure Payment','desc'=>'256-bit SSL on every checkout.'],
                    ['icon'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.41 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.52 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.16 6.16l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>','title'=>'24/7 Support','desc'=>'Always here when you need us.'],
                ]; @endphp
                @foreach($trusts as $t)
                <div class="trust-cell slide-up">
                    <div class="trust-icon-box">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $t['icon'] !!}</svg>
                    </div>
                    <h4>{{ $t['title'] }}</h4>
                    <p>{{ $t['desc'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Newsletter --}}
            <div class="newsletter-panel slide-up">
                <div>
                    <p class="nl-tag">Newsletter</p>
                    <h2 class="nl-title">
                        GET THE<br>
                        <span class="volt">DROP.</span>
                    </h2>
                    <p class="nl-sub">
                        First access to new collections, exclusive deals, and editorial drops. No noise — just the good stuff.
                    </p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <div class="nl-form-noir">
                            <input type="email" name="email" placeholder="your@email.com" required>
                            <button type="submit">Subscribe →</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     GSAP ANIMATIONS
══════════════════════════════════════════════════════════ --}}
<script>
window.addEventListener('load', () => {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    /* ── Hero entrance ─────────────────────────── */
    const tl = gsap.timeline({ delay: 0.7 });

    tl.fromTo('.hero-issue', { opacity: 0, x: -30 }, { opacity: 1, x: 0, duration: 0.6, ease: 'power3.out' })
      .fromTo('.hero-headline', { opacity: 0, y: 60, skewY: 4 }, { opacity: 1, y: 0, skewY: 0, duration: 0.9, ease: 'power4.out' }, '-=0.3')
      .fromTo('.hero-desc', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.4')
      .fromTo('.hero-cta-group', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.4')
      .fromTo('.hero-scroll-indicator', { opacity: 0 }, { opacity: 1, duration: 0.5 }, '-=0.2')
      .fromTo('.hero-stat', { opacity: 0, y: 20 }, { opacity: 1, y: 0, stagger: 0.15, duration: 0.6, ease: 'power3.out' }, '-=0.6');

    /* Hero image Ken Burns */
    const heroImg = document.getElementById('heroImg');
    if (heroImg) {
        gsap.to(heroImg, { scale: 1.06, duration: 10, ease: 'none', repeat: -1, yoyo: true });
    }

    /* ── Stats bar count-up ─────────────────────── */
    ScrollTrigger.create({
        trigger: '.stats-bar',
        start: 'top 85%',
        once: true,
        onEnter: () => {
            gsap.fromTo('.stat-cell', { opacity: 0, y: 30 }, { opacity: 1, y: 0, stagger: 0.1, duration: 0.6, ease: 'power3.out' });
        }
    });

    /* ── slide-up ScrollTrigger ─────────────────── */
    document.querySelectorAll('.slide-up').forEach(el => {
        gsap.to(el, {
            opacity: 1, y: 0,
            duration: 0.75,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 87%',
                toggleActions: 'play none none none'
            }
        });
    });

    /* ── Product grid card stagger ──────────────── */
    document.querySelectorAll('.product-grid').forEach(grid => {
        const cards = grid.querySelectorAll('.product-card');
        cards.forEach(c => { c.style.opacity = '0'; c.style.transform = 'translateY(30px)'; });
        ScrollTrigger.create({
            trigger: grid,
            start: 'top 82%',
            once: true,
            onEnter: () => {
                gsap.to(cards, {
                    opacity: 1, y: 0,
                    duration: 0.65,
                    ease: 'power3.out',
                    stagger: 0.08
                });
            }
        });
    });

    /* ── Collection mosaic reveal ───────────────── */
    document.querySelectorAll('.col-card').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.clipPath = 'inset(100% 0 0 0)';
        ScrollTrigger.create({
            trigger: card,
            start: 'top 90%',
            once: true,
            onEnter: () => {
                gsap.to(card, {
                    opacity: 1,
                    clipPath: 'inset(0% 0 0 0)',
                    duration: 0.85,
                    ease: 'power4.out',
                    delay: i * 0.1
                });
            }
        });
    });

    /* ── Trust cells stagger ────────────────────── */
    ScrollTrigger.create({
        trigger: '.trust-panel',
        start: 'top 85%',
        once: true,
        onEnter: () => {
            gsap.fromTo('.trust-cell',
                { opacity: 0, y: 20 },
                { opacity: 1, y: 0, stagger: 0.1, duration: 0.6, ease: 'power3.out' }
            );
        }
    });

    /* ── Marquee hover speed control ────────────── */
    const mTrack = document.querySelector('.marquee-track-noir');
    if (mTrack) {
        mTrack.addEventListener('mouseenter', () => gsap.to(mTrack, { '--play-state': 'paused', ease: 'none' }));
    }

    /* ── Volt button magnetic effect ────────────── */
    document.querySelectorAll('.btn-volt').forEach(btn => {
        btn.addEventListener('mousemove', e => {
            const r = btn.getBoundingClientRect();
            const dx = (e.clientX - r.left - r.width/2) / r.width;
            const dy = (e.clientY - r.top - r.height/2) / r.height;
            gsap.to(btn, { x: dx * 8, y: dy * 5, duration: 0.3, ease: 'power2.out' });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1.2, 0.4)' });
        });
    });

    /* ── Section title glitch flash on scroll ────── */
    document.querySelectorAll('.section-title').forEach(title => {
        ScrollTrigger.create({
            trigger: title,
            start: 'top 85%',
            once: true,
            onEnter: () => {
                gsap.fromTo(title,
                    { opacity: 0, x: -20, skewX: -5 },
                    { opacity: 1, x: 0, skewX: 0, duration: 0.55, ease: 'power3.out' }
                );
            }
        });
    });
});
</script>

@endsection