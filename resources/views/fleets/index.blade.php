@extends('layouts.app')

@section('title', 'Fleet Management')
@section('page-title', 'Fleet Management')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Fleet Management</h2>
                <a href="{{ route('fleets.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add New Fleet
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('fleets.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Vehicle Type</label>
                            <select name="vehicle_type" class="form-select">
                                <option value="">All Types</option>
                                <option value="truck" {{ request('vehicle_type') === 'truck' ? 'selected' : '' }}>Truck
                                </option>
                                <option value="van" {{ request('vehicle_type') === 'van' ? 'selected' : '' }}>Van
                                </option>
                                <option value="motorcycle" {{ request('vehicle_type') === 'motorcycle' ? 'selected' : '' }}>
                                    Motorcycle</option>
                                <option value="container" {{ request('vehicle_type') === 'container' ? 'selected' : '' }}>
                                    Container</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Availability</label>
                            <select name="availability" class="form-select">
                                <option value="">All Status</option>
                                <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>
                                    Available</option>
                                <option value="unavailable"
                                    {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                <option value="maintenance"
                                    {{ request('availability') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Fleet number or driver name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                                <a href="{{ route('fleets.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>
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
                    @if ($fleets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fleet Number</th>
                                        <th>Vehicle Type</th>
                                        <th>Capacity (tons)</th>
                                        <th>Driver</th>
                                        <th>Availability</th>
                                        <th>Last Location Update</th>
                                        <th>Active Shipments</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fleets as $fleet)
                                        <tr>
                                            <td>
                                                <strong>{{ $fleet->fleet_number }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($fleet->vehicle_type) }}</span>
                                            </td>
                                            <td>{{ number_format($fleet->capacity, 2) }}</td>
                                            <td>
                                                @if ($fleet->driver_name)
                                                    <div>{{ $fleet->driver_name }}</div>
                                                    @if ($fleet->driver_phone)
                                                        <small class="text-muted">{{ $fleet->driver_phone }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No driver assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge status-badge
                                                @if ($fleet->availability === 'available') bg-success
                                                @elseif($fleet->availability === 'unavailable') bg-danger
                                                @else bg-warning text-dark @endif">
                                                    {{ ucfirst($fleet->availability) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($fleet->last_location_update)
                                                    <small>{{ $fleet->last_location_update->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $fleet->active_shipments_count }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('fleets.show', $fleet) }}"
                                                        class="btn btn-outline-info" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('fleets.edit', $fleet) }}"
                                                        class="btn btn-outline-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('locations.history', $fleet) }}"
                                                        class="btn btn-outline-secondary" title="Location History">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </a>
                                                    @if ($fleet->active_shipments_count == 0)
                                                        <form method="POST" action="{{ route('fleets.destroy', $fleet) }}"
                                                            class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this fleet?')">
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
                            {{ $fleets->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-truck text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No Fleets Found</h4>
                            <p class="text-muted">No fleets match your search criteria.</p>
                            <a href="{{ route('fleets.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add First Fleet
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
