<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTrackingEvent extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function shipment() { return $this->belongsTo(Shipment::class); }
}
