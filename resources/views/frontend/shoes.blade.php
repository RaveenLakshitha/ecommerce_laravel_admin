<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SHOE CARNIVAL — Official Store</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
:root {
  --teal: #00897b;
  --teal-dark: #00695c;
  --red: #e53935;
  --dark: #111;
  --mid: #555;
  --light-gray: #f5f5f5;
  --border: #e0e0e0;
  --white: #fff;
  --font-display: 'Barlow Condensed', sans-serif;
  --font-body: 'Barlow', sans-serif;
}
html { scroll-behavior: smooth; }
body { font-family: var(--font-body); background: var(--white); color: var(--dark); overflow-x: hidden; }
a { text-decoration: none; color: inherit; }
img { display: block; max-width: 100%; }

/* TOP BAR */
.top-bar {
  background: var(--teal); color: #fff;
  font-size: 0.78rem; font-weight: 500; letter-spacing: 0.5px;
  display: flex; align-items: center; justify-content: center;
  padding: 0.5rem 2rem; position: relative; gap: 0.4rem;
}
.top-bar .top-link { color: #fff; text-decoration: underline; }
.top-nav-btn {
  position: absolute; background: none; border: none; color: #fff; cursor: pointer; font-size: 1rem; opacity: 0.7;
}

/* NAV */
nav {
  position: sticky; top: 0; z-index: 200;
  background: var(--white);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 2rem; height: 58px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.nav-logo {
  font-family: var(--font-display); font-size: 1.55rem; font-weight: 800;
  letter-spacing: 0.5px; color: var(--dark); white-space: nowrap;
}
.nav-logo span { color: var(--teal); }
.nav-menu { display: flex; align-items: center; height: 58px; }
.nav-menu a {
  font-family: var(--font-display); font-size: 0.95rem; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase; color: var(--dark);
  padding: 0 1rem; height: 100%; display: flex; align-items: center;
  border-bottom: 3px solid transparent; transition: color 0.2s, border-color 0.2s;
}
.nav-menu a:hover { color: var(--teal); border-bottom-color: var(--teal); }
.nav-menu a.perks { color: var(--red); }
.nav-right { display: flex; align-items: center; gap: 0.2rem; }
.nav-btn {
  display: flex; align-items: center; gap: 0.3rem;
  font-size: 0.8rem; font-weight: 500; padding: 0.4rem 0.8rem;
  background: none; border: none; cursor: pointer; color: var(--dark); transition: color 0.2s;
}
.nav-btn:hover { color: var(--teal); }
.cart-count {
  background: var(--teal); color: #fff; font-size: 0.62rem; font-weight: 700;
  width: 17px; height: 17px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
}
.nav-search-btn {
  background: var(--dark); color: var(--white); padding: 0.4rem 1rem;
  display: flex; align-items: center; gap: 0.4rem;
  font-size: 0.8rem; font-weight: 600; border: none; cursor: pointer; letter-spacing: 0.5px;
  transition: background 0.2s;
}
.nav-search-btn:hover { background: var(--teal); }

/* HERO */
.hero {
  height: 460px; position: relative; overflow: hidden;
  background: #0d1117; display: flex; align-items: center;
}
.hero-img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.5;
}
.hero-content {
  position: relative; z-index: 2; padding: 0 5rem; max-width: 620px;
  opacity: 0; transform: translateY(28px);
}
.hero-eyebrow {
  font-family: var(--font-display); font-size: 0.8rem; font-weight: 700;
  letter-spacing: 4px; text-transform: uppercase; color: var(--teal); margin-bottom: 0.7rem;
}
.hero-title {
  font-family: var(--font-display); font-size: clamp(3rem, 6vw, 5.5rem);
  font-weight: 800; line-height: 0.9; text-transform: uppercase; color: #fff;
  letter-spacing: 1px; margin-bottom: 1rem;
}
.hero-title em { color: var(--teal); font-style: normal; }
.hero-sub { font-size: 0.95rem; color: rgba(255,255,255,0.7); margin-bottom: 1.8rem; font-weight: 300; line-height: 1.6; }
.hero-btns { display: flex; gap: 1rem; }
.btn-teal {
  background: var(--teal); color: #fff; padding: 0.8rem 2rem;
  font-family: var(--font-display); font-size: 1rem; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase; border: none; cursor: pointer;
  display: inline-block; transition: background 0.2s;
}
.btn-teal:hover { background: var(--teal-dark); }
.btn-outline-white {
  background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.45);
  padding: 0.8rem 2rem; font-family: var(--font-display); font-size: 1rem; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase; cursor: pointer;
  display: inline-block; transition: border-color 0.2s;
}
.btn-outline-white:hover { border-color: #fff; }

/* TICKER */
.ticker {
  background: var(--dark); overflow: hidden; padding: 0.5rem 0; white-space: nowrap;
}
.ticker-inner {
  display: inline-block; animation: tick 26s linear infinite;
  font-family: var(--font-display); font-size: 0.75rem; letter-spacing: 2px;
  text-transform: uppercase; color: rgba(255,255,255,0.65); font-weight: 600;
}
@keyframes tick { from{transform:translateX(0)} to{transform:translateX(-50%)} }

/* SECTION SHARED */
.section { padding: 3rem 2rem; }
.section-head {
  display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 1.4rem;
}
.section-title {
  font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2rem);
  font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--dark);
}
.view-all {
  font-size: 0.8rem; font-weight: 600; color: var(--teal); border-bottom: 1px solid var(--teal);
  padding-bottom: 1px; transition: color 0.2s;
}
.view-all:hover { color: var(--teal-dark); border-color: var(--teal-dark); }

