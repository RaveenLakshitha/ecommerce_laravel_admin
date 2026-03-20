<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Product reviews & ratings (with moderation)
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_item_id',        // optional - link to specific purchased item
        'product_id',
        'variant_id',           // optional
        'rating',               // 1–5
        'title',
        'content',
        'pros',
        'cons',
        'is_anonymous',
        'status',               // pending, approved, rejected, spam
        'moderated_by',
        'moderated_at',
        'helpful_count',
    ];

    protected $casts = [
        'rating'         => 'integer',
        'is_anonymous'   => 'boolean',
        'helpful_count'  => 'integer',
        'moderated_at'   => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function helpfulVotes(): MorphMany
    {
        return $this->morphMany(HelpfulVote::class, 'voteable');
    }

    public function scopeVisible($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePendingModeration($query)
    {
        return $query->where('status', 'pending');
    }
}
