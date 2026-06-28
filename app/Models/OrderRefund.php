<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderRefund extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id',
        'order_item_id',            
        'amount',
        'reason',
        'status',                   
        'refunded_at',
        'transaction_id',           
        'notes',
        'performed_by',             
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_at' => 'datetime',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function item(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
    public function user(): BelongsTo
    {
        return $this->performedBy();
    }
}