/* PRODUCT SCROLL */
.product-scroll-wrap { position: relative; }
.product-scroll {
  display: flex; gap: 1px; background: var(--border);
  overflow-x: auto; scroll-snap-type: x mandatory;
  -ms-overflow-style: none; scrollbar-width: none;
}
.product-scroll::-webkit-scrollbar { display: none; }
.product-card {
  flex: 0 0 calc(20% - 1px); min-width: 210px;
  background: var(--white); scroll-snap-align: start;
  position: relative; cursor: pointer; transition: box-shadow 0.2s;
}
.product-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); z-index: 2; }
.product-img-box {
  background: #f0f0f0; aspect-ratio: 1/1; overflow: hidden;
  position: relative; display: flex; align-items: center; justify-content: center;
}
.product-img-box img {
  width: 84%; height: 84%; object-fit: contain;
  transition: transform 0.35s ease;
}
.product-card:hover .product-img-box img { transform: scale(1.07); }
.badge {
  position: absolute; top: 0.7rem; left: 0;
  font-family: var(--font-display); font-size: 0.68rem; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase; padding: 0.2rem 0.6rem; color: #fff;
}
.badge.new { background: var(--teal); }
.badge.sale { background: var(--red); }
.swatch-row {
  position: absolute; bottom: 0.6rem; left: 0.7rem; display: flex; gap: 4px;
}
.swatch {
  width: 17px; height: 17px; border-radius: 50%;
  border: 2px solid #fff; box-shadow: 0 0 0 1px #ccc;
}
.product-info { padding: 0.7rem 0.85rem 0.9rem; }
.brand-name {
  font-family: var(--font-display); font-size: 0.68rem; font-weight: 700;
  letter-spacing: 1.5px; text-transform: uppercase; color: var(--mid); margin-bottom: 0.2rem;
}
.product-name { font-size: 0.82rem; font-weight: 400; color: var(--dark); line-height: 1.35; margin-bottom: 0.4rem; }
.price-row { display: flex; align-items: center; gap: 0.5rem; }
.price { font-size: 0.92rem; font-weight: 600; color: var(--dark); }
.price-orig { font-size: 0.8rem; color: #bbb; text-decoration: line-through; }
.price-sale { color: var(--red); }
.stars { display: flex; align-items: center; gap: 2px; margin-top: 0.3rem; color: #f5a623; font-size: 0.72rem; }
.star-count { font-size: 0.7rem; color: #aaa; margin-left: 3px; }
.scroll-arrow {
  position: absolute; top: 42%; transform: translateY(-50%);
  width: 36px; height: 36px; background: var(--white); border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  font-size: 1rem; color: var(--dark); transition: background 0.2s, color 0.2s;
}
.scroll-arrow:hover { background: var(--teal); color: #fff; border-color: var(--teal); }
.scroll-arrow.left { left: -18px; }
.scroll-arrow.right { right: -18px; }

/* PROMO BANNER */
.promo-banner {
  margin: 0 2rem 0; position: relative; overflow: hidden;
  height: 195px; display: flex; align-items: center; background: #0d0d0d; cursor: pointer;
}
.promo-banner img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.4;
}
.promo-content {
  position: relative; z-index: 2; display: flex; align-items: center;
  justify-content: space-between; width: 100%; padding: 0 3rem;
}
.promo-left { display: flex; flex-direction: column; gap: 0.2rem; }
.promo-eyebrow {
  font-family: var(--font-display); font-size: 0.73rem; font-weight: 700;
  letter-spacing: 3px; text-transform: uppercase; color: var(--teal);
}
.promo-headline {
  font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 800; text-transform: uppercase; color: #fff; line-height: 1;
}
.promo-headline em { color: var(--red); font-style: normal; font-size: 1.3em; }
.promo-sub { font-size: 0.8rem; color: rgba(255,255,255,0.6); margin-top: 0.2rem; }
.promo-cta {
  background: #fff; color: var(--dark); font-family: var(--font-display);
  font-size: 1rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
  padding: 0.8rem 2rem; border: none; cursor: pointer; white-space: nowrap;
  transition: background 0.2s, color 0.2s;
}
.promo-cta:hover { background: var(--teal); color: #fff; }

/* CATEGORY GRID */
.categories { background: var(--white); padding: 3rem 2rem; }
.cat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; }
.cat-card {
  position: relative; overflow: hidden; aspect-ratio: 3/4;
  cursor: pointer; display: flex; align-items: flex-end;
}
.cat-card img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.5s ease;
}
.cat-card:hover img { transform: scale(1.06); }
.cat-card::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.05) 55%, transparent 100%);
  z-index: 1;
}
.cat-label { position: relative; z-index: 2; width: 100%; padding-bottom: 1rem; }
.cat-pill {
  display: inline-block; background: var(--teal);
  padding: 0.3rem 1rem; margin-left: 1rem;
  font-family: var(--font-display); font-size: 1.05rem; font-weight: 800;
  letter-spacing: 2px; text-transform: uppercase; color: #fff;
}
.cat-sub { font-size: 0.7rem; color: rgba(255,255,255,0.75); padding-left: 1.1rem; margin-top: 0.3rem; }

