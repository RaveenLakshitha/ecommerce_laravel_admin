<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\DiscountRule;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DiscountService
{
    const SESSION_KEY = 'applied_coupon';

    // ─────────────────────────────────────────────────────────────────────────
    // COUPON METHODS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Validate and apply a coupon code to the session.
     *
     * @return array ['success' => bool, 'message' => string, 'coupon' => Coupon|null, 'discount' => float]
     */
    public function applyCoupon(string $code, float $subtotal): array
    {
        $code = strtoupper(trim($code));

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.', 'coupon' => null, 'discount' => 0];
        }

        if (! $coupon->isValid()) {
            return ['success' => false, 'message' => 'This coupon has expired or is inactive.', 'coupon' => null, 'discount' => 0];
        }

        // Usage limit check
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit.', 'coupon' => null, 'discount' => 0];
        }

        // Per-user usage limit check
        if ($coupon->usage_per_user && Auth::check()) {
            $userUsages = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', Auth::id())
                ->count();

            if ($userUsages >= $coupon->usage_per_user) {
                return ['success' => false, 'message' => 'You have already used this coupon the maximum number of times.', 'coupon' => null, 'discount' => 0];
            }
        }

        // Minimum order amount
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return [
                'success' => false,
                'message' => 'Minimum order amount of ' . number_format($coupon->min_order_amount, 2) . ' is required for this coupon.',
                'coupon'  => null,
                'discount' => 0,
            ];
        }

        // applies_to validation (cart items must qualify)
        if (! $this->cartQualifiesForCoupon($coupon)) {
            return ['success' => false, 'message' => 'This coupon does not apply to any items in your cart.', 'coupon' => null, 'discount' => 0];
        }

        $discount = $this->calculateCouponDiscount($coupon, $subtotal);

        // Store in session
        Session::put(self::SESSION_KEY, [
            'id'       => $coupon->id,
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
        ]);

        return [
            'success'  => true,
            'message'  => 'Coupon applied! You saved ' . number_format($discount, 2) . '.',
            'coupon'   => $coupon,
            'discount' => $discount,
        ];
    }

    /**
     * Remove the currently applied coupon from session.
     */
    public function removeCoupon(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Get applied coupon data from session (or null if none).
     */
    public function getAppliedCoupon(): ?array
    {
        return Session::get(self::SESSION_KEY);
    }

    /**
     * Get the coupon discount amount from session (0 if none applied).
     */
    public function getCouponDiscount(): float
    {
        $applied = $this->getAppliedCoupon();
        return $applied ? (float) ($applied['discount'] ?? 0) : 0.0;
    }

    /**
     * Record coupon usage after a successful order.
     */
    public function recordUsage(int $couponId, int $orderId, float $discountAmount): void
    {
        $coupon = Coupon::find($couponId);
        if (! $coupon) return;

        \App\Models\CouponUsage::create([
            'coupon_id'       => $couponId,
            'user_id'         => Auth::id(),
            'order_id'        => $orderId,
            'code_used'       => $coupon->code,
            'discount_amount' => $discountAmount,
            'used_at'         => now(),
        ]);

        $coupon->increment('used_count');

        // Clear from session
        $this->removeCoupon();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DISCOUNT RULE METHODS (Automatic)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Get all active, applicable automatic discount rules sorted by priority.
     */
    public function getActiveDiscountRules(): \Illuminate\Database\Eloquent\Collection
    {
        return DiscountRule::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->get();
    }

    /**
     * Calculate the total automatic discount for the current cart.
     * Returns the total discount amount to subtract.
     */
    public function calculateAutomaticDiscount(float $subtotal): float
    {
        $cartItems = Cart::getContent();
        $rules     = $this->getActiveDiscountRules();
        $totalDiscount = 0.0;

        foreach ($rules as $rule) {
            if (! $this->cartQualifiesForDiscountRule($rule, $cartItems)) {
                continue;
            }

            if ($rule->min_order_amount && $subtotal < $rule->min_order_amount) {
                continue;
            }

            $ruleDiscount = $this->calculateRuleDiscount($rule, $cartItems, $subtotal);
            $totalDiscount += $ruleDiscount;

            // Only apply the highest-priority rule (first match wins)
            // Remove this break to stack multiple rules
            if ($ruleDiscount > 0) {
                break;
            }
        }

        return round($totalDiscount, 2);
    }

    /**
     * Get the best matching active discount rule (for display purposes).
     */
    public function getBestActiveRule(): ?DiscountRule
    {
        $cartItems = Cart::getContent();
        $rules = $this->getActiveDiscountRules();

        foreach ($rules as $rule) {
            if ($this->cartQualifiesForDiscountRule($rule, $cartItems)) {
                return $rule;
            }
        }

        return null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function calculateCouponDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($coupon->type === 'percentage') {
            $discount = $subtotal * ($coupon->value / 100);
        } else {
            $discount = (float) $coupon->value;
        }

        // Apply max discount cap if set
        if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
            $discount = (float) $coupon->max_discount_amount;
        }

        // Discount can't exceed subtotal
        return round(min($discount, $subtotal), 2);
    }

    private function cartQualifiesForCoupon(Coupon $coupon): bool
    {
        if ($coupon->applies_to === 'all') {
            return true;
        }

        $cartItems = Cart::getContent();
        $variantIds = $cartItems->pluck('id')->toArray();

        if ($coupon->applies_to === 'specific_products') {
            $productIds = $coupon->products()->pluck('products.id')->toArray();
            foreach ($cartItems as $item) {
                $variant = \App\Models\Variant::find($item->id);
                if ($variant && in_array($variant->product_id, $productIds)) {
                    return true;
                }
            }
            return false;
        }

        if ($coupon->applies_to === 'specific_categories') {
            $categoryIds = $coupon->categories()->pluck('categories.id')->toArray();
            foreach ($cartItems as $item) {
                $variant = \App\Models\Variant::with('product')->find($item->id);
                if ($variant && in_array($variant->product->category_id, $categoryIds)) {
                    return true;
                }
            }
            return false;
        }

        if ($coupon->applies_to === 'specific_collections') {
            $collectionIds = $coupon->collections()->pluck('collections.id')->toArray();
            foreach ($cartItems as $item) {
                $variant = \App\Models\Variant::with('product.collections')->find($item->id);
                if ($variant) {
                    $productCollectionIds = $variant->product->collections->pluck('id')->toArray();
                    if (array_intersect($collectionIds, $productCollectionIds)) {
                        return true;
                    }
                }
            }
            return false;
        }

        return true;
    }

    private function cartQualifiesForDiscountRule(DiscountRule $rule, $cartItems): bool
    {
        if ($rule->applies_to === 'all') {
            return $cartItems->count() > 0;
        }

        if ($rule->applies_to === 'products') {
            $productIds = $rule->products()->pluck('products.id')->toArray();
            foreach ($cartItems as $item) {
                $variant = \App\Models\Variant::find($item->id);
                if ($variant && in_array($variant->product_id, $productIds)) {
                    return true;
                }
            }
            return false;
        }

        if ($rule->applies_to === 'categories') {
            $categoryIds = $rule->categories()->pluck('categories.id')->toArray();
            foreach ($cartItems as $item) {
                $variant = \App\Models\Variant::with('product')->find($item->id);
                if ($variant && in_array($variant->product->category_id, $categoryIds)) {
                    return true;
                }
            }
            return false;
        }

        if ($rule->applies_to === 'collections') {
            $collectionIds = $rule->collections()->pluck('collections.id')->toArray();
            foreach ($cartItems as $item) {
                $variant = \App\Models\Variant::with('product.collections')->find($item->id);
                if ($variant) {
                    $productCollectionIds = $variant->product->collections->pluck('id')->toArray();
                    if (array_intersect($collectionIds, $productCollectionIds)) {
                        return true;
                    }
                }
            }
            return false;
        }

        return false;
    }

    private function calculateRuleDiscount(DiscountRule $rule, $cartItems, float $subtotal): float
    {
        switch ($rule->type) {
            case 'percentage':
                $discount = $subtotal * ($rule->value / 100);
                break;

            case 'fixed':
                $discount = (float) $rule->value;
                break;

            case 'bogo':
            case 'buy_x_get_y':
                // Count total qualifying items
                $totalQty = 0;
                $cheapestItemPrice = PHP_INT_MAX;

                foreach ($cartItems as $item) {
                    if ($this->itemQualifiesForRule($rule, $item)) {
                        $totalQty += $item->quantity;
                        $unitPrice = $item->price;
                        if ($unitPrice < $cheapestItemPrice) {
                            $cheapestItemPrice = $unitPrice;
                        }
                    }
                }

                $buyQty = $rule->buy_quantity ?? 1;
                $getQty = $rule->get_quantity ?? 1;

                if ($totalQty < $buyQty + $getQty) {
                    return 0.0;
                }

                // How many "get" items are free?
                $sets = floor($totalQty / ($buyQty + $getQty));
                $freeItems = $sets * $getQty;
                $discount = $cheapestItemPrice * $freeItems;
                break;

            default:
                $discount = 0.0;
        }

        return round(min($discount, $subtotal), 2);
    }

    private function itemQualifiesForRule(DiscountRule $rule, $item): bool
    {
        if ($rule->applies_to === 'all') return true;

        $variant = \App\Models\Variant::with('product.collections')->find($item->id);
        if (! $variant) return false;

        if ($rule->applies_to === 'products') {
            return in_array($variant->product_id, $rule->products()->pluck('products.id')->toArray());
        }

        if ($rule->applies_to === 'categories') {
            return in_array($variant->product->category_id, $rule->categories()->pluck('categories.id')->toArray());
        }

        if ($rule->applies_to === 'collections') {
            $productCollectionIds = $variant->product->collections->pluck('id')->toArray();
            return (bool) array_intersect($rule->collections()->pluck('collections.id')->toArray(), $productCollectionIds);
        }

        return false;
    }
}
