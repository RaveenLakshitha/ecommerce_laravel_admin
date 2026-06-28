<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_number',             
        'user_id',                  
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',                   
        'payment_method',           
        'payment_status',           
        'subtotal',
        'discount_amount',
        'coupon_id',
        'coupon_code_used',
        'shipping_amount',
        'tax_amount',
        'total_amount',
        'currency',
        'shipping_address_id',
        'billing_address_id',
        'notes',                    
        'internal_notes',           
        'placed_at',
    ];
    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'placed_at' => 'datetime',
    ];
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }
    public function refunds(): HasMany
    {
        return $this->hasMany(OrderRefund::class);
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class); 
    }
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
    public function getDisplayStatusAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }
    public function canBeRefunded(): bool
    {
        return in_array($this->status, ['delivered', 'shipped'])
            && in_array($this->payment_status, ['paid', 'partially_refunded']);
    }
    public function getRefundedAmountAttribute(): float
    {
        return $this->refunds()->sum('amount');
    }
    public function isFullyRefunded(): bool
    {
        return $this->refunded_amount >= $this->total_amount;
    }
}
