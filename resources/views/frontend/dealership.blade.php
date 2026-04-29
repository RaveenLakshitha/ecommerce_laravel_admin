@extends('frontend.layouts.app')@section('title', 'Buy & Sell Cars in Sri Lanka | Best Deals on New & Used Vehicles')

@section('pageWrapperClass', 'hero-page')

@push('head')
    <meta name="description"
        content="Find your perfect car in Sri Lanka. Thousands of verified listings, transparent prices, secure deals. Buy or sell new and used cars across Colombo, Kandy, Galle and all districts.">
@endpush

@section('styles')
    <style>
        /* ═══════════════════════════════════════════════════════
               HOME PAGE — SECTION TOKENS
            ═══════════════════════════════════════════════════════ */
        :root {
            --s-hero-h: 100svh;
            --s-pad-y: clamp(4rem, 8vw, 7rem);
            --s-pad-y-sm: clamp(2.5rem, 5vw, 4rem);
        }

        /* ── shared section spacing ── */
        .hp-section {
            padding-block: var(--s-pad-y);
        }

        .hp-section--sm {
            padding-block: var(--s-pad-y-sm);
        }

        /* ── reveal animation base ── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(40px);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(.94);
        }

        /* ══════════════════════════════════════════════════════
               1. HERO
            ══════════════════════════════════════════════════════ */
        #hero {
            position: relative;
            min-height: var(--s-hero-h);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding-bottom: clamp(3rem, 8vh, 6rem);
            overflow: hidden;
            background: #f8fafc;
        }

        /* Background image / video layer */
        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .45;
            transform: scale(1.06);
            /* JS will animate this to scale(1) on load */
        }

        /* Diagonal gradient overlay */
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(175deg, transparent 30%, rgba(248, 250, 252, 1) 90%),
                linear-gradient(90deg, rgba(248, 250, 252, .8) 0%, transparent 60%);
        }

        /* Animated noise grain */
        .hero-grain {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            opacity: .05;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 200px;
            animation: grainShift 8s steps(2) infinite;
        }

        @keyframes grainShift {

            0%,
            100% {
                transform: translate(0, 0);
            }

            25% {
                transform: translate(-3px, 2px);
            }

            50% {
                transform: translate(2px, -3px);
            }

            75% {
                transform: translate(-1px, 3px);
            }
        }

        /* Accent stripe left edge */
        .hero-stripe {
            position: absolute;
            left: 0;
            top: 15%;
            bottom: 15%;
            width: 4px;
            background: linear-gradient(180deg, transparent, var(--c-accent), transparent);
            z-index: 2;
        }

        /* Stats floating top-right */
        .hero-stats {
            position: absolute;
            top: clamp(5rem, 12vh, 7rem);
            right: clamp(1.5rem, 5vw, 4rem);
            z-index: 4;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .hero-stat-pill {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem .9rem;
            background: rgba(0, 0, 0, .04);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 60px;
            backdrop-filter: blur(10px);
            font-size: .78rem;
            color: rgba(15, 23, 42, .7);
            font-family: var(--font-display);
            font-weight: 400;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        .hero-stat-pill strong {
            color: var(--c-accent);
            font-weight: 600;
        }

        .hero-stat-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            flex-shrink: 0;
            box-shadow: 0 0 6px #22c55e;
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(.7);
            }
        }

        /* Main hero content */
        .hero-content {
            position: relative;
            z-index: 3;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-family: var(--font-display);
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--c-accent);
            margin-bottom: 1rem;
        }

        .hero-eyebrow-line {
            display: block;
            width: 30px;
            height: 2px;
            background: var(--c-accent);
        }

        .hero-headline {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(2.8rem, 7vw, 6rem);
            line-height: 1.0;
            letter-spacing: -.01em;
            color: var(--c-text);
            max-width: 700px;
            margin-bottom: 1.25rem;
        }

        .hero-headline em {
            font-style: normal;
            color: var(--c-accent);
            display: block;
        }

        .hero-sub {
            font-size: clamp(.9rem, 1.8vw, 1.1rem);
            color: rgba(15, 23, 42, .6);
            max-width: 480px;
            line-height: 1.7;
            margin-bottom: 2.25rem;
        }

        .hero-sub span {
            color: rgba(15, 23, 42, .85);
        }

        /* ── Search bar ── */
        .hero-search-wrap {
            background: var(--c-white);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: var(--radius-md);
            box-shadow: 0 10px 40px rgba(0, 0, 0, .06);
            padding: .6rem .6rem .6rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            max-width: 640px;
            margin-bottom: 1.25rem;
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .hero-search-wrap:focus-within {
            border-color: rgba(5, 150, 105, .5);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .12);
        }

        .hero-search-icon {
            color: var(--c-muted);
            flex-shrink: 0;
        }

        .hero-search-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            font-family: var(--font-body);
            font-size: 1rem;
            color: var(--c-text);
            min-width: 0;
        }

        .hero-search-input::placeholder {
            color: var(--c-muted);
        }

        .hero-search-divider {
            width: 1px;
            height: 24px;
            background: rgba(0, 0, 0, .08);
            flex-shrink: 0;
        }

        .hero-search-filter {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: var(--c-muted);
            padding: .3rem .6rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .hero-search-filter:hover {
            background: rgba(0, 0, 0, .04);
            color: var(--c-text);
        }

        .hero-search-btn {
            flex-shrink: 0;
            padding: .7rem 1.35rem;
            background: var(--c-accent);
            color: white;
            border-radius: var(--radius-sm);
            font-family: var(--font-display);
            font-size: .88rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: .4rem;
            white-space: nowrap;
        }

        .hero-search-btn:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, .4);
        }

        /* Quick filters row */
        .hero-quick-filters {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .hero-filter-label {
            font-size: .75rem;
            color: var(--c-muted);
            margin-right: .25rem;
            font-family: var(--font-display);
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .hero-filter-chip {
            padding: .35rem .8rem;
            background: rgba(0, 0, 0, .04);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 60px;
            font-size: .78rem;
            color: rgba(15, 23, 42, .7);
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .hero-filter-chip:hover,
        .hero-filter-chip.active {
            background: rgba(5, 150, 105, .15);
            border-color: rgba(5, 150, 105, .4);
            color: var(--c-text);
        }

        /* Hero CTA row */
        .hero-ctas {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
            margin-top: 1.75rem;
        }

        /* Scroll indicator */
        .hero-scroll-hint {
            position: absolute;
            bottom: 2rem;
            right: clamp(1.5rem, 5vw, 4rem);
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .4rem;
            color: var(--c-muted);
            font-family: var(--font-display);
            font-size: .65rem;
            letter-spacing: .14em;
            text-transform: uppercase;
            writing-mode: vertical-rl;
        }

        .hero-scroll-track {
            width: 1px;
            height: 50px;
            background: rgba(0, 0, 0, .08);
            position: relative;
            overflow: hidden;
            border-radius: 1px;
        }

        .hero-scroll-track::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 40%;
            background: var(--c-accent);
            animation: scrollTrack 2.5s ease infinite;
        }

        @keyframes scrollTrack {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(300%);
            }
        }

        /* ══════════════════════════════════════════════════════
               2. CATEGORY STRIP
            ══════════════════════════════════════════════════════ */
        #category-strip {
            background: var(--c-surface);
            border-bottom: 1px solid var(--c-border);
            padding-block: 0;
            position: sticky;
            top: 56px;
            z-index: 80;
        }

        .cat-strip-inner {
            display: flex;
            align-items: stretch;
            overflow-x: auto;
            scrollbar-width: none;
            gap: 0;
            -webkit-overflow-scrolling: touch;
        }

        .cat-strip-inner::-webkit-scrollbar {
            display: none;
        }

        .cat-tab {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: 1rem 1.35rem;
            font-family: var(--font-display);
            font-size: .82rem;
            font-weight: 400;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--c-muted);
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .cat-tab svg {
            opacity: .6;
            transition: opacity var(--transition);
        }

        .cat-tab:hover {
            color: var(--c-text);
            background: rgba(0, 0, 0, .03);
        }

        .cat-tab:hover svg {
            opacity: 1;
        }

        .cat-tab.active {
            color: var(--c-accent);
            border-bottom-color: var(--c-accent);
        }

        .cat-tab.active svg {
            opacity: 1;
        }

        .cat-strip-divider {
            width: 1px;
            background: var(--c-border);
            margin-block: .75rem;
            align-self: stretch;
            flex-shrink: 0;
        }

        /* ══════════════════════════════════════════════════════
               3. FEATURED LISTINGS
            ══════════════════════════════════════════════════════ */
        #featured {
            background: var(--c-bg);
        }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }

        .section-header-left {}

        .section-header-right {}

        /* Car grid */
        .car-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        /* Car card */
        .car-card {
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
            cursor: pointer;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .car-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, .5);
            border-color: rgba(255, 255, 255, .14);
        }

        .car-card-img-wrap {
            position: relative;
            padding-top: 62%;
            overflow: hidden;
            background: var(--c-surface-2);
        }

        .car-card-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .car-card:hover .car-card-img {
            transform: scale(1.05);
        }

        .car-card-badges {
            position: absolute;
            top: .75rem;
            left: .75rem;
            display: flex;
            flex-wrap: wrap;
            gap: .3rem;
            z-index: 2;
        }

        .car-badge {
            padding: .2rem .5rem;
            border-radius: 3px;
            font-family: var(--font-display);
            font-size: .62rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .car-badge--deal {
            background: #059669;
            color: white;
        }

        .car-badge--hot {
            background: #f59e0b;
            color: white;
        }

        .car-badge--low {
            background: #2563eb;
            color: white;
        }

        .car-badge--verify {
            background: rgba(37, 99, 235, .15);
            color: #93c5fd;
            border: 1px solid rgba(37, 99, 235, .3);
            backdrop-filter: blur(8px);
        }

        .car-badge--drop {
            background: #d97706;
            color: white;
        }

        .car-badge--new {
            background: #7c3aed;
            color: white;
        }

        .car-card-save {
            position: absolute;
            top: .75rem;
            right: .75rem;
            z-index: 2;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            background: rgba(10, 10, 12, .6);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, .1);
            display: grid;
            place-items: center;
            color: var(--c-muted);
            transition: var(--transition);
        }

        .car-card-save:hover {
            background: rgba(5, 150, 105, .2);
            color: var(--c-accent);
            border-color: var(--c-accent);
        }

        .car-card-img-placeholder {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }

        .car-card-img-placeholder svg {
            opacity: .15;
        }

        .car-card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: .4rem;
            flex: 1;
        }

        .car-card-price {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--c-text);
            letter-spacing: .01em;
        }

        .car-card-price span {
            font-size: .72rem;
            color: var(--c-muted);
            font-weight: 400;
            margin-left: .2rem;
        }

        .car-card-name {
            font-family: var(--font-display);
            font-weight: 500;
            font-size: .95rem;
            color: var(--c-text);
            line-height: 1.3;
        }

        .car-card-meta {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .car-card-meta-item {
            display: flex;
            align-items: center;
            gap: .3rem;
            font-size: .75rem;
            color: var(--c-muted);
        }

        .car-card-meta-item svg {
            opacity: .7;
            flex-shrink: 0;
        }

        .car-card-footer {
            padding: .65rem 1rem;
            border-top: 1px solid var(--c-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
        }

        .car-card-location {
            display: flex;
            align-items: center;
            gap: .3rem;
            font-size: .74rem;
            color: var(--c-muted);
        }

        .car-card-contact {
            padding: .35rem .75rem;
            background: rgba(5, 150, 105, .1);
            border: 1px solid rgba(5, 150, 105, .25);
            border-radius: var(--radius-sm);
            font-family: var(--font-display);
            font-size: .7rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--c-accent);
            transition: var(--transition);
        }

        .car-card-contact:hover {
            background: var(--c-accent);
            color: white;
            border-color: var(--c-accent);
        }

        /* Featured "hero" card — larger first card */
        .car-card--featured {
            grid-column: span 2;
            grid-row: span 2;
        }

        .car-card--featured .car-card-img-wrap {
            padding-top: 56%;
        }

        .car-card--featured .car-card-price {
            font-size: 1.4rem;
        }

        .car-card--featured .car-card-name {
            font-size: 1.1rem;
        }

        /* ══════════════════════════════════════════════════════
               4. HOW IT WORKS
            ══════════════════════════════════════════════════════ */
        #how-it-works {
            background: var(--c-surface);
            border-top: 1px solid var(--c-border);
            border-bottom: 1px solid var(--c-border);
            position: relative;
            overflow: hidden;
        }

        /* Decorative diagonal accent */
        #how-it-works::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(5, 150, 105, .08), transparent 70%);
            pointer-events: none;
        }

        .how-tabs {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 3rem;
        }

        .how-tab {
            padding: .5rem 1.1rem;
            border-radius: 60px;
            font-family: var(--font-display);
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--c-muted);
            border: 1px solid var(--c-border);
            cursor: pointer;
            transition: var(--transition);
        }

        .how-tab.active,
        .how-tab:hover {
            background: var(--c-accent);
            border-color: var(--c-accent);
            color: white;
        }

        .how-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            position: relative;
        }

        /* Connector line */
        .how-steps::before {
            content: '';
            position: absolute;
            top: 28px;
            left: calc(12.5% + 20px);
            right: calc(12.5% + 20px);
            height: 1px;
            background: linear-gradient(90deg,
                    var(--c-accent),
                    rgba(5, 150, 105, .3) 50%,
                    transparent);
            z-index: 0;
        }

        .how-step {
            position: relative;
            z-index: 1;
        }

        .how-step-number {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-sm);
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            display: grid;
            place-items: center;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--c-accent);
            margin-bottom: 1.25rem;
            transition: var(--transition);
            position: relative;
        }

        .how-step:hover .how-step-number {
            background: var(--c-accent);
            color: white;
            border-color: var(--c-accent);
            box-shadow: 0 8px 24px rgba(5, 150, 105, .3);
            transform: translateY(-2px);
        }

        .how-step-title {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--c-text);
            margin-bottom: .5rem;
        }

        .how-step-desc {
            font-size: .88rem;
            color: var(--c-muted);
            line-height: 1.65;
        }

        /* Trust badges row */
        .trust-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 3rem;
            padding-top: 3rem;
            border-top: 1px solid var(--c-border);
        }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.1rem 1.25rem;
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        .trust-badge:hover {
            border-color: rgba(0, 0, 0, .14);
            background: rgba(0, 0, 0, .01);
        }

        .trust-badge-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: rgba(5, 150, 105, .1);
            border: 1px solid rgba(5, 150, 105, .2);
            display: grid;
            place-items: center;
            color: var(--c-accent);
            flex-shrink: 0;
        }

        .trust-badge-text strong {
            display: block;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: .9rem;
            color: var(--c-text);
            margin-bottom: .15rem;
        }

        .trust-badge-text span {
            font-size: .78rem;
            color: var(--c-muted);
        }

        /* ══════════════════════════════════════════════════════
               5. DEALS / PROMOTIONS BANNER
            ══════════════════════════════════════════════════════ */
        #deals {
            background: var(--c-bg);
            position: relative;
            overflow: hidden;
        }

        .deals-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 1.25rem;
        }

        .deal-card {
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: relative;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 280px;
        }

        .deal-card-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .deal-card-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .3;
        }

        .deal-card-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, transparent 20%, rgba(6, 6, 10, .88) 80%);
        }

        .deal-card--accent .deal-card-bg::after {
            background: linear-gradient(160deg, rgba(232, 56, 13, .3) 0%, rgba(6, 6, 10, .95) 70%);
        }

        .deal-card-content {
            position: relative;
            z-index: 1;
        }

        .deal-card-tag {
            display: inline-block;
            padding: .2rem .55rem;
            background: var(--c-accent);
            border-radius: 3px;
            font-family: var(--font-display);
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: white;
            margin-bottom: .75rem;
        }

        .deal-card h3 {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(1.2rem, 2.5vw, 1.7rem);
            color: var(--c-white);
            line-height: 1.2;
            margin-bottom: .5rem;
        }

        .deal-card p {
            font-size: .85rem;
            color: rgba(240, 237, 232, .6);
            margin-bottom: 1.25rem;
            max-width: 360px;
        }

        /* Small deals stack */
        .deal-stack {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .deal-stack-card {
            flex: 1;
            border-radius: var(--radius-md);
            border: 1px solid var(--c-border);
            padding: 1.4rem;
            background: var(--c-surface);
            display: flex;
            align-items: center;
            gap: 1.1rem;
            transition: var(--transition);
        }

        .deal-stack-card:hover {
            border-color: rgba(232, 56, 13, .3);
            background: rgba(232, 56, 13, .04);
        }

        .deal-stack-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            background: rgba(232, 56, 13, .1);
            border: 1px solid rgba(232, 56, 13, .2);
            display: grid;
            place-items: center;
            color: var(--c-accent);
            flex-shrink: 0;
        }

        .deal-stack-text strong {
            display: block;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: .95rem;
            color: var(--c-text);
            margin-bottom: .2rem;
        }

        .deal-stack-text span {
            font-size: .8rem;
            color: var(--c-muted);
        }

        /* ══════════════════════════════════════════════════════
               6. TOOLS & CALCULATORS
            ══════════════════════════════════════════════════════ */
        #tools {
            background: var(--c-surface);
            border-top: 1px solid var(--c-border);
            border-bottom: 1px solid var(--c-border);
        }

        .tools-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .tool-card {
            background: var(--c-bg);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            transition: var(--transition);
        }

        .tool-card:hover {
            border-color: rgba(232, 56, 13, .25);
        }

        .tool-card-head {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .tool-icon {
            width: 50px;
            height: 50px;
            border-radius: var(--radius-sm);
            background: rgba(232, 56, 13, .1);
            border: 1px solid rgba(232, 56, 13, .2);
            display: grid;
            place-items: center;
            color: var(--c-accent);
            flex-shrink: 0;
        }

        .tool-card-head-text h3 {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--c-text);
            margin-bottom: .2rem;
        }

        .tool-card-head-text p {
            font-size: .8rem;
            color: var(--c-muted);
        }

        /* EMI Calculator */
        .emi-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
            margin-bottom: 1.25rem;
        }

        .emi-field label {
            display: block;
            font-family: var(--font-display);
            font-size: .68rem;
            font-weight: 500;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--c-muted);
            margin-bottom: .4rem;
        }

        .emi-field input,
        .emi-field select {
            width: 100%;
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            padding: .6rem .85rem;
            font-family: var(--font-body);
            font-size: .9rem;
            color: var(--c-text);
            outline: none;
            transition: border-color var(--transition);
        }

        .emi-field input:focus,
        .emi-field select:focus {
            border-color: rgba(232, 56, 13, .5);
        }

        .emi-field select option {
            background: var(--c-surface);
        }

        .emi-result {
            background: var(--c-surface);
            border: 1px solid rgba(232, 56, 13, .2);
            border-radius: var(--radius-sm);
            padding: .85rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .emi-result-label {
            font-family: var(--font-display);
            font-size: .7rem;
            font-weight: 500;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--c-muted);
        }

        .emi-result-value {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--c-accent);
        }

        .emi-result-value small {
            font-size: .75rem;
            font-weight: 400;
            color: var(--c-muted);
            margin-left: .2rem;
        }

        /* Valuation */
        .val-fields {
            display: flex;
            flex-direction: column;
            gap: .85rem;
            margin-bottom: 1.25rem;
        }

        .val-field label {
            display: block;
            font-family: var(--font-display);
            font-size: .68rem;
            font-weight: 500;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--c-muted);
            margin-bottom: .4rem;
        }

        .val-field select,
        .val-field input {
            width: 100%;
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            padding: .6rem .85rem;
            font-family: var(--font-body);
            font-size: .9rem;
            color: var(--c-text);
            outline: none;
            transition: border-color var(--transition);
        }

        .val-field select:focus,
        .val-field input:focus {
            border-color: rgba(232, 56, 13, .5);
        }

        .val-field select option {
            background: var(--c-surface);
        }

        .val-field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
        }

        /* ══════════════════════════════════════════════════════
               7. POPULAR SEARCHES / TRENDING
            ══════════════════════════════════════════════════════ */
        #trending {
            background: var(--c-bg);
        }

        .trending-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        .trending-list {
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }

        .trending-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .85rem 1rem;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .trending-item:hover {
            background: rgba(255, 255, 255, .04);
            border-color: var(--c-border);
        }

        .trending-item-left {
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .trending-rank {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: .75rem;
            color: var(--c-muted);
            width: 22px;
            text-align: right;
            opacity: .5;
        }

        .trending-rank.top {
            color: var(--c-accent);
            opacity: 1;
        }

        .trending-name {
            font-family: var(--font-display);
            font-weight: 500;
            font-size: .92rem;
            color: var(--c-text);
        }

        .trending-count {
            font-size: .75rem;
            color: var(--c-muted);
        }

        .trending-arrow {
            color: var(--c-muted);
            transition: transform var(--transition), color var(--transition);
        }

        .trending-item:hover .trending-arrow {
            transform: translateX(3px);
            color: var(--c-accent);
        }

        /* District quick links */
        .district-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .6rem;
        }

        .district-btn {
            padding: .6rem .85rem;
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            font-size: .82rem;
            color: var(--c-muted);
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            display: block;
        }

        .district-btn:hover {
            border-color: rgba(232, 56, 13, .3);
            color: var(--c-text);
            background: rgba(232, 56, 13, .05);
        }

        /* ══════════════════════════════════════════════════════
               8. TESTIMONIALS
            ══════════════════════════════════════════════════════ */
        #testimonials {
            background: var(--c-surface);
            border-top: 1px solid var(--c-border);
            border-bottom: 1px solid var(--c-border);
            position: relative;
            overflow: hidden;
        }

        #testimonials::before {
            content: '"';
            position: absolute;
            top: -2rem;
            right: 5%;
            font-family: var(--font-display);
            font-size: 24rem;
            line-height: 1;
            color: rgba(232, 56, 13, .04);
            pointer-events: none;
            user-select: none;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }

        .testimonial-card {
            background: var(--c-bg);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-md);
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            transition: var(--transition);
        }

        .testimonial-card:hover {
            border-color: rgba(255, 255, 255, .12);
            transform: translateY(-2px);
        }

        .testimonial-stars {
            display: flex;
            gap: .2rem;
            color: var(--c-accent-2);
        }

        .testimonial-quote {
            font-size: .92rem;
            color: rgba(240, 237, 232, .75);
            line-height: 1.75;
            flex: 1;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding-top: 1rem;
            border-top: 1px solid var(--c-border);
        }

        .testimonial-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--c-accent), var(--c-accent-2));
            display: grid;
            place-items: center;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: .85rem;
            color: white;
            flex-shrink: 0;
        }

        .testimonial-author-info strong {
            display: block;
            font-family: var(--font-display);
            font-weight: 500;
            font-size: .88rem;
            color: var(--c-text);
        }

        .testimonial-author-info span {
            font-size: .75rem;
            color: var(--c-muted);
        }

        /* Stats counter row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-top: 3rem;
            padding-top: 3rem;
            border-top: 1px solid var(--c-border);
        }

        .stat-block {
            text-align: center;
        }

        .stat-block-number {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            color: var(--c-text);
            line-height: 1;
            margin-bottom: .4rem;
        }

        .stat-block-number span {
            color: var(--c-accent);
        }

        .stat-block-label {
            font-size: .8rem;
            color: var(--c-muted);
            letter-spacing: .04em;
        }

        /* ══════════════════════════════════════════════════════
               9. SELL CTA BANNER
            ══════════════════════════════════════════════════════ */
        #sell-cta {
            background: var(--c-bg);
        }

        .sell-cta-wrap {
            background: linear-gradient(120deg, #12070a 0%, #1a0a08 40%, #0a0a0c 100%);
            border: 1px solid rgba(232, 56, 13, .2);
            border-radius: var(--radius-lg);
            padding: clamp(2.5rem, 6vw, 5rem);
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 3rem;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .sell-cta-wrap::before {
            content: '';
            position: absolute;
            top: -80px;
            right: 200px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(232, 56, 13, .15), transparent 70%);
            pointer-events: none;
        }

        .sell-cta-wrap::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(245, 166, 35, .08), transparent 70%);
            pointer-events: none;
        }

        .sell-cta-text h2 {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(1.8rem, 4vw, 3rem);
            color: var(--c-white);
            line-height: 1.1;
            margin-bottom: .75rem;
        }

        .sell-cta-text h2 span {
            color: var(--c-accent);
        }

        .sell-cta-text p {
            font-size: 1rem;
            color: var(--c-muted);
            max-width: 480px;
            line-height: 1.7;
        }

        .sell-cta-perks {
            display: flex;
            flex-direction: column;
            gap: .5rem;
            margin-top: 1.5rem;
        }

        .sell-cta-perk {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .85rem;
            color: rgba(240, 237, 232, .7);
        }

        .sell-cta-perk svg {
            color: #22c55e;
            flex-shrink: 0;
        }

        .sell-cta-actions {
            display: flex;
            flex-direction: column;
            gap: .75rem;
            align-items: flex-start;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .sell-cta-actions .btn {
            width: 100%;
            justify-content: center;
            min-width: 200px;
        }

        /* ══════════════════════════════════════════════════════
               RESPONSIVE BREAKPOINTS
            ══════════════════════════════════════════════════════ */
        @media (max-width: 1200px) {
            .car-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .car-card--featured {
                grid-column: span 2;
            }
        }

        @media (max-width: 1024px) {
            .how-steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .how-steps::before {
                display: none;
            }

            .trending-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .tools-grid {
                grid-template-columns: 1fr;
            }

            .sell-cta-wrap {
                grid-template-columns: 1fr;
            }

            .sell-cta-actions {
                flex-direction: row;
                width: 100%;
            }

            .sell-cta-actions .btn {
                width: auto;
            }
        }

        @media (max-width: 768px) {
            .hero-stats {
                display: none;
            }

            .hero-scroll-hint {
                display: none;
            }

            .hero-search-filter {
                display: none;
            }

            .hero-search-divider {
                display: none;
            }

            .car-grid {
                grid-template-columns: 1fr 1fr;
            }

            .car-card--featured {
                grid-column: span 2;
            }

            .deals-grid {
                grid-template-columns: 1fr;
            }

            .trust-row {
                grid-template-columns: 1fr;
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .emi-fields {
                grid-template-columns: 1fr;
            }

            .val-field-row {
                grid-template-columns: 1fr;
            }

            .district-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .sell-cta-actions {
                flex-direction: column;
            }

            .sell-cta-actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .car-grid {
                grid-template-columns: 1fr;
            }

            .car-card--featured {
                grid-column: span 1;
            }

            .how-steps {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
            }

            .hero-headline {
                font-size: clamp(2rem, 10vw, 3.2rem);
            }

            .hero-ctas {
                flex-direction: column;
                align-items: stretch;
            }

            .hero-ctas .btn {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')

    {{-- ═══════════════════════════════════════════════════════
    1. HERO SECTION
    ═══════════════════════════════════════════════════════ --}}
    <section id="hero" aria-label="Hero">

        {{-- Background --}}
        <div class="hero-bg">
            <img class="hero-bg-img" id="heroBgImg"
                src="https://cimg2.ibsrv.net/ibimg/hgm/1600x900-1/100/370/toyota_100370246.jpg" alt="" role="presentation"
                loading="eager" onerror="this.style.opacity=0">
        </div>

        <div class="hero-grain" aria-hidden="true"></div>
        <div class="hero-stripe" aria-hidden="true"></div>

        {{-- Floating stat pills --}}
        <div class="hero-stats" aria-hidden="true">
            <div class="hero-stat-pill">
                <span class="hero-stat-dot"></span>
                <strong>{{ number_format($listing_count ?? 12400) }}+</strong> Active Listings
            </div>
            <div class="hero-stat-pill">
                <strong>{{ number_format($sold_today ?? 47) }}</strong> Sold Today
            </div>
            <div class="hero-stat-pill">
                <strong>All 25</strong> Districts
            </div>
        </div>

        {{-- Main content --}}
        <div class="container hero-content">
            <div class="hero-eyebrow" id="heroEyebrow">
                <span class="hero-eyebrow-line"></span>
                Sri Lanka's #1 Car Marketplace
            </div>

            <h1 class="hero-headline" id="heroHeadline">
                Find Your
                <em>Perfect Car</em>
            </h1>

            <p class="hero-sub" id="heroSub">
                <span>Thousands of verified listings</span> · Transparent prices · Secure deals across every district in Sri
                Lanka.
            </p>

            {{-- Search Bar --}}
            <div class="hero-search-wrap" id="heroSearch">
                <svg class="hero-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input type="text" class="hero-search-input" id="heroSearchInput"
                    placeholder="Search make, model, or keyword…" autocomplete="off" aria-label="Search cars">
                <div class="hero-search-divider" aria-hidden="true"></div>
                <button class="hero-search-filter" id="heroConditionFilter" aria-label="Filter by condition">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                    Condition
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </button>
                <button class="hero-search-filter" id="heroLocationFilter" aria-label="Filter by district">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    District
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </button>
                <button class="hero-search-btn" id="heroSearchBtn" onclick="window.location='#'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    Search
                </button>
            </div>

            {{-- Quick filter chips --}}
            <div class="hero-quick-filters" id="heroChips">
                <span class="hero-filter-label">Quick:</span>
                <button class="hero-filter-chip active" data-filter="all" onclick="location.href='#'">All</button>
                <button class="hero-filter-chip" data-filter="new" onclick="location.href='#'">New Cars</button>
                <button class="hero-filter-chip" data-filter="used" onclick="location.href='#'">Used Cars</button>
                <button class="hero-filter-chip" data-filter="hybrid" onclick="location.href='#'">Hybrid</button>
                <button class="hero-filter-chip" data-filter="electric" onclick="location.href='#'">Electric</button>
                <button class="hero-filter-chip" data-filter="under3m" onclick="location.href='#'">Under 3M {{ $currency_symbol }}</button>
                <button class="hero-filter-chip" data-filter="suv" onclick="location.href='#'">SUV</button>
            </div>

            {{-- CTA buttons --}}
            <div class="hero-ctas" id="heroCtas">
                <a href="#" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    Browse All Cars
                </a>
                <a href="#" class="btn btn-outline">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    List My Car — Free
                </a>
                <a href="#emi-calc" class="btn btn-ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M9 9h6M9 12h6M9 15h4" />
                    </svg>
                    EMI Calculator
                </a>
            </div>
        </div>

        {{-- Scroll hint --}}
        <div class="hero-scroll-hint" aria-hidden="true">
            <div class="hero-scroll-track"></div>
            Scroll
        </div>

    </section>

    {{-- ═══════════════════════════════════════════════════════
    2. CATEGORY STRIP
    ═══════════════════════════════════════════════════════ --}}
    <div id="category-strip" role="navigation" aria-label="Browse by category">
        <div class="container">
            <div class="cat-strip-inner">
                <a href="#" class="cat-tab active">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="10" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    All Cars
                </a>
                <div class="cat-strip-divider" aria-hidden="true"></div>
                <a href="#" class="cat-tab">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v7a2 2 0 0 1-2 2h-2" />
                        <circle cx="7" cy="19" r="2" />
                        <circle cx="17" cy="19" r="2" />
                    </svg>
                    SUV
                </a>
                <a href="#" class="cat-tab">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 17H1a1 1 0 0 1-1-1V9l4-4h14l4 4v7a1 1 0 0 1-1 1h-2" />
                        <circle cx="6" cy="17" r="2" />
                        <circle cx="18" cy="17" r="2" />
                    </svg>
                    Sedan
                </a>
                <a href="#" class="cat-tab">Hatchback</a>
                <a href="#" class="cat-tab">Van</a>
                <a href="#" class="cat-tab">Pickup</a>
                <a href="#" class="cat-tab">Wagon</a>
                <div class="cat-strip-divider" aria-hidden="true"></div>
                <a href="#" class="cat-tab">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                    Hybrid
                </a>
                <a href="#" class="cat-tab">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                    Electric
                </a>
                <a href="#" class="cat-tab">Diesel</a>
                <div class="cat-strip-divider" aria-hidden="true"></div>
                <a href="#" class="cat-tab">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    Dealers
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
    3. FEATURED LISTINGS
    ═══════════════════════════════════════════════════════ --}}
    <section id="featured" class="hp-section" aria-label="Featured listings">
        <div class="container">

            <div class="section-header">
                <div class="section-header-left reveal">
                    <p class="section-label">Hot Right Now</p>
                    <h2 class="section-title">Featured <span>Listings</span></h2>
                    <p class="section-subtitle">Hand-picked deals from verified sellers across Sri Lanka.</p>
                </div>
                <div class="section-header-right reveal">
                    <a href="#" class="btn btn-outline">
                        View All Cars
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="car-grid" id="carGrid">

                {{-- Featured large card --}}
                @php $featured = $featured_cars[0] ?? null; @endphp
                <article class="car-card car-card--featured reveal-scale"
                    aria-label="{{ $featured->title ?? 'Featured car' }}">
                    <div class="car-card-img-wrap">
                        @if($featured && $featured->primary_image)
                            <img class="car-card-img" src="{{ $featured->primary_image }}" alt="{{ $featured->title }}">
                        @else
                            <img class="car-card-img"
                                src="https://images.hgmsites.net/lrg/2022-bmw-3-series-330e-xdrive-plug-in-hybrid-angular-front-exterior-view_100826982_l.jpg"
                                alt="BMW 3 Series">
                        @endif
                        <div class="car-card-badges">
                            <span class="car-badge car-badge--hot">🔥 Hot Deal</span>
                            <span class="car-badge car-badge--verify">✓ Verified</span>
                        </div>
                        <button class="car-card-save" aria-label="Save to favourites">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                        </button>
                    </div>
                    <div class="car-card-body">
                        <div class="car-card-price">
                            @price($featured->price ?? 5800000)<span>Negotiable</span>
                        </div>
                        <div class="car-card-name">{{ $featured->title ?? 'Toyota Land Cruiser Prado 2019' }}</div>
                        <div class="car-card-meta">
                            <span class="car-card-meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                {{ $featured->year ?? '2019' }}
                            </span>
                            <span class="car-card-meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="16 3 21 3 21 8" />
                                    <line x1="4" y1="20" x2="21" y2="3" />
                                    <polyline points="21 16 21 21 16 21" />
                                    <line x1="15" y1="15" x2="21" y2="21" />
                                </svg>
                                {{ number_format($featured->mileage ?? 48000) }} km
                            </span>
                            <span class="car-card-meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                </svg>
                                {{ $featured->fuel ?? 'Diesel' }}
                            </span>
                            <span class="car-card-meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <path d="M9 3v18M3 9h6M3 15h6" />
                                </svg>
                                {{ $featured->transmission ?? 'Automatic' }}
                            </span>
                        </div>
                    </div>
                    <div class="car-card-footer">
                        <div class="car-card-location">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            {{ $featured->district ?? 'Colombo' }}
                        </div>
                        <a href="#" class="car-card-contact">View Details</a>
                    </div>
                </article>

                {{-- Remaining featured cards --}}
                @php
                    $sample_cars = $featured_cars ?? collect([
                        (object) ['title' => 'Honda HR-V 2015', 'price' => 5200000, 'year' => '2015', 'mileage' => 62000, 'fuel' => 'Petrol', 'transmission' => 'Auto', 'district' => 'Kandy', 'slug' => '#', 'badge' => 'deal', 'image' => 'https://cimg1.ibsrv.net/ibimg/hgm/1920x1080-1/100/464/2015-honda-hr-v_100464673.jpg'],
                        (object) ['title' => 'Suzuki Alto Lapin 2015', 'price' => 2950000, 'year' => '2015', 'mileage' => 18000, 'fuel' => 'Petrol', 'transmission' => 'Auto', 'district' => 'Galle', 'slug' => '#', 'badge' => 'new', 'image' => 'https://c8.alamy.com/comp/PXTJ3R/license-available-at-maximimagescom-2015-suzuki-alto-lapin-japanese-kei-car-light-compact-small-city-car-isolated-on-white-studio-background-PXTJ3R.jpg'],
                        (object) ['title' => 'BMW 3 Series 2017', 'price' => 9800000, 'year' => '2017', 'mileage' => 55000, 'fuel' => 'Petrol', 'transmission' => 'Auto', 'district' => 'Colombo', 'slug' => '#', 'badge' => 'drop', 'image' => 'https://images.hgmsites.net/lrg/2022-bmw-3-series-330e-xdrive-plug-in-hybrid-angular-front-exterior-view_100826982_l.jpg'],
                        (object) ['title' => 'Toyota Land Cruiser 2024', 'price' => 3750000, 'year' => '2024', 'mileage' => 78000, 'fuel' => 'Hybrid', 'transmission' => 'Auto', 'district' => 'Negombo', 'slug' => '#', 'badge' => 'verify', 'image' => 'https://cimg2.ibsrv.net/ibimg/hgm/1600x900-1/100/370/toyota_100370246.jpg'],
                        (object) ['title' => 'Toyota Prado 2023', 'price' => 11200000, 'year' => '2023', 'mileage' => 31000, 'fuel' => 'Petrol', 'transmission' => 'Auto', 'district' => 'Colombo', 'slug' => '#', 'badge' => 'low', 'image' => 'https://assets.cdntoyota.co.za/toyotacms23/attachments/cmhanuqtomdaf8pak74dleknv-2a5a3663-ret-vx-l-1920x1080.desktop.jpg'],
                    ]);
                @endphp

                @php $badges = ['deal' => ['car-badge--deal', 'Great Deal'], 'hot' => ['car-badge--hot', 'Hot'], 'new' => ['car-badge--new', 'New'], 'drop' => ['car-badge--drop', 'Price Drop'], 'verify' => ['car-badge--verify', '✓ Verified'], 'low' => ['car-badge--low', 'Low Mileage']]; @endphp

                @foreach($sample_cars->take(5) as $i => $car)
                    <article class="car-card reveal-scale" style="animation-delay:{{ ($i + 1) * 0.08 }}s"
                        aria-label="{{ $car->title }}">
                        <div class="car-card-img-wrap">
                            @if(isset($car->image))
                                <img class="car-card-img" src="{{ $car->image }}" alt="{{ $car->title }}">
                            @else
                                <div class="car-card-img-placeholder">
                                    <svg width="50" height="50" viewBox="0 0 24 24" fill="currentColor"
                                        style="color:var(--c-muted)">
                                        <path
                                            d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM19 17H5v-5h14v5z" />
                                        <circle cx="7.5" cy="14.5" r="1.5" />
                                        <circle cx="16.5" cy="14.5" r="1.5" />
                                    </svg>
                                </div>
                            @endif
                            @if(isset($badges[$car->badge ?? 'verify']))
                                <div class="car-card-badges">
                                    <span
                                        class="car-badge {{ $badges[$car->badge ?? 'verify'][0] }}">{{ $badges[$car->badge ?? 'verify'][1] }}</span>
                                </div>
                            @endif
                            <button class="car-card-save" aria-label="Save to favourites">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </button>
                        </div>
                        <div class="car-card-body">
                            <div class="car-card-price">@price($car->price)</div>
                            <div class="car-card-name">{{ $car->title }}</div>
                            <div class="car-card-meta">
                                <span class="car-card-meta-item">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    {{ $car->year }}
                                </span>
                                <span class="car-card-meta-item">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="16 3 21 3 21 8" />
                                        <line x1="4" y1="20" x2="21" y2="3" />
                                    </svg>
                                    {{ number_format($car->mileage) }} km
                                </span>
                                <span class="car-card-meta-item">{{ $car->fuel }}</span>
                            </div>
                        </div>
                        <div class="car-card-footer">
                            <div class="car-card-location">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                {{ $car->district }}
                            </div>
                            <a href="#" class="car-card-contact">View</a>
                        </div>
                    </article>
                @endforeach

            </div>{{-- /.car-grid --}}
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
    4. HOW IT WORKS
    ═══════════════════════════════════════════════════════ --}}
    <section id="how-it-works" class="hp-section" aria-label="How it works">
        <div class="container">

            <div class="section-header">
                <div class="section-header-left reveal">
                    <p class="section-label">Simple Process</p>
                    <h2 class="section-title">How <span>It Works</span></h2>
                </div>
                <div class="section-header-right">
                    <div class="how-tabs">
                        <button class="how-tab active" data-tab="buy" id="howTabBuy">For Buyers</button>
                        <button class="how-tab" data-tab="sell" id="howTabSell">For Sellers</button>
                    </div>
                </div>
            </div>

            {{-- Buyer steps --}}
            <div id="howBuySteps" class="how-steps">
                @php
                    $buy_steps = [
                        ['num' => '01', 'title' => 'Search & Filter', 'desc' => 'Use powerful filters — make, model, year, price, district, fuel type — to find cars that match exactly what you need.'],
                        ['num' => '02', 'title' => 'Compare & Shortlist', 'desc' => 'Compare specs, prices, and photos side-by-side. Save your favourites and review seller ratings and history.'],
                        ['num' => '03', 'title' => 'Inspect & Test Drive', 'desc' => 'Book a test drive or a certified vehicle inspection through our trusted partners before you commit.'],
                        ['num' => '04', 'title' => 'Buy Securely', 'desc' => 'Complete the purchase with confidence — escrow payments, ownership transfer guidance, and full paper support.'],
                    ];
                @endphp
                @foreach($buy_steps as $i => $step)
                    <div class="how-step reveal" style="transition-delay:{{ $i * 0.1 }}s">
                        <div class="how-step-number">{{ $step['num'] }}</div>
                        <h3 class="how-step-title">{{ $step['title'] }}</h3>
                        <p class="how-step-desc">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Seller steps (hidden by default) --}}
            <div id="howSellSteps" class="how-steps" style="display:none;">
                @php
                    $sell_steps = [
                        ['num' => '01', 'title' => 'Create Your Listing', 'desc' => 'Upload up to 20 photos, enter vehicle details, set your price. Takes under 5 minutes and is completely free.'],
                        ['num' => '02', 'title' => 'Get Verified', 'desc' => 'Our team reviews your listing and optionally arranges a certified inspection to build buyer trust.'],
                        ['num' => '03', 'title' => 'Connect with Buyers', 'desc' => 'Receive inquiries via chat, WhatsApp, or phone. Manage all conversations in your seller dashboard.'],
                        ['num' => '04', 'title' => 'Close the Deal', 'desc' => 'Finalise the sale with guided ownership transfer, secure payment handling, and post-sale support.'],
                    ];
                @endphp
                @foreach($sell_steps as $i => $step)
                    <div class="how-step">
                        <div class="how-step-number">{{ $step['num'] }}</div>
                        <h3 class="how-step-title">{{ $step['title'] }}</h3>
                        <p class="how-step-desc">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Trust row --}}
            <div class="trust-row">
                <div class="trust-badge reveal">
                    <div class="trust-badge-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <div class="trust-badge-text">
                        <strong>Secure Payments</strong>
                        <span>Escrow & verified transactions for private sales</span>
                    </div>
                </div>
                <div class="trust-badge reveal" style="transition-delay:.1s">
                    <div class="trust-badge-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                    </div>
                    <div class="trust-badge-text">
                        <strong>Vehicle History Reports</strong>
                        <span>Full accident, service & ownership history available</span>
                    </div>
                </div>
                <div class="trust-badge reveal" style="transition-delay:.2s">
                    <div class="trust-badge-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <div class="trust-badge-text">
                        <strong>{{ number_format($user_count ?? 50000) }}+ Members</strong>
                        <span>Sri Lanka's largest verified car community</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
    5. DEALS & PROMOTIONS
    ═══════════════════════════════════════════════════════ --}}
    <section id="deals" class="hp-section" aria-label="Deals and promotions">
        <div class="container">

            <div class="section-header">
                <div class="reveal">
                    <p class="section-label">This Week</p>
                    <h2 class="section-title">Special <span>Offers</span></h2>
                </div>
            </div>

            <div class="deals-grid">

                {{-- Hero deal card --}}
                <div class="deal-card deal-card--accent reveal-left">
                    <div class="deal-card-bg">
                        <img src="https://assets.cdntoyota.co.za/toyotacms23/attachments/cmhanuqtomdaf8pak74dleknv-2a5a3663-ret-vx-l-1920x1080.desktop.jpg"
                            alt="" role="presentation" onerror="this.style.display='none'">
                    </div>
                    <div class="deal-card-content">
                        <span class="deal-card-tag">🔥 Zero Commission — Limited Time</span>
                        <h3>Sell Your Car for<br>Free — First 30 Days</h3>
                        <p>List your vehicle with zero platform fees for your first 30 days. Reach over 50,000 active buyers
                            across Sri Lanka.</p>
                        <a href="#" class="btn btn-primary">
                            Start Selling Free
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Small deal stack --}}
                <div class="deal-stack reveal-right">
                    <div class="deal-stack-card">
                        <div class="deal-stack-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <path d="M9 9h6M9 12h6M9 15h4" />
                            </svg>
                        </div>
                        <div class="deal-stack-text">
                            <strong>Finance From 9.5% p.a.</strong>
                            <span>Partner bank offers on approved used vehicles. Get pre-approved in minutes.</span>
                        </div>
                    </div>
                    <div class="deal-stack-card">
                        <div class="deal-stack-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                        </div>
                        <div class="deal-stack-text">
                            <strong>Free Inspection Voucher</strong>
                            <span>Buy through Karbnzol and get a complimentary 50-point vehicle inspection.</span>
                        </div>
                    </div>
                    <div class="deal-stack-card">
                        <div class="deal-stack-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                        </div>
                        <div class="deal-stack-text">
                            <strong>Toyota & Honda Deals</strong>
                            <span>Price-dropped certified pre-owned Toyotas and Hondas updated weekly.</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
    6. TOOLS
    ═══════════════════════════════════════════════════════ --}}
    <section id="tools" class="hp-section" aria-label="Buyer and seller tools">
        <div class="container">

            <div class="section-header">
                <div class="reveal">
                    <p class="section-label">Smart Tools</p>
                    <h2 class="section-title">Plan Your <span>Purchase</span></h2>
                    <p class="section-subtitle">Two quick tools to help you budget and estimate your car's value before you
                        commit.</p>
                </div>
            </div>

            <div class="tools-grid">

                {{-- EMI Calculator --}}
                <div class="tool-card reveal-left" id="emi-calc">
                    <div class="tool-card-head">
                        <div class="tool-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <path d="M9 9h6M9 12h6M9 15h4" />
                            </svg>
                        </div>
                        <div class="tool-card-head-text">
                            <h3>EMI Calculator</h3>
                            <p>Estimate your monthly repayment</p>
                        </div>
                    </div>

                    <div class="emi-fields">
                        <div class="emi-field">
                            <label for="emiPrice">Car Price ({{ $currency_symbol }})</label>
                            <input type="number" id="emiPrice" placeholder="5,000,000" min="0" value="5000000">
                        </div>
                        <div class="emi-field">
                            <label for="emiDown">Down Payment ({{ $currency_symbol }})</label>
                            <input type="number" id="emiDown" placeholder="1,000,000" min="0" value="1000000">
                        </div>
                        <div class="emi-field">
                            <label for="emiRate">Interest Rate (% p.a.)</label>
                            <input type="number" id="emiRate" placeholder="9.5" step="0.1" min="0" value="9.5">
                        </div>
                        <div class="emi-field">
                            <label for="emiTerm">Loan Term</label>
                            <select id="emiTerm">
                                <option value="12">1 Year</option>
                                <option value="24">2 Years</option>
                                <option value="36" selected>3 Years</option>
                                <option value="48">4 Years</option>
                                <option value="60">5 Years</option>
                                <option value="72">6 Years</option>
                                <option value="84">7 Years</option>
                            </select>
                        </div>
                    </div>

                    <div class="emi-result">
                        <div>
                            <div class="emi-result-label">Monthly Payment</div>
                            <div class="emi-result-value" id="emiMonthly"><span class="js-currency-symbol">{{ $currency_symbol }}</span> 0<small>/mo</small></div>
                        </div>
                        <div style="text-align:right;">
                            <div class="emi-result-label">Total Interest</div>
                            <div style="font-family:var(--font-display);font-weight:600;font-size:.9rem;color:var(--c-muted)"
                                id="emiInterest"><span class="js-currency-symbol">{{ $currency_symbol }}</span> 0</div>
                        </div>
                    </div>

                    <button class="btn btn-primary" onclick="window.location='#'">
                        Find Cars in My Budget
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                {{-- Valuation Tool --}}
                <div class="tool-card reveal-right" id="val-tool">
                    <div class="tool-card-head">
                        <div class="tool-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <div class="tool-card-head-text">
                            <h3>Instant Valuation</h3>
                            <p>Find out what your car is worth today</p>
                        </div>
                    </div>

                    <div class="val-fields">
                        <div class="val-field-row">
                            <div class="val-field">
                                <label for="valMake">Make</label>
                                <select id="valMake">
                                    <option value="">Select Make</option>
                                    <option>Toyota</option>
                                    <option>Honda</option>
                                    <option>Suzuki</option>
                                    <option>Mitsubishi</option>
                                    <option>Nissan</option>
                                    <option>BMW</option>
                                    <option>Mercedes-Benz</option>
                                    <option>Volkswagen</option>
                                    <option>Hyundai</option>
                                    <option>Kia</option>
                                </select>
                            </div>
                            <div class="val-field">
                                <label for="valYear">Year</label>
                                <select id="valYear">
                                    <option value="">Year</option>
                                    @for($y = date('Y'); $y >= 2000; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="val-field">
                            <label for="valModel">Model</label>
                            <input type="text" id="valModel" placeholder="e.g. Vezel, Corolla, Aqua…">
                        </div>
                        <div class="val-field-row">
                            <div class="val-field">
                                <label for="valMileage">Mileage (km)</label>
                                <input type="number" id="valMileage" placeholder="e.g. 60,000">
                            </div>
                            <div class="val-field">
                                <label for="valCondition">Condition</label>
                                <select id="valCondition">
                                    <option value="">Select</option>
                                    <option>Excellent</option>
                                    <option>Good</option>
                                    <option>Fair</option>
                                    <option>Needs Work</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" style="width:100%;justify-content:center;" id="getValuationBtn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                        Get Instant Valuation
                    </button>

                    <p style="font-size:.75rem;color:var(--c-muted);margin-top:.75rem;text-align:center;">
                        Valuations are estimates based on current market data. Actual prices may vary.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
    7. TRENDING SEARCHES & DISTRICTS
    ═══════════════════════════════════════════════════════ --}}
    <section id="trending" class="hp-section" aria-label="Trending searches and locations">
        <div class="container">

            <div class="section-header">
                <div class="reveal">
                    <p class="section-label">What's Popular</p>
                    <h2 class="section-title">Trending <span>Now</span></h2>
                </div>
            </div>

            <div class="trending-grid">

                {{-- Trending searches --}}
                <div>
                    <h3
                        style="font-family:var(--font-display);font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;color:var(--c-muted);margin-bottom:.75rem;">
                        Top Searches</h3>
                    <div class="trending-list">
                        @php
                            $trending = [
                                ['name' => 'Toyota Corolla', 'count' => '1,248 listings'],
                                ['name' => 'Honda Vezel Hybrid', 'count' => '986 listings'],
                                ['name' => 'Suzuki Alto / WagonR', 'count' => '874 listings'],
                                ['name' => 'Toyota Aqua', 'count' => '751 listings'],
                                ['name' => 'Mitsubishi Outlander', 'count' => '634 listings'],
                                ['name' => 'BMW 3 Series', 'count' => '412 listings'],
                                ['name' => 'Honda Fit / Jazz', 'count' => '398 listings'],
                                ['name' => 'Toyota Prius', 'count' => '365 listings'],
                            ];
                        @endphp
                        @foreach($trending as $i => $item)
                            <a href="#" class="trending-item reveal" style="transition-delay:{{ $i * 0.05 }}s">
                                <div class="trending-item-left">
                                    <span class="trending-rank {{ $i < 3 ? 'top' : '' }}">{{ $i + 1 }}</span>
                                    <div>
                                        <div class="trending-name">{{ $item['name'] }}</div>
                                        <div class="trending-count">{{ $item['count'] }}</div>
                                    </div>
                                </div>
                                <svg class="trending-arrow" width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Browse by district --}}
                <div>
                    <h3
                        style="font-family:var(--font-display);font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;color:var(--c-muted);margin-bottom:.75rem;">
                        Browse by District</h3>
                    <div class="district-grid reveal">
                        @php
                            $districts = ['Colombo', 'Kandy', 'Galle', 'Negombo', 'Matara', 'Kurunegala', 'Ratnapura', 'Badulla', 'Jaffna', 'Trincomalee', 'Anuradhapura', 'Polonnaruwa'];
                        @endphp
                        @foreach($districts as $d)
                            <a href="#" class="district-btn">{{ $d }}</a>
                        @endforeach
                    </div>

                    <div
                        style="margin-top:1.5rem;padding:1.25rem;background:var(--c-surface);border:1px solid var(--c-border);border-radius:var(--radius-md);">
                        <p
                            style="font-family:var(--font-display);font-size:.8rem;font-weight:600;color:var(--c-white);margin-bottom:.3rem;">
                            Budget Picks Under 3M {{ $currency_symbol }}</p>
                        <p style="font-size:.78rem;color:var(--c-muted);margin-bottom:.85rem;">Popular daily drivers at
                            unbeatable prices.</p>
                        <a href="#" class="btn btn-outline" style="padding:.5rem 1rem;font-size:.78rem;">
                            Browse Budget Cars
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
    8. TESTIMONIALS
    ═══════════════════════════════════════════════════════ --}}
    <section id="testimonials" class="hp-section" aria-label="Customer testimonials">
        <div class="container">

            <div class="section-header">
                <div class="reveal">
                    <p class="section-label">Real Stories</p>
                    <h2 class="section-title">What Our <span>Members Say</span></h2>
                </div>
            </div>

            <div class="testimonials-grid">
                @php
                    $testimonials = [
                        ['quote' => 'Found my dream Honda Vezel in just 2 days. The seller was verified, the price was fair, and the whole process was smoother than I expected. Couldn\'t be happier.', 'name' => 'Kasun Perera', 'location' => 'Colombo', 'init' => 'KP', 'rating' => 5],
                        ['quote' => 'I listed my old Toyota Axio on a Friday evening. By Monday I had three serious inquiries and sold it for a great price. The free listing offer was a real bonus.', 'name' => 'Amali Fernando', 'location' => 'Kandy', 'init' => 'AF', 'rating' => 5],
                        ['quote' => 'The EMI calculator helped me figure out exactly what I could afford before I even started browsing. Ended up getting financed through their partner bank at a great rate.', 'name' => 'Ranjith Silva', 'location' => 'Galle', 'init' => 'RS', 'rating' => 5],
                    ];
                @endphp
                @foreach($testimonials as $i => $t)
                    <div class="testimonial-card reveal-scale" style="transition-delay:{{ $i * 0.12 }}s">
                        <div class="testimonial-stars">
                            @for($s = 0; $s < $t['rating']; $s++)
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="testimonial-quote">"{{ $t['quote'] }}"</p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">{{ $t['init'] }}</div>
                            <div class="testimonial-author-info">
                                <strong>{{ $t['name'] }}</strong>
                                <span>{{ $t['location'] }}, Sri Lanka</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Stats row --}}
            <div class="stats-row">
                @php
                    $stats = [
                        ['num' => '12,400', 'suf' => '+', 'label' => 'Active Listings'],
                        ['num' => '50,000', 'suf' => '+', 'label' => 'Happy Members'],
                        ['num' => '98', 'suf' => '%', 'label' => 'Verified Sellers'],
                        ['num' => '25', 'suf' => '', 'label' => 'Districts Covered'],
                    ];
                @endphp
                @foreach($stats as $s)
                    <div class="stat-block reveal">
                        <div class="stat-block-number">{{ $s['num'] }}<span>{{ $s['suf'] }}</span></div>
                        <div class="stat-block-label">{{ $s['label'] }}</div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
    9. SELL CTA
    ═══════════════════════════════════════════════════════ --}}
    <section id="sell-cta" class="hp-section--sm" aria-label="Sell your car CTA">
        <div class="container">
            <div class="sell-cta-wrap reveal-scale">
                <div class="sell-cta-text">
                    <h2>Ready to Sell<br>Your <span>Car?</span></h2>
                    <p>Join thousands of Sri Lankans who have successfully sold their vehicles through Karbnzol. Fast, safe,
                        and completely free to list.</p>
                    <div class="sell-cta-perks">
                        <div class="sell-cta-perk">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            Free to list for your first 30 days
                        </div>
                        <div class="sell-cta-perk">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            Reach 50,000+ verified buyers
                        </div>
                        <div class="sell-cta-perk">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            Instant valuation before you list
                        </div>
                        <div class="sell-cta-perk">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            Average sale time: under 7 days
                        </div>
                    </div>
                </div>
                <div class="sell-cta-actions">
                    <a href="#" class="btn btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        List My Car — Free
                    </a>
                    <a href="#val-tool" class="btn btn-outline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                        Get Instant Valuation
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        (function () {
            'use strict';

            /* ── Wait for GSAP ── */
            function initGSAP() {
                if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                    setTimeout(initGSAP, 80);
                    return;
                }

                gsap.registerPlugin(ScrollTrigger);

                /* ─────────────────────────────────
                   HERO entrance animations
                ───────────────────────────────── */
                const heroTl = gsap.timeline({ delay: 0.3 });

                // Hero bg parallax scale-in
                heroTl.fromTo('#heroBgImg',
                    { scale: 1.08, opacity: 0 },
                    { scale: 1, opacity: 0.38, duration: 1.6, ease: 'power3.out' },
                    0
                );

                // Stripe
                heroTl.fromTo('.hero-stripe',
                    { scaleY: 0, transformOrigin: 'top' },
                    { scaleY: 1, duration: 1.2, ease: 'expo.out' },
                    0.2
                );

                // Eyebrow
                heroTl.fromTo('#heroEyebrow',
                    { opacity: 0, y: 20 },
                    { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' },
                    0.5
                );

                // Headline — split by words
                const headlineEl = document.querySelector('.hero-headline');
                if (headlineEl) {
                    const words = headlineEl.innerHTML.split(/(?<=<\/?\w+>|\s)(?=\S)/);
                    heroTl.fromTo('#heroHeadline',
                        { opacity: 0, y: 40, skewY: 3 },
                        { opacity: 1, y: 0, skewY: 0, duration: 0.9, ease: 'power4.out' },
                        0.65
                    );
                }

                heroTl.fromTo('#heroSub',
                    { opacity: 0, y: 24 },
                    { opacity: 1, y: 0, duration: 0.75, ease: 'power3.out' },
                    0.85
                );

                heroTl.fromTo('#heroSearch',
                    { opacity: 0, y: 20, scale: 0.97 },
                    { opacity: 1, y: 0, scale: 1, duration: 0.7, ease: 'back.out(1.4)' },
                    1.05
                );

                heroTl.fromTo('#heroChips .hero-filter-chip',
                    { opacity: 0, y: 12, scale: 0.92 },
                    { opacity: 1, y: 0, scale: 1, duration: 0.5, stagger: 0.06, ease: 'back.out(1.6)' },
                    1.25
                );

                heroTl.fromTo('#heroCtas .btn',
                    { opacity: 0, y: 16 },
                    { opacity: 1, y: 0, duration: 0.55, stagger: 0.1, ease: 'power3.out' },
                    1.5
                );

                heroTl.fromTo('.hero-stat-pill',
                    { opacity: 0, x: 20 },
                    { opacity: 1, x: 0, duration: 0.5, stagger: 0.12, ease: 'power2.out' },
                    1.2
                );

                /* ─────────────────────────────────
                   Scroll-triggered reveals
                ───────────────────────────────── */

                // Generic .reveal elements
                gsap.utils.toArray('.reveal').forEach(function (el) {
                    gsap.fromTo(el,
                        { opacity: 0, y: 36 },
                        {
                            opacity: 1, y: 0,
                            duration: 0.75,
                            ease: 'power3.out',
                            delay: parseFloat(el.style.transitionDelay || 0),
                            scrollTrigger: {
                                trigger: el,
                                start: 'top 88%',
                                toggleActions: 'play none none none'
                            }
                        }
                    );
                });

                gsap.utils.toArray('.reveal-left').forEach(function (el) {
                    gsap.fromTo(el,
                        { opacity: 0, x: -50 },
                        {
                            opacity: 1, x: 0,
                            duration: 0.85,
                            ease: 'power3.out',
                            scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' }
                        }
                    );
                });

                gsap.utils.toArray('.reveal-right').forEach(function (el) {
                    gsap.fromTo(el,
                        { opacity: 0, x: 50 },
                        {
                            opacity: 1, x: 0,
                            duration: 0.85,
                            ease: 'power3.out',
                            scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' }
                        }
                    );
                });

                gsap.utils.toArray('.reveal-scale').forEach(function (el, i) {
                    gsap.fromTo(el,
                        { opacity: 0, scale: 0.93, y: 20 },
                        {
                            opacity: 1, scale: 1, y: 0,
                            duration: 0.7,
                            ease: 'back.out(1.2)',
                            delay: parseFloat(el.style.animationDelay || 0),
                            scrollTrigger: { trigger: el, start: 'top 90%', toggleActions: 'play none none none' }
                        }
                    );
                });

                /* ─────────────────────────────────
                   Hero parallax on scroll
                ───────────────────────────────── */
                gsap.to('.hero-bg-img', {
                    yPercent: 18,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '#hero',
                        start: 'top top',
                        end: 'bottom top',
                        scrub: true
                    }
                });

                gsap.to('.hero-content', {
                    yPercent: 22,
                    opacity: 0,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '#hero',
                        start: 'center top',
                        end: 'bottom top',
                        scrub: true
                    }
                });

                /* ─────────────────────────────────
                   Car card stagger on featured section
                ───────────────────────────────── */
                ScrollTrigger.create({
                    trigger: '#carGrid',
                    start: 'top 85%',
                    onEnter: function () {
                        gsap.fromTo('#carGrid .car-card',
                            { opacity: 0, y: 30 },
                            { opacity: 1, y: 0, duration: 0.6, stagger: { each: 0.1, from: 'start' }, ease: 'power3.out' }
                        );
                    }
                });

                /* ─────────────────────────────────
                   Stats count-up animation
                ───────────────────────────────── */
                const statNums = document.querySelectorAll('.stat-block-number');
                statNums.forEach(function (el) {
                    const raw = el.textContent.replace(/[^0-9,]/g, '').replace(/,/g, '');
                    const target = parseInt(raw, 10);
                    if (!target) return;
                    const suffix = el.querySelector('span') ? el.querySelector('span').textContent : '';
                    const formatted = el.textContent;

                    ScrollTrigger.create({
                        trigger: el,
                        start: 'top 85%',
                        onEnter: function () {
                            gsap.fromTo({ val: 0 },
                                {
                                    val: target,
                                    duration: 2,
                                    ease: 'power2.out',
                                    onUpdate: function () {
                                        const v = Math.round(this.targets()[0].val);
                                        el.innerHTML = v.toLocaleString() + '<span>' + suffix + '</span>';
                                    }
                                }
                            );
                        }
                    });
                });

                /* ─────────────────────────────────
                   How-it-works steps connector anim
                ───────────────────────────────── */
                gsap.fromTo('.how-steps::before',
                    { scaleX: 0, transformOrigin: 'left' },
                    {
                        scaleX: 1,
                        duration: 1.2,
                        ease: 'power3.out',
                        scrollTrigger: { trigger: '.how-steps', start: 'top 75%' }
                    }
                );

                gsap.fromTo('.how-step-number',
                    { opacity: 0, scale: 0.7 },
                    {
                        opacity: 1, scale: 1,
                        duration: 0.5,
                        stagger: 0.15,
                        ease: 'back.out(1.8)',
                        scrollTrigger: { trigger: '.how-steps', start: 'top 80%' }
                    }
                );
            }

            initGSAP();

            /* ── Category strip active tab ── */
            const catTabs = document.querySelectorAll('.cat-tab');
            catTabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    catTabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                });
            });

            /* ── How it works tab toggle ── */
            const buySteps = document.getElementById('howBuySteps');
            const sellSteps = document.getElementById('howSellSteps');
            const buyTab = document.getElementById('howTabBuy');
            const sellTab = document.getElementById('howTabSell');

            if (buyTab && sellTab) {
                buyTab.addEventListener('click', function () {
                    buyTab.classList.add('active');
                    sellTab.classList.remove('active');
                    buySteps.style.display = 'grid';
                    sellSteps.style.display = 'none';
                    gsap.fromTo(buySteps.querySelectorAll('.how-step'),
                        { opacity: 0, y: 20 },
                        { opacity: 1, y: 0, duration: 0.5, stagger: 0.1, ease: 'power3.out' }
                    );
                });

                sellTab.addEventListener('click', function () {
                    sellTab.classList.add('active');
                    buyTab.classList.remove('active');
                    buySteps.style.display = 'none';
                    sellSteps.style.display = 'grid';
                    gsap.fromTo(sellSteps.querySelectorAll('.how-step'),
                        { opacity: 0, y: 20 },
                        { opacity: 1, y: 0, duration: 0.5, stagger: 0.1, ease: 'power3.out' }
                    );
                });
            }

            /* ── Hero filter chip toggle ── */
            document.querySelectorAll('.hero-filter-chip').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    document.querySelectorAll('.hero-filter-chip').forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                });
            });

            /* ── Search on Enter key ── */
            const heroInput = document.getElementById('heroSearchInput');
            const heroBtn = document.getElementById('heroSearchBtn');
            if (heroInput && heroBtn) {
                heroInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') heroBtn.click();
                });
            }

            /* ─────────────────────────────────
               EMI CALCULATOR
            ───────────────────────────────── */
            function calcEMI() {
                const price = parseFloat(document.getElementById('emiPrice').value) || 0;
                const down = parseFloat(document.getElementById('emiDown').value) || 0;
                const ratePA = parseFloat(document.getElementById('emiRate').value) || 0;
                const months = parseInt(document.getElementById('emiTerm').value) || 36;

                const principal = Math.max(price - down, 0);
                const rateM = ratePA / 100 / 12;

                let monthly = 0;
                if (rateM > 0 && principal > 0) {
                    monthly = principal * rateM * Math.pow(1 + rateM, months) / (Math.pow(1 + rateM, months) - 1);
                } else if (principal > 0) {
                    monthly = principal / months;
                }

                const totalPaid = monthly * months;
                const totalInterest = Math.max(totalPaid - principal, 0);

                const currencySymbol = "{{ $currency_symbol }}";
                const currencyPos = "{{ $currency_position }}";
                const decimals = {{ $decimal_count }};

                const fmt = n => {
                    const val = Math.round(n).toLocaleString('en-LK', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });
                    return currencyPos === 'before' ? currencySymbol + ' ' + val : val + ' ' + currencySymbol;
                };

                const monthlyEl = document.getElementById('emiMonthly');
                const interestEl = document.getElementById('emiInterest');

                if (monthlyEl) monthlyEl.innerHTML = fmt(monthly) + '<small>/mo</small>';
                if (interestEl) interestEl.textContent = fmt(totalInterest);
            }

            ['emiPrice', 'emiDown', 'emiRate', 'emiTerm'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', calcEMI);
            });

            calcEMI(); // run on load

            /* ─────────────────────────────────
               VALUATION TOOL
            ───────────────────────────────── */
            const valBtn = document.getElementById('getValuationBtn');
            if (valBtn) {
                valBtn.addEventListener('click', function () {
                    const make = document.getElementById('valMake').value;
                    const model = document.getElementById('valModel').value;
                    const year = document.getElementById('valYear').value;

                    if (!make || !model || !year) {
                        valBtn.textContent = 'Please fill in Make, Model & Year';
                        valBtn.style.background = '#d97706';
                        setTimeout(() => {
                            valBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg> Get Instant Valuation';
                            valBtn.style.background = '';
                        }, 2000);
                        return;
                    }

                    // Redirect to search with params (real valuation would be an API call)
                    window.location = '#';
                });
            }

            /* ─────────────────────────────────
               Car card save button animation
            ───────────────────────────────── */
            document.querySelectorAll('.car-card-save').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const saved = btn.dataset.saved === '1';
                    btn.dataset.saved = saved ? '0' : '1';
                    btn.style.color = saved ? '' : 'var(--c-accent)';
                    btn.style.background = saved ? '' : 'rgba(232,56,13,.2)';
                    btn.style.borderColor = saved ? '' : 'var(--c-accent)';

                    if (typeof gsap !== 'undefined') {
                        gsap.fromTo(btn, { scale: 0.8 }, { scale: 1, duration: 0.4, ease: 'back.out(3)' });
                    }

                    @auth
                        fetch('#', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            body: JSON.stringify({ car_id: btn.dataset.carId })
                        });
                    @endauth
                    });
            });

        })();
    </script>
@endpush
