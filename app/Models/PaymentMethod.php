<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'gateway',                  
        'method_type',              
        'last_four',
        'brand',                    
        'expiry_month',
        'expiry_year',
        'is_default',
        'token',                    
        'status',                   
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
