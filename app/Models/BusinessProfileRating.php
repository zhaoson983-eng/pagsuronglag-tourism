<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessProfileRating extends Model
{
    protected $fillable = [
        'business_profile_id',
        'user_id',
        'rating'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    public function businessProfile(): BelongsTo
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