/* BRANDS MARQUEE */
.brands-strip {
  background: #fff; padding: 2.8rem 0;
  border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
}
.brands-heading {
  text-align: center; margin-bottom: 1.6rem;
  font-family: var(--font-display); font-size: 0.72rem; font-weight: 700;
  letter-spacing: 3px; text-transform: uppercase; color: #aaa;
}
.brands-marquee-outer {
  position: relative; overflow: hidden;
}
.brands-marquee-outer::before,
.brands-marquee-outer::after {
  content: ''; position: absolute; top: 0; bottom: 0; width: 80px; z-index: 2;
  pointer-events: none;
}
.brands-marquee-outer::before {
  left: 0;
  background: linear-gradient(to right, #fff 0%, transparent 100%);
}
.brands-marquee-outer::after {
  right: 0;
  background: linear-gradient(to left, #fff 0%, transparent 100%);
}
.brands-track {
  display: flex; align-items: center; gap: 0;
  animation: brandScroll 28s linear infinite;
  width: max-content;
}
.brands-track:hover { animation-play-state: paused; }
@keyframes brandScroll {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.brand-logo-box {
  flex-shrink: 0; width: 200px; height: 90px;
  display: flex; align-items: center; justify-content: center;
  padding: 0 2.5rem; cursor: pointer;
  filter: grayscale(0.6); opacity: 0.65;
  transition: filter 0.35s, opacity 0.35s, transform 0.25s;
  border-right: 1px solid var(--border);
}
.brand-logo-box:hover { filter: grayscale(0); opacity: 1; transform: scale(1.06); }
.brand-logo-box img { height: 48px; width: auto; object-fit: contain; max-width: 160px; }

/* TRUST BAR */
.trust-bar {
  background: var(--white); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
  display: grid; grid-template-columns: repeat(4,1fr);
}
.trust-item {
  display: flex; align-items: center; gap: 0.9rem;
  padding: 1.4rem 2rem; border-right: 1px solid var(--border);
}
.trust-item:last-child { border-right: none; }
.trust-icon { font-size: 1.5rem; flex-shrink: 0; }
.trust-text strong { display: block; font-size: 0.82rem; font-weight: 600; color: var(--dark); }
.trust-text span { font-size: 0.75rem; color: #999; }

/* NEWSLETTER */
.newsletter {
  background: var(--teal); padding: 4rem 2rem; text-align: center;
}
.newsletter-title {
  font-family: var(--font-display); font-size: 2.4rem; font-weight: 800;
  text-transform: uppercase; color: #fff; letter-spacing: 1px; margin-bottom: 0.4rem;
}
.newsletter-sub { font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-bottom: 1.8rem; }
.newsletter-form { display: flex; max-width: 440px; margin: 0 auto; }
.newsletter-form input {
  flex: 1; padding: 0.85rem 1.2rem; border: none; outline: none;
  font-family: var(--font-body); font-size: 0.88rem;
  background: rgba(255,255,255,0.18); color: #fff;
  border-bottom: 2px solid rgba(255,255,255,0.4);
}
.newsletter-form input::placeholder { color: rgba(255,255,255,0.6); }
.newsletter-form button {
  background: var(--dark); color: #fff; border: none; padding: 0.85rem 1.6rem;
  font-family: var(--font-display); font-size: 1rem; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: background 0.2s;
}
.newsletter-form button:hover { background: #333; }
.newsletter-note { font-size: 0.72rem; color: rgba(255,255,255,0.55); margin-top: 0.8rem; }

/* FOOTER */
footer { background: #111; color: rgba(255,255,255,0.65); padding: 3.5rem 2rem 1.5rem; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2.5rem; margin-bottom: 2.5rem; }
.footer-logo { font-family: var(--font-display); font-size: 1.9rem; font-weight: 800; color: #fff; letter-spacing: 0.5px; display: block; margin-bottom: 0.7rem; }
.footer-logo span { color: var(--teal); }
.footer-desc { font-size: 0.8rem; line-height: 1.7; color: rgba(255,255,255,0.4); max-width: 230px; margin-bottom: 1.2rem; }
.footer-socials { display: flex; gap: 0.4rem; }
.social-btn {
  width: 32px; height: 32px; border: 1px solid rgba(255,255,255,0.12);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.45);
  cursor: pointer; transition: border-color 0.2s, color 0.2s;
}
.social-btn:hover { border-color: var(--teal); color: var(--teal); }
.footer-col h5 {
  font-family: var(--font-display); font-size: 0.72rem; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.3);
  margin-bottom: 0.9rem;
}
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 0.55rem; }
.footer-col a { font-size: 0.82rem; color: rgba(255,255,255,0.5); transition: color 0.2s; }
.footer-col a:hover { color: var(--teal); }
.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.07); padding-top: 1.3rem;
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
}
.footer-copy { font-size: 0.72rem; color: rgba(255,255,255,0.28); }
.pay-icons { display: flex; gap: 0.35rem; }
.pay-icon {
  background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
  padding: 0.22rem 0.5rem; font-size: 0.6rem; font-weight: 700;
  color: rgba(255,255,255,0.38); letter-spacing: 0.5px;
}
</style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
  <button class="top-nav-btn" style="left:1.5rem;">‹</button>
  📍 Fast, Reliable Delivery — Most orders in as soon as 2 days. &nbsp;<a href="#" class="top-link">Shop Now</a>
  <button class="top-nav-btn" style="right:1.5rem;">›</button>
</div>

<!-- NAV -->
<nav>
  <a href="#" class="nav-logo">SHOE <span>CARNIVAL</span></a>
  <div class="nav-menu">
    <a href="#">Womens</a>
    <a href="#">Mens</a>
    <a href="#">Kids</a>
    <a href="#">Accessories</a>
    <a href="#">Brands</a>
    <a href="#">Deals</a>
    <a href="#" class="perks">Shoe Perks</a>
  </div>
  <div class="nav-right">
    <button class="nav-btn">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Sign In
    </button>
    <button class="nav-btn">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      <span class="cart-count">0</span>
    </button>
    <button class="nav-search-btn">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      Search
    </button>
  </div>
</nav>

<!-- HERO -->
<div class="hero">
  <img class="hero-img" src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1400&q=80" alt="Hero">
  <div class="hero-content" id="heroContent">
    <div class="hero-eyebrow">Spring / Summer 2025 Collection</div>
    <h1 class="hero-title">New Season.<br><em>New Steps.</em></h1>
    <p class="hero-sub">Explore the latest from Nike, Adidas, New Balance & more. Free shipping on orders over $75.</p>
    <div class="hero-btns">
      <a href="#" class="btn-teal">Shop Now</a>
      <a href="#" class="btn-outline-white">Browse All</a>
    </div>
  </div>
</div>

<!-- TICKER -->
<div class="ticker">
  <div class="ticker-inner">FREE SHIPPING ON $75+ &nbsp;·&nbsp; 30-DAY EASY RETURNS &nbsp;·&nbsp; SHOE PERKS MEMBERS EARN 2X POINTS &nbsp;·&nbsp; NEW ARRIVALS WEEKLY &nbsp;·&nbsp; SECURE CHECKOUT &nbsp;·&nbsp; FREE SHIPPING ON $75+ &nbsp;·&nbsp; 30-DAY EASY RETURNS &nbsp;·&nbsp; SHOE PERKS MEMBERS EARN 2X POINTS &nbsp;·&nbsp; NEW ARRIVALS WEEKLY &nbsp;·&nbsp; SECURE CHECKOUT &nbsp;·&nbsp;</div>
</div>

<!-- BEST SELLERS -->
<section class="section">
  <div class="section-head">
    <div class="section-title">Best Sellers</div>
    <a href="#" class="view-all">View All →</a>
  </div>
  <div class="product-scroll-wrap">
    <button class="scroll-arrow left" onclick="scrollRow('best',-1)">‹</button>
    <div class="product-scroll" id="best">
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80" alt="Nike Air Max">
          <span class="badge sale">-30%</span>
          <div class="swatch-row"><div class="swatch" style="background:#fff"></div><div class="swatch" style="background:#111"></div><div class="swatch" style="background:#c62828"></div></div>
        </div>
        <div class="product-info">
          <div class="brand-name">Nike</div>
          <div class="product-name">Men's Air Max 270 React Running Shoes</div>
          <div class="price-row"><span class="price price-sale">$104.99</span><span class="price-orig">$149.99</span></div>
          <div class="stars">★★★★★<span class="star-count">(128)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=400&q=80" alt="Adidas">
          <div class="swatch-row"><div class="swatch" style="background:#e53935"></div><div class="swatch" style="background:#fff"></div></div>
        </div>
        <div class="product-info">
          <div class="brand-name">Adidas</div>
          <div class="product-name">Women's Adidas Barreda Mary Jane Sneakers</div>
          <div class="price-row"><span class="price">$89.99</span></div>
          <div class="stars">★★★★½<span class="star-count">(94)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=400&q=80" alt="New Balance">
          <span class="badge sale">-15%</span>
        </div>
        <div class="product-info">
          <div class="brand-name">New Balance</div>
          <div class="product-name">Girls' New Balance Big Kid 408 Sneakers</div>
          <div class="price-row"><span class="price price-sale">$49.99</span><span class="price-orig">$59.99</span></div>
          <div class="stars">★★★★★<span class="star-count">(211)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400&q=80" alt="Running">
          <div class="swatch-row"><div class="swatch" style="background:#fff"></div><div class="swatch" style="background:#1565c0"></div><div class="swatch" style="background:#000"></div></div>
        </div>
        <div class="product-info">
          <div class="brand-name">ASICS</div>
          <div class="product-name">Men's ASICS Gel-Pulse 17 Running Shoes</div>
          <div class="price-row"><span class="price">$104.99</span></div>
          <div class="stars">★★★★☆<span class="star-count">(76)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1584735175315-9d5df23be620?w=400&q=80" alt="Boot">
          <span class="badge sale">-20%</span>
        </div>
        <div class="product-info">
          <div class="brand-name">Timberland</div>
          <div class="product-name">Men's Classic 6" Premium Waterproof Boot</div>
          <div class="price-row"><span class="price price-sale">$175.99</span><span class="price-orig">$219.99</span></div>
          <div class="stars">★★★★★<span class="star-count">(302)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1543508282-6319a3e2621f?w=400&q=80" alt="Vans">
          <div class="swatch-row"><div class="swatch" style="background:#111"></div><div class="swatch" style="background:#fff"></div><div class="swatch" style="background:#6a1b9a"></div></div>
        </div>
        <div class="product-info">
          <div class="brand-name">Vans</div>
          <div class="product-name">Unisex Old Skool Classic Skate Shoes</div>
          <div class="price-row"><span class="price">$64.99</span></div>
          <div class="stars">★★★★★<span class="star-count">(524)</span></div>
        </div>
      </div>
    </div>
    <button class="scroll-arrow right" onclick="scrollRow('best',1)">›</button>
  </div>
</section>

<!-- PROMO BANNER 1: SHOE PERKS -->
<div class="promo-banner" id="promoBanner1">
  <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1200&q=80" alt="Perks promo">
  <div class="promo-content">
    <div class="promo-left">
      <div class="promo-eyebrow">Shoe Perks · Members Earn</div>
      <div class="promo-headline"><em>2</em> or <em>3×</em> Points</div>
      <div class="promo-sub">On all Jordan brand. Members earn 2X · Gold Members earn 3X!</div>
    </div>
    <button class="promo-cta">Shop Jordan</button>
  </div>
</div>

<!-- NEW ARRIVALS -->
<section class="section" style="background:var(--light-gray); margin-top:0;">
  <div class="section-head">
    <div class="section-title">New Arrivals</div>
    <a href="#" class="view-all">View All →</a>
  </div>
  <div class="product-scroll-wrap">
    <button class="scroll-arrow left" onclick="scrollRow('newarr',-1)">‹</button>
    <div class="product-scroll" id="newarr">
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=400&q=80" alt="Nike Running">
          <span class="badge new">New</span>
        </div>
        <div class="product-info">
          <div class="brand-name">Nike</div>
          <div class="product-name">Women's Nike Pegasus 41 Road Running Shoes</div>
          <div class="price-row"><span class="price">$139.99</span></div>
          <div class="stars">★★★★★<span class="star-count">(47)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1620188467120-5042ed1eb5da?w=400&q=80" alt="Adidas Boost">
          <span class="badge new">New</span>
          <div class="swatch-row"><div class="swatch" style="background:#fff"></div><div class="swatch" style="background:#111"></div></div>
        </div>
        <div class="product-info">
          <div class="brand-name">Adidas</div>
          <div class="product-name">Men's Adidas Ultraboost 24 Running Shoes</div>
          <div class="price-row"><span class="price">$189.99</span></div>
          <div class="stars">★★★★☆<span class="star-count">(33)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?w=400&q=80" alt="Sneaker">
          <span class="badge new">New</span>
        </div>
        <div class="product-info">
          <div class="brand-name">New Balance</div>
          <div class="product-name">Men's NB Fresh Foam 1080v13 Running</div>
          <div class="price-row"><span class="price">$164.99</span></div>
          <div class="stars">★★★★★<span class="star-count">(61)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&q=80" alt="Reebok">
          <span class="badge new">New</span>
          <div class="swatch-row"><div class="swatch" style="background:#e53935"></div><div class="swatch" style="background:#fff"></div></div>
        </div>
        <div class="product-info">
          <div class="brand-name">Reebok</div>
          <div class="product-name">Men's Reebok Nano X4 Training Shoes</div>
          <div class="price-row"><span class="price">$119.99</span></div>
          <div class="stars">★★★★☆<span class="star-count">(28)</span></div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img-box">
          <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?w=400&q=80" alt="Converse">
          <span class="badge new">New</span>
        </div>
        <div class="product-info">
          <div class="brand-name">Converse</div>
          <div class="product-name">Unisex Chuck Taylor All Star High Top</div>
          <div class="price-row"><span class="price">$59.99</span></div>
          <div class="stars">★★★★★<span class="star-count">(412)</span></div>
        </div>
      </div>
    </div>
    <button class="scroll-arrow right" onclick="scrollRow('newarr',1)">›</button>
  </div>
</section>

<!-- SHOP BY CATEGORY -->
<section class="categories" id="categories">
  <div class="section-head">
    <div class="section-title">Shop by Category</div>
    <a href="#" class="view-all">All Categories →</a>
  </div>
  <div class="cat-grid">
    <div class="cat-card">
      <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80" alt="Women's">
      <div class="cat-label">
        <div class="cat-pill">Women's</div>
        <div class="cat-sub">350+ styles</div>
      </div>
    </div>
    <div class="cat-card">
      <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&q=80" alt="Men's">
      <div class="cat-label">
        <div class="cat-pill">Men's</div>
        <div class="cat-sub">280+ styles</div>
      </div>
    </div>
    <div class="cat-card">
      <img src="https://images.unsplash.com/photo-1503919545889-aef636e10ad4?w=600&q=80" alt="Kids">
      <div class="cat-label">
        <div class="cat-pill">Kids'</div>
        <div class="cat-sub">190+ styles</div>
      </div>
    </div>
    <div class="cat-card">
      <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80" alt="Work">
      <div class="cat-label">
        <div class="cat-pill">Work</div>
        <div class="cat-sub">120+ styles</div>
      </div>
    </div>
  </div>
</section>

<!-- PROMO BANNER 2: DEALS -->
<div class="promo-banner" id="promoBanner2">
  <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=1200&q=80" alt="Deals">
  <div class="promo-content">
    <div class="promo-left">
      <div class="promo-eyebrow">Clearance Event — Today Only</div>
      <div class="promo-headline">Up to <em>50%</em> Off</div>
      <div class="promo-sub">Shop hundreds of marked-down styles. While supplies last.</div>
    </div>
    <button class="promo-cta">Shop Deals</button>
  </div>
</div>

<!-- BRANDS MARQUEE -->
<section class="brands-strip">
  <div class="brands-heading">Trusted Brands We Carry</div>
  <div class="brands-marquee-outer">
    <div class="brands-track" id="brandsTrack">
      <!-- Set 1 -->
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Nike"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Adidas"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="New Balance"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Puma"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Vans"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Converse"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Reebok"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Skechers"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="ASICS"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Timberland"></div>
      <!-- Set 2 (duplicate for seamless loop) -->
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Nike"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Adidas"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="New Balance"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Puma"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Vans"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Converse"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Reebok"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Skechers"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="ASICS"></div>
      <div class="brand-logo-box"><img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/large/s3/sumpiuhan_logo_hitam.png?eWrL7dzZVU2l81ng6kWq1czHZX6r9PAK&itok=XYmC5Hou" alt="Timberland"></div>
    </div>
  </div>
</section>

<!-- TRUST BAR -->
<div class="trust-bar">
  <div class="trust-item"><div class="trust-icon">📦</div><div class="trust-text"><strong>Free Shipping</strong><span>On orders over $75</span></div></div>
  <div class="trust-item"><div class="trust-icon">↩</div><div class="trust-text"><strong>30-Day Returns</strong><span>Easy, hassle-free returns</span></div></div>
  <div class="trust-item"><div class="trust-icon">📏</div><div class="trust-text"><strong>Size Guide</strong><span>Find your perfect fit</span></div></div>
  <div class="trust-item"><div class="trust-icon">🔒</div><div class="trust-text"><strong>Secure Payment</strong><span>256-bit SSL encryption</span></div></div>
</div>

<!-- NEWSLETTER -->
<section class="newsletter">
  <div class="newsletter-title">Get 15% Off Your First Order</div>
  <p class="newsletter-sub">Weekly deals, new drops & exclusive Shoe Perks member offers.</p>
  <div class="newsletter-form">
    <input type="email" placeholder="Enter your email address…">
    <button type="button">Subscribe</button>
  </div>
  <p class="newsletter-note">No spam. Unsubscribe anytime.</p>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div>
      <span class="footer-logo">SHOE <span>CARNIVAL</span></span>
      <p class="footer-desc">Premium footwear for every stride. Shop the latest styles from your favorite brands.</p>
      <div class="footer-socials">
        <div class="social-btn">IG</div><div class="social-btn">FB</div>
        <div class="social-btn">TK</div><div class="social-btn">YT</div>
        <div class="social-btn">X</div>
      </div>
    </div>
    <div class="footer-col">
      <h5>Shop</h5>
      <ul>
        <li><a href="#">Women's Shoes</a></li>
        <li><a href="#">Men's Shoes</a></li>
        <li><a href="#">Kids' Shoes</a></li>
        <li><a href="#">Accessories</a></li>
        <li><a href="#">Sale</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Help</h5>
      <ul>
        <li><a href="#">Track My Order</a></li>
        <li><a href="#">Returns & Exchanges</a></li>
        <li><a href="#">Size Guide</a></li>
        <li><a href="#">FAQ</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Company</h5>
      <ul>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Store Locator</a></li>
        <li><a href="#">Shoe Perks</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Press</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span class="footer-copy">© 2025 Shoe Carnival, Inc. All rights reserved.</span>
    <div class="pay-icons">
      <span class="pay-icon">VISA</span><span class="pay-icon">MC</span>
      <span class="pay-icon">AMEX</span><span class="pay-icon">PAYPAL</span>
      <span class="pay-icon">APPLE PAY</span>
    </div>
  </div>
</footer>

<script>
  gsap.registerPlugin(ScrollTrigger);

  // Hero entrance
  gsap.to('#heroContent', { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', delay: 0.3 });

  // Scroll-triggered fades
  ['#categories','#promoBanner1','#promoBanner2','.brands-strip','.trust-bar','.newsletter'].forEach(sel => {
    gsap.from(sel, {
      scrollTrigger: { trigger: sel, start: 'top 88%' },
      opacity: 0, y: 35, duration: 0.7, ease: 'power2.out'
    });
  });

  // Product rows stagger
  document.querySelectorAll('.product-scroll').forEach(row => {
    gsap.from(row.querySelectorAll('.product-card'), {
      scrollTrigger: { trigger: row, start: 'top 88%' },
      opacity: 0, y: 28, stagger: 0.07, duration: 0.55, ease: 'power2.out'
    });
  });

  // Category cards
  gsap.from('.cat-card', {
    scrollTrigger: { trigger: '.cat-grid', start: 'top 88%' },
    opacity: 0, y: 35, stagger: 0.09, duration: 0.6, ease: 'power2.out'
  });

  // Brand logos
  gsap.from('.brand-logo-box', {
    scrollTrigger: { trigger: '.brands-row', start: 'top 92%' },
    opacity: 0, y: 18, stagger: 0.06, duration: 0.45, ease: 'power2.out'
  });

  // Trust items
  gsap.from('.trust-item', {
    scrollTrigger: { trigger: '.trust-bar', start: 'top 92%' },
    opacity: 0, x: -18, stagger: 0.09, duration: 0.45, ease: 'power2.out'
  });

  // Horizontal scroll arrows
  function scrollRow(id, dir) {
    const el = document.getElementById(id);
    const card = el.querySelector('.product-card');
    const w = card ? card.offsetWidth + 1 : 240;
    gsap.to(el, { scrollLeft: el.scrollLeft + dir * w * 2, duration: 0.45, ease: 'power2.inOut' });
  }
  window.scrollRow = scrollRow;
</script>
</body>
</html>