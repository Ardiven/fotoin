<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Portfolio;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Basic stats
        $stats = [
            'total_users' => User::count(),
            'total_photographers' => User::where('user_type', 'photographer')->count(),
            'total_customers' => User::where('user_type', 'customer')->count(),
            'total_portfolios' => Portfolio::count(),
            'total_packages' => Package::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
        ];

        // Monthly user registrations
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Monthly bookings
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Monthly revenue
        $monthlyRevenue = Transaction::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent activities
        $recentBookings = Booking::with(['package.user', 'customer'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()
            ->take(10)
            ->get();

        // Top photographers by bookings
        $topPhotographers = User::withCount('bookings')
            ->where('user_type', 'photographer')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyUsers',
            'monthlyBookings', 
            'monthlyRevenue',
            'recentBookings',
            'recentUsers',
            'topPhotographers'
        ));
    }

    public function reports(Request $request)
    {
        $period = $request->get('period', 'month'); // month, year, custom
        
        $bookingStats = $this->getBookingStats($period, $request);
        $revenueStats = $this->getRevenueStats($period, $request);
        $userStats = $this->getUserStats($period, $request);
        $packageStats = $this->getPackageStats($period, $request);

        return view('admin.reports', compact('bookingStats', 'revenueStats', 'userStats', 'packageStats', 'period'));
    }

    private function getBookingStats($period, $request)
    {
        $query = Booking::query();

        if ($period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        $statusCounts = (clone $query)->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Popular booking times
        $popularTimes = (clone $query)->selectRaw('HOUR(booking_time) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Most booked packages
        $popularPackages = (clone $query)->with('package')
            ->selectRaw('package_id, COUNT(*) as booking_count')
            ->groupBy('package_id')
            ->orderBy('booking_count', 'desc')
            ->take(10)
            ->get();

        return [
            'total' => $query->count(),
            'pending' => $statusCounts['pending'] ?? 0,
            'confirmed' => $statusCounts['confirmed'] ?? 0,
            'completed' => $statusCounts['completed'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0,
            'popular_times' => $popularTimes,
            'popular_packages' => $popularPackages,
            'status_distribution' => $statusCounts,
        ];
    }

    private function getRevenueStats($period, $request)
    {
        $query = Transaction::where('status', 'completed');

        if ($period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        $totalRevenue = $query->sum('amount');
        $averageTransaction = $query->avg('amount');
        $transactionCount = $query->count();

        // Revenue by photographer
        $revenueByPhotographer = $query->with('booking.package.user')
            ->get()
            ->groupBy('booking.package.user.id')
            ->map(function ($transactions, $photographerId) {
                $photographer = $transactions->first()->booking->package->user;
                return [
                    'photographer' => $photographer,
                    'total_revenue' => $transactions->sum('amount'),
                    'transaction_count' => $transactions->count(),
                ];
            })
            ->sortByDesc('total_revenue')
            ->take(10);

        // Daily revenue for charts
        $dailyRevenue = (clone $query)->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_revenue' => $totalRevenue,
            'average_transaction' => $averageTransaction,
            'transaction_count' => $transactionCount,
            'revenue_by_photographer' => $revenueByPhotographer,
            'daily_revenue' => $dailyRevenue,
        ];
    }

    private function getUserStats($period, $request)
    {
        $query = User::query();

        if ($period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        $userTypeCounts = (clone $query)->selectRaw('user_type, COUNT(*) as count')
            ->groupBy('user_type')
            ->pluck('count', 'user_type');

        // User activity stats
        $activeUsers = User::whereHas('bookings', function ($q) use ($period, $request) {
            if ($period === 'custom' && $request->start_date && $request->end_date) {
                $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
            } elseif ($period === 'month') {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            } elseif ($period === 'year') {
                $q->whereYear('created_at', now()->year);
            }
        })->count();

        // Most active customers
        $activeCustomers = User::withCount(['bookings' => function ($q) use ($period, $request) {
            if ($period === 'custom' && $request->start_date && $request->end_date) {
                $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
            } elseif ($period === 'month') {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            } elseif ($period === 'year') {
                $q->whereYear('created_at', now()->year);
            }
        }])
        ->where('user_type', 'customer')
        ->orderBy('bookings_count', 'desc')
        ->take(10)
        ->get();

        return [
            'total_new_users' => $query->count(),
            'new_photographers' => $userTypeCounts['photographer'] ?? 0,
            'new_customers' => $userTypeCounts['customer'] ?? 0,
            'active_users' => $activeUsers,
            'active_customers' => $activeCustomers,
            'user_type_distribution' => $userTypeCounts,
        ];
    }

    private function getPackageStats($period, $request)
    {
        $query = Package::query();

        if ($period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        $totalPackages = $query->count();
        $activePackages = (clone $query)->where('is_active', true)->count();

        // Packages by category
        $packagesByCategory = (clone $query)->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        // Average package price
        $averagePrice = $query->avg('price');

        // Most expensive packages
        $expensivePackages = (clone $query)->with('user')
            ->orderBy('price', 'desc')
            ->take(10)
            ->get();

        // Package performance (with booking counts)
        $packagePerformance = Package::withCount('bookings')
            ->with('user')
            ->orderBy('bookings_count', 'desc')
            ->take(10)
            ->get();

        return [
            'total_packages' => $totalPackages,
            'active_packages' => $activePackages,
            'packages_by_category' => $packagesByCategory,
            'average_price' => $averagePrice,
            'expensive_packages' => $expensivePackages,
            'package_performance' => $packagePerformance,
        ];
    }

    public function analytics(Request $request)
    {
        // Advanced analytics data
        $dateRange = $request->get('range', 30); // days

        // User growth trend
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($dateRange))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Booking conversion rate (views to bookings)
        $portfolioViews = DB::table('portfolio_views')
            ->where('created_at', '>=', now()->subDays($dateRange))
            ->count();

        $bookingsFromViews = Booking::where('created_at', '>=', now()->subDays($dateRange))
            ->count();

        $conversionRate = $portfolioViews > 0 ? ($bookingsFromViews / $portfolioViews) * 100 : 0;

        // Peak booking hours
        $peakHours = Booking::selectRaw('HOUR(booking_time) as hour, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($dateRange))
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->get();

        // Photographer ratings analysis
        $photographerRatings = DB::table('reviews')
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as review_count, photographer_id')
            ->where('created_at', '>=', now()->subDays($dateRange))
            ->groupBy('photographer_id')
            ->having('review_count', '>=', 5) // Only photographers with 5+ reviews
            ->orderBy('avg_rating', 'desc')
            ->take(10)
            ->get();

        // Location-based booking analysis
        $popularLocations = Booking::selectRaw('location, COUNT(*) as booking_count')
            ->where('created_at', '>=', now()->subDays($dateRange))
            ->whereNotNull('location')
            ->groupBy('location')
            ->orderBy('booking_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics', compact(
            'userGrowth',
            'conversionRate',
            'peakHours',
            'photographerRatings',
            'popularLocations',
            'dateRange'
        ));
    }

    public function systemHealth()
    {
        // System health metrics
        $metrics = [
            'database_size' => $this->getDatabaseSize(),
            'storage_usage' => $this->getStorageUsage(),
            'cache_status' => $this->getCacheStatus(),
            'queue_status' => $this->getQueueStatus(),
            'error_rate' => $this->getErrorRate(),
            'average_response_time' => $this->getAverageResponseTime(),
        ];

        // Recent errors
        $recentErrors = DB::table('failed_jobs')
            ->latest()
            ->take(10)
            ->get();

        // System logs
        $systemLogs = DB::table('admin_logs')
            ->where('action', 'system_action')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.system-health', compact('metrics', 'recentErrors', 'systemLogs'));
    }

    private function getDatabaseSize()
    {
        try {
            $result = DB::select('SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
                FROM information_schema.tables 
                WHERE table_schema = ?', [config('database.connections.mysql.database')]);
            
            return $result[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getStorageUsage()
    {
        try {
            $storagePath = storage_path('app/public');
            $sizeInBytes = $this->getFolderSize($storagePath);
            return round($sizeInBytes / 1024 / 1024, 2); // Convert to MB
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getFolderSize($dir)
    {
        $size = 0;
        if (is_dir($dir)) {
            foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
                $size += is_file($each) ? filesize($each) : $this->getFolderSize($each);
            }
        }
        return $size;
    }

    private function getCacheStatus()
    {
        try {
            cache()->put('health_check', 'ok', 60);
            return cache()->get('health_check') === 'ok' ? 'Healthy' : 'Issues Detected';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    private function getQueueStatus()
    {
        try {
            $failedJobs = DB::table('failed_jobs')->count();
            $pendingJobs = DB::table('jobs')->count();
            
            return [
                'failed' => $failedJobs,
                'pending' => $pendingJobs,
                'status' => $failedJobs > 10 ? 'Issues' : 'Healthy'
            ];
        } catch (\Exception $e) {
            return ['status' => 'Error'];
        }
    }

    private function getErrorRate()
    {
        try {
            $totalRequests = DB::table('admin_logs')
                ->where('created_at', '>=', now()->subHour())
                ->count();
            
            $errorRequests = DB::table('failed_jobs')
                ->where('created_at', '>=', now()->subHour())
                ->count();
            
            return $totalRequests > 0 ? round(($errorRequests / $totalRequests) * 100, 2) : 0;
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getAverageResponseTime()
    {
        // This would typically come from application performance monitoring
        // For now, return a placeholder
        return '200ms';
    }

    public function exportData(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'users':
                return $this->exportUsers($format);
            case 'bookings':
                return $this->exportBookings($format);
            case 'transactions':
                return $this->exportTransactions($format);
            case 'portfolios':
                return $this->exportPortfolios($format);
            default:
                return redirect()->back()->with('error', 'Invalid export type');
        }
    }

    private function exportUsers($format)
    {
        $users = User::with('roles')->get();
        
        if ($format === 'csv') {
            $filename = 'users_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Name', 'Email', 'User Type', 'Role', 'Created At', 'Status']);

                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->user_type,
                        $user->roles->pluck('name')->implode(', '),
                        $user->created_at->format('Y-m-d H:i:s'),
                        $user->is_active ? 'Active' : 'Inactive'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Add JSON export option
        if ($format === 'json') {
            $filename = 'users_' . now()->format('Y-m-d_H-i-s') . '.json';
            
            return response()->json($users->toArray())
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }
    }

    private function exportBookings($format)
    {
        $bookings = Booking::with(['package.user', 'customer'])->get();
        
        if ($format === 'csv') {
            $filename = 'bookings_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                fputcsv($file, [
                    'ID', 'Customer', 'Photographer', 'Package', 'Booking Date', 
                    'Status', 'Total Amount', 'Created At'
                ]);

                foreach ($bookings as $booking) {
                    fputcsv($file, [
                        $booking->id,
                        $booking->customer->name,
                        $booking->package->user->name,
                        $booking->package->name,
                        $booking->booking_date,
                        $booking->status,
                        $booking->total_amount,
                        $booking->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json($bookings->toArray());
    }

    private function exportTransactions($format)
    {
        $transactions = Transaction::with('booking.customer')->get();
        
        if ($format === 'csv') {
            $filename = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($transactions) {
                $file = fopen('php://output', 'w');
                fputcsv($file, [
                    'ID', 'Customer', 'Amount', 'Status', 'Payment Method', 
                    'Transaction ID', 'Created At'
                ]);

                foreach ($transactions as $transaction) {
                    fputcsv($file, [
                        $transaction->id,
                        $transaction->booking->customer->name,
                        $transaction->amount,
                        $transaction->status,
                        $transaction->payment_method,
                        $transaction->transaction_id,
                        $transaction->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json($transactions->toArray());
    }

    private function exportPortfolios($format)
    {
        $portfolios = Portfolio::with('user')->get();
        
        if ($format === 'csv') {
            $filename = 'portfolios_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($portfolios) {
                $file = fopen('php://output', 'w');
                fputcsv($file, [
                    'ID', 'Title', 'Photographer', 'Category', 'Location', 
                    'Featured', 'Created At'
                ]);

                foreach ($portfolios as $portfolio) {
                    fputcsv($file, [
                        $portfolio->id,
                        $portfolio->title,
                        $portfolio->user->name,
                        $portfolio->category,
                        $portfolio->location,
                        $portfolio->is_featured ? 'Yes' : 'No',
                        $portfolio->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json($portfolios->toArray());
    }
}