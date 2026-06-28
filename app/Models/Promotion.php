<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',                     
        'description',
        'type',                     
        'value',                    
        'priority',                 
        'is_automatic',             
        'is_active',
        'starts_at',
        'ends_at',
        'minimum_cart_amount',
        'maximum_discount_amount',  
        'usage_limit',              
        'used_count',
        'per_customer_limit',       
    ];
    protected $casts = [
        'value'                  => 'decimal:2',
        'minimum_cart_amount'    => 'decimal:2',
        'maximum_discount_amount' => 'decimal:2',
        'is_automatic'           => 'boolean',
        'is_active'              => 'boolean',
        'starts_at'              => 'datetime',
        'ends_at'                => 'datetime',
        'used_count'             => 'integer',
    ];
    public function codes(): HasMany
    {
        return $this->hasMany(PromotionCode::class);
    }
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'promotable');
    }
    public function categories(): MorphToMany
    {
        return $this->morphedByMany(Category::class, 'promotable');
    }
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'promotion_customer')
                    ->withPivot('uses_count', 'last_used_at');
    }
    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }
    public function isActiveNow(): bool
    {
        $now = now();
        return $this->is_active
            && (!$this->starts_at || $this->starts_at->lte($now))
            && (!$this->ends_at   || $this->ends_at->gte($now));
    }
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
                     ->where(function ($q) use ($now) {
                         $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                     })->where(function ($q) use ($now) {
                         $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                     });
    }
    public function scopeAutomatic($query)
    {
        return $query->where('is_automatic', true);
    }
}
