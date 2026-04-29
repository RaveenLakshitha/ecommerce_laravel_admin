<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ $store_name ?? 'Karbnzol' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    @stack('head')

    <style>
        /* ───────────────────────────────────────────
           CSS VARIABLES & RESET
        ─────────────────────────────────────────── */
        :root {
            --c-bg: #f8fafc;
            --c-surface: #ffffff;
            --c-surface-2: #f1f5f9;
            --c-border: rgba(0, 0, 0, .08);
            --c-accent: #059669;
            --c-accent-2: #f59e0b;
            --c-trust: #2563eb;
            --c-text: #0f172a;
            --c-muted: #64748b;
            --c-white: #ffffff;

            --font-display: 'Space Grotesk', sans-serif;
            --font-body: 'Inter', sans-serif;

            --nav-h: 64px;
            --nav-h-mobile: 56px;

            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 20px;

            --transition: .25s cubic-bezier(.4, 0, .2, 1);
            --shadow-card: 0 4px 32px rgba(0, 0, 0, .45);
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
        }

        body {
            font-family: var(--font-body);
            background: var(--c-bg);
            color: var(--c-text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        img,
        video {
            display: block;
            max-width: 100%;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            cursor: pointer;
            font-family: inherit;
            border: none;
            background: none;
        }

        /* ───────────────────────────────────────────
           UTILITY CLASSES
        ─────────────────────────────────────────── */
        .container {
            width: 100%;
            max-width: 1280px;
            margin-inline: auto;
            padding-inline: clamp(1rem, 4vw, 2rem);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-family: var(--font-display);
            font-weight: 500;
            font-size: .95rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--c-accent);
            color: var(--c-white);
        }

        .btn-primary:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(5, 150, 105, .35);
        }

        .btn-outline {
            background: transparent;
            color: var(--c-text);
            border: 1px solid var(--c-border);
        }

        .btn-outline:hover {
            border-color: rgba(0, 0, 0, .15);
            background: rgba(0, 0, 0, .02);
        }

        .btn-ghost {
            background: rgba(0, 0, 0, .04);
            color: var(--c-text);
            backdrop-filter: blur(8px);
        }

        .btn-ghost:hover {
            background: rgba(0, 0, 0, .08);
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-family: var(--font-display);
            font-size: .75rem;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--c-accent);
            margin-bottom: .75rem;
        }

        .section-label::before {
            content: '';
            display: block;
            width: 20px;
            height: 2px;
            background: var(--c-accent);
        }

        .section-title {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            line-height: 1.15;
            color: var(--c-text);
        }

        .section-title span {
            color: var(--c-accent);
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--c-muted);
            max-width: 540px;
            margin-top: .5rem;
        }

        /* ───────────────────────────────────────────
           NAV
        ─────────────────────────────────────────── */
        #site-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 900;
            height: var(--nav-h);
            display: flex;
            align-items: center;
            border-bottom: 1px solid transparent;
            transition: background var(--transition), border-color var(--transition), backdrop-filter var(--transition);
        }

        #site-nav.scrolled {
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(18px) saturate(1.4);
            border-color: var(--c-border);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--c-text);
            flex-shrink: 0;
        }

        .nav-logo-mark {
            width: 32px;
            height: 32px;
            background: var(--c-accent);
            border-radius: 4px;
            display: grid;
            place-items: center;
        }

        .nav-logo-mark svg {
            width: 18px;
            height: 18px;
            fill: white;
        }

        /* Desktop links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: .25rem;
            list-style: none;
        }

        .nav-links a {
            padding: .45rem .85rem;
            border-radius: var(--radius-sm);
            font-size: .88rem;
            font-weight: 400;
            color: rgba(15, 23, 42, .7);
            transition: var(--transition);
            letter-spacing: .01em;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--c-text);
            background: rgba(0, 0, 0, .05);
        }

        .nav-links .nav-badge {
            display: inline-block;
            background: var(--c-accent);
            color: white;
            font-size: .6rem;
            font-family: var(--font-display);
            font-weight: 600;
            padding: 1px 5px;
            border-radius: 3px;
            margin-left: .3rem;
            vertical-align: middle;
            letter-spacing: .04em;
        }

        /* Nav actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .nav-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            color: var(--c-muted);
            transition: var(--transition);
            position: relative;
        }

        .nav-icon-btn:hover {
            background: rgba(0, 0, 0, .07);
            color: var(--c-text);
        }

        .nav-icon-btn .badge-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 7px;
            height: 7px;
            background: var(--c-accent);
            border-radius: 50%;
            border: 2px solid var(--c-bg);
        }

        .nav-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--c-accent), var(--c-accent-2));
            display: grid;
            place-items: center;
            font-family: var(--font-display);
            font-size: .85rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .nav-user-avatar:hover {
            border-color: var(--c-accent);
        }

        /* Hamburger */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            padding: 8px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }

        .nav-hamburger:hover {
            background: rgba(255, 255, 255, .07);
        }

        .nav-hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--c-text);
            border-radius: 2px;
            transition: var(--transition);
        }

        .nav-hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .nav-hamburger.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .nav-hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Mobile drawer */
        .nav-mobile-drawer {
            display: none;
            position: fixed;
            top: var(--nav-h-mobile);
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, .97);
            backdrop-filter: blur(24px);
            z-index: 850;
            padding: 1.25rem 1.25rem 2rem;
            border-bottom: 1px solid var(--c-border);
            transform: translateY(-8px);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease, transform .2s ease;
        }

        .nav-mobile-drawer.open {
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
        }

        .nav-mobile-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .25rem;
            margin-bottom: 1.25rem;
        }

        .nav-mobile-links a {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .7rem .75rem;
            border-radius: var(--radius-sm);
            font-size: .95rem;
            color: rgba(15, 23, 42, .8);
            transition: var(--transition);
        }

        .nav-mobile-links a:hover,
        .nav-mobile-links a.active {
            background: rgba(0, 0, 0, .07);
            color: var(--c-text);
        }

        .nav-mobile-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .6rem;
        }

        .nav-mobile-divider {
            height: 1px;
            background: var(--c-border);
            margin: .75rem 0;
        }

        /* ───────────────────────────────────────────
           PAGE WRAPPER
        ─────────────────────────────────────────── */
        #page-wrapper {
            min-height: 100vh;
            padding-top: var(--nav-h);
        }

        /* Hero pages start right at nav, regular pages get padding */
        #page-wrapper.hero-page {
            padding-top: 0;
        }

        /* ───────────────────────────────────────────
           MAIN CONTENT SLOT
        ─────────────────────────────────────────── */
        #main-content {
            width: 100%;
        }

        /* ───────────────────────────────────────────
           FOOTER
        ─────────────────────────────────────────── */
        #site-footer {
            background: var(--c-white);
            border-top: 1px solid var(--c-border);
            padding-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.8fr repeat(3, 1fr);
            gap: 3rem;
            padding-bottom: 3.5rem;
            border-bottom: 1px solid var(--c-border);
        }

        .footer-brand p {
            font-size: .9rem;
            color: var(--c-muted);
            line-height: 1.7;
            margin-top: .75rem;
            max-width: 280px;
        }

        .footer-social {
            display: flex;
            gap: .5rem;
            margin-top: 1.25rem;
        }

        .footer-social-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--c-border);
            display: grid;
            place-items: center;
            color: var(--c-muted);
            transition: var(--transition);
        }

        .footer-social-btn:hover {
            background: rgba(0, 0, 0, .07);
            color: var(--c-text);
            border-color: rgba(0, 0, 0, .15);
        }

        .footer-col h4 {
            font-family: var(--font-display);
            font-weight: 500;
            font-size: .82rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--c-muted);
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .footer-links a {
            font-size: .9rem;
            color: rgba(15, 23, 42, .6);
            transition: color var(--transition);
        }

        .footer-links a:hover {
            color: var(--c-text);
        }

        .footer-app-badges {
            display: flex;
            flex-direction: column;
            gap: .6rem;
            margin-top: 1rem;
        }

        .footer-app-badge {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem .85rem;
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }

        .footer-app-badge:hover {
            border-color: rgba(0, 0, 0, .18);
            background: rgba(0, 0, 0, .04);
        }

        .footer-app-badge svg {
            flex-shrink: 0;
        }

        .footer-app-badge-text {
            display: flex;
            flex-direction: column;
        }

        .footer-app-badge-text small {
            font-size: .65rem;
            color: var(--c-muted);
            line-height: 1;
        }

        .footer-app-badge-text strong {
            font-family: var(--font-display);
            font-size: .82rem;
            font-weight: 500;
            color: var(--c-text);
            line-height: 1.3;
        }

        /* Footer bottom bar */
        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding-block: 1.5rem;
        }

        .footer-bottom-left {
            font-size: .82rem;
            color: var(--c-muted);
        }

        .footer-bottom-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .footer-bottom-right a {
            font-size: .82rem;
            color: var(--c-muted);
            transition: color var(--transition);
        }

        .footer-bottom-right a:hover {
            color: var(--c-text);
        }

        .footer-lang-selector {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: var(--c-muted);
            background: rgba(0, 0, 0, .04);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            padding: .3rem .65rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .footer-lang-selector:hover {
            border-color: rgba(0, 0, 0, .15);
            color: var(--c-text);
        }

        /* ───────────────────────────────────────────
           FLASH MESSAGES / ALERTS
        ─────────────────────────────────────────── */
        .flash-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .6rem;
            pointer-events: none;
        }

        .flash-msg {
            pointer-events: all;
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .85rem 1rem;
            border-radius: var(--radius-md);
            font-size: .88rem;
            font-weight: 500;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, .1);
            box-shadow: var(--shadow-card);
            animation: flashIn .35s cubic-bezier(.34, 1.56, .64, 1) both;
            max-width: 360px;
        }

        .flash-msg.success {
            background: rgba(16, 185, 129, .12);
            color: #6ee7b7;
            border-color: rgba(16, 185, 129, .25);
        }

        .flash-msg.error {
            background: rgba(239, 68, 68, .12);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, .25);
        }

        .flash-msg.info {
            background: rgba(59, 130, 246, .12);
            color: #93c5fd;
            border-color: rgba(59, 130, 246, .25);
        }

        .flash-msg.warning {
            background: rgba(245, 158, 11, .12);
            color: #fcd34d;
            border-color: rgba(245, 158, 11, .25);
        }

        .flash-close {
            margin-left: auto;
            opacity: .5;
            transition: opacity .15s;
            padding: 2px;
        }

        .flash-close:hover {
            opacity: 1;
        }

        @keyframes flashIn {
            from {
                opacity: 0;
                transform: translateX(20px) scale(.95);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes flashOut {
            to {
                opacity: 0;
                transform: translateX(20px) scale(.95);
                max-height: 0;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
        }

        /* ───────────────────────────────────────────
           BACK-TO-TOP
        ─────────────────────────────────────────── */
        #back-to-top {
            position: fixed;
            bottom: 1.75rem;
            left: 1.75rem;
            z-index: 800;
            width: 42px;
            height: 42px;
            background: var(--c-surface-2);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            color: var(--c-muted);
            opacity: 0;
            pointer-events: none;
            transform: translateY(8px);
            transition: opacity .25s, transform .25s, background var(--transition), color var(--transition);
        }

        #back-to-top.visible {
            opacity: 1;
            pointer-events: all;
            transform: translateY(0);
        }

        #back-to-top:hover {
            background: var(--c-accent);
            color: white;
            border-color: transparent;
        }

        /* ───────────────────────────────────────────
           LOADING OVERLAY (page transitions)
        ─────────────────────────────────────────── */
        #page-loader {
            position: fixed;
            inset: 0;
            z-index: 9990;
            background: var(--c-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .4s ease;
        }

        #page-loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader-logo {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.8rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--c-text);
        }

        .loader-logo span {
            color: var(--c-accent);
        }

        .loader-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--c-accent), var(--c-accent-2));
            border-radius: 0 2px 0 0;
            animation: loaderBar 1s cubic-bezier(.4, 0, .2, 1) forwards;
        }

        @keyframes loaderBar {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }

        /* ───────────────────────────────────────────
           RESPONSIVE
        ─────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            :root {
                --nav-h: var(--nav-h-mobile);
            }

            .nav-links,
            .nav-actions .btn {
                display: none;
            }

            .nav-hamburger {
                display: flex;
            }

            .nav-mobile-drawer {
                display: block;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding-bottom: 2.5rem;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }

            .flash-container {
                left: 1rem;
                right: 1rem;
                bottom: 1rem;
            }

            .flash-msg {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .nav-actions .nav-icon-btn:not(:last-child) {
                display: none;
            }
        }
    </style>

    @yield('styles')
</head>

<body class="{{ $bodyClass ?? '' }}">

    {{-- ─────────────────────────────────────────────
    PAGE LOADER
    ───────────────────────────────────────────── --}}
    <div id="page-loader" aria-hidden="true">
        <div class="loader-logo">Karb<span>n</span>zol</div>
        <div class="loader-bar"></div>
    </div>

    {{-- ─────────────────────────────────────────────
    NAVIGATION
    ───────────────────────────────────────────── --}}
    <nav id="site-nav" role="navigation" aria-label="Main navigation">
        <div class="container nav-inner">

            {{-- Logo --}}
            <a href="#" class="nav-logo" aria-label="Karbnzol — Home">
                <div class="nav-logo-mark">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM19 17H5v-5h14v5z" />
                        <circle cx="7.5" cy="14.5" r="1.5" />
                        <circle cx="16.5" cy="14.5" r="1.5" />
                    </svg>
                </div>
                Karbnzol
            </a>

            {{-- Desktop navigation links --}}
            <ul class="nav-links" role="list">
                <li>
                    <a href="#" class="{{ request()->routeIs('cars.*') ? 'active' : '' }}">
                        Browse Cars
                    </a>
                </li>
                <li>
                    <a href="#" class="{{ request()->routeIs('cars.new') ? 'active' : '' }}">
                        New Cars
                    </a>
                </li>
                <li>
                    <a href="#" class="{{ request()->routeIs('cars.used') ? 'active' : '' }}">
                        Used Cars
                    </a>
                </li>
                <li>
                    <a href="#" class="{{ request()->routeIs('cars.certified') ? 'active' : '' }}">
                        Certified
                        <span class="nav-badge">✓</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="{{ request()->routeIs('dealers.*') ? 'active' : '' }}">
                        Dealers
                    </a>
                </li>
            </ul>

            {{-- Desktop actions --}}
            <div class="nav-actions">
                {{-- Search icon --}}
                <button class="nav-icon-btn" aria-label="Search" id="nav-search-toggle">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                </button>

                {{-- Favourites --}}
                <a href="#" class="nav-icon-btn" aria-label="Saved cars">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                    @auth
                        @if(auth()->user()->favourites_count ?? 0)
                            <span class="badge-dot"></span>
                        @endif
                    @endauth
                </a>

                {{-- Sell CTA --}}
                <a href="#" class="btn btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Sell My Car
                </a>

                {{-- Auth --}}
                @auth
                    <div class="nav-user-avatar" title="{{ auth()->user()->name }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @else
                    <a href="#" class="btn btn-ghost" style="padding:.55rem 1rem;">
                        Sign In
                    </a>
                @endauth
            </div>

            {{-- Hamburger (mobile) --}}
            <button class="nav-hamburger" id="nav-hamburger" aria-label="Open menu" aria-expanded="false"
                aria-controls="nav-mobile-drawer">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>

        {{-- ── Mobile Drawer ── --}}
        <div id="nav-mobile-drawer" class="nav-mobile-drawer" aria-hidden="true">
            <ul class="nav-mobile-links" role="list">
                <li>
                    <a href="#" class="{{ request()->routeIs('cars.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="10" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        Browse Cars
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                        New Cars
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                        Used Cars
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                        Certified Vehicles
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                        Dealers
                    </a>
                </li>
            </ul>
            <div class="nav-mobile-divider"></div>
            <div class="nav-mobile-actions">
                <a href="#" class="btn btn-primary" style="justify-content:center;">
                    + Sell My Car
                </a>
                @auth
                    <a href="#" class="btn btn-outline" style="justify-content:center;">
                        My Account
                    </a>
                @else
                    <a href="#" class="btn btn-outline" style="justify-content:center;">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ─────────────────────────────────────────────
    FLASH MESSAGES
    ───────────────────────────────────────────── --}}
    <div class="flash-container" role="status" aria-live="polite">
        @foreach(['success', 'error', 'warning', 'info'] as $type)
            @if(session($type))
                <div class="flash-msg {{ $type }}">
                    {{-- Icon --}}
                    @if($type === 'success')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                    @elseif($type === 'error')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M15 9l-6 6M9 9l6 6" />
                        </svg>
                    @elseif($type === 'warning')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    @else
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 8v4m0 4h.01" />
                        </svg>
                    @endif
                    <span>{{ session($type) }}</span>
                    <button class="flash-close" aria-label="Dismiss">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- ─────────────────────────────────────────────
    PAGE WRAPPER
    ───────────────────────────────────────────── --}}
    <div id="page-wrapper" class="@yield('pageWrapperClass')">

        {{-- Optional: breadcrumbs slot --}}
        @hasSection('breadcrumbs')
            <div style="padding-block:.75rem; border-bottom:1px solid var(--c-border);">
                <div class="container">
                    @yield('breadcrumbs')
                </div>
            </div>
        @endif

        {{-- ── MAIN CONTENT ── --}}
        <main id="main-content" role="main">
            @yield('content')
        </main>

    </div>{{-- /#page-wrapper --}}

    {{-- ─────────────────────────────────────────────
    FOOTER
    ───────────────────────────────────────────── --}}
    <footer id="site-footer" role="contentinfo">
        <div class="container">

            <div class="footer-grid">

                {{-- Brand column --}}
                <div class="footer-brand">
                    <a href="#" class="nav-logo" style="font-size:1.2rem;">
                        <div class="nav-logo-mark" style="width:28px;height:28px;">
                            <svg viewBox="0 0 24 24" style="width:15px;height:15px;" fill="white">
                                <path
                                    d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM19 17H5v-5h14v5z" />
                                <circle cx="7.5" cy="14.5" r="1.5" />
                                <circle cx="16.5" cy="14.5" r="1.5" />
                            </svg>
                        </div>
                        Karbnzol
                    </a>
                    <p>Sri Lanka's most trusted marketplace to buy and sell cars. Thousands of verified listings across
                        every district.</p>
                    <div class="footer-social">
                        <a href="#" class="footer-social-btn" aria-label="Facebook">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-btn" aria-label="Instagram">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <rect x="2" y="2" width="20" height="20" rx="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-btn" aria-label="YouTube">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path
                                    d="M22.54 6.42a2.78 2.78 0 0 0-1.94-1.96C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.4 19.54C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z" />
                                <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-btn" aria-label="WhatsApp">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Buying --}}
                <div class="footer-col">
                    <h4>Buy</h4>
                    <ul class="footer-links">
                        <li><a href="#">Browse All Cars</a></li>
                        <li><a href="#">New Cars</a></li>
                        <li><a href="#">Used Cars</a></li>
                        <li><a href="#">Certified Pre-Owned</a></li>
                        <li><a href="#">Cars by Brand</a></li>
                        <li><a href="#">Cars by Body Type</a></li>
                        <li><a href="#">EMI Calculator</a></li>
                        <li><a href="#">Buying Guide</a></li>
                    </ul>
                </div>

                {{-- Selling --}}
                <div class="footer-col">
                    <h4>Sell</h4>
                    <ul class="footer-links">
                        <li><a href="#">Sell My Car</a></li>
                        <li><a href="#">Instant Valuation</a></li>
                        <li><a href="#">Dealer Registration</a></li>
                        <li><a href="#">Advertise With Us</a></li>
                        <li><a href="#">Selling Tips</a></li>
                        <li><a href="#">Pricing Plans</a></li>
                    </ul>
                </div>

                {{-- Company / App --}}
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Blog & Guides</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Safety Tips</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>

                    <div class="footer-app-badges" style="margin-top:1.5rem;">
                        <a href="#" class="footer-app-badge">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"
                                style="color:var(--c-muted)">
                                <path
                                    d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" />
                            </svg>
                            <div class="footer-app-badge-text">
                                <small>Download on the</small>
                                <strong>App Store</strong>
                            </div>
                        </a>
                        <a href="#" class="footer-app-badge">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"
                                style="color:var(--c-muted)">
                                <path
                                    d="M3.18 23.76c.35.2.73.24 1.1.12l.1-.06 10.72-6.2-2.3-2.31-9.62 8.45zm14.3-8.28L15 13l2.5-2.5L20.56 12c.84.49.84 1.28 0 1.77l-3.08 1.71zM2.3.37C2.1.58 2 .9 2 1.29V22.7c0 .39.11.71.31.92l.06.05 11.9-11.92v-.28L2.36.31 2.3.37zm8.2 8.16l-8.2-8.2" />
                            </svg>
                            <div class="footer-app-badge-text">
                                <small>Get it on</small>
                                <strong>Google Play</strong>
                            </div>
                        </a>
                    </div>
                </div>

            </div>{{-- /.footer-grid --}}

            {{-- Bottom bar --}}
            <div class="footer-bottom">
                <p class="footer-bottom-left">
                    &copy; {{ date('Y') }} Karbnzol (Pvt) Ltd. All rights reserved. Sri Lanka 🇱🇰
                </p>
                <div class="footer-bottom-right">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Use</a>
                    <a href="#">Cookie Policy</a>
                    <a href="#">Safety Tips</a>

                    {{-- Language selector --}}
                    <button class="footer-lang-selector" aria-label="Select language">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="10" />
                            <path
                                d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                        English
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>{{-- /.container --}}
    </footer>

    {{-- ─────────────────────────────────────────────
    BACK TO TOP
    ───────────────────────────────────────────── --}}
    <button id="back-to-top" aria-label="Back to top">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M18 15l-6-6-6 6" />
        </svg>
    </button>

    {{-- ─────────────────────────────────────────────
    LAYOUT JAVASCRIPT
    ───────────────────────────────────────────── --}}
    <script>
        (function () {
            'use strict';

            /* ── Page loader ── */
            window.addEventListener('load', function () {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    setTimeout(() => loader.classList.add('hidden'), 600);
                }
            });

            /* ── Nav: scroll state ── */
            const nav = document.getElementById('site-nav');
            function updateNav() {
                nav && nav.classList.toggle('scrolled', window.scrollY > 40);
            }
            updateNav();
            window.addEventListener('scroll', updateNav, { passive: true });

            /* ── Nav: mobile hamburger ── */
            const hamburger = document.getElementById('nav-hamburger');
            const drawer = document.getElementById('nav-mobile-drawer');

            if (hamburger && drawer) {
                hamburger.addEventListener('click', function () {
                    const open = hamburger.classList.toggle('open');
                    hamburger.setAttribute('aria-expanded', String(open));
                    drawer.classList.toggle('open', open);
                    drawer.setAttribute('aria-hidden', String(!open));
                    document.body.style.overflow = open ? 'hidden' : '';
                });

                /* Close on outside click */
                document.addEventListener('click', function (e) {
                    if (!nav.contains(e.target) && drawer.classList.contains('open')) {
                        hamburger.classList.remove('open');
                        drawer.classList.remove('open');
                        hamburger.setAttribute('aria-expanded', 'false');
                        drawer.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }
                });

                /* Close on nav link click */
                drawer.querySelectorAll('a').forEach(function (a) {
                    a.addEventListener('click', function () {
                        hamburger.classList.remove('open');
                        drawer.classList.remove('open');
                        hamburger.setAttribute('aria-expanded', 'false');
                        drawer.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    });
                });
            }

            /* ── Flash messages auto-dismiss ── */
            document.querySelectorAll('.flash-msg').forEach(function (msg) {
                /* Auto dismiss after 5s */
                const timer = setTimeout(() => dismissFlash(msg), 5000);

                const closeBtn = msg.querySelector('.flash-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function () {
                        clearTimeout(timer);
                        dismissFlash(msg);
                    });
                }
            });

            function dismissFlash(el) {
                el.style.animation = 'flashOut .3s ease forwards';
                setTimeout(() => el.remove(), 320);
            }

            /* ── Back to top ── */
            const btt = document.getElementById('back-to-top');
            if (btt) {
                window.addEventListener('scroll', function () {
                    btt.classList.toggle('visible', window.scrollY > 400);
                }, { passive: true });

                btt.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            /* ── Nav search toggle (hook for search overlay) ── */
            const searchToggle = document.getElementById('nav-search-toggle');
            if (searchToggle) {
                searchToggle.addEventListener('click', function () {
                    /* Dispatch custom event — implement overlay in your page JS */
                    document.dispatchEvent(new CustomEvent('karbnzol:search-open'));
                });
            }

        })();
    </script>

    @stack('scripts')

</body>

</html>
