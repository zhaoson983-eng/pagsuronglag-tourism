<?php

namespace App\Policies;

use App\Models\Cottage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CottagePolicy
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
    public function view(User $user, Cottage $cottage): bool
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
    public function update(User $user, Cottage $cottage): bool
    {
        // Admin can update any cottage
        if ($user->role === 'admin') {
            return true;
        }

        // Business owner can only update cottages belonging to their business
        if ($user->role === 'business_owner') {
            return $cottage->business && $cottage->business->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cottage $cottage): bool
    {
        // Admin can delete any cottage
        if ($user->role === 'admin') {
            return true;
        }

        // Business owner can only delete cottages belonging to their business
        if ($user->role === 'business_owner') {
            return $cottage->business && $cottage->business->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cottage $cottage): bool
    {
        return $this->update($user, $cottage);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cottage $cottage): bool
    {
        return $this->delete($user, $cottage);
    }
}
