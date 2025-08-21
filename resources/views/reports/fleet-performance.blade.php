@extends('layouts.app')

@section('title', 'Fleet Performance Report')
@section('page-title', 'Fleet Performance Report')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Fleet Performance Analysis
                    </h5>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('reports.fleet-performance') }}" class="d-flex gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">From</span>
                                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}"
                                    style="max-width: 150px;">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">To</span>
                                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}"
                                    style="max-width: 150px;">
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if ($fleetPerformance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fleet Number</th>
                                        <th>Vehicle Type</th>
                                        <th>Capacity (tons)</th>
                                        <th>Status</th>
                                        <th>Total Shipments</th>
                                        <th>Delivered</th>
                                        <th>Total Bookings</th>
                                        <th>Completed</th>
                                        <th>Weight Shipped (kg)</th>
                                        <th>Efficiency</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fleetPerformance as $fleet)
                                        <tr>
                                            <td>
                                                <a href="{{ route('fleets.show', $fleet->id) }}"
                                                    class="text-decoration-none fw-bold">
                                                    {{ $fleet->fleet_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst($fleet->vehicle_type) }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($fleet->capacity, 1) }}</td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($fleet->availability === 'available') bg-success
                                                    @elseif($fleet->availability === 'unavailable') bg-danger
                                                    @else bg-warning text-dark @endif">
                                                    {{ ucfirst($fleet->availability) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $fleet->total_shipments }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $fleet->delivered_shipments }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $fleet->total_bookings }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $fleet->completed_bookings }}</span>
                                            </td>
                                            <td>{{ number_format($fleet->total_weight_shipped ?? 0, 2) }}</td>
                                            <td>
                                                @php
                                                    $efficiency =
                                                        $fleet->total_shipments > 0
                                                            ? round(
                                                                ($fleet->delivered_shipments /
                                                                    $fleet->total_shipments) *
                                                                    100,
                                                                1,
                                                            )
                                                            : 0;
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 60px; height: 20px;">
                                                        <div class="progress-bar 
                                                            @if ($efficiency >= 80) bg-success
                                                            @elseif($efficiency >= 60) bg-warning
                                                            @else bg-danger @endif"
                                                            style="width: {{ $efficiency }}%">
                                                        </div>
                                                    </div>
                                                    <small class="fw-bold">{{ $efficiency }}%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary">{{ $fleetPerformance->sum('total_shipments') }}</h3>
                                        <p class="mb-0">Total Shipments</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h3 class="text-success">{{ $fleetPerformance->sum('delivered_shipments') }}</h3>
                                        <p class="mb-0">Delivered Successfully</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        @php
                                            $totalShipments = $fleetPerformance->sum('total_shipments');
                                            $totalDelivered = $fleetPerformance->sum('delivered_shipments');
                                            $overallEfficiency =
                                                $totalShipments > 0
                                                    ? round(($totalDelivered / $totalShipments) * 100, 1)
                                                    : 0;
                                        @endphp
                                        <h3
                                            class="
                                            @if ($overallEfficiency >= 80) text-success
                                            @elseif($overallEfficiency >= 60) text-warning
                                            @else text-danger @endif">
                                            {{ $overallEfficiency }}%
                                        </h3>
                                        <p class="mb-0">Overall Efficiency</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bar-chart text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No Performance Data</h4>
                            <p class="text-muted">No fleet performance data available for the selected date range.</p>
                            <a href="{{ route('fleets.index') }}" class="btn btn-primary">
                                <i class="bi bi-truck me-2"></i>Manage Fleets
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
