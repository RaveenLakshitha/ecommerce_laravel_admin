<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'fabric_details',
        'base_price',
        'sale_price',
        'brand_id',
        'category_id',
        'is_featured',
        'is_visible',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
    ];

    // Relationships
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->visible()->latest();
    }

    // Helper: get primary image
    public function primaryImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasManyThrough(
            \App\Models\OrderItem::class,
            \App\Models\Variant::class,
            'product_id',     // foreign key on variants table → references products.id
            'variant_id',     // foreign key on order_items table → references variants.id
            'id',             // local key on products
            'id'              // local key on variants
        );
    }
}
