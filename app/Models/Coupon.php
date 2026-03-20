<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',                 // 'percentage' or 'fixed'
        'value',                // e.g. 20 for 20% or 500 for Rs.500
        'min_order_amount',     // minimum cart value to apply
        'max_discount_amount',  // max discount cap (useful for %)
        'usage_limit',          // total times this coupon can be used
        'usage_per_user',       // times per customer
        'starts_at',
        'expires_at',
        'is_active',
        'applies_to',           // 'all', 'specific_products', 'specific_categories'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'coupon_category');
    }

    public function isValid(): bool
    {
        return $this->is_active &&
            (!$this->starts_at || now()->gte($this->starts_at)) &&
            (!$this->expires_at || now()->lte($this->expires_at));
    }

    public function remainingUses(): int
    {
        if ($this->usage_limit === null)
            return 999999;
        return $this->usage_limit - $this->used_count ?? 0;
    }
}