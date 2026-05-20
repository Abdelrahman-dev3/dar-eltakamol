<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMessage;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingsController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::with(['service', 'user'])
            ->withCount('messages')
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create(): View
    {
        $services = Service::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('bookings.create', compact('services', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['date'],
            'booking_time' => $validated['time'],
            'notes' => $validated['notes'] ?? null,
            'status' => Booking::STATUS_RECEIVED,
        ]);

        if (filled($validated['notes'] ?? null)) {
            $booking->messages()->create([
                'user_id' => $validated['user_id'],
                'author_type' => BookingMessage::AUTHOR_CONTRIBUTOR,
                'message' => $validated['notes'],
            ]);
        }

        return redirect()->route('bookings.index')->with('success', 'تم إضافة طلب الخدمة بنجاح');
    }

    public function show($id): View
    {
        $booking = Booking::with(['service', 'user.contributor', 'messages.user'])
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    public function edit($id): View
    {
        $services = Service::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $booking = Booking::findOrFail($id);

        return view('bookings.edit', compact('booking', 'services', 'users'));
    }

    public function update(Request $request, $id): RedirectResponse
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

        return redirect()->route('bookings.index')->with('success', 'تم تحديث طلب الخدمة بنجاح');
    }

    public function destroy($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', 'تم حذف طلب الخدمة بنجاح');
    }

    public function update_status($bookingId, Request $request)
    {
        $booking = Booking::findOrFail($bookingId);
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Booking::getStatuses())),
        ]);

        $booking->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Booking status updated successfully.', 'status' => $booking->status]);
    }

    public function addProgress($bookingId, Request $request): RedirectResponse
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === Booking::STATUS_COMPLETED) {
            return redirect()->back()->withErrors([
                'message' => 'لا يمكن إضافة متابعة على طلب مكتمل.',
            ]);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $booking->messages()->create([
            'user_id' => auth()->id(),
            'author_type' => BookingMessage::AUTHOR_ADMIN,
            'message' => $validated['message'],
        ]);

        $booking->update(['status' => Booking::STATUS_IN_PROGRESS]);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'تم إضافة متابعة الطلب بنجاح.');
    }

    public function complete($bookingId, Request $request): RedirectResponse
    {
        $booking = Booking::findOrFail($bookingId);

        $validated = $request->validate([
            'message' => 'nullable|string|max:2000',
        ]);

        if (filled($validated['message'] ?? null)) {
            $booking->messages()->create([
                'user_id' => auth()->id(),
                'author_type' => BookingMessage::AUTHOR_ADMIN,
                'message' => $validated['message'],
            ]);
        }

        $booking->update(['status' => Booking::STATUS_COMPLETED]);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'تم إغلاق طلب الخدمة وتحديث حالته إلى مكتملة.');
    }
}
