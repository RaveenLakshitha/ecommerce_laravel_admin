<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ $store_name ?? 'Karbnzol' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Oswald (condensed uppercase) + Barlow (clean body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap"
        rel="stylesheet">

    <!-- GSAP + ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    <style>
        /* ════════════════════════════════════════════
           VARIABLES — DARK CHARCOAL MENSWEAR
        ════════════════════════════════════════════ */
        :root {
            --bg: #1a1a1a;
            --bg-2: #222222;
            --bg-3: #2a2a2a;
            --bg-4: #333333;
            --bg-panel: #1e1e1e;
            --white: #ffffff;
            --off-white: #f0f0f0;
            --silver: #d1d5db;
            --dim: #a1a1aa;
            --gold: #c8a96e;
            --gold-bg: rgba(200, 169, 110, 0.12);
            --red: #cc3333;

            --font-display: 'Oswald', 'Arial Narrow', sans-serif;
            --font-body: 'Barlow', sans-serif;
            --nav-h: 64px;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

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
            background: var(--bg);
            color: var(--off-white);
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

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bg-4);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--silver);
        }

        /* ── PAGE WIPE ────────────────────────────── */
        #page-wipe {
            position: fixed;
            inset: 0;
            background: var(--bg-3);
            z-index: 9000;
            transform: scaleX(1);
            transform-origin: right;
            pointer-events: none;
        }

        /* ── ANNOUNCEMENT BAR ─────────────────────── */
        .announce-bar {
            background: var(--gold);
            color: var(--bg);
            text-align: center;
            padding: 0.45rem 1rem;
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            overflow: hidden;
        }

        .announce-inner {
            display: inline-block;
            animation: ann-scroll 32s linear infinite;
            white-space: nowrap;
        }

        @keyframes ann-scroll {
            from {
                transform: translateX(35vw);
            }

            to {
                transform: translateX(-100%);
            }
        }

        /* ── HEADER ───────────────────────────────── */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 200;
            height: var(--nav-h);
            background: rgba(26, 26, 26, 0.97);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--bg-4);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .site-header.scrolled {
            border-bottom-color: #3a3a3a;
            box-shadow: 0 4px 32px rgba(0, 0, 0, 0.45);
        }

        .nav-wrap {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .logo-main {
            font-family: var(--font-display);
            font-size: 1.625rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--white);
            line-height: 1;
        }

        .logo-main em {
            font-style: normal;
            color: var(--gold);
        }

        .logo-sub {
            font-family: var(--font-display);
            font-size: 0.48rem;
            font-weight: 300;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--dim);
        }

        /* Nav links */
        .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            height: var(--nav-h);
            padding: 0 0.95rem;
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--silver);
            position: relative;
            transition: color 0.2s;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0.95rem;
            right: 0.95rem;
            height: 2px;
            background: var(--gold);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s var(--ease-out);
        }

        .nav-links a:hover {
            color: var(--white);
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            transform: scaleX(1);
        }

        .nav-links a.active {
            color: var(--white);
        }

        .new-pill {
            display: inline-block;
            background: var(--red);
            color: var(--white);
            font-size: 0.38rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            padding: 0.12rem 0.3rem;
            margin-left: 0.3rem;
            vertical-align: super;
            border-radius: 2px;
        }

        /* Right icons cluster */
        .nav-right {
            display: flex;
            align-items: center;
        }

        .nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: var(--nav-h);
            color: var(--silver);
            transition: color 0.2s, background 0.2s;
            position: relative;
        }

        .nav-icon:hover {
            color: var(--white);
            background: var(--bg-3);
        }

        .cart-dot {
            position: absolute;
            top: 13px;
            right: 8px;
            width: 16px;
            height: 16px;
            background: var(--gold);
            color: var(--bg);
            font-family: var(--font-display);
            font-size: 0.5rem;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Auth area */
        .nav-auth {
            display: flex;
            align-items: center;
            border-left: 1px solid var(--bg-4);
            margin-left: 4px;
        }

        .auth-link {
            display: flex;
            align-items: center;
            height: var(--nav-h);
            padding: 0 1rem;
            font-family: var(--font-display);
            font-size: 0.63rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--silver);
            border-right: 1px solid var(--bg-4);
            transition: color 0.2s, background 0.2s;
        }

        .auth-link:hover {
            color: var(--white);
            background: var(--bg-3);
        }

        .auth-link.primary {
            background: var(--gold);
            color: var(--bg);
            font-weight: 700;
        }

        .auth-link.primary:hover {
            background: var(--off-white);
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
            background: var(--off-white);
            transition: all 0.3s var(--ease-out);
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(6.5px) rotate(45deg);
            background: var(--gold);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-6.5px) rotate(-45deg);
            background: var(--gold);
        }

        /* Mobile drawer */
        .mobile-nav {
            position: fixed;
            top: var(--nav-h);
            left: 0;
            right: 0;
            background: var(--bg-panel);
            border-top: 2px solid var(--gold);
            z-index: 199;
            transform: translateY(-110%);
            transition: transform 0.4s var(--ease-out);
            padding: 1.25rem 1.5rem;
            max-height: calc(100vh - var(--nav-h));
            overflow-y: auto;
        }

        .mobile-nav.open {
            transform: translateY(0);
        }

        .mobile-nav a {
            display: block;
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--silver);
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--bg-4);
            transition: color 0.2s, padding-left 0.2s;
        }

        .mobile-nav a:hover {
            color: var(--white);
            padding-left: 0.5rem;
        }

        /* ── MAIN ─────────────────────────────────── */
        main {
            min-height: 80vh;
        }

        /* ── FOOTER ───────────────────────────────── */
        .site-footer {
            background: #111;
            border-top: 1px solid var(--bg-4);
            padding: 4rem 0 0;
        }

        .footer-inner {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1.6fr;
            gap: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid var(--bg-4);
        }

        .footer-brand {}

        .ft-logo {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--white);
            line-height: 1;
            margin-bottom: 3px;
        }

        .ft-logo em {
            font-style: normal;
            color: var(--gold);
        }

        .ft-logo-tag {
            font-family: var(--font-display);
            font-size: 0.5rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--dim);
            margin-bottom: 1.25rem;
        }

        .ft-desc {
            font-size: 0.8125rem;
            color: var(--dim);
            line-height: 1.75;
            max-width: 280px;
            font-weight: 300;
            margin-bottom: 1.5rem;
        }

        .ft-socials {
            display: flex;
        }

        .ft-soc {
            width: 38px;
            height: 38px;
            border: 1px solid var(--bg-4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: 0.55rem;
            font-weight: 600;
            color: var(--dim);
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }

        .ft-soc+.ft-soc {
            border-left: none;
        }

        .ft-soc:hover {
            background: var(--gold);
            color: var(--bg);
            border-color: var(--gold);
        }

        .ft-col-h {
            font-family: var(--font-display);
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--white);
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--bg-4);
            margin-bottom: 1.25rem;
        }

        .ft-col-links {
            list-style: none;
        }

        .ft-col-links li {
            margin-bottom: 0.5rem;
        }

        .ft-col-links a {
            font-size: 0.8125rem;
            color: var(--dim);
            transition: color 0.2s, padding-left 0.2s;
            display: inline-block;
            font-weight: 300;
        }

        .ft-col-links a:hover {
            color: var(--off-white);
            padding-left: 5px;
        }

        .ft-contact {
            font-size: 0.8rem;
            color: var(--dim);
            line-height: 2;
            font-weight: 300;
        }

        .ft-contact a {
            color: var(--gold);
        }

        .ft-contact a:hover {
            text-decoration: underline;
        }

        .ft-nl-note {
            font-size: 0.76rem;
            color: var(--dim);
            line-height: 1.6;
            margin-bottom: 0.875rem;
            font-weight: 300;
        }

        .ft-nl-form {
            display: flex;
            border: 1px solid var(--bg-4);
            transition: border-color 0.2s;
            margin-bottom: 1.5rem;
        }

        .ft-nl-form:focus-within {
            border-color: var(--gold);
        }

        .ft-nl-form input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 0.625rem 0.875rem;
            font-family: var(--font-body);
            font-size: 0.78rem;
            color: var(--off-white);
            letter-spacing: 0.04em;
        }

        .ft-nl-form input::placeholder {
            color: var(--dim);
        }

        .ft-nl-form button {
            background: var(--gold);
            border: none;
            color: var(--bg);
            padding: 0.625rem 1rem;
            font-family: var(--font-display);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }

        .ft-nl-form button:hover {
            background: var(--white);
        }

        .ft-pay-label {
            font-family: var(--font-display);
            font-size: 0.56rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--dim);
            margin-bottom: 0.5rem;
        }

        .ft-pay-row {
            display: flex;
            gap: 0;
            flex-wrap: wrap;
        }

        .pay-chip {
            background: var(--bg-3);
            border: 1px solid var(--bg-4);
            padding: 0.25rem 0.6rem;
            font-family: var(--font-display);
            font-size: 0.5rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--silver);
        }

        .pay-chip+.pay-chip {
            border-left: none;
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 0;
            flex-wrap: wrap;
        }

        .ft-copy {
            font-family: var(--font-display);
            font-size: 0.6rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--dim);
        }

        .ft-copy em {
            font-style: normal;
            color: var(--gold);
        }

        .ft-legal {
            display: flex;
            gap: 1.5rem;
        }

        .ft-legal a {
            font-family: var(--font-display);
            font-size: 0.56rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--dim);
            transition: color 0.2s;
        }

        .ft-legal a:hover {
            color: var(--off-white);
        }

        /* ── GSAP INIT ────────────────────────────── */
        .g-up {
            opacity: 0;
            transform: translateY(22px);
        }

        /* ── RESPONSIVE ───────────────────────────── */
        @media (max-width: 1200px) {
            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .footer-top {
                grid-template-columns: 1fr 1fr 1fr;
            }

            .footer-brand {
                grid-column: 1 / 4;
            }
        }

        @media (max-width: 768px) {
            .nav-auth {
                display: none;
            }

            .footer-top {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }

            .footer-brand {
                grid-column: 1 / 3;
            }

            .footer-inner {
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .nav-wrap {
                padding: 0 1rem;
            }

            .footer-top {
                grid-template-columns: 1fr;
            }

            .footer-brand {
                grid-column: 1;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div id="page-wipe"></div>

    <!-- Announcement bar -->
    <div class="announce-bar" aria-live="polite">
        <span class="announce-inner">
            FREE DELIVERY ON ORDERS OVER RS. 5,000 &nbsp;·&nbsp;
            NEW ARRIVALS EVERY FRIDAY &nbsp;·&nbsp;
            MINTPAY — PAY IN 3 EASY INSTALLMENTS &nbsp;·&nbsp;
            4% CASHBACK WITH MINTPAY &nbsp;·&nbsp;
            FREE DELIVERY ON ORDERS OVER RS. 5,000 &nbsp;·&nbsp;
            NEW ARRIVALS EVERY FRIDAY &nbsp;·&nbsp;
            MINTPAY — PAY IN 3 EASY INSTALLMENTS &nbsp;·&nbsp;
            4% CASHBACK WITH MINTPAY
        </span>
    </div>

    <!-- Header -->
    <header class="site-header" id="siteHeader">
        <div class="nav-wrap">
            <a href="{{ route('home') }}" class="nav-logo">
                <span class="logo-main">KARBN<em>ZOL</em></span>
                <span class="logo-sub">T-Shirts · Jeans · Chinos</span>
            </a>

            <nav aria-label="Main">
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="active">Home</a></li>
                    <li><a href="{{ route('frontend.products.index') }}">New Arrivals <span class="new-pill">New</span></a></li>
                    @foreach($globalCategories as $category)
                        <li><a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('frontend.about') }}">About</a></li>
                    <li><a href="{{ route('frontend.about') }}#contact-info">Contact</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </nav>

            <div class="nav-right">
                @auth('web')
                    <a href="{{ route('account.dashboard') }}" class="nav-icon" aria-label="Account">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </a>
                @else
                    <div class="nav-auth">
                        <a href="{{ route('login') }}" class="auth-link">Sign In</a>
                        <a href="{{ route('register') }}" class="auth-link primary">Register</a>
                    </div>
                @endauth

                <a href="#" class="nav-icon" aria-label="Search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </a>

                <a href="{{ route('cart.index') }}" class="nav-icon" aria-label="Shopping bag">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <span class="cart-dot">0</span>
                </a>

                <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile drawer -->
    <nav class="mobile-nav" id="mobileNav" aria-label="Mobile navigation">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('frontend.products.index') }}">New Arrivals</a>
        @foreach($globalCategories as $category)
            <a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
        @endforeach
        <a href="{{ route('frontend.about') }}">About</a>
        <a href="{{ route('frontend.about') }}#contact-info">Contact</a>
        <a href="#">Careers</a>
        <a href="{{ route('cart.index') }}">My Bag (0)</a>
        @auth('web')
            <a href="{{ route('account.dashboard') }}">My Account</a>
        @else
            <a href="{{ route('login') }}">Sign In</a>
            <a href="{{ route('register') }}" style="color: var(--gold)!important;">Register →</a>
        @endauth
    </nav>

    <main>@yield('content')</main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-brand g-up">
                    <div class="ft-logo">KARBN<em>ZOL</em></div>
                    <div class="ft-logo-tag">T-Shirts · Jeans · Chinos</div>
                    <p class="ft-desc">Sri Lanka's premier destination for men's and kids' fashion. Quality
                        craftsmanship, contemporary style, unbeatable value.</p>
                    <div class="ft-socials">
                        <a class="ft-soc" href="#" aria-label="Instagram">IG</a>
                        <a class="ft-soc" href="#" aria-label="Facebook">FB</a>
                        <a class="ft-soc" href="#" aria-label="TikTok">TK</a>
                        <a class="ft-soc" href="#" aria-label="YouTube">YT</a>
                    </div>
                </div>

                <div class="g-up">
                    <p class="ft-col-h">Shop</p>
                    <ul class="ft-col-links">
                        <li><a href="{{ route('frontend.products.index') }}">All Products</a></li>
                        <li><a href="{{ route('frontend.products.index') }}">New Arrivals</a></li>
                        @foreach($globalCategories as $category)
                            <li><a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('frontend.products.index') }}">Sale</a></li>
                    </ul>
                </div>

                <div class="g-up">
                    <p class="ft-col-h">Help</p>
                    <ul class="ft-col-links">
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns Policy</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Track Order</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="{{ route('frontend.about') }}">About Us</a></li>
                        <li><a href="{{ route('frontend.about') }}#contact-info">Contact Us</a></li>
                    </ul>
                </div>

                <div class="g-up">
                    <p class="ft-col-h">Contact</p>
                    <div class="ft-contact">
                        <a
                            href="tel:{{ $store_phone ?? '+94112345678' }}">{{ $store_phone ?? '+94 11 234 5678' }}</a><br>
                        <a href="mailto:{{ $store_email ?? 'info@karbnzol.com' }}">{{ $store_email ??
                            'info@karbnzol.com' }}</a><br><br>
                        Mon – Sat &nbsp; 9am – 6pm
                    </div>
                </div>

                <div class="g-up">
                    <p class="ft-col-h">Newsletter</p>
                    <p class="ft-nl-note">Get exclusive deals and early access to new drops.</p>
                    <form class="ft-nl-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Your email" required>
                        <button type="submit">Go</button>
                    </form>
                    <p class="ft-pay-label">We Accept</p>
                    <div class="ft-pay-row">
                        <span class="pay-chip">Visa</span>
                        <span class="pay-chip">Master</span>
                        <span class="pay-chip">Amex</span>
                        <span class="pay-chip">MintPay</span>
                        <span class="pay-chip">Cash</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="ft-copy">© {{ date('Y') }} <em>KARBNZOL</em>. All Rights Reserved.</p>
                <nav class="ft-legal" aria-label="Legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Use</a>
                    <a href="#">Cookies</a>
                </nav>
            </div>
        </div>
    </footer>

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
            /* Sticky */
            const header = document.getElementById('siteHeader');
            window.addEventListener('scroll', () => {
                header.classList.toggle('scrolled', window.scrollY > 40);
            }, { passive: true });

            /* Hamburger */
            const burger = document.getElementById('hamburger');
            const mNav = document.getElementById('mobileNav');
            function toggleNav(open) {
                burger.classList.toggle('open', open);
                mNav.classList.toggle('open', open);
                burger.setAttribute('aria-expanded', open);
                document.body.style.overflow = open ? 'hidden' : '';
            }
            burger.addEventListener('click', () => toggleNav(!mNav.classList.contains('open')));
            mNav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => toggleNav(false)));

            /* GSAP */
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            gsap.to('#page-wipe', {
                scaleX: 0, duration: 0.85, ease: 'power4.inOut',
                transformOrigin: 'left', clearProps: 'all',
                onComplete: () => document.getElementById('page-wipe').style.display = 'none'
            });

            gsap.from('#siteHeader', { y: -70, opacity: 0, duration: 0.6, ease: 'power3.out', delay: 0.6 });

            document.querySelectorAll('.g-up').forEach((el, i) => {
                gsap.to(el, {
                    opacity: 1, y: 0, duration: 0.7, ease: 'power3.out',
                    delay: i * 0.1,
                    scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' }
                });
            });

            /* Page leave */
            document.querySelectorAll('a[href]').forEach(link => {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto') || href.startsWith('tel') || link.target === '_blank') return;
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const dest = href;
                    const wipe = document.getElementById('page-wipe');
                    wipe.style.display = 'block';
                    wipe.style.transform = 'scaleX(0)';
                    wipe.style.transformOrigin = 'right';
                    gsap.to(wipe, {
                        scaleX: 1, duration: 0.5, ease: 'power4.inOut',
                        onComplete: () => window.location.href = dest
                    });
                });
            });
        });
    </script>
</body>

</html>

