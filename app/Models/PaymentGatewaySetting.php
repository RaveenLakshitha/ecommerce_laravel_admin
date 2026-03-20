<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Payment gateway configuration per store / environment
 * (useful when supporting multiple gateways or multi-tenant)
 */
class PaymentGatewaySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'gateway',                  // stripe, payhere, paypal, ...
        'is_active',
        'environment',              // live, sandbox
        'public_key',
        'secret_key',
        'merchant_id',
        'additional_config',        // json — webhook secret, currency, etc.
        'minimum_amount',
        'maximum_amount',
        'supported_currencies',
        'logo',
        'display_name',
        'description',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'additional_config'   => 'array',
        'supported_currencies' => 'array',
        'minimum_amount'      => 'decimal:2',
        'maximum_amount'      => 'decimal:2',
    ];

    public function isLive(): bool
    {
        return $this->environment === 'live';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
