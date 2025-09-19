<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HotelRoom;
use App\Models\ResortRoom;
use App\Models\Cottage;
use App\Models\BusinessProfile;
use App\Models\Rating;

class Business extends Model
{
    use HasFactory;

    protected $table = 'businesses';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'address',
        'contact_number',
        'is_published',
        'delivery_available',
        'delivery_fee',
        'delivery_radius',
        'business_type',
        'average_rating',
        'total_ratings',
        // Hotel and resort specific fields
        'entrance_fee',
        'cottage_fee',
        'check_in_time',
        'check_out_time',
        'policies',
        'amenities',
        'star_rating',
        'has_swimming_pool',
        'has_restaurant',
        'has_parking',
        'has_wifi',
        'latitude',
        'longitude',
        'monday_hours',
        'tuesday_hours',
        'wednesday_hours',
        'thursday_hours',
        'friday_hours',
        'saturday_hours',
        'sunday_hours',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'email',
        'website',
        'phone',
    ];
    
    /**
     * Get the cover image URL.
     */
    public function getCoverImageAttribute()
    {
        return $this->businessProfile->cover_image ?? null;
    }
    
    protected $casts = [
        'is_published' => 'boolean',
        'delivery_available' => 'boolean',
        'delivery_fee' => 'decimal:2',
        'delivery_radius' => 'integer',
        'average_rating' => 'decimal:1',
        'total_ratings' => 'integer',
        'entrance_fee' => 'decimal:2',
        'cottage_fee' => 'decimal:2',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'star_rating' => 'integer',
        'has_swimming_pool' => 'boolean',
        'has_restaurant' => 'boolean',
        'has_parking' => 'boolean',
        'has_wifi' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'amenities' => 'array',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a new business, set the business_type from the owner's profile
        static::creating(function ($business) {
            if ($business->owner && $business->owner->businessProfile) {
                $business->business_type = $business->owner->businessProfile->business_type;
            }
        });
    }

    /**
     * Get the owner of the business.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    /**
     * Get all ratings for the business (only for local product shops).
     */
    public function ratings()
    {
        return $this->hasMany(BusinessRating::class);
    }

    /**
     * Get all likes for the business (only for local product shops).
     */
    public function likes()
    {
        return $this->hasMany(BusinessLike::class);
    }

    /**
     * Get all comments for the business (only for local product shops).
     */
    public function comments()
    {
        return $this->hasMany(BusinessComment::class);
    }

    /**
     * Check if this business is liked by a specific user.
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the likes count attribute.
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Update the average rating and total ratings.
     */
    public function updateRating()
    {
        $ratings = $this->ratings();
        $this->total_ratings = $ratings->count();
        $this->average_rating = $ratings->avg('rating') ?? 0;
        $this->save();
    }

    /**
     * Get the business profile associated with the business.
     */
    public function businessProfile()
    {
        return $this->hasOne(BusinessProfile::class, 'user_id', 'owner_id');
    }

    /**
     * Get all of the products for the business.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'business_id');
    }

    /**
     * Get all of the orders for the business.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'business_id');
    }

    /**
     * Get all of the hotel rooms for the business.
     */
    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class, 'business_id');
    }

    /**
     * Get all of the resort rooms for the business.
     */
    public function resortRooms()
    {
        return $this->hasMany(ResortRoom::class, 'business_id');
    }

    /**
     * Get all of the cottages for the business.
     */
    public function cottages()
    {
        return $this->hasMany(Cottage::class, 'business_id');
    }

    /**
     * Scope a query to only include shops.
     */
    public function scopeShops($query)
    {
        return $query->where('business_type', 'local_products');
    }

    /**
     * Scope a query to only include hotels.
     */
    public function scopeHotels($query)
    {
        return $query->where('business_type', 'hotel');
    }

    /**
     * Scope a query to only include resorts.
     */
    public function scopeResorts($query)
    {
        return $query->where('business_type', 'resort');
    }

    /**
     * Get the business type.
     */
    public function getTypeAttribute()
    {
        if ($this->businessProfile) {
            return $this->businessProfile->business_type;
        }
        return $this->business_type ?? 'local_products';
    }

    /**
     * Get the rooms of the business.
     */
    public function rooms()
    {
        return $this->hasManyThrough(
            Room::class,
            BusinessProfile::class,
            'user_id', // Foreign key on BusinessProfile table
            'business_profile_id', // Foreign key on Room table
            'owner_id', // Local key on Business table
            'id' // Local key on BusinessProfile table
        );
    }
    
    /**
     * Check if the business is a shop.
     */
    public function isShop()
    {
        return $this->type === 'local_products';
    }

    /**
     * Check if the business is a hotel.
     */
    public function isHotel()
    {
        return $this->type === 'hotel';
    }

    /**
     * Check if the business is a resort.
     */
    public function isResort()
    {
        return $this->type === 'resort';
    }

    /**
     * Get the galleries for the business through business profile.
     */
    public function galleries()
    {
        return $this->hasManyThrough(
            Gallery::class,
            BusinessProfile::class,
            'user_id', // Foreign key on BusinessProfile table
            'business_profile_id', // Foreign key on Gallery table
            'owner_id', // Local key on Business table
            'id' // Local key on BusinessProfile table
        );
    }

    /**
     * Get the promotions for the business through business profile.
     */
    public function promotions()
    {
        return $this->hasManyThrough(
            Promotion::class,
            BusinessProfile::class,
            'user_id', // Foreign key on BusinessProfile table
            'business_profile_id', // Foreign key on Promotion table
            'owner_id', // Local key on Business table
            'id' // Local key on BusinessProfile table
        );
    }
}
