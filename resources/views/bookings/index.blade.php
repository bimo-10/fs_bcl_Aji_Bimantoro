@extends('layouts.app')

@section('title', 'Bookings')
@section('page-title', 'Fleet Booking Management')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Fleet Bookings</h2>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>New Booking
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('bookings.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>
                                    Confirmed</option>
                                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned
                                </option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Booking number or customer name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
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
                    @if ($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Booking Number</th>
                                        <th>Customer</th>
                                        <th>Vehicle Type</th>
                                        <th>Booking Date</th>
                                        <th>Route</th>
                                        <th>Weight</th>
                                        <th>Status</th>
                                        <th>Fleet</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>
                                                <strong>{{ $booking->booking_number }}</strong>
                                            </td>
                                            <td>
                                                <div>{{ $booking->customer_name }}</div>
                                                <small class="text-muted">{{ $booking->customer_phone }}</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($booking->vehicle_type) }}</span>
                                            </td>
                                            <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                            <td>
                                                <small>
                                                    <strong>From:</strong>
                                                    {{ Str::limit($booking->pickup_address, 30) }}<br>
                                                    <strong>To:</strong> {{ Str::limit($booking->delivery_address, 30) }}
                                                </small>
                                            </td>
                                            <td>{{ number_format($booking->weight, 2) }} kg</td>
                                            <td>
                                                <span
                                                    class="badge status-badge
                                                @if ($booking->status === 'completed') bg-success
                                                @elseif($booking->status === 'assigned') bg-primary
                                                @elseif($booking->status === 'confirmed') bg-info
                                                @elseif($booking->status === 'pending') bg-warning text-dark
                                                @else bg-danger @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($booking->fleet)
                                                    <a href="{{ route('fleets.show', $booking->fleet) }}"
                                                        class="text-decoration-none">
                                                        {{ $booking->fleet->fleet_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('bookings.show', $booking) }}"
                                                        class="btn btn-outline-info" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if ($booking->status !== 'completed' && $booking->status !== 'cancelled')
                                                        <a href="{{ route('bookings.edit', $booking) }}"
                                                            class="btn btn-outline-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                    @if ($booking->status === 'pending' || $booking->status === 'confirmed')
                                                        <form method="POST"
                                                            action="{{ route('bookings.destroy', $booking) }}"
                                                            class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger"
                                                                title="Cancel">
                                                                <i class="bi bi-x-circle"></i>
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
                            {{ $bookings->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No Bookings Found</h4>
                            <p class="text-muted">No bookings match your search criteria.</p>
                            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create First Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
