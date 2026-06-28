<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PaymentGatewaySetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'gateway',                  
        'is_active',
        'environment',              
        'public_key',
        'secret_key',
        'merchant_id',
        'additional_config',        
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
