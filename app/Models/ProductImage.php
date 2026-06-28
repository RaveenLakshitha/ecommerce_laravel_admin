<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class ProductImage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'product_id',
        'variant_id',
        'file_path',
        'file_name',
        'alt_text',
        'sort_order',
        'is_primary',
    ];
    protected $appends = ['url'];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
    public function getUrlAttribute()
    {
        $mediaUrl = $this->getFirstMediaUrl('images', 'optimized');
        if ($mediaUrl) {
            return $mediaUrl;
        }
        return Storage::url($this->file_path);
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
             ->format('webp')
             ->quality(80)
             ->nonQueued();
        $this->addMediaConversion('thumb')
             ->format('webp')
             ->width(400)
             ->quality(80)
             ->nonQueued();
    }
}
