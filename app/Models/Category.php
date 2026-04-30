<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'parent_id', 'description', 'image', 'banner_images', 'meta_title', 'meta_description', 'is_active'];

    protected $casts = [
        'banner_images' => 'array',
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // For nested categories if needed
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getImageUrlAttribute()
    {
        $mediaUrl = $this->getFirstMediaUrl('images', 'optimized');
        if ($mediaUrl) {
            return $mediaUrl;
        }
        return $this->image ? \Illuminate\Support\Facades\Storage::url($this->image) : asset('images/logo-main.jpg');
    }

    public function getBannerUrlsAttribute()
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
        
        if (empty($banners)) {
            return []; // Return empty instead of just one default image path now.
        }

        return $banners;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
             ->format('webp')
             ->quality(80)
             ->nonQueued();
    }
}
