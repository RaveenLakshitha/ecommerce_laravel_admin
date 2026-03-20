@extends('frontend.layouts.app')@section('title', $store_name ?? 'Welcome to Karbnzol')@section('content'){{-- ─── LOCAL STYLES ──────────────────────────────────────────────────── --}}
    <style>
        /* Reuse layout's CSS vars */
        :root {
            --cream: #f5f0e8;
            --sand: #e8dfc8;
            --ink: #1a1612;
            --mink: #6b5e52;
            --rust: #c4602a;
            --rust-lt: #e8874f;
            --white: #ffffff;
            --font-display: 'Cormorant Garamond', Georgia, serif;
            --font-body: 'DM Sans', sans-serif;
            --ease-silk: cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* ── SECTION SHARED ─────────────────────────── */
        .home-section {
            padding: 6rem 0;
        }

        .section-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 3rem;
            gap: 1rem;
        }

        .section-title {
            font-family: var(--font-display);
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 300;
            letter-spacing: 0.04em;
            color: var(--ink);
            line-height: 1.05;
        }

        .section-title em {
            font-style: italic;
            color: var(--rust);
        }

        .section-link {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--mink);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.25s, gap 0.25s;
        }

        .section-link:hover {
            color: var(--rust);
            gap: 0.75rem;
        }

        /* ── HERO ───────────────────────────────────── */
        .hero {
            position: relative;
            height: 96vh;
            min-height: 560px;
            overflow: hidden;
        }

        .hero-track {
            display: flex;
            height: 100%;
            will-change: transform;
        }

        .hero-slide {
            min-width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-slide-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transform: scale(1.06);
            transition: transform 8s var(--ease-silk);
        }

        .hero-slide.active .hero-slide-bg {
            transform: scale(1);
        }

        .hero-slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg,
                    rgba(26, 22, 18, 0.55) 0%,
                    rgba(26, 22, 18, 0.18) 55%,
                    transparent 100%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 0 6vw;
            max-width: 760px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(245, 240, 232, 0.65);
            margin-bottom: 1.25rem;
        }

        .hero-eyebrow::before {
            content: '';
            width: 28px;
            height: 1px;
            background: var(--rust-lt);
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: clamp(3rem, 7.5vw, 6.5rem);
            font-weight: 300;
            line-height: 0.95;
            letter-spacing: -0.01em;
            color: var(--cream);
            margin-bottom: 1.5rem;
        }

        .hero-title em {
            font-style: italic;
            color: var(--rust-lt);
        }

        .hero-sub {
            font-size: 1.0625rem;
            color: rgba(245, 240, 232, 0.72);
            margin-bottom: 2.5rem;
            line-height: 1.6;
            max-width: 460px;
        }

        .hero-ctas {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--rust);
            color: var(--white);
            padding: 0.875rem 2rem;
            border-radius: 100px;
            font-size: 0.8125rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: background 0.25s, transform 0.2s;
        }

        .btn-hero-primary:hover {
            background: var(--rust-lt);
            transform: translateY(-2px);
        }

        .btn-hero-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: transparent;
            color: var(--cream);
            padding: 0.875rem 2rem;
            border-radius: 100px;
            border: 1px solid rgba(245, 240, 232, 0.35);
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: border-color 0.25s, background 0.25s, transform 0.2s;
        }

        .btn-hero-ghost:hover {
            border-color: rgba(245, 240, 232, 0.7);
            background: rgba(245, 240, 232, 0.08);
            transform: translateY(-2px);
        }

        /* Hero dots */
        .hero-dots {
            position: absolute;
            bottom: 2.25rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }

        .hero-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(245, 240, 232, 0.35);
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
            border: none;
        }

        .hero-dot.active {
            background: var(--rust-lt);
            transform: scale(1.4);
        }

        /* Hero scroll hint */
        .hero-scroll {
            position: absolute;
            bottom: 2rem;
            right: 3rem;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: rgba(245, 240, 232, 0.5);
            font-size: 0.65rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .scroll-line {
            width: 1px;
            height: 48px;
            background: rgba(245, 240, 232, 0.3);
            position: relative;
            overflow: hidden;
        }

        .scroll-line::after {
            content: '';
            position: absolute;
            top: -100%;
            width: 100%;
            height: 100%;
            background: var(--rust-lt);
            animation: scrollDown 2s ease-in-out infinite;
        }

        @keyframes scrollDown {
            to {
                top: 200%;
            }
        }

        /* ── CATEGORY STRIPS ─────────────────────────── */
        .categories-strip {
            background: var(--ink);
            padding: 2.5rem 0;
        }

        .cat-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .cat-inner::-webkit-scrollbar {
            display: none;
        }

        .cat-pill {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            border-radius: 100px;
            border: 1px solid rgba(245, 240, 232, 0.15);
            color: rgba(245, 240, 232, 0.6);
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: border-color 0.25s, color 0.25s, background 0.25s;
            cursor: pointer;
            white-space: nowrap;
        }

        .cat-pill:hover,
        .cat-pill.active {
            border-color: var(--rust-lt);
            color: var(--cream);
            background: rgba(232, 135, 79, 0.1);
        }

        .cat-pill.active {
            background: var(--rust);
            border-color: var(--rust);
        }

        /* ── PRODUCT CARDS ───────────────────────────── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .product-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            transition: transform 0.35s var(--ease-silk), box-shadow 0.35s;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(26, 22, 18, 0.12);
        }

        .product-img-wrap {
            position: relative;
            aspect-ratio: 3/4;
            overflow: hidden;
            background: var(--sand);
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.55s var(--ease-silk);
        }

        .product-card:hover .product-img-wrap img {
            transform: scale(1.06);
        }

        .product-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: var(--rust);
            color: var(--white);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.25rem 0.65rem;
            border-radius: 100px;
            z-index: 2;
        }

        .product-badge.sold {
            background: var(--ink);
        }

        .product-badge.bestseller {
            background: #3a7d44;
        }

        .product-wishlist {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 34px;
            height: 34px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.85);
            transition: opacity 0.25s, transform 0.25s;
            z-index: 2;
            cursor: pointer;
            border: none;
        }

        .product-card:hover .product-wishlist {
            opacity: 1;
            transform: scale(1);
        }

        .product-wishlist:hover svg {
            stroke: var(--rust);
        }

        .product-quick-add {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(26, 22, 18, 0.88);
            backdrop-filter: blur(6px);
            color: var(--cream);
            text-align: center;
            padding: 0.75rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            transform: translateY(100%);
            transition: transform 0.3s var(--ease-silk);
            cursor: pointer;
            border: none;
            width: 100%;
        }

        .product-card:hover .product-quick-add {
            transform: translateY(0);
        }

        .product-info {
            padding: 1rem 1.125rem 1.25rem;
        }

        .product-category {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--mink);
            margin-bottom: 0.35rem;
        }

        .product-name {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 400;
            color: var(--ink);
            margin-bottom: 0.5rem;
            line-height: 1.25;
        }

        .product-price-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .price-current {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--ink);
        }

        .price-original {
            font-size: 0.8125rem;
            color: var(--mink);
            text-decoration: line-through;
        }

        .product-colors {
            display: flex;
            gap: 5px;
            margin-top: 0.6rem;
        }

        .color-swatch {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--white);
            box-shadow: 0 0 0 1px rgba(107, 94, 82, 0.25);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .color-swatch:hover {
            transform: scale(1.3);
        }

        /* ── COLLECTIONS GRID ─────────────────────────── */
        .collections-section {
            background: var(--ink);
            padding: 6rem 0;
        }

        .collection-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            grid-template-rows: repeat(2, 240px);
            gap: 1rem;
        }

        .collection-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
        }

        .collection-card:first-child {
            grid-row: 1 / 3;
        }

        .collection-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s var(--ease-silk);
        }

        .collection-card:hover .collection-bg {
            transform: scale(1.06);
        }

        .collection-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(26, 22, 18, 0.72) 0%, transparent 55%);
            transition: background 0.35s;
        }

        .collection-card:hover .collection-overlay {
            background: linear-gradient(to top, rgba(26, 22, 18, 0.85) 0%, transparent 50%);
        }

        .collection-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            z-index: 2;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .collection-name {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 300;
            color: var(--cream);
            letter-spacing: 0.03em;
            line-height: 1.1;
        }

        .collection-count {
            font-size: 0.65rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(245, 240, 232, 0.55);
            margin-top: 0.25rem;
        }

        .collection-arrow {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid rgba(245, 240, 232, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cream);
            opacity: 0;
            transform: translateX(-8px);
            transition: opacity 0.3s, transform 0.3s, border-color 0.3s;
            flex-shrink: 0;
        }

        .collection-card:hover .collection-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .collection-card:hover .collection-arrow {
            border-color: var(--rust-lt);
        }

        /* ── MARQUEE / TICKER ────────────────────────── */
        .marquee-section {
            overflow: hidden;
            background: var(--rust);
            padding: 1.1rem 0;
        }

        .marquee-track {
            display: flex;
            gap: 0;
            animation: marquee-slide 28s linear infinite;
            white-space: nowrap;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        .marquee-item {
            display: inline-flex;
            align-items: center;
            gap: 1.25rem;
            padding: 0 2rem;
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-style: italic;
            font-weight: 300;
            color: rgba(245, 240, 232, 0.9);
            letter-spacing: 0.04em;
        }

        .marquee-dot {
            width: 5px;
            height: 5px;
            background: rgba(245, 240, 232, 0.5);
            border-radius: 50%;
            flex-shrink: 0;
        }

        @keyframes marquee-slide {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        /* ── TRUST BADGES ─────────────────────────────── */
        .trust-section {
            background: var(--cream);
            padding: 5rem 0;
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .trust-badge {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
            padding: 2rem 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--sand);
            background: var(--white);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .trust-badge:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(26, 22, 18, 0.08);
        }

        .trust-icon {
            width: 48px;
            height: 48px;
            background: rgba(196, 96, 42, 0.08);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--rust);
        }

        .trust-title {
            font-family: var(--font-display);
            font-size: 1.125rem;
            font-weight: 400;
            color: var(--ink);
        }

        .trust-sub {
            font-size: 0.8125rem;
            color: var(--mink);
            line-height: 1.6;
        }

        /* ── NEWSLETTER ───────────────────────────────── */
        .newsletter-banner {
            background: var(--ink);
            border-radius: 16px;
            padding: 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
            position: relative;
            overflow: hidden;
        }

        .newsletter-banner::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(196, 96, 42, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .newsletter-text {
            flex: 1;
        }

        .newsletter-eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--rust-lt);
            margin-bottom: 0.75rem;
        }

        .newsletter-title {
            font-family: var(--font-display);
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 300;
            color: var(--cream);
            line-height: 1.1;
            margin-bottom: 0.75rem;
        }

        .newsletter-title em {
            font-style: italic;
            color: var(--rust-lt);
        }

        .newsletter-desc {
            font-size: 0.875rem;
            color: rgba(245, 240, 232, 0.5);
            line-height: 1.6;
        }

        .newsletter-form-wrap {
            flex-shrink: 0;
            width: min(380px, 100%);
        }

        .nl-form {
            display: flex;
            background: rgba(245, 240, 232, 0.07);
            border: 1px solid rgba(245, 240, 232, 0.15);
            border-radius: 100px;
            overflow: hidden;
            margin-bottom: 0.75rem;
            transition: border-color 0.25s;
        }

        .nl-form:focus-within {
            border-color: var(--rust-lt);
        }

        .nl-form input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 0.875rem 1.25rem;
            font-family: var(--font-body);
            font-size: 0.875rem;
            color: var(--cream);
        }

        .nl-form input::placeholder {
            color: rgba(245, 240, 232, 0.3);
        }

        .nl-form button {
            background: var(--rust);
            border: none;
            color: var(--white);
            padding: 0.875rem 1.5rem;
            font-family: var(--font-body);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.25s;
            border-radius: 0 100px 100px 0;
        }

        .nl-form button:hover {
            background: var(--rust-lt);
        }

        .nl-privacy {
            font-size: 0.7rem;
            color: rgba(245, 240, 232, 0.3);
            letter-spacing: 0.04em;
            text-align: center;
        }

        /* ── GSAP INITIAL STATES ──────────────────────── */
        .fade-up {
            opacity: 0;
            transform: translateY(32px);
        }

        .fade-left {
            opacity: 0;
            transform: translateX(-32px);
        }

        /* ── RESPONSIVE ───────────────────────────────── */
        @media (max-width: 1100px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .collection-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 280px 200px 200px;
            }

            .collection-card:first-child {
                grid-column: 1 / 3;
                grid-row: 1;
            }

            .trust-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .home-section {
                padding: 4rem 0;
            }

            .section-inner {
                padding: 0 1rem;
            }

            .hero {
                height: 88vh;
            }

            .hero-content {
                padding: 0 1.5rem;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .collection-grid {
                grid-template-columns: 1fr;
                grid-template-rows: 320px 200px 200px 200px;
            }

            .collection-card:first-child {
                grid-column: 1;
                grid-row: 1;
            }

            .newsletter-banner {
                flex-direction: column;
                padding: 2rem;
                gap: 2rem;
            }

            .newsletter-form-wrap {
                width: 100%;
            }

            .trust-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .hero-title {
                font-size: 2.75rem;
            }

            .trust-grid {
                grid-template-columns: 1fr;
            }

            .hero-scroll {
                display: none;
            }
        }
    </style>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- HERO SLIDER --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <section class="hero" id="hero">
        <div class="hero-track" id="heroTrack">

            {{-- Slide 1 --}}
            <div class="hero-slide active">
                {{-- Replace with real image asset --}}
                <div class="hero-slide-bg"
                    style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1800&q=80');">
                </div>
                <div class="hero-content">
                    <p class="hero-eyebrow">New Season</p>
                    <h1 class="hero-title">
                        Dressed for<br><em>every chapter</em>
                    </h1>
                    <p class="hero-sub">Timeless silhouettes crafted for modern living — from sunrise to midnight.</p>
                    <div class="hero-ctas">
                        <a href="{{ route('frontend.products.index') }}" class="btn-hero-primary">
                            Shop Now
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="#" class="btn-hero-ghost">View Lookbook</a>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="hero-slide">
                <div class="hero-slide-bg"
                    style="background-image: url('https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1800&q=80');">
                </div>
                <div class="hero-content">
                    <p class="hero-eyebrow">Collections</p>
                    <h1 class="hero-title">
                        Summer<br><em>Essentials</em>
                    </h1>
                    <p class="hero-sub">Breathable fabrics and effortless cuts made for the warmest days.</p>
                    <div class="hero-ctas">
                        <a href="{{ route('frontend.products.index') }}" class="btn-hero-primary">
                            Explore
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="#" class="btn-hero-ghost">New Arrivals</a>
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="hero-slide">
                <div class="hero-slide-bg"
                    style="background-image: url('https://images.unsplash.com/photo-1445205170230-053b83016050?w=1800&q=80');">
                </div>
                <div class="hero-content">
                    <p class="hero-eyebrow">Limited Edition</p>
                    <h1 class="hero-title">
                        The Art<br><em>of Detail</em>
                    </h1>
                    <p class="hero-sub">Precision stitching and hand-finished touches on our signature pieces.</p>
                    <div class="hero-ctas">
                        <a href="{{ route('frontend.products.index') }}" class="btn-hero-primary">
                            Shop Limited
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dots --}}
        <div class="hero-dots">
            <button class="hero-dot active" data-slide="0"></button>
            <button class="hero-dot" data-slide="1"></button>
            <button class="hero-dot" data-slide="2"></button>
        </div>

        {{-- Scroll hint --}}
        <div class="hero-scroll" aria-hidden="true">
            <div class="scroll-line"></div>
            <span>Scroll</span>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- CATEGORIES STRIP --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="categories-strip">
        <div class="cat-inner">
            <a href="#" class="cat-pill active">All</a>
            <a href="#" class="cat-pill">Women</a>
            <a href="#" class="cat-pill">Men</a>
            <a href="#" class="cat-pill">Accessories</a>
            <a href="#" class="cat-pill">Footwear</a>
            <a href="#" class="cat-pill">Bags</a>
            <a href="#" class="cat-pill">Activewear</a>
            <a href="#" class="cat-pill">Occasion</a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- NEW ARRIVALS --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <section class="home-section" id="new-arrivals">
        <div class="section-inner">
            <div class="section-header fade-up">
                <h2 class="section-title">New <em>Arrivals</em></h2>
                <a href="{{ route('frontend.products.index') }}" class="section-link">
                    View all
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="product-grid">
                {{-- Blade: @foreach($newArrivals as $product) --}}
                {{-- DUMMY DATA — replace with Blade foreach --}}

                @foreach($newArrivals as $product)
                    <div class="product-card fade-up">
                        <div class="product-img-wrap">
                            <img src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->file_path) : asset('images/placeholder.jpg') }}"
                                alt="{{ $product->name }}" loading="lazy">
                            @if($product->created_at >= now()->subDays(14))
                                <span class="product-badge">New</span>
                            @endif
                            <button class="product-wishlist" aria-label="Add to wishlist">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </button>
                            <button class="product-quick-add">+ Quick Add</button>
                        </div>
                        <div class="product-info">
                            <p class="product-category">{{ $product->categories->first()->name ?? 'Uncategorized' }}</p>
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price-row">
                                <span class="price-current">Rs. {{ number_format($product->base_price, 2) }}</span>
                            </div>
                            <div class="product-colors">
                                {{-- Colors logic can be implemented here based on variants --}}
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- Blade: @endforeach --}}
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- MARQUEE TICKER --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="marquee-section" aria-hidden="true">
        <div class="marquee-track">
            @php $items = ['New Season', 'Free Shipping', 'Handcrafted', 'Sustainably Made', 'Sri Lanka\'s Finest', 'New Season', 'Free Shipping', 'Handcrafted', 'Sustainably Made', 'Sri Lanka\'s Finest', 'New Season', 'Free Shipping', 'Handcrafted', 'Sustainably Made', 'Sri Lanka\'s Finest', 'New Season', 'Free Shipping', 'Handcrafted', 'Sustainably Made', 'Sri Lanka\'s Finest']; @endphp
            @foreach($items as $item)
                <span class="marquee-item">{{ $item }} <span class="marquee-dot"></span></span>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- FEATURED COLLECTIONS --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <section class="collections-section">
        <div class="section-inner">
            <div class="section-header fade-up" style="margin-bottom: 2.5rem;">
                <h2 class="section-title" style="color: var(--cream);">Featured <em>Collections</em></h2>
                <a href="#" class="section-link" style="color: rgba(245,240,232,0.5);">Browse all →</a>
            </div>

            <div class="collection-grid">
                {{-- Blade: @foreach($featuredCollections as $collection) --}}
                @php
                    $dummyCollections = [
                        ['name' => 'The Summer Edit', 'slug' => 'summer-edit', 'count' => '42 pieces', 'img' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&q=80'],
                        ['name' => 'Work & Play', 'slug' => 'work-play', 'count' => '28 pieces', 'img' => 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=800&q=80'],
                        ['name' => 'Evening Luxe', 'slug' => 'evening-luxe', 'count' => '19 pieces', 'img' => 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=800&q=80'],
                    ];
                @endphp

                @foreach($dummyCollections as $collection)
                    <div class="collection-card fade-up">
                        <div class="collection-bg" style="background-image: url('{{ $collection['img'] }}');"></div>
                        <div class="collection-overlay"></div>
                        <div class="collection-content">
                            <div>
                                <h3 class="collection-name">{{ $collection['name'] }}</h3>
                                <p class="collection-count">{{ $collection['count'] }}</p>
                            </div>

                        </div>
                    </div>
                @endforeach
                {{-- Blade: @endforeach --}}
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- BEST SELLERS --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <section class="home-section" style="background: var(--cream);">
        <div class="section-inner">
            <div class="section-header fade-up">
                <h2 class="section-title"><em>Best</em> Sellers</h2>
                <a href="{{ route('frontend.products.index') }}" class="section-link">
                    View all
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="product-grid">
                @foreach($bestSellers as $product)
                    <div class="product-card fade-up">
                        <div class="product-img-wrap">
                            <img src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->file_path) : asset('images/placeholder.jpg') }}"
                                alt="{{ $product->name }}" loading="lazy">
                            <span class="product-badge bestseller">Best Seller</span>
                            <button class="product-wishlist" aria-label="Add to wishlist">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </button>
                            <button class="product-quick-add">+ Quick Add</button>
                        </div>
                        <div class="product-info">
                            <p class="product-category">{{ $product->categories->first()->name ?? 'Uncategorized' }}</p>
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price-row">
                                <span class="price-current">Rs. {{ number_format($product->base_price, 2) }}</span>
                            </div>
                            <div class="product-colors">
                                {{-- Colors logic can be implemented here based on variants --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TRUST BADGES + NEWSLETTER --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <section class="trust-section">
        <div class="section-inner">

            {{-- Trust badges --}}
            <div class="trust-grid">
                <div class="trust-badge fade-up">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                    </div>
                    <h4 class="trust-title">Free Shipping</h4>
                    <p class="trust-sub">On all orders above Rs. 5,000 island-wide.</p>
                </div>
                <div class="trust-badge fade-up">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10" />
                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                        </svg>
                    </div>
                    <h4 class="trust-title">Easy Returns</h4>
                    <p class="trust-sub">14-day hassle-free return policy on all items.</p>
                </div>
                <div class="trust-badge fade-up">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <h4 class="trust-title">Secure Payment</h4>
                    <p class="trust-sub">256-bit SSL encryption on every transaction.</p>
                </div>
                <div class="trust-badge fade-up">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.41 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.52 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.16 6.16l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                    </div>
                    <h4 class="trust-title">24/7 Support</h4>
                    <p class="trust-sub">Our team is always here to help you out.</p>
                </div>
            </div>

            {{-- Newsletter banner --}}
            <div class="newsletter-banner fade-up">
                <div class="newsletter-text">
                    <p class="newsletter-eyebrow">Newsletter</p>
                    <h2 class="newsletter-title">Join the <em>inner circle</em></h2>
                    <p class="newsletter-desc">Be first to know about new collections, exclusive offers, and style
                        inspiration delivered to your inbox.</p>
                </div>
                <div class="newsletter-form-wrap">
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <div class="nl-form">
                            <input type="email" name="email" placeholder="Enter your email address" required>
                            <button type="submit">Subscribe</button>
                        </div>
                    </form>
                    <p class="nl-privacy">No spam. Unsubscribe anytime. 🔒</p>
                </div>
            </div>

        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- GSAP ANIMATIONS --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <script>
        window.addEventListener('load', () => {
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            /* ── Hero slider ─────────────────────────────────────────── */
            let currentSlide = 0;
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            const track = document.getElementById('heroTrack');

            function goToSlide(n) {
                slides[currentSlide].classList.remove('active');
                dots[currentSlide].classList.remove('active');
                currentSlide = (n + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');

                gsap.to(track, {
                    x: `-${currentSlide * 100}%`,
                    duration: 1.1,
                    ease: 'power3.inOut'
                });

                /* Animate new slide content in */
                const content = slides[currentSlide].querySelector('.hero-content');
                gsap.fromTo(content.children,
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', stagger: 0.12, delay: 0.35 }
                );
            }

            dots.forEach(dot => {
                dot.addEventListener('click', () => goToSlide(+dot.dataset.slide));
            });

            /* Auto-advance */
            let autoSlide = setInterval(() => goToSlide(currentSlide + 1), 5500);
            document.querySelector('.hero').addEventListener('mouseenter', () => clearInterval(autoSlide));
            document.querySelector('.hero').addEventListener('mouseleave', () => {
                autoSlide = setInterval(() => goToSlide(currentSlide + 1), 5500);
            });

            /* Swipe support */
            let touchStartX = 0;
            track.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
            track.addEventListener('touchend', e => {
                const dx = e.changedTouches[0].clientX - touchStartX;
                if (Math.abs(dx) > 50) goToSlide(currentSlide + (dx < 0 ? 1 : -1));
            });

            /* ── Hero content entrance ───────────────────────────────── */
            const firstContent = slides[0].querySelector('.hero-content');
            gsap.fromTo(firstContent.children,
                { opacity: 0, y: 40 },
                { opacity: 1, y: 0, duration: 1, ease: 'power3.out', stagger: 0.15, delay: 1.1 }
            );

            /* ── ScrollTrigger: fade-up elements ─────────────────────── */
            document.querySelectorAll('.fade-up').forEach((el, i) => {
                gsap.to(el, {
                    opacity: 1, y: 0,
                    duration: 0.85,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: el,
                        start: 'top 86%',
                        toggleActions: 'play none none none'
                    },
                    delay: (i % 4) * 0.08
                });
            });

            /* ── Product cards stagger ───────────────────────────────── */
            document.querySelectorAll('.product-grid').forEach(grid => {
                const cards = grid.querySelectorAll('.product-card');
                cards.forEach(c => { c.style.opacity = '0'; c.style.transform = 'translateY(40px)'; });
                ScrollTrigger.create({
                    trigger: grid,
                    start: 'top 85%',
                    onEnter: () => {
                        gsap.to(cards, {
                            opacity: 1, y: 0,
                            duration: 0.75,
                            ease: 'power3.out',
                            stagger: 0.1
                        });
                    }
                });
            });

            /* ── Collection cards ────────────────────────────────────── */
            document.querySelectorAll('.collection-card').forEach((card, i) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                ScrollTrigger.create({
                    trigger: card,
                    start: 'top 88%',
                    onEnter: () => {
                        gsap.to(card, {
                            opacity: 1, y: 0,
                            duration: 0.8,
                            ease: 'power3.out',
                            delay: i * 0.12
                        });
                    }
                });
            });

            /* ── Category pills pop-in ───────────────────────────────── */
            gsap.from('.cat-pill', {
                opacity: 0, y: 16, scale: 0.92,
                duration: 0.5,
                ease: 'back.out(1.7)',
                stagger: 0.06,
                scrollTrigger: {
                    trigger: '.categories-strip',
                    start: 'top 90%'
                }
            });

            /* ── Trust badges ────────────────────────────────────────── */
            gsap.from('.trust-badge', {
                opacity: 0, y: 24,
                duration: 0.65,
                ease: 'power3.out',
                stagger: 0.1,
                scrollTrigger: {
                    trigger: '.trust-grid',
                    start: 'top 85%'
                }
            });

            /* ── Newsletter banner slide-up ──────────────────────────── */
            gsap.from('.newsletter-banner', {
                opacity: 0, y: 40,
                duration: 0.9,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.newsletter-banner',
                    start: 'top 88%'
                }
            });

            /* ── Marquee pause on scroll stop ───────────────────────── */
            const marqueeTrack = document.querySelector('.marquee-track');
            if (marqueeTrack) {
                ScrollTrigger.create({
                    trigger: '.marquee-section',
                    start: 'top bottom',
                    end: 'bottom top',
                    onEnter: () => gsap.to(marqueeTrack, { opacity: 1, duration: 0.4 }),
                    onLeave: () => gsap.to(marqueeTrack, { opacity: 0.4, duration: 0.4 }),
                    onEnterBack: () => gsap.to(marqueeTrack, { opacity: 1, duration: 0.4 }),
                });
            }

            /* ── Category pill active state ─────────────────────────── */
            document.querySelectorAll('.cat-pill').forEach(pill => {
                pill.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    gsap.from(this, { scale: 0.92, duration: 0.35, ease: 'back.out(2)' });
                });
            });
        });
    </script>

@endsection