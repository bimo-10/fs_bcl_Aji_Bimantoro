@extends('layouts.app')

@section('title', 'Shipment Status Report')
@section('page-title', 'Shipment Status Report')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        Shipment Status Analysis
                    </h5>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('reports.shipment-status') }}" class="d-flex gap-2">
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
                    @if ($statusReport->count() > 0)
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Status Distribution</h6>
                                @php
                                    $totalShipments = $statusReport->sum('count');
                                @endphp
                                @foreach ($statusReport as $status)
                                    @php
                                        $percentage =
                                            $totalShipments > 0
                                                ? round(($status->count / $totalShipments) * 100, 1)
                                                : 0;
                                        $badgeClass = match ($status->status) {
                                            'delivered' => 'bg-success',
                                            'in_transit' => 'bg-primary',
                                            'pending' => 'bg-warning text-dark',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <span class="badge {{ $badgeClass }} me-2">
                                                {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                                            </span>
                                            <span>{{ $status->count }} shipments</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 100px; height: 20px;">
                                                <div class="progress-bar {{ str_replace('text-dark', '', $badgeClass) }}"
                                                    style="width: {{ $percentage }}%">
                                                </div>
                                            </div>
                                            <small class="fw-bold">{{ $percentage }}%</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Summary Statistics</h6>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                <h4 class="text-primary mb-1">{{ $totalShipments }}</h4>
                                                <small>Total Shipments</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                @php
                                                    $deliveredCount =
                                                        $statusReport->where('status', 'delivered')->first()->count ??
                                                        0;
                                                    $successRate =
                                                        $totalShipments > 0
                                                            ? round(($deliveredCount / $totalShipments) * 100, 1)
                                                            : 0;
                                                @endphp
                                                <h4 class="text-success mb-1">{{ $successRate }}%</h4>
                                                <small>Success Rate</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                @php
                                                    $inTransitCount =
                                                        $statusReport->where('status', 'in_transit')->first()->count ??
                                                        0;
                                                @endphp
                                                <h4 class="text-primary mb-1">{{ $inTransitCount }}</h4>
                                                <small>In Transit</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center p-3">
                                                @php
                                                    $pendingCount =
                                                        $statusReport->where('status', 'pending')->first()->count ?? 0;
                                                @endphp
                                                <h4 class="text-warning mb-1">{{ $pendingCount }}</h4>
                                                <small>Pending</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($dailyShipments->count() > 0)
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3">Daily Shipment Trend</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Shipments Created</th>
                                            <th>Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dailyShipments as $daily)
                                            <tr>
                                                <td>{{ date('M d, Y', strtotime($daily->date)) }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $daily->count }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $maxCount = $dailyShipments->max('count');
                                                        $barWidth =
                                                            $maxCount > 0 ? ($daily->count / $maxCount) * 100 : 0;
                                                    @endphp
                                                    <div class="progress" style="height: 15px; width: 100px;">
                                                        <div class="progress-bar bg-info"
                                                            style="width: {{ $barWidth }}%"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-pie-chart text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No Status Data</h4>
                            <p class="text-muted">No shipment status data available for the selected date range.</p>
                            <a href="{{ route('shipments.index') }}" class="btn btn-primary">
                                <i class="bi bi-box-seam me-2"></i>Manage Shipments
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
