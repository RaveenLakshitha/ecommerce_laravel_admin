<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'rate_amount' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function zone() { return $this->belongsTo(ShippingZone::class, 'shipping_zone_id'); }
    public function courier() { return $this->belongsTo(Courier::class); }
}
