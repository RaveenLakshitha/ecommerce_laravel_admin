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
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap"
        rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    <style>
        /* ════════════════════════════════════════════
           VARIABLES
        ════════════════════════════════════════════ */
        :root {
            --bg: #1a1a1a;
            --bg-2: #222222;
            --bg-3: #2a2a2a;
            --bg-4: #333333;
            --bg-panel: #1e1e1e;
            --white: #ffffff;
            --off-white: #f0f0f0;
            --silver: #aaaaaa;
            --dim: #666666;
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

        /* ── PAGE WIPE ──────────────────────────── */
        #page-wipe {
            position: fixed;
            inset: 0;
            background: var(--bg-3);
            z-index: 9000;
            transform: scaleX(1);
            transform-origin: right;
            pointer-events: none;
        }

        /* ── ANNOUNCEMENT BAR ───────────────────── */
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

        /* ════════════════════════════════════════════
           HEADER
        ════════════════════════════════════════════ */
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

        /* ── LOGO ───────────────────────────────── */
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

        /* ════════════════════════════════════════════
           NAV LINKS + DROPDOWN SYSTEM
        ════════════════════════════════════════════ */
        .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
        }

        /* Each top-level item */
        .nav-item {
            position: relative;
        }

        .nav-item>a {
            display: flex;
            align-items: center;
            gap: 0.3rem;
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
            white-space: nowrap;
        }

        .nav-item>a::after {
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

        .nav-item>a:hover,
        .nav-item>a.active {
            color: var(--white);
        }

        .nav-item>a:hover::after,
        .nav-item>a.active::after {
            transform: scaleX(1);
        }

        /* Chevron on items that have dropdowns */
        .nav-item.has-drop>a .nav-chevron {
            display: inline-block;
            width: 10px;
            height: 10px;
            flex-shrink: 0;
            transition: transform 0.25s var(--ease-out);
        }

        .nav-item.has-drop:hover>a .nav-chevron,
        .nav-item.has-drop.dd-open>a .nav-chevron {
            transform: rotate(180deg);
        }

        /* ── GOLD BOTTOM LINE on hover / open for items WITH dropdown ── */
        .nav-item.has-drop:hover>a,
        .nav-item.has-drop.dd-open>a {
            color: var(--white);
        }

        .nav-item.has-drop:hover>a::after,
        .nav-item.has-drop.dd-open>a::after {
            transform: scaleX(1);
        }

        /* ── NEW badge pill ───────────────────────── */
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

        /* ════════════════════════════════════════════
           MEGA DROPDOWN PANEL
        ════════════════════════════════════════════ */

        /* Simple/small dropdowns – sit under their trigger */
        .nav-dropdown {
            position: absolute;
            top: calc(var(--nav-h) - 1px);
            left: 50%;
            transform: translateX(-50%) translateY(-8px);
            min-width: 280px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.22s var(--ease-out), transform 0.22s var(--ease-out);
            z-index: 300;
            background: var(--bg-2);
            border: 1px solid var(--bg-4);
            border-top: 2px solid var(--gold);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.55);
        }

        /* Mega dropdowns: position fixed, always full viewport width */
        .nav-dropdown.mega {
            position: fixed;
            left: 0;
            right: 0;
            width: 100%;
            transform: translateY(-8px);
            min-width: 0;
        }

        /* Invisible hover bridge — fills any pixel gap between the
           nav link bottom and the dropdown top so the cursor never
           "escapes" into the void and triggers a close             */
        .nav-item.has-drop::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: transparent;
            z-index: 299;
        }

        /* JS adds .dd-open to show — NOT :hover CSS anymore */
        .nav-item.has-drop.dd-open>.nav-dropdown:not(.mega) {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }

        .nav-item.has-drop.dd-open>.nav-dropdown.mega {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* ── Dropdown inner layout ──────────────────
           Full viewport width. Promo image is fixed.
           Columns take all remaining space.           */
        .dd-inner {
            display: flex;
            padding: 0;
            width: 100%;
            min-height: 280px;
        }

        /* Fixed-size promo image column */
        .dd-promo {
            width: 300px;
            min-width: 300px;
            max-width: 300px;
            min-height: 300px;
            background: var(--bg-3);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.75rem;
            flex-shrink: 0;
            flex-grow: 0;
        }

        .dd-promo-img {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center top;
            filter: brightness(0.42);
        }

        .dd-promo-label {
            position: relative;
            z-index: 1;
            font-family: var(--font-display);
            font-size: 0.56rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.3rem;
        }

        .dd-promo-title {
            position: relative;
            z-index: 1;
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--white);
            line-height: 0.95;
            margin-bottom: 1rem;
        }

        .dd-promo-cta {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--gold);
            color: var(--bg);
            padding: 0.4rem 0.875rem;
            font-family: var(--font-display);
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            transition: background 0.2s;
            width: fit-content;
        }

        .dd-promo-cta:hover {
            background: var(--white);
        }

        /* Column groups — take all remaining width */
        .dd-cols {
            flex: 1;
            display: flex;
            padding: 2rem 2rem 2rem 0;
            min-width: 0;
        }

        .dd-col {
            flex: 1;
            padding: 0 2.25rem;
            border-right: 1px solid var(--bg-4);
            min-width: 0;
        }

        .dd-col:first-child {
            padding-left: 2.5rem;
        }

        .dd-col:last-child {
            border-right: none;
            padding-right: 2.5rem;
        }

        .dd-col-head {
            font-family: var(--font-display);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1rem;
            padding-bottom: 0.625rem;
            border-bottom: 1px solid var(--bg-4);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dd-col-head::before {
            content: '';
            width: 16px;
            height: 1.5px;
            background: var(--gold);
        }

        .dd-col ul {
            list-style: none;
        }

        .dd-col ul li {
            margin-bottom: 0.1rem;
        }

        .dd-col ul a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.45rem 0.5rem;
            font-size: 0.9rem;
            color: var(--silver);
            font-weight: 400;
            letter-spacing: 0.02em;
            transition: color 0.15s, background 0.15s, padding-left 0.2s;
            border-radius: 2px;
        }

        .dd-col ul a:hover {
            color: var(--off-white);
            background: var(--bg-3);
            padding-left: 1rem;
        }

        .dd-col ul a:hover .dd-arrow {
            opacity: 1;
        }

        .dd-arrow {
            opacity: 0;
            color: var(--gold);
            transition: opacity 0.15s;
            flex-shrink: 0;
        }

        /* Featured / badge items inside dropdown */
        .dd-item-badge {
            display: inline-block;
            background: var(--red);
            color: var(--white);
            font-size: 0.44rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.1rem 0.3rem;
            margin-left: 0.4rem;
            border-radius: 2px;
            vertical-align: middle;
        }

        .dd-item-badge.gold {
            background: var(--gold);
            color: var(--bg);
        }

        /* ── Simple (non-mega) dropdown ─────────── */
        .nav-dropdown.simple {
            min-width: 280px;
        }

        .dd-simple-list {
            list-style: none;
            padding: 0.625rem 0;
        }

        .dd-simple-list li {}

        .dd-simple-list a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 1.5rem;
            font-size: 0.875rem;
            color: var(--silver);
            letter-spacing: 0.04em;
            transition: color 0.15s, background 0.15s, padding-left 0.2s;
        }

        .dd-simple-list a:hover {
            color: var(--off-white);
            background: var(--bg-3);
            padding-left: 1.75rem;
        }

        .dd-simple-list a:hover .dd-arrow {
            opacity: 1;
        }

        .dd-simple-divider {
            height: 1px;
            background: var(--bg-4);
            margin: 0.5rem 0;
        }

        /* ── Bottom all-items link — full width ──── */
        .dd-footer {
            border-top: 1px solid var(--bg-4);
            padding: 0.875rem 2.5rem 0.875rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .dd-footer a {
            font-family: var(--font-display);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: gap 0.2s, opacity 0.2s;
        }

        .dd-footer a:hover {
            gap: 0.65rem;
        }

        .dd-footer-count {
            font-family: var(--font-display);
            font-size: 0.6rem;
            letter-spacing: 0.1em;
            color: var(--dim);
        }

        /* ════════════════════════════════════════════
           RIGHT ICONS
        ════════════════════════════════════════════ */
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
            top: 10px;
            right: 6px;
            width: 20px;
            height: 20px;
            background: var(--gold);
            color: var(--bg);
            font-family: var(--font-display);
            font-size: 0.62rem;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

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

        /* ════════════════════════════════════════════
           HAMBURGER + MOBILE NAV
        ════════════════════════════════════════════ */
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

        /* Mobile nav drawer */
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
            max-height: calc(100vh - var(--nav-h));
            overflow-y: auto;
        }

        .mobile-nav.open {
            transform: translateY(0);
        }

        /* Mobile accordion items */
        .mob-item {
            border-bottom: 1px solid var(--bg-4);
        }

        .mob-item-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1.5rem;
            cursor: pointer;
            font-family: var(--font-display);
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--silver);
            transition: color 0.2s, background 0.2s;
            user-select: none;
        }

        .mob-item-head:hover {
            color: var(--white);
            background: var(--bg-3);
        }

        .mob-item-head a {
            flex: 1;
            color: inherit;
        }

        .mob-chevron {
            width: 14px;
            height: 14px;
            color: var(--dim);
            transition: transform 0.25s var(--ease-out);
            flex-shrink: 0;
        }

        .mob-item.open .mob-chevron {
            transform: rotate(180deg);
            color: var(--gold);
        }

        .mob-item.open .mob-item-head {
            color: var(--white);
        }

        /* Mobile sub-links */
        .mob-sub {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s var(--ease-out);
            background: var(--bg-2);
        }

        .mob-item.open .mob-sub {
            max-height: 600px;
        }

        .mob-sub-section {
            padding: 0.5rem 0 0.25rem;
        }

        .mob-sub-head {
            font-family: var(--font-display);
            font-size: 0.56rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gold);
            padding: 0.4rem 1.5rem 0.3rem 2rem;
        }

        .mob-sub a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1.5rem 0.5rem 2rem;
            font-size: 0.85rem;
            color: var(--silver);
            letter-spacing: 0.03em;
            transition: color 0.15s, background 0.15s;
        }

        .mob-sub a:hover {
            color: var(--off-white);
            background: var(--bg-3);
        }

        .mob-sub-divider {
            height: 1px;
            background: var(--bg-4);
            margin: 0.4rem 1.5rem;
        }

        /* Simple mobile links (no sub) */
        .mob-plain a {
            display: block;
            padding: 0.875rem 1.5rem;
            font-family: var(--font-display);
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--silver);
            transition: color 0.2s, background 0.2s, padding-left 0.2s;
        }

        .mob-plain a:hover {
            color: var(--white);
            background: var(--bg-3);
            padding-left: 2rem;
        }

        .mob-plain.gold-link a {
            color: var(--gold);
        }

        /* Mobile auth section */
        .mob-auth {
            padding: 1rem 1.5rem;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            border-top: 1px solid var(--bg-4);
        }

        .mob-auth a {
            flex: 1;
            text-align: center;
            padding: 0.65rem;
            font-family: var(--font-display);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .mob-auth .mob-signin {
            border: 1px solid var(--bg-4);
            color: var(--silver);
            transition: all 0.2s;
        }

        .mob-auth .mob-signin:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .mob-auth .mob-register {
            background: var(--gold);
            color: var(--bg);
        }

        .mob-auth .mob-register:hover {
            background: var(--off-white);
        }

        /* ════════════════════════════════════════════
           MAIN + FOOTER
        ════════════════════════════════════════════ */
        main {
            min-height: 80vh;
        }

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

        .g-up {
            opacity: 0;
            transform: translateY(22px);
        }

        /* ════════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════════ */
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
        /* ── Language Switcher ──────────────────── */
        .lang-switcher .lang-text {
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--gold);
        }

        .lang-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.65rem 1.5rem;
            background: none;
            border: none;
            color: var(--silver);
            font-family: var(--font-body);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .lang-btn:hover {
            color: var(--white);
            background: var(--bg-3);
        }

        .lang-btn.active {
            color: var(--gold);
            font-weight: 600;
        }

        .mob-lang-row {
            padding: 1rem 1.5rem;
            display: flex;
            gap: 1rem;
            border-bottom: 1px solid var(--bg-4);
        }

        .mob-lang-btn {
            font-family: var(--font-display);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            color: var(--silver);
            text-transform: uppercase;
            background: none;
            border: 1px solid var(--bg-4);
            padding: 0.5rem 1rem;
            flex: 1;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mob-lang-btn.active {
            border-color: var(--gold);
            color: var(--gold);
        }
    </style>
</head>

<body>
    <div id="page-wipe"></div>

    <!-- Announcement bar -->
    <div class="announce-bar" aria-live="polite">
        <span class="announce-inner">
            @php
                $announcement = $storefront_offer_text ?? 'FREE DELIVERY ON ORDERS OVER RS. 5,000 &nbsp;·&nbsp; NEW ARRIVALS EVERY FRIDAY &nbsp;·&nbsp; MINTPAY — PAY IN 3 EASY INSTALLMENTS';
                // Repeat it to fill the marquee
                $repeated = str_repeat($announcement . ' &nbsp;·&nbsp; ', 4);
            @endphp
            {!! $repeated !!}
        </span>
    </div>

    <!-- ═══════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════ -->
    <header class="site-header" id="siteHeader">
        <div class="nav-wrap"><!-- Logo -->
            <a href="{{ route('home') }}" class="nav-logo">
                <span class="logo-main">KARBN<em>ZOL</em></span>
                <span class="logo-sub">T-Shirts · Jeans · Chinos</span>
            </a>

            <!-- Desktop Nav -->
            <nav aria-label="Main navigation">
                <ul class="nav-links">

                    <!-- Home (no dropdown) -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="active">{{ __('file.home') }}</a>
                    </li>

                    <!-- New Arrivals — simple dropdown -->
                    <li class="nav-item has-drop">
                        <a href="#">
                            {{ __('file.new_arrivals') }}
                            <span class="new-pill">{{ __('file.new') }}</span>
                            <svg class="nav-chevron" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 1 5 5 9 1"/></svg>
                        </a>
                        <div class="nav-dropdown simple" role="menu">
                            <ul class="dd-simple-list">
                                <li><a href="#" role="menuitem">{{ __('file.view_all_new_arrivals') }} <span class="dd-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span></a></li>
                                <div class="dd-simple-divider"></div>
                                <li><a href="#" role="menuitem">{{ __('file.this_weeks_drop') }} <span class="dd-item-badge">{{ __('file.hot') }}</span> <span class="dd-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span></a></li>
                                <li><a href="#" role="menuitem">{{ __('file.new_mens_styles') }} <span class="dd-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span></a></li>
                                <li><a href="#" role="menuitem">{{ __('file.new_kids_styles') }} <span class="dd-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span></a></li>
                                <li><a href="#" role="menuitem">{{ __('file.new_activewear') }} <span class="dd-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span></a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Dynamic Categories — MEGA dropdown -->
                    @foreach($globalCategories as $category)
                        <li class="nav-item {{ $category->children->count() > 0 ? 'has-drop' : '' }}">
                            <a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}" class="{{ request('category') == $category->slug ? 'active' : '' }}">
                                {{ $category->name }}
                                @if($category->children->count() > 0)
                                    <svg class="nav-chevron" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 1 5 5 9 1"/></svg>
                                @endif
                            </a>
                            @if($category->children->count() > 0)
                                <div class="nav-dropdown mega" role="menu">
                                    <div class="dd-inner">
                                        <!-- Promo image column -->
                                        <div class="dd-promo">
                                            <div class="dd-promo-img" style="background-image: url('{{ $category->image_url ?? $category->banner_urls[0] ?? asset('images/default-banner.png') }}');"></div>
                                            <p class="dd-promo-label">{{ $category->name }}</p>
                                            <p class="dd-promo-title">NEW<br>SEASON</p>
                                            <a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}" class="dd-promo-cta">
                                                Shop {{ $category->name }}
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                            </a>
                                        </div>
                                        <!-- Category columns -->
                                        <div class="dd-cols" style="flex-wrap: wrap; gap: 1rem 0;">
                                            @foreach($category->children as $child)
                                                @php
                                                    $grandchildChunks = $child->children->isNotEmpty() ? $child->children->chunk(8) : collect([collect()]);
                                                @endphp
                                                @foreach($grandchildChunks as $index => $chunk)
                                                    <div class="dd-col" style="min-width: 200px; flex: 0 0 auto; margin-bottom: 2rem;">
                                                        <p class="dd-col-head">
                                                            <a href="{{ route('frontend.products.index', ['category' => $child->slug]) }}" style="text-decoration:none; color:inherit; {{ $index > 0 ? 'visibility:hidden;' : '' }}">{{ $child->name }}</a>
                                                        </p>
                                                        @if($chunk->isNotEmpty())
                                                        <ul>
                                                            @foreach($chunk as $grandchild)
                                                                <li><a href="{{ route('frontend.products.index', ['category' => $grandchild->slug]) }}">{{ $grandchild->name }} <span class="dd-arrow"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span></a></li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="dd-footer">
                                        <a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}">
                                            View All {{ $category->name }}
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                        </a>
                                        <span class="dd-footer-count">{{ $category->products_count ?? 0 }} products</span>
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach





                    <!-- About (no dropdown) -->
                    <li class="nav-item"><a href="#">{{ __('file.about') }}</a></li>

                    <!-- Contact (no dropdown) -->
                    <li class="nav-item"><a href="#">{{ __('file.contact') }}</a></li>

                </ul>
            </nav>

            <!-- Right icons -->
            <div class="nav-right">
                <!-- Language Switcher -->
                <div class="nav-item has-drop lang-switcher">
                    <a href="#" class="nav-icon" aria-label="{{ __('file.switch_language') }}">
                        <span class="lang-text">{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="nav-chevron" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 1 5 5 9 1"/></svg>
                    </a>
                    <div class="nav-dropdown simple" role="menu" style="min-width: 120px;">
                        <ul class="dd-simple-list">
                            <li>
                                <form action="{{ route('language.switch') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="locale" value="en">
                                    <button type="submit" class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">English</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('language.switch') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="locale" value="es">
                                    <button type="submit" class="lang-btn {{ app()->getLocale() == 'es' ? 'active' : '' }}">Español</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                @auth('web')
                    <a href="{{ route('account.dashboard') }}" class="nav-icon" aria-label="Account">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                @else
                    <div class="nav-auth">
                        <a href="#" onclick="openAuthModal('login'); return false;" class="auth-link">{{ __('file.sign_in') }}</a>
                        <a href="#" onclick="openAuthModal('register'); return false;" class="auth-link primary">{{ __('file.register') }}</a>
                    </div>
                @endauth
                <a href="#" class="nav-icon" aria-label="{{ __('file.search') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </a>
                <a href="{{ route('cart.index') }}" class="nav-icon" aria-label="Shopping cart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span class="cart-dot" id="navCartCount">{{ \Darryldecode\Cart\Facades\CartFacade::getTotalQuantity() }}</span>
                </a>
                <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════
         MOBILE NAV (accordion)
    ═══════════════════════════════════════════ -->
    <nav class="mobile-nav" id="mobileNav" aria-label="Mobile navigation">

        <!-- Plain link: Home -->
        <div class="mob-item mob-plain"><a href="{{ route('home') }}">{{ __('file.home') }}</a></div>

        <!-- New Arrivals accordion -->
        <div class="mob-item" id="mob-new">
            <div class="mob-item-head" onclick="toggleMob('mob-new')">
                <a href="#" onclick="return false;">{{ __('file.new_arrivals') }}</a>
                <svg class="mob-chevron" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 1 5 5 9 1"/></svg>
            </div>
            <div class="mob-sub">
                <div class="mob-sub-section">
                    <a href="#">{{ __('file.view_all_new_arrivals') }}</a>
                    <a href="#">This Week's Drop</a>
                    <a href="#">New Men's Styles</a>
                    <a href="#">New Kids' Styles</a>
                    <a href="#">New Activewear</a>
                </div>
            </div>
        </div>

        <!-- Dynamic Categories accordion -->
        @foreach($globalCategories as $category)
            @if($category->children->count() > 0)
                <div class="mob-item" id="mob-cat-{{ $category->id }}">
                    <div class="mob-item-head" onclick="toggleMob('mob-cat-{{ $category->id }}')">
                        <a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}" onclick="return false;">{{ $category->name }}</a>
                        <svg class="mob-chevron" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 1 5 5 9 1"/></svg>
                    </div>
                    <div class="mob-sub">
                        <div class="mob-sub-section">
                            @foreach($category->children as $child)
                                <p class="mob-sub-head" style="margin-top: {{ $loop->first ? '0' : '0.5rem' }};"><a href="{{ route('frontend.products.index', ['category' => $child->slug]) }}" style="text-decoration:none; color:inherit;">{{ $child->name }}</a></p>
                                @foreach($child->children as $grandchild)
                                    <a href="{{ route('frontend.products.index', ['category' => $grandchild->slug]) }}">{{ $grandchild->name }}</a>
                                @endforeach
                                @if(!$loop->last)<div class="mob-sub-divider"></div>@endif
                            @endforeach
                            <div class="mob-sub-divider"></div>
                            <a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}" style="color:var(--gold);font-weight:600;">View All {{ $category->name }} →</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="mob-item mob-plain"><a href="{{ route('frontend.products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></div>
            @endif
        @endforeach

        <!-- Plain links -->
        <div class="mob-item mob-plain"><a href="#">{{ __('file.about') }}</a></div>
        <div class="mob-item mob-plain"><a href="#">{{ __('file.contact') }}</a></div>
        <div class="mob-item mob-plain"><a href="#">{{ __('file.careers') }}</a></div>
        <div class="mob-item mob-plain"><a href="{{ route('cart.index') }}">{{ __('file.my_bag') }} ({{ \Darryldecode\Cart\Facades\CartFacade::getTotalQuantity() }})</a></div>

        <!-- Language row -->
        <div class="mob-lang-row">
            <form action="{{ route('language.switch') }}" method="POST" style="flex: 1;">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" class="mob-lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</button>
            </form>
            <form action="{{ route('language.switch') }}" method="POST" style="flex: 1;">
                @csrf
                <input type="hidden" name="locale" value="es">
                <button type="submit" class="mob-lang-btn {{ app()->getLocale() == 'es' ? 'active' : '' }}">ES</button>
            </form>
        </div>

        <!-- Auth buttons -->
        @auth('web')
            <div class="mob-item mob-plain"><a href="{{ route('account.dashboard') }}">{{ __('file.my_account') }}</a></div>
        @else
            <div class="mob-auth">
                <a href="#" onclick="openAuthModal('login'); return false;" class="mob-signin">{{ __('file.sign_in') }}</a>
                <a href="#" onclick="openAuthModal('register'); return false;" class="mob-register">{{ __('file.register') }}</a>
            </div>
        @endauth
    </nav>

    <!-- ═══════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════ -->
    <main>@yield('content')</main>

    <!-- ═══════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════ -->
    @php $storefront = \App\Models\Setting::getAll(); @endphp
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-brand g-up">
                    <div class="ft-logo">KARBN<em>ZOL</em></div>
                    <div class="ft-logo-tag">T-Shirts · Jeans · Chinos</div>
                    <p class="ft-desc">{{ $storefront->storefront_about_us ?? "Sri Lanka's premier destination for men's and kids' fashion. Quality craftsmanship, contemporary style, unbeatable value." }}</p>
                    <div class="ft-socials">
                        <a class="ft-soc" href="#" aria-label="Instagram">IG</a>
                        <a class="ft-soc" href="#" aria-label="Facebook">FB</a>
                        <a class="ft-soc" href="#" aria-label="TikTok">TK</a>
                        <a class="ft-soc" href="#" aria-label="YouTube">YT</a>
                    </div>
                </div>
                <div class="g-up">
                    <p class="ft-col-h">{{ __('file.shop') }}</p>
                    <ul class="ft-col-links">
                        <li><a href="{{ route('frontend.products.index') }}">{{ __('file.all_products') }}</a></li>
                        <li><a href="#">{{ __('file.new_arrivals') }}</a></li>
                        <li><a href="{{ route('frontend.products.index') }}">{{ __('file.mens_clothing') }}</a></li>
                        <li><a href="#">{{ __('file.kids_clothing') }}</a></li>
                        <li><a href="#">{{ __('file.activewear') }}</a></li>
                        <li><a href="#">{{ __('file.sale') }}</a></li>
                    </ul>
                </div>
                <div class="g-up">
                    <p class="ft-col-h">{{ __('file.help') }}</p>
                    <ul class="ft-col-links">
                        <li><a href="#">{{ __('file.shipping_info') }}</a></li>
                        <li><a href="#">{{ __('file.returns_policy') }}</a></li>
                        <li><a href="#">{{ __('file.size_guide') }}</a></li>
                        <li><a href="#">{{ __('file.track_order') }}</a></li>
                        <li><a href="#">{{ __('file.faq') }}</a></li>
                        <li><a href="#">{{ __('file.contact_us') }}</a></li>
                    </ul>
                </div>
                <div class="g-up">
                    <p class="ft-col-h">{{ __('file.contact') }}</p>
                    <div class="ft-contact">
                        <a href="tel:{{ $storefront->phone ?? '+94112345678' }}">{{ $storefront->phone ?? '+94 11 234 5678' }}</a><br>
                        <a href="mailto:{{ $storefront->email ?? 'hello@karbnzol.com' }}">{{ $storefront->email ?? 'hello@karbnzol.com' }}</a><br><br>
                        Mon – Sat &nbsp; 9am – 6pm
                    </div>
                </div>
                <div class="g-up">
                    <p class="ft-col-h">{{ __('file.newsletter') }}</p>
                    <p class="ft-nl-note">{{ __('file.newsletter_note') }}</p>
                    <form class="ft-nl-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="{{ __('file.your_email') }}" required>
                        <button type="submit">{{ __('file.subscribe') }}</button>
                    </form>
                    <p class="ft-pay-label">{{ __('file.we_accept') }}</p>
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

    <!-- ═══════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════ -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            const wipe = document.getElementById('page-wipe');
            if (wipe) {
                wipe.style.display = 'none';
            }
        }
    });

    window.addEventListener('load', () => {

        /* ── Sticky header ────────────────────────── */
        const header = document.getElementById('siteHeader');
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 40);
        }, { passive: true });

        /* ── Mobile hamburger ─────────────────────── */
        const burger = document.getElementById('hamburger');
        const mNav   = document.getElementById('mobileNav');
        function toggleNav(open) {
            burger.classList.toggle('open', open);
            mNav.classList.toggle('open', open);
            burger.setAttribute('aria-expanded', open);
            document.body.style.overflow = open ? 'hidden' : '';
        }
        burger.addEventListener('click', () => toggleNav(!mNav.classList.contains('open')));

        /* ── Desktop dropdown: close on outside click ─ */
        document.addEventListener('click', e => {
            if (!e.target.closest('.nav-item.has-drop')) {
                /* CSS handles visibility — no JS needed for hover;
                   but if we ever add click-to-open we'd close here */
            }
        });

        /* ── Set mega dropdown top = header bottom ──── */
        function setMegaTop() {
            const hdr = document.getElementById('siteHeader');
            if (!hdr) return;
            const bottom = hdr.getBoundingClientRect().bottom;
            document.querySelectorAll('.nav-dropdown.mega').forEach(d => {
                d.style.top = bottom + 'px';
            });
        }
        setMegaTop();
        window.addEventListener('scroll', setMegaTop, { passive: true });
        window.addEventListener('resize', setMegaTop, { passive: true });

        /* ── Delayed hover open/close for dropdowns ──
           300ms close delay so the cursor can travel
           from the nav link into the dropdown panel
           without it snapping shut in between.        */
        const CLOSE_DELAY = 280; /* ms before closing */
        let closeTimer = null;

        function openDropdown(item) {
            /* Cancel any pending close */
            if (closeTimer) { clearTimeout(closeTimer); closeTimer = null; }
            /* Close any other open dropdown first */
            document.querySelectorAll('.nav-item.has-drop.dd-open').forEach(el => {
                if (el !== item) el.classList.remove('dd-open');
            });
            item.classList.add('dd-open');
        }

        function closeDropdown(item) {
            /* Delay closing so cursor can travel to panel */
            closeTimer = setTimeout(() => {
                item.classList.remove('dd-open');
                closeTimer = null;
            }, CLOSE_DELAY);
        }

        function cancelClose() {
            if (closeTimer) { clearTimeout(closeTimer); closeTimer = null; }
        }

        document.querySelectorAll('.nav-item.has-drop').forEach(item => {
            const dropdown = item.querySelector('.nav-dropdown');

            /* Open on mouseenter of the nav item (trigger + dropdown area) */
            item.addEventListener('mouseenter', () => openDropdown(item));

            /* Start close timer when cursor leaves the whole nav item */
            item.addEventListener('mouseleave', () => closeDropdown(item));

            /* If cursor enters the dropdown panel, cancel any close timer */
            if (dropdown) {
                dropdown.addEventListener('mouseenter', cancelClose);
                dropdown.addEventListener('mouseleave', () => closeDropdown(item));
            }
        });

        /* Close all dropdowns when clicking outside */
        document.addEventListener('click', e => {
            if (!e.target.closest('.nav-item.has-drop')) {
                document.querySelectorAll('.nav-item.has-drop.dd-open')
                    .forEach(el => el.classList.remove('dd-open'));
            }
        });

        /* Close all dropdowns on Escape */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.nav-item.has-drop.dd-open')
                    .forEach(el => el.classList.remove('dd-open'));
            }
        });

        /* ── Keyboard accessibility for dropdowns ──── */
        document.querySelectorAll('.nav-item.has-drop').forEach(item => {
            const trigger = item.querySelector(':scope > a');
            const dropdown = item.querySelector('.nav-dropdown');
            if (!trigger || !dropdown) return;

            trigger.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const isOpen = item.classList.contains('dd-open');
                    document.querySelectorAll('.nav-item.has-drop.dd-open')
                        .forEach(el => el.classList.remove('dd-open'));
                    if (!isOpen) {
                        item.classList.add('dd-open');
                        const firstLink = dropdown.querySelector('a');
                        if (firstLink) firstLink.focus();
                    }
                }
                if (e.key === 'Escape') {
                    item.classList.remove('dd-open');
                    trigger.focus();
                }
            });

            dropdown.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    item.classList.remove('dd-open');
                    trigger.focus();
                }
            });
        });

        /* ── GSAP ─────────────────────────────────── */
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

        /* Page leave wipe */
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

    /* ── Mobile accordion toggle (global so onclick= works) ── */
    function toggleMob(id) {
        const item = document.getElementById(id);
        if (!item) return;
        const isOpen = item.classList.contains('open');
        /* Close all open items first */
        document.querySelectorAll('.mob-item.open').forEach(el => {
            el.classList.remove('open');
        });
        /* If it wasn't open, open it now */
        if (!isOpen) {
            item.classList.add('open');
        }
    }
    </script>
    
    @include('frontend.layouts.auth-modals')
</body>
</html>