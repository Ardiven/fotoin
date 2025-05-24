<?php
// app/Policies/PackagePolicy.php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        // Everyone can view active packages
        return true;
    }

    public function view(?User $user, Package $package)
    {
        // Everyone can view active packages
        // Inactive packages only viewable by owner or admin
        if ($package->is_active) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $package->photographer_id 
            || $user->hasRole('admin');
    }

    public function create(User $user)
    {
        // Only photographers can create packages
        return $user->hasRole('photographer');
    }

    public function update(User $user, Package $package)
    {
        // Only package owner can update
        // Cannot update if there are pending bookings
        if ($package->hasPendingBookings()) {
            return false;
        }

        return $user->id === $package->photographer_id;
    }

    public function delete(User $user, Package $package)
    {
        // Cannot delete if there are any bookings
        if ($package->hasBookings()) {
            return false;
        }

        return $user->id === $package->photographer_id 
            || $user->hasRole('admin');
    }

    public function activate(User $user, Package $package)
    {
        return $user->id === $package->photographer_id;
    }

    public function deactivate(User $user, Package $package)
    {
        return $user->id === $package->photographer_id;
    }

    public function duplicate(User $user, Package $package)
    {
        // Photographers can duplicate their own packages
        return $user->id === $package->photographer_id;
    }
}