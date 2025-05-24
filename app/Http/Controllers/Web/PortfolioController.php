<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\StorePortfolioRequest;
use App\Http\Requests\Portfolio\UpdatePortfolioRequest;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use App\Events\PortfolioViewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('role:photographer')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $portfolios = Portfolio::with(['user', 'images'])
            ->when(auth()->check() && auth()->user()->hasRole('photographer'), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->latest()
            ->paginate(12);

        $categories = Portfolio::distinct()->pluck('category');

        return view('portfolios.index', compact('portfolios', 'categories'));
    }

    public function create()
    {
        return view('portfolios.create');
    }

    public function store(StorePortfolioRequest $request)
    {
        $portfolio = Portfolio::create([
            'user_id' => auth()->id(),
            ...$request->validated()
        ]);

        return redirect()->route('portfolios.show', $portfolio)
            ->with('success', 'Portfolio created successfully');
    }

    public function show(Portfolio $portfolio)
    {
        $portfolio->load(['user', 'images', 'views']);
        
        // Track portfolio view
        if (auth()->check()) {
            event(new PortfolioViewed($portfolio, auth()->user()));
        }

        $relatedPortfolios = Portfolio::with(['user', 'images'])
            ->where('category', $portfolio->category)
            ->where('id', '!=', $portfolio->id)
            ->take(4)
            ->get();

        return view('portfolios.show', compact('portfolio', 'relatedPortfolios'));
    }

    public function edit(Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);
        return view('portfolios.edit', compact('portfolio'));
    }

    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);

        $portfolio->update($request->validated());

        return redirect()->route('portfolios.show', $portfolio)
            ->with('success', 'Portfolio updated successfully');
    }

    public function destroy(Portfolio $portfolio)
    {
        $this->authorize('delete', $portfolio);

        // Delete associated images
        foreach ($portfolio->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $portfolio->delete();

        return redirect()->route('portfolios.index')
            ->with('success', 'Portfolio deleted successfully');
    }

    public function uploadImages(Request $request, Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);

        $request->validate([
            'images.*' => 'required|image|max:5120', // 5MB max
            'captions.*' => 'nullable|string|max:255'
        ]);

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('portfolios', 'public');
            
            PortfolioImage::create([
                'portfolio_id' => $portfolio->id,
                'image_path' => $path,
                'caption' => $request->captions[$index] ?? null,
            ]);
        }

        return back()->with('success', 'Images uploaded successfully');
    }

    public function deleteImage(Portfolio $portfolio, PortfolioImage $image)
    {
        $this->authorize('update', $portfolio);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully');
    }
}