<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Request $request, Product $product)
    {
        $user = Auth::user();
        $wishlist = $user->wishlists()->where('product_id', $product->id)->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
            $message = 'Product removed from wishlist.';
        } else {
            $user->wishlists()->create(['product_id' => $product->id]);
            $status = 'added';
            $message = 'Product added to wishlist.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
                'wishlist_count' => $user->wishlists()->count()
            ]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();
        $user->wishlists()->where('product_id', $product->id)->delete();

        return back()->with('success', 'Product removed from wishlist.');
    }
}
