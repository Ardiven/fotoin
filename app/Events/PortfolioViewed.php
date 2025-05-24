<?php
// app/Events/PortfolioViewed.php

namespace App\Events;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PortfolioViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Portfolio $portfolio,
        public ?User $viewer = null,
        public ?string $ipAddress = null,
        public ?string $userAgent = null
    ) {}
}