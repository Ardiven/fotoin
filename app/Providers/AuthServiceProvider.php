<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Portfolio::class => \App\Policies\PortfolioPolicy::class,
        \App\Models\Package::class => \App\Policies\PackagePolicy::class,
        \App\Models\Booking::class => \App\Policies\BookingPolicy::class,
        \App\Models\Chat::class => \App\Policies\ChatPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define additional gates
        Gate::define('admin-access', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('photographer-access', function ($user) {
            return $user->hasRole('photographer');
        });

        Gate::define('customer-access', function ($user) {
            return $user->hasRole('customer');
        });

        // Super admin gate
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}