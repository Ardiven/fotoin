<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Events\BookingCreated;
use App\Jobs\SendBookingNotification;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->with(['package.user', 'customer', 'transaction'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->photographer_id, function ($query, $id) {
                return $query->whereHas('package', function ($q) use ($id) {
                    $q->where('user_id', $id);
                });
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

        return BookingResource::collection($bookings);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully',
            'data' => new BookingResource($booking->load(['package', 'selectedAddons']))
        ], 201);
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['package.user', 'customer', 'selectedAddons', 'transaction']);
        return new BookingResource($booking);
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $booking->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Booking updated successfully',
            'data' => new BookingResource($booking)
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $booking->update([
            'status' => $request->status,
            'photographer_notes' => $request->photographer_notes
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking status updated successfully',
            'data' => new BookingResource($booking)
        ]);
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        if ($booking->status === 'confirmed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete confirmed booking'
            ], 422);
        }

        $booking->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Booking deleted successfully'
        ]);
    }
}