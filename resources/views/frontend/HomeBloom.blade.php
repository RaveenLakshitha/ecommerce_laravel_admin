@extends('frontend.layouts.app-bloom')

@section('title', $store_name ?? 'Karbnzol — Where Style Blooms')

@section('content')

<style>
    :root {
        --blush:      #f9ece8;
        --blush-deep: #f0d8d0;
        --petal:      #e8c4b8;
        --rose:       #c4785a;
        --rose-lt:    #d9957a;
        --sage:       #7a9e7e;
        --sage-lt:    #a8c5ab;
        --sage-bg:    rgba(122,158,126,0.08);
        --linen:      #faf6f2;
        --stone:      #4a3f38;
        --taupe:      #8c7b72;
        --bone:       #e8ddd8;
        --white:      #ffffff;
        --font-display: 'Playfair Display', Georgia, serif;
        --font-body:    'Plus Jakarta Sans', sans-serif;
        --ease-bloom:  cubic-bezier(0.34, 1.56, 0.64, 1);
        --ease-gentle: cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* ── HERO ─────────────────────────────────────────────── */
    .hero {
        background: var(--blush);
        min-height: calc(100vh - 110px);
        display: grid;
        grid-template-columns: 55% 45%;
        position: relative;
        overflow: hidden;
    }

    /* Organic blob shapes in bg */
    .hero-blob {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        filter: blur(60px);
    }
    .hero-blob-1 {
        width: 500px; height: 500px;
        background: rgba(196,120,90,0.12);
        top: -120px; right: 200px;
    }
    .hero-blob-2 {
        width: 350px; height: 350px;
        background: rgba(122,158,126,0.1);
        bottom: -80px; left: 80px;
    }
    .hero-blob-3 {
        width: 250px; height: 250px;
        background: rgba(232,196,184,0.4);
        top: 50%; left: 40%;
        transform: translate(-50%, -50%);
    }

    /* Left text */
    .hero-text-col {
        padding: 5rem 3rem 5rem 5vw;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        z-index: 1;
    }
    .hero-pre {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--sage);
        margin-bottom: 1.75rem;
    }
    .hero-pre .pre-dot {
        width: 6px; height: 6px;
        background: var(--sage);
        border-radius: 50%;
    }
    .hero-title {
        font-family: var(--font-display);
        font-size: clamp(3rem, 6vw, 5.5rem);
        font-weight: 400;
        line-height: 1.05;
        letter-spacing: -0.01em;
        color: var(--stone);
        margin-bottom: 1.75rem;
    }
    .hero-title em {
        font-style: italic;
        color: var(--rose);
        display: block;
    }
    .hero-sub {
        font-size: 1rem;
        color: var(--taupe);
        line-height: 1.75;
        max-width: 380px;
        margin-bottom: 2.5rem;
        font-weight: 300;
    }
    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 3.5rem;
    }
    .btn-petal {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: var(--rose);
        color: var(--white);
        padding: 0.9rem 2.25rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
        box-shadow: 0 8px 24px rgba(196,120,90,0.28);
    }
    .btn-petal:hover {
        background: var(--rose-lt);
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(196,120,90,0.35);
    }
    .btn-ghost-rose {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: transparent;
        color: var(--stone);
        padding: 0.9rem 2.25rem;
        border-radius: 100px;
        border: 1.5px solid var(--petal);
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.04em;
        transition: border-color 0.25s, background 0.25s, transform 0.2s;
    }
    .btn-ghost-rose:hover {
        border-color: var(--rose);
        background: var(--blush-deep);
        transform: translateY(-2px);
    }

    /* Hero mini stats */
    .hero-mini-stats {
        display: flex;
        gap: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--bone);
    }
    .mini-stat {}
    .mini-stat-n {
        font-family: var(--font-display);
        font-size: 1.75rem;
        font-weight: 500;
        color: var(--stone);
        line-height: 1;
        margin-bottom: 0.2rem;
    }
    .mini-stat-l {
        font-size: 0.7rem;
        color: var(--taupe);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-weight: 500;
    }

    /* Right: image collage */
    .hero-image-col {
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .hero-img-main {
        width: 100%; height: 100%;
        object-fit: cover;
        object-position: center top;
    }
    .hero-img-card {
        position: absolute;
        background: var(--white);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        box-shadow: 0 16px 40px rgba(74,63,56,0.12);
        z-index: 3;
    }
    .hero-img-card-1 {
        bottom: 2.5rem;
        left: -2.5rem;
        min-width: 180px;
    }
    .hero-img-card-2 {
        top: 2rem;
        right: 1.5rem;
        min-width: 140px;
    }
    .card-label {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--taupe);
        margin-bottom: 0.3rem;
    }
    .card-value {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-weight: 500;
        color: var(--stone);
    }
    .card-sub {
        font-size: 0.7rem;
        color: var(--sage);
        font-weight: 600;
        margin-top: 0.1rem;
    }

    /* ── CATEGORY PILLS ───────────────────────────────── */
    .categories-row {
        background: var(--white);
        border-bottom: 1px solid var(--bone);
        padding: 1.25rem 0;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .categories-row::-webkit-scrollbar { display: none; }
    .cat-row-inner {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 2.5rem;
        display: flex;
        gap: 0.75rem;
        align-items: center;
        min-width: max-content;
    }
    .cat-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.125rem;
        border-radius: 100px;
        border: 1.5px solid var(--bone);
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--taupe);
        background: transparent;
        cursor: pointer;
        transition: all 0.25s var(--ease-gentle);
        white-space: nowrap;
    }
    .cat-chip:hover { border-color: var(--petal); color: var(--stone); background: var(--blush); }
    .cat-chip.active { background: var(--stone); color: var(--linen); border-color: var(--stone); }
    .cat-chip .chip-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; opacity: 0.5; }

    /* ── SECTION SHARED ───────────────────────────────── */
    .page-sec { padding: 5.5rem 0; }
    .sec-inner { max-width: 1440px; margin: 0 auto; padding: 0 2.5rem; }
    .sec-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3rem;
        gap: 1rem;
    }
    .sec-title {
        font-family: var(--font-display);
        font-size: clamp(1.75rem, 3.5vw, 2.75rem);
        font-weight: 400;
        color: var(--stone);
        line-height: 1.1;
    }
    .sec-title em { font-style: italic; color: var(--rose); }
    .sec-eyebrow {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--sage);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .sec-eyebrow::before { content: ''; width: 16px; height: 1px; background: var(--sage); }
    .sec-all-link {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: var(--taupe);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.2s, gap 0.2s;
        white-space: nowrap;
    }
    .sec-all-link:hover { color: var(--rose); gap: 0.65rem; }

    /* ── PRODUCT CARDS ────────────────────────────────── */
    .product-grid-bloom {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.75rem;
    }
    .p-card-bloom {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.35s var(--ease-gentle), box-shadow 0.35s;
    }
    .p-card-bloom:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 56px rgba(74,63,56,0.1);
    }
    .p-img-wrap {
        position: relative;
        aspect-ratio: 3/4;
        overflow: hidden;
        background: var(--blush);
    }
    .p-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.55s var(--ease-gentle);
    }
    .p-card-bloom:hover .p-img-wrap img { transform: scale(1.05); }

    /* Badge */
    .p-badge-bloom {
        position: absolute;
        top: 0.875rem; left: 0.875rem;
        padding: 0.28rem 0.7rem;
        border-radius: 100px;
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        z-index: 2;
    }
    .p-badge-bloom.new { background: var(--sage); color: var(--white); }
    .p-badge-bloom.sale { background: var(--rose); color: var(--white); }
    .p-badge-bloom.bs { background: var(--stone); color: var(--linen); }

    /* Wishlist */
    .p-wish-btn {
        position: absolute;
        top: 0.875rem; right: 0.875rem;
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(6px);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: none;
        color: var(--taupe);
        opacity: 0;
        transform: scale(0.8) translateY(-4px);
        transition: opacity 0.25s, transform 0.3s var(--ease-bloom), color 0.2s;
        z-index: 3;
        cursor: pointer;
    }
    .p-card-bloom:hover .p-wish-btn { opacity: 1; transform: scale(1) translateY(0); }
    .p-wish-btn:hover { color: var(--rose); }

    /* Quick add */
    .p-quick-btn {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: rgba(74,63,56,0.88);
        backdrop-filter: blur(8px);
        color: var(--linen);
        padding: 0.8rem;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        text-align: center;
        border: none;
        transform: translateY(100%);
        transition: transform 0.3s var(--ease-gentle);
        cursor: pointer;
    }
    .p-card-bloom:hover .p-quick-btn { transform: translateY(0); }

    /* Info */
    .p-info-bloom {
        padding: 1rem 1.125rem 1.25rem;
    }
    .p-category-bloom {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--sage);
        margin-bottom: 0.3rem;
    }
    .p-name-bloom {
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 400;
        color: var(--stone);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .p-price-bloom { display: flex; align-items: center; gap: 0.5rem; }
    .p-price-cur { font-size: 0.9rem; font-weight: 600; color: var(--stone); }
    .p-price-was { font-size: 0.8rem; color: var(--taupe); text-decoration: line-through; }
    .p-swatches-bloom {
        display: flex;
        gap: 5px;
        margin-top: 0.6rem;
        align-items: center;
    }
    .p-dot-swatch {
        width: 13px; height: 13px;
        border-radius: 50%;
        border: 2px solid var(--white);
        box-shadow: 0 0 0 1px rgba(74,63,56,0.15);
        cursor: pointer;
        transition: transform 0.2s;
    }
    .p-dot-swatch:hover { transform: scale(1.3); }

    /* ── EDITORIAL FEATURE STRIP ──────────────────────── */
    .editorial-strip {
        background: var(--stone);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    .editorial-inner {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 2.5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    .editorial-text {}
    .editorial-eyebrow {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--rose-lt);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .editorial-eyebrow::before { content: ''; width: 20px; height: 1px; background: var(--rose-lt); }
    .editorial-title {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4vw, 3.5rem);
        font-weight: 400;
        color: var(--linen);
        line-height: 1.1;
        margin-bottom: 1.25rem;
    }
    .editorial-title em { font-style: italic; color: var(--rose-lt); }
    .editorial-body {
        font-size: 0.9375rem;
        color: rgba(250,246,242,0.55);
        line-height: 1.8;
        margin-bottom: 2rem;
        max-width: 420px;
        font-weight: 300;
    }
    .editorial-img-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 200px 200px;
        gap: 0.75rem;
    }
    .ed-img {
        border-radius: 12px;
        overflow: hidden;
        background: var(--blush);
    }
    .ed-img:first-child { grid-row: 1 / 3; border-radius: 16px; }
    .ed-img img { width: 100%; height: 100%; object-fit: cover; }

    /* ── COLLECTIONS ──────────────────────────────────── */
    .collections-bloom {
        background: var(--blush);
        padding: 5.5rem 0;
    }
    .col-grid-bloom {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .col-card-bloom {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        cursor: pointer;
        aspect-ratio: 3/4;
    }
    .col-card-bloom:first-child { aspect-ratio: auto; grid-row: span 1; }
    .col-bg-bloom {
        position: absolute; inset: 0;
        background-size: cover;
        background-position: center;
        transition: transform 0.6s var(--ease-gentle);
    }
    .col-card-bloom:hover .col-bg-bloom { transform: scale(1.05); }
    .col-overlay-bloom {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(74,63,56,0.65) 0%, transparent 55%);
    }
    .col-content-bloom {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 1.75rem;
        z-index: 2;
    }
    .col-tag-bloom {
        display: inline-flex;
        align-items: center;
        background: rgba(250,246,242,0.15);
        backdrop-filter: blur(8px);
        color: var(--linen);
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        padding: 0.3rem 0.75rem;
        border-radius: 100px;
        margin-bottom: 0.75rem;
    }
    .col-name-bloom {
        font-family: var(--font-display);
        font-size: 1.625rem;
        font-weight: 400;
        color: var(--white);
        margin-bottom: 0.75rem;
        line-height: 1.1;
    }
    .col-cta-bloom {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: rgba(250,246,242,0.8);
        opacity: 0;
        transform: translateY(6px);
        transition: opacity 0.3s, transform 0.3s, color 0.2s;
    }
    .col-card-bloom:hover .col-cta-bloom { opacity: 1; transform: translateY(0); }
    .col-cta-bloom:hover { color: var(--rose-lt); }

    /* ── TRUST BADGES ─────────────────────────────────── */
    .trust-bloom {
        background: var(--white);
        padding: 4rem 0;
        border-top: 1px solid var(--bone);
        border-bottom: 1px solid var(--bone);
    }
    .trust-row {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 2.5rem;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        align-items: center;
    }
    .trust-item-bloom {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        border-radius: 12px;
        transition: background 0.25s;
    }
    .trust-item-bloom:hover { background: var(--blush); }
    .trust-icon-bloom {
        width: 44px; height: 44px;
        background: var(--blush-deep);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--rose);
        flex-shrink: 0;
    }
    .trust-text-bloom h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--stone);
        margin-bottom: 0.15rem;
    }
    .trust-text-bloom p { font-size: 0.75rem; color: var(--taupe); line-height: 1.5; }

    /* ── NEWSLETTER ───────────────────────────────────── */
    .nl-bloom {
        background: var(--blush-deep);
        padding: 6rem 0;
        position: relative;
        overflow: hidden;
    }
    .nl-bloom::before {
        content: '';
        position: absolute;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(196,120,90,0.12) 0%, transparent 70%);
        top: -200px; right: -100px;
        pointer-events: none;
    }
    .nl-bloom::after {
        content: '';
        position: absolute;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(122,158,126,0.12) 0%, transparent 70%);
        bottom: -100px; left: -80px;
        pointer-events: none;
    }
    .nl-bloom-inner {
        max-width: 680px;
        margin: 0 auto;
        padding: 0 2rem;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    .nl-bloom-eyebrow {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--sage);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    .nl-bloom-eyebrow::before, .nl-bloom-eyebrow::after {
        content: ''; width: 24px; height: 1px; background: var(--sage-lt);
    }
    .nl-bloom-title {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 400;
        color: var(--stone);
        margin-bottom: 1rem;
        line-height: 1.1;
    }
    .nl-bloom-title em { font-style: italic; color: var(--rose); }
    .nl-bloom-sub {
        font-size: 0.9375rem;
        color: var(--taupe);
        line-height: 1.7;
        margin-bottom: 2.5rem;
        font-weight: 300;
    }
    .nl-bloom-form {
        display: flex;
        background: var(--white);
        border: 1.5px solid var(--bone);
        border-radius: 100px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(74,63,56,0.07);
        transition: border-color 0.25s, box-shadow 0.25s;
        max-width: 480px;
        margin: 0 auto;
    }
    .nl-bloom-form:focus-within {
        border-color: var(--rose);
        box-shadow: 0 8px 32px rgba(196,120,90,0.15);
    }
    .nl-bloom-form input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        padding: 0.9rem 1.25rem;
        font-family: var(--font-body);
        font-size: 0.875rem;
        color: var(--stone);
    }
    .nl-bloom-form input::placeholder { color: var(--taupe); }
    .nl-bloom-form button {
        background: var(--rose);
        border: none;
        color: var(--white);
        padding: 0.9rem 1.75rem;
        font-family: var(--font-body);
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.25s;
        border-radius: 0 100px 100px 0;
        white-space: nowrap;
    }
    .nl-bloom-form button:hover { background: var(--rose-lt); }
    .nl-bloom-note {
        font-size: 0.7rem;
        color: var(--taupe);
        margin-top: 0.875rem;
        opacity: 0.7;
    }

    /* ── REVEAL STATES ────────────────────────────────── */
    .bloom-up { opacity: 0; transform: translateY(30px); }
    .bloom-scale { opacity: 0; transform: scale(0.95); }

    /* ── RESPONSIVE ───────────────────────────────────── */
    @media (max-width: 1100px) {
        .hero { grid-template-columns: 1fr; min-height: auto; }
        .hero-image-col { height: 60vw; max-height: 500px; }
        .hero-text-col { padding: 4rem 2.5rem 3rem; }
        .product-grid-bloom { grid-template-columns: repeat(3, 1fr); }
        .editorial-inner { grid-template-columns: 1fr; gap: 2.5rem; }
        .editorial-img-grid { order: -1; }
        .trust-row { grid-template-columns: repeat(2, 1fr); }
        .col-grid-bloom { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
        .sec-inner { padding: 0 1rem; }
        .product-grid-bloom { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .col-grid-bloom { grid-template-columns: 1fr; }
        .hero-text-col { padding: 3rem 1.5rem 2.5rem; }
        .hero-mini-stats { gap: 1.5rem; }
        .sec-head { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
    }
    @media (max-width: 480px) {
        .hero-title { font-size: 2.5rem; }
        .product-grid-bloom { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .trust-row { grid-template-columns: 1fr; }
        .cat-row-inner { padding: 0 1rem; }
        .editorial-img-grid { grid-template-rows: 160px 160px; }
    }
</style>

{{-- ═══════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════ --}}
<section class="hero" id="hero">
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>
    <div class="hero-blob hero-blob-3"></div>

    <div class="hero-text-col">
        <p class="hero-pre bloom-up"><span class="pre-dot"></span> New Season · SS 2025</p>

        <h1 class="hero-title bloom-up">
            Style that<br>
            <em>blossoms</em><br>
            with you.
        </h1>

        <p class="hero-sub bloom-up">
            Delicate craftsmanship and refined silhouettes for those who find beauty in every season of life.
        </p>

        <div class="hero-actions bloom-up">
            <a href="{{ route('products.index') }}" class="btn-petal">
                Explore Collection
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="#" class="btn-ghost-rose">View Lookbook</a>
        </div>

        <div class="hero-mini-stats bloom-up">
            <div class="mini-stat">
                <div class="mini-stat-n">2K+</div>
                <div class="mini-stat-l">Happy Clients</div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-n">500+</div>
                <div class="mini-stat-l">Styles</div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-n">4.9★</div>
                <div class="mini-stat-l">Avg. Rating</div>
            </div>
        </div>
    </div>

    <div class="hero-image-col">
        <img class="hero-img-main" src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200&q=80" alt="New season fashion" id="heroMainImg">

        <div class="hero-img-card hero-img-card-1 bloom-up">
            <p class="card-label">Latest Drop</p>
            <p class="card-value">Summer Edit</p>
            <p class="card-sub">↑ 42 new pieces</p>
        </div>
        <div class="hero-img-card hero-img-card-2 bloom-up">
            <p class="card-label">Free Shipping</p>
            <p class="card-value" style="font-size:1rem;">Over Rs. 5,000</p>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CATEGORY ROW
═══════════════════════════════════════════════════════ --}}
<div class="categories-row">
    <div class="cat-row-inner">
        <a class="cat-chip active" href="#">
            <span class="chip-dot"></span> All
        </a>
        <a class="cat-chip" href="#">Women</a>
        <a class="cat-chip" href="#">Men</a>
        <a class="cat-chip" href="#">Dresses</a>
        <a class="cat-chip" href="#">Tops</a>
        <a class="cat-chip" href="#">Bottoms</a>
        <a class="cat-chip" href="#">Outerwear</a>
        <a class="cat-chip" href="#">Accessories</a>
        <a class="cat-chip" href="#">Footwear</a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     NEW ARRIVALS
═══════════════════════════════════════════════════════ --}}
<section class="page-sec" style="background: var(--linen);">
    <div class="sec-inner">
        <div class="sec-head bloom-up">
            <div>
                <p class="sec-eyebrow">Just Landed</p>
                <h2 class="sec-title">New <em>Arrivals</em></h2>
            </div>
            <a href="{{ route('products.index') }}" class="sec-all-link">
                View all
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="product-grid-bloom">
            @php
            $newArrivals = [
                ['name'=>'Linen Wrap Midi Dress','cat'=>'Women','price'=>'Rs. 5,800','orig'=>null,'badge'=>'New','badge_cls'=>'new','colors'=>['#d4b896','#c4602a','#1a1612'],'img'=>'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=600&q=80'],
                ['name'=>'Tailored Linen Blazer','cat'=>'Men','price'=>'Rs. 9,200','orig'=>'Rs. 11,500','badge'=>'Sale','badge_cls'=>'sale','colors'=>['#4a3f38','#8c7b72'],'img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80'],
                ['name'=>'Satin Slip Skirt','cat'=>'Women','price'=>'Rs. 4,400','orig'=>null,'badge'=>'New','badge_cls'=>'new','colors'=>['#faf6f2','#e8ddd8','#c4785a'],'img'=>'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=600&q=80'],
                ['name'=>'Organic Cotton Tee','cat'=>'Unisex','price'=>'Rs. 2,100','orig'=>null,'badge'=>null,'badge_cls'=>'','colors'=>['#ffffff','#4a3f38','#7a9e7e'],'img'=>'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80'],
            ];
            @endphp

            @foreach($newArrivals as $p)
            <div class="p-card-bloom bloom-up">
                <div class="p-img-wrap">
                    @if($p['badge'])<span class="p-badge-bloom {{ $p['badge_cls'] }}">{{ $p['badge'] }}</span>@endif
                    <button class="p-wish-btn" aria-label="Add to wishlist">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>
                    <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}" loading="lazy">
                    <button class="p-quick-btn">+ Add to Bag</button>
                </div>
                <div class="p-info-bloom">
                    <p class="p-category-bloom">{{ $p['cat'] }}</p>
                    <h3 class="p-name-bloom">{{ $p['name'] }}</h3>
                    <div class="p-price-bloom">
                        <span class="p-price-cur">{{ $p['price'] }}</span>
                        @if($p['orig'])<span class="p-price-was">{{ $p['orig'] }}</span>@endif
                    </div>
                    <div class="p-swatches-bloom">
                        @foreach($p['colors'] as $c)<span class="p-dot-swatch" style="background:{{ $c }};"></span>@endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     EDITORIAL FEATURE
