<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Brand / Designer (e.g. "Nike", "Adidas", "Local Brand XYZ")
 */
class Brand extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }
}
