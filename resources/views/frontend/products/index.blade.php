@extends('frontend.layouts.app')

@section('title', 'Shop All | ' . ($store_name ?? 'Karbnzol'))

@section('content')

<style>
    /* ── LOCAL STYLES (Shop Page) ─────────────────────────────────── */
    .shop-header {
        padding: 5rem 0 3rem;
        text-align: center;
        background: var(--cream);
        border-bottom: 1px solid var(--sand);
    }
    .shop-title {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 300;
        color: var(--ink);
        line-height: 1.1;
        margin-bottom: 1rem;
    }
    .shop-title em {
        font-style: italic;
        color: var(--rust);
    }
    .shop-desc {
        font-size: 0.9375rem;
        color: var(--mink);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ── LAYOUT ─────────────────────────────────────────────────── */
    .shop-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 4rem 2rem;
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 3rem;
        align-items: start;
    }
    
    @media (max-width: 992px) {
        .shop-container {
            grid-template-columns: 1fr;
        }
    }

    /* ── SIDEBAR FILTERS ───────────────────────────────────────── */
    .filter-sidebar {
        position: sticky;
        top: calc(var(--nav-h) + 2rem);
    }
    .filter-group {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--sand);
    }
    .filter-group:last-child {
        border-bottom: none;
    }
    .filter-title {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-weight: 400;
        color: var(--ink);
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }
    .filter-title svg {
        transition: transform 0.3s;
    }
    .filter-title.closed svg {
        transform: rotate(-90deg);
    }
    .filter-options {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        transition: max-height 0.3s ease-out, opacity 0.3s;
        overflow: hidden;
    }
    .filter-options.closed {
        max-height: 0;
        opacity: 0;
    }
    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        font-size: 0.8125rem;
        color: var(--mink);
        transition: color 0.2s;
    }
    .filter-checkbox:hover {
        color: var(--ink);
    }
    .filter-checkbox input {
        appearance: none;
        width: 16px;
        height: 16px;
        border: 1px solid var(--mink);
        border-radius: 2px;
        display: grid;
        place-content: center;
        transition: background 0.2s, border-color 0.2s;
    }
    .filter-checkbox input::before {
        content: "";
        width: 8px;
        height: 8px;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em var(--white);
        background-color: var(--white);
        transform-origin: center;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }
    .filter-checkbox input:checked {
        background: var(--rust);
        border-color: var(--rust);
    }
    .filter-checkbox input:checked::before {
        transform: scale(1);
    }
    .filter-color-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }
    .filter-color {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.2s, border-color 0.2s;
        box-shadow: 0 0 0 1px rgba(107, 94, 82, 0.2);
    }
    .filter-color:hover, .filter-color.active {
        transform: scale(1.15);
        border-color: var(--white);
        box-shadow: 0 0 0 1px var(--ink);
    }

    /* ── PRODUCT GRID AREA ─────────────────────────────────────── */
    .shop-main {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .shop-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--sand);
    }
    .shop-results-count {
        font-size: 0.8125rem;
        color: var(--mink);
        letter-spacing: 0.05em;
    }
    .shop-sort select {
        appearance: none;
        background: transparent;
        border: none;
        border-bottom: 1px solid var(--ink);
        padding: 0.25rem 1.5rem 0.25rem 0;
        font-family: var(--font-body);
        font-size: 0.8125rem;
        color: var(--ink);
        cursor: pointer;
        outline: none;
        border-radius: 0;
    }
    .shop-sort-wrap {
        position: relative;
    }
    .shop-sort-wrap::after {
        content: '▼';
        font-size: 0.5rem;
        position: absolute;
        right: 0.25rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .shop-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    @media (max-width: 1200px) {
        .shop-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .shop-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    }

    /* Resuable Product Card Styles (copied from home for standalone use) */
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
    .product-wishlist {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        width: 34px;
        height: 34px;
        background: var(--white);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transform: scale(0.85);
        transition: opacity 0.25s, transform 0.25s;
        z-index: 2; cursor: pointer; border: none;
    }
    .product-card:hover .product-wishlist { opacity: 1; transform: scale(1); }
    .product-wishlist:hover svg { stroke: var(--rust); }
    .product-quick-add {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: rgba(26, 22, 18, 0.88);
        backdrop-filter: blur(6px);
        color: var(--cream);
        text-align: center;
        padding: 0.75rem;
        font-size: 0.7rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase;
        transform: translateY(100%); transition: transform 0.3s var(--ease-silk);
        cursor: pointer; border: none; width: 100%;
    }
    .product-card:hover .product-quick-add { transform: translateY(0); }
    .product-info { padding: 1rem 1.125rem 1.25rem; }
    .product-category { font-size: 0.65rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--mink); margin-bottom: 0.35rem; }
    .product-name { font-family: var(--font-display); font-size: 1.1rem; font-weight: 400; color: var(--ink); margin-bottom: 0.5rem; line-height: 1.25; }
    .product-price-row { display: flex; align-items: center; gap: 0.6rem; }
    .price-current { font-size: 0.9375rem; font-weight: 600; color: var(--ink); }
    .price-original { font-size: 0.8125rem; color: var(--mink); text-decoration: line-through; }
    .product-colors { display: flex; gap: 5px; margin-top: 0.6rem; }
    .color-swatch { width: 14px; height: 14px; border-radius: 50%; border: 2px solid var(--white); box-shadow: 0 0 0 1px rgba(107, 94, 82, 0.25); cursor: pointer; transition: transform 0.2s; }
    .color-swatch:hover { transform: scale(1.3); }

    /* ── PAGINATION ─────────────────────────────────────────────── */
    .shop-pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid var(--sand);
    }
    .page-link {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid var(--sand);
        font-size: 0.8125rem;
        color: var(--mink);
        transition: all 0.25s;
    }
    .page-link:hover, .page-link.active {
        background: var(--ink);
        color: var(--cream);
        border-color: var(--ink);
    }
    
    /* GSAP Classes */
    .fade-up { opacity: 0; transform: translateY(32px); }
