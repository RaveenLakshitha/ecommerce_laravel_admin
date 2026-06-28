<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class PromotionCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'promotion_id',
        'code',                     
        'usage_limit',              
        'used_count',
    ];
    protected $casts = [
        'used_count' => 'integer',
    ];
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }
    public function isValid(): bool
    {
        return $this->promotion->isActiveNow()
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
