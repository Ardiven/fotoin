<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AvailabilityResource;
use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $availabilities = Availability::query()
            ->when($request->photographer_id, function ($query, $id) {
                return $query->where('user_id', $id);
            })
            ->when(auth()->user()->hasRole('photographer'), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('available_date', $date);
            })
            ->when($request->month, function ($query, $month) {
                return $query->whereMonth('available_date', $month);
            })
            ->where('available_date', '>=', now()->toDateString())
            ->orderBy('available_date')
            ->paginate(30);

        return AvailabilityResource::collection($availabilities);
    }

    public function store(Request $request)
    {
        $request->validate([
            'available_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
        ]);

        $availability = Availability::create([
            'user_id' => auth()->id(),
            'available_date' => $request->available_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => $request->is_available ?? true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Availability created successfully',
            'data' => new AvailabilityResource($availability)
        ], 201);
    }

    public function show(Availability $availability)
    {
        return new AvailabilityResource($availability);
    }

    public function update(Request $request, Availability $availability)
    {
        $this->authorize('update', $availability);

        $request->validate([
            'available_date' => 'date|after_or_equal:today',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i|after:start_time',
            'is_available' => 'boolean',
        ]);

        $availability->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Availability updated successfully',
            'data' => new AvailabilityResource($availability)
        ]);
    }

    public function destroy(Availability $availability)
    {
        $this->authorize('delete', $availability);

        $availability->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Availability deleted successfully'
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'photographer_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $availability = Availability::where('user_id', $request->photographer_id)
            ->whereDate('available_date', $request->date)
            ->where('start_time', '<=', $request->time)
            ->where('end_time', '>=', $request->time)
            ->where('is_available', true)
            ->first();

        $isAvailable = $availability && !$availability->bookings()
            ->whereDate('booking_date', $request->date)
            ->whereTime('booking_time', $request->time)
            ->exists();

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_available' => $isAvailable,
                'availability' => $availability ? new AvailabilityResource($availability) : null
            ]
        ]);
    }
}