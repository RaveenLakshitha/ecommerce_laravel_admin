<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Courier / Delivery Provider (DOMEX, Prompt, etc.)
 */
class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_path',
        'api_key',
        'api_secret',
        'base_url',
        'is_active',
        'supports_tracking',
        'supports_label_generation',
        'supports_cod',
        'default_for_cod',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'supports_tracking' => 'boolean',
        'supports_label_generation' => 'boolean',
        'supports_cod' => 'boolean',
        'default_for_cod' => 'boolean',
    ];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(ShippingRate::class);
    }
}
