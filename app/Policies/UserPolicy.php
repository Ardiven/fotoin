<?php
// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // Only admins can view user lists
        return $user->hasRole('admin');
    }

    public function view(User $user, User $model)
    {
        // Users can view their own profile
        // Photographers' profiles are public
        // Admin can view all
        if ($user->id === $model->id || $user->hasRole('admin')) {
            return true;
        }

        // Public photographer profiles
        if ($model->hasRole('photographer') && $model->is_profile_public) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        // Only admins can create users directly
        return $user->hasRole('admin');
    }

    public function update(User $user, User $model)
    {
        // Users can update their own profile, admins can update any
        return $user->id === $model->id || $user->hasRole('admin');
    }

    public function delete(User $user, User $model)
    {
        // Only admin can delete users
        // Cannot delete if user has active bookings
        if ($model->hasActiveBookings()) {
            return false;
        }

        return $user->hasRole('admin') && $user->id !== $model->id;
    }

    public function suspend(User $user, User $model)
    {
        // Only admin can suspend users
        return $user->hasRole('admin') && $user->id !== $model->id;
    }

    public function changeRole(User $user, User $model)
    {
        // Only admin can change user roles
        return $user->hasRole('admin') && $user->id !== $model->id;
    }

    public function viewBookings(User $user, User $model)
    {
        // Users can view their own bookings, admins can view all
        return $user->id === $model->id || $user->hasRole('admin');
    }

    public function viewTransactions(User $user, User $model)
    {
        // Users can view their own transactions, admins can view all
        return $user->id === $model->id || $user->hasRole('admin');
    }
}