@extends('frontend.layouts.app')

@section('title', 'Complete Payment')
@section('body_class', 'light-page')

@section('content')
<style>
    .stripe-wrap {
        background: #f7f4ef;
        min-height: 80vh;
        padding-bottom: 4rem;
    }
    .stripe-header {
        background: #1a1a1a;
        color: #f5f0e8;
        padding: 1.25rem 2rem;
        text-align: center;
        border-bottom: 1px solid #2a2a2a;
    }
    .stripe-header h1 {
        font-family: var(--font-display, Georgia, serif);
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 400;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 0.2rem;
    }
    .stripe-container {
        max-width: 560px;
        margin: 2.5rem auto;
        padding: 0 1.5rem;
    }
    .stripe-card {
        background: #ffffff;
        border: 1px solid #e2dcd4;
        padding: 2rem;
    }
    .amount-block {
        background: #f7f4ef;
        border: 1px solid #e2dcd4;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
    }
    .amount-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #7a7068;
    }
    .amount-value {
        font-family: var(--font-display, Georgia, serif);
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
    }
    #payment-element {
        margin-bottom: 1.5rem;
    }
    #payment-errors {
        background: #ffebee;
        color: #c62828;
        border-left: 4px solid #c62828;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        display: none;
    }
    .btn-pay {
        display: block;
        width: 100%;
        background: #1a1a1a;
        color: #ffffff;
        text-align: center;
        padding: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: opacity 0.25s;
        margin-top: 0.5rem;
    }
    .btn-pay:hover { opacity: 0.85; }
    .btn-pay:disabled { opacity: 0.6; cursor: not-allowed; }
    .secure-note {
        text-align: center;
        margin-top: 1.25rem;
        font-size: 0.75rem;
        color: #7a7068;
        letter-spacing: 0.05em;
    }
    .spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        margin: 0 auto;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="stripe-wrap">
    <div class="stripe-header">
        <h1>Secure Payment</h1>
    </div>

    <div class="stripe-container">
        <div class="stripe-card">

            <div class="amount-block">
                <span class="amount-label">Total Due</span>
                <span class="amount-value">{{ strtoupper($currency) }} {{ number_format($totalAmount, 2) }}</span>
            </div>

            <form id="stripe-payment-form">
                <div id="payment-element"></div>
                <div id="payment-errors"></div>

                <button id="submit-btn" type="submit" class="btn-pay">
                    <span id="btn-label">Pay Now</span>
                    <div id="btn-spinner" class="spinner"></div>
                </button>
            </form>

            <p class="secure-note">🔒 &nbsp;SSL Secured &nbsp;·&nbsp; PCI DSS Compliant &nbsp;·&nbsp; Powered by Stripe</p>
        </div>
    </div>
</div>

{{-- Hidden form posted to server after Stripe confirms --}}
<form id="confirm-form" action="{{ route('checkout.stripe.confirm') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="payment_intent_id" id="payment_intent_id">
</form>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stripe = Stripe('{{ $stripePublicKey }}');

    const elements = stripe.elements({
        clientSecret: '{{ $clientSecret }}',
        appearance: {
            theme: 'stripe',
            variables: { colorPrimary: '#1a1a1a' }
        }
    });

    const paymentEl = elements.create('payment');
    paymentEl.mount('#payment-element');

    const form      = document.getElementById('stripe-payment-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnLabel  = document.getElementById('btn-label');
    const spinner   = document.getElementById('btn-spinner');
    const errorDiv  = document.getElementById('payment-errors');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        setLoading(true);

        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            redirect: 'if_required',
            confirmParams: {
                return_url: '{{ route("checkout.stripe.return") }}',
            },
        });

        if (error) {
            errorDiv.textContent = error.message;
            errorDiv.style.display = 'block';
            setLoading(false);
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            document.getElementById('payment_intent_id').value = paymentIntent.id;
            document.getElementById('confirm-form').submit();
        } else {
            errorDiv.textContent = 'Unexpected payment status. Please try again.';
            errorDiv.style.display = 'block';
            setLoading(false);
        }
    });

    function setLoading(on) {
        submitBtn.disabled    = on;
        btnLabel.style.display  = on ? 'none'   : 'inline';
        spinner.style.display   = on ? 'block'  : 'none';
    }
});
</script>
@endpush
