<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tracks every time a promotion/code is applied (for limits & analytics)
 */
class PromotionUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'promotion_code_id',        // null if automatic promo
        'order_id',
        'customer_id',
        'code_used',
        'discount_amount',
        'applied_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'applied_at'      => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function code(): BelongsTo
    {
        return $this->belongsTo(PromotionCode::class, 'promotion_code_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
