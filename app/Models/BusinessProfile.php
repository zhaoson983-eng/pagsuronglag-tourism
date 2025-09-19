<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Gallery;
use App\Models\BusinessStatistic;
use App\Models\HotelLike;
use App\Models\HotelRating;
use App\Models\HotelComment;
use App\Models\ResortLike;
use App\Models\ResortRating;
use App\Models\ResortComment;

class BusinessProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'description',
        'cover_image',
        'business_permit_path',
        'licenses',
        'status',
        'rejection_reason',
        'contact_number',
        'address',
        'location',
        'city',
        'province',
        'postal_code',
        'website',
        'is_published',
        'facebook_page',
        'published_at',
        'approved_at',
        'approved_by',
        'business_type',
        'email',
        'phone',
        'average_rating',
        'total_ratings'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'licenses' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'average_rating' => 'decimal:2',
        'total_ratings' => 'integer',
    ];

    // Business status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Business type constants
    const TYPE_LOCAL_PRODUCTS = 'local_products';
    const TYPE_HOTEL = 'hotel';
    const TYPE_RESORT = 'resort';

    /**
     * Get the user that owns the business profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the rooms for the business profile.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    
    /**
     * Get all of the cottages for the business profile.
     */
    public function cottages()
    {
        return $this->hasMany(Cottage::class);
    }
    
    /**
     * Get all of the gallery images for the business profile.
     */
    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'business_profile_id');
    }

    /**
     * Get all of the gallery images for the business profile (alias for consistency).
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'business_profile_id');
    }

    /**
     * Get the business associated with this profile.
     */
    public function business()
    {
        return $this->hasOne(Business::class, 'owner_id', 'user_id');
    }

    /**
     * Get the admin who approved this business.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include approved businesses.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Get the business type label.
     */
    public function getBusinessTypeLabelAttribute(): string
    {
        return [
            self::TYPE_LOCAL_PRODUCTS => 'Local Products Shop',
            self::TYPE_HOTEL => 'Hotel',
            self::TYPE_RESORT => 'Resort',
        ][$this->business_type] ?? 'Unknown';
    }

    /**
     * Check if the business is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if the business is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the business is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get all hotel likes for this business profile.
     */
    public function hotelLikes()
    {
        return $this->hasMany(HotelLike::class);
    }

    /**
     * Get all hotel ratings for this business profile.
     */
    public function hotelRatings()
    {
        return $this->hasMany(HotelRating::class);
    }

    /**
     * Get all hotel comments for this business profile.
     */
    public function hotelComments()
    {
        return $this->hasMany(HotelComment::class);
    }

    /**
     * Get all resort likes for this business profile.
     */
    public function resortLikes()
    {
        return $this->hasMany(ResortLike::class);
    }

    /**
     * Get all resort ratings for this business profile.
     */
    public function resortRatings()
    {
        return $this->hasMany(ResortRating::class);
    }

    /**
     * Get all resort comments for this business profile.
     */
    public function resortComments()
    {
        return $this->hasMany(ResortComment::class);
    }

    /**
     * Get unified likes based on business type.
     */
    public function likes()
    {
        if ($this->business_type === self::TYPE_HOTEL) {
            return $this->hotelLikes();
        } elseif ($this->business_type === self::TYPE_RESORT) {
            return $this->resortLikes();
        }
        return collect(); // Empty collection for other types
    }

    /**
     * Get unified ratings based on business type.
     */
    public function ratings()
    {
        if ($this->business_type === self::TYPE_HOTEL) {
            return $this->hotelRatings();
        } elseif ($this->business_type === self::TYPE_RESORT) {
            return $this->resortRatings();
        }
        return collect(); // Empty collection for other types
    }

    /**
     * Get unified comments based on business type.
     */
    public function comments()
    {
        if ($this->business_type === self::TYPE_HOTEL) {
            return $this->hotelComments();
        } elseif ($this->business_type === self::TYPE_RESORT) {
            return $this->resortComments();
        }
        return collect(); // Empty collection for other types
    }

    /**
     * Check if this business profile is liked by a specific user.
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
     * Update the average rating for this business profile.
     */
    public function updateRating()
    {
        if ($this->business_type === self::TYPE_HOTEL) {
            $this->total_ratings = $this->hotelRatings()->count();
            $this->average_rating = $this->hotelRatings()->avg('rating') ?? 0;
        } elseif ($this->business_type === self::TYPE_RESORT) {
            $this->total_ratings = $this->resortRatings()->count();
            $this->average_rating = $this->resortRatings()->avg('rating') ?? 0;
        } else {
            $this->total_ratings = 0;
            $this->average_rating = 0;
        }
        $this->save();
    }
}
