<?php

namespace App\Policies;

use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BusinessProfile $businessProfile): bool
    {
        // Admin can view any business profile
        if ($user->isAdmin()) {
            return true;
        }

        // Business owner can view their own profile
        return $user->isBusinessOwner() && $user->id === $businessProfile->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only business owners can create business profiles
        // and they can only have one business profile
        return $user->isBusinessOwner() && !$user->businessProfile()->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BusinessProfile $businessProfile): bool
    {
        // Admin can update any business profile
        if ($user->isAdmin()) {
            return true;
        }

        // Business owner can only update their own profile
        return $user->isBusinessOwner() && $user->id === $businessProfile->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BusinessProfile $businessProfile): bool
    {
        // Only admin can delete business profiles
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can approve a business profile.
     */
    public function approve(User $user, BusinessProfile $businessProfile): bool
    {
        // Only admin can approve business profiles
        // and the business must be pending approval
        return $user->isAdmin() && $businessProfile->isPending();
    }

    /**
     * Determine whether the user can reject a business profile.
     */
    public function reject(User $user, BusinessProfile $businessProfile): bool
    {
        // Only admin can reject business profiles
        // and the business must be pending approval
        return $user->isAdmin() && $businessProfile->isPending();
    }

    /**
     * Determine whether the user can publish/unpublish a business.
     */
    public function togglePublish(User $user, BusinessProfile $businessProfile): bool
    {
        // Only admin can publish/unpublish businesses
        // and the business must be approved
        return $user->isAdmin() && $businessProfile->isApproved();
    }

    /**
     * Determine whether the user can view the business dashboard.
     */
    public function viewDashboard(User $user, BusinessProfile $businessProfile): bool
    {
        // Admin can view any business dashboard
        if ($user->isAdmin()) {
            return true;
        }

        // Business owner can only view their own dashboard
        return $user->isBusinessOwner() && $user->id === $businessProfile->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BusinessProfile $businessProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BusinessProfile $businessProfile): bool
    {
        return false;
    }
}
