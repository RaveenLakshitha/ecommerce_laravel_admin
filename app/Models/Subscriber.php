<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Subscriber extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'status',               
        'source',               
        'subscribed_at',
        'unsubscribed_at',
        'customer_id',          
    ];
    protected $casts = [
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'subscribed')
                     ->whereNull('unsubscribed_at');
    }
}
