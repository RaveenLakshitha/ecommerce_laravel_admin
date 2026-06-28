<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'variant_id',
        'product_name_snapshot',    
        'variant_attributes',       
        'quantity',
        'unit_price',
        'subtotal',
        'discount_amount',
        'total',
    ];
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'variant_attributes' => 'array',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
}
