<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Fleet;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('fleet');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $availableFleets = Fleet::where('availability', 'available')->get();
        return view('bookings.create', compact('availableFleets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|in:truck,van,motorcycle,container',
            'booking_date' => 'required|date|after_or_equal:today',
            'pickup_address' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'item_details' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
        ]);

        if (!Booking::validateBookingDate($validated['booking_date'])) {
            return back()->withErrors(['booking_date' => 'Booking date cannot be in the past.'])->withInput();
        }

        $validated['booking_number'] = Booking::generateBookingNumber();

        $availableFleet = Fleet::where('vehicle_type', $validated['vehicle_type'])
                               ->where('availability', 'available')
                               ->first();

        if ($availableFleet) {
            $validated['fleet_id'] = $availableFleet->id;
            $validated['status'] = 'assigned';
            
            $availableFleet->update(['availability' => 'unavailable']);
        } else {
            $validated['status'] = 'pending';
        }

        $booking = Booking::create($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully! Booking number: ' . $booking->booking_number);
    }

    public function show(Booking $booking)
    {
        $booking->load('fleet');
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $availableFleets = Fleet::where('availability', 'available')
                                ->orWhere('id', $booking->fleet_id)
                                ->get();
        return view('bookings.edit', compact('booking', 'availableFleets'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|in:truck,van,motorcycle,container',
            'booking_date' => 'required|date',
            'pickup_address' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'item_details' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'status' => 'required|in:pending,confirmed,assigned,completed,cancelled',
            'fleet_id' => 'nullable|exists:fleets,id',
            'notes' => 'nullable|string',
        ]);

        $oldFleetId = $booking->fleet_id;
        $newFleetId = $validated['fleet_id'];

        if ($oldFleetId != $newFleetId) {
            if ($oldFleetId) {
                Fleet::find($oldFleetId)->update(['availability' => 'available']);
            }
            
            if ($newFleetId) {
                Fleet::find($newFleetId)->update(['availability' => 'unavailable']);
            }
        }

        if ($validated['status'] === 'completed' && $booking->status !== 'completed') {
            if ($booking->fleet_id) {
                Fleet::find($booking->fleet_id)->update(['availability' => 'available']);
            }
        }

        if ($validated['status'] === 'cancelled' && $booking->status !== 'cancelled') {
            if ($booking->fleet_id) {
                Fleet::find($booking->fleet_id)->update(['availability' => 'available']);
            }
        }

        $booking->update($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->fleet_id) {
            Fleet::find($booking->fleet_id)->update(['availability' => 'available']);
        }

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }

    public function assignFleet(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'fleet_id' => 'required|exists:fleets,id',
        ]);

        $fleet = Fleet::find($validated['fleet_id']);

        if (!$fleet->isAvailable()) {
            return back()->with('error', 'Selected fleet is not available!');
        }

        if ($fleet->vehicle_type !== $booking->vehicle_type) {
            return back()->with('error', 'Fleet vehicle type does not match booking requirement!');
        }

        $booking->assignFleet($fleet);

        return redirect()->route('bookings.show', $booking)->with('success', 'Fleet assigned successfully!');
    }
}
