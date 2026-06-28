<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class InventoryTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'variant_id',
        'quantity_change',
        'type',                 
        'reference_id',
        'reference_type',
        'notes',
        'performed_by',
    ];
    protected $casts = [
        'quantity_change' => 'integer',
    ];
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
    public function reference()
    {
        return $this->morphTo();
    }
}
