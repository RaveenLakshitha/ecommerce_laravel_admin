<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ $store_name ?? 'KARBNZOL' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bebas Neue (condensed display) + Syne (geometric body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    {{-- GSAP + ScrollTrigger + SplitText (via CDN) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    <style>
        /* ═══════════════════════════════════════════════════════════
           CSS VARIABLES — NOIR BRUTALIST
        ═══════════════════════════════════════════════════════════ */
        :root {
            --void: #080808;
            /* near-black base */
            --void-2: #0f0f0f;
            /* cards / panels */
            --void-3: #161616;
            /* hover states */
            --grid-ln: #1f1f1f;
            /* subtle grid lines */
            --volt: #c8ff00;
            /* electric chartreuse accent */
            --volt-dim: #8cb500;
            /* muted volt */
            --volt-bg: rgba(200, 255, 0, 0.06);
            /* volt tint */
            --ash: #888888;
            /* secondary text */
            --smoke: #444444;
            /* borders */
            --bone: #e8e8e8;
            /* primary text */
            --white: #ffffff;

            --font-display: 'Bebas Neue', 'Arial Narrow', sans-serif;
            --font-body: 'Syne', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;

            --nav-h: 64px;
            --ease-harsh: cubic-bezier(0.16, 1, 0.3, 1);
            --ease-snap: cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* ═══════════════════════════════════════════════════════════
           RESET + BASE
        ═══════════════════════════════════════════════════════════ */
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
            cursor: none;
        }

        body {
            font-family: var(--font-body);
            background: var(--void);
            color: var(--bone);
            overflow-x: hidden;
            /* Dot-grid background texture */
            background-image: radial-gradient(circle, #1a1a1a 1px, transparent 1px);
            background-size: 32px 32px;
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
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--void);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--volt);
        }

        /* ═══════════════════════════════════════════════════════════
           CUSTOM CURSOR
        ═══════════════════════════════════════════════════════════ */
        #cursor {
            position: fixed;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border: 1.5px solid var(--volt);
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s, background 0.2s, border-radius 0.2s;
            mix-blend-mode: difference;
        }

        #cursor-fill {
            position: fixed;
            top: 0;
            left: 0;
            width: 4px;
            height: 4px;
            background: var(--volt);
            pointer-events: none;
            z-index: 10000;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            transition: width 0.1s, height 0.1s;
        }

        body.cursor-hover #cursor {
            width: 48px;
            height: 48px;
            background: var(--volt-bg);
            border-radius: 2px;
        }

        /* ═══════════════════════════════════════════════════════════
           PAGE TRANSITION
        ═══════════════════════════════════════════════════════════ */
        #page-wipe {
            position: fixed;
            inset: 0;
            background: var(--volt);
            z-index: 9000;
            transform: scaleX(1);
            transform-origin: left;
            pointer-events: none;
        }

        /* ═══════════════════════════════════════════════════════════
           TICKER / MARQUEE TOP BAR
        ═══════════════════════════════════════════════════════════ */
        .top-ticker {
            background: var(--volt);
            overflow: hidden;
            height: 32px;
            display: flex;
            align-items: center;
        }

        .ticker-track {
            display: flex;
            align-items: center;
            animation: ticker-roll 22s linear infinite;
            white-space: nowrap;
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 1.5rem;
            padding: 0 2rem;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--void);
        }

        .ticker-sep {
            width: 4px;
            height: 4px;
            background: rgba(8, 8, 8, 0.4);
            transform: rotate(45deg);
            flex-shrink: 0;
        }

        @keyframes ticker-roll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        /* ═══════════════════════════════════════════════════════════
           HEADER / NAV
        ═══════════════════════════════════════════════════════════ */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 200;
            height: var(--nav-h);
            background: rgba(8, 8, 8, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--grid-ln);
            transition: border-color 0.3s;
        }

        .site-header.scrolled {
            border-bottom-color: var(--smoke);
        }

        .nav-inner {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 100%;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 2rem;
        }

        /* Logo — brutalist wordmark */
        .nav-logo {
            font-family: var(--font-display);
            font-size: 2rem;
            letter-spacing: 0.12em;
            color: var(--white);
            line-height: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-logo-dot {
            width: 8px;
            height: 8px;
            background: var(--volt);
            border-radius: 0;
            /* sharp square */
            transform: rotate(45deg);
            flex-shrink: 0;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: rotate(45deg) scale(1);
            }

            50% {
                opacity: 0.5;
                transform: rotate(45deg) scale(0.7);
            }
        }

        /* Center nav links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0;
            list-style: none;
        }

        .nav-links a {
            display: block;
            padding: 0 1.25rem;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--ash);
            position: relative;
            height: var(--nav-h);
            display: flex;
            align-items: center;
            border-right: 1px solid var(--grid-ln);
            transition: color 0.2s, background 0.2s;
        }

        .nav-links li:first-child a {
            border-left: 1px solid var(--grid-ln);
        }

        .nav-links a:hover {
            color: var(--volt);
            background: var(--volt-bg);
        }

        .nav-links a.active {
            color: var(--volt);
        }

        /* Right actions */
        .nav-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0;
        }

        .nav-action-link {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0 1.125rem;
            height: var(--nav-h);
            font-family: var(--font-mono);
            font-size: 0.6rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--ash);
            border-left: 1px solid var(--grid-ln);
            transition: color 0.2s, background 0.2s;
        }

        .nav-action-link:hover {
            color: var(--volt);
            background: var(--volt-bg);
        }

        .nav-action-link svg {
            flex-shrink: 0;
        }

        /* Cart CTA — volt filled */
        .nav-cart {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0 1.5rem;
            height: var(--nav-h);
            background: var(--volt);
            color: var(--void);
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            transition: background 0.2s, color 0.2s;
            border-left: 1px solid var(--volt);
        }

        .nav-cart:hover {
            background: var(--white);
        }

        .cart-num {
            background: var(--void);
            color: var(--volt);
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 700;
            border-radius: 0;
        }

        /* Register CTA */
        .nav-register {
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            height: var(--nav-h);
            border-left: 1px solid var(--grid-ln);
            background: transparent;
            color: var(--bone);
            font-family: var(--font-mono);
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            transition: color 0.2s, background 0.2s;
        }

        .nav-register:hover {
            color: var(--volt);
            background: var(--volt-bg);
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: none;
            padding: 4px;
            background: none;
            border: none;
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 1.5px;
            background: var(--bone);
            transition: all 0.3s var(--ease-harsh);
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(6.5px) rotate(45deg);
            background: var(--volt);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-6.5px) rotate(-45deg);
            background: var(--volt);
        }

        /* Mobile drawer */
        .mobile-drawer {
            position: fixed;
            top: calc(var(--nav-h) + 32px);
            left: 0;
            right: 0;
            height: calc(100vh - var(--nav-h) - 32px);
            background: var(--void);
            z-index: 199;
            border-top: 1px solid var(--smoke);
            transform: translateY(-110%);
            transition: transform 0.5s var(--ease-harsh);
            overflow-y: auto;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .mobile-drawer.open {
            transform: translateY(0);
        }

        .mobile-drawer a {
            font-family: var(--font-display);
            font-size: 3rem;
            letter-spacing: 0.08em;
            color: var(--bone);
            display: block;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--grid-ln);
            transition: color 0.2s, letter-spacing 0.3s;
        }

        .mobile-drawer a:hover {
            color: var(--volt);
            letter-spacing: 0.14em;
        }

        /* ═══════════════════════════════════════════════════════════
           MAIN
        ═══════════════════════════════════════════════════════════ */
        main {
            min-height: 80vh;
        }

        /* ═══════════════════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════════════════ */
        .site-footer {
            background: var(--void-2);
            border-top: 1px solid var(--grid-ln);
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        /* Big footer wordmark background text */
        .footer-bg-text {
            position: absolute;
            bottom: -0.15em;
            left: -0.02em;
            font-family: var(--font-display);
            font-size: clamp(8rem, 20vw, 18rem);
            letter-spacing: -0.02em;
            color: transparent;
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.04);
            pointer-events: none;
            white-space: nowrap;
            user-select: none;
            line-height: 1;
        }

        .footer-top {
            max-width: 1600px;
            margin: 0 auto;
            padding: 5rem 2rem 4rem;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            border-bottom: 1px solid var(--grid-ln);
            position: relative;
            z-index: 1;
        }

        /* Brand col */
        .footer-brand {}

        .footer-wordmark {
            font-family: var(--font-display);
            font-size: 4rem;
            letter-spacing: 0.1em;
            color: var(--white);
            line-height: 1;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-wordmark .volt-dot {
            width: 10px;
            height: 10px;
            background: var(--volt);
            transform: rotate(45deg);
        }

        .footer-tagline {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            color: var(--ash);
            text-transform: uppercase;
            line-height: 2;
            max-width: 280px;
            margin-bottom: 2rem;
        }

        /* Social icons — square brutal style */
        .footer-socials {
            display: flex;
            gap: 0;
        }

        .social-sq {
            width: 40px;
            height: 40px;
            border: 1px solid var(--smoke);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-mono);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--ash);
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }

        .social-sq:hover {
            background: var(--volt);
            color: var(--void);
            border-color: var(--volt);
        }

        .social-sq+.social-sq {
            border-left: none;
        }

        /* Footer nav cols */
        .footer-col {}

        .footer-col-label {
            font-family: var(--font-mono);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--volt);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-col-label::before {
            content: '';
            width: 16px;
            height: 1px;
            background: var(--volt);
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 0.5rem;
        }

        .footer-col ul a {
            font-size: 0.875rem;
            color: var(--ash);
            letter-spacing: 0.04em;
            transition: color 0.2s, padding-left 0.2s;
            display: inline-block;
        }

        .footer-col ul a:hover {
            color: var(--bone);
            padding-left: 8px;
        }

        /* Contact col */
        .footer-contact-info {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--ash);
            line-height: 2;
            margin-bottom: 1.5rem;
        }

        .footer-contact-info a {
            color: var(--volt);
        }

        .footer-contact-info a:hover {
            text-decoration: underline;
        }

        /* Newsletter inline */
        .footer-nl-label {
            font-family: var(--font-mono);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--ash);
            margin-bottom: 0.75rem;
        }

        .footer-nl-form {
            display: flex;
            border: 1px solid var(--smoke);
        }

        .footer-nl-form input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 0.6rem 0.875rem;
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--bone);
            letter-spacing: 0.08em;
        }

        .footer-nl-form input::placeholder {
            color: var(--smoke);
        }

        .footer-nl-form:focus-within {
            border-color: var(--volt);
        }

        .footer-nl-form button {
            background: var(--volt);
            border: none;
            color: var(--void);
            padding: 0.6rem 1rem;
            font-family: var(--font-mono);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: none;
            transition: background 0.2s;
        }

        .footer-nl-form button:hover {
            background: var(--white);
        }

        /* Footer bottom bar */
        .footer-bottom {
            max-width: 1600px;
            margin: 0 auto;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .footer-copy {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            color: var(--smoke);
            letter-spacing: 0.1em;
        }

        .footer-copy span {
            color: var(--volt);
        }

        .footer-legal {
            display: flex;
            gap: 0;
        }

        .footer-legal a {
            font-family: var(--font-mono);
            font-size: 0.6rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--smoke);
            padding: 0 1rem;
            border-left: 1px solid var(--grid-ln);
            transition: color 0.2s;
        }

        .footer-legal a:first-child {
            border-left: none;
        }

        .footer-legal a:hover {
            color: var(--volt);
        }

        /* ═══════════════════════════════════════════════════════════
           GSAP INIT STATES
        ═══════════════════════════════════════════════════════════ */
        .gsap-init {
            opacity: 0;
        }

        /* ═══════════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════════ */
        @media (max-width: 1100px) {
            .footer-top {
                grid-template-columns: 1fr 1fr;
                gap: 2.5rem;
            }

            .nav-links {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .nav-inner {
                grid-template-columns: auto 1fr;
            }

            .nav-actions .nav-action-link:not(.nav-cart) {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .nav-actions {
                gap: 0;
            }

            .footer-top {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 3rem 1rem 2rem;
            }

            .footer-bottom {
                padding: 1rem;
            }

            html {
                cursor: auto;
            }

            #cursor,
            #cursor-fill {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .nav-inner {
                padding: 0 1rem;
            }

            .footer-legal {
                display: none;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           UTILITY
        ═══════════════════════════════════════════════════════════ */
        .volt {
            color: var(--volt);
        }

        .mono {
            font-family: var(--font-mono);
        }
    </style>
</head>

<body>
    <!-- Custom cursor -->
    <div id="cursor"></div>
    <div id="cursor-fill"></div>

    <!-- Page wipe -->
    <div id="page-wipe"></div>

    <!-- ── TOP TICKER ──────────────────────────────────────────── -->
    <div class="top-ticker" aria-hidden="true">
        <div class="ticker-track">
            @php $tItems = ['Free Shipping Over Rs. 5,000', 'New Drop: Summer Edit', 'Code FIRST10 — 10% Off', 'Island-Wide Delivery', '14-Day Returns', 'Free Shipping Over Rs. 5,000', 'New Drop: Summer Edit', 'Code FIRST10 — 10% Off', 'Island-Wide Delivery', '14-Day Returns', 'Free Shipping Over Rs. 5,000', 'New Drop: Summer Edit', 'Code FIRST10 — 10% Off', 'Island-Wide Delivery', '14-Day Returns']; @endphp
            @foreach($tItems as $item)
                <span class="ticker-item">{{ $item }} <span class="ticker-sep"></span></span>
            @endforeach
        </div>
    </div>

    <!-- ── HEADER ─────────────────────────────────────────────── -->
    <header class="site-header" id="site-header">
        <div class="nav-inner">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="nav-logo">
                <span class="nav-logo-dot"></span>
                {{ strtoupper($store_name ?? 'KARBNZOL') }}
            </a>

            <!-- Center nav -->
            <nav aria-label="Primary">
                <ul class="nav-links">
                    <li><a href="{{ route('products.index') }}" class="active">Shop</a></li>
                    <li><a href="#">New Drop</a></li>
                    <li><a href="#">Collections</a></li>
                    <li><a href="#">Editorial</a></li>
                    <li><a href="#">About</a></li>
                </ul>
            </nav>

            <!-- Actions -->
            <div class="nav-actions">
                <!-- Search -->
                <a href="#" class="nav-action-link" aria-label="Search">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <span>Search</span>
                </a>

                @auth('web')
                    <a href="{{ route('account.dashboard') }}" class="nav-action-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <span>Account</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-action-link">Login</a>
                    <a href="{{ route('register') }}" class="nav-register">Register →</a>
                @endauth

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="nav-cart" aria-label="Cart">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    BAG
                    <span class="cart-num">0</span>
                </a>

                <!-- Mobile burger -->
                <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile nav drawer -->
    <nav class="mobile-drawer" id="mobile-drawer" aria-label="Mobile navigation">
        <a href="{{ route('products.index') }}">Shop</a>
        <a href="#">New Drop</a>
        <a href="#">Collections</a>
        <a href="#">Editorial</a>
        <a href="#">About</a>
        <a href="{{ route('cart.index') }}">Bag (0)</a>
        @auth('web')
            <a href="{{ route('account.dashboard') }}">Account</a>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}" style="color: var(--volt)!important;">Register →</a>
        @endauth
    </nav>

    <!-- ── MAIN CONTENT ───────────────────────────────────────── -->
    <main>
        @yield('content')
    </main>

    <!-- ── FOOTER ─────────────────────────────────────────────── -->
    <footer class="site-footer">
        <!-- Background ghost text -->
        <div class="footer-bg-text" aria-hidden="true">{{ strtoupper($store_name ?? 'KARBNZOL') }}</div>

        <div class="footer-top">

            <!-- Brand -->
            <div class="footer-brand gsap-init">
                <div class="footer-wordmark">
                    <span class="volt-dot"></span>
                    {{ strtoupper($store_name ?? 'KARBNZOL') }}
                </div>
                <p class="footer-tagline">
                    Boundary-breaking fashion<br>
                    for the ones who move<br>
                    on their own frequency.
                </p>
                <div class="footer-socials">
                    <a class="social-sq" href="#" aria-label="Instagram">IG</a>
                    <a class="social-sq" href="#" aria-label="Facebook">FB</a>
                    <a class="social-sq" href="#" aria-label="X / Twitter">𝕏</a>
                    <a class="social-sq" href="#" aria-label="TikTok">TK</a>
                    <a class="social-sq" href="#" aria-label="Pinterest">PT</a>
                </div>
            </div>

            <!-- Shop col -->
            <div class="footer-col gsap-init">
                <p class="footer-col-label">Navigate</p>
                <ul>
                    <li><a href="{{ route('products.index') }}">Shop All</a></li>
                    <li><a href="#">New Drop</a></li>
                    <li><a href="#">Collections</a></li>
                    <li><a href="#">Lookbook</a></li>
                    <li><a href="#">Editorial</a></li>
                    <li><a href="#">Sale</a></li>
                </ul>
            </div>

            <!-- Support col -->
            <div class="footer-col gsap-init">
                <p class="footer-col-label">Support</p>
                <ul>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Shipping</a></li>
                    <li><a href="#">Size Guide</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Track Order</a></li>
                </ul>
            </div>

            <!-- Contact + newsletter -->
            <div class="footer-col gsap-init">
                <p class="footer-col-label">Contact</p>
                <div class="footer-contact-info">
                    <a href="tel:{{ $store_phone ?? '+94112345678' }}">{{ $store_phone ?? '+94 11 234 5678' }}</a><br>
                    <a href="mailto:{{ $store_email ?? 'hello@karbnzol.com' }}">{{ $store_email ?? 'hello@karbnzol.com'
                        }}</a><br>
                    Mon–Sat, 9am–6pm
                </div>
                <p class="footer-nl-label">// Get the drop</p>
                <form class="footer-nl-form">
                    @csrf
                    <input type="email" name="email" placeholder="your@email.com" required>
                    <button type="submit">→</button>
                </form>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <p class="footer-copy">
                © {{ date('Y') }} <span>{{ strtoupper($store_name ?? 'KARBNZOL') }}</span>. All rights reserved.
            </p>
            <nav class="footer-legal" aria-label="Legal">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Cookies</a>
                <a href="#">Accessibility</a>
            </nav>
        </div>
    </footer>

    <!-- ── SCRIPTS ─────────────────────────────────────────────── -->
    <script>
        window.addEventListener('load', () => {

            /* ── Custom cursor ───────────────────────────────────── */
            const cur = document.getElementById('cursor');
            const fill = document.getElementById('cursor-fill');
            if (cur && fill && window.matchMedia('(pointer:fine)').matches) {
                let mx = 0, my = 0, fx = 0, fy = 0;
                document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
                (function animCursor() {
                    fx += (mx - fx) * 0.18;
                    fy += (my - fy) * 0.18;
                    cur.style.left = fx + 'px';
                    cur.style.top = fy + 'px';
                    fill.style.left = mx + 'px';
                    fill.style.top = my + 'px';
                    requestAnimationFrame(animCursor);
                })();
                document.querySelectorAll('a, button, [role=button]').forEach(el => {
                    el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
                    el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
                });
            }

            /* ── Sticky scroll class ─────────────────────────────── */
            const header = document.getElementById('site-header');
            window.addEventListener('scroll', () => {
                header.classList.toggle('scrolled', window.scrollY > 32);
            }, { passive: true });

            /* ── Mobile drawer ───────────────────────────────────── */
            const burger = document.getElementById('hamburger');
            const drawer = document.getElementById('mobile-drawer');
            function toggleDrawer(open) {
                burger.classList.toggle('open', open);
                drawer.classList.toggle('open', open);
                burger.setAttribute('aria-expanded', open);
                document.body.style.overflow = open ? 'hidden' : '';
            }
            burger.addEventListener('click', () => toggleDrawer(!drawer.classList.contains('open')));
            drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', () => toggleDrawer(false)));

            /* ── GSAP ────────────────────────────────────────────── */
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            /* Page enter — horizontal volt wipe */
            gsap.to('#page-wipe', {
                scaleX: 0,
                duration: 0.85,
                ease: 'power4.inOut',
                transformOrigin: 'right',
                clearProps: 'all',
                onComplete: () => document.getElementById('page-wipe').style.display = 'none'
            });

            /* Header slide down */
            gsap.from('#site-header', {
                y: -var_nav_h(), opacity: 0,
                duration: 0.6,
                ease: 'power3.out',
                delay: 0.6
            });

            /* Footer gsap-init stagger */
            document.querySelectorAll('.gsap-init').forEach((el, i) => {
                gsap.to(el, {
                    opacity: 1,
                    y: 0,
                    duration: 0.75,
                    ease: 'power3.out',
                    delay: i * 0.1,
                    scrollTrigger: {
                        trigger: el,
                        start: 'top 88%',
                        toggleActions: 'play none none none'
                    }
                });
            });

            /* Nav link hover: volt underline stretch via JS for extra snap */
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('mouseenter', () => {
                    gsap.fromTo(link, { '--underW': '0%' }, { '--underW': '100%', duration: 0.25, ease: 'power2.out' });
                });
            });

            /* Cart jolt */
            const cartBtn = document.querySelector('.nav-cart');
            if (cartBtn) {
                cartBtn.addEventListener('mouseenter', () => {
                    gsap.fromTo(cartBtn,
                        { skewX: 0 },
                        {
                            skewX: -4, duration: 0.15, ease: 'power2.out',
                            onComplete: () => gsap.to(cartBtn, { skewX: 0, duration: 0.3, ease: 'elastic.out(1.5, 0.5)' })
                        }
                    );
                });
            }

            /* Page leave — volt wipe out */
            document.querySelectorAll('a[href]').forEach(link => {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto') || href.startsWith('tel') || link.target === '_blank') return;
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const dest = href;
                    const wipe = document.getElementById('page-wipe');
                    wipe.style.display = 'block';
                    wipe.style.transform = 'scaleX(0)';
                    wipe.style.transformOrigin = 'left';
                    gsap.to(wipe, {
                        scaleX: 1,
                        duration: 0.55,
                        ease: 'power4.inOut',
                        onComplete: () => window.location.href = dest
                    });
                });
            });

            function var_nav_h() { return 64; }
        });
    </script>
</body>

</html>