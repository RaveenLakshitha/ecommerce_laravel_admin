<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Refund record (supports partial refunds per order or per item)
 */
class OrderRefund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'order_item_id',            // nullable – if refunding specific item(s)
        'amount',
        'reason',
        'status',                   // 'pending', 'processed', 'failed'
        'refunded_at',
        'transaction_id',           // gateway refund ID (Stripe, PayHere, etc.)
        'notes',
        'performed_by',             // admin user_id
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function user(): BelongsTo
    {
        return $this->performedBy();
    }
}
