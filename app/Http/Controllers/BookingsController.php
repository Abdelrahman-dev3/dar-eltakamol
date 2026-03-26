<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;

class BookingsController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['service', 'user'])
            ->orderByDesc('booking_date')
            ->orderByDesc('booking_time')
            ->get();

        return view('bookings.index', compact('bookings'));
    }


    public function create()
    {
        $services = Service::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('bookings.create' , compact('services' , 'users'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        Booking::create([
            'user_id' => $validated['user_id'],
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['date'],
            'booking_time' => $validated['time'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'تم اضافة الحجز بنجاح');
    }

    public function edit($id)
    {
        $services = Service::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $booking = Booking::findOrFail($id);

        return view('bookings.edit' , compact('booking' , 'services' , 'users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'user_id' => $validated['user_id'],
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['date'],
            'booking_time' => $validated['time'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('bookings.index')->with('success', 'تم تحديث الحجز بنجاح');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->back()->with('success', 'تم حذف الحجز بنجاح');
    }

    public function update_status($bookingId, Request $request)
    {
        $booking = Booking::findOrFail($bookingId);
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Booking::getStatuses())),
        ]);

        $booking->status = $validated['status'];
        $booking->save();

        return response()->json(['message' => 'Booking status updated successfully.' , 'status' => $booking->status]);
    }
}
