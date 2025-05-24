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

        return view('admin.reports', compact('bookingStats', 'revenueStats', 'userStats', 'period'));
    }

    private function getBookingStats($period, $request)
    {
        $query = Booking::query();

        if ($period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        return [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
        ];
    }

    private function getRevenueStats($period, $request)
    {
        $query = Transaction::where('status', 'completed');

        if ($period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        return [
            'total' => $query->sum('amount'),
            'average' => $query->avg('amount'),
            'transactions_count' => $query->count(),
            'top_packages' => Package::withSum(['bookings as total_revenue' => function($query) use ($period, $request) {
                $query->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
                      ->where('transactions.status', 'completed');
                      
                if ($period === 'custom' && $request->start_date && $request->end_date) {
                    $query->whereBetween('transactions.created_at', [$request->start_date, $request->end_date]);
                } elseif ($period === 'month') {
                    $query->whereMonth('transactions.created_at', now()->month);
                } elseif ($period === 'year') {
                    $query->whereYear('transactions.created_at', now()->year);
                }
            }], 'amount')
            ->with('user')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get()
        ];
    }

    private function getUserStats($period, $request)
    {
        $query = User::query();

        if ($period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        return [
            'total_registered' => $query->count(),
            'photographers' => (clone $query)->where('user_type', 'photographer')->count(),
            'customers' => (clone $query)->where('user_type', 'customer')->count(),
            'active_users' => (clone $query)->where('is_active', true)->count(),
            'inactive_users' => (clone $query)->where('is_active', false)->count(),
        ];
    }
}