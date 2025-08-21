@extends('layouts.app')

@section('title', 'Track Shipment')
@section('page-title', 'Track Shipment')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-search me-2"></i>
                        Track Your Shipment
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tracking.track') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="tracking_number" class="form-label">Tracking Number</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">
                                    <i class="bi bi-box-seam"></i>
                                </span>
                                <input type="text" class="form-control @error('tracking_number') is-invalid @enderror"
                                    id="tracking_number" name="tracking_number" value="{{ old('tracking_number') }}"
                                    placeholder="Enter your tracking number (e.g., TRK20250821001)" required>
                                @error('tracking_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-search me-2"></i>
                                Track Shipment
                            </button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <h6>Sample Tracking Numbers:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><code>TRK20250821001</code> - In Transit</li>
                                    <li><code>TRK20250821002</code> - In Transit</li>
                                    <li><code>TRK20250821003</code> - Pending</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><code>TRK20250821004</code> - Delivered</li>
                                    <li><code>TRK20250821005</code> - In Transit</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-code-slash me-2"></i>
                        API Tracking
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">You can also track shipments programmatically using our API:</p>
                    <div class="bg-dark text-light p-3 rounded">
                        <code>
                            POST {{ url('/api/tracking') }}<br>
                            Content-Type: application/json<br><br>
                            {<br>
                            &nbsp;&nbsp;"tracking_number": "TRK20250821001"<br>
                            }
                        </code>
                    </div>

                    <div class="mt-3">
                        <h6>Try API Tracking:</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" id="api_tracking_number"
                                placeholder="Enter tracking number" value="TRK20250821001">
                            <button class="btn btn-outline-secondary" type="button" onclick="testApiTracking()">
                                Test API
                            </button>
                        </div>
                        <div id="api_result" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function testApiTracking() {
            const trackingNumber = document.getElementById('api_tracking_number').value;
            const resultDiv = document.getElementById('api_result');

            if (!trackingNumber) {
                resultDiv.innerHTML = '<div class="alert alert-warning">Please enter a tracking number</div>';
                return;
            }

            resultDiv.innerHTML = '<div class="alert alert-info">Loading...</div>';

            fetch('{{ url('/api/tracking') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        tracking_number: trackingNumber
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6>API Response:</h6>
                    <pre><code>${JSON.stringify(data, null, 2)}</code></pre>
                </div>
            `;
                    } else {
                        resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <strong>Error:</strong> ${data.message}
                </div>
            `;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = `
            <div class="alert alert-danger">
                <strong>Error:</strong> ${error.message}
            </div>
        `;
                });
        }
    </script>
@endpush
