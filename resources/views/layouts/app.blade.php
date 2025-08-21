<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fleet & Shipment Management') - {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .map-container {
            height: 400px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <h4 class="text-white mb-4">
                        <i class="bi bi-truck"></i> Fleet Management
                    </h4>

                    <nav class="nav flex-column">
                        <a class="nav-link py-2 px-3 mb-1 {{ request()->routeIs('dashboard') ? 'bg-primary' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>

                        <a class="nav-link py-2 px-3 mb-1 {{ request()->routeIs('fleets.*') ? 'bg-primary' : '' }}"
                            href="{{ route('fleets.index') }}">
                            <i class="bi bi-truck me-2"></i> Fleet Management
                        </a>

                        <a class="nav-link py-2 px-3 mb-1 {{ request()->routeIs('shipments.*') ? 'bg-primary' : '' }}"
                            href="{{ route('shipments.index') }}">
                            <i class="bi bi-box-seam me-2"></i> Shipments
                        </a>

                        <a class="nav-link py-2 px-3 mb-1 {{ request()->routeIs('bookings.*') ? 'bg-primary' : '' }}"
                            href="{{ route('bookings.index') }}">
                            <i class="bi bi-calendar-check me-2"></i> Bookings
                        </a>

                        <a class="nav-link py-2 px-3 mb-1 {{ request()->routeIs('tracking.*') ? 'bg-primary' : '' }}"
                            href="{{ route('tracking.index') }}">
                            <i class="bi bi-search me-2"></i> Track Shipment
                        </a>

                        <a class="nav-link py-2 px-3 mb-1 {{ request()->routeIs('locations.*') ? 'bg-primary' : '' }}"
                            href="{{ route('locations.index') }}">
                            <i class="bi bi-geo-alt me-2"></i> Fleet Locations
                        </a>

                        <div class="dropdown">
                            <a class="nav-link py-2 px-3 mb-1 dropdown-toggle {{ request()->routeIs('reports.*') ? 'bg-primary' : '' }}"
                                href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-graph-up me-2"></i> Reports
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-primary"
                                        href="{{ route('reports.fleet-performance') }}">Fleet
                                        Performance</a></li>
                                <li><a class="dropdown-item text-primary"
                                        href="{{ route('reports.shipment-status') }}">Shipment
                                        Status</a></li>
                                <li><a class="dropdown-item text-primary" href="{{ route('reports.revenue') }}">Revenue
                                        Report</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                        <div class="container-fluid">
                            <span class="navbar-brand">@yield('page-title', 'Dashboard')</span>
                            <div class="navbar-nav ms-auto">
                                <span class="nav-link">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ now()->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                    </nav>

                    <div class="container-fluid p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>
