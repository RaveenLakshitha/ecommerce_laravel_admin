<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Refund linked to a transaction
 */
class Refund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'amount',
        'currency',
        'refund_id',                // gateway refund reference
        'status',                   // pending, completed, failed
        'reason',
        'requested_by',             // 'customer', 'admin', 'vendor'
        'approved_by',              // admin user id
        'notes',
        'gateway_response',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
