<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',              
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'status',               
        'total_orders',
        'total_spent',
        'last_order_at',
        'lifetime_value',
    ];
    protected $casts = [
        'total_orders'   => 'integer',
        'total_spent'    => 'decimal:2',
        'last_order_at'  => 'datetime',
        'lifetime_value' => 'decimal:2',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    public function getTagsListAttribute(): array
    {
        return $this->tags->pluck('name')->toArray();
    }
    public function scopeVip($query)
    {
        return $query->whereHas('tags', fn($q) => $q->where('name', 'VIP'));
    }
    public function scopeFrequentBuyers($query, int $minOrders = 5)
    {
        return $query->where('total_orders', '>=', $minOrders);
    }
}
