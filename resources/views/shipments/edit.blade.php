@extends('layouts.app')

@section('title', 'Edit Shipment')
@section('page-title', 'Edit Shipment - ' . $shipment->tracking_number)

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Shipment: {{ $shipment->tracking_number }}
                    </h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye me-1"></i> View
                        </a>
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('shipments.update', $shipment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipment_date" class="form-label">Shipment Date</label>
                                <input type="date" class="form-control @error('shipment_date') is-invalid @enderror"
                                    id="shipment_date" name="shipment_date"
                                    value="{{ old('shipment_date', $shipment->shipment_date?->format('Y-m-d')) }}" required>
                                @error('shipment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="pending"
                                        {{ old('status', $shipment->status) === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="in_transit"
                                        {{ old('status', $shipment->status) === 'in_transit' ? 'selected' : '' }}>In Transit
                                    </option>
                                    <option value="delivered"
                                        {{ old('status', $shipment->status) === 'delivered' ? 'selected' : '' }}>Delivered
                                    </option>
                                    <option value="cancelled"
                                        {{ old('status', $shipment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="fleet_id" class="form-label">Assign Fleet</label>
                                <select class="form-select @error('fleet_id') is-invalid @enderror" id="fleet_id"
                                    name="fleet_id">
                                    <option value="">No Fleet Assigned</option>
                                    @foreach ($fleets as $fleet)
                                        <option value="{{ $fleet->id }}"
                                            {{ old('fleet_id', $shipment->fleet_id) == $fleet->id ? 'selected' : '' }}>
                                            {{ $fleet->fleet_number }} - {{ ucfirst($fleet->vehicle_type) }}
                                            ({{ $fleet->capacity }}t)
                                            - {{ ucfirst($fleet->availability) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fleet_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">
                            <i class="bi bi-geo-alt me-2"></i>Addresses
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="origin_address" class="form-label">Origin Address</label>
                                <textarea class="form-control @error('origin_address') is-invalid @enderror" id="origin_address" name="origin_address"
                                    rows="3" required>{{ old('origin_address', $shipment->origin_address) }}</textarea>
                                @error('origin_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="destination_address" class="form-label">Destination Address</label>
                                <textarea class="form-control @error('destination_address') is-invalid @enderror" id="destination_address"
                                    name="destination_address" rows="3" required>{{ old('destination_address', $shipment->destination_address) }}</textarea>
                                @error('destination_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">
                            <i class="bi bi-box me-2"></i>Item Details
                        </h6>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="item_details" class="form-label">Item Details</label>
                                <textarea class="form-control @error('item_details') is-invalid @enderror" id="item_details" name="item_details"
                                    rows="3" required>{{ old('item_details', $shipment->item_details) }}</textarea>
                                @error('item_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.1"
                                    class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight"
                                    value="{{ old('weight', $shipment->weight) }}" required>
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">Value (Rp)</label>
                                <input type="number" class="form-control @error('value') is-invalid @enderror"
                                    id="value" name="value" value="{{ old('value', $shipment->value) }}">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">
                            <i class="bi bi-person me-2"></i>Sender Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sender_name" class="form-label">Sender Name</label>
                                <input type="text" class="form-control @error('sender_name') is-invalid @enderror"
                                    id="sender_name" name="sender_name"
                                    value="{{ old('sender_name', $shipment->sender_name) }}" required>
                                @error('sender_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sender_phone" class="form-label">Sender Phone</label>
                                <input type="text" class="form-control @error('sender_phone') is-invalid @enderror"
                                    id="sender_phone" name="sender_phone"
                                    value="{{ old('sender_phone', $shipment->sender_phone) }}" required>
                                @error('sender_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">
                            <i class="bi bi-person-check me-2"></i>Recipient Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="recipient_name" class="form-label">Recipient Name</label>
                                <input type="text" class="form-control @error('recipient_name') is-invalid @enderror"
                                    id="recipient_name" name="recipient_name"
                                    value="{{ old('recipient_name', $shipment->recipient_name) }}" required>
                                @error('recipient_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recipient_phone" class="form-label">Recipient Phone</label>
                                <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror"
                                    id="recipient_phone" name="recipient_phone"
                                    value="{{ old('recipient_phone', $shipment->recipient_phone) }}" required>
                                @error('recipient_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-secondary me-md-2">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Update Shipment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
