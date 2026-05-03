<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use App\Models\PaymentGatewaySetting;

class StripeService
{
    public function __construct()
    {
        // Prefer admin-configured key, fall back to config/env
        $setting = PaymentGatewaySetting::where('gateway', 'stripe')
                     ->where('is_active', true)->first();
        
        $secret = $setting?->secret_key ?? config('stripe.secret');
        Stripe::setApiKey($secret);
    }

    /**
     * Create a PaymentIntent on Stripe's servers.
     * Amount must be in the SMALLEST currency unit (cents for USD).
     */
    public function createPaymentIntent(float $amount, string $currency, array $metadata = []): PaymentIntent
    {
        return PaymentIntent::create([
            'amount'                    => (int) round($amount * 100),
            'currency'                  => strtolower($currency),
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => $metadata,
        ]);
    }

    /**
     * Create a Stripe Checkout Session (hosted payment page).
     */
    public function createCheckoutSession(array $params): \Stripe\Checkout\Session
    {
        return \Stripe\Checkout\Session::create($params);
    }

    /**
     * Retrieve a Stripe Checkout Session (to verify after redirect).
     */
    public function retrieveCheckoutSession(string $sessionId): \Stripe\Checkout\Session
    {
        return \Stripe\Checkout\Session::retrieve([
            'id'     => $sessionId,
            'expand' => ['payment_intent'],
        ]);
    }

    /**
     * Retrieve a PaymentIntent (used to verify after redirect).
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return PaymentIntent::retrieve($paymentIntentId);
    }

    /**
     * Verify and construct Stripe webhook event.
     */
    public function constructWebhookEvent(string $payload, string $sigHeader): \Stripe\Event
    {
        return Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('stripe.webhook_secret')
        );
    }
}
