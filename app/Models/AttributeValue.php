<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AttributeValue extends Model
{
    use HasFactory;
    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color_hex',    
        'sort_order',
    ];
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
    public function variants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'variant_attribute_value');
    }
}
