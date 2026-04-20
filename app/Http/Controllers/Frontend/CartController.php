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
        $cartItems      = Cart::getContent();
        $subtotal       = Cart::getSubTotal();
        $total          = Cart::getTotal();
        $currency_symbol = 'Rs.';

        return view('frontend.cart.index', compact(
            'cartItems', 'subtotal', 'total', 'currency_symbol'
        ));
    }

    public function add(Request $request, $variantId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = Variant::with('product')->findOrFail($variantId);

        // Stock check
        if ($variant->stock_quantity < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available.'], 422);
            }
            return back()->with('error', 'Not enough stock available.');
        }

        Cart::add([
            'id'       => $variant->id,
            'name'     => $variant->product->name,
            'price'    => $variant->display_price ?? $variant->price,
            'quantity' => $request->quantity,
            'attributes' => [
                'slug'  => $variant->product->slug,
                'size'  => $variant->size ?? null,
                'color' => $variant->color ?? null,
                'image' => $variant->product->primaryImage?->file_path ?? null,
            ],
            'associatedModel' => $variant,
        ]);

        if (Auth::check()) {
            Cart::session(Auth::id())->store();
        }

        $cartCount = Cart::getTotalQuantity();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Item added to cart!',
                'cartCount' => $cartCount,
            ]);
        }

        return back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'rowId'    => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Cart::get($request->rowId);

        if (!$item) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
            }
            return back()->with('error', 'Item not found in cart.');
        }

        // Stock check
        $variant = Variant::find($item->id);
        if ($variant && $variant->stock_quantity < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available.'], 422);
            }
            return back()->with('error', 'Not enough stock available.');
        }

        // darryldecode/laravelshoppingcart: update(rowId, array|int)
        Cart::update($request->rowId, [
            'quantity' => [
                'relative' => false,
                'value'    => (int) $request->quantity,
            ],
        ]);

        if (Auth::check()) {
            Cart::session(Auth::id())->store();
        }

        // Re-fetch updated item for its new subtotal
        $updatedItem   = Cart::get($request->rowId);
        $itemSubtotal  = $updatedItem ? number_format($updatedItem->getPriceSumWithConditions(), 2) : '0.00';
        $cartSubtotal  = number_format(Cart::getSubTotal(), 2);
        $cartTotal     = number_format(Cart::getTotal(), 2);
        $cartCount     = Cart::getTotalQuantity();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Cart updated!',
                'itemSubtotal' => $itemSubtotal,
                'cartSubtotal' => $cartSubtotal,
                'cartTotal'    => $cartTotal,
                'cartCount'    => $cartCount,
            ]);
        }

        return back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request, $rowId)
    {
        Cart::remove($rowId);

        if (Auth::check()) {
            Cart::session(Auth::id())->store();
        }

        $cartSubtotal = number_format(Cart::getSubTotal(), 2);
        $cartTotal    = number_format(Cart::getTotal(), 2);
        $cartCount    = Cart::getTotalQuantity();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Item removed from cart!',
                'cartSubtotal' => $cartSubtotal,
                'cartTotal'    => $cartTotal,
                'cartCount'    => $cartCount,
            ]);
        }

        return back()->with('success', 'Item removed from cart!');
    }

    /**
     * Merge guest cart with logged-in user's cart after login
     */
    public static function mergeAfterLogin()
    {
        if (Auth::check()) {
            Cart::session(Auth::id())->restore();

            $guestCart = Cart::getContent();
            foreach ($guestCart as $item) {
                Cart::session(Auth::id())->add($item);
            }

            Cart::clear();
            Cart::session(Auth::id())->store();
        }
    }
}
