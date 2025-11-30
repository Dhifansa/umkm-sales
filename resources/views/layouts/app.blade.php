<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UMKM Sales') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        .sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
        }
        .main-content {
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 30px;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .badge {
            padding: 8px 15px;
            border-radius: 20px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="text-center py-4">
                    <h4 class="text-white fw-bold">
                        <i class="bi bi-shop"></i> UMKM Sales
                    </h4>
                </div>

                <nav class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i> Kasir / POS
                    </a>
                    <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> Transaksi
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Produk
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Kategori
                    </a>
                    <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Pelanggan
                    </a>

                    <hr class="text-white mx-3">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Navbar -->
                <div class="navbar-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">@yield('page-title')</h5>
                    </div>
                    <div>
                        <span class="me-3"><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</span>
                        <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>
</html>
