<?php
// app/Listeners/RecordPortfolioView.php

namespace App\Listeners;

use App\Events\PortfolioViewed;
use App\Models\PortfolioView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordPortfolioView implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PortfolioViewed $event)
    {
        $portfolio = $event->portfolio;
        $viewer = $event->viewer;
        $ipAddress = $event->ipAddress;
        $userAgent = $event->userAgent;
        
        // Don't record views from the portfolio owner
        if ($viewer && $viewer->id === $portfolio->photographer_id) {
            return;
        }
        
        // Check if this IP has viewed this portfolio recently (prevent spam)
        $recentView = PortfolioView::where('portfolio_id', $portfolio->id)
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>', now()->subHours(1))
            ->exists();
            
        if ($recentView) {
            return;
        }
        
        // Record the view
        PortfolioView::create([
            'portfolio_id' => $portfolio->id,
            'viewer_id' => $viewer?->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'viewed_at' => now()
        ]);
        
        // Update portfolio view count
        $portfolio->increment('views_count');
        
        // Log activity
        activity()
            ->causedBy($viewer)
            ->performedOn($portfolio)
            ->withProperties([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ])
            ->log('Portfolio viewed');
    }
}