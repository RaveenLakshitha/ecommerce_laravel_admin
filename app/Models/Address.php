<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Reusable address (customer can save multiple)
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',                 // 'shipping', 'billing'
        'first_name',
        'last_name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'province',
        'postal_code',
        'country',
        'is_default',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
