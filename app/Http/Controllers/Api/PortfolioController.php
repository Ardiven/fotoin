<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\StorePortfolioRequest;
use App\Http\Requests\Portfolio\UpdatePortfolioRequest;
use App\Http\Requests\Portfolio\UploadImageRequest;
use App\Http\Resources\PortfolioResource;
use App\Http\Resources\PortfolioCollection;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use App\Events\PortfolioViewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $portfolios = Portfolio::query()
            ->with(['user', 'images', 'views'])
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->photographer_id, function ($query, $id) {
                return $query->where('user_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return new PortfolioCollection($portfolios);
    }

    public function store(StorePortfolioRequest $request)
    {
        $portfolio = Portfolio::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'is_featured' => $request->is_featured ?? false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Portfolio created successfully',
            'data' => new PortfolioResource($portfolio)
        ], 201);
    }

    public function show(Portfolio $portfolio)
    {
        $portfolio->load(['user', 'images', 'views']);
        
        // Track portfolio view
        event(new PortfolioViewed($portfolio, auth()->user()));

        return new PortfolioResource($portfolio);
    }

    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);

        $portfolio->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Portfolio updated successfully',
            'data' => new PortfolioResource($portfolio)
        ]);
    }

    public function destroy(Portfolio $portfolio)
    {
        $this->authorize('delete', $portfolio);

        // Delete associated images
        foreach ($portfolio->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $portfolio->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Portfolio deleted successfully'
        ]);
    }

    public function uploadImages(UploadImageRequest $request, Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);

        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('portfolios', 'public');
            
            $portfolioImage = PortfolioImage::create([
                'portfolio_id' => $portfolio->id,
                'image_path' => $path,
                'caption' => $request->caption ?? null,
            ]);

            $uploadedImages[] = $portfolioImage;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Images uploaded successfully',
            'data' => $uploadedImages
        ]);
    }

    public function deleteImage(Portfolio $portfolio, PortfolioImage $image)
    {
        $this->authorize('update', $portfolio);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Image deleted successfully'
        ]);
    }
}