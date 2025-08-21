<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $shipment = Shipment::with('fleet')->where('tracking_number', $request->tracking_number)->first();

        if (!$shipment) {
            return back()->with('error', 'Tracking number not found!');
        }

        return view('tracking.result', compact('shipment'));
    }

    public function apiTrack(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $shipment = Shipment::with('fleet')->where('tracking_number', $request->tracking_number)->first();

        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking number not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'status' => $shipment->status,
                'shipment_date' => $shipment->shipment_date->format('Y-m-d'),
                'origin_address' => $shipment->origin_address,
                'destination_address' => $shipment->destination_address,
                'recipient_name' => $shipment->recipient_name,
                'item_details' => $shipment->item_details,
                'weight' => $shipment->weight,
                'fleet' => $shipment->fleet ? [
                    'fleet_number' => $shipment->fleet->fleet_number,
                    'vehicle_type' => $shipment->fleet->vehicle_type,
                    'driver_name' => $shipment->fleet->driver_name,
                    'current_latitude' => $shipment->fleet->current_latitude,
                    'current_longitude' => $shipment->fleet->current_longitude,
                    'last_location_update' => $shipment->fleet->last_location_update
                ] : null,
                'delivered_at' => $shipment->delivered_at ? $shipment->delivered_at->format('Y-m-d H:i:s') : null
            ]
        ]);
    }
}