═══════════════════════════════════════════════════════ --}}
<section class="editorial-strip">
    <div class="editorial-inner">
        <div class="editorial-text bloom-up">
            <p class="editorial-eyebrow">Our Story</p>
            <h2 class="editorial-title">
                Fashion with a<br>
                <em>conscious soul.</em>
            </h2>
            <p class="editorial-body">
                Every piece in our collection is thoughtfully designed and ethically crafted. We believe that beautiful clothing should feel as good as it looks — for you and the planet.
            </p>
            <a href="#" class="btn-petal">
                Discover Our Values
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="editorial-img-grid bloom-scale">
            <div class="ed-img">
                <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=600&q=80" alt="Editorial 1" loading="lazy">
            </div>
            <div class="ed-img">
                <img src="https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=400&q=80" alt="Editorial 2" loading="lazy">
            </div>
            <div class="ed-img">
                <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=400&q=80" alt="Editorial 3" loading="lazy">
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     COLLECTIONS
═══════════════════════════════════════════════════════ --}}
<section class="collections-bloom">
    <div class="sec-inner">
        <div class="sec-head bloom-up">
            <div>
                <p class="sec-eyebrow">Curated For You</p>
                <h2 class="sec-title">Featured <em>Collections</em></h2>
            </div>
            <a href="#" class="sec-all-link">Browse all <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>

        <div class="col-grid-bloom">
            @php
            $collections = [
                ['name'=>'The Summer Bloom','slug'=>'summer-bloom','tag'=>'42 pieces','img'=>'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&q=80'],
                ['name'=>'Work & Poise','slug'=>'work-poise','tag'=>'28 pieces','img'=>'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600&q=80'],
                ['name'=>'Evening Garden','slug'=>'evening-garden','tag'=>'19 pieces','img'=>'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=600&q=80'],
            ];
            @endphp
            @foreach($collections as $c)
            <div class="col-card-bloom bloom-up">
                <div class="col-bg-bloom" style="background-image: url('{{ $c['img'] }}');"></div>
                <div class="col-overlay-bloom"></div>
                <div class="col-content-bloom">
                    <span class="col-tag-bloom">{{ $c['tag'] }}</span>
                    <h3 class="col-name-bloom">{{ $c['name'] }}</h3>
                    <a href="{{ route('collections.show', $c['slug']) }}" class="col-cta-bloom">
                        Shop Collection →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     BEST SELLERS
