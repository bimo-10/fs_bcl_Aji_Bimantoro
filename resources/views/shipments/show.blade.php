@extends('layouts.app')

@section('title', 'Shipment Details')
@section('page-title', 'Shipment Details - ' . $shipment->tracking_number)

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Shipment: {{ $shipment->tracking_number }}
                    </h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">
                                <i class="bi bi-info-circle me-2"></i>Shipment Information
                            </h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="fw-bold">Tracking Number:</td>
                                    <td>{{ $shipment->tracking_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Shipment Date:</td>
                                    <td>{{ $shipment->shipment_date?->format('d M Y') ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        <span
                                            class="badge 
                                            @if ($shipment->status === 'delivered') bg-success
                                            @elseif($shipment->status === 'in_transit') bg-primary
                                            @elseif($shipment->status === 'pending') bg-warning text-dark
                                            @else bg-danger @endif">
                                            {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Created:</td>
                                    <td>{{ $shipment->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                @if ($shipment->delivered_at)
                                    <tr>
                                        <td class="fw-bold">Delivered:</td>
                                        <td>{{ $shipment->delivered_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">
                                <i class="bi bi-truck me-2"></i>Fleet Assignment
                            </h6>
                            @if ($shipment->fleet)
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">{{ $shipment->fleet->fleet_number }}</h6>
                                        <p class="card-text mb-1">
                                            <small class="text-muted">Vehicle Type:</small>
                                            <span
                                                class="badge bg-secondary">{{ ucfirst($shipment->fleet->vehicle_type) }}</span>
                                        </p>
                                        <p class="card-text mb-1">
                                            <small class="text-muted">Capacity:</small> {{ $shipment->fleet->capacity }}
                                            tons
                                        </p>
                                        @if ($shipment->fleet->driver_name)
                                            <p class="card-text mb-1">
                                                <small class="text-muted">Driver:</small>
                                                {{ $shipment->fleet->driver_name }}
                                            </p>
                                        @endif
                                        <a href="{{ route('fleets.show', $shipment->fleet) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> View Fleet
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No fleet assigned yet
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">
                                <i class="bi bi-geo-alt me-2"></i>Origin
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <p class="mb-0">{{ $shipment->origin_address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">
                                <i class="bi bi-geo-alt-fill me-2"></i>Destination
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <p class="mb-0">{{ $shipment->destination_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">
                                <i class="bi bi-box me-2"></i>Item Details
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <p class="mb-2">{{ $shipment->item_details }}</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Weight:</small>
                                            <span class="fw-bold">{{ number_format($shipment->weight, 2) }} kg</span>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Value:</small>
                                            <span class="fw-bold">
                                                @if ($shipment->value)
                                                    Rp {{ number_format($shipment->value, 0, ',', '.') }}
                                                @else
                                                    Not specified
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">
                                <i class="bi bi-person me-2"></i>Sender
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-1">{{ $shipment->sender_name }}</h6>
                                    <p class="card-text mb-0">
                                        <i class="bi bi-telephone me-1"></i>{{ $shipment->sender_phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">
                                <i class="bi bi-person-check me-2"></i>Recipient
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-1">{{ $shipment->recipient_name }}</h6>
                                    <p class="card-text mb-0">
                                        <i class="bi bi-telephone me-1"></i>{{ $shipment->recipient_phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                @if ($shipment->status !== 'delivered' && $shipment->status !== 'cancelled')
                                    <form action="{{ route('shipments.update', $shipment) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="delivered">
                                        <input type="hidden" name="shipment_date"
                                            value="{{ $shipment->shipment_date?->format('Y-m-d') }}">
                                        <input type="hidden" name="origin_address"
                                            value="{{ $shipment->origin_address }}">
                                        <input type="hidden" name="destination_address"
                                            value="{{ $shipment->destination_address }}">
                                        <input type="hidden" name="item_details" value="{{ $shipment->item_details }}">
                                        <input type="hidden" name="weight" value="{{ $shipment->weight }}">
                                        <input type="hidden" name="value" value="{{ $shipment->value }}">
                                        <input type="hidden" name="recipient_name"
                                            value="{{ $shipment->recipient_name }}">
                                        <input type="hidden" name="recipient_phone"
                                            value="{{ $shipment->recipient_phone }}">
                                        <input type="hidden" name="sender_name" value="{{ $shipment->sender_name }}">
                                        <input type="hidden" name="sender_phone" value="{{ $shipment->sender_phone }}">
                                        <input type="hidden" name="fleet_id" value="{{ $shipment->fleet_id }}">
                                        <button type="submit" class="btn btn-success me-md-2"
                                            onclick="return confirm('Mark this shipment as delivered?')">
                                            <i class="bi bi-check-circle me-1"></i> Mark as Delivered
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-warning me-md-2">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <form action="{{ route('shipments.destroy', $shipment) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this shipment?')">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
