<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\PortfolioView;

class TrackPortfolioViews
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track views for GET requests to portfolio detail
        if ($request->isMethod('GET') && $request->route('portfolio')) {
            $portfolioId = $request->route('portfolio');
            $portfolio = Portfolio::find($portfolioId);

            if ($portfolio) {
                $this->trackView($portfolio, $request);
            }
        }

        return $response;
    }

    private function trackView(Portfolio $portfolio, Request $request)
    {
        $userId = auth()->id();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Don't track views from the portfolio owner
        if ($userId && $userId === $portfolio->user_id) {
            return;
        }

        // Check if this IP has viewed this portfolio in the last hour
        $recentView = PortfolioView::where('portfolio_id', $portfolio->id)
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>', now()->subHour())
            ->first();

        if (!$recentView) {
            // Create new view record
            PortfolioView::create([
                'portfolio_id' => $portfolio->id,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'viewed_at' => now(),
            ]);

            // Increment view count on portfolio
            $portfolio->increment('view_count');
        }
    }
}