@extends('layouts.app')

@section('title', 'Revenue Report')
@section('page-title', 'Revenue Report')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Revenue Analysis
                    </h5>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('reports.revenue') }}" class="d-flex gap-2">
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
                    @if ($monthlyRevenue->count() > 0)
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3>Rp {{ number_format($monthlyRevenue->sum('total_value'), 0, ',', '.') }}</h3>
                                        <p class="mb-0">Total Revenue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3>{{ $monthlyRevenue->sum('shipment_count') }}</h3>
                                        <p class="mb-0">Delivered Shipments</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        @php
                                            $totalShipments = $monthlyRevenue->sum('shipment_count');
                                            $avgRevenue =
                                                $totalShipments > 0
                                                    ? $monthlyRevenue->sum('total_value') / $totalShipments
                                                    : 0;
                                        @endphp
                                        <h3>Rp {{ number_format($avgRevenue, 0, ',', '.') }}</h3>
                                        <p class="mb-0">Avg. per Shipment</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Shipments</th>
                                        <th>Revenue</th>
                                        <th>Average per Shipment</th>
                                        <th>Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyRevenue as $revenue)
                                        <tr>
                                            <td>
                                                <strong>
                                                    {{ date('F Y', mktime(0, 0, 0, $revenue->month, 1, $revenue->year)) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $revenue->shipment_count }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    Rp {{ number_format($revenue->total_value, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td>
                                                @php
                                                    $avgPerShipment =
                                                        $revenue->shipment_count > 0
                                                            ? $revenue->total_value / $revenue->shipment_count
                                                            : 0;
                                                @endphp
                                                Rp {{ number_format($avgPerShipment, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @php
                                                    $maxRevenue = $monthlyRevenue->max('total_value');
                                                    $barWidth =
                                                        $maxRevenue > 0
                                                            ? ($revenue->total_value / $maxRevenue) * 100
                                                            : 0;
                                                @endphp
                                                <div class="progress" style="height: 20px; width: 120px;">
                                                    <div class="progress-bar bg-success"
                                                        style="width: {{ $barWidth }}%">
                                                        <small>{{ round($barWidth) }}%</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th>Total</th>
                                        <th>
                                            <span
                                                class="badge bg-primary">{{ $monthlyRevenue->sum('shipment_count') }}</span>
                                        </th>
                                        <th>
                                            <strong class="text-success">
                                                Rp {{ number_format($monthlyRevenue->sum('total_value'), 0, ',', '.') }}
                                            </strong>
                                        </th>
                                        <th>
                                            Rp {{ number_format($avgRevenue, 0, ',', '.') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-graph-up text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No Revenue Data</h4>
                            <p class="text-muted">No revenue data available for the selected date range.</p>
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
