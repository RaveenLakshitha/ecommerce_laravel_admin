<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    public function rates() { return $this->hasMany(ShippingRate::class); }
}
