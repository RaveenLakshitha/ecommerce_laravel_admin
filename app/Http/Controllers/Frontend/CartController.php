<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Get current cart content (guest or logged-in)
        $cartItems = Cart::getContent();

        // Optional: calculate totals
        $subtotal = Cart::getSubTotal();
        $total = Cart::getTotal();

        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'total'));
    }

    public function add(Request $request, $variantId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = Variant::with('product')->findOrFail($variantId);

        // Check stock (basic)
        if ($variant->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        Cart::add([
            'id' => $variant->id,
            'name' => $variant->product->name,
            'price' => $variant->price,
            'quantity' => $request->quantity,
            'attributes' => [
                'slug' => $variant->product->slug,
                'size' => $variant->size ?? null,
                'color' => $variant->color ?? null,
                'image' => $variant->product->primaryImage?->url ?? null,
            ],
            'associatedModel' => $variant,
        ]);

        // If user is logged in → save cart to database
        if (Auth::check()) {
            Cart::session(Auth::id())->store();
        }

        return back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'rowId' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Cart::get($request->rowId);

        if (!$item) {
            return back()->with('error', 'Item not found in cart.');
        }

        // Optional: check stock again
        $variant = Variant::find($item->id);
        if ($variant && $variant->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        Cart::update($request->rowId, $request->quantity);

        // Save if logged in
        if (Auth::check()) {
            Cart::session(Auth::id())->store();
        }

        return back()->with('success', 'Cart updated!');
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);

        // Save if logged in
        if (Auth::check()) {
            Cart::session(Auth::id())->store();
        }

        return back()->with('success', 'Item removed from cart!');
    }

    /**
     * Optional: Merge guest cart with logged-in user's cart after login
     * Call this from your LoginController after successful login
     */
    public static function mergeAfterLogin()
    {
        if (Auth::check()) {
            // Restore saved cart (if any)
            Cart::session(Auth::id())->restore();

            // Merge guest cart (session cart) into user cart
            $guestCart = Cart::getContent();
            foreach ($guestCart as $item) {
                Cart::session(Auth::id())->add($item);
            }

            // Clear guest cart
            Cart::clear();

            // Save merged cart
            Cart::session(Auth::id())->store();
        }
    }
}
