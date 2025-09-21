<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Business;
use App\Models\Order;

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
    
    /**
     * Get all messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get all messages received by the user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get all messages (both sent and received) for the user.
     */
    public function allMessages()
    {
        return Message::where(function($query) {
            $query->where('sender_id', $this->id)
                  ->orWhere('receiver_id', $this->id);
        });
    }
    
    /**
     * Get all threads (conversations) for the user.
     */
    public function threads()
    {
        // Get all unique user IDs that the current user has messaged with
        $sentTo = $this->sentMessages()->select('receiver_id as user_id');
        $receivedFrom = $this->receivedMessages()->select('sender_id as user_id');
        
        $userIds = $sentTo->union($receivedFrom)->pluck('user_id')->unique();
        
        // Return users with their last message
        return User::whereIn('id', $userIds)
            ->with(['sentMessages' => function($query) {
                $query->where('receiver_id', $this->id)
                    ->orWhere('sender_id', $this->id)
                    ->latest()
                    ->limit(1);
            }, 'receivedMessages' => function($query) {
                $query->where('receiver_id', $this->id)
                    ->orWhere('sender_id', $this->id)
                    ->latest()
                    ->limit(1);
            }])
            ->get()
            ->map(function($user) {
                $lastSent = $user->sentMessages->first();
                $lastReceived = $user->receivedMessages->first();
                
                // Get the most recent message between the two users
                if ($lastSent && $lastReceived) {
                    $lastMessage = $lastSent->created_at->gt($lastReceived->created_at) ? $lastSent : $lastReceived;
                } else {
                    $lastMessage = $lastSent ?? $lastReceived;
                }
                
                $user->last_message = $lastMessage;
                $user->last_message_time = $lastMessage ? $lastMessage->created_at : now();
                return $user;
            })
            ->sortByDesc('last_message_time');
    }
    
    /**
     * Get unread messages count.
     */
    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')
            ->whereNull('read_at');
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
    /**
     * Get all orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
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

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}