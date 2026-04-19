<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Saved / reusable payment method (card, PayHere token, PayPal billing agreement, etc.)
 */
class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'gateway',                  // stripe, payhere, paypal, etc.
        'method_type',              // card, bank, mobile_wallet, cod
        'last_four',
        'brand',                    // visa, mastercard, amex...
        'expiry_month',
        'expiry_year',
        'is_default',
        'token',                    // gateway payment method id / token
        'status',                   // active, expired, failed
    ];

    protected $casts = [
        'is_default'    => 'boolean',
        'expiry_month'  => 'integer',
        'expiry_year'   => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
