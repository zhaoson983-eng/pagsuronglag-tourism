<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_profile_id',
        'name',
        'description',
        'price_per_night',
        'capacity',
        'beds',
        'bathrooms',
        'size_sqm',
        'is_available',
        'amenities',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean',
        'amenities' => 'array',
    ];

    /**
     * Get the business profile that owns the room.
     */
    public function businessProfile(): BelongsTo
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    /**
     * Get the bookings for the room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the room images.
     */
    public function images()
    {
        return $this->hasMany(RoomImage::class)->orderBy('is_primary', 'desc')->orderBy('order');
    }
    
    /**
     * Get the primary image for the room.
     */
    public function primaryImage()
    {
        return $this->hasOne(RoomImage::class)->where('is_primary', true);
    }
}
