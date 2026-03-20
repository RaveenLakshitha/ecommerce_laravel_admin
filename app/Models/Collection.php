<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Curated Collection (e.g. "Summer 2026", "New Arrivals", "Sale", "Ethnic Wear")
 * Used for grouping products in frontend sections/carousels
 */
class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'banner_image_path',
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

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image_path ? Storage::url($this->banner_image_path) : null;
    }

    public function isActiveNow(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return $this->is_active;
        }

        return $this->is_active
            && now()->between($this->start_date, $this->end_date);
    }
}
