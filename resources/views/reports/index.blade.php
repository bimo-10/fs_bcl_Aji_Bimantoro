@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard - Fleet & Shipment Management')

@section('content')
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalFleets }}</h4>
                            <p class="card-text">Total Fleets</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-truck" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <small>{{ $availableFleets }} Available</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalShipments }}</h4>
                            <p class="card-text">Total Shipments</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <small>{{ $inTransitShipments }} In Transit, {{ $deliveredShipments }} Delivered</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-dark bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalBookings }}</h4>
                            <p class="card-text">Total Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <small>{{ $pendingBookings }} Pending</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $inTransitShipments }}</h4>
                            <p class="card-text">Active Shipments</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-arrow-right-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <small>Currently In Transit</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-truck me-2"></i>
                        Shipments in Transit by Fleet
                    </h5>
                    <a href="{{ route('reports.export.shipments-in-transit') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-download me-1"></i> Export CSV
                    </a>
                </div>
                <div class="card-body">
                    @if ($shipmentsInTransit->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fleet Number</th>
                                        <th>Vehicle Type</th>
                                        <th>Driver</th>
                                        <th>Shipments Count</th>
                                        <th>Total Weight (kg)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shipmentsInTransit as $fleet)
                                        <tr>
                                            <td>
                                                <a href="{{ route('fleets.show', $fleet->id) }}"
                                                    class="text-decoration-none">
                                                    {{ $fleet->fleet_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($fleet->vehicle_type) }}</span>
                                            </td>
                                            <td>{{ $fleet->driver_name }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $fleet->shipments_count }}</span>
                                            </td>
                                            <td>{{ number_format($fleet->total_weight, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No shipments currently in transit</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create New Shipment
                        </a>
                        <a href="{{ route('bookings.create') }}" class="btn btn-success">
                            <i class="bi bi-calendar-plus me-2"></i>
                            New Booking
                        </a>
                        <a href="{{ route('fleets.create') }}" class="btn btn-info">
                            <i class="bi bi-truck me-2"></i>
                            Add Fleet
                        </a>
                        <a href="{{ route('tracking.index') }}" class="btn btn-warning">
                            <i class="bi bi-search me-2"></i>
                            Track Shipment
                        </a>
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                            <i class="bi bi-geo-alt me-2"></i>
                            Fleet Locations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Recent Shipments
                    </h5>
                    <a href="{{ route('shipments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if ($recentShipments->count() > 0)
                        @foreach ($recentShipments as $shipment)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <div class="fw-bold">{{ $shipment->tracking_number }}</div>
                                    <small class="text-muted">{{ $shipment->destination_address }}</small>
                                </div>
                                <div class="text-end">
                                    <span
                                        class="badge 
                                    @if ($shipment->status === 'delivered') bg-success
                                    @elseif($shipment->status === 'in_transit') bg-primary
                                    @elseif($shipment->status === 'pending') bg-warning text-dark
                                    @else bg-danger @endif">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $shipment->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No recent shipments</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar me-2"></i>
                        Recent Bookings
                    </h5>
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if ($recentBookings->count() > 0)
                        @foreach ($recentBookings as $booking)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <div class="fw-bold">{{ $booking->booking_number }}</div>
                                    <small class="text-muted">{{ $booking->customer_name }}</small>
                                </div>
                                <div class="text-end">
                                    <span
                                        class="badge 
                                    @if ($booking->status === 'completed') bg-success
                                    @elseif($booking->status === 'assigned') bg-primary
                                    @elseif($booking->status === 'confirmed') bg-info
                                    @elseif($booking->status === 'pending') bg-warning text-dark
                                    @else bg-danger @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No recent bookings</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
