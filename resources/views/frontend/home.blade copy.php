@extends('frontend.layouts.app')@section('title', $store_name ?? 'Karbnzol — Premium Menswear')@section('content')

    <style>
        :root {
            --bg:        #1a1a1a;
            --bg-2:      #222222;
            --bg-3:      #2a2a2a;
            --bg-4:      #333333;
            --white:     #ffffff;
            --off-white: #f0f0f0;
            --silver:    #aaaaaa;
            --dim:       #666666;
            --gold:      #c8a96e;
            --gold-bg:   rgba(200,169,110,0.1);
            --red:       #cc3333;
            --green:     #2d7a4f;
            --font-display: 'Oswald', 'Arial Narrow', sans-serif;
            --font-body:    'Barlow', sans-serif;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* ════════════════════════════════════════════════
           HERO SLIDER
        ════════════════════════════════════════════════ */
        .hero {
            position: relative;
            height: calc(100vh - 96px);
            min-height: 580px;
            overflow: hidden;
            background: var(--bg-2);
        }
        .hero-track { display: flex; height: 100%; }
        .hero-slide {
            min-width: 100%; height: 100%;
            position: relative; overflow: hidden;
        }
        .slide-bg {
            position: absolute; inset: 0;
            background-size: cover; background-position: center center;
            transform: scale(1.06);
            transition: transform 8s ease-out;
            filter: brightness(0.5);
        }
        .hero-slide.active .slide-bg { transform: scale(1); }
        .hero-slide::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(100deg, rgba(26,26,26,0.8) 0%, rgba(26,26,26,0.3) 55%, transparent 100%);
        }
        .hero-content {
            position: absolute; bottom: 0; left: 0;
            padding: 4.5rem 5vw;
            z-index: 2; max-width: 700px;
        }
        .slide-eyebrow {
            display: inline-flex; align-items: center; gap: 0.6rem;
            font-family: var(--font-display);
            font-size: 0.68rem; font-weight: 500;
            letter-spacing: 0.28em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 1.25rem;
        }
        .slide-eyebrow::before { content: ''; width: 24px; height: 1.5px; background: var(--gold); }
        .slide-title {
            font-family: var(--font-display);
            font-size: clamp(3.5rem, 9vw, 7.5rem);
            font-weight: 700; letter-spacing: 0.04em;
            text-transform: uppercase; color: var(--white);
            line-height: 0.88; margin-bottom: 1.5rem;
        }
        .slide-title .stroke { -webkit-text-stroke: 1.5px var(--off-white); color: transparent; display: block; }
        .slide-title .gold  { color: var(--gold); display: block; }
        .slide-sub {
            font-size: 0.9375rem; color: rgba(240,240,240,0.62);
            line-height: 1.65; margin-bottom: 2rem;
            font-weight: 300; max-width: 400px;
        }
        .slide-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

        /* Slide progress bar */
        .slide-progress {
            position: absolute; bottom: 0; left: 0;
            height: 2px; background: var(--gold);
            z-index: 10; width: 0;
            transition: width 6s linear;
        }
        .hero-slide.active .slide-progress { width: 100%; }

        /* Nav arrows */
        .slide-arrows {
            position: absolute; bottom: 2.25rem; right: 4vw;
            display: flex; gap: 0.5rem; z-index: 10;
        }
        .slide-arrow {
            width: 40px; height: 40px;
            border: 1px solid rgba(240,240,240,0.25);
            background: transparent; color: var(--silver);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
        }
        .slide-arrow:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-bg); }

        /* Counter */
        .slide-counter {
            position: absolute; bottom: 2.5rem; left: 5vw;
            font-family: var(--font-display);
            font-size: 0.65rem; letter-spacing: 0.14em;
            color: var(--silver); z-index: 10;
        }
        .slide-counter .curr { color: var(--gold); font-size: 1.3rem; font-weight: 700; }

        /* Scroll indicator */
        .scroll-hint {
            position: absolute; right: 4vw; top: 50%;
            transform: translateY(-50%) rotate(90deg);
            font-family: var(--font-display);
            font-size: 0.55rem; letter-spacing: 0.22em;
            text-transform: uppercase; color: rgba(240,240,240,0.3);
            z-index: 10; display: flex; align-items: center; gap: 0.5rem;
        }
        .scroll-hint::after { content:''; width: 30px; height: 1px; background: rgba(240,240,240,0.2); }

        /* ════════════════════════════════════════════════
           BUTTONS (shared)
        ════════════════════════════════════════════════ */
        .btn-gold {
            display: inline-flex; align-items: center; gap: 0.55rem;
            background: var(--gold); color: var(--bg);
            padding: 0.85rem 2.25rem;
            font-family: var(--font-display); font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.18em; text-transform: uppercase;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-gold:hover { background: var(--off-white); transform: translateY(-2px); }
        .btn-outline-light {
            display: inline-flex; align-items: center; gap: 0.55rem;
            background: transparent; color: var(--off-white);
            padding: 0.85rem 2.25rem;
            border: 1px solid rgba(240,240,240,0.35);
            font-family: var(--font-display); font-size: 0.72rem; font-weight: 500;
            letter-spacing: 0.18em; text-transform: uppercase;
            transition: border-color 0.2s, color 0.2s, background 0.2s, transform 0.15s;
        }
        .btn-outline-light:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-bg); transform: translateY(-2px); }
        .btn-outline-dark {
            display: inline-flex; align-items: center; gap: 0.55rem;
            background: transparent; color: var(--silver);
            padding: 0.75rem 1.75rem;
            border: 1px solid var(--bg-4);
            font-family: var(--font-display); font-size: 0.68rem; font-weight: 500;
            letter-spacing: 0.16em; text-transform: uppercase;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }
        .btn-outline-dark:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-bg); }

        /* ════════════════════════════════════════════════
           SECTION SHARED
        ════════════════════════════════════════════════ */
        .pg-sec { padding: 5rem 0; }
        .pg-sec-alt { padding: 5rem 0; background: var(--bg-2); }
        .sec-wrap { max-width: 1600px; margin: 0 auto; padding: 0 2rem; }
        .sec-head {
            display: flex; align-items: flex-end;
            justify-content: space-between;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--bg-4);
            margin-bottom: 2.5rem; gap: 1rem;
        }
        .sec-eyebrow {
            font-family: var(--font-display);
            font-size: 0.62rem; font-weight: 500;
            letter-spacing: 0.26em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 0.35rem;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .sec-eyebrow::before { content: ''; width: 16px; height: 1.5px; background: var(--gold); }
        .sec-title {
            font-family: var(--font-display);
            font-size: clamp(1.75rem, 3.5vw, 2.75rem);
            font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--off-white);
            line-height: 1;
        }
        .sec-link {
            display: flex; align-items: center; gap: 0.4rem;
            font-family: var(--font-display); font-size: 0.65rem; font-weight: 600;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--gold); white-space: nowrap;
            transition: gap 0.2s, opacity 0.2s;
        }
        .sec-link:hover { gap: 0.65rem; opacity: 0.8; }

        /* ════════════════════════════════════════════════
           CATEGORY TABS
        ════════════════════════════════════════════════ */
        .cat-bar {
            background: var(--bg-2);
            border-bottom: 1px solid var(--bg-4);
            overflow-x: auto; scrollbar-width: none;
        }
        .cat-bar::-webkit-scrollbar { display: none; }
        .cat-bar-inner {
            max-width: 1600px; margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: stretch; min-width: max-content;
        }
        .cat-tab {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.9rem 1.4rem;
            font-family: var(--font-display); font-size: 0.68rem; font-weight: 500;
            letter-spacing: 0.15em; text-transform: uppercase; color: var(--dim);
            border-right: 1px solid var(--bg-4); white-space: nowrap;
            transition: color 0.2s, background 0.2s; cursor: pointer;
            position: relative;
        }
        .cat-tab::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 2px; background: var(--gold);
            transform: scaleX(0); transform-origin: left;
            transition: transform 0.3s var(--ease-out);
        }
        .cat-tab:first-child { border-left: 1px solid var(--bg-4); }
        .cat-tab:hover { color: var(--off-white); background: var(--bg-3); }
        .cat-tab.active { color: var(--gold); }
        .cat-tab.active::after { transform: scaleX(1); }
        .cat-count-chip {
            background: var(--bg-4); color: var(--silver);
            font-size: 0.5rem; font-weight: 600;
            padding: 0.1rem 0.35rem; border-radius: 2px;
        }
        .cat-tab.active .cat-count-chip { background: var(--gold-bg); color: var(--gold); }

        /* ════════════════════════════════════════════════
           PRODUCT CARDS (shared)
        ════════════════════════════════════════════════ */
        .product-grid-4 {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 0; border: 1px solid var(--bg-4);
        }
        .product-grid-3 {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 0; border: 1px solid var(--bg-4);
        }
        .p-card {
            border-right: 1px solid var(--bg-4);
            border-bottom: 1px solid var(--bg-4);
            background: var(--bg-2); position: relative;
            transition: background 0.25s;
        }
        .product-grid-4 .p-card:nth-child(4n),
        .product-grid-3 .p-card:nth-child(3n) { border-right: none; }
        .product-grid-4 .p-card:nth-last-child(-n+4):nth-child(4n+1),
        .product-grid-4 .p-card:nth-last-child(-n+4) ~ .p-card { border-bottom: none; }
        .p-card:hover { background: var(--bg-3); }

        .p-img-wrap {
            position: relative; aspect-ratio: 3/4; overflow: hidden;
            background: #1c1c1c;
        }
        .p-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center top;
            transition: transform 0.55s var(--ease-out), filter 0.4s;
            filter: brightness(0.9);
        }
        .p-card:hover .p-img-wrap img { transform: scale(1.05); filter: brightness(1); }

        /* Badge ribbon */
        .p-ribbon {
            position: absolute; top: 0; left: 0;
            background: rgba(26,26,26,0.85); backdrop-filter: blur(4px);
            color: var(--off-white);
            font-family: var(--font-display); font-size: 0.52rem; font-weight: 600;
            letter-spacing: 0.22em; text-transform: uppercase;
            padding: 0.35rem 0.75rem; z-index: 2;
        }
        .p-ribbon.sale   { background: var(--red); }
        .p-ribbon.top    { background: rgba(200,169,110,0.92); color: var(--bg); }

        /* Wishlist / quick-view buttons */
        .p-side-actions {
            position: absolute; top: 0; right: 0;
            display: flex; flex-direction: column;
            transform: translateX(110%);
            transition: transform 0.3s var(--ease-out); z-index: 3;
        }
        .p-card:hover .p-side-actions { transform: translateX(0); }
        .p-side-btn {
            width: 38px; height: 38px;
            background: rgba(26,26,26,0.88); border: none;
            border-bottom: 1px solid var(--bg-4);
            color: var(--silver); display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background 0.2s, color 0.2s;
        }
        .p-side-btn:hover { background: var(--gold); color: var(--bg); }

        /* Quick add */
        .p-quick-add {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: var(--gold); color: var(--bg); border: none; cursor: pointer;
            font-family: var(--font-display); font-size: 0.62rem; font-weight: 700;
            letter-spacing: 0.2em; text-transform: uppercase;
            padding: 0.7rem; text-align: center;
            transform: translateY(100%); transition: transform 0.3s var(--ease-out);
        }
        .p-quick-add:hover { background: var(--white); }
        .p-card:hover .p-quick-add { transform: translateY(0); }

        /* Info */
        .p-info {
            padding: 0.875rem 1rem 1rem;
            border-top: 1px solid var(--bg-4);
        }
        .p-brand {
            font-family: var(--font-display); font-size: 0.54rem; font-weight: 600;
            letter-spacing: 0.2em; text-transform: uppercase; color: var(--dim);
            margin-bottom: 0.25rem;
        }
        .p-name {
            font-family: var(--font-display); font-size: 0.78rem; font-weight: 500;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--off-white); line-height: 1.35; margin-bottom: 0.45rem;
        }
        .p-price-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem; }
        .p-price { font-family: var(--font-display); font-size: 0.875rem; font-weight: 600; letter-spacing: 0.06em; color: var(--off-white); }
        .p-price-was { font-family: var(--font-display); font-size: 0.72rem; color: var(--dim); text-decoration: line-through; }
        .p-install {
            font-size: 0.68rem; color: var(--dim); margin-bottom: 0.5rem; line-height: 1.4;
        }
        .p-install strong { color: var(--silver); }
        .p-install .cb { color: var(--gold); font-weight: 600; }
        .mintpay {
            display: inline-flex; align-items: center;
            background: #1a2e45; color: #5aadff;
            font-size: 0.48rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            padding: 0.12rem 0.35rem; border-radius: 2px;
            vertical-align: middle; margin-left: 0.2rem;
        }
        .p-swatches { display: flex; gap: 4px; flex-wrap: wrap; }
        .p-dot {
            width: 16px; height: 16px;
            border: 1.5px solid var(--bg-4); cursor: pointer;
            transition: border-color 0.2s, transform 0.2s;
        }
        .p-dot:hover { border-color: var(--silver); transform: scale(1.15); }

        /* ════════════════════════════════════════════════
           STATS BAR
        ════════════════════════════════════════════════ */
        .stats-bar {
            background: var(--bg-2); border-bottom: 1px solid var(--bg-4);
            display: grid; grid-template-columns: repeat(4, 1fr);
        }
        .stat-item {
            padding: 2rem;
            border-right: 1px solid var(--bg-4);
            transition: background 0.2s;
        }
        .stat-item:last-child { border-right: none; }
        .stat-item:hover { background: var(--bg-3); }
        .stat-num {
            font-family: var(--font-display); font-size: 2.5rem; font-weight: 700;
            letter-spacing: 0.06em; color: var(--gold); line-height: 1; margin-bottom: 0.2rem;
        }
        .stat-lbl {
            font-family: var(--font-display); font-size: 0.6rem; font-weight: 500;
            letter-spacing: 0.2em; text-transform: uppercase; color: var(--dim);
        }

        /* ════════════════════════════════════════════════
           MARQUEE
        ════════════════════════════════════════════════ */
        .marquee-bar {
            background: var(--gold); overflow: hidden;
            border-top: 1px solid rgba(200,169,110,0.3);
            border-bottom: 1px solid rgba(200,169,110,0.3);
            padding: 0.9rem 0;
        }
        .marquee-track {
            display: flex; white-space: nowrap;
            animation: mscroll 28s linear infinite;
        }
        .marquee-track:hover { animation-play-state: paused; }
        .marquee-item {
            display: inline-flex; align-items: center; gap: 1.5rem;
            padding: 0 2.5rem;
            font-family: var(--font-display); font-size: 0.72rem; font-weight: 600;
            letter-spacing: 0.2em; text-transform: uppercase; color: var(--bg);
        }
        .marquee-sep { width: 5px; height: 5px; background: rgba(26,26,26,0.3); transform: rotate(45deg); flex-shrink: 0; }
        @keyframes mscroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        /* ════════════════════════════════════════════════
           COLLECTIONS GRID
        ════════════════════════════════════════════════ */
        .collections-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            grid-template-rows: 280px 220px;
            gap: 1px;
            background: var(--bg-4);
        }
        .col-card {
            position: relative; overflow: hidden;
            background: var(--bg-3); cursor: pointer;
        }
        .col-card:first-child { grid-row: 1 / 3; }
        .col-bg {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
            filter: brightness(0.45);
            transition: filter 0.5s, transform 0.65s var(--ease-out);
        }
        .col-card:hover .col-bg { filter: brightness(0.6); transform: scale(1.04); }
        .col-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(26,26,26,0.85) 0%, rgba(26,26,26,0.1) 50%, transparent 100%);
        }
        .col-content {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 1.5rem; z-index: 2;
            display: flex; align-items: flex-end; justify-content: space-between;
        }
        .col-num {
            font-family: var(--font-display); font-size: 0.56rem; font-weight: 500;
            letter-spacing: 0.22em; text-transform: uppercase; color: var(--gold);
            margin-bottom: 0.35rem;
        }
        .col-name {
            font-family: var(--font-display);
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--white); line-height: 0.95;
        }
        .col-arrow {
            width: 38px; height: 38px;
            border: 1px solid rgba(240,240,240,0.3);
            display: flex; align-items: center; justify-content: center;
            color: var(--off-white); flex-shrink: 0;
            opacity: 0; transform: translateX(-8px);
            transition: opacity 0.3s, transform 0.3s, border-color 0.2s;
        }
        .col-card:hover .col-arrow { opacity: 1; transform: translateX(0); border-color: var(--gold); color: var(--gold); }

        /* ════════════════════════════════════════════════
           EDITORIAL FEATURE STRIP
        ════════════════════════════════════════════════ */
        .editorial-strip {
            background: var(--bg-2);
            display: grid; grid-template-columns: 1fr 1fr;
            border-top: 1px solid var(--bg-4);
            border-bottom: 1px solid var(--bg-4);
        }
        .editorial-img { position: relative; overflow: hidden; min-height: 480px; }
        .editorial-img img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.65); }
        .editorial-text {
            padding: 5rem 4rem;
            display: flex; flex-direction: column; justify-content: center;
            border-left: 1px solid var(--bg-4);
        }
        .editorial-tag {
            font-family: var(--font-display); font-size: 0.62rem; font-weight: 500;
            letter-spacing: 0.28em; text-transform: uppercase; color: var(--gold);
            margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
        }
        .editorial-tag::before { content: ''; width: 20px; height: 1.5px; background: var(--gold); }
        .editorial-headline {
            font-family: var(--font-display); font-size: clamp(2rem, 3.5vw, 3.25rem);
            font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--off-white); line-height: 0.95; margin-bottom: 1.25rem;
        }
        .editorial-body {
            font-size: 0.9375rem; color: var(--dim); line-height: 1.75;
            font-weight: 300; margin-bottom: 2rem; max-width: 400px;
        }

        /* ════════════════════════════════════════════════
           TRUST BAR
        ════════════════════════════════════════════════ */
        .trust-bar {
            background: var(--bg-2); border-top: 1px solid var(--bg-4);
            display: grid; grid-template-columns: repeat(4, 1fr);
        }
        .trust-cell {
            display: flex; align-items: center; gap: 1rem;
            padding: 1.75rem 2rem;
            border-right: 1px solid var(--bg-4);
            transition: background 0.2s;
        }
        .trust-cell:last-child { border-right: none; }
        .trust-cell:hover { background: var(--bg-3); }
        .trust-icon { width: 42px; height: 42px; flex-shrink: 0; color: var(--gold); }
        .trust-h { font-family: var(--font-display); font-size: 0.7rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--off-white); margin-bottom: 0.15rem; }
        .trust-p { font-size: 0.75rem; color: var(--dim); font-weight: 300; }

        /* ════════════════════════════════════════════════
           NEWSLETTER
        ════════════════════════════════════════════════ */
        .nl-section {
            background: var(--gold);
            padding: 3.5rem 0;
        }
        .nl-inner {
            max-width: 1600px; margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 2.5rem; flex-wrap: wrap;
        }
        .nl-text h3 {
            font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2.25rem);
            font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--bg); line-height: 1;
        }
        .nl-text p { font-size: 0.825rem; color: rgba(26,26,26,0.6); margin-top: 0.3rem; font-weight: 300; }
        .nl-form { display: flex; flex: 1; max-width: 480px; }
        .nl-form input {
            flex: 1; background: rgba(26,26,26,0.14); border: none;
            border-bottom: 2px solid rgba(26,26,26,0.35); outline: none;
            padding: 0.8rem 1rem;
            font-family: var(--font-body); font-size: 0.875rem; color: var(--bg);
            transition: border-color 0.2s;
        }
        .nl-form input::placeholder { color: rgba(26,26,26,0.45); }
        .nl-form input:focus { border-bottom-color: var(--bg); }
        .nl-form button {
            background: var(--bg); color: var(--gold); border: none;
            padding: 0.8rem 1.75rem;
            font-family: var(--font-display); font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.18em; text-transform: uppercase;
            cursor: pointer; transition: background 0.2s;
        }
        .nl-form button:hover { background: var(--bg-3); }

        /* ════════════════════════════════════════════════
           REVEAL INIT
        ════════════════════════════════════════════════ */
        .reveal { opacity: 0; transform: translateY(22px); }
        .reveal-x { opacity: 0; transform: translateX(-22px); }

        /* ════════════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════════════ */
        @media (max-width: 1200px) {
            .product-grid-4 { grid-template-columns: repeat(3, 1fr); }
            .product-grid-4 .p-card:nth-child(4n) { border-right: 1px solid var(--bg-4); }
            .product-grid-4 .p-card:nth-child(3n) { border-right: none; }
            .collections-grid { grid-template-columns: 1fr 1fr; grid-template-rows: 300px 220px 220px; }
            .col-card:first-child { grid-column: 1 / 3; grid-row: 1; }
            .editorial-text { padding: 3.5rem 2.5rem; }
        }
        @media (max-width: 900px) {
            .stats-bar { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(2) { border-right: none; }
            .stat-item:nth-child(3), .stat-item:nth-child(4) { border-top: 1px solid var(--bg-4); }
            .editorial-strip { grid-template-columns: 1fr; }
            .editorial-img { min-height: 300px; }
            .editorial-text { border-left: none; border-top: 1px solid var(--bg-4); }
            .trust-bar { grid-template-columns: 1fr 1fr; }
            .trust-cell:nth-child(2) { border-right: none; }
            .trust-cell:nth-child(3), .trust-cell:nth-child(4) { border-top: 1px solid var(--bg-4); }
        }
        @media (max-width: 768px) {
            .product-grid-4, .product-grid-3 { grid-template-columns: repeat(2, 1fr); }
            .product-grid-4 .p-card:nth-child(3n),
            .product-grid-4 .p-card:nth-child(4n) { border-right: 1px solid var(--bg-4); }
            .product-grid-4 .p-card:nth-child(2n) { border-right: none; }
            .pg-sec, .pg-sec-alt { padding: 3.5rem 0; }
            .sec-wrap { padding: 0 1rem; }
            .collections-grid { grid-template-columns: 1fr; grid-template-rows: 300px 220px 220px 220px; }
            .col-card:first-child { grid-column: 1; grid-row: 1; }
            .nl-inner { flex-direction: column; }
            .nl-form { max-width: 100%; width: 100%; }
        }
        @media (max-width: 480px) {
            .hero-title { font-size: 3rem; }
            .hero-content { padding: 2.5rem 1.5rem; }
            .trust-bar { grid-template-columns: 1fr; }
            .trust-cell { border-right: none; border-top: 1px solid var(--bg-4); }
            .trust-cell:first-child { border-top: none; }
            .stats-bar { grid-template-columns: 1fr 1fr; }
            .cat-bar-inner { padding: 0 1rem; }
        }
    </style>

    {{-- ═══════════════════════════════════════════════════
         HERO SLIDER
    ═══════════════════════════════════════════════════ --}}
    <section class="hero" id="heroSection">
        <div class="hero-track" id="heroTrack">

            @php 
                $banners = $storefront->storefront_banners ?? [];
                if (!is_array($banners)) $banners = [];
                // Fallback to default if no banners configured
                if (empty($banners)) {
                    $banners = [
                        ['tag' => 'SS 2025 — New Drop', 'title' => 'WEAR THE EDGE.', 'subtitle' => 'Precision-cut menswear engineered for the modern man. Sharp lines, premium fabrics, uncompromising quality.', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1600&q=80'],
                        ['tag' => 'New Arrivals', 'title' => 'DEFINE YOUR STYLE.', 'subtitle' => 'From boardroom to weekend — our latest drop covers every dimension of the contemporary wardrobe.', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1516826957135-700dedea698c?w=1600&q=80'],
                        ['tag' => 'Activewear Collection', 'title' => 'PUSH YOUR LIMITS.', 'subtitle' => 'High-performance activewear built for intensity. Style that doesn\'t stop when you do.', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1571945153237-4929e783af4a?w=1600&q=80'],
                    ];
                }
            @endphp

            @foreach($banners as $i => $sl)
                <div class="hero-slide {{ $i === 0 ? 'active' : '' }}">
                    @php 
                        $slImg = $sl['image'] ?? '';
                        $imgUrl = !empty($slImg) && str_starts_with($slImg, 'http') ? $slImg : (!empty($slImg) ? asset('storage/' . $slImg) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1600&q=80'); 
                    @endphp
                    <div class="slide-bg" style="background-image: url('{{ $imgUrl }}');"></div>
                    <div class="slide-progress"></div>
                    <div class="hero-content">
                        @if(!empty($sl['tag']))<p class="slide-eyebrow">{{ $sl['tag'] }}</p>@endif
                        <h1 class="slide-title">
                            {{ $sl['title'] ?? '' }}
                        </h1>
                        @if(!empty($sl['subtitle']))<p class="slide-sub">{{ $sl['subtitle'] }}</p>@endif
                        <div class="slide-actions">
                            @if(!empty($sl['link']))
                                <a href="{{ rtrim(url('/'), '/') . '/' . ltrim($sl['link'], '/') }}" class="btn-gold">
                                    {{ __('file.shop_now') }}
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                            <a href="#" class="btn-outline-light">{{ __('file.lookbook') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="slide-counter">
            <span class="curr" id="slideNum">01</span>
            <span style="color:var(--dim);margin:0 0.3rem;font-size:0.75rem;">/</span>
            <span>0{{ count($banners) }}</span>
        </div>

        <div class="slide-arrows">
            <button class="slide-arrow" id="prevSlide" aria-label="Previous">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </button>
            <button class="slide-arrow" id="nextSlide" aria-label="Next">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
        </div>

        <div class="scroll-hint" aria-hidden="true">{{ __('file.scroll_to_explore') }}</div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         STATS BAR
    ═══════════════════════════════════════════════════ --}}
    <div class="stats-bar">
        @php $stats = [['n' => '12K+', 'l' => __('file.orders_shipped')], ['n' => '500+', 'l' => __('file.styles_in_stock')], ['n' => '98%', 'l' => __('file.five_star_reviews')], ['n' => '48H', 'l' => __('file.island_delivery')]]; @endphp
        @foreach($stats as $s)
            <div class="stat-item reveal">
                <div class="stat-num">{{ $s['n'] }}</div>
                <div class="stat-lbl">{{ $s['l'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════
         CATEGORY TABS
    ═══════════════════════════════════════════════════ --}}
    <div class="cat-bar">
        <div class="cat-bar-inner">
            <a class="cat-tab active" href="{{ route('frontend.products.index') }}">{{ __('file.all') }}
                @php $totalProducts = $featuredCategories->sum('products_count'); @endphp
                <span class="cat-count-chip">{{ $totalProducts }}</span>
            </a>
            @foreach($featuredCategories as $cat)
                <a class="cat-tab" href="{{ route('frontend.products.index', ['category' => $cat->slug]) }}">
                    {{ $cat->name }}
                    <span class="cat-count-chip">{{ $cat->products_count }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         NEW ARRIVALS
    ═══════════════════════════════════════════════════ --}}
    <section class="pg-sec">
        <div class="sec-wrap">
            <div class="sec-head reveal">
                <div>
                    <p class="sec-eyebrow">{{ __('file.just_dropped') }}</p>
                    <h2 class="sec-title">{{ __('file.new_arrivals') }}</h2>
                </div>
                <a href="{{ route('products.index') }}" class="sec-link">
                    {{ __('file.view_all') }}
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="product-grid-4">
                @forelse($newArrivals as $product)
                    @php
                        $defaultVariant = $product->variants->where('is_default', true)->first()
                            ?? $product->variants->first();
                        $displayPrice   = $defaultVariant ? ($defaultVariant->sale_price ?? $defaultVariant->price) : $product->sale_price ?? $product->base_price;
                        $originalPrice  = ($defaultVariant && $defaultVariant->sale_price) ? $defaultVariant->price : ($product->sale_price ? $product->base_price : null);
                        $imgUrl         = $product->primaryImage
                            ? asset('storage/' . $product->primaryImage->file_path)
                            : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80';
                    @endphp
                    <div class="p-card reveal">
                        <a href="{{ route('frontend.products.show', $product->slug) }}" class="p-img-wrap" style="display:block;">
                            <span class="p-ribbon">{{ __('file.new_arrival') }}</span>
                            <div class="p-side-actions">
                                <button class="p-side-btn" aria-label="Wishlist" onclick="event.preventDefault()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                </button>
                                <button class="p-side-btn" aria-label="Quick view" onclick="event.preventDefault()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" loading="lazy">
                            @if($defaultVariant)
                                <button class="p-quick-add js-add-to-cart"
                                    data-variant-id="{{ $defaultVariant->id }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); addToCart(this)"
                                >{{ __('file.add_to_cart') }}</button>
                            @else
                                <span class="p-quick-add" style="cursor:default;background:var(--bg-4);color:var(--dim);">{{ __('file.out_of_stock') }}</span>
                            @endif
                        </a>
                        <div class="p-info">
                            <p class="p-brand">{{ $product->brand?->name ?? 'Karbnzol' }}</p>
                            <p class="p-name">{{ $product->name }}</p>
                            <div class="p-price-row">
                                <span class="p-price">@price($displayPrice)</span>
                                @if($originalPrice)
                                    <span class="p-price-was">@price($originalPrice)</span>
                                @endif
                            </div>
                            @if($displayPrice > 0)
                                <p class="p-install">3 × <strong>@price($displayPrice / 3)</strong> &amp; up to <span class="cb">4% Cashback</span> with<span class="mintpay">MintPay</span></p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="grid-column:1/-1;padding:3rem;text-align:center;color:var(--dim);font-family:var(--font-display);letter-spacing:0.14em;font-size:0.85rem;">
                        {{ __('file.coming_soon') }}
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         MARQUEE
    ═══════════════════════════════════════════════════ --}}
    <div class="marquee-bar" aria-hidden="true">
        <div class="marquee-track">
            @php 
                $mqRaw = $storefront_marquee_text ?? '';
                $mqLink = $storefront_marquee_link ?? null;
                
                if (!empty($mqRaw)) {
                    // Split by | and trim whitespace
                    $parts = array_map('trim', explode('|', $mqRaw));
                    // Repeat the parts to ensure we have enough items for the marquee loop
                    $mw = [];
                    while(count($mw) < 15) {
                        $mw = array_merge($mw, $parts);
                    }
                    $mw = array_slice($mw, 0, count($mw) > 15 ? count($mw) : 15);
                } else {
                    $mw = ['Free Island Delivery', 'New Arrivals Weekly', 'MintPay Available', 'Premium Quality', 'Sri Lanka\'s Best', 'Free Island Delivery', 'New Arrivals Weekly', 'MintPay Available', 'Premium Quality', 'Sri Lanka\'s Best', 'Free Island Delivery', 'New Arrivals Weekly', 'MintPay Available', 'Premium Quality', 'Sri Lanka\'s Best'];
                }
            @endphp
            @foreach($mw as $w)
                @if($mqLink)
                    <a href="{{ $mqLink }}" class="marquee-item hover:opacity-80 transition">{{ strtoupper($w) }} <span class="marquee-sep"></span></a>
                @else
                    <span class="marquee-item">{{ strtoupper($w) }} <span class="marquee-sep"></span></span>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         COLLECTIONS
    ═══════════════════════════════════════════════════ --}}
    <section class="pg-sec-alt">
        <div class="sec-wrap">
            <div class="sec-head reveal">
                <div>
                    <p class="sec-eyebrow">{{ __('file.shop_by_collection') }}</p>
                    <h2 class="sec-title">{{ __('file.featured_collections') }}</h2>
                </div>
                <a href="{{ route('products.index') }}" class="sec-link">{{ __('file.browse_all') }} <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
            </div>
        </div>
        <div style="max-width:1600px;margin:0 auto;padding:0 2rem;">
            <div class="collections-grid">
                @if($featuredCollections->isNotEmpty())
                    @foreach($featuredCollections as $i => $col)
                        @php
                            $colImg = $col->banner_url
                                ?? ($col->products->first()?->primaryImage
                                    ? $col->products->first()->primaryImage->url
                                    : 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=900&q=80');
                        @endphp
                        <div class="col-card reveal">
                            <div class="col-bg" style="background-image: url('{{ $colImg }}');"></div>
                            <div class="col-overlay"></div>
                            <div class="col-content">
                                <div>
                                    <p class="col-num">Col. {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</p>
                                    <h3 class="col-name">{{ strtoupper($col->name) }}</h3>
                                </div>
                                <div class="col-arrow">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback static collections when none configured in admin --}}
                    @php $fallbackCols = [
                        ['num' => 'Col. 01', 'name' => 'The Dark Edit',      'img' => 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=900&q=80'],
                        ['num' => 'Col. 02', 'name' => 'Street Ready',       'img' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80'],
                        ['num' => 'Col. 03', 'name' => 'Active Series',      'img' => 'https://images.unsplash.com/photo-1571945153237-4929e783af4a?w=600&q=80'],
                        ['num' => 'Col. 04', 'name' => 'Classic Essentials', 'img' => 'https://images.unsplash.com/photo-1516826957135-700dedea698c?w=600&q=80'],
                    ]; @endphp
                    @foreach($fallbackCols as $c)
                        <div class="col-card reveal">
                            <div class="col-bg" style="background-image: url('{{ $c['img'] }}');"></div>
                            <div class="col-overlay"></div>
                            <div class="col-content">
                                <div>
                                    <p class="col-num">{{ $c['num'] }}</p>
                                    <h3 class="col-name">{{ strtoupper($c['name']) }}</h3>
                                </div>
                                <div class="col-arrow">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         BEST SELLERS
    ═══════════════════════════════════════════════════ --}}
    <section class="pg-sec">
        <div class="sec-wrap">
            <div class="sec-head reveal">
                <div>
                    <p class="sec-eyebrow">{{ __('file.customer_favourites') }}</p>
                    <h2 class="sec-title">{{ __('file.best_sellers') }}</h2>
                </div>
                <a href="{{ route('products.index') }}" class="sec-link">{{ __('file.view_all') }} <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
            </div>

            <div class="product-grid-4">
                @forelse($bestSellers as $product)
                    @php
                        $defaultVariant = $product->variants->where('is_default', true)->first()
                            ?? $product->variants->first();
                        $displayPrice   = $defaultVariant ? ($defaultVariant->sale_price ?? $defaultVariant->price) : $product->sale_price ?? $product->base_price;
                        $originalPrice  = ($defaultVariant && $defaultVariant->sale_price) ? $defaultVariant->price : ($product->sale_price ? $product->base_price : null);
                        $imgUrl         = $product->primaryImage
                            ? asset('storage/' . $product->primaryImage->file_path)
                            : 'https://images.unsplash.com/photo-1594938298603-c8148c4b4e3d?w=600&q=80';
                        $ribbon         = $product->is_featured ? 'top' : '';
                        $ribbonLabel    = $product->is_featured ? __('file.best_seller') : __('file.popular');
                    @endphp
                    <div class="p-card reveal">
                        <a href="{{ route('frontend.products.show', $product->slug) }}" class="p-img-wrap" style="display:block;">
                            <span class="p-ribbon {{ $ribbon }}">{{ $ribbonLabel }}</span>
                            <div class="p-side-actions">
                                <button class="p-side-btn" aria-label="Wishlist" onclick="event.preventDefault()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                </button>
                                <button class="p-side-btn" aria-label="Quick view" onclick="event.preventDefault()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" loading="lazy">
                            @if($defaultVariant)
                                <button class="p-quick-add js-add-to-cart"
                                    data-variant-id="{{ $defaultVariant->id }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); addToCart(this)"
                                >{{ __('file.add_to_cart') }}</button>
                            @else
                                <span class="p-quick-add" style="cursor:default;background:var(--bg-4);color:var(--dim);">Out of Stock</span>
                            @endif
                        </a>
                        <div class="p-info">
                            <p class="p-brand">{{ $product->brand?->name ?? 'Karbnzol' }}</p>
                            <p class="p-name">{{ $product->name }}</p>
                            <div class="p-price-row">
                                <span class="p-price">@price($displayPrice)</span>
                                @if($originalPrice)
                                    <span class="p-price-was">@price($originalPrice)</span>
                                @endif
                            </div>
                            @if($displayPrice > 0)
                                <p class="p-install">3 × <strong>@price($displayPrice / 3)</strong> &amp; up to <span class="cb">4% Cashback</span> with<span class="mintpay">MintPay</span></p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="grid-column:1/-1;padding:3rem;text-align:center;color:var(--dim);font-family:var(--font-display);letter-spacing:0.14em;font-size:0.85rem;">
                        {{ __('file.best_sellers_coming_soon') }}
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         EDITORIAL STRIP
    ═══════════════════════════════════════════════════ --}}
    <section class="editorial-strip">
        <div class="editorial-img reveal-x">
            <img src="https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=900&q=80" alt="About Karbnzol" loading="lazy">
        </div>
        <div class="editorial-text reveal">
            <p class="editorial-tag">{{ __('file.our_story') }}</p>
            <h2 class="editorial-headline">
                {!! str_replace('.', '.<br>', __('file.built_for_modern_man')) !!}
            </h2>
            <p class="editorial-body">
                {{ $storefront->storefront_about_us ?? "From everyday essentials to statement pieces, every garment we make is precision-cut and quality-tested. Sri Lanka's most trusted menswear brand — worn by thousands, loved by all." }}
            </p>
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                <a href="#" class="btn-gold">{{ __('file.our_story') }} <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
                <a href="{{ route('products.index') }}" class="btn-outline-dark">{{ __('file.shop_now') }}</a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         TRUST BAR
    ═══════════════════════════════════════════════════ --}}
    <div class="trust-bar">
        @php $trusts = [
            ['svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>', 'h' => __('file.free_delivery_trust'), 'p' => __('file.free_delivery_trust_subtitle')],
            ['svg' => '<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>', 'h' => __('file.easy_returns'), 'p' => __('file.easy_returns_subtitle')],
            ['svg' => '<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>', 'h' => __('file.secure_payment'), 'p' => __('file.secure_payment_subtitle')],
            ['svg' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.41 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.52 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.16 6.16l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>', 'h' => __('file.customer_support'), 'p' => __('file.customer_support_subtitle')],
        ]; @endphp
        @foreach($trusts as $t)
            <div class="trust-cell reveal">
                <div class="trust-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $t['svg'] !!}</svg></div>
                <div><p class="trust-h">{{ $t['h'] }}</p><p class="trust-p">{{ $t['p'] }}</p></div>
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════
         NEWSLETTER
    ═══════════════════════════════════════════════════ --}}
    <section class="nl-section">
        <div class="nl-inner">
            <div class="nl-text reveal">
                <h3>{{ __('file.join_the_club') }}</h3>
                <p>{{ __('file.newsletter_subtitle') }}</p>
            </div>
            <form class="nl-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="{{ __('file.enter_email_placeholder') }}" required>
                <button type="submit">{{ __('file.subscribe') }}</button>
            </form>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         CART TOAST NOTIFICATION
    ═══════════════════════════════════════════════════ --}}
    <style>
        #cart-toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--bg-2);
            border: 1px solid var(--bg-4);
            border-left: 3px solid var(--gold);
            padding: 0.875rem 1.25rem;
            min-width: 260px;
            max-width: 340px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.55);
            transform: translateY(120%);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.16,1,0.3,1), opacity 0.35s;
            pointer-events: none;
        }
        #cart-toast.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        #cart-toast.error { border-left-color: var(--red); }
        .toast-icon { color: var(--gold); flex-shrink: 0; }
        #cart-toast.error .toast-icon { color: var(--red); }
        .toast-msg {
            font-family: var(--font-display);
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--off-white);
            flex: 1;
        }
        .toast-close {
            background: none; border: none; cursor: pointer;
            color: var(--dim); font-size: 1.1rem; line-height: 1;
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .toast-close:hover { color: var(--off-white); }
        .p-quick-add.loading { opacity: 0.6; pointer-events: none; }
    </style>

    <div id="cart-toast" role="alert" aria-live="polite">
        <span class="toast-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </span>
        <span class="toast-msg" id="toast-message">{{ __('file.item_added_to_cart') }}</span>
        <button class="toast-close" onclick="hideToast()" aria-label="{{ __('file.dismiss') }}">&times;</button>
    </div>

    <script>
    // JS Translations
    const translations = {
        adding: "{{ __('file.adding') }}",
        added: "{{ __('file.added') }}",
        item_added: "{{ __('file.item_added_to_cart') }}",
        error_adding: "{{ __('file.error_adding_to_cart') }}",
        network_error: "{{ __('file.network_error') }}"
    };
    /* ══════════════════════════════════════════
       ADD TO CART — AJAX
    ══════════════════════════════════════════ */
    const _csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let toastTimer = null;

    function showToast(msg, isError = false) {
        const toast = document.getElementById('cart-toast');
        const msgEl = document.getElementById('toast-message');
        msgEl.textContent = msg;
        toast.classList.toggle('error', isError);
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(hideToast, 3500);
    }

    function hideToast() {
        document.getElementById('cart-toast')?.classList.remove('show');
    }

    function addToCart(btn) {
        const variantId = btn.dataset.variantId;
        if (!variantId) return;

        btn.classList.add('loading');
        const originalText = btn.textContent;
        btn.textContent = translations.adding;

        fetch(`/cart/add/${variantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': _csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quantity: 1 }),
        })
        .then(async res => {
            const data = await res.json();
            if (data.success) {
                showToast(data.message ?? translations.item_added);
                // Update cart count badge in nav
                const badge = document.getElementById('navCartCount');
                if (badge) badge.textContent = data.cartCount ?? '';
                btn.textContent = '✓ ' + translations.added;
                setTimeout(() => { btn.textContent = originalText; }, 2000);
            } else {
                showToast(data.message ?? translations.error_adding, true);
                btn.textContent = originalText;
            }
        })
        .catch(() => {
            showToast(translations.network_error, true);
            btn.textContent = originalText;
        })
        .finally(() => {
            btn.classList.remove('loading');
        });
    }

    window.addEventListener('load', () => {
        if (typeof gsap === 'undefined') return;
        gsap.registerPlugin(ScrollTrigger);

        /* ── Hero slider ───────────────────────────── */
        let cur = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const total  = slides.length;
        const numEl  = document.getElementById('slideNum');
        const track  = document.getElementById('heroTrack');

        function goTo(n) {
            slides[cur].classList.remove('active');
            cur = (n + total) % total;
            slides[cur].classList.add('active');
            if (numEl) numEl.textContent = String(cur + 1).padStart(2,'0');
            gsap.to(track, { x: `-${cur * 100}%`, duration: 1.1, ease: 'power3.inOut' });
            const content = slides[cur].querySelector('.hero-content');
            gsap.fromTo(content.children,
                { opacity:0, y:32 },
                { opacity:1, y:0, duration:0.85, ease:'power3.out', stagger:0.12, delay:0.25 }
            );
        }

        document.getElementById('nextSlide')?.addEventListener('click', () => goTo(cur + 1));
        document.getElementById('prevSlide')?.addEventListener('click', () => goTo(cur - 1));

        let auto = setInterval(() => goTo(cur + 1), 6000);
        document.getElementById('heroSection')?.addEventListener('mouseenter', () => clearInterval(auto));
        document.getElementById('heroSection')?.addEventListener('mouseleave', () => { auto = setInterval(() => goTo(cur + 1), 6000); });

        /* Touch/swipe */
        let tx = 0;
        track.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
        track.addEventListener('touchend', e => {
            const dx = e.changedTouches[0].clientX - tx;
            if (Math.abs(dx) > 50) goTo(cur + (dx < 0 ? 1 : -1));
        });

        /* Hero entrance */
        const ht = gsap.timeline({ delay: 0.7 });
        ht.fromTo('.slide-eyebrow', { opacity:0, x:-20 }, { opacity:1, x:0, duration:0.55, ease:'power3.out' })
          .fromTo('.slide-title',   { opacity:0, y:55, skewY:2 }, { opacity:1, y:0, skewY:0, duration:0.9, ease:'power4.out' }, '-=0.3')
          .fromTo('.slide-sub',     { opacity:0, y:20 }, { opacity:1, y:0, duration:0.6, ease:'power3.out' }, '-=0.45')
          .fromTo('.slide-actions', { opacity:0, y:20 }, { opacity:1, y:0, duration:0.55, ease:'power3.out' }, '-=0.4')
          .fromTo('.slide-counter,.slide-arrows', { opacity:0 }, { opacity:1, duration:0.4 }, '-=0.3');

        /* Category tabs */
        document.querySelectorAll('.cat-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                gsap.from(this, { scaleX: 0.95, duration: 0.3, ease: 'back.out(2)' });
            });
        });

        /* Stats reveal */
        ScrollTrigger.create({
            trigger: '.stats-bar', start: 'top 88%', once: true,
            onEnter: () => gsap.fromTo('.stat-item', { opacity:0, y:20 }, { opacity:1, y:0, stagger:0.1, duration:0.6, ease:'power3.out' })
        });

        /* General reveals */
        document.querySelectorAll('.reveal').forEach((el, i) => {
            gsap.to(el, {
                opacity:1, y:0, duration:0.75, ease:'power3.out',
                delay: (i % 4) * 0.08,
                scrollTrigger: { trigger:el, start:'top 87%', toggleActions:'play none none none' }
            });
        });
        document.querySelectorAll('.reveal-x').forEach(el => {
            gsap.to(el, {
                opacity:1, x:0, duration:0.85, ease:'power3.out',
                scrollTrigger: { trigger:el, start:'top 85%', toggleActions:'play none none none' }
            });
        });

        /* Product grids stagger */
        document.querySelectorAll('.product-grid-4, .product-grid-3').forEach(grid => {
            const cards = grid.querySelectorAll('.p-card');
            cards.forEach(c => { c.style.opacity = '0'; c.style.transform = 'translateY(20px)'; });
            ScrollTrigger.create({
                trigger: grid, start: 'top 83%', once: true,
                onEnter: () => gsap.to(cards, { opacity:1, y:0, duration:0.65, ease:'power3.out', stagger:0.07 })
            });
        });

        /* Collections */
        document.querySelectorAll('.col-card').forEach((c, i) => {
            c.style.opacity = '0'; c.style.clipPath = 'inset(100% 0 0 0)';
            ScrollTrigger.create({
                trigger: c, start: 'top 88%', once: true,
                onEnter: () => gsap.to(c, { opacity:1, clipPath:'inset(0% 0 0 0)', duration:0.85, ease:'power4.out', delay: i * 0.1 })
            });
        });

        /* Trust stagger */
        ScrollTrigger.create({
            trigger: '.trust-bar', start: 'top 85%', once: true,
            onEnter: () => gsap.fromTo('.trust-cell', { opacity:0, y:16 }, { opacity:1, y:0, stagger:0.1, duration:0.55, ease:'power3.out' })
        });
    });
    </script>

@endsection