@extends('layouts.app')

@section('title', 'Add New Fleet')
@section('page-title', 'Add New Fleet')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-truck me-2"></i>
                        Add New Fleet
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('fleets.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fleet_number" class="form-label">Fleet Number *</label>
                                <input type="text" class="form-control @error('fleet_number') is-invalid @enderror"
                                    id="fleet_number" name="fleet_number" value="{{ old('fleet_number') }}"
                                    placeholder="e.g., TRK001" required>
                                @error('fleet_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="vehicle_type" class="form-label">Vehicle Type *</label>
                                <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type"
                                    name="vehicle_type" required>
                                    <option value="">Select Vehicle Type</option>
                                    <option value="truck" {{ old('vehicle_type') === 'truck' ? 'selected' : '' }}>Truck
                                    </option>
                                    <option value="van" {{ old('vehicle_type') === 'van' ? 'selected' : '' }}>Van
                                    </option>
                                    <option value="motorcycle" {{ old('vehicle_type') === 'motorcycle' ? 'selected' : '' }}>
                                        Motorcycle</option>
                                    <option value="container" {{ old('vehicle_type') === 'container' ? 'selected' : '' }}>
                                        Container</option>
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity (tons) *</label>
                            <input type="number" step="0.01" min="0.1"
                                class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity"
                                value="{{ old('capacity') }}" placeholder="e.g., 5.50" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="driver_name" class="form-label">Driver Name</label>
                                <input type="text" class="form-control @error('driver_name') is-invalid @enderror"
                                    id="driver_name" name="driver_name" value="{{ old('driver_name') }}"
                                    placeholder="e.g., John Doe">
                                @error('driver_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="driver_phone" class="form-label">Driver Phone</label>
                                <input type="text" class="form-control @error('driver_phone') is-invalid @enderror"
                                    id="driver_phone" name="driver_phone" value="{{ old('driver_phone') }}"
                                    placeholder="e.g., 081234567890">
                                @error('driver_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> The fleet will be set as "Available" by default. You can change the
                            status later from the fleet management page.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('fleets.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Fleet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
