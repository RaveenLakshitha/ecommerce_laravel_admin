<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function courier() { return $this->belongsTo(Courier::class); }
    public function pickupLocation() { return $this->belongsTo(PickupLocation::class); }
    public function trackingEvents() { return $this->hasMany(ShipmentTrackingEvent::class)->orderBy('timestamp', 'desc'); }
}
