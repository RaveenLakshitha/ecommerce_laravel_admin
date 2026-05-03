<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ $store_name ?? 'Karbnzol' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Playfair Display (elegant serif) + Plus Jakarta Sans (refined body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    <style>
        /* ─── VARIABLES ─────────────────────────────────────────── */
        :root {
            --blush: #f9ece8;
            /* warm blush bg */
            --blush-deep: #f0d8d0;
            /* deeper blush */
            --petal: #e8c4b8;
            /* petal pink */
            --rose: #c4785a;
            /* dusty rose accent */
            --rose-lt: #d9957a;
            /* lighter rose */
            --sage: #7a9e7e;
            /* sage green */
            --sage-lt: #a8c5ab;
            /* light sage */
            --sage-bg: rgba(122, 158, 126, 0.08);
            --linen: #faf6f2;
            /* near-white linen */
            --stone: #4a3f38;
            /* dark warm brown (ink) */
            --taupe: #8c7b72;
            /* secondary text */
            --bone: #e8ddd8;
            /* border/line */
            --white: #ffffff;

            --font-display: 'Playfair Display', Georgia, serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;

            --nav-h: 76px;
            --ease-bloom: cubic-bezier(0.34, 1.56, 0.64, 1);
            --ease-gentle: cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* ─── RESET ─────────────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: var(--font-body);
            background: var(--linen);
            color: var(--stone);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: var(--linen);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--petal);
            border-radius: 10px;
        }

        /* ─── PAGE TRANSITION ───────────────────────────────────── */
        #page-wipe {
            position: fixed;
            inset: 0;
            background: var(--blush-deep);
            z-index: 9000;
            transform: scaleY(1);
            transform-origin: top;
            pointer-events: none;
        }

        /* ─── PROMO BAR ─────────────────────────────────────────── */
        .promo-bar {
            background: var(--sage);
            color: var(--white);
            text-align: center;
            padding: 0.6rem 1rem;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
        }

        .promo-bar span {
            opacity: 0.8;
        }

        .promo-close {
            background: none;
            border: none;
            color: var(--white);
            opacity: 0.6;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
            padding: 0 0.5rem;
            transition: opacity 0.2s;
            position: absolute;
            right: 1rem;
        }

        .promo-close:hover {
            opacity: 1;
        }

        .promo-bar {
            position: relative;
        }

        /* ─── HEADER ────────────────────────────────────────────── */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 200;
            height: var(--nav-h);
            background: rgba(250, 246, 242, 0.9);
            backdrop-filter: blur(16px) saturate(1.3);
            -webkit-backdrop-filter: blur(16px) saturate(1.3);
            border-bottom: 1px solid var(--bone);
            transition: box-shadow 0.35s var(--ease-gentle), background 0.35s;
        }

        .site-header.scrolled {
            background: rgba(250, 246, 242, 0.97);
            box-shadow: 0 4px 32px rgba(74, 63, 56, 0.06);
        }

        .nav-wrap {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 2.5rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0;
            text-decoration: none;
        }

        .nav-logo-main {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 400;
            letter-spacing: 0.12em;
            color: var(--stone);
            line-height: 1;
            text-transform: uppercase;
        }

        .nav-logo-sub {
            font-size: 0.55rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--taupe);
            font-weight: 500;
            margin-top: 2px;
        }

        /* Center nav */
        .nav-center {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-center a {
            padding: 0.5rem 1.1rem;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: var(--taupe);
            border-radius: 100px;
            transition: color 0.25s, background 0.25s;
            position: relative;
        }

        .nav-center a:hover {
            color: var(--stone);
            background: var(--blush);
        }

        .nav-center a.active {
            color: var(--rose);
        }

        /* Badge on nav */
        .nav-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--sage);
            color: var(--white);
            font-size: 0.45rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.15rem 0.4rem;
            border-radius: 3px;
            position: absolute;
            top: 0.1rem;
            right: 0.3rem;
        }

        /* Right actions */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-icon-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--taupe);
            transition: color 0.25s, background 0.25s;
            position: relative;
        }

        .nav-icon-btn:hover {
            color: var(--stone);
            background: var(--blush);
        }

        .nav-cart-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.25rem 0.55rem 1rem;
            background: var(--stone);
            color: var(--linen);
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            margin-left: 0.5rem;
            transition: background 0.25s, transform 0.2s;
        }

        .nav-cart-btn:hover {
            background: var(--rose);
            transform: translateY(-1px);
        }

        .cart-badge-pill {
            background: var(--rose);
            color: var(--white);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 0.6rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.25s;
        }

        .nav-cart-btn:hover .cart-badge-pill {
            background: var(--linen);
            color: var(--rose);
        }

        .nav-auth-btn {
            padding: 0.55rem 1.25rem;
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: var(--taupe);
            border: 1.5px solid var(--bone);
            transition: border-color 0.25s, color 0.25s, background 0.25s;
        }

        .nav-auth-btn:hover {
            border-color: var(--petal);
            color: var(--stone);
            background: var(--blush);
        }

        .nav-auth-btn.primary {
            background: var(--rose);
            color: var(--white);
            border-color: var(--rose);
        }

        .nav-auth-btn.primary:hover {
            background: var(--rose-lt);
            border-color: var(--rose-lt);
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            background: none;
            border: none;
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 1.5px;
            background: var(--stone);
            border-radius: 2px;
            transition: all 0.35s var(--ease-gentle);
            transform-origin: center;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(6.5px) rotate(45deg);
            background: var(--rose);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-6.5px) rotate(-45deg);
            background: var(--rose);
        }

        /* Mobile drawer */
        .mobile-drawer {
            position: fixed;
            top: var(--nav-h);
            right: 0;
            width: min(300px, 88vw);
            height: calc(100vh - var(--nav-h));
            background: var(--linen);
            border-left: 1px solid var(--bone);
            z-index: 199;
            padding: 2rem 1.5rem;
            transform: translateX(110%);
            transition: transform 0.45s var(--ease-gentle);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .mobile-drawer.open {
            transform: translateX(0);
        }

        .mobile-drawer a {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 400;
            color: var(--stone);
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--bone);
            display: block;
            transition: color 0.2s, padding-left 0.25s;
        }

        .mobile-drawer a:hover {
            color: var(--rose);
            padding-left: 0.5rem;
        }

        .drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(74, 63, 56, 0.25);
            backdrop-filter: blur(3px);
            z-index: 198;
            display: none;
        }

        .drawer-overlay.open {
            display: block;
        }

        /* ─── MAIN ──────────────────────────────────────────────── */
        main {
            min-height: 80vh;
        }

        /* ─── FOOTER ────────────────────────────────────────────── */
        .site-footer {
            background: var(--stone);
            color: var(--linen);
            padding: 5rem 0 0;
            position: relative;
            overflow: hidden;
        }

        /* Decorative botanical SVG bg */
        .footer-botanical {
            position: absolute;
            top: 0;
            right: 0;
            width: 480px;
            opacity: 0.04;
            pointer-events: none;
        }

        .footer-inner {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 2.5rem;
        }

        /* Top: logo + tagline wide */
        .footer-top-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(250, 246, 242, 0.1);
            margin-bottom: 3.5rem;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .footer-logo-lg {
            font-family: var(--font-display);
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 400;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(250, 246, 242, 0.9);
            line-height: 1;
        }

        .footer-logo-lg em {
            font-style: italic;
            color: var(--rose-lt);
        }

        .footer-tagline-block {
            max-width: 300px;
        }

        .footer-tagline-block p {
            font-size: 0.875rem;
            color: rgba(250, 246, 242, 0.45);
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .footer-socials {
            display: flex;
            gap: 0.625rem;
        }

        .footer-social {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid rgba(250, 246, 242, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(250, 246, 242, 0.4);
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            transition: border-color 0.25s, color 0.25s, background 0.25s;
        }

        .footer-social:hover {
            border-color: var(--rose-lt);
            color: var(--rose-lt);
            background: rgba(217, 149, 122, 0.1);
        }

        /* Footer columns */
        .footer-cols {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2.5rem;
            padding-bottom: 3.5rem;
        }

        .footer-col-title {
            font-family: var(--font-display);
            font-size: 0.9375rem;
            font-weight: 500;
            color: rgba(250, 246, 242, 0.85);
            margin-bottom: 1.25rem;
            letter-spacing: 0.04em;
        }

        .footer-col-links {
            list-style: none;
        }

        .footer-col-links li {
            margin-bottom: 0.6rem;
        }

        .footer-col-links a {
            font-size: 0.8125rem;
            color: rgba(250, 246, 242, 0.4);
            letter-spacing: 0.02em;
            transition: color 0.2s, padding-left 0.2s;
            display: inline-block;
        }

        .footer-col-links a:hover {
            color: var(--rose-lt);
            padding-left: 5px;
        }

        /* Contact */
        .footer-contact p {
            font-size: 0.8125rem;
            color: rgba(250, 246, 242, 0.4);
            line-height: 1.9;
        }

        .footer-contact a {
            color: var(--rose-lt);
        }

        .footer-contact a:hover {
            text-decoration: underline;
        }

        /* Newsletter in footer */
        .footer-nl-label {
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(250, 246, 242, 0.3);
            margin-bottom: 0.6rem;
        }

        .footer-nl-form {
            display: flex;
            border: 1px solid rgba(250, 246, 242, 0.15);
            border-radius: 100px;
            overflow: hidden;
            transition: border-color 0.25s;
        }

        .footer-nl-form:focus-within {
            border-color: var(--rose-lt);
        }

        .footer-nl-form input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 0.6rem 1rem;
            font-family: var(--font-body);
            font-size: 0.78rem;
            color: var(--linen);
        }

        .footer-nl-form input::placeholder {
            color: rgba(250, 246, 242, 0.25);
        }

        .footer-nl-form button {
            background: var(--rose);
            border: none;
            color: var(--white);
            padding: 0.6rem 1rem;
            font-family: var(--font-body);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
            border-radius: 0 100px 100px 0;
        }

        .footer-nl-form button:hover {
            background: var(--rose-lt);
        }

        /* Bottom bar */
        .footer-bottom-bar {
            border-top: 1px solid rgba(250, 246, 242, 0.08);
            padding: 1.5rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-copy {
            font-size: 0.75rem;
            color: rgba(250, 246, 242, 0.22);
            letter-spacing: 0.06em;
        }

        .footer-legal-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-legal-links a {
            font-size: 0.7rem;
            color: rgba(250, 246, 242, 0.22);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: color 0.2s;
        }

        .footer-legal-links a:hover {
            color: var(--rose-lt);
        }

        /* ─── GSAP INIT STATES ──────────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-20px);
        }

        /* ─── RESPONSIVE ────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .footer-cols {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .nav-center {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .nav-auth-btn,
            .nav-icon-btn:not(.nav-cart-icon) {
                display: none;
            }

            .footer-top-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-cols {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .nav-wrap {
                padding: 0 1rem;
            }

            .footer-inner {
                padding: 0 1rem;
            }

            .footer-cols {
                grid-template-columns: 1fr;
            }

            .footer-bottom-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* ─── UTILITY ───────────────────────────────────────────── */
        .rose {
            color: var(--rose);
        }

        .sage-text {
            color: var(--sage);
        }
    </style>
</head>

<body>
    <!-- Page wipe -->
    <div id="page-wipe"></div>

    <!-- ── PROMO BAR ──────────────────────────────────────────── -->
    <div class="promo-bar" id="promoBar">
        <span>🌿 Free shipping on orders over Rs. 5,000 &nbsp;·&nbsp; Use code <strong>BLOOM15</strong> for 15%
            off</span>
        <button class="promo-close" id="promoClose" aria-label="Close">×</button>
    </div>

    <!-- ── HEADER ─────────────────────────────────────────────── -->
    <header class="site-header" id="siteHeader">
        <div class="nav-wrap">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="nav-logo">
                <span class="nav-logo-main">{{ $store_name ?? 'Karbnzol' }}</span>
                <span class="nav-logo-sub">Est. 2020 · Sri Lanka</span>
            </a>

            <!-- Center links -->
            <nav class="nav-center" aria-label="Main">
                <a href="{{ route('frontend.products.index') }}">Shop</a>
                <a href="#" style="position:relative;">
                    New In
                    <span class="nav-badge">New</span>
                </a>
                <a href="#">Collections</a>
                <a href="#">Lookbook</a>
                <a href="{{ route('frontend.about') }}">About</a>
                <a href="{{ route('frontend.about') }}#contact-info">Contact</a>
            </nav>

            <!-- Right actions -->
            <div class="nav-right">
                <a href="#" class="nav-icon-btn" aria-label="Search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </a>
                <a href="#" class="nav-icon-btn" aria-label="Wishlist">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </a>

                @auth('web')
                    <a href="{{ route('account.dashboard') }}" class="nav-auth-btn">My Account</a>
                @else
                    <a href="{{ route('login') }}" class="nav-auth-btn">Sign In</a>
                    <a href="{{ route('register') }}" class="nav-auth-btn primary">Join Us</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="nav-cart-btn" aria-label="Shopping bag">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    Bag
                    <span class="cart-badge-pill">0</span>
                </a>

                <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile drawer overlay -->
    <div class="drawer-overlay" id="drawerOverlay"></div>

    <!-- Mobile drawer -->
    <nav class="mobile-drawer" id="mobileDrawer" aria-label="Mobile navigation">
        <a href="{{ route('frontend.products.index') }}">Shop</a>
        <a href="#">New In</a>
        <a href="#">Collections</a>
        <a href="#">Lookbook</a>
        <a href="{{ route('frontend.about') }}">About</a>
        <a href="{{ route('frontend.about') }}#contact-info">Contact</a>
        <a href="{{ route('cart.index') }}">Bag (0)</a>
        @auth('web')
            <a href="{{ route('account.dashboard') }}">My Account</a>
        @else
            <a href="{{ route('login') }}">Sign In</a>
            <a href="{{ route('register') }}" style="color: var(--rose)!important;">Join Us →</a>
        @endauth
    </nav>

    <!-- ── MAIN ───────────────────────────────────────────────── -->
    <main>@yield('content')</main>

    <!-- ── FOOTER ─────────────────────────────────────────────── -->
    <footer class="site-footer">
        <!-- Botanical bg decoration -->
        <svg class="footer-botanical" viewBox="0 0 400 500" fill="none" xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true">
            <path
                d="M200 480 Q180 400 120 350 Q60 300 80 220 Q100 140 200 100 Q300 60 340 140 Q380 220 320 300 Q260 380 280 480"
                stroke="white" stroke-width="2" fill="none" />
            <ellipse cx="120" cy="280" rx="60" ry="35" transform="rotate(-30 120 280)" stroke="white" stroke-width="1.5"
                fill="none" />
            <ellipse cx="280" cy="200" rx="50" ry="30" transform="rotate(20 280 200)" stroke="white" stroke-width="1.5"
                fill="none" />
            <ellipse cx="200" cy="160" rx="45" ry="25" transform="rotate(-10 200 160)" stroke="white" stroke-width="1.5"
                fill="none" />
            <path d="M200 480 Q220 400 280 360 Q340 320 320 240" stroke="white" stroke-width="1.5" fill="none" />
        </svg>

        <div class="footer-inner">

            <!-- Top row: big logo + socials -->
            <div class="footer-top-row reveal">
                <h2 class="footer-logo-lg">
                    Karbn<em>zol</em>
                </h2>
                <div class="footer-tagline-block">
                    <p>Thoughtfully designed fashion for those who appreciate beauty in every detail. Made with care,
                        worn with love.</p>
                    <div class="footer-socials">
                        <a class="footer-social" href="#" aria-label="Instagram">IG</a>
                        <a class="footer-social" href="#" aria-label="Facebook">FB</a>
                        <a class="footer-social" href="#" aria-label="Pinterest">PT</a>
                        <a class="footer-social" href="#" aria-label="TikTok">TK</a>
                    </div>
                </div>
            </div>

            <!-- Columns -->
            <div class="footer-cols">
                <div class="reveal">
                    <p class="footer-col-title">Explore</p>
                    <ul class="footer-col-links">
                        <li><a href="{{ route('frontend.products.index') }}">All Products</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Collections</a></li>
                        <li><a href="#">Lookbook</a></li>
                        <li><a href="#">Sale</a></li>
                    </ul>
                </div>
                <div class="reveal">
                    <p class="footer-col-title">Help</p>
                    <ul class="footer-col-links">
                        <li><a href="#">Shipping & Returns</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Track My Order</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="{{ route('frontend.about') }}">About Us</a></li>
                        <li><a href="{{ route('frontend.about') }}#contact-info">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-contact reveal">
                    <p class="footer-col-title">Get in Touch</p>
                    <p>
                        <a
                            href="tel:{{ $store_phone ?? '+94112345678' }}">{{ $store_phone ?? '+94 11 234 5678' }}</a><br>
                        <a href="mailto:{{ $store_email ?? 'hello@karbnzol.com' }}">{{ $store_email ??
                            'hello@karbnzol.com' }}</a><br><br>
                        Monday – Saturday<br>
                        9:00 AM – 6:00 PM
                    </p>
                </div>
                <div class="reveal">
                    <p class="footer-col-title">Newsletter</p>
                    <p style="font-size:0.8rem;color:rgba(250,246,242,0.38);line-height:1.7;margin-bottom:1rem;">
                        Join our community for new arrivals and exclusive offers.
                    </p>
                    <p class="footer-nl-label">Your email</p>
                    <form class="footer-nl-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="hello@you.com" required>
                        <button type="submit">→</button>
                    </form>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="footer-bottom-bar">
                <p class="footer-copy">© {{ date('Y') }} {{ $store_name ?? 'Karbnzol' }}. All rights reserved.</p>
                <nav class="footer-legal-links" aria-label="Legal">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Cookies</a>
                </nav>
            </div>
        </div>
    </footer>

    <!-- ── SCRIPTS ─────────────────────────────────────────────── -->
    <script>
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                const wipe = document.getElementById('page-wipe');
                if (wipe) {
                    wipe.style.display = 'none';
                }
            }
        });

        window.addEventListener('load', () => {

            /* ── Promo bar close ─────────────────────────────────── */
            const promoClose = document.getElementById('promoClose');
            const promoBar = document.getElementById('promoBar');
            if (promoClose && promoBar) {
                promoClose.addEventListener('click', () => {
                    if (typeof gsap !== 'undefined') {
                        gsap.to(promoBar, {
                            height: 0, opacity: 0, padding: 0, duration: 0.3, ease: 'power2.inOut',
                            onComplete: () => promoBar.style.display = 'none'
                        });
                    } else {
                        promoBar.style.display = 'none';
                    }
                });
            }

            /* ── Sticky header ───────────────────────────────────── */
            const header = document.getElementById('siteHeader');
            window.addEventListener('scroll', () => {
                header.classList.toggle('scrolled', window.scrollY > 40);
            }, { passive: true });

            /* ── Mobile drawer ───────────────────────────────────── */
            const burger = document.getElementById('hamburger');
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('drawerOverlay');
            function toggleDrawer(open) {
                burger.classList.toggle('open', open);
                drawer.classList.toggle('open', open);
                overlay.classList.toggle('open', open);
                burger.setAttribute('aria-expanded', open);
                document.body.style.overflow = open ? 'hidden' : '';
            }
            if (burger) {
                burger.addEventListener('click', () => toggleDrawer(!drawer.classList.contains('open')));
                overlay.addEventListener('click', () => toggleDrawer(false));
                drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', () => toggleDrawer(false)));
            }

            /* ── GSAP ────────────────────────────────────────────── */
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            /* Page enter — blush reveal from top */
            gsap.to('#page-wipe', {
                scaleY: 0,
                duration: 1.0,
                ease: 'power4.inOut',
                transformOrigin: 'bottom',
                clearProps: 'all',
                onComplete: () => document.getElementById('page-wipe').style.display = 'none'
            });

            /* Header entrance */
            gsap.from('#siteHeader', { y: -90, opacity: 0, duration: 0.7, ease: 'power3.out', delay: 0.65 });

            /* Footer reveals */
            document.querySelectorAll('.reveal').forEach((el, i) => {
                gsap.to(el, {
                    opacity: 1, y: 0,
                    duration: 0.8,
                    ease: 'power3.out',
                    delay: i * 0.1,
                    scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' }
                });
            });

            /* Cart button wiggle on hover */
            const cartBtn = document.querySelector('.nav-cart-btn');
            if (cartBtn) {
                cartBtn.addEventListener('mouseenter', () => {
                    gsap.fromTo(cartBtn, { rotate: 0 }, {
                        rotate: -3, duration: 0.12, ease: 'power1.out',
                        onComplete: () => gsap.to(cartBtn, { rotate: 0, duration: 0.4, ease: 'elastic.out(1.6, 0.4)' })
                    });
                });
            }

            /* Page leave — blush wipe */
            document.querySelectorAll('a[href]').forEach(link => {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto') || href.startsWith('tel') || link.target === '_blank') return;
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const dest = href;
                    const wipe = document.getElementById('page-wipe');
                    wipe.style.display = 'block';
                    wipe.style.transform = 'scaleY(0)';
                    wipe.style.transformOrigin = 'top';
                    gsap.to(wipe, {
                        scaleY: 1, duration: 0.6, ease: 'power4.inOut',
                        onComplete: () => window.location.href = dest
                    });
                });
            });
        });
    </script>
</body>

</html>

