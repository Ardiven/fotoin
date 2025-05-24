<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPortfolios = Portfolio::with(['user', 'images'])
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $topPhotographers = User::withCount(['portfolios', 'packages'])
            ->where('user_type', 'photographer')
            ->orderBy('portfolios_count', 'desc')
            ->take(6)
            ->get();

        $popularPackages = Package::with(['user'])
            ->withCount('bookings')
            ->where('is_active', true)
            ->orderBy('bookings_count', 'desc')
            ->take(6)
            ->get();

        return view('home', compact('featuredPortfolios', 'topPhotographers', 'popularPackages'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function photographers(Request $request)
    {
        $photographers = User::query()
            ->where('user_type', 'photographer')
            ->with(['portfolios', 'packages'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->location, function ($query, $location) {
                return $query->where('location', 'like', "%{$location}%");
            })
            ->paginate(12);

        return view('photographers', compact('photographers'));
    }

    public function portfolios(Request $request)
    {
        $portfolios = Portfolio::with(['user', 'images'])
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        $categories = Portfolio::distinct()->pluck('category');

        return view('portfolios', compact('portfolios', 'categories'));
    }

    public function packages(Request $request)
    {
        $packages = Package::with(['user'])
            ->where('is_active', true)
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->min_price, function ($query, $price) {
                return $query->where('price', '>=', $price);
            })
            ->when($request->max_price, function ($query, $price) {
                return $query->where('price', '<=', $price);
            })
            ->orderBy('price')
            ->paginate(12);

        $categories = Package::distinct()->pluck('category');

        return view('packages', compact('packages', 'categories'));
    }
}