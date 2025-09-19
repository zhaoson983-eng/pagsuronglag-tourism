<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Business;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'business_type',
        'is_archived'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_archived' => 'boolean',
    ];
    
    /**
     * Get the businesses owned by the user.
     */
    public function businesses()
    {
        return $this->hasMany(Business::class, 'owner_id');
    }
    
    /**
     * Get all ratings made by the user.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role constants
    const ROLE_CUSTOMER = 'customer';
    const ROLE_BUSINESS_OWNER = 'business_owner';
    const ROLE_ADMIN = 'admin';

    // Relationships
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the business profile associated with the user.
     */
    // Relationships
    public function businessProfile()
    {
        return $this->hasOne(BusinessProfile::class);
    }
    
    public function approvedBusinesses()
    {
        return $this->hasMany(BusinessProfile::class, 'approved_by');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }
    
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
    
    public function scopeBusinessOwners($query)
    {
        return $query->where('role', 'business_owner');
    }
    
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }
    
    // Role Checks
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    public function isBusinessOwner(): bool
    {
        return $this->role === 'business_owner';
    }
    
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
    
    // Business Type Checks
    public function isLocalProductOwner()
    {
        return $this->business_type === 'local_products';
    }
    
    public function isHotelOwner()
    {
        return $this->business_type === 'hotel';
    }
    
    public function isResortOwner()
    {
        return $this->business_type === 'resort';
    }
    
    // Account Status
    public function isArchived()
    {
        return $this->is_archived;
    }
    
    public function isActive()
    {
        return !$this->is_archived;
    }
    
    // Redirect based on role
    public function getRedirectRoute()
    {
        if ($this->isAdmin()) {
            return route('admin.dashboard');
        }
        
        if ($this->isBusinessOwner()) {
            if ($this->isLocalProductOwner()) {
                return route('business.dashboard');
            } elseif ($this->isHotelOwner()) {
                return route('hotel.dashboard');
            } elseif ($this->isResortOwner()) {
                return route('resort.dashboard');
            }
        }
        
        return route('customer.dashboard');
    }


    /**
     * Check if the user has an approved business.
     */
    public function hasApprovedBusiness(): bool
    {
        return $this->isBusinessOwner() && 
               $this->businessProfile && 
               $this->businessProfile->isApproved();
    }

    public function business()
    {
        return $this->hasOne(Business::class, 'owner_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')->whereNull('read_at');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}