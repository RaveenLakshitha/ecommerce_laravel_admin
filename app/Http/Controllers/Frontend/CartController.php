<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Services\DiscountService;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
class CartController extends Controller
{
    protected DiscountService $discountService;
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }
    public function index()
    {
        $cart      = $this->getCart();
        $cartItems = $cart->getContent();
        $subtotal  = $cart->getSubTotal();
        $appliedCoupon   = $this->discountService->getAppliedCoupon();
        $couponDiscount  = $this->discountService->getCouponDiscount();
        $autoDiscount    = $this->discountService->calculateAutomaticDiscount($subtotal);
        $bestRule        = $this->discountService->getBestActiveRule();
        $totalDiscount = max($couponDiscount, $autoDiscount);
        $total = max(0, $subtotal - $couponDiscount - $autoDiscount);
        return view('frontend.cart.index', compact(
            'cartItems', 'subtotal', 'total',
            'appliedCoupon', 'couponDiscount',
            'autoDiscount', 'bestRule', 'totalDiscount'
        ));
    }
    public function add(Request $request, $variantId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $variant = Variant::with('product')->findOrFail($variantId);
        if ($variant->stock_quantity < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available.'], 422);
            }
            return back()->with('error', 'Not enough stock available.');
        }
        $cart = $this->getCart();
        $cart->add([
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
        $cartCount = $cart->getTotalQuantity();
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
        $cart = $this->getCart();
        $item = $cart->get($request->rowId);
        if (!$item) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
            }
            return back()->with('error', 'Item not found in cart.');
        }
        $variant = Variant::find($item->id);
        if ($variant && $variant->stock_quantity < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available.'], 422);
            }
            return back()->with('error', 'Not enough stock available.');
        }
        $cart->update($request->rowId, [
            'quantity' => [
                'relative' => false,
                'value'    => (int) $request->quantity,
            ],
        ]);
        $updatedItem  = $cart->get($request->rowId);
        $newSubtotal  = $cart->getSubTotal();
        $couponDiscount = $this->discountService->getCouponDiscount();
        $autoDiscount   = $this->discountService->calculateAutomaticDiscount($newSubtotal);
        $newTotal     = max(0, $newSubtotal - $couponDiscount - $autoDiscount);
        $itemSubtotal = $updatedItem ? Setting::formatPrice($updatedItem->getPriceSumWithConditions()) : Setting::formatPrice(0);
        $cartSubtotal = Setting::formatPrice($newSubtotal);
        $cartTotal    = Setting::formatPrice($newTotal);
        $cartCount    = $cart->getTotalQuantity();
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
        $cart = $this->getCart();
        $cart->remove($rowId);
        $newSubtotal    = $cart->getSubTotal();
        $couponDiscount = $this->discountService->getCouponDiscount();
        $autoDiscount   = $this->discountService->calculateAutomaticDiscount($newSubtotal);
        $newTotal       = max(0, $newSubtotal - $couponDiscount - $autoDiscount);
        $cartSubtotal = Setting::formatPrice($newSubtotal);
        $cartTotal    = Setting::formatPrice($newTotal);
        $cartCount    = $cart->getTotalQuantity();
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
    public function applyPromo(Request $request)
    {
        $request->validate(['code' => 'required|string|max:100']);
        $cart     = $this->getCart();
        $subtotal = $cart->getSubTotal();
        $result   = $this->discountService->applyCoupon($request->code, $subtotal);
        if ($result['success']) {
            $newTotal = max(0, $subtotal - $result['discount'] - $this->discountService->calculateAutomaticDiscount($subtotal));
            return response()->json([
                'success'        => true,
                'message'        => $result['message'],
                'discount'       => Setting::formatPrice($result['discount']),
                'discountRaw'    => $result['discount'],
                'cartTotal'      => Setting::formatPrice($newTotal),
                'code'           => $result['coupon']->code,
            ]);
        }
        return response()->json(['success' => false, 'message' => $result['message']], 422);
    }
    public function removePromo(Request $request)
    {
        $this->discountService->removeCoupon();
        $cart         = $this->getCart();
        $subtotal     = $cart->getSubTotal();
        $autoDiscount = $this->discountService->calculateAutomaticDiscount($subtotal);
        $newTotal     = max(0, $subtotal - $autoDiscount);
        return response()->json([
            'success'   => true,
            'message'   => 'Coupon removed.',
            'cartTotal' => Setting::formatPrice($newTotal),
        ]);
    }
    public static function mergeAfterLogin()
    {
        if (Auth::check()) {
            $guestCart = Cart::getFacadeRoot()->getContent();
            $userCart  = Cart::session(Auth::id());
            foreach ($guestCart as $item) {
                $userCart->add([
                    'id'       => $item->id,
                    'name'     => $item->name,
                    'price'    => $item->price,
                    'quantity' => $item->quantity,
                    'attributes' => $item->attributes->toArray(),
                    'associatedModel' => $item->associatedModel,
                ]);
            }
            Cart::getFacadeRoot()->clear();
        }
    }
    private function getCart()
    {
        return Auth::check() ? Cart::session(Auth::id()) : Cart::getFacadeRoot();
    }
}
