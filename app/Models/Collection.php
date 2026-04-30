<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Curated Collection (e.g. "Summer 2026", "New Arrivals", "Sale", "Ethnic Wear")
 * Used for grouping products in frontend sections/carousels
 */
class Collection extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'banner_url',
        'start_date',
        'end_date',             // for seasonal / flash collections
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Products in this collection
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('sort_order')           // optional: custom order per collection
            ->orderByPivot('sort_order');
    }

    public function isActiveNow(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return $this->is_active;
        }

        return $this->is_active
            && now()->between($this->start_date, $this->end_date);
    }

    public function getBannerUrlAttribute(): ?string
    {
        $mediaUrl = $this->getFirstMediaUrl('images', 'optimized');
        if ($mediaUrl) {
            return $mediaUrl;
        }
        return ($this->attributes['banner_url'] ?? null) ? Storage::url($this->attributes['banner_url']) : null;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
             ->format('webp')
             ->quality(80)
             ->nonQueued();
    }
}