═══════════════════════════════════════════════════════ --}}
<section class="page-sec" style="background: var(--white);">
    <div class="sec-inner">
        <div class="sec-head bloom-up">
            <div>
                <p class="sec-eyebrow">Customer Favourites</p>
                <h2 class="sec-title"><em>Best</em> Sellers</h2>
            </div>
            <a href="{{ route('products.index') }}" class="sec-all-link">View all <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>

        <div class="product-grid-bloom">
            @php
            $bestSellers = [
                ['name'=>'Wide Leg Linen Trousers','cat'=>'Women','price'=>'Rs. 6,400','orig'=>null,'badge'=>'Best Seller','badge_cls'=>'bs','colors'=>['#4a3f38','#8c7b72','#faf6f2'],'img'=>'https://images.unsplash.com/photo-1594938298603-c8148c4b4e3d?w=600&q=80'],
                ['name'=>'Classic Poplin Shirt','cat'=>'Men','price'=>'Rs. 3,800','orig'=>null,'badge'=>'Best Seller','badge_cls'=>'bs','colors'=>['#ffffff','#c4d6e0','#4a3f38'],'img'=>'https://images.unsplash.com/photo-1581824043583-6904b080a19c?w=600&q=80'],
                ['name'=>'Knit Open Cardigan','cat'=>'Women','price'=>'Rs. 7,200','orig'=>'Rs. 8,800','badge'=>'Sale','badge_cls'=>'sale','colors'=>['#e8874f','#faf6f2','#8c7b72'],'img'=>'https://images.unsplash.com/photo-1594938298603-c8148c4b4e3d?w=600&q=80'],
                ['name'=>'Structured Leather Tote','cat'=>'Accessories','price'=>'Rs. 14,500','orig'=>null,'badge'=>null,'badge_cls'=>'','colors'=>['#c4785a','#4a3f38'],'img'=>'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80'],
            ];
            @endphp
            @foreach($bestSellers as $p)
            <div class="p-card-bloom bloom-up">
                <div class="p-img-wrap">
                    @if($p['badge'])<span class="p-badge-bloom {{ $p['badge_cls'] }}">{{ $p['badge'] }}</span>@endif
                    <button class="p-wish-btn" aria-label="Add to wishlist">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>
                    <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}" loading="lazy">
                    <button class="p-quick-btn">+ Add to Bag</button>
                </div>
                <div class="p-info-bloom">
                    <p class="p-category-bloom">{{ $p['cat'] }}</p>
                    <h3 class="p-name-bloom">{{ $p['name'] }}</h3>
                    <div class="p-price-bloom">
                        <span class="p-price-cur">{{ $p['price'] }}</span>
                        @if($p['orig'])<span class="p-price-was">{{ $p['orig'] }}</span>@endif
                    </div>
                    <div class="p-swatches-bloom">
                        @foreach($p['colors'] as $c)<span class="p-dot-swatch" style="background:{{ $c }};"></span>@endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     TRUST BADGES
