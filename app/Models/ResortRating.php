<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_profile_id',
        'user_id',
        'rating',
        'comment'
    ];

    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
