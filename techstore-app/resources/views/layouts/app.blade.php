<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechStore') - E-Commerce Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            --tw-backdrop-blur: blur(0);
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #1e40af 0%, #0c4a6e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #60a5fa;
            padding-left: 16px;
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #60a5fa;
            padding-left: 16px;
            font-weight: 600;
        }
        
        .sidebar-title {
            padding: 20px;
            font-size: 18px;
            font-weight: 800;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 40px;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            min-height: 100vh;
        }
        
        .navbar-top {
            background: white;
            padding: 25px 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        
        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 14px;
        }
        
        .breadcrumb {
            background: transparent;
            margin-bottom: 20px;
            padding: 0;
        }
        
        .card {
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            margin-bottom: 20px;
            background: white;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #0c4a6e 100%);
            color: white;
            border: none;
            padding: 18px 20px;
            border-radius: 11px 11px 0 0;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1e40af 0%, #0c4a6e 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
            transform: translateY(-2px);
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            text-align: center;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #d1d5db;
        }
        
        .stat-card .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 8px;
        }
        
        .stat-card .stat-label {
            color: #6b7280;
            margin-top: 0;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 14px 18px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #7f1d1d;
            border: 1px solid #fecaca;
        }
        
        .section-label {
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
    @yield('extra-css')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="sidebar w-72">
        <div class="sidebar-title">
            <i class="fas fa-store text-2xl"></i>
            <span>TechStore</span>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-house text-sm"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="section-label">Inventory Management</div>
            
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="fas fa-box text-sm"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags text-sm"></i>
                <span>Categories</span>
            </a>
            
            <div class="section-label">Sales & Orders</div>
            
            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <i class="fas fa-receipt text-sm"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="fas fa-users text-sm"></i>
                <span>Customers</span>
            </a>
            
            <div class="section-label">Customer Feedback</div>
            
            <a href="{{ route('reviews.index') }}" class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                <i class="fas fa-star text-sm"></i>
                <span>Reviews</span>
            </a>
            
            <div class="section-label">Analytics & Reports</div>
            
            <a href="{{ route('analytics') }}" class="nav-link {{ request()->routeIs('analytics') ? 'active' : '' }}">
                <i class="fas fa-chart-line text-sm"></i>
                <span>Analytics</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="navbar-top flex justify-between items-center">
            <div>
                <h2 class="page-title">@yield('page-title', 'Dashboard')</h2>
                @if (View::hasSection('page-subtitle'))
                    <p class="page-subtitle">@yield('page-subtitle')</p>
                @else
                    <p class="page-subtitle">Welcome back! Here's what's happening today.</p>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right text-sm">
                    <p class="text-gray-900 font-600">Admin User</p>
                    <p class="text-gray-500 text-xs">Logged in</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                    A
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-6" role="alert">
                <strong><i class="fas fa-exclamation-circle"></i> Oops! Something went wrong.</strong>
                <ul class="mb-0 mt-2 ml-4">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-6" role="alert">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>

    @yield('extra-js')
</body>
</html>
