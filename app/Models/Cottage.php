<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cottage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_profile_id',
        'cottage_name',
        'cottage_type',
        'price_per_night',
        'capacity',
        'description',
        'is_available'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'capacity' => 'integer',
        'is_available' => 'boolean'
    ];

    /**
     * Get the business profile that owns the cottage.
     */
    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }

    /**
     * Get the business that owns the cottage.
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_profile_id', 'id')
            ->join('business_profiles', 'business_profiles.business_id', '=', 'businesses.id')
            ->select('businesses.*');
    }

    /**
     * Get the galleries for the cottage.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'cottage_id');
    }
}
