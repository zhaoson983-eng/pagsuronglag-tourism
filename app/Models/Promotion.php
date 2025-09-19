<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_profile_id',
        'title',
        'description',
        'discount_percentage',
        'discount_amount',
        'start_date',
        'end_date',
        'is_active',
        'terms_conditions',
        'max_uses',
        'current_uses',
        'promo_code',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'current_uses' => 'integer',
    ];

    /**
     * Get the business profile that owns the promotion.
     */
    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    /**
     * Get the business through the business profile.
     */
    public function business()
    {
        return $this->hasOneThrough(
            Business::class,
            BusinessProfile::class,
            'id', // Foreign key on BusinessProfile table
            'owner_id', // Foreign key on Business table
            'business_profile_id', // Local key on Promotion table
            'user_id' // Local key on BusinessProfile table
        );
    }

    /**
     * Check if the promotion is currently active.
     */
    public function isActive()
    {
        return $this->is_active && 
               $this->start_date <= now() && 
               $this->end_date >= now() &&
               ($this->max_uses === null || $this->current_uses < $this->max_uses);
    }

    /**
     * Scope a query to only include active promotions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where(function ($q) {
                        $q->whereNull('max_uses')
                          ->orWhereRaw('current_uses < max_uses');
                    });
    }
}
