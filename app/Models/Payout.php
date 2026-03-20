<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Vendor / seller payout (for multi-vendor marketplaces)
 */
class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',                // or seller_id / user_id
        'period_start',
        'period_end',
        'total_sales',
        'total_commission',         // platform fee
        'total_refunds',
        'net_amount',
        'status',                   // pending, approved, paid, failed
        'payment_method',
        'transaction_id',           // outgoing payment reference
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'total_sales'     => 'decimal:2',
        'total_commission' => 'decimal:2',
        'total_refunds'   => 'decimal:2',
        'net_amount'      => 'decimal:2',
        'period_start'    => 'date',
        'period_end'      => 'date',
        'paid_at'         => 'datetime',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class); // or User if vendors are users
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class, 'transaction_id');
    }

    public function scopePayable($query)
    {
        return $query->where('status', 'approved')
                     ->where('net_amount', '>', 0);
    }
}
