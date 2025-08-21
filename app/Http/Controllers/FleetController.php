<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $query = Fleet::query();

        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fleet_number', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%");
            });
        }

        $fleets = $query->with('latestLocation')->paginate(10);

        return view('fleets.index', compact('fleets'));
    }

    public function create()
    {
        return view('fleets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fleet_number' => 'required|string|unique:fleets',
            'vehicle_type' => 'required|in:truck,van,motorcycle,container',
            'capacity' => 'required|numeric|min:0.1',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:20',
        ]);

        Fleet::create($validated);

        return redirect()->route('fleets.index')->with('success', 'Fleet created successfully!');
    }

    public function show(Fleet $fleet)
    {
        $fleet->load(['shipments', 'bookings', 'locations' => function ($query) {
            $query->orderBy('checked_in_at', 'desc')->take(10);
        }]);

        return view('fleets.show', compact('fleet'));
    }

    public function edit(Fleet $fleet)
    {
        return view('fleets.edit', compact('fleet'));
    }

    public function update(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'fleet_number' => ['required', 'string', Rule::unique('fleets')->ignore($fleet->id)],
            'vehicle_type' => 'required|in:truck,van,motorcycle,container',
            'availability' => 'required|in:available,unavailable,maintenance',
            'capacity' => 'required|numeric|min:0.1',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:20',
        ]);

        $fleet->update($validated);

        return redirect()->route('fleets.index')->with('success', 'Fleet updated successfully!');
    }

    public function destroy(Fleet $fleet)
    {
        if ($fleet->shipments()->where('status', 'in_transit')->exists()) {
            return redirect()->route('fleets.index')->with('error', 'Cannot delete fleet with active shipments!');
        }

        $fleet->delete();

        return redirect()->route('fleets.index')->with('success', 'Fleet deleted successfully!');
    }

    public function updateLocation(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $fleet->update([
            'current_latitude' => $validated['latitude'],
            'current_longitude' => $validated['longitude'],
            'last_location_update' => now()
        ]);

        $fleet->locations()->create([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'checked_in_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Location updated successfully!']);
    }
}
