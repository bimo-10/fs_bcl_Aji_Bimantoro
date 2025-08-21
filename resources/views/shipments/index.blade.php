@extends('layouts.app')

@section('title', 'Shipments')
@section('page-title', 'Shipment Management')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Shipment Management</h2>
                <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create Shipment
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('shipments.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="in_transit" {{ request('status') === 'in_transit' ? 'selected' : '' }}>In
                                    Transit</option>
                                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>
                                    Delivered</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Tracking number, destination, or recipient name..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if ($shipments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tracking Number</th>
                                        <th>Date</th>
                                        <th>Route</th>
                                        <th>Recipient</th>
                                        <th>Weight</th>
                                        <th>Status</th>
                                        <th>Fleet</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shipments as $shipment)
                                        <tr>
                                            <td>
                                                <strong>{{ $shipment->tracking_number }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $shipment->created_at->format('d M Y, H:i') }}</small>
                                            </td>
                                            <td>{{ $shipment->shipment_date->format('d M Y') }}</td>
                                            <td>
                                                <small>
                                                    <strong>From:</strong>
                                                    {{ Str::limit($shipment->origin_address, 25) }}<br>
                                                    <strong>To:</strong>
                                                    {{ Str::limit($shipment->destination_address, 25) }}
                                                </small>
                                            </td>
                                            <td>
                                                <div>{{ $shipment->recipient_name }}</div>
                                                <small class="text-muted">{{ $shipment->recipient_phone }}</small>
                                            </td>
                                            <td>{{ number_format($shipment->weight, 2) }} kg</td>
                                            <td>
                                                <span
                                                    class="badge status-badge
                                                @if ($shipment->status === 'delivered') bg-success
                                                @elseif($shipment->status === 'in_transit') bg-primary
                                                @elseif($shipment->status === 'pending') bg-warning text-dark
                                                @else bg-danger @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                                </span>
                                                @if ($shipment->delivered_at)
                                                    <br><small
                                                        class="text-muted">{{ $shipment->delivered_at->diffForHumans() }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($shipment->fleet)
                                                    <a href="{{ route('fleets.show', $shipment->fleet) }}"
                                                        class="text-decoration-none">
                                                        {{ $shipment->fleet->fleet_number }}
                                                    </a>
                                                    @if ($shipment->fleet->driver_name)
                                                        <br><small
                                                            class="text-muted">{{ $shipment->fleet->driver_name }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('shipments.show', $shipment) }}"
                                                        class="btn btn-outline-info" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('tracking.track') }}?tracking_number={{ $shipment->tracking_number }}"
                                                        class="btn btn-outline-secondary" title="Track">
                                                        <i class="bi bi-search"></i>
                                                    </a>
                                                    @if ($shipment->status !== 'delivered' && $shipment->status !== 'cancelled')
                                                        <a href="{{ route('shipments.edit', $shipment) }}"
                                                            class="btn btn-outline-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                    @if ($shipment->status === 'pending')
                                                        <form method="POST"
                                                            action="{{ route('shipments.destroy', $shipment) }}"
                                                            class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this shipment?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger"
                                                                title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $shipments->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No Shipments Found</h4>
                            <p class="text-muted">No shipments match your search criteria.</p>
                            <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create First Shipment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>{{ $shipments->where('status', 'pending')->count() }}</h5>
                    <small class="text-muted">Pending Shipments</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>{{ $shipments->where('status', 'in_transit')->count() }}</h5>
                    <small class="text-muted">In Transit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>{{ $shipments->where('status', 'delivered')->count() }}</h5>
                    <small class="text-muted">Delivered</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>{{ number_format($shipments->sum('weight'), 2) }}</h5>
                    <small class="text-muted">Total Weight (kg)</small>
                </div>
            </div>
        </div>
    </div>
@endsection
