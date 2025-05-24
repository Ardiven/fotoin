<?php
// app/Providers/EventServiceProvider.php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Custom Events
        \App\Events\BookingCreated::class => [
            \App\Listeners\SendBookingNotification::class,
        ],
        
        \App\Events\BookingConfirmed::class => [
            \App\Listeners\SendBookingConfirmation::class,
        ],
        
        \App\Events\BookingCancelled::class => [
            \App\Listeners\HandleBookingCancellation::class,
        ],
        
        \App\Events\PaymentProcessed::class => [
            \App\Listeners\UpdateBookingStatus::class,
        ],
        
        \App\Events\PaymentFailed::class => [
            \App\Listeners\HandlePaymentFailure::class,
        ],
        
        \App\Events\MessageSent::class => [
            \App\Listeners\NotifyRecipient::class,
        ],
        
        \App\Events\PortfolioViewed::class => [
            \App\Listeners\RecordPortfolioView::class,
        ],
        
        \App\Events\UserRegistered::class => [
            \App\Listeners\SendWelcomeEmail::class,
        ],
        
        \App\Events\PackageUpdated::class => [
            \App\Listeners\NotifyPackageUpdate::class,
        ],
    ];

    public function boot()
    {
        //
    }

    public function shouldDiscoverEvents()
    {
        return false;
    }
}