<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\Shipment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $shipmentsInTransit = DB::table('shipments')
            ->join('fleets', 'shipments.fleet_id', '=', 'fleets.id')
            ->where('shipments.status', 'in_transit')
            ->select(
                'fleets.id',
                'fleets.fleet_number',
                'fleets.vehicle_type',
                'fleets.driver_name',
                DB::raw('COUNT(shipments.id) as shipments_count'),
                DB::raw('SUM(shipments.weight) as total_weight')
            )
            ->groupBy('fleets.id', 'fleets.fleet_number', 'fleets.vehicle_type', 'fleets.driver_name')
            ->orderBy('shipments_count', 'desc')
            ->get();

        $totalFleets = Fleet::count();
        $availableFleets = Fleet::where('availability', 'available')->count();
        $totalShipments = Shipment::count();
        $inTransitShipments = Shipment::where('status', 'in_transit')->count();
        $deliveredShipments = Shipment::where('status', 'delivered')->count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        $recentShipments = Shipment::with('fleet')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentBookings = Booking::with('fleet')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('reports.index', compact(
            'shipmentsInTransit',
            'totalFleets',
            'availableFleets',
            'totalShipments',
            'inTransitShipments',
            'deliveredShipments',
            'totalBookings',
            'pendingBookings',
            'recentShipments',
            'recentBookings'
        ));
    }

    public function fleetPerformance(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $fleetPerformance = DB::table('fleets')
            ->leftJoin('shipments', 'fleets.id', '=', 'shipments.fleet_id')
            ->leftJoin('bookings', 'fleets.id', '=', 'bookings.fleet_id')
            ->whereBetween('shipments.created_at', [$dateFrom, $dateTo])
            ->orWhereBetween('bookings.created_at', [$dateFrom, $dateTo])
            ->select(
                'fleets.id',
                'fleets.fleet_number',
                'fleets.vehicle_type',
                'fleets.capacity',
                'fleets.availability',
                DB::raw('COUNT(DISTINCT shipments.id) as total_shipments'),
                DB::raw('COUNT(DISTINCT CASE WHEN shipments.status = "delivered" THEN shipments.id END) as delivered_shipments'),
                DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
                DB::raw('COUNT(DISTINCT CASE WHEN bookings.status = "completed" THEN bookings.id END) as completed_bookings'),
                DB::raw('SUM(shipments.weight) as total_weight_shipped')
            )
            ->groupBy('fleets.id', 'fleets.fleet_number', 'fleets.vehicle_type', 'fleets.capacity', 'fleets.availability')
            ->orderBy('total_shipments', 'desc')
            ->get();

        return view('reports.fleet-performance', compact('fleetPerformance', 'dateFrom', 'dateTo'));
    }

    public function shipmentStatus(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $statusReport = Shipment::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $dailyShipments = Shipment::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('reports.shipment-status', compact('statusReport', 'dailyShipments', 'dateFrom', 'dateTo'));
    }

    public function revenue(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $monthlyRevenue = Shipment::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'delivered')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(value) as total_value'),
                DB::raw('COUNT(*) as shipment_count')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('reports.revenue', compact('monthlyRevenue', 'dateFrom', 'dateTo'));
    }

    public function exportShipmentsInTransit()
    {
        $shipmentsInTransit = DB::table('shipments')
            ->join('fleets', 'shipments.fleet_id', '=', 'fleets.id')
            ->where('shipments.status', 'in_transit')
            ->select(
                'fleets.fleet_number',
                'fleets.vehicle_type',
                'fleets.driver_name',
                'shipments.tracking_number',
                'shipments.origin_address',
                'shipments.destination_address',
                'shipments.weight',
                'shipments.shipment_date'
            )
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="shipments_in_transit_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($shipmentsInTransit) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Fleet Number',
                'Vehicle Type',
                'Driver Name',
                'Tracking Number',
                'Origin',
                'Destination',
                'Weight (kg)',
                'Shipment Date'
            ]);

            foreach ($shipmentsInTransit as $shipment) {
                fputcsv($file, [
                    $shipment->fleet_number,
                    $shipment->vehicle_type,
                    $shipment->driver_name,
                    $shipment->tracking_number,
                    $shipment->origin_address,
                    $shipment->destination_address,
                    $shipment->weight,
                    $shipment->shipment_date
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
