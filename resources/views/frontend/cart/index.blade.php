@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<style>
    /* ─── CART HEADER ─────────────────────────────────────────── */
    .cart-header-wrap {
        background: var(--ink);
        color: var(--cream);
        padding: 6rem 2rem 4rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cart-header-wrap h1 {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 300;
        letter-spacing: 0.02em;
        margin-bottom: 0.5rem;
    }
    .cart-header-wrap p {
        font-size: 0.875rem;
        color: rgba(245, 240, 232, 0.6);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    /* ─── CART CONTENT ────────────────────────────────────────── */
    .cart-container {
        max-width: 1200px;
        margin: 4rem auto 6rem;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 4rem;
    }

    @media (max-width: 992px) {
        .cart-container {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
    }

    /* ─── CART ITEMS LIST ─────────────────────────────────────── */
    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: 2rem;
        align-items: center;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--sand);
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
            margin-top: 1rem;
        }
    }

    .cart-item-img {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        background: var(--sand);
    }

    .cart-item-details h3 {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-weight: 400;
        color: var(--ink);
        margin-bottom: 0.5rem;
    }

    .cart-item-details p {
        font-size: 0.875rem;
        color: var(--mink);
        margin-bottom: 0.25rem;
    }
    
    .cart-item-price {
        font-size: 1rem;
        font-weight: 600;
        color: var(--ink);
        margin-top: 0.75rem;
    }

    /* ─── QUANTITY CONTROL ────────────────────────────────────── */
    .qty-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid var(--sand);
        border-radius: 4px;
        overflow: hidden;
    }

    .qty-btn {
        background: var(--cream);
        border: none;
        width: 32px;
        height: 32px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        color: var(--ink);
        transition: background 0.2s;
    }

    .qty-btn:hover {
        background: var(--sand);
    }

    .qty-input {
        width: 40px;
        height: 32px;
        border: none;
        background: transparent;
        text-align: center;
        font-family: var(--font-body);
        font-size: 0.875rem;
        color: var(--ink);
        -moz-appearance: textfield;
    }

    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .remove-btn {
        background: none;
        border: none;
        color: var(--rust);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        margin-left: 1rem;
        text-decoration: underline;
    }

    .remove-btn:hover {
        color: var(--ink);
    }

    /* ─── CART SUMMARY ────────────────────────────────────────── */
    .cart-summary {
        background: var(--white);
        padding: 2.5rem;
        border: 1px solid var(--sand);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .cart-summary h2 {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 400;
        color: var(--ink);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--sand);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1rem;
        color: var(--mink);
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--sand);
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ink);
    }

    .checkout-btn {
        display: block;
        width: 100%;
        background: var(--ink);
        color: var(--white);
        text-align: center;
        padding: 1rem;
        margin-top: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: background 0.3s;
    }

    .checkout-btn:hover {
        background: var(--rust);
    }

    .continue-shopping {
        display: block;
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.875rem;
        color: var(--mink);
        text-decoration: underline;
        transition: color 0.2s;
    }

    .continue-shopping:hover {
        color: var(--ink);
    }

    .empty-cart {
        text-align: center;
        padding: 4rem 0;
        grid-column: 1 / -1;
    }

    .empty-cart h2 {
        font-family: var(--font-display);
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .empty-cart p {
        color: var(--mink);
        margin-bottom: 2rem;
    }

    .btn-primary {
        display: inline-block;
        background: var(--ink);
        color: var(--white);
        padding: 0.875rem 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: var(--rust);
    }
</style>

<div class="cart-header-wrap">
    <h1>Your Bag</h1>
    <p>{{ $cartItems->count() }} item(s)</p>
</div>

<div class="cart-container">
    @if(session('success'))
        <div style="background: rgba(46, 204, 113, 0.1); color: #27ae60; padding: 1rem; margin-bottom: 2rem; border-left: 4px solid #27ae60; grid-column: 1 / -1;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(231, 76, 60, 0.1); color: #c0392b; padding: 1rem; margin-bottom: 2rem; border-left: 4px solid #c0392b; grid-column: 1 / -1;">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->count() > 0)
        <!-- Cart Items List -->
        <div class="cart-items">
            @foreach($cartItems as $item)
                <div class="cart-item">
                    <!-- Image -->
                    <div class="cart-item-img-wrap">
                        @if(isset($item->attributes['image']) && $item->attributes['image'])
                            <img src="{{ asset('storage/' . $item->attributes['image']) }}" alt="{{ $item->name }}" class="cart-item-img">
                        @else
                            <div class="cart-item-img" style="display:flex; align-items:center; justify-content:center; color: var(--mink); font-size: 0.75rem;">No Image</div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="cart-item-details">
                        <h3>{{ $item->name }}</h3>
                        
                        @if(isset($item->attributes['size']) && $item->attributes['size'])
                            <p>Size: {{ $item->attributes['size'] }}</p>
                        @endif
                        
                        @if(isset($item->attributes['color']) && $item->attributes['color'])
                            <p>Color: {{ $item->attributes['color'] }}</p>
                        @endif
                        
                        <div class="cart-item-price">Rs. {{ number_format($item->price, 2) }}</div>
                    </div>

                    <!-- Actions -->
                    <div class="cart-item-actions">
                        <form action="{{ route('cart.update') }}" method="POST" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="rowId" value="{{ $item->id }}">
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.form.submit();">-</button>
                                <input type="number" name="quantity" class="qty-input" value="{{ $item->quantity }}" min="1">
                                <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.form.submit();">+</button>
                            </div>
                        </form>

                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="cart-summary">
            <h2>Order Summary</h2>
            
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rs. {{ number_format($subtotal, 2) }}</span>
            </div>
            
            <div class="summary-row">
                <span>Shipping</span>
                <span>Calculated at checkout</span>
            </div>
            
            <div class="summary-row">
                <span>Tax</span>
                <span>Included</span>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>

            <a href="#" class="checkout-btn">Proceed to Checkout</a>
            <a href="{{ route('frontend.products.index') }}" class="continue-shopping">Continue Shopping</a>
        </div>
    @else
        <div class="empty-cart">
            <h2>Your bag is empty</h2>
            <p>Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('frontend.products.index') }}" class="btn-primary">Shop Now</a>
        </div>
    @endif
</div>
@endsection

