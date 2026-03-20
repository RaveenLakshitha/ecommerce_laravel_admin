<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Tag for filtering & SEO (e.g. "cotton", "casual", "party wear", "eco-friendly")
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',          // optional short explanation
        'color_hex',            // optional for visual tags
        'sort_order',
    ];

    /**
     * Products with this tag
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
