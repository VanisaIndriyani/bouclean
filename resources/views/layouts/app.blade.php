<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bouclear - Bank Sampah Digital')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0dcaf0;
            --secondary: #198754;
            --bg-white: #ffffff;
            --bg-light: #f8f9fa;
            --text-dark: #212529;
            --sidebar-width: 250px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden; /* Add this to prevent horizontal scroll */
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0d6efd 0%, #198754 100%);
            z-index: 1050;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        .sidebar-brand {
            padding: 20px;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-brand i {
            color: var(--primary);
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }
        body.sidebar-collapsed .main-content {
            margin-left: 0;
        }
        .navbar {
            background-color: var(--bg-white) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 998;
        }
        .navbar .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.5em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            transition: transform 0.2s ease;
        }
        .navbar .dropdown-toggle.show::after {
            transform: rotate(-180deg);
        }
        .profile-dropdown {
            padding: 5px 12px;
            border-radius: 50px;
            transition: all 0.2s ease;
        }
        .profile-dropdown:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .card-stat {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        .card-stat .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .page-header {
            background: linear-gradient(135deg, #0dcaf0 0%, #198754 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2);
        }
        .page-header .btn-outline-secondary {
            border-color: rgba(255, 255, 255, 0.85);
            color: #fff;
        }
        .page-header .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #fff;
            color: #fff;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: #0bb8db;
            border-color: #0bb8db;
        }
        .btn-success {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .btn-success:hover {
            background-color: #146c43;
            border-color: #146c43;
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
        }
        .table tbody tr:hover {
            background-color: #f8faff;
        }
        .table td {
            vertical-align: middle;
            padding: 12px 15px;
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .pagination {
            margin-bottom: 0;
        }
        .card {
            border-radius: 15px;
        }
        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1045; /* Increased z-index */
        }
        .sidebar-overlay.show {
            display: block;
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand d-flex flex-column align-items-center py-4">
            <img src="{{ asset('img/Bougenville.png') }}" alt="Logo" class="mb-2" style="width: 100px; height: auto;">
            <span class="fw-bold text-white fs-4">Bouclear</span>
        </div>
        <ul class="nav flex-column mt-2">
            <li class="nav-item mt-2">
                <div class="nav-header px-4 py-2 text-white-50 small text-uppercase">Dashboard</div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
           

            <li class="nav-item">
                <div class="nav-header px-4 py-2 text-white-50 small text-uppercase">Data Dasawisma</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('warga.*') ? 'active' : '' }}" href="{{ route('warga.index') }}">
                    <i class="bi bi-people"></i> Data Warga
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('perpindahan.*') ? 'active' : '' }}" href="{{ route('perpindahan.index') }}">
                    <i class="bi bi-arrow-left-right"></i> Perpindahan Warga
                </a>
            </li>

            <li class="nav-item mt-2">
                <div class="nav-header px-4 py-2 text-white-50 small text-uppercase">Data Pilah Sampah</div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pilah-sampah.*') ? 'active' : '' }}" href="{{ route('pilah-sampah.index') }}">
                    <i class="bi bi-recycle"></i> Pilah Sampah
                </a>
            </li>

            @if(Auth::user()->role === 'admin')
            @php
                $dataMasterActive = request()->routeIs('wilayah.index');
                $dataMasterExpanded = $dataMasterActive ? 'show' : '';
            @endphp
            <li class="nav-item mt-2">
                <div class="nav-header px-4 py-2 text-white-50 small text-uppercase">Data Master</div>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between {{ $dataMasterActive ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#dataMasterMenu" aria-expanded="{{ $dataMasterActive ? 'true' : 'false' }}">
                    <span><i class="bi bi-gear"></i> Wilayah Administrasi</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse {{ $dataMasterExpanded }}" id="dataMasterMenu">
                    <ul class="nav flex-column ms-4 my-1">
                        <li class="nav-item">
                            <a class="nav-link py-2 {{ request()->routeIs('wilayah.index') && request('view') !== 'dasawisma' ? 'active' : '' }}" href="{{ route('wilayah.index') }}">
                                Wilayah Administrasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-2 {{ request()->routeIs('wilayah.index') && request('view') === 'dasawisma' ? 'active' : '' }}" href="{{ route('wilayah.index', ['view' => 'dasawisma', 'dasawisma' => 'all']) }}">
                                Dasawisma
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <li class="nav-item mt-2">
                <div class="nav-header px-4 py-2 text-white-50 small text-uppercase">Lainnya</div>
            </li>
            <li class="nav-item mt-1 px-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100 rounded-pill">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="modal fade" id="panduanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Panduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-muted">Panduan belum tersedia.</div>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <div class="main-content {{ !Auth::check() ? 'ms-0' : '' }}">
        @auth
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="btn btn-link text-dark me-2" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <div class="d-none d-lg-block text-muted small">
                        Semarang Utara, Plombokan, RW III RT 5
                    </div>
                    <div class="dropdown">
                        <a class="dropdown-toggle text-dark text-decoration-none d-flex align-items-center profile-dropdown" href="#" role="button" data-bs-toggle="dropdown">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-person-fill fs-6"></i>
                                </div>
                            @endif
                            <div class="d-none d-sm-block me-1">
                                <div class="fw-semibold small lh-1">{{ Auth::user()->name }}</div>
                                <small class="text-muted" style="font-size: 10px;">{{ ucfirst(Auth::user()->role) }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 py-2" style="min-width: 200px; border-radius: 12px;">
                            <li class="px-3 py-2 d-sm-none border-bottom mb-2">
                                <div class="fw-bold small">{{ Auth::user()->name }}</div>
                                <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear me-2 text-primary fs-5"></i> Edit Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2 fs-5"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        @endauth

        <main class="container-fluid p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            if (window.innerWidth >= 992) {
                // Desktop: Toggle collapsed class
                document.body.classList.toggle('sidebar-collapsed');
            } else {
                // Mobile: Toggle off-canvas
                document.getElementById('sidebar').classList.toggle('show');
                document.querySelector('.sidebar-overlay').classList.toggle('show');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