═══════════════════════════════════════════════════════ --}}
<div class="trust-bloom">
    <div class="trust-row">
        @php $trusts = [
            ['svg'=>'<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>','h'=>'Free Shipping','p'=>'On all orders over Rs. 5,000'],
            ['svg'=>'<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>','h'=>'Easy Returns','p'=>'14-day hassle-free policy'],
            ['svg'=>'<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>','h'=>'Secure Payment','p'=>'256-bit SSL encryption'],
            ['svg'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.41 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.52 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.16 6.16l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>','h'=>'24/7 Support','p'=>'Always here to help you'],
        ]; @endphp
        @foreach($trusts as $t)
        <div class="trust-item-bloom bloom-up">
            <div class="trust-icon-bloom">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $t['svg'] !!}</svg>
            </div>
            <div class="trust-text-bloom">
                <h4>{{ $t['h'] }}</h4>
                <p>{{ $t['p'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     NEWSLETTER
═══════════════════════════════════════════════════════ --}}
<section class="nl-bloom">
    <div class="nl-bloom-inner bloom-up">
        <p class="nl-bloom-eyebrow">Newsletter</p>
        <h2 class="nl-bloom-title">
            Join our<br><em>blooming community</em>
        </h2>
        <p class="nl-bloom-sub">
            Be the first to know about new arrivals, exclusive offers, and style inspiration delivered gently to your inbox.
        </p>
        <form action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <div class="nl-bloom-form">
                <input type="email" name="email" placeholder="your@email.com" required>
                <button type="submit">Subscribe</button>
            </div>
        </form>
        <p class="nl-bloom-note">No spam, ever. Unsubscribe anytime.</p>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     GSAP ANIMATIONS
═══════════════════════════════════════════════════════ --}}
<script>
window.addEventListener('load', () => {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    /* ── Hero entrance ──────────────────────────── */
    const heroTl = gsap.timeline({ delay: 0.75 });
    heroTl
        .fromTo('.hero-pre',          { opacity:0, y:20 }, { opacity:1, y:0, duration:0.6, ease:'power3.out' })
        .fromTo('.hero-title',        { opacity:0, y:40 }, { opacity:1, y:0, duration:0.85, ease:'power3.out' }, '-=0.3')
        .fromTo('.hero-sub',          { opacity:0, y:20 }, { opacity:1, y:0, duration:0.6,  ease:'power3.out' }, '-=0.4')
        .fromTo('.hero-actions',      { opacity:0, y:20 }, { opacity:1, y:0, duration:0.6,  ease:'power3.out' }, '-=0.4')
        .fromTo('.hero-mini-stats',   { opacity:0, y:16 }, { opacity:1, y:0, duration:0.55, ease:'power3.out' }, '-=0.35')
        .fromTo('.hero-img-card',     { opacity:0, y:24, scale:0.95 }, { opacity:1, y:0, scale:1, duration:0.7, stagger:0.15, ease:'back.out(1.4)' }, '-=0.5');

    /* Hero image gentle float */
    gsap.to('#heroMainImg', {
        y: -15,
        duration: 4,
        ease: 'sine.inOut',
        repeat: -1,
        yoyo: true
    });

    /* Floating blobs */
    document.querySelectorAll('.hero-blob').forEach((blob, i) => {
        gsap.to(blob, {
            x: (i % 2 === 0 ? 20 : -20),
            y: (i % 2 === 0 ? -15 : 20),
            duration: 5 + i * 1.5,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true
        });
    });

    /* ── ScrollTrigger bloom-up ─────────────────── */
    document.querySelectorAll('.bloom-up').forEach((el, i) => {
        gsap.to(el, {
            opacity: 1, y: 0,
            duration: 0.8,
            ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 87%', toggleActions: 'play none none none' },
            delay: (i % 4) * 0.07
        });
    });

    /* bloom-scale */
    document.querySelectorAll('.bloom-scale').forEach(el => {
        gsap.to(el, {
            opacity: 1, scale: 1,
            duration: 0.9,
            ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' }
        });
    });

    /* ── Product cards stagger ──────────────────── */
    document.querySelectorAll('.product-grid-bloom').forEach(grid => {
        const cards = grid.querySelectorAll('.p-card-bloom');
        cards.forEach(c => { c.style.opacity = '0'; c.style.transform = 'translateY(32px)'; });
        ScrollTrigger.create({
            trigger: grid, start: 'top 83%', once: true,
            onEnter: () => gsap.to(cards, { opacity:1, y:0, duration:0.75, ease:'power3.out', stagger:0.1 })
        });
    });

    /* ── Collection cards cascade ───────────────── */
    document.querySelectorAll('.col-card-bloom').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(28px) scale(0.98)';
        ScrollTrigger.create({
            trigger: card, start: 'top 88%', once: true,
            onEnter: () => gsap.to(card, { opacity:1, y:0, scale:1, duration:0.8, ease:'power3.out', delay: i * 0.12 })
        });
    });

    /* ── Trust items stagger ────────────────────── */
    ScrollTrigger.create({
        trigger: '.trust-row', start: 'top 85%', once: true,
        onEnter: () => gsap.fromTo('.trust-item-bloom',
            { opacity:0, x:-16 },
            { opacity:1, x:0, duration:0.6, ease:'power3.out', stagger:0.1 }
        )
    });

    /* ── Category chips pop-in ──────────────────── */
    gsap.from('.cat-chip', {
        opacity: 0, y: 12, scale: 0.9,
        duration: 0.45,
        ease: 'back.out(1.7)',
        stagger: 0.05,
        scrollTrigger: { trigger: '.categories-row', start: 'top 92%' }
    });

    /* ── Active chip click ──────────────────────── */
    document.querySelectorAll('.cat-chip').forEach(chip => {
        chip.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            gsap.from(this, { scale:0.88, duration:0.4, ease:'back.out(2)' });
        });
    });

    /* ── Petal buttons hover ripple ─────────────── */
    document.querySelectorAll('.btn-petal').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            gsap.fromTo(btn, { boxShadow:'0 8px 24px rgba(196,120,90,0.28)' },
                { boxShadow:'0 16px 40px rgba(196,120,90,0.42)', duration:0.3, ease:'power2.out' });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { boxShadow:'0 8px 24px rgba(196,120,90,0.28)', duration:0.4 });
        });
    });
});
</script>

@endsection