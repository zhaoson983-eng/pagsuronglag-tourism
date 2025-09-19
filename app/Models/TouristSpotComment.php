<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristSpotComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourist_spot_id',
        'user_id',
        'comment',
    ];

    public function touristSpot()
    {
        return $this->belongsTo(TouristSpot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
