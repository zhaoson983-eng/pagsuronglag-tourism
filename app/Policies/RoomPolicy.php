<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('business_owner') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Room $room)
    {
        return $user->hasRole('admin') || 
               ($user->hasRole('business_owner') && $user->businessProfile && $user->businessProfile->id === $room->business_profile_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === 'business_owner' && $user->businessProfile;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Room $room)
    {
        // Check if user has admin role
        if ($user->role === 'admin') {
            return true;
        }
        
        // Check if user is business owner and owns this room
        if ($user->role === 'business_owner' && $user->businessProfile) {
            return $user->businessProfile->id === $room->business_profile_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Room $room)
    {
        // Check if user has admin role
        if ($user->role === 'admin') {
            return true;
        }
        
        // Check if user is business owner and owns this room
        if ($user->role === 'business_owner' && $user->businessProfile) {
            return $user->businessProfile->id === $room->business_profile_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Room $room)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Room $room)
    {
        return $user->hasRole('admin');
    }
}
