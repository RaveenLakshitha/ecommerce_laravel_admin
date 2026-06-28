<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CustomerAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'type',                 
        'is_default',
        'first_name',
        'last_name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postcode',
        'country',
        'latitude',
        'longitude',
    ];
    protected $casts = [
        'is_default' => 'boolean',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
