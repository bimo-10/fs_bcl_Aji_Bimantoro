<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Fleet;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('fleet');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                    ->orWhere('destination_address', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $fleets = Fleet::where('availability', 'available')->get();
        return view('shipments.create', compact('fleets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_date' => 'required|date|after_or_equal:today',
            'origin_address' => 'required|string|max:255',
            'destination_address' => 'required|string|max:255',
            'item_details' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'value' => 'nullable|numeric|min:0',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'fleet_id' => 'nullable|exists:fleets,id',
        ]);

        $validated['tracking_number'] = Shipment::generateTrackingNumber();

        $shipment = Shipment::create($validated);

        if ($shipment->fleet_id) {
            Fleet::find($shipment->fleet_id)->update(['availability' => 'unavailable']);
            $shipment->update(['status' => 'in_transit']);
        }

        return redirect()->route('shipments.index')->with('success', 'Shipment created successfully!');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load('fleet');
        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $fleets = Fleet::where('availability', 'available')
            ->orWhere('id', $shipment->fleet_id)
            ->get();
        return view('shipments.edit', compact('shipment', 'fleets'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'shipment_date' => 'required|date',
            'origin_address' => 'required|string|max:255',
            'destination_address' => 'required|string|max:255',
            'status' => 'required|in:pending,in_transit,delivered,cancelled',
            'item_details' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'value' => 'nullable|numeric|min:0',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'fleet_id' => 'nullable|exists:fleets,id',
        ]);

        $oldFleetId = $shipment->fleet_id;
        $newFleetId = $validated['fleet_id'];

        if ($oldFleetId != $newFleetId) {
            if ($oldFleetId) {
                Fleet::find($oldFleetId)->update(['availability' => 'available']);
            }

            if ($newFleetId) {
                Fleet::find($newFleetId)->update(['availability' => 'unavailable']);
            }
        }

        $shipment->update($validated);

        return redirect()->route('shipments.index')->with('success', 'Shipment updated successfully!');
    }

    public function destroy(Shipment $shipment)
    {
        if ($shipment->fleet_id) {
            Fleet::find($shipment->fleet_id)->update(['availability' => 'available']);
        }

        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'Shipment deleted successfully!');
    }
}
