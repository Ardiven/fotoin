<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('role:photographer')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $packages = Package::with(['user', 'addons'])
            ->when(auth()->check() && auth()->user()->hasRole('photographer'), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->when(!auth()->check() || !auth()->user()->hasRole('photographer'), function ($query) {
                return $query->where('is_active', true);
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->orderBy('price')
            ->paginate(12);

        $categories = Package::distinct()->pluck('category');

        return view('packages.index', compact('packages', 'categories'));
    }

    public function create()
    {
        return view('packages.create');
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

        return redirect()->route('packages.show', $package)
            ->with('success', 'Package created successfully');
    }

    public function show(Package $package)
    {
        $package->load(['user', 'addons', 'bookings']);
        
        $relatedPackages = Package::with(['user'])
            ->where('category', $package->category)
            ->where('user_id', '!=', $package->user_id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('packages.show', compact('package', 'relatedPackages'));
    }

    public function edit(Package $package)
    {
        $this->authorize('update', $package);
        $package->load('addons');
        return view('packages.edit', compact('package'));
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

        return redirect()->route('packages.show', $package)
            ->with('success', 'Package updated successfully');
    }

    public function destroy(Package $package)
    {
        $this->authorize('delete', $package);

        // Check if package has active bookings
        if ($package->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->withErrors(['error' => 'Cannot delete package with active bookings']);
        }

        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Package deleted successfully');
    }
}