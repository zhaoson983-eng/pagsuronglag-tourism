<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_profile_id',
        'user_id'
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
