<?php

namespace App\Policies;

use App\Models\ResortRoom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ResortRoomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ResortRoom $resortRoom): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'business_owner' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ResortRoom $resortRoom): bool
    {
        // Admin can update any resort room
        if ($user->role === 'admin') {
            return true;
        }

        // Business owner can only update rooms belonging to their business
        if ($user->role === 'business_owner') {
            return $resortRoom->business && $resortRoom->business->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ResortRoom $resortRoom): bool
    {
        // Admin can delete any resort room
        if ($user->role === 'admin') {
            return true;
        }

        // Business owner can only delete rooms belonging to their business
        if ($user->role === 'business_owner') {
            return $resortRoom->business && $resortRoom->business->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ResortRoom $resortRoom): bool
    {
        return $this->update($user, $resortRoom);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ResortRoom $resortRoom): bool
    {
        return $this->delete($user, $resortRoom);
    }
}
