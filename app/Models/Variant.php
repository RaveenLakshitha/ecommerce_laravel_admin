<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Product Variant (specific combination: size + color + material, etc.)
 * Holds per-variant stock, price, SKU for single-warehouse setup
 */
class Variant extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($variant) {
            if (empty($variant->barcode)) {
                // Numeric barcode: ProductID (padded) + VariantID (padded)
                $variant->barcode = str_pad($variant->product_id, 4, '0', STR_PAD_LEFT) . 
                                   str_pad($variant->id, 4, '0', STR_PAD_LEFT);
                $variant->saveQuietly();
            }
        });
    }

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'reserved_quantity',
        'low_stock_threshold',
        'weight_grams',
        'dimensions',
        'barcode',
        'is_default',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'weight_grams' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attribute_value');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getAvailableQuantityAttribute(): int
    {
        return $this->stock_quantity - ($this->reserved_quantity ?? 0);
    }

    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function isInStock(): bool
    {
        return $this->available_quantity > 0;
    }

    public function isLowStock(): bool
    {
        return $this->available_quantity <= ($this->low_stock_threshold ?? 5);
    }
}
