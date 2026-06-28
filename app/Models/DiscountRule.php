<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class DiscountRule extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'type',                     
        'value',                    
        'buy_quantity',             
        'get_quantity',             
        'min_order_amount',
        'applies_to',               
        'priority',                 
        'starts_at',
        'expires_at',
        'is_active',
        'is_flash_sale',
        'banner_images',
    ];
    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_flash_sale' => 'boolean',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'banner_images' => 'array',
    ];
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'discount_rule_product');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'discount_rule_category');
    }
    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'discount_rule_collection');
    }
    public function isActiveNow(): bool
    {
        return $this->is_active &&
            (!$this->starts_at || now()->gte($this->starts_at)) &&
            (!$this->expires_at || now()->lte($this->expires_at));
    }
    public function getBannerUrlsAttribute(): array
    {
        $banners = [];
        if ($this->banner_images && is_array($this->banner_images)) {
            foreach ($this->banner_images as $banner) {
                if (isset($banner['image'])) {
                    $banner['image_url'] = \Illuminate\Support\Facades\Storage::url($banner['image']);
                } else {
                    $banner['image_url'] = asset('images/logo-main.jpg');
                }
                $banners[] = $banner;
            }
        }
        return $banners;
    }
}