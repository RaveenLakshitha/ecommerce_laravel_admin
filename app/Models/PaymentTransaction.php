<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id',
        'customer_id',              
        'payment_method_id',
        'gateway',                  
        'transaction_id',           
        'amount',
        'currency',
        'status',                   
        'payment_type',             
        'is_manual',                
        'notes',
        'failure_reason',
        'metadata',                 
    ];
    protected $casts = [
        'amount'         => 'decimal:2',
        'is_manual'      => 'boolean',
        'metadata'       => 'array',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
    public function isSuccessful(): bool
    {
        return in_array($this->status, ['completed', 'captured']);
    }
    public function scopeCod($query)
    {
        return $query->where('gateway', 'cod');
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeRefundable($query)
    {
        return $query->whereIn('status', ['completed', 'captured'])
                     ->where('amount', '>', 0);
    }
}
