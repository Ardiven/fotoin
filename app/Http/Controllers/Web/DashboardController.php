<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('photographer')) {
            return $this->photographerDashboard();
        } elseif ($user->hasRole('customer')) {
            return $this->customerDashboard();
        }

        return redirect()->route('home');
    }

    protected function photographerDashboard()
    {
        $stats = [
            'total_portfolios' => Portfolio::where('user_id', auth()->id())->count(),
            'total_packages' => Package::where('user_id', auth()->id())->count(),
            'total_bookings' => Booking::whereHas('package', function ($query) {
                $query->where('user_id', auth()->id());
            })->count(),
            'pending_bookings' => Booking::whereHas('package', function ($query) {
                $query->where('user_id', auth()->id());
            })->where('status', 'pending')->count(),
        ];

        $recentBookings = Booking::with(['package', 'customer'])
            ->whereHas('package', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->take(5)
            ->get();

        $monthlyEarnings = Transaction::whereHas('booking.package', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        return view('dashboard.photographer', compact('stats', 'recentBookings', 'monthlyEarnings'));
    }

    protected function customerDashboard()
    {
        $stats = [
            'total_bookings' => Booking::where('customer_id', auth()->id())->count(),
            'pending_bookings' => Booking::where('customer_id', auth()->id())->where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('customer_id', auth()->id())->where('status', 'completed')->count(),
            'total_spent' => Transaction::whereHas('booking', function ($query) {
                $query->where('customer_id', auth()->id());
            })->where('status', 'completed')->sum('amount'),
        ];

        $recentBookings = Booking::with(['package.user'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $upcomingBookings = Booking::with(['package.user'])
            ->where('customer_id', auth()->id())
            ->where('booking_date', '>=', now()->toDateString())
            ->where('status', 'confirmed')
            ->orderBy('booking_date')
            ->take(3)
            ->get();

        return view('dashboard.customer', compact('stats', 'recentBookings', 'upcomingBookings'));
    }
}