<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Brand / Designer (e.g. "Nike", "Adidas", "Local Brand XYZ")
 */
class Brand extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_path',
        'website_url',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    /**
     * Products belonging to this brand
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        $mediaUrl = $this->getFirstMediaUrl('images', 'optimized');
        if ($mediaUrl) {
            return $mediaUrl;
        }
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
             ->format('webp')
             ->quality(80)
             ->nonQueued();
    }
}
