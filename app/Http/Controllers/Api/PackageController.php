<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PackageCollection;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::query()
            ->with(['user', 'addons'])
            ->when($request->photographer_id, function ($query, $id) {
                return $query->where('user_id', $id);
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->min_price, function ($query, $price) {
                return $query->where('price', '>=', $price);
            })
            ->when($request->max_price, function ($query, $price) {
                return $query->where('price', '<=', $price);
            })
            ->where('is_active', true)
            ->orderBy('price', 'asc')
            ->paginate(12);

        return new PackageCollection($packages);
    }

    public function store(StorePackageRequest $request)
    {
        $package = Package::create([
            'user_id' => auth()->id(),
            ...$request->validated()
        ]);

        // Create addons if provided
        if ($request->has('addons')) {
            foreach ($request->addons as $addon) {
                $package->addons()->create($addon);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Package created successfully',
            'data' => new PackageResource($package->load('addons'))
        ], 201);
    }

    public function show(Package $package)
    {
        $package->load(['user', 'addons', 'bookings']);
        return new PackageResource($package);
    }

    public function update(UpdatePackageRequest $request, Package $package)
    {
        $this->authorize('update', $package);

        $package->update($request->validated());

        // Update addons if provided
        if ($request->has('addons')) {
            $package->addons()->delete();
            foreach ($request->addons as $addon) {
                $package->addons()->create($addon);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Package updated successfully',
            'data' => new PackageResource($package->load('addons'))
        ]);
    }

    public function destroy(Package $package)
    {
        $this->authorize('delete', $package);

        // Check if package has active bookings
        if ($package->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete package with active bookings'
            ], 422);
        }

        $package->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Package deleted successfully'
        ]);
    }
}