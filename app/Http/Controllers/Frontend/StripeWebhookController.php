<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    protected StripeService $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = $this->stripe->constructWebhookEvent($payload, $sigHeader);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook: invalid payload');
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook: invalid signature');
            return response('Invalid signature', 400);
        }

        match ($event->type) {
            'payment_intent.succeeded'        => $this->handleSucceeded($event->data->object),
            'payment_intent.payment_failed'   => $this->handleFailed($event->data->object),
            'charge.refunded'                 => $this->handleRefunded($event->data->object),
            default                           => null,
        };

        return response('Webhook handled', 200);
    }

    protected function handleSucceeded($paymentIntent): void
    {
        $txn = PaymentTransaction::where('transaction_id', $paymentIntent->id)->first();
        if ($txn && $txn->status !== 'completed') {
            $txn->update(['status' => 'completed']);
            $txn->order?->update(['payment_status' => 'paid']);
        }
    }

    protected function handleFailed($paymentIntent): void
    {
        $txn = PaymentTransaction::where('transaction_id', $paymentIntent->id)->first();
        if ($txn) {
            $txn->update([
                'status'         => 'failed',
                'failure_reason' => $paymentIntent->last_payment_error?->message ?? 'Payment failed',
            ]);
            $txn->order?->update(['payment_status' => 'failed']);
        }
    }

    protected function handleRefunded($charge): void
    {
        $txn = PaymentTransaction::where('transaction_id', $charge->payment_intent)->first();
        if ($txn) {
            $txn->update(['status' => 'refunded']);
            $txn->order?->update(['payment_status' => 'refunded']);
        }
    }
}
