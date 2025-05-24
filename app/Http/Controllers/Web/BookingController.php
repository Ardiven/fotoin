<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Package;
use App\Events\BookingCreated;
use App\Jobs\SendBookingNotification;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $bookings = Booking::with(['package.user', 'customer', 'transaction'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when(auth()->user()->hasRole('customer'), function ($query) {
                return $query->where('customer_id', auth()->id());
            })
            ->when(auth()->user()->hasRole('photographer'), function ($query) {
                return $query->whereHas('package', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            })
            ->orderBy('booking_date', 'desc')
            ->paginate(15);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $package = null;
        if ($request->package_id) {
            $package = Package::with(['user', 'addons'])->findOrFail($request->package_id);
        }

        return view('bookings.create', compact('package'));
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = Booking::create([
            'customer_id' => auth()->id(),
            'package_id' => $request->package_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'pending',
            'total_amount' => $request->total_amount,
        ]);

        // Create selected addons
        if ($request->has('addon_ids')) {
            $booking->selectedAddons()->attach($request->addon_ids);
        }

        // Dispatch events and jobs
        event(new BookingCreated($booking));
        SendBookingNotification::dispatch($booking);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['package.user', 'customer', 'selectedAddons', 'transaction']);
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);
        $booking->load(['package.addons', 'selectedAddons']);
        return view('bookings.edit', compact('booking'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $booking->update($request->validated());

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'photographer_notes' => 'nullable|string'
        ]);

        $booking->update([
            'status' => $request->status,
            'photographer_notes' => $request->photographer_notes
        ]);

        return back()->with('success', 'Booking status updated successfully');
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        if ($booking->status === 'confirmed') {
            return back()->withErrors(['error' => 'Cannot delete confirmed booking']);
        }

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully');
    }
}