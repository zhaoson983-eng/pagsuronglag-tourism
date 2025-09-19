<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TouristSpotLike extends Model
{
    protected $fillable = [
        'tourist_spot_id',
        'user_id'
    ];

    public function touristSpot(): BelongsTo
    {
        return $this->belongsTo(TouristSpot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
