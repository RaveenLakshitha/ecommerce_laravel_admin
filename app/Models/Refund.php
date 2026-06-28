<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class Refund extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'transaction_id',
        'order_id',
        'amount',
        'currency',
        'refund_id',                
        'status',                   
        'reason',
        'requested_by',             
        'approved_by',              
        'notes',
        'gateway_response',
    ];
    protected $casts = [
        'amount'         => 'decimal:2',
        'gateway_response' => 'array',
    ];
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
