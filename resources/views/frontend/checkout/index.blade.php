@extends('frontend.layouts.app')

@section('title', __('file.checkout'))
@section('body_class', 'light-page')

@section('content')
<style>
    *, *::before, *::after { box-sizing: border-box; }
    
    .checkout-wrap {
        background: #f7f4ef;
        min-height: 80vh;
        padding-bottom: 4rem;
    }

    .checkout-header {
        background: #1a1a1a;
        color: #f5f0e8;
        padding: 1.25rem 2rem;
        text-align: center;
        border-bottom: 1px solid #2a2a2a;
    }

    .checkout-header h1 {
        font-family: var(--font-display, Georgia, serif);
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 400;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 0.2rem;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 3rem;
        align-items: start;
    }

    @media (max-width: 1024px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }

    .section-title {
        font-family: var(--font-display, Georgia, serif);
        font-size: 1.2rem;
        font-weight: 500;
        margin-bottom: 1rem;
        color: #1a1a1a;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e2dcd4;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.4rem;
        color: #1a1a1a;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2dcd4;
        background: #ffffff;
        font-family: var(--font-body, sans-serif);
        font-size: 0.875rem;
        color: #1a1a1a;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: #1a1a1a;
        outline: none;
    }

    .shipping-method-card, .payment-method-card {
        border: 1px solid #e2dcd4;
        background: #ffffff;
        padding: 1rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .shipping-method-card:hover, .payment-method-card:hover {
        border-color: #1a1a1a;
    }

    .method-radio {
        margin-right: 1rem;
    }

    .method-details {
        flex: 1;
    }

    .method-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1a1a1a;
    }

    .method-desc {
        font-size: 0.75rem;
        color: #7a7068;
        margin-top: 0.25rem;
    }

    .method-price {
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Summary */
    .checkout-summary {
        background: #ffffff;
        border: 1px solid #e2dcd4;
        padding: 1.5rem;
        position: sticky;
        top: 100px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        border-bottom: 1px solid #f7f4ef;
        padding-bottom: 1rem;
    }

    .summary-item-img {
        width: 60px;
        height: 75px;
        object-fit: cover;
        background: #f7f4ef;
        margin-right: 1rem;
    }

    .summary-item-info {
        flex: 1;
    }

    .summary-item-title {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .summary-item-meta {
        font-size: 0.75rem;
        color: #7a7068;
    }

    .summary-totals {
        margin-top: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        color: #1a1a1a;
    }

    .summary-total {
        font-family: var(--font-display, Georgia, serif);
        font-size: 1.25rem;
        font-weight: 500;
        border-top: 1px solid #e2dcd4;
        padding-top: 1rem;
        margin-top: 0.5rem;
    }

    .btn-submit {
        display: block;
        width: 100%;
        background: #1a1a1a;
        color: #ffffff !important;
        text-align: center;
        padding: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: opacity 0.25s;
        margin-top: 1.5rem;
    }

    .btn-submit:hover {
        opacity: 0.85;
    }
</style>

<div class="checkout-wrap">
    <div class="checkout-header">
        <h1>{{ __('file.secure_checkout') }}</h1>
    </div>

    <div class="checkout-container">
        <!-- Left Column: Forms -->
        <div class="checkout-form-area">
            @if(session('error'))
                <div style="background: #ffebee; color: #c62828; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid #c62828;">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background: #ffebee; color: #c62828; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid #c62828;">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf

                <!-- Contact Info -->
                <h2 class="section-title">{{ __('file.contact_information') }}</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('file.first_name') }}</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->customer->first_name ?? $user->name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('file.last_name') }}</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->customer->last_name ?? '') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('file.email') }}</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('file.phone') }}</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->customer->phone ?? '') }}" required>
                    </div>
                </div>

                <!-- Shipping Address -->
                <h2 class="section-title" style="margin-top: 2rem;">{{ __('file.shipping_address') }}</h2>
                
                @php
                    $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();
                @endphp

                <div class="form-group">
                    <label class="form-label">{{ __('file.address_line_1') }}</label>
                    <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $defaultAddress->address_line1 ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('file.address_line_2_optional') }}</label>
                    <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $defaultAddress->address_line2 ?? '') }}">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('file.city') }}</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $defaultAddress->city ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('file.state_province') }}</label>
                        <input type="text" name="state" class="form-control" value="{{ old('state', $defaultAddress->province ?? '') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('file.postal_code') }}</label>
                        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('file.country') }}</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $defaultAddress->country ?? '') }}" required>
                    </div>
                </div>

                <!-- Shipping Method -->
                <h2 class="section-title" style="margin-top: 2rem;">{{ __('file.shipping_method') }}</h2>
                <div class="shipping-methods-container">
                    @forelse($shippingRates as $index => $rate)
                        <label class="shipping-method-card">
                            <input type="radio" name="shipping_rate_id" value="{{ $rate->id }}" class="method-radio" {{ $index === 0 ? 'checked' : '' }} data-cost="{{ $rate->rate_amount }}" onchange="updateTotal()">
                            <div class="method-details">
                                <div class="method-name">{{ $rate->name ?? __('file.standard_shipping') }}</div>
                                <div class="method-desc">{{ $rate->delivery_time ?? '3-5 ' . __('file.business_days') }} ({{ $rate->zone->name ?? __('file.all_zones') }})</div>
                            </div>
                            <div class="method-price">@price($rate->rate_amount)</div>
                        </label>
                    @empty
                        <div style="padding: 1rem; border: 1px solid #e2dcd4; text-align: center;">{{ __('file.no_shipping_methods_available') }}</div>
                    @endforelse
                </div>

                <!-- Payment Method (Bypass) -->
                <h2 class="section-title" style="margin-top: 2rem;">{{ __('file.payment_method') }}</h2>
                <div class="payment-methods-container">
                    <label class="payment-method-card">
                        <input type="radio" name="payment_method" value="cod" class="method-radio" checked>
                        <div class="method-details">
                            <div class="method-name">{{ __('file.cash_on_delivery_cod') }}</div>
                            <div class="method-desc">{{ __('file.pay_with_cash_upon_delivery') }}</div>
                        </div>
                    </label>
                    <label class="payment-method-card">
                        <input type="radio" name="payment_method" value="bank_transfer" class="method-radio">
                        <div class="method-details">
                            <div class="method-name">{{ __('file.direct_bank_transfer') }}</div>
                            <div class="method-desc">{{ __('file.make_your_payment_directly_into_our_bank_account') }}</div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn-submit">{{ __('file.place_order') }}</button>
            </form>
        </div>

        <!-- Right Column: Summary -->
        <div class="checkout-summary">
            <h2 class="section-title">{{ __('file.order_summary') }}</h2>
            
            <div class="summary-items">
                @foreach($cartItems as $item)
                    <div class="summary-item">
                        @if(isset($item->attributes['image']))
                            <img src="{{ asset('storage/' . $item->attributes['image']) }}" alt="{{ $item->name }}" class="summary-item-img">
                        @else
                            <div class="summary-item-img" style="display:flex; align-items:center; justify-content:center; font-size:0.6rem; color:#999;">{{ __('file.no_image') }}</div>
                        @endif
                        <div class="summary-item-info">
                            <div class="summary-item-title">{{ $item->name }}</div>
                            <div class="summary-item-meta">
                                {{ __('file.qty') }}: {{ $item->quantity }}
                                @if(isset($item->attributes['size'])) | {{ __('file.size') }}: {{ $item->attributes['size'] }} @endif
                                @if(isset($item->attributes['color'])) | {{ __('file.color') }}: {{ $item->attributes['color'] }} @endif
                            </div>
                        </div>
                        <div style="font-weight: 600; color: #1a1a1a;">
                            @price($item->price * $item->quantity)
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="summary-totals">
                <div class="summary-row">
                    <span>{{ __('file.subtotal') }}</span>
                    <span>@price($subtotal)</span>
                </div>
                <div class="summary-row">
                    <span>{{ __('file.shipping') }}</span>
                    <span id="summary-shipping-display">@price(0)</span>
                </div>
                <div class="summary-row summary-total">
                    <span>{{ __('file.total') }}</span>
                    <span id="summary-total-display">@price($total)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTotal() {
        const selectedShipping = document.querySelector('input[name="shipping_rate_id"]:checked');
        if (selectedShipping) {
            const shippingCost = parseFloat(selectedShipping.dataset.cost || 0);
            const subtotal = {{ $subtotal }};
            const total = subtotal + shippingCost;
            
            // For the summary display, we'll do a simple replacement if we don't have a JS formatter
            // Ideally we'd have a window.currency_format function
            const symbol = "{{ $currency_symbol }}";
            const position = "{{ Setting::getValue('currency_position', 'left') }}";
            const decimals = {{ Setting::getValue('number_of_decimals', 2) }};
            
            const format = (val) => {
                const formatted = val.toLocaleString(undefined, {minimumFractionDigits: decimals, maximumFractionDigits: decimals});
                return position === 'left' ? symbol + formatted : formatted + ' ' + symbol;
            };

            document.getElementById('summary-shipping-display').textContent = format(shippingCost);
            document.getElementById('summary-total-display').textContent = format(total);
        }
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endsection
