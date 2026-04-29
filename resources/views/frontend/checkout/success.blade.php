@extends('frontend.layouts.app')

@section('title', __('file.order_successful'))
@section('body_class', 'light-page')

@section('content')
<style>
    *, *::before, *::after { box-sizing: border-box; }
    
    .success-wrap {
        background: #f7f4ef;
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1.5rem;
    }

    .success-container {
        max-width: 600px;
        width: 100%;
        background: #ffffff;
        border: 1px solid #e2dcd4;
        padding: 3rem 2rem;
        text-align: center;
    }

    .success-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.5rem;
        color: #27ae60;
    }

    .success-title {
        font-family: var(--font-display, Georgia, serif);
        font-size: 2rem;
        font-weight: 400;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .success-message {
        font-size: 0.95rem;
        color: #7a7068;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .order-details {
        background: #f7f4ef;
        padding: 1.5rem;
        border: 1px solid #e2dcd4;
        margin-bottom: 2rem;
        text-align: left;
    }

    .order-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        color: #1a1a1a;
    }

    .order-row:last-child {
        margin-bottom: 0;
    }

    .order-label {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.75rem;
        color: #7a7068;
    }

    .btn-primary {
        display: inline-block;
        background: #1a1a1a;
        color: #ffffff !important;
        padding: 1rem 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: opacity 0.25s;
        text-decoration: none !important;
    }

    .btn-primary:hover {
        opacity: 0.85;
    }
</style>

<div class="success-wrap">
    <div class="success-container">
        <svg class="success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        
        <h1 class="success-title">{{ __('file.thank_you_for_your_order') }}</h1>
        <p class="success-message">{{ __('file.order_placed_successfully_note') }}</p>
        
        <div class="order-details">
            <div class="order-row">
                <span class="order-label">{{ __('file.order_number') }}:</span>
                <span style="font-weight: 600;">{{ $order->order_number }}</span>
            </div>
            <div class="order-row">
                <span class="order-label">{{ __('file.date') }}:</span>
                <span>{{ $order->placed_at->format('F j, Y') }}</span>
            </div>
            <div class="order-row">
                <span class="order-label">{{ __('file.total_amount') }}:</span>
                <span style="font-weight: 600;">@price($order->total_amount, $order->currency)</span>
            </div>
            <div class="order-row">
                <span class="order-label">{{ __('file.payment_method') }}:</span>
                <span>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
        </div>
        
        <a href="{{ route('frontend.products.index') }}" class="btn-primary">{{ __('file.continue_shopping') }}</a>
        @auth
            <a href="{{ route('account.dashboard') }}" style="display: block; margin-top: 1rem; color: #1a1a1a; text-decoration: underline; font-size: 0.85rem; font-weight: 600;">{{ __('file.view_order_history') }}</a>
        @endauth
    </div>
</div>
@endsection
