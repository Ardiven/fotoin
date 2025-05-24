<?php
// app/Policies/PortfolioPolicy.php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        // Everyone can view portfolios list
        return true;
    }

    public function view(?User $user, Portfolio $portfolio)
    {
        // Everyone can view individual portfolios if they're published
        // Private portfolios only viewable by owner or admins
        if ($portfolio->is_public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $portfolio->photographer_id 
            || $user->hasRole('admin');
    }

    public function create(User $user)
    {
        // Only photographers can create portfolios
        return $user->hasRole('photographer');
    }

    public function update(User $user, Portfolio $portfolio)
    {
        // Only portfolio owner or admin can update
        return $user->id === $portfolio->photographer_id 
            || $user->hasRole('admin');
    }

    public function delete(User $user, Portfolio $portfolio)
    {
        // Only portfolio owner or admin can delete
        // Cannot delete if there are active bookings
        if ($portfolio->hasActiveBookings()) {
            return false;
        }

        return $user->id === $portfolio->photographer_id 
            || $user->hasRole('admin');
    }

    public function restore(User $user, Portfolio $portfolio)
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Portfolio $portfolio)
    {
        return $user->hasRole('admin');
    }

    public function publish(User $user, Portfolio $portfolio)
    {
        // Only owner can publish/unpublish their portfolio
        return $user->id === $portfolio->photographer_id;
    }

    public function addImage(User $user, Portfolio $portfolio)
    {
        return $user->id === $portfolio->photographer_id;
    }

    public function removeImage(User $user, Portfolio $portfolio)
    {
        return $user->id === $portfolio->photographer_id;
    }
}