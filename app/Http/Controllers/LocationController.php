<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Fleet;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $fleets = Fleet::with('latestLocation')->where('availability', '!=', 'maintenance')->get();
        return view('locations.index', compact('fleets'));
    }

    public function checkin(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['fleet_id'] = $fleet->id;
        $validated['checked_in_at'] = now();

        Location::create($validated);

        $fleet->update([
            'current_latitude' => $validated['latitude'],
            'current_longitude' => $validated['longitude'],
            'last_location_update' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location checked in successfully!'
        ]);
    }

    public function history(Fleet $fleet)
    {
        $locations = $fleet->locations()
            ->orderBy('checked_in_at', 'desc')
            ->paginate(20);

        return view('locations.history', compact('fleet', 'locations'));
    }

    public function apiFleetLocations()
    {
        $fleets = Fleet::with('latestLocation')
            ->where('availability', '!=', 'maintenance')
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->get();

        $locations = $fleets->map(function ($fleet) {
            return [
                'fleet_id' => $fleet->id,
                'fleet_number' => $fleet->fleet_number,
                'vehicle_type' => $fleet->vehicle_type,
                'availability' => $fleet->availability,
                'driver_name' => $fleet->driver_name,
                'latitude' => $fleet->current_latitude,
                'longitude' => $fleet->current_longitude,
                'last_update' => $fleet->last_location_update ? $fleet->last_location_update->format('Y-m-d H:i:s') : null,
                'active_shipments' => $fleet->active_shipments_count
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    public function apiFleetLocation(Fleet $fleet)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'fleet_id' => $fleet->id,
                'fleet_number' => $fleet->fleet_number,
                'vehicle_type' => $fleet->vehicle_type,
                'availability' => $fleet->availability,
                'driver_name' => $fleet->driver_name,
                'latitude' => $fleet->current_latitude,
                'longitude' => $fleet->current_longitude,
                'last_update' => $fleet->last_location_update ? $fleet->last_location_update->format('Y-m-d H:i:s') : null,
                'recent_locations' => $fleet->locations()
                    ->orderBy('checked_in_at', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function ($location) {
                        return [
                            'latitude' => $location->latitude,
                            'longitude' => $location->longitude,
                            'address' => $location->address,
                            'checked_in_at' => $location->checked_in_at->format('Y-m-d H:i:s')
                        ];
                    })
            ]
        ]);
    }
}
