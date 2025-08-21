@extends('layouts.app')

@section('title', 'Fleet Locations')
@section('page-title', 'Fleet Locations Map')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Fleet Locations</h2>
                <div>
                    <button class="btn btn-outline-primary" onclick="refreshLocations()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                    </button>
                    <button class="btn btn-primary" onclick="showLocationForm()">
                        <i class="bi bi-geo-alt-fill me-2"></i>Check-in Location
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-map me-2"></i>
                        Live Fleet Tracking Map
                    </h5>
                </div>
                <div class="card-body">
                    <div id="map" class="map-container"></div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Click on markers to view fleet details. Map shows real-time locations of active fleets.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($fleets as $fleet)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title">{{ $fleet->fleet_number }}</h6>
                                <span class="badge bg-secondary mb-2">{{ ucfirst($fleet->vehicle_type) }}</span>
                                <p class="card-text mb-1">
                                    <strong>Driver:</strong> {{ $fleet->driver_name ?? 'Not assigned' }}
                                </p>
                                <p class="card-text mb-1">
                                    <strong>Status:</strong>
                                    <span
                                        class="badge
                                    @if ($fleet->availability === 'available') bg-success
                                    @elseif($fleet->availability === 'unavailable') bg-danger
                                    @else bg-warning text-dark @endif">
                                        {{ ucfirst($fleet->availability) }}
                                    </span>
                                </p>
                                @if ($fleet->last_location_update)
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        Last update: {{ $fleet->last_location_update->diffForHumans() }}
                                    </small>
                                @endif
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('fleets.show', $fleet) }}">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('locations.history', $fleet) }}">
                                            <i class="bi bi-clock-history me-2"></i>Location History
                                        </a>
                                    </li>
                                    @if ($fleet->current_latitude && $fleet->current_longitude)
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="centerMapOnFleet({{ $fleet->current_latitude }}, {{ $fleet->current_longitude }})">
                                                <i class="bi bi-crosshair me-2"></i>Center on Map
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="openCheckInModal({{ $fleet->id }}, '{{ $fleet->fleet_number }}')">
                                            <i class="bi bi-geo-alt me-2"></i>Check-in Location
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="checkinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Check-in Fleet Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="checkinForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Fleet</label>
                            <input type="text" class="form-control" id="modalFleetNumber" readonly>
                            <input type="hidden" id="modalFleetId">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">Latitude *</label>
                                <input type="number" step="any" class="form-control" id="latitude" name="latitude"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label">Longitude *</label>
                                <input type="number" step="any" class="form-control" id="longitude" name="longitude"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Optional address description">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Optional notes about this location"></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="getCurrentLocation()">
                                <i class="bi bi-geo-alt me-1"></i>Use Current Location
                            </button>
                            Click to automatically fill coordinates with your current location.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Check-in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map;
        let markers = [];

        // Initialize map
        document.addEventListener('DOMContentLoaded', function() {
            // Default to Jakarta coordinates
            map = L.map('map').setView([-6.2088, 106.8456], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            loadFleetLocations();
        });

        // Load fleet locations from API
        function loadFleetLocations() {
            fetch('/api/fleet-locations')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateMapMarkers(data.data);
                    }
                })
                .catch(error => console.error('Error loading fleet locations:', error));
        }

        // Update map markers
        function updateMapMarkers(fleets) {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            // Add new markers
            fleets.forEach(fleet => {
                if (fleet.latitude && fleet.longitude) {
                    const marker = L.marker([fleet.latitude, fleet.longitude]).addTo(map);

                    const popupContent = `
                <div>
                    <h6>${fleet.fleet_number}</h6>
                    <p><strong>Type:</strong> ${fleet.vehicle_type}</p>
                    <p><strong>Driver:</strong> ${fleet.driver_name || 'Not assigned'}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${fleet.availability === 'available' ? 'success' : 'danger'}">${fleet.availability}</span></p>
                    <p><strong>Active Shipments:</strong> ${fleet.active_shipments}</p>
                    ${fleet.last_update ? `<small>Updated: ${fleet.last_update}</small>` : ''}
                    <br><br>
                    <a href="/fleets/${fleet.fleet_id}" class="btn btn-sm btn-primary">View Details</a>
                </div>
            `;

                    marker.bindPopup(popupContent);
                    markers.push(marker);
                }
            });
        }

        // Refresh locations
        function refreshLocations() {
            loadFleetLocations();

            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
        <i class="bi bi-check-circle me-2"></i>
        Locations refreshed successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
            document.body.appendChild(alert);

            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 3000);
        }

        // Center map on specific fleet
        function centerMapOnFleet(lat, lng) {
            map.setView([lat, lng], 15);
        }

        // Show location form modal
        function showLocationForm() {
            // If no fleet selected, show first available fleet
            @if ($fleets->count() > 0)
                openCheckInModal({{ $fleets->first()->id }}, '{{ $fleets->first()->fleet_number }}');
            @endif
        }

        // Open check-in modal
        function openCheckInModal(fleetId, fleetNumber) {
            document.getElementById('modalFleetId').value = fleetId;
            document.getElementById('modalFleetNumber').value = fleetNumber;

            // Clear form
            document.getElementById('checkinForm').reset();
            document.getElementById('modalFleetId').value = fleetId;
            document.getElementById('modalFleetNumber').value = fleetNumber;

            new bootstrap.Modal(document.getElementById('checkinModal')).show();
        }

        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                }, function(error) {
                    alert('Error getting location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        // Handle check-in form submission
        document.getElementById('checkinForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fleetId = document.getElementById('modalFleetId').value;
            const formData = {
                latitude: document.getElementById('latitude').value,
                longitude: document.getElementById('longitude').value,
                address: document.getElementById('address').value,
                notes: document.getElementById('notes').value
            };

            fetch(`/fleets/${fleetId}/checkin`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('checkinModal')).hide();

                        // Show success message
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
                        alert.style.top = '20px';
                        alert.style.right = '20px';
                        alert.style.zIndex = '9999';
                        alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                Location checked in successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
                        document.body.appendChild(alert);

                        // Refresh locations
                        setTimeout(() => {
                            refreshLocations();
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 2000);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while checking in location.');
                });
        });
    </script>
@endpush