</style>

{{-- ── HEADER ── --}}
<header class="shop-header fade-up">
    <h1 class="shop-title">Shop <em>All</em></h1>
    <p class="shop-desc">Discover our thoughtfully curated collection of timeless pieces, designed for modern living.</p>
</header>

{{-- ── MAIN CONTAINER ── --}}
<div class="shop-container">
    
    {{-- ── SIDEBAR FILTERS ── --}}
    <aside class="filter-sidebar fade-up">
        
        {{-- Category Filter --}}
        <div class="filter-group">
            <div class="filter-title" onclick="toggleFilter(this)">
                Categories
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <div class="filter-options">
                <label class="filter-checkbox">
                    <input type="checkbox" checked> All Products
                </label>
                @foreach($categories as $category)
                    <label class="filter-checkbox">
                        <input type="checkbox" name="category[]" value="{{ $category->slug }}"> {{ $category->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Price Filter --}}
        <div class="filter-group">
            <div class="filter-title" onclick="toggleFilter(this)">
                Price
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <div class="filter-options">
                <label class="filter-checkbox">
                    <input type="checkbox"> Under Rs. 5,000
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox"> Rs. 5,000 - Rs. 10,000
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox"> Over Rs. 10,000
                </label>
            </div>
        </div>

        {{-- Color Filter --}}
        <div class="filter-group">
            <div class="filter-title" onclick="toggleFilter(this)">
                Color
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <div class="filter-options">
                <div class="filter-color-grid">
                    @foreach($colors as $color)
                        <div class="filter-color" style="background: {{ $color->color_hex }};" title="{{ $color->value }}">
                            <input type="checkbox" name="color[]" value="{{ $color->slug }}" style="display: none;">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Size Filter --}}
        <div class="filter-group">
            <div class="filter-title" onclick="toggleFilter(this)">
                Size
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <div class="filter-options">
                @foreach($sizes as $size)
                    <label class="filter-checkbox">
                        <input type="checkbox" name="size[]" value="{{ $size->slug }}"> {{ $size->value }}
                    </label>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- ── PRODUCT MAIN ── --}}
    <main class="shop-main">
        
        {{-- Toolbar --}}
        <div class="shop-toolbar fade-up">
            <div class="shop-results-count">Showing 1-12 of 145 products</div>
            <div class="shop-sort-wrap">
                <select class="shop-sort" aria-label="Sort by">
                    <option value="featured">Featured</option>
                    <option value="newest">New Arrivals</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                </select>
            </div>
        </div>

        {{-- Grid --}}
        <div class="shop-grid">
            
            {{-- DUMMY DATA LOOP FOR NOW --}}
            @php
                $dummyProducts = [
                    ['name' => 'Linen Wrap Dress', 'category' => 'Women', 'price' => 'Rs. 5,800', 'original' => null, 'badge' => 'New', 'colors' => ['#d4b896', '#c4602a'], 'img' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=600&q=80'],
                    ['name' => 'Structured Blazer', 'category' => 'Men', 'price' => 'Rs. 9,200', 'original' => 'Rs. 11,500', 'badge' => 'Sale', 'colors' => ['#1a1612', '#6b5e52'], 'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80'],
                    ['name' => 'Silk Slip Skirt', 'category' => 'Women', 'price' => 'Rs. 4,400', 'original' => null, 'badge' => null, 'colors' => ['#f5f0e8', '#e8dfc8'], 'img' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=600&q=80'],
                    ['name' => 'Tailored Wide Trousers', 'category' => 'Women', 'price' => 'Rs. 6,400', 'original' => null, 'badge' => 'Best Seller', 'colors' => ['#1a1612', '#f5f0e8'], 'img' => 'https://images.unsplash.com/photo-1594938298603-c8148c4b4e3d?w=600&q=80'],
                    ['name' => 'Classic Poplin Shirt', 'category' => 'Men', 'price' => 'Rs. 3,800', 'original' => null, 'badge' => null, 'colors' => ['#ffffff', '#c4d6e0'], 'img' => 'https://images.unsplash.com/photo-1581824043583-6904b080a19c?w=600&q=80'],
                    ['name' => 'Knit Cardigan', 'category' => 'Women', 'price' => 'Rs. 7,200', 'original' => 'Rs. 8,800', 'badge' => 'Sale', 'colors' => ['#e8874f', '#f5f0e8'], 'img' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600&q=80'],
                    ['name' => 'Oversized Tee', 'category' => 'Unisex', 'price' => 'Rs. 2,100', 'original' => null, 'badge' => null, 'colors' => ['#ffffff', '#1a1612'], 'img' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80'],
                    ['name' => 'Leather Tote Bag', 'category' => 'Accessories', 'price' => 'Rs. 14,500', 'original' => null, 'badge' => null, 'colors' => ['#c4602a'], 'img' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80'],
                    ['name' => 'Cashmere Crew Neck', 'category' => 'Men', 'price' => 'Rs. 12,000', 'original' => null, 'badge' => 'Luxury', 'colors' => ['#6b5e52', '#1a1612'], 'img' => 'https://images.unsplash.com/photo-1620799139834-6b8f844fbe61?w=600&q=80'],
                ];
            @endphp
            
            {{-- In reality, we'd use: @foreach($products as $product) --}}
            @foreach($dummyProducts as $product)
                <div class="product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ $product['img'] }}" alt="{{ $product['name'] }}" loading="lazy">
                        @if($product['badge'])
                            <span class="product-badge" {{ $product['badge'] == 'Sale' ? 'style="background:var(--ink);"' : '' }}>{{ $product['badge'] }}</span>
                        @endif
                        <button class="product-wishlist" aria-label="Add to wishlist">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <button class="product-quick-add">+ Quick Add</button>
                    </div>
                    <div class="product-info">
                        <p class="product-category">{{ $product['category'] }}</p>
                        <h3 class="product-name">
                            {{-- <a href="{{ route('frontend.products.show', $product->slug) }}">...</a> --}}
                            <a href="#">{{ $product['name'] }}</a>
                        </h3>
                        <div class="product-price-row">
                            <span class="price-current">{{ $product['price'] }}</span>
                            @if($product['original'])
                                <span class="price-original">{{ $product['original'] }}</span>
                            @endif
                        </div>
                        <div class="product-colors">
                            @foreach($product['colors'] as $color)
                                <span class="color-swatch" style="background: {{ $color }};"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            
        </div>

        {{-- Pagination --}}
        <div class="shop-pagination fade-up">
            <a href="#" class="page-link" aria-label="Previous">&laquo;</a>
            <a href="#" class="page-link active">1</a>
            <a href="#" class="page-link">2</a>
            <a href="#" class="page-link">3</a>
            <span class="page-link" style="border:none;">...</span>
            <a href="#" class="page-link">12</a>
            <a href="#" class="page-link" aria-label="Next">&raquo;</a>
            
            {{-- Blade Pagination when using real data: --}}
            {{-- {{ $products->links() }} --}}
        </div>

    </main>
</div>

<script>
    // Simple filter toggle script
    function toggleFilter(element) {
        element.classList.toggle('closed');
        const options = element.nextElementSibling;
        if(element.classList.contains('closed')){
            options.classList.add('closed');
        } else {
            options.classList.remove('closed');
        }
    }

    // Connect with GSAP
    window.addEventListener('load', () => {
        if(typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            
            // Fade-up elements
            document.querySelectorAll('.fade-up').forEach((el, i) => {
                gsap.to(el, {
                    opacity: 1, y: 0,
                    duration: 0.8,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: el,
                        start: 'top 90%',
                        toggleActions: 'play none none none'
                    },
                    delay: (i % 5) * 0.08 // slight stagger for grid items
                });
            });

            // Product Cards stagger
            gsap.to('.product-card', {
                opacity: 1, y: 0,
                duration: 0.8,
                ease: 'power3.out',
                stagger: 0.1,
                scrollTrigger: {
                    trigger: '.shop-grid',
                    start: 'top 85%'
                }
            });
        }
    });
</script>

@endsection

