<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ $store_name ?? 'Karbnzol' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Editorial serif + clean sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- GSAP + ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    <style>
        /* ─── CSS VARIABLES ─────────────────────────────────────────── */
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
            --nav-h: 72px;
            --ease-silk: cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* ─── RESET / BASE ──────────────────────────────────────────── */
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
            background: var(--cream);
            color: var(--ink);
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

        /* ─── SCROLLBAR ─────────────────────────────────────────────── */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--cream);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--mink);
            border-radius: 3px;
        }

        /* ─── NAV ───────────────────────────────────────────────────── */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 200;
            height: var(--nav-h);
            background: rgba(245, 240, 232, 0.88);
            backdrop-filter: blur(14px) saturate(1.4);
            -webkit-backdrop-filter: blur(14px) saturate(1.4);
            border-bottom: 1px solid rgba(107, 94, 82, 0.12);
            transition: background 0.35s var(--ease-silk);
        }

        .site-header.scrolled {
            background: rgba(245, 240, 232, 0.97);
            box-shadow: 0 2px 24px rgba(26, 22, 18, 0.07);
        }

        .nav-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        /* Logo */
        .nav-logo {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 300;
            letter-spacing: 0.08em;
            color: var(--ink);
            white-space: nowrap;
        }

        .nav-logo span {
            color: var(--rust);
        }

        /* Desktop links */
        .nav-links {
            display: flex;
            gap: 2.25rem;
            list-style: none;
        }

        .nav-links a {
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--mink);
            position: relative;
            padding-bottom: 2px;
            transition: color 0.25s;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: var(--rust);
            transition: width 0.35s var(--ease-silk);
        }

        .nav-links a:hover {
            color: var(--ink);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Nav actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .nav-actions a {
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--mink);
            transition: color 0.25s;
        }

        .nav-actions a:hover {
            color: var(--ink);
        }

        .nav-actions .cart-pill {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--ink);
            color: var(--cream);
            padding: 0.5rem 1.125rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: background 0.25s, transform 0.2s;
        }

        .nav-actions .cart-pill:hover {
            background: var(--rust);
            transform: translateY(-1px);
        }

        .nav-actions .cart-pill .cart-count {
            background: var(--rust);
            color: var(--white);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: background 0.25s;
        }

        .nav-actions .cart-pill:hover .cart-count {
            background: var(--cream);
            color: var(--rust);
        }

        .btn-register {
            display: inline-flex;
            align-items: center;
            background: var(--rust);
            color: var(--white) !important;
            padding: 0.5rem 1.25rem;
            border-radius: 100px;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: background 0.25s, transform 0.2s !important;
        }

        .btn-register:hover {
            background: var(--ink) !important;
            transform: translateY(-1px) !important;
        }

        /* Mobile hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
            background: none;
            border: none;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--ink);
            border-radius: 2px;
            transition: all 0.35s var(--ease-silk);
            transform-origin: center;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Mobile drawer */
        .mobile-drawer {
            position: fixed;
            top: var(--nav-h);
            right: 0;
            width: min(320px, 90vw);
            height: calc(100vh - var(--nav-h));
            background: var(--cream);
            border-left: 1px solid var(--sand);
            padding: 2.5rem 2rem;
            z-index: 199;
            transform: translateX(110%);
            transition: transform 0.45s var(--ease-silk);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .mobile-drawer.open {
            transform: translateX(0);
        }

        .mobile-drawer a {
            font-family: var(--font-display);
            font-size: 1.6rem;
            font-weight: 300;
            letter-spacing: 0.04em;
            color: var(--ink);
            display: block;
            padding: 0.35rem 0;
            border-bottom: 1px solid var(--sand);
            transition: color 0.2s, padding-left 0.25s;
        }

        .mobile-drawer a:hover {
            color: var(--rust);
            padding-left: 0.5rem;
        }

        .drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(26, 22, 18, 0.35);
            z-index: 198;
            backdrop-filter: blur(2px);
        }

        .drawer-overlay.open {
            display: block;
        }

        /* ─── ANNOUNCEMENT BAR ──────────────────────────────────────── */
        .announcement-bar {
            background: var(--ink);
            color: var(--cream);
            text-align: center;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 500;
            overflow: hidden;
        }

        .announcement-scroll {
            display: inline-block;
            animation: marquee 28s linear infinite;
            white-space: nowrap;
        }

        @keyframes marquee {
            from {
                transform: translateX(40vw);
            }

            to {
                transform: translateX(-100%);
            }
        }

        /* ─── MAIN ──────────────────────────────────────────────────── */
        main {
            min-height: 80vh;
        }

        /* ─── PAGE TRANSITION WIPE ──────────────────────────────────── */
        #page-wipe {
            position: fixed;
            inset: 0;
            background: var(--ink);
            z-index: 9000;
            transform-origin: bottom;
            transform: scaleY(1);
            pointer-events: none;
        }

        /* ─── FOOTER ────────────────────────────────────────────────── */
        .site-footer {
            background: var(--ink);
            color: var(--cream);
            padding: 5rem 0 2rem;
            position: relative;
            overflow: hidden;
        }

        .site-footer::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -120px;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(196, 96, 42, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .footer-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.4fr;
            gap: 3rem;
        }

        .footer-brand .footer-logo {
            font-family: var(--font-display);
            font-size: 2.25rem;
            font-weight: 300;
            letter-spacing: 0.08em;
            color: var(--cream);
            line-height: 1.1;
            margin-bottom: 0.75rem;
        }

        .footer-brand .footer-logo span {
            color: var(--rust-lt);
        }

        .footer-brand p {
            color: rgba(245, 240, 232, 0.45);
            font-size: 0.875rem;
            line-height: 1.7;
            max-width: 240px;
        }

        .footer-brand .social-row {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.75rem;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            border: 1px solid rgba(245, 240, 232, 0.18);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(245, 240, 232, 0.55);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            transition: border-color 0.25s, color 0.25s, background 0.25s;
        }

        .social-icon:hover {
            border-color: var(--rust-lt);
            color: var(--rust-lt);
            background: rgba(232, 135, 79, 0.1);
        }

        .footer-col h4 {
            font-family: var(--font-display);
            font-size: 1.0625rem;
            font-weight: 400;
            letter-spacing: 0.08em;
            color: var(--cream);
            margin-bottom: 1.25rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 0.65rem;
        }

        .footer-col ul a {
            font-size: 0.8125rem;
            color: rgba(245, 240, 232, 0.45);
            letter-spacing: 0.04em;
            transition: color 0.25s, padding-left 0.2s;
            display: inline-block;
        }

        .footer-col ul a:hover {
            color: var(--rust-lt);
            padding-left: 6px;
        }

        .footer-contact p {
            font-size: 0.8125rem;
            color: rgba(245, 240, 232, 0.45);
            line-height: 1.9;
        }

        .footer-contact a {
            color: var(--rust-lt);
            transition: opacity 0.2s;
        }

        .footer-contact a:hover {
            opacity: 0.75;
        }

        .footer-newsletter {
            margin-top: 1.5rem;
        }

        .footer-newsletter p {
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(245, 240, 232, 0.4);
            margin-bottom: 0.65rem;
            font-weight: 500;
        }

        .newsletter-form {
            display: flex;
            border: 1px solid rgba(245, 240, 232, 0.18);
            border-radius: 100px;
            overflow: hidden;
            transition: border-color 0.25s;
        }

        .newsletter-form:focus-within {
            border-color: var(--rust-lt);
        }

        .newsletter-form input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 0.6rem 1rem;
            font-family: var(--font-body);
            font-size: 0.8125rem;
            color: var(--cream);
        }

        .newsletter-form input::placeholder {
            color: rgba(245, 240, 232, 0.3);
        }

        .newsletter-form button {
            background: var(--rust);
            border: none;
            color: var(--white);
            padding: 0.6rem 1.125rem;
            font-family: var(--font-body);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.25s;
            border-radius: 0 100px 100px 0;
        }

        .newsletter-form button:hover {
            background: var(--rust-lt);
        }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(245, 240, 232, 0.08);
            margin: 3rem 0 1.75rem;
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-bottom p {
            font-size: 0.75rem;
            color: rgba(245, 240, 232, 0.28);
            letter-spacing: 0.06em;
        }

        .footer-legal {
            display: flex;
            gap: 1.75rem;
        }

        .footer-legal a {
            font-size: 0.7rem;
            color: rgba(245, 240, 232, 0.28);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: color 0.2s;
        }

        .footer-legal a:hover {
            color: var(--rust-lt);
        }

        /* ─── GSAP INITIAL STATES ───────────────────────────────────── */
        .gsap-fade-up {
            opacity: 0;
            transform: translateY(28px);
        }

        /* ─── RESPONSIVE ────────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .nav-actions .register-link,
            .nav-actions .login-link {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-brand p {
                max-width: 100%;
            }

            .announcement-scroll {
                animation-duration: 18s;
            }

        }

        @media (max-width: 480px) {
            .nav-inner {
                padding: 0 1rem;
            }

            .footer-inner {
                padding: 0 1rem;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <!-- Page wipe transition -->
    <div id="page-wipe"></div>

    <!-- Announcement bar -->
    <div class="announcement-bar" aria-label="Announcement">
        <span class="announcement-scroll">
            Free shipping on orders over Rs. 5,000 &nbsp;·&nbsp;
            New collection now live &nbsp;·&nbsp;
            Use code <strong>FIRST10</strong> for 10% off your first order &nbsp;·&nbsp;
            Easy 14-day returns &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Free shipping on orders over Rs. 5,000 &nbsp;·&nbsp;
            New collection now live &nbsp;·&nbsp;
            Use code <strong>FIRST10</strong> for 10% off your first order &nbsp;·&nbsp;
            Easy 14-day returns
        </span>
    </div>

    <!-- ─── HEADER ─────────────────────────────────────────────────── -->
    <header class="site-header" id="site-header">
        <div class="nav-inner">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="nav-logo">
                {{ $store_name_prefix ?? 'Karbn' }}<span>{{ $store_name_suffix ?? 'zol' }}</span>
            </a>

            <!-- Desktop nav -->
            <nav aria-label="Main navigation">
                <ul class="nav-links">
                    <li><a href="{{ route('frontend.products.index') }}">Shop</a></li>
                    <li><a href="#">New Arrivals</a></li>
                    <li><a href="#">Collections</a></li>
                    <li><a href="#">About</a></li>
                </ul>
            </nav>

            <!-- Actions -->
            <div class="nav-actions">
                <!-- Search icon -->
                <a href="#" aria-label="Search" style="color: var(--mink); display:flex; align-items:center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </a>

                @auth('web')
                    <a href="{{ route('account.dashboard') }}" class="login-link"
                        style="display:flex;align-items:center;gap:.35rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Account
                    </a>
                @else
                    <a href="{{ route('login') }}" class="login-link">Login</a>
                    <a href="{{ route('register') }}" class="btn-register register-link">Register</a>
                @endauth

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="cart-pill" aria-label="Cart">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    Cart
                    <span class="cart-count">0</span>
                </a>
            </div>

            <!-- Hamburger -->
            <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <!-- Mobile drawer -->
    <div class="drawer-overlay" id="drawer-overlay"></div>
    <nav class="mobile-drawer" id="mobile-drawer" aria-label="Mobile navigation">
        <a href="{{ route('frontend.products.index') }}">Shop</a>
        <a href="#">New Arrivals</a>
        <a href="#">Collections</a>
        <a href="#">About</a>
        <a href="{{ route('cart.index') }}">Cart (0)</a>
        @auth('web')
            <a href="{{ route('account.dashboard') }}">My Account</a>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}" style="color: var(--rust)!important;">Register →</a>
        @endauth
    </nav>

    <!-- ─── MAIN CONTENT ───────────────────────────────────────────── -->
    <main>
        @yield('content')
    </main>

    <!-- ─── FOOTER ────────────────────────────────────────────────── -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">

                <!-- Brand -->
                <div class="footer-brand gsap-fade-up">
                    <div class="footer-logo">
                        {{ $store_name_prefix ?? 'Karbn' }}<span>{{ $store_name_suffix ?? 'zol' }}</span>
                    </div>
                    <p>Thoughtfully crafted fashion for the modern individual. Quality that endures, style that speaks.
                    </p>
                    <div class="social-row">
                        <a class="social-icon" href="#" aria-label="Instagram">IG</a>
                        <a class="social-icon" href="#" aria-label="Facebook">FB</a>
                        <a class="social-icon" href="#" aria-label="Twitter/X">𝕏</a>
                        <a class="social-icon" href="#" aria-label="Pinterest">PT</a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col gsap-fade-up">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('frontend.products.index') }}">Shop All</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Collections</a></li>
                        <li><a href="#">Lookbook</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div class="footer-col gsap-fade-up">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Returns & Exchanges</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Contact + Newsletter -->
                <div class="footer-col footer-contact gsap-fade-up">
                    <h4>Stay Connected</h4>
                    <p>
                        <a
                            href="tel:{{ $store_phone ?? '+94112345678' }}">{{ $store_phone ?? '+94 11 234 5678' }}</a><br>
                        <a href="mailto:{{ $store_email ?? 'support@karbnzol.com' }}">{{ $store_email ??
                            'support@karbnzol.com' }}</a>
                    </p>
                    <div class="footer-newsletter">
                        <p>Get the newsletter</p>
                        <div class="newsletter-form">
                            <input type="email" placeholder="your@email.com" aria-label="Email for newsletter">
                            <button type="button" aria-label="Subscribe">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="footer-bottom">
                <p>© {{ date('Y') }} {{ $store_name ?? 'Karbnzol' }}. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ─── SCRIPTS ────────────────────────────────────────────────── -->
    <script>
        window.addEventListener('load', () => {

            /* ── Cursor ──────────────────────────────────────────────────── */
            const dot = document.getElementById('cursor-dot');
            const ring = document.getElementById('cursor-ring');
            if (dot && ring && window.matchMedia('(pointer:fine)').matches) {
                let mx = 0, my = 0, rx = 0, ry = 0;
                document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
                (function animRing() {
                    rx += (mx - rx) * 0.12;
                    ry += (my - ry) * 0.12;
                    dot.style.left = mx + 'px'; dot.style.top = my + 'px';
                    ring.style.left = rx + 'px'; ring.style.top = ry + 'px';
                    requestAnimationFrame(animRing);
                })();
                document.querySelectorAll('a, button').forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        dot.style.width = '14px'; dot.style.height = '14px';
                        ring.style.width = '46px'; ring.style.height = '46px';
                    });
                    el.addEventListener('mouseleave', () => {
                        dot.style.width = '8px'; dot.style.height = '8px';
                        ring.style.width = '32px'; ring.style.height = '32px';
                    });
                });
            }

            /* ── Sticky header ───────────────────────────────────────────── */
            const header = document.getElementById('site-header');
            window.addEventListener('scroll', () => {
                header.classList.toggle('scrolled', window.scrollY > 40);
            }, { passive: true });

            /* ── Mobile drawer ───────────────────────────────────────────── */
            const hamburger = document.getElementById('hamburger');
            const drawer = document.getElementById('mobile-drawer');
            const overlay = document.getElementById('drawer-overlay');
            function toggleDrawer(open) {
                hamburger.classList.toggle('open', open);
                drawer.classList.toggle('open', open);
                overlay.classList.toggle('open', open);
                hamburger.setAttribute('aria-expanded', open);
                document.body.style.overflow = open ? 'hidden' : '';
            }
            hamburger.addEventListener('click', () => toggleDrawer(!drawer.classList.contains('open')));
            overlay.addEventListener('click', () => toggleDrawer(false));
            drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', () => toggleDrawer(false)));

            /* ── GSAP ────────────────────────────────────────────────────── */
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            /* Page-enter wipe */
            gsap.to('#page-wipe', {
                scaleY: 0,
                duration: 0.9,
                ease: 'power4.inOut',
                transformOrigin: 'top',
                clearProps: 'all',
                onComplete: () => document.getElementById('page-wipe').style.display = 'none'
            });

            /* Nav entrance */
            gsap.from('#site-header', {
                y: -80, opacity: 0, duration: 0.75, ease: 'power3.out', delay: 0.55
            });

            /* Footer fade-ups (ScrollTrigger) */
            document.querySelectorAll('.gsap-fade-up').forEach((el, i) => {
                gsap.to(el, {
                    opacity: 1, y: 0,
                    duration: 0.8,
                    ease: 'power3.out',
                    delay: i * 0.1,
                    scrollTrigger: {
                        trigger: el,
                        start: 'top 88%',
                        toggleActions: 'play none none none'
                    }
                });
            });

            /* Cart pill bounce */
            const cartPill = document.querySelector('.cart-pill');
            if (cartPill) {
                cartPill.addEventListener('mouseenter', () => {
                    gsap.fromTo(cartPill, { scale: 1 }, {
                        scale: 1.05, duration: 0.18, ease: 'power2.out',
                        onComplete: () => gsap.to(cartPill, { scale: 1, duration: 0.22, ease: 'elastic.out(1.4, 0.5)' })
                    });
                });
            }

            /* Page-leave transition */
            document.querySelectorAll('a[href]').forEach(link => {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto') || href.startsWith('tel') || link.target === '_blank') return;
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const dest = href;
                    const wipe = document.getElementById('page-wipe');
                    wipe.style.display = 'block';
                    wipe.style.transform = 'scaleY(0)';
                    wipe.style.transformOrigin = 'bottom';
                    gsap.to(wipe, {
                        scaleY: 1, duration: 0.55, ease: 'power4.inOut',
                        onComplete: () => { window.location.href = dest; }
                    });
                });
            });
        });
    </script>
</body>

</html>