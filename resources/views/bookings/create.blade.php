@extends('layouts.app')

@section('title', 'New Booking')
@section('page-title', 'Create New Booking')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-plus me-2"></i>
                        Create New Fleet Booking
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('bookings.store') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">CUSTOMER INFORMATION</h6>
                            </div>

                            <div class="col-md-4">
                                <label for="customer_name" class="form-label">Customer Name *</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                    id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                                    placeholder="Full name" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="customer_phone" class="form-label">Phone Number *</label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                    id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                                    placeholder="e.g., 081234567890" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="customer_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                    id="customer_email" name="customer_email" value="{{ old('customer_email') }}"
                                    placeholder="customer@example.com">
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">BOOKING DETAILS</h6>
                            </div>

                            <div class="col-md-6">
                                <label for="vehicle_type" class="form-label">Vehicle Type *</label>
                                <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type"
                                    name="vehicle_type" required>
                                    <option value="">Select Vehicle Type</option>
                                    <option value="truck" {{ old('vehicle_type') === 'truck' ? 'selected' : '' }}>Truck
                                        (Large cargo)</option>
                                    <option value="van" {{ old('vehicle_type') === 'van' ? 'selected' : '' }}>Van (Medium
                                        cargo)</option>
                                    <option value="motorcycle" {{ old('vehicle_type') === 'motorcycle' ? 'selected' : '' }}>
                                        Motorcycle (Small/urgent delivery)</option>
                                    <option value="container" {{ old('vehicle_type') === 'container' ? 'selected' : '' }}>
                                        Container (Heavy/bulk cargo)</option>
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="booking_date" class="form-label">Booking Date *</label>
                                <input type="date" class="form-control @error('booking_date') is-invalid @enderror"
                                    id="booking_date" name="booking_date"
                                    value="{{ old('booking_date', now()->format('Y-m-d')) }}"
                                    min="{{ now()->format('Y-m-d') }}" required>
                                @error('booking_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">PICKUP & DELIVERY ADDRESSES</h6>
                            </div>

                            <div class="col-md-6">
                                <label for="pickup_address" class="form-label">Pickup Address *</label>
                                <textarea class="form-control @error('pickup_address') is-invalid @enderror" id="pickup_address" name="pickup_address"
                                    rows="3" placeholder="Complete pickup address with landmarks" required>{{ old('pickup_address') }}</textarea>
                                @error('pickup_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="delivery_address" class="form-label">Delivery Address *</label>
                                <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address"
                                    name="delivery_address" rows="3" placeholder="Complete delivery address with landmarks" required>{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">ITEM INFORMATION</h6>
                            </div>

                            <div class="col-md-8">
                                <label for="item_details" class="form-label">Item Details *</label>
                                <textarea class="form-control @error('item_details') is-invalid @enderror" id="item_details" name="item_details"
                                    rows="3" placeholder="Describe the items to be transported (quantity, type, special requirements)" required>{{ old('item_details') }}</textarea>
                                @error('item_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="weight" class="form-label">Estimated Weight (kg) *</label>
                                <input type="number" step="0.01" min="0.1"
                                    class="form-control @error('weight') is-invalid @enderror" id="weight"
                                    name="weight" value="{{ old('weight') }}" placeholder="e.g., 5.50" required>
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                    placeholder="Any special instructions, requirements, or notes">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if ($availableFleets->count() > 0)
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle me-2"></i>Available Fleets for Selected Type</h6>
                                <div id="available-fleets-info">
                                    <p class="mb-0">Select a vehicle type to see available fleets.</p>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>No fleets available:</strong> Your booking will be placed in pending status until a
                                fleet becomes available.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vehicleTypeSelect = document.getElementById('vehicle_type');
            const availableFleetsInfo = document.getElementById('available-fleets-info');

            // Available fleets data from backend
            const availableFleets = @json($availableFleets->groupBy('vehicle_type'));

            vehicleTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;

                if (selectedType && availableFleets[selectedType]) {
                    const fleets = availableFleets[selectedType];
                    let fleetsHtml =
                        `<p><strong>Available ${selectedType}s (${fleets.length}):</strong></p><ul class="mb-0">`;

                    fleets.forEach(fleet => {
                        fleetsHtml +=
                        `<li>${fleet.fleet_number} - Capacity: ${fleet.capacity} tons`;
                        if (fleet.driver_name) {
                            fleetsHtml += ` (Driver: ${fleet.driver_name})`;
                        }
                        fleetsHtml += `</li>`;
                    });

                    fleetsHtml += '</ul>';
                    availableFleetsInfo.innerHTML = fleetsHtml;
                } else if (selectedType) {
                    availableFleetsInfo.innerHTML =
                        `<p class="mb-0 text-warning">No ${selectedType}s currently available. Your booking will be pending until one becomes available.</p>`;
                } else {
                    availableFleetsInfo.innerHTML =
                        '<p class="mb-0">Select a vehicle type to see available fleets.</p>';
                }
            });

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('booking_date').min = today;
        });
    </script>
@endpush
