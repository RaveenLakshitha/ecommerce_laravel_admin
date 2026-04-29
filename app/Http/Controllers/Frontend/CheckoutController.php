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
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getContent();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = Cart::getSubTotal();
        $total = Cart::getTotal();
        $total = Cart::getTotal();
        
        $shippingRates = ShippingRate::with('zone')->where('is_active', true)->get();

        $user = Auth::user();
        $addresses = $user ? $user->addresses : collect();

        return view('frontend.checkout.index', compact(
            'cartItems', 'subtotal', 'total', 'shippingRates', 'addresses', 'user'
        ));
    }

    public function process(Request $request)
    {
        $cartItems = Cart::getContent();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:50',
            'address_line1' => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'state'      => 'required|string|max:255',
            'postal_code'=> 'required|string|max:50',
            'country'    => 'required|string|max:255',
            'shipping_rate_id' => 'required|exists:shipping_rates,id',
            'payment_method' => 'required|string|in:cod,bank_transfer' // Bypassed integration
        ]);

        try {
            DB::beginTransaction();

            $shippingRate = ShippingRate::findOrFail($request->shipping_rate_id);
            $subtotal = Cart::getSubTotal();
            $shippingCost = $shippingRate->rate_amount;
            // Let's assume 'rate'. I'll double check the model below and fix it if needed.

            $totalAmount = $subtotal + $shippingCost;

            // Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'customer_name' => trim($request->first_name . ' ' . $request->last_name),
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending', // Both COD and Bank Transfer start as pending
                'subtotal' => $subtotal,
                'discount_amount' => 0, // Extendable for coupons later
                'shipping_amount' => $shippingCost,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
                'currency' => Setting::getValue('currency', 'USD'), 
                'notes' => $request->notes ?? null,
                'placed_at' => now(),
            ]);

            // Log Payment Transaction
            PaymentTransaction::create([
                'order_id' => $order->id,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'gateway' => $request->payment_method === 'bank_transfer' ? 'bank' : 'cod',
                'amount' => $totalAmount,
                'currency' => Setting::getValue('currency', 'USD'),
                'status' => 'pending', // Will be updated when paid
                'payment_type' => 'sale',
                'is_manual' => true,
            ]);

            // For simplicity, we are saving the address on the order indirectly or as a customer address. 
            // The Order model has shipping_address_id and billing_address_id. 
            // We should create an address record if it's a guest, or save it to user.
            
            $address = null;
            if (Auth::check()) {
                // Try to find if user has this exact address, else create one
                $address = Auth::user()->addresses()->create([
                    'type' => 'shipping',
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2 ?? null,
                    'city' => $request->city,
                    'province' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                    'is_default' => false,
                ]);
            } else {
                // For guest, we can create a generic Address without user_id if allowed, or just let order keep customer details.
                // Looking at Address model, user_id is fillable. Let's create an unlinked address or we just omit for guests if it fails.
                $address = \App\Models\Address::create([
                    'user_id' => null,
                    'type' => 'both',
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2 ?? null,
                    'city' => $request->city,
                    'province' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                ]);
            }

            if ($address) {
                $order->update([
                    'shipping_address_id' => $address->id,
                    'billing_address_id' => $address->id,
                ]);
            }

            // Create Order Items and adjust inventory
            foreach ($cartItems as $item) {
                $variant = Variant::find($item->id);

                if ($variant) {
                    // Check stock
                    if ($variant->stock_quantity < $item->quantity) {
                        throw new \Exception("Item {$item->name} does not have enough stock.");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'variant_id' => $variant->id,
                        'product_name_snapshot' => $item->name,
                        'variant_attributes' => $item->attributes->toArray(),
                        'quantity' => $item->quantity,
                        'unit_price' => $item->price,
                        'subtotal' => $item->price * $item->quantity,
                        'discount_amount' => 0,
                        'total' => $item->price * $item->quantity,
                    ]);

                    // Adjust inventory
                    $variant->decrement('stock_quantity', $item->quantity);

                    // Record Inventory Transaction
                    InventoryTransaction::create([
                        'variant_id' => $variant->id,
                        'type' => 'sale',
                        'quantity_change' => -$item->quantity,
                        'reference_type' => \App\Models\Order::class,
                        'reference_id' => $order->id,
                        'notes' => 'Sold in order ' . $order->order_number,
                    ]);
                }
            }

            DB::commit();

            // Clear Cart
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
}
