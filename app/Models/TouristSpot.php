<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TouristSpot extends Model
{
    protected $fillable = [
        'name',
        'description',
        'profile_avatar',
        'cover_image',
        'gallery_images',
        'map_link',
        'location',
        'additional_info',
        'average_rating',
        'total_ratings',
        'is_active',
        'uploaded_by'
    ];

    protected $casts = [
        'average_rating' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function ratings()
    {
        return $this->hasMany(TouristSpotRating::class);
    }

    public function comments()
    {
        return $this->hasMany(TouristSpotComment::class);
    }

    public function likes()
    {
        return $this->hasMany(TouristSpotLike::class);
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function updateRating()
    {
        $ratings = $this->ratings();
        $this->total_ratings = $ratings->count();
        $this->average_rating = $ratings->avg('rating') ?? 0;
        $this->save();
    }
}
