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
        $bookings = Booking::all();
        return view('bookings.index', compact('bookings'));
    }


    public function create()
    {
        $services = Service::all();
        $users = User::all();
        return view('bookings.create' , compact('services' , 'users'));
    }


    public function store(Request $request)
    {
        request()->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        Booking::create([
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'booking_date' => $request->date,
            'booking_time' => $request->time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'تم اضافة الحجز بنجاح');
    }

    public function edit($id)
    {
        $services = Service::all();
        $users = User::all();
        $booking = Booking::findOrFail($id);
        return view('bookings.edit' , compact('booking' , 'services' , 'users'));
    }

    public function update($id)
    {
        request()->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'user_id' => request()->user_id,
            'service_id' => request()->service_id,
            'booking_date' => request()->date,
            'booking_time' => request()->time,
            'notes' => request()->notes,
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
        $booking->status = $request->input('status');
        $booking->save();

        return response()->json(['message' => 'Booking status updated successfully.' , 'status' => $booking->status]);
    }
}