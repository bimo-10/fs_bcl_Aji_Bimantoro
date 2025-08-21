@extends('layouts.app')

@section('title', 'Tracking Result')
@section('page-title', 'Shipment Tracking Result')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="mb-3">
                <a href="{{ route('tracking.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Track Another Shipment
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-box-seam me-2"></i>
                                Tracking: {{ $shipment->tracking_number }}
                            </h4>
                        </div>
                        <div class="col-auto">
                            <span
                                class="badge badge-lg
                            @if ($shipment->status === 'delivered') bg-success
                            @elseif($shipment->status === 'in_transit') bg-primary
                            @elseif($shipment->status === 'pending') bg-warning text-dark
                            @else bg-danger @endif">
                                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">SHIPMENT INFORMATION</h6>

                            <div class="mb-3">
                                <strong>Shipment Date:</strong><br>
                                {{ $shipment->shipment_date->format('d M Y') }}
                            </div>

                            <div class="mb-3">
                                <strong>Weight:</strong><br>
                                {{ number_format($shipment->weight, 2) }} kg
                            </div>

                            @if ($shipment->value)
                                <div class="mb-3">
                                    <strong>Declared Value:</strong><br>
                                    Rp {{ number_format($shipment->value, 0, ',', '.') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <strong>Item Details:</strong><br>
                                {{ $shipment->item_details }}
                            </div>

                            @if ($shipment->delivered_at)
                                <div class="mb-3">
                                    <strong>Delivered At:</strong><br>
                                    <span class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        {{ $shipment->delivered_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">ADDRESSES</h6>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="border rounded p-3 mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-geo-alt text-success me-2"></i>
                                            <strong>FROM</strong>
                                        </div>
                                        <div>{{ $shipment->origin_address }}</div>
                                        <div class="mt-2">
                                            <strong>Sender:</strong> {{ $shipment->sender_name }}<br>
                                            <strong>Phone:</strong> {{ $shipment->sender_phone }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                            <strong>TO</strong>
                                        </div>
                                        <div>{{ $shipment->destination_address }}</div>
                                        <div class="mt-2">
                                            <strong>Recipient:</strong> {{ $shipment->recipient_name }}<br>
                                            <strong>Phone:</strong> {{ $shipment->recipient_phone }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($shipment->fleet)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-truck me-2"></i>
                            Assigned Fleet
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Fleet Number:</strong> {{ $shipment->fleet->fleet_number }}
                                </div>
                                <div class="mb-2">
                                    <strong>Vehicle Type:</strong>
                                    <span class="badge bg-secondary">{{ ucfirst($shipment->fleet->vehicle_type) }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Driver:</strong> {{ $shipment->fleet->driver_name ?? 'Not assigned' }}
                                </div>
                                @if ($shipment->fleet->driver_phone)
                                    <div class="mb-2">
                                        <strong>Driver Phone:</strong> {{ $shipment->fleet->driver_phone }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if ($shipment->fleet->current_latitude && $shipment->fleet->current_longitude)
                                    <div class="mb-2">
                                        <strong>Last Known Location:</strong><br>
                                        <small class="text-muted">
                                            Lat: {{ $shipment->fleet->current_latitude }},
                                            Lng: {{ $shipment->fleet->current_longitude }}
                                        </small>
                                    </div>
                                    @if ($shipment->fleet->last_location_update)
                                        <div class="mb-2">
                                            <strong>Last Update:</strong><br>
                                            <small class="text-muted">
                                                {{ $shipment->fleet->last_location_update->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <a href="https://www.google.com/maps?q={{ $shipment->fleet->current_latitude }},{{ $shipment->fleet->current_longitude }}"
                                            target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            View on Map
                                        </a>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        Location not available
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <i class="bi bi-truck text-muted" style="font-size: 2rem;"></i>
                        <h6 class="text-muted mt-2">No Fleet Assigned Yet</h6>
                        <p class="text-muted">This shipment is pending fleet assignment.</p>
                    </div>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Shipment Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Shipment Created</h6>
                                <p class="text-muted mb-0">{{ $shipment->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if ($shipment->fleet)
                            <div class="timeline-item">
                                <div
                                    class="timeline-marker {{ $shipment->status === 'pending' ? 'bg-secondary' : 'bg-success' }}">
                                </div>
                                <div class="timeline-content">
                                    <h6>Fleet Assigned</h6>
                                    <p class="text-muted mb-0">Fleet {{ $shipment->fleet->fleet_number }} assigned</p>
                                </div>
                            </div>
                        @endif

                        @if ($shipment->status === 'in_transit' || $shipment->status === 'delivered')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>In Transit</h6>
                                    <p class="text-muted mb-0">Shipment is on the way</p>
                                </div>
                            </div>
                        @endif

                        @if ($shipment->status === 'delivered')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6>Delivered</h6>
                                    <p class="text-muted mb-0">{{ $shipment->delivered_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -25px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #dee2e6;
        }

        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    </style>
@endpush
