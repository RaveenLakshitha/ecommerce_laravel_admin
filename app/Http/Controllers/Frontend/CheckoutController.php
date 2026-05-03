<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InventoryTransaction;
use App\Models\PaymentTransaction;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\Variant;
use App\Services\DiscountService;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\PaymentGatewaySetting;
use App\Services\StripeService;


class CheckoutController extends Controller
{
    protected DiscountService $discountService;
    protected StripeService $stripeService;

    public function __construct(DiscountService $discountService, StripeService $stripeService)
    {
        $this->discountService = $discountService;
        $this->stripeService   = $stripeService;
    }

    public function index()
    {
        $cartItems = Cart::getContent();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = Cart::getSubTotal();

        // Coupon discount (from session)
        $appliedCoupon  = $this->discountService->getAppliedCoupon();
        $couponDiscount = $this->discountService->getCouponDiscount();

        // Automatic discount rules
        $autoDiscount = $this->discountService->calculateAutomaticDiscount($subtotal);

        $totalDiscount = $couponDiscount + $autoDiscount;
        $total         = max(0, $subtotal - $totalDiscount);

        $shippingRates = ShippingRate::with('zone')->where('is_active', true)->get();

        $user      = Auth::user();
        $addresses = $user ? $user->addresses : collect();

        // Stripe configuration
        $stripeSetting = PaymentGatewaySetting::where('gateway', 'stripe')->active()->first();
        $stripePublicKey = $stripeSetting?->public_key ?? config('stripe.key');

        // Currency formatting for JS
        $currency_position = Setting::getValue('currency_position', 'left');
        $currency_decimals = (int) Setting::getValue('number_of_decimals', 2);

        return view('frontend.checkout.index', compact(
            'cartItems', 'subtotal', 'total', 'shippingRates', 'addresses', 'user',
            'appliedCoupon', 'couponDiscount', 'autoDiscount', 'totalDiscount',
            'stripePublicKey', 'currency_position', 'currency_decimals'
        ));

    }

