<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DiscountRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',                     // 'percentage', 'fixed', 'bogo', 'buy_x_get_y'
        'value',                    // discount amount or percentage
        'buy_quantity',             // for BOGO: buy this many
        'get_quantity',             // get this many free/cheaper
        'min_order_amount',
        'applies_to',               // 'all', 'products', 'categories', 'collections'
        'priority',                 // higher = applied first
        'starts_at',
        'expires_at',
        'is_active',
        'is_flash_sale',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_flash_sale' => 'boolean',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'discount_rule_product');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'discount_rule_category');
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'discount_rule_collection');
    }

    public function isActiveNow(): bool
    {
        return $this->is_active &&
            (!$this->starts_at || now()->gte($this->starts_at)) &&
            (!$this->expires_at || now()->lte($this->expires_at));
    }
}