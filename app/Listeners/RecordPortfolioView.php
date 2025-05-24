<?php

namespace App\Listeners;

use App\Events\PortfolioViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordPortfolioView
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PortfolioViewed $event): void
    {
        //
    }
}
