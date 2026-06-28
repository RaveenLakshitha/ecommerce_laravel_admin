<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'is_anonymous' => 'sometimes|boolean',
        ]);
        $customer = Auth::user()->customer;
        if (!$customer) {
            $customer = Auth::user()->customer()->create([
                'first_name' => explode(' ', Auth::user()->name)[0] ?? 'User',
                'last_name' => explode(' ', Auth::user()->name)[1] ?? '',
                'email' => Auth::user()->email,
            ]);
        }
        $existingReview = Review::where('customer_id', $customer->id)
            ->where('product_id', $product->id)
            ->first();
        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => __('file.already_reviewed_this_product') ?: 'You have already reviewed this product.'
            ], 422);
        }
        $hasPurchased = Auth::user()->orders()
            ->where('status', 'delivered')
            ->whereHas('items.variant', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->exists();
        $review = Review::create([
            'customer_id'   => $customer->id,
            'product_id'    => $product->id,
            'rating'        => $request->rating,
            'title'         => $request->title,
            'content'       => $request->content,
            'is_anonymous'  => $request->boolean('is_anonymous', false),
            'status'        => 'pending', 
            'helpful_count' => 0,
        ]);
        return response()->json([
            'success' => true,
            'message' => __('file.review_submitted_for_moderation') ?: 'Thank you! Your review has been submitted and is awaiting moderation.'
        ]);
    }
    public function helpful(Review $review)
    {
        $review->increment('helpful_count');
        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }
}
