@extends('frontend.layouts.app')

@section('title', 'Browse Collections | ' . ($store_name ?? 'Karbnzol'))

@section('content')
<style>
    :root {
        --bg: #1a1a1a;
        --bg-2: #222222;
        --bg-3: #2a2a2a;
        --bg-4: #333333;
        --white: #ffffff;
        --off-white: #f0f0f0;
        --silver: #d1d5db;
        --dim: #a1a1aa;
        --gold: #c8a96e;
        --gold-bg: rgba(200, 169, 110, 0.1);
        --font-display: 'Oswald', 'Arial Narrow', sans-serif;
        --font-body: 'Barlow', sans-serif;
        --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .collections-page {
        background: var(--bg);
        min-height: 80vh;
        padding-bottom: 5rem;
    }

    /* ── BREADCRUMB ──────────────────────────────────────── */
    .breadcrumb-bar {
        background: var(--bg-2);
        border-bottom: 1px solid var(--bg-4);
        padding: 0.75rem 2rem;
    }

    .breadcrumb-inner {
        max-width: 1600px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: var(--font-display);
        font-size: 0.6rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--dim);
    }

    .breadcrumb-inner a {
        color: var(--dim);
        transition: color 0.2s;
    }

    .breadcrumb-inner a:hover {
        color: var(--gold);
    }

    .breadcrumb-inner .sep {
        color: var(--bg-4);
    }

    .breadcrumb-inner .current {
        color: var(--silver);
    }

    /* ── HEADER ──────────────────────────────────────────── */
    .page-header {
        padding: 4rem 2rem 3rem;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .header-eyebrow {
        font-family: var(--font-display);
        font-size: 0.65rem;
        font-weight: 500;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }
    .header-eyebrow::before, .header-eyebrow::after {
        content: ''; width: 20px; height: 1px; background: var(--gold);
    }

    .header-title {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--off-white);
        line-height: 0.95;
        margin-bottom: 1.5rem;
    }

    .header-desc {
        font-size: 1rem;
        color: var(--dim);
        line-height: 1.6;
        font-weight: 300;
    }

    /* ── GRID ────────────────────────────────────────────── */
    .collections-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .collections-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
    }

    @media (max-width: 768px) {
        .collections-grid {
            grid-template-columns: 1fr;
        }
    }

    .col-card {
        position: relative;
        aspect-ratio: 16/9;
        overflow: hidden;
        background: var(--bg-2);
        border: 1px solid var(--bg-4);
        transition: transform 0.3s var(--ease-out);
        display: block;
    }

    .col-card:hover {
        border-color: var(--gold);
    }

    .col-img {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transition: transform 0.8s var(--ease-out), filter 0.5s;
        filter: brightness(0.5);
    }

    .col-card:hover .col-img {
        transform: scale(1.05);
        filter: brightness(0.7);
    }

    .col-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(26,26,26,0.9) 0%, rgba(26,26,26,0.2) 60%, transparent 100%);
        z-index: 1;
    }

    .col-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        z-index: 2;
        display: flex;
        flex-direction: column;
    }

    .col-tag {
        font-family: var(--font-display);
        font-size: 0.55rem;
        font-weight: 600;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.5rem;
    }

    .col-name {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--white);
        line-height: 1;
        margin-bottom: 0.75rem;
    }

    .col-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        font-size: 0.7rem;
        color: var(--dim);
        font-weight: 400;
        letter-spacing: 0.05em;
    }

    .col-meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .col-arrow {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 44px;
        height: 44px;
        border: 1px solid rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        z-index: 2;
        transition: all 0.3s;
        backdrop-filter: blur(4px);
        background: rgba(26,26,26,0.2);
    }

    .col-card:hover .col-arrow {
        background: var(--gold);
        border-color: var(--gold);
        color: var(--bg);
        transform: rotate(-45deg);
    }

    /* ── EMPTY STATE ─────────────────────────────────────── */
    .empty-state {
        padding: 6rem 2rem;
        text-align: center;
        color: var(--dim);
        font-family: var(--font-display);
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    /* ── PAGINATION ──────────────────────────────────────── */
    .pagination-wrap {
        margin-top: 4rem;
        display: flex;
        justify-content: center;
    }

    /* Reveal animation */
    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s var(--ease-out);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

</style>

<div class="collections-page">
    {{-- Breadcrumb --}}
    <div class="breadcrumb-bar">
        <div class="breadcrumb-inner">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            <span class="current">Collections</span>
        </div>
    </div>

    {{-- Header --}}
    <header class="page-header reveal">
        <p class="header-eyebrow">Curated Editions</p>
        <h1 class="header-title">Our Collections</h1>
        <p class="header-desc">
            Explore our meticulously curated collections, where every piece is selected to embody the pinnacle of modern craftsmanship and timeless style.
        </p>
    </header>

    {{-- Collections Grid --}}
    <div class="collections-container">
        @if($collections->isNotEmpty())
            <div class="collections-grid">
                @foreach($collections as $i => $col)
                    @php
                        $colImg = $col->banner_url
                            ?? ($col->products->first()?->primaryImage
                                ? $col->products->first()->primaryImage->url
                                : \Illuminate\Support\Facades\Blade::render("@placeholder($col->id)"));
                    @endphp
                    <a href="{{ route('frontend.products.index', ['collection' => $col->slug]) }}" class="col-card reveal" style="transition-delay: {{ $i * 0.1 }}s">
                        <div class="col-img" style="background-image: url('{{ $colImg }}');"></div>
                        <div class="col-overlay"></div>
                        <div class="col-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </div>
                        <div class="col-content">
                            <p class="col-tag">Season Edit</p>
                            <h2 class="col-name">{{ $col->name }}</h2>
                            <div class="col-meta">
                                <div class="col-meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    {{ $col->products_count }} {{ Str::plural('Style', $col->products_count) }}
                                </div>
                                @if($col->start_date)
                                    <div class="col-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        {{ $col->start_date->format('M Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="pagination-wrap reveal">
                {{ $collections->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state reveal">
                <p>New collections are currently in the works. Stay tuned.</p>
                <a href="{{ route('frontend.products.index') }}" class="btn-gold mt-6" style="display:inline-flex;">View All Products</a>
            </div>
        @endif
    </div>
</div>

<script>
    // Intersection Observer for reveal animation
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>
@endsection
