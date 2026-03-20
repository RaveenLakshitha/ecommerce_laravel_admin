<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Payment / Transaction (one per payment attempt — success, fail, pending, COD, refund, etc.)
 */
class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'customer_id',              // optional — for guest checkout
        'payment_method_id',
        'gateway',                  // 'stripe', 'payhere', 'paypal', 'cod', 'bank', 'manual'
        'transaction_id',           // gateway's txn id / reference
        'amount',
        'currency',
        'status',                   // pending, processing, completed, failed, refunded, partially_refunded, cancelled
        'payment_type',             // 'sale', 'authorization', 'capture', 'refund', 'payout'
        'is_manual',                // true for COD / bank-transfer / admin-marked
        'notes',
        'failure_reason',
        'metadata',                 // json — gateway response, IP, etc.
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'is_manual'      => 'boolean',
        'metadata'       => 'array',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function isSuccessful(): bool
    {
        return in_array($this->status, ['completed', 'captured']);
    }

    public function scopeCod($query)
    {
        return $query->where('gateway', 'cod');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRefundable($query)
    {
        return $query->whereIn('status', ['completed', 'captured'])
                     ->where('amount', '>', 0);
    }
}
