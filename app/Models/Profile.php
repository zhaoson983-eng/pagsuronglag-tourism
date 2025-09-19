<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'birthday',
        'age',
        'sex',
        'phone_number',
        'address',
        'location',
        'bio',
        'profile_picture',
        'facebook',
        'instagram',
        'twitter',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL to the profile picture.
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }

        return null;
    }
}