    public function process(Request $request)
    {
        $cartItems = Cart::getContent();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'            => 'required|string|max:50',
            'address_line1'    => 'required|string|max:255',
            'city'             => 'required|string|max:255',
            'state'            => 'required|string|max:255',
            'postal_code'      => 'required|string|max:50',
            'country'          => 'required|string|max:255',
            'shipping_rate_id' => 'required|exists:shipping_rates,id',
            'payment_method'   => 'required|string|in:cod,bank_transfer,stripe',
        ]);

        // Stripe redirection
        if ($request->payment_method === 'stripe') {
            return $this->initiateStripePayment($request);
        }

        try {
            DB::beginTransaction();

            $shippingRate = ShippingRate::findOrFail($request->shipping_rate_id);
            $subtotal     = Cart::getSubTotal();
            $shippingCost = $shippingRate->rate_amount;

            // ── Discount Resolution ──────────────────────────────────────────
            $appliedCoupon  = $this->discountService->getAppliedCoupon();
            $couponDiscount = $this->discountService->getCouponDiscount();
            $autoDiscount   = $this->discountService->calculateAutomaticDiscount($subtotal);
            $totalDiscount  = round($couponDiscount + $autoDiscount, 2);

            $totalAmount = max(0, $subtotal - $totalDiscount) + $shippingCost;

            // ── Create Order ─────────────────────────────────────────────────
            $order = Order::create([
                'order_number'    => 'ORD-' . strtoupper(uniqid()),
                'user_id'         => Auth::id(),
                'customer_name'   => trim($request->first_name . ' ' . $request->last_name),
                'customer_email'  => $request->email,
                'customer_phone'  => $request->phone,
                'status'          => 'pending',
                'payment_method'  => $request->payment_method,
                'payment_status'  => 'pending',
                'subtotal'        => $subtotal,
                'discount_amount' => $totalDiscount,
                'coupon_id'       => $appliedCoupon['id'] ?? null,
                'coupon_code_used'=> $appliedCoupon['code'] ?? null,
                'shipping_amount' => $shippingCost,
                'tax_amount'      => 0,
                'total_amount'    => $totalAmount,
                'currency'        => Setting::getValue('currency', 'USD'),
                'notes'           => $request->notes ?? null,
                'placed_at'       => now(),
            ]);

            // ── Log Payment Transaction ──────────────────────────────────────
            PaymentTransaction::create([
                'order_id'      => $order->id,
                'transaction_id'=> 'TXN-' . strtoupper(uniqid()),
                'gateway'       => $request->payment_method === 'bank_transfer' ? 'bank' : 'cod',
                'amount'        => $totalAmount,
                'currency'      => Setting::getValue('currency', 'USD'),
                'status'        => 'pending',
                'payment_type'  => 'sale',
                'is_manual'     => true,
            ]);

            // ── Save Shipping Address ────────────────────────────────────────
            $address = null;
            if (Auth::check()) {
                $address = Auth::user()->addresses()->create([
                    'type'          => 'shipping',
                    'first_name'    => $request->first_name,
                    'last_name'     => $request->last_name,
                    'phone'         => $request->phone,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2 ?? null,
                    'city'          => $request->city,
                    'province'      => $request->state,
                    'postal_code'   => $request->postal_code,
                    'country'       => $request->country,
                    'is_default'    => false,
                ]);
            } else {
                $address = \App\Models\Address::create([
                    'user_id'       => null,
                    'type'          => 'both',
                    'first_name'    => $request->first_name,
                    'last_name'     => $request->last_name,
                    'phone'         => $request->phone,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2 ?? null,
                    'city'          => $request->city,
                    'province'      => $request->state,
                    'postal_code'   => $request->postal_code,
                    'country'       => $request->country,
                ]);
            }

            if ($address) {
                $order->update([
                    'shipping_address_id' => $address->id,
                    'billing_address_id'  => $address->id,
                ]);
            }

            // ── Create Order Items & Adjust Inventory ────────────────────────
            foreach ($cartItems as $item) {
                $variant = Variant::find($item->id);

                if ($variant) {
                    if ($variant->stock_quantity < $item->quantity) {
                        throw new \Exception("Item {$item->name} does not have enough stock.");
                    }

                    OrderItem::create([
                        'order_id'               => $order->id,
                        'variant_id'             => $variant->id,
                        'product_name_snapshot'  => $item->name,
                        'variant_attributes'     => $item->attributes->toArray(),
                        'quantity'               => $item->quantity,
                        'unit_price'             => $item->price,
                        'subtotal'               => $item->price * $item->quantity,
                        'discount_amount'        => 0,
                        'total'                  => $item->price * $item->quantity,
                    ]);

                    $variant->decrement('stock_quantity', $item->quantity);

                    InventoryTransaction::create([
                        'variant_id'      => $variant->id,
                        'type'            => 'sale',
                        'quantity_change' => -$item->quantity,
                        'reference_type'  => \App\Models\Order::class,
                        'reference_id'    => $order->id,
                        'notes'           => 'Sold in order ' . $order->order_number,
                    ]);
                }
            }

            // ── Create Shipment ─────────────────────────────────────────────
            \App\Models\Shipment::create([
                'order_id' => $order->id,
                'status'   => 'pending',
            ]);

            DB::commit();

            // ── Record Coupon Usage & Clear Cart ─────────────────────────────
            if ($appliedCoupon && isset($appliedCoupon['id'])) {
                $this->discountService->recordUsage(
                    $appliedCoupon['id'],
                    $order->id,
                    $couponDiscount
                );
            }

            Cart::clear();

            return redirect()->route('checkout.success')->with('order_number', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout failed: ' . $e->getMessage())->withInput();
        }
    }

    public function success()
    {
        $orderNumber = session('order_number');
        if (!$orderNumber) {
            return redirect()->route('home');
        }

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return view('frontend.checkout.success', compact('order'));
    }

    /**
     * Called when payment_method=stripe is selected.
     * Creates a Stripe PaymentIntent and stores order data in session.
     */
    protected function initiateStripePayment(Request $request)
    {
        $subtotal       = Cart::getSubTotal();
        $shippingRate   = ShippingRate::findOrFail($request->shipping_rate_id);
        $shippingCost   = $shippingRate->rate_amount;
        $appliedCoupon  = $this->discountService->getAppliedCoupon();
        $couponDiscount = $this->discountService->getCouponDiscount();
        $autoDiscount   = $this->discountService->calculateAutomaticDiscount($subtotal);
        $totalDiscount  = round($couponDiscount + $autoDiscount, 2);
        $totalAmount    = max(0, $subtotal - $totalDiscount) + $shippingCost;
        $currency       = Setting::getValue('currency', 'USD');

        // Store checkout form data in session — retrieved after Stripe redirects back
        session([
            'stripe_checkout' => $request->except('_token'),
            'stripe_total'    => $totalAmount,
            'stripe_currency' => $currency,
        ]);

        // Create a Stripe Checkout Session (hosted payment page on Stripe's servers)
        $checkoutSession = $this->stripeService->createCheckoutSession([
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => strtolower($currency),
                    'product_data' => [
                        'name' => 'Order from ' . config('app.name'),
                    ],
                    'unit_amount'  => (int) round($totalAmount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode'           => 'payment',
            'customer_email' => $request->email,
            'success_url'    => route('checkout.stripe.return') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('checkout.index'),
        ]);

        // Redirect customer to Stripe's hosted payment page
        return redirect($checkoutSession->url);
    }

    /**
     * GET /checkout/stripe/pay
     * Renders the Stripe card form — always a GET so Stripe's 3DS redirect works.
     */
    public function stripePayPage()
    {
        $clientSecret    = session('stripe_client_secret');
        $stripePublicKey = session('stripe_public_key');
        $totalAmount     = session('stripe_total');
        $currency        = session('stripe_currency');

        if (!$clientSecret || !$totalAmount) {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment session expired. Please try again.');
        }

        return view('frontend.checkout.stripe_confirm', compact(
            'clientSecret', 'stripePublicKey', 'totalAmount', 'currency'
        ));
    }

    /**
     * GET /checkout/stripe/return
     * Stripe redirects here after payment on their hosted page.
     * Verifies the Checkout Session and creates the order.
     */
    public function stripeReturn(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment could not be verified. Please try again.');
        }

        // Retrieve & verify on the server — never trust the URL alone
        $checkoutSession = $this->stripeService->retrieveCheckoutSession($sessionId);

        if ($checkoutSession->payment_status !== 'paid') {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment was not completed. Status: ' . $checkoutSession->payment_status);
        }

        $stripeCheckout = session('stripe_checkout');
        if (!$stripeCheckout) {
            return redirect()->route('checkout.index')
                ->with('error', 'Session expired. Please try again.');
        }

        $cartItems   = Cart::getContent();
        $totalAmount = session('stripe_total');
        $currency    = session('stripe_currency');
        $paymentIntentId = $checkoutSession->payment_intent->id ?? $checkoutSession->payment_intent;

        try {
            DB::beginTransaction();

            $shippingRate   = ShippingRate::findOrFail($stripeCheckout['shipping_rate_id']);
            $subtotal       = Cart::getSubTotal();
            $appliedCoupon  = $this->discountService->getAppliedCoupon();
            $couponDiscount = $this->discountService->getCouponDiscount();
            $autoDiscount   = $this->discountService->calculateAutomaticDiscount($subtotal);
            $totalDiscount  = round($couponDiscount + $autoDiscount, 2);

            $order = Order::create([
                'order_number'    => 'ORD-' . strtoupper(uniqid()),
                'user_id'         => Auth::id(),
                'customer_name'   => trim($stripeCheckout['first_name'] . ' ' . $stripeCheckout['last_name']),
                'customer_email'  => $stripeCheckout['email'],
                'customer_phone'  => $stripeCheckout['phone'],
                'status'          => 'processing',
                'payment_method'  => 'stripe',
                'payment_status'  => 'paid',
                'subtotal'        => $subtotal,
                'discount_amount' => $totalDiscount,
                'coupon_id'       => $appliedCoupon['id'] ?? null,
                'coupon_code_used'=> $appliedCoupon['code'] ?? null,
                'shipping_amount' => $shippingRate->rate_amount,
                'tax_amount'      => 0,
                'total_amount'    => $totalAmount,
                'currency'        => $currency,
                'notes'           => $stripeCheckout['notes'] ?? null,
                'placed_at'       => now(),
            ]);

            PaymentTransaction::create([
                'order_id'       => $order->id,
                'transaction_id' => $paymentIntentId,
                'gateway'        => 'stripe',
                'amount'         => $totalAmount,
                'currency'       => $currency,
                'status'         => 'completed',
                'payment_type'   => 'sale',
                'is_manual'      => false,
                'metadata'       => [
                    'stripe_session_id'     => $sessionId,
                    'stripe_payment_intent' => $paymentIntentId,
                    'stripe_payment_status' => $checkoutSession->payment_status,
                ],
            ]);

            $addressData = [
                'type'          => 'shipping',
                'first_name'    => $stripeCheckout['first_name'],
                'last_name'     => $stripeCheckout['last_name'],
                'phone'         => $stripeCheckout['phone'],
                'address_line1' => $stripeCheckout['address_line1'],
                'address_line2' => $stripeCheckout['address_line2'] ?? null,
                'city'          => $stripeCheckout['city'],
                'province'      => $stripeCheckout['state'],
                'postal_code'   => $stripeCheckout['postal_code'],
                'country'       => $stripeCheckout['country'],
                'is_default'    => false,
            ];

            $address = Auth::check()
                ? Auth::user()->addresses()->create($addressData)
                : \App\Models\Address::create(array_merge($addressData, ['user_id' => null, 'type' => 'both']));

            if ($address) {
                $order->update([
                    'shipping_address_id' => $address->id,
                    'billing_address_id'  => $address->id,
                ]);
            }

            foreach ($cartItems as $item) {
                $variant = Variant::find($item->id);
                if ($variant) {
                    OrderItem::create([
                        'order_id'              => $order->id,
                        'variant_id'            => $variant->id,
                        'product_name_snapshot' => $item->name,
                        'variant_attributes'    => $item->attributes->toArray(),
                        'quantity'              => $item->quantity,
                        'unit_price'            => $item->price,
                        'subtotal'              => $item->price * $item->quantity,
                        'discount_amount'       => 0,
                        'total'                 => $item->price * $item->quantity,
                    ]);
                    $variant->decrement('stock_quantity', $item->quantity);
                    InventoryTransaction::create([
                        'variant_id'      => $variant->id,
                        'type'            => 'sale',
                        'quantity_change' => -$item->quantity,
                        'reference_type'  => \App\Models\Order::class,
                        'reference_id'    => $order->id,
                        'notes'           => 'Sold in order ' . $order->order_number,
                    ]);
                }
            }

            \App\Models\Shipment::create(['order_id' => $order->id, 'status' => 'pending']);

            DB::commit();

            if ($appliedCoupon && isset($appliedCoupon['id'])) {
                $this->discountService->recordUsage($appliedCoupon['id'], $order->id, $couponDiscount);
            }

            session()->forget(['stripe_checkout', 'stripe_total', 'stripe_currency']);
            Cart::clear();

            return redirect()->route('checkout.success')->with('order_number', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')
                ->with('error', 'Order creation failed: ' . $e->getMessage());
        }
    }

    /**
     * After Stripe.js confirms the card, the browser posts here.
     * We verify on the server and then create the order.
     */
    public function stripeConfirm(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $stripeCheckout = session('stripe_checkout');
        if (!$stripeCheckout) {
            return redirect()->route('checkout.index')->with('error', 'Session expired. Please try again.');
        }

        // Verify payment with Stripe (server-side — never trust the frontend alone)
        $paymentIntent = $this->stripeService->retrievePaymentIntent($request->payment_intent_id);

        if ($paymentIntent->status !== 'succeeded') {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment was not completed. Status: ' . $paymentIntent->status);
        }

        $cartItems   = Cart::getContent();
        $totalAmount = session('stripe_total');
        $currency    = session('stripe_currency');

        try {
            DB::beginTransaction();

            $shippingRate = ShippingRate::findOrFail($stripeCheckout['shipping_rate_id']);
            $subtotal     = Cart::getSubTotal();

            $appliedCoupon  = $this->discountService->getAppliedCoupon();
            $couponDiscount = $this->discountService->getCouponDiscount();
            $autoDiscount   = $this->discountService->calculateAutomaticDiscount($subtotal);
            $totalDiscount  = round($couponDiscount + $autoDiscount, 2);

            // ── Create Order ─────────────────────────────────────────────────
            $order = Order::create([
                'order_number'    => 'ORD-' . strtoupper(uniqid()),
                'user_id'         => Auth::id(),
                'customer_name'   => trim($stripeCheckout['first_name'] . ' ' . $stripeCheckout['last_name']),
                'customer_email'  => $stripeCheckout['email'],
                'customer_phone'  => $stripeCheckout['phone'],
                'status'          => 'processing',
                'payment_method'  => 'stripe',
                'payment_status'  => 'paid',
                'subtotal'        => $subtotal,
                'discount_amount' => $totalDiscount,
                'coupon_id'       => $appliedCoupon['id'] ?? null,
                'coupon_code_used'=> $appliedCoupon['code'] ?? null,
                'shipping_amount' => $shippingRate->rate_amount,
                'tax_amount'      => 0,
                'total_amount'    => $totalAmount,
                'currency'        => $currency,
                'notes'           => $stripeCheckout['notes'] ?? null,
                'placed_at'       => now(),
            ]);

            // ── Log Payment Transaction ──────────────────────────────────────
            PaymentTransaction::create([
                'order_id'       => $order->id,
                'transaction_id' => $paymentIntent->id,
                'gateway'        => 'stripe',
                'amount'         => $totalAmount,
                'currency'       => $currency,
                'status'         => 'completed',
                'payment_type'   => 'sale',
                'is_manual'      => false,
                'metadata'       => [
                    'stripe_payment_intent' => $paymentIntent->id,
                    'stripe_status'         => $paymentIntent->status,
                ],
            ]);

            // ── Save Shipping Address ────────────────────────────────────────
            $address = null;
            if (Auth::check()) {
                $address = Auth::user()->addresses()->create([
                    'type'          => 'shipping',
                    'first_name'    => $stripeCheckout['first_name'],
                    'last_name'     => $stripeCheckout['last_name'],
                    'phone'         => $stripeCheckout['phone'],
                    'address_line1' => $stripeCheckout['address_line1'],
                    'address_line2' => $stripeCheckout['address_line2'] ?? null,
                    'city'          => $stripeCheckout['city'],
                    'province'      => $stripeCheckout['state'],
                    'postal_code'   => $stripeCheckout['postal_code'],
                    'country'       => $stripeCheckout['country'],
                    'is_default'    => false,
                ]);
            } else {
                $address = \App\Models\Address::create([
                    'user_id'       => null,
                    'type'          => 'both',
                    'first_name'    => $stripeCheckout['first_name'],
                    'last_name'     => $stripeCheckout['last_name'],
                    'phone'         => $stripeCheckout['phone'],
                    'address_line1' => $stripeCheckout['address_line1'],
                    'address_line2' => $stripeCheckout['address_line2'] ?? null,
                    'city'          => $stripeCheckout['city'],
                    'province'      => $stripeCheckout['state'],
                    'postal_code'   => $stripeCheckout['postal_code'],
                    'country'       => $stripeCheckout['country'],
                ]);
            }

            if ($address) {
                $order->update([
                    'shipping_address_id' => $address->id,
                    'billing_address_id'  => $address->id,
                ]);
            }

            // ── Create Order Items & Adjust Inventory ────────────────────────
            foreach ($cartItems as $item) {
                $variant = Variant::find($item->id);
                if ($variant) {
                    OrderItem::create([
                        'order_id'               => $order->id,
                        'variant_id'             => $variant->id,
                        'product_name_snapshot'  => $item->name,
                        'variant_attributes'     => $item->attributes->toArray(),
                        'quantity'               => $item->quantity,
                        'unit_price'             => $item->price,
                        'subtotal'               => $item->price * $item->quantity,
                        'discount_amount'        => 0,
                        'total'                  => $item->price * $item->quantity,
                    ]);
                    $variant->decrement('stock_quantity', $item->quantity);
                    InventoryTransaction::create([
                        'variant_id'      => $variant->id,
                        'type'            => 'sale',
                        'quantity_change' => -$item->quantity,
                        'reference_type'  => \App\Models\Order::class,
                        'reference_id'    => $order->id,
                        'notes'           => 'Sold in order ' . $order->order_number,
                    ]);
                }
            }

            // ── Create Shipment ─────────────────────────────────────────────
            \App\Models\Shipment::create([
                'order_id' => $order->id,
                'status'   => 'pending',
            ]);

            DB::commit();

            if ($appliedCoupon && isset($appliedCoupon['id'])) {
                $this->discountService->recordUsage($appliedCoupon['id'], $order->id, $couponDiscount);
            }

            session()->forget(['stripe_checkout', 'stripe_total', 'stripe_currency']);
            Cart::clear();

            return redirect()->route('checkout.success')->with('order_number', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}
