@extends('layouts.app')

@section('title', 'Edit Fleet')
@section('page-title', 'Edit Fleet - ' . $fleet->fleet_number)

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Fleet: {{ $fleet->fleet_number }}
                    </h5>
                    <a href="{{ route('fleets.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fleets.update', $fleet) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="fleet_number" class="form-label">Fleet Number</label>
                            <input type="text" class="form-control @error('fleet_number') is-invalid @enderror"
                                id="fleet_number" name="fleet_number"
                                value="{{ old('fleet_number', $fleet->fleet_number) }}" required>
                            @error('fleet_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_type" class="form-label">Vehicle Type</label>
                            <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type"
                                name="vehicle_type" required>
                                <option value="truck"
                                    {{ old('vehicle_type', $fleet->vehicle_type) === 'truck' ? 'selected' : '' }}>Truck
                                </option>
                                <option value="van"
                                    {{ old('vehicle_type', $fleet->vehicle_type) === 'van' ? 'selected' : '' }}>Van</option>
                                <option value="motorcycle"
                                    {{ old('vehicle_type', $fleet->vehicle_type) === 'motorcycle' ? 'selected' : '' }}>
                                    Motorcycle</option>
                                <option value="container"
                                    {{ old('vehicle_type', $fleet->vehicle_type) === 'container' ? 'selected' : '' }}>
                                    Container</option>
                            </select>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="availability" class="form-label">Availability</label>
                            <select class="form-select @error('availability') is-invalid @enderror" id="availability"
                                name="availability" required>
                                <option value="available"
                                    {{ old('availability', $fleet->availability) === 'available' ? 'selected' : '' }}>
                                    Available</option>
                                <option value="unavailable"
                                    {{ old('availability', $fleet->availability) === 'unavailable' ? 'selected' : '' }}>
                                    Unavailable</option>
                                <option value="maintenance"
                                    {{ old('availability', $fleet->availability) === 'maintenance' ? 'selected' : '' }}>
                                    Maintenance</option>
                            </select>
                            @error('availability')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity (tons)</label>
                            <input type="number" step="0.1"
                                class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity"
                                value="{{ old('capacity', $fleet->capacity) }}" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="driver_name" class="form-label">Driver Name</label>
                            <input type="text" class="form-control @error('driver_name') is-invalid @enderror"
                                id="driver_name" name="driver_name" value="{{ old('driver_name', $fleet->driver_name) }}">
                            @error('driver_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="driver_phone" class="form-label">Driver Phone</label>
                            <input type="text" class="form-control @error('driver_phone') is-invalid @enderror"
                                id="driver_phone" name="driver_phone"
                                value="{{ old('driver_phone', $fleet->driver_phone) }}">
                            @error('driver_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('fleets.show', $fleet) }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update Fleet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
