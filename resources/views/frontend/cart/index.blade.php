@extends('frontend.layouts.app')

@section('title', __('file.shopping_cart'))
@section('body_class', 'light-page')

@section('content')
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .cart-page-wrap {
            background: #f7f4ef;
            min-height: 50vh;
        }

        /* ─── HEADER ──────────────────────────────────────────────── */
        .cart-header-wrap {
            background: #1a1a1a;
            color: #f5f0e8;
            padding: 1.25rem 2rem;
            text-align: center;
            border-bottom: 1px solid #2a2a2a;
        }

        .cart-header-wrap h1 {
            font-family: var(--font-display, Georgia, serif);
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 400;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #f5f0e8;
            margin-bottom: 0.2rem;
        }

        .cart-header-wrap p {
            font-size: 0.68rem;
            color: rgba(245, 240, 232, 0.6);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin: 0;
        }

        /* ─── LAYOUT ──────────────────────────────────────────────── */
        .cart-container {
            max-width: 1200px;
            margin: 1.5rem auto 3rem;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 2.5rem;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .cart-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
        }

        /* ─── SECTION LABEL ───────────────────────────────────────── */
        .section-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #000000;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2dcd4;
            font-weight: 700;
        }

        /* ─── ALERTS ──────────────────────────────────────────────── */
        .cart-alert {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 3px solid;
            font-size: 0.875rem;
        }

        .cart-alert-success {
            background: rgba(46, 204, 113, 0.08);
            color: #27ae60;
            border-color: #27ae60;
        }

        .cart-alert-error {
            background: rgba(231, 76, 60, 0.08);
            color: #c0392b;
            border-color: #c0392b;
        }

        /* ─── CART ITEMS ──────────────────────────────────────────── */
        .cart-items {
            display: flex;
            flex-direction: column;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 1.25rem;
            align-items: center;
            padding: 1.25rem 0;
            border-bottom: 1px solid #e2dcd4;
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .cart-item:first-child {
            padding-top: 0.5rem;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        @media (max-width: 576px) {
            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 1rem;
            }

            .cart-item-actions {
                grid-column: 1 / -1;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }

        .cart-item-img-wrap {
            width: 80px;
            height: 100px;
            flex-shrink: 0;
            overflow: hidden;
            background: #e2dcd4;
        }

        @media (max-width: 576px) {
            .cart-item-img-wrap {
                width: 80px;
                height: 102px;
            }
        }

        .cart-item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .cart-item-img-wrap:hover .cart-item-img {
            transform: scale(1.04);
        }

        .cart-item-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7a7068;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .cart-item-details {
            min-width: 0;
        }

        .cart-item-brand {
            font-size: var(--fs-label);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #555555;
            margin-bottom: 0.2rem;
        }

        .cart-item-details h3 {
            font-family: var(--font-display, Georgia, serif);
            font-size: var(--fs-cart-item);
            font-weight: 500;
            color: #000000;
            margin-bottom: 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-item-meta {
            font-size: 0.75rem;
            color: #000000;
            line-height: 1.5;
            margin: 0;
        }

        .cart-item-price {
            font-size: var(--fs-cart-price);
            font-weight: 700;
            color: #000000;
            margin-top: 0.5rem;
        }

        /* ─── ITEM ACTIONS ────────────────────────────────────────── */
        .cart-item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.85rem;
        }

        .qty-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid #e2dcd4;
            background: #ffffff;
        }

        .qty-btn {
            background: none;
            border: none;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            color: #000000;
            transition: background 0.15s;
            line-height: 1;
        }

        .qty-btn:hover {
            background: #e2dcd4;
        }

        .qty-input {
            width: 36px;
            height: 28px;
            border: none;
            border-left: 1px solid #e2dcd4;
            border-right: 1px solid #e2dcd4;
            background: #ffffff;
            text-align: center;
            font-family: var(--font-body, sans-serif);
            font-size: 0.875rem;
            color: #1a1a1a;
            -moz-appearance: textfield;
            outline: none;
        }

        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #000000;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            text-decoration: underline;
            transition: color 0.2s;
            padding: 0;
            font-weight: 600;
        }

        .remove-btn:hover {
            color: #b85c38;
        }

        /* ─── ORDER SUMMARY ───────────────────────────────────────── */
        .cart-summary {
            background: #ffffff;
            border: 1px solid #e2dcd4;
            position: sticky;
            top: 100px;
            overflow: hidden;
        }

        .summary-header {
            background: #1a1a1a;
            padding: 1.25rem 1.75rem;
        }

        .summary-header h2 {
            font-family: var(--font-display, Georgia, serif);
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: #f5f0e8;
            margin: 0;
        }

        .summary-body {
            padding: 1.25rem 1.5rem;
            background: #ffffff;
        }

        /* ─── SUMMARY ROWS ────────────────────────────────────────── */
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.65rem 0;
            font-size: var(--fs-label);
            border-bottom: 1px solid #e2dcd4;
        }

        .summary-row:last-of-type {
            border-bottom: none;
        }

        .summary-row .s-label {
            color: #000000;
        }

        .summary-row .s-value {
            color: #000000;
            font-weight: 600;
        }

        .summary-row .s-muted {
            color: #555555;
            font-size: 0.75rem;
            font-weight: 400;
        }

        /* ─── PROMO CODE ──────────────────────────────────────────── */
        .promo-section {
            margin: 1.25rem 0;
        }

        .promo-label {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #000000;
            margin-bottom: 0.4rem;
            font-weight: 700;
        }

        .promo-row {
            display: flex;
        }

        .promo-input {
            flex: 1;
            padding: 0.55rem 0.75rem;
            border: 1px solid #000000;
            border-right: none;
            background: #f7f4ef;
            font-family: var(--font-body, sans-serif);
            font-size: 0.75rem;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            outline: none;
            transition: border-color 0.2s;
        }

        .promo-input::placeholder {
            text-transform: none;
            color: #a09890;
            letter-spacing: 0;
        }

        .promo-input:focus {
            border-color: #7a7068;
        }

        .promo-apply-btn {
            padding: 0.55rem 1rem;
            background: #000000;
            border: 1px solid #000000;
            font-family: var(--font-body, sans-serif);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            color: #ffffff;
            transition: background 0.2s, opacity 0.2s;
            white-space: nowrap;
        }

        .promo-apply-btn:hover {
            opacity: 0.85;
        }

        /* ─── TOTAL ───────────────────────────────────────────────── */
        .summary-divider {
            height: 1px;
            background: #e2dcd4;
            margin: 1rem 0;
        }

        .summary-total-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 0.5rem 0 1.5rem;
        }

        .summary-total-label {
            font-size: var(--fs-label);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #000000;
            font-weight: 700;
        }

        .summary-total-amount {
            font-family: var(--font-display, Georgia, serif);
            font-size: var(--fs-cart-total);
            font-weight: 700;
            color: #000000;
            line-height: 1;
        }

        /* ─── BUTTONS ─────────────────────────────────────────────── */
        .checkout-btn {
            display: block;
            width: 100%;
            background: #000000;
            color: #ffffff !important;
            text-align: center;
            padding: 0.85rem;
            font-family: var(--font-body, sans-serif);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: opacity 0.25s;
            text-decoration: none !important;
            margin-bottom: 0.75rem;
        }

        .checkout-btn:hover {
            opacity: 0.85;
        }

        .continue-shopping {
            display: block;
            text-align: center;
            font-size: 0.7rem;
            color: #000000 !important;
            text-decoration: underline !important;
            transition: opacity 0.2s;
            cursor: pointer;
            font-weight: 600;
        }

        .continue-shopping:hover {
            opacity: 0.7;
        }

        /* ─── TRUST SECTION ───────────────────────────────────────── */
        .trust-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2dcd4;
        }

        .secure-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.65rem;
            color: #000000;
            letter-spacing: 0.04em;
            margin-bottom: 0.75rem;
            font-weight: 500;
        }

        .secure-note svg {
            opacity: 0.45;
            flex-shrink: 0;
        }

        .payment-badges {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .payment-badge {
            font-size: 0.62rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #7a7068;
            border: 1px solid #e2dcd4;
            padding: 0.28rem 0.6rem;
            background: #f7f4ef;
        }

        /* ─── EMPTY STATE ─────────────────────────────────────────── */
        .empty-cart {
            text-align: center;
            padding: 5rem 0;
            grid-column: 1 / -1;
        }

        .empty-cart-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            opacity: 0.2;
        }

        .empty-cart h2 {
            font-family: var(--font-display, Georgia, serif);
            font-size: 2rem;
            font-weight: 300;
            color: #1a1a1a;
            margin-bottom: 0.75rem;
        }

        .empty-cart p {
            color: #7a7068;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .btn-primary {
            display: inline-block;
            background: #1a1a1a;
            color: #ffffff !important;
            padding: 0.875rem 2.5rem;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            transition: background 0.3s;
            text-decoration: none !important;
        }

        .btn-primary:hover {
            background: #b85c38;
        }

        @media (max-width: 768px) {
            .cart-summary {
                position: static;
            }

            .summary-total-amount {
                font-size: 1.4rem;
            }
        }
    </style>

    <div class="cart-page-wrap">

        {{-- ─── HEADER ─────────────────────────────────────────────── --}}
        <div class="cart-header-wrap">
            <h1>{{ __('file.cart') }}</h1>
            <p>
                <span class="cart-item-count">{{ $cartItems->count() }}</span>
                item{{ $cartItems->count() !== 1 ? 's' : '' }}
            </p>
        </div>

        {{-- ─── MAIN CONTAINER ──────────────────────────────────────── --}}
        <div class="cart-container">

            @if(session('success'))
                <div class="cart-alert cart-alert-success" style="grid-column: 1 / -1;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="cart-alert cart-alert-error" style="grid-column: 1 / -1;">
                    {{ session('error') }}
                </div>
            @endif

            @if($cartItems->count() > 0)

                {{-- ─── LEFT: CART ITEMS ───────────────────────────────── --}}
                <div>
                    <p class="section-label">{{ __('file.cart_items') }}</p>
                    <div class="cart-items">
                        @foreach($cartItems as $item)
                            <div class="cart-item cart-item-row" data-id="{{ $item->id }}">

                                {{-- Image --}}
                                <div class="cart-item-img-wrap">
                                    @if(isset($item->attributes['image']) && $item->attributes['image'])
                                        <img src="{{ asset('storage/' . $item->attributes['image']) }}" alt="{{ $item->name }}"
                                            class="cart-item-img" loading="lazy">
                                    @else
                                        <div class="cart-item-img-placeholder">{{ __('file.no_image') }}</div>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="cart-item-details">
                                    @if(isset($item->attributes['brand']) && $item->attributes['brand'])
                                        <p class="cart-item-brand">{{ $item->attributes['brand'] }}</p>
                                    @endif
                                    <h3 title="{{ $item->name }}">{{ $item->name }}</h3>
                                    <p class="cart-item-meta">
                                        @if(isset($item->attributes['size']) && $item->attributes['size'])
                                            {{ __('file.size') }}: {{ $item->attributes['size'] }}
                                        @endif
                                        @if(isset($item->attributes['size']) && $item->attributes['size'] && isset($item->attributes['color']) && $item->attributes['color'])
                                            &nbsp;·&nbsp;
                                        @endif
                                        @if(isset($item->attributes['color']) && $item->attributes['color'])
                                            {{ __('file.color') }}: {{ $item->attributes['color'] }}
                                        @endif
                                    </p>
                                    <div class="cart-item-price">
                                        @price($item->getPriceSumWithConditions())
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="cart-item-actions">
                                    <form action="{{ route('cart.update') }}" method="POST" class="cart-update-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="rowId" value="{{ $item->id }}">
                                        <div class="qty-control">
                                            <button type="button" class="qty-btn" onclick="updateQty(this, -1)"
                                                aria-label="Decrease">−</button>
                                            <input type="number" name="quantity" class="qty-input" value="{{ $item->quantity }}"
                                                min="1" aria-label="Quantity" onchange="submitUpdate(this.form)">
                                            <button type="button" class="qty-btn" onclick="updateQty(this, 1)"
                                                aria-label="Increase">+</button>
                                        </div>
                                    </form>

                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="cart-remove-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-btn">{{ __('file.remove') }}</button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ─── RIGHT: ORDER SUMMARY ────────────────────────────── --}}
                <div class="cart-summary">

                    <div class="summary-header">
                        <h2>{{ __('file.order_summary') }}</h2>
                    </div>

                    <div class="summary-body">

                        {{-- Line rows --}}
                        <div>
                            <div class="summary-row">
                                <span class="s-label">{{ __('file.subtotal') }} ({{ $cartItems->sum('quantity') }}
                                     {{ $cartItems->sum('quantity') !== 1 ? __('file.items') : __('file.item') }})</span>
                                <span class="s-value cart-subtotal">@price($subtotal)</span>
                            </div>

                            @if($autoDiscount > 0 && $bestRule)
                                <div class="summary-row" id="auto-discount-row">
                                    <span class="s-label" style="color:#16a34a;">
                                        🏷 {{ $bestRule->name }}
                                    </span>
                                    <span class="s-value" style="color:#16a34a;">−@price($autoDiscount)</span>
                                </div>
                            @endif

                            <div class="summary-row" id="coupon-discount-row" style="{{ $couponDiscount > 0 ? '' : 'display:none;' }}">
                                <span class="s-label" style="color:#16a34a;">
                                    🎟 <span id="applied-coupon-code">{{ $appliedCoupon['code'] ?? '' }}</span>
                                    <button type="button" onclick="removePromo()" style="margin-left:6px;font-size:0.65rem;color:#b85c38;text-decoration:underline;background:none;border:none;cursor:pointer;" title="Remove coupon">✕ Remove</button>
                                </span>
                                <span class="s-value" style="color:#16a34a;">−<span id="coupon-discount-display">@price($couponDiscount)</span></span>
                            </div>

                            <div class="summary-row">
                                <span class="s-label">{{ __('file.shipping') }}</span>
                                <span class="s-muted">{{ __('file.calculated_at_checkout') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="s-label">{{ __('file.tax') }}</span>
                                <span class="s-muted">{{ __('file.included') }}</span>
                            </div>
                        </div>

                        {{-- Promo Code --}}
                        <div class="promo-section" id="promo-input-section" style="{{ $couponDiscount > 0 ? 'display:none;' : '' }}">
                            <span class="promo-label">{{ __('file.promo_code') }}</span>
                            <div class="promo-row">
                                <input type="text" class="promo-input" id="promo-input" placeholder="{{ __('file.enter_code') }}"
                                    autocomplete="off">
                                <button class="promo-apply-btn" type="button" onclick="applyPromo()">{{ __('file.apply') }}</button>
                            </div>
                            <div id="promo-message" style="font-size:0.75rem; margin-top:0.5rem; display:none;"></div>
                        </div>

                        <div class="summary-divider"></div>

                        {{-- Total --}}
                        <div class="summary-total-row">
                            <span class="summary-total-label">{{ __('file.total') }}</span>
                            <span class="summary-total-amount cart-total">
                                @price($total)
                            </span>
                        </div>

                        {{-- CTA --}}
                        <a href="{{ route('checkout.index') }}" class="checkout-btn">
                            {{ __('file.proceed_to_checkout') }}
                        </a>
                        <a href="{{ route('frontend.products.index') }}" class="continue-shopping">
                            {{ __('file.continue_shopping') }}
                        </a>

                        {{-- Trust signals --}}
                        <div class="trust-section">
                            <div class="secure-note">
                                {!! __('file.secure_checkout_ssl') !!}
                            </div>
                            <div class="payment-badges">
                                <span class="payment-badge">Visa</span>
                                <span class="payment-badge">Mastercard</span>
                                <span class="payment-badge">Amex</span>
                                <span class="payment-badge">PayPal</span>
                            </div>
                        </div>

                    </div>
                </div>

            @else

                {{-- ─── EMPTY STATE ─────────────────────────────────────── --}}
                <div class="empty-cart">
                    <svg class="empty-cart-icon" viewBox="0 0 64 64" fill="none" stroke="#1a1a1a" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 8h6l4 28h28l4-20H18" />
                        <circle cx="24" cy="52" r="3" />
                        <circle cx="44" cy="52" r="3" />
                    </svg>
                    <h2>{{ __('file.your_cart_is_empty') }}</h2>
                    <p>{{ __('file.looks_like_you_havent_added_anything_yet') }}</p>
                    <a href="{{ route('frontend.products.index') }}" class="btn-primary">{{ __('file.explore_collection') }}</a>
                </div>

            @endif

        </div>{{-- .cart-container --}}

    </div>{{-- .cart-page-wrap --}}

    <script>
        /* ─── QTY UPDATE ──────────────────────────────────────────── */
        function updateQty(btn, delta) {
            const input = btn.parentNode.querySelector('input[type=number]');
            const newVal = parseInt(input.value) + delta;
            if (newVal >= 1) {
                input.value = newVal;
                submitUpdate(input.closest('form'));
            }
        }

        function submitUpdate(form) {
            const formData = new FormData(form);
            const rowId = formData.get('rowId');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`.cart-item-row[data-id="${rowId}"]`);
                        if (row) row.querySelector('.item-subtotal').textContent = data.itemSubtotal;
                        updateOrderTotals(data);
                    } else {
                        alert(data.message || 'Error updating cart');
                        window.location.reload();
                    }
                })
                .catch(() => window.location.reload());
        }

        /* ─── TOTALS UPDATE ───────────────────────────────────────── */
        function updateOrderTotals(data) {
            document.querySelectorAll('.cart-subtotal').forEach(el => el.textContent = data.cartSubtotal);
            document.querySelectorAll('.cart-total').forEach(el => el.textContent = data.cartTotal);

            const badge = document.getElementById('navCartCount');
            if (badge) badge.textContent = data.cartCount;

            const headerCount = document.querySelector('.cart-item-count');
            if (headerCount) headerCount.textContent = data.cartCount;

            if (parseInt(data.cartCount) === 0) window.location.reload();
        }

        /* ─── REMOVE ITEM ─────────────────────────────────────────── */
        document.querySelectorAll('.cart-remove-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const row = this.closest('.cart-item-row');

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-16px)';
                            setTimeout(() => {
                                row.remove();
                                updateOrderTotals(data);
                            }, 400);
                        }
                    })
                    .catch(err => console.error(err));
            });
        });

        /* ─── PROMO CODE ──────────────────────────────────────────── */
        function applyPromo() {
            const input = document.getElementById('promo-input');
            const msg   = document.getElementById('promo-message');
            const code  = input.value.trim().toUpperCase();

            if (!code) {
                msg.style.display = 'block';
                msg.style.color   = '#b85c38';
                msg.textContent   = 'Please enter a promo code.';
                return;
            }

            const btn = document.querySelector('.promo-apply-btn');
            btn.disabled    = true;
            btn.textContent = '...';

            fetch('{{ route("cart.promo.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept':       'application/json'
                },
                body: JSON.stringify({ code })
            })
            .then(res => res.json())
            .then(data => {
                msg.style.display = 'block';
                if (data.success) {
                    msg.style.color = '#27ae60';
                    msg.textContent = data.message || 'Coupon applied!';

                    // Show discount row
                    const discountRow = document.getElementById('coupon-discount-row');
                    if (discountRow) {
                        document.getElementById('applied-coupon-code').textContent = data.code;
                        document.getElementById('coupon-discount-display').textContent = data.discount;
                        discountRow.style.display = '';
                    }

                    // Update total
                    document.querySelectorAll('.cart-total').forEach(el => el.textContent = data.cartTotal);

                    // Hide input section
                    const promoSection = document.getElementById('promo-input-section');
                    if (promoSection) promoSection.style.display = 'none';

                } else {
                    msg.style.color = '#b85c38';
                    msg.textContent = data.message || 'Invalid promo code.';
                }
            })
            .catch(() => {
                msg.style.display = 'block';
                msg.style.color   = '#b85c38';
                msg.textContent   = 'Something went wrong. Please try again.';
            })
            .finally(() => {
                btn.disabled    = false;
                btn.textContent = '{{ __('file.apply') }}';
            });
        }

        function removePromo() {
            fetch('{{ route("cart.promo.remove") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept':       'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Hide discount row
                    const discountRow = document.getElementById('coupon-discount-row');
                    if (discountRow) discountRow.style.display = 'none';

                    // Show input section again
                    const promoSection = document.getElementById('promo-input-section');
                    if (promoSection) promoSection.style.display = '';

                    // Clear input & message
                    const input = document.getElementById('promo-input');
                    if (input) input.value = '';
                    const msg = document.getElementById('promo-message');
                    if (msg) { msg.style.display = 'none'; msg.textContent = ''; }

                    // Update total
                    document.querySelectorAll('.cart-total').forEach(el => el.textContent = data.cartTotal);
                }
            })
            .catch(err => console.error(err));
        }

        document.getElementById('promo-input')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); applyPromo(); }
        });
    </script>
@endsection
