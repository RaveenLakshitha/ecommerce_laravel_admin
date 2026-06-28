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
        $setting = PaymentGatewaySetting::where('gateway', 'stripe')
                     ->where('is_active', true)->first();
        $secret = $setting?->secret_key ?? config('stripe.secret');
        Stripe::setApiKey($secret);
    }
    public function createPaymentIntent(float $amount, string $currency, array $metadata = []): PaymentIntent
    {
        return PaymentIntent::create([
            'amount'                    => (int) round($amount * 100),
            'currency'                  => strtolower($currency),
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => $metadata,
        ]);
    }
    public function createCheckoutSession(array $params): \Stripe\Checkout\Session
    {
        return \Stripe\Checkout\Session::create($params);
    }
    public function retrieveCheckoutSession(string $sessionId): \Stripe\Checkout\Session
    {
        return \Stripe\Checkout\Session::retrieve([
            'id'     => $sessionId,
            'expand' => ['payment_intent'],
        ]);
    }
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return PaymentIntent::retrieve($paymentIntentId);
    }
    public function constructWebhookEvent(string $payload, string $sigHeader): \Stripe\Event
    {
        return Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('stripe.webhook_secret')
        );
    }
}
