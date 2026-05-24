<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Quản trị')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f5f5f5; }

        /* Sidebar */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            background: #1a1a2e;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: all 0.3s;
        }
        #sidebar .sidebar-brand {
            padding: 20px;
            background: #16213e;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            display: block;
        }
        #sidebar .nav-link {
            color: #adb5bd;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background: #0f3460;
        }
        #sidebar .nav-section {
            color: #6c757d;
            font-size: 11px;
            text-transform: uppercase;
            padding: 15px 20px 5px;
            letter-spacing: 1px;
        }

        /* Main content */
        #main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
        }

        /* Topbar */
        #topbar {
            background: #fff;
            padding: 12px 20px;
            margin: -20px -20px 20px -20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Cards thống kê */
        .stat-card {
            border-radius: 12px;
            border: none;
            padding: 20px;
            color: #fff;
        }
        .stat-card .stat-icon {
            font-size: 40px;
            opacity: 0.8;
        }
        .stat-card .stat-number {
            font-size: 28px;
            font-weight: bold;
        }
    </style>

    @yield('styles')
</head>
<body>

{{-- Sidebar --}}
<div id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <i class="bi bi-clock"></i> Đồng Hồ Admin
    </a>

    <nav class="mt-2">
        <div class="nav-section">Tổng quan</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Sản phẩm</div>
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Danh mục
        </a>
        <a href="{{ route('admin.brands.index') }}"
           class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
            <i class="bi bi-award"></i> Thương hiệu
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-watch"></i> Sản phẩm
        </a>

        <div class="nav-section">Bán hàng</div>
        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> Đơn hàng
        </a>
        <a href="{{ route('admin.coupons.index') }}"
           class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> Mã giảm giá
        </a>
        <a href="{{ route('admin.reviews.index') }}"
           class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Đánh giá
        </a>

        <div class="nav-section">Nội dung</div>
        <a href="{{ route('admin.banners.index') }}"
           class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
            <i class="bi bi-image"></i> Banner
        </a>
        <a href="{{ route('admin.news.index') }}"
           class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i> Tin tức
        </a>

        <div class="nav-section">Hệ thống</div>
        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Người dùng
        </a>
        <a href="{{ route('home') }}" class="nav-link">
            <i class="bi bi-globe"></i> Xem website
        </a>
    </nav>
    {{-- Thêm vào sau phần "Hệ thống" --}}
        <div class="nav-section">AI</div>
            <a href="{{ route('admin.ai.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.ai.*') ? 'active' : '' }}">
                <i class="bi bi-robot"></i> AI Dashboard
            </a>
</div>

{{-- Main Content --}}
<div id="main-content">

    {{-- Topbar --}}
    <div id="topbar">
        <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted">Xin chào, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </button>
            </form>
        </div>
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Nội dung trang --}}
    @yield('content')

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@yield('scripts')
</body>
</html>