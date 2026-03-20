<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * General-purpose notes (CRM notes on customers, orders, products...)
 */
class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'notable_id',
        'notable_type',
        'content',
        'author_id',            // admin/staff who wrote it
        'is_internal',          // only visible to staff?
        'pinned',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'pinned'      => 'boolean',
    ];

    public function notable(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
