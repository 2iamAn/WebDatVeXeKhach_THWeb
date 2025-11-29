<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhà Xe - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f6f9 0%, #e8f5f3 100%);
            background-attachment: fixed;
            overflow-x: hidden;
        }
        
        .partner-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 2px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 24px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-header h4 i {
            color: #4FB99F;
            font-size: 28px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            color: #ecf0f1;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-size: 15px;
            margin: 5px 10px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .sidebar-menu a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar-menu a:hover::before {
            left: 100%;
        }

        .sidebar-menu a i {
            width: 25px;
            margin-right: 15px;
            font-size: 18px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #4FB99F;
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar-menu a:hover i {
            transform: scale(1.2);
            color: #4FB99F;
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(79, 185, 159, 0.3), rgba(79, 185, 159, 0.1));
            border-left-color: #4FB99F;
            color: #4FB99F;
            font-weight: 600;
        }

        .sidebar-menu a.active i {
            color: #4FB99F;
        }

        .sidebar-divider {
            border-color: rgba(255,255,255,0.1);
            margin: 20px 15px;
        }

        .sidebar-menu .logout-link {
            color: #e74c3c;
            margin-top: 20px;
        }

        .sidebar-menu .logout-link:hover {
            background: rgba(231, 76, 60, 0.2);
            border-left-color: #e74c3c;
            color: #e74c3c;
        }

        .sidebar-menu .logout-link:hover i {
            color: #e74c3c;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            background: transparent;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-left: 5px solid #4FB99F;
        }

        .page-header h2 {
            margin: 0;
            color: #2c3e50;
            font-weight: 700;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header h2 i {
            color: #4FB99F;
            font-size: 32px;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transition: all 0.5s;
        }

        .stat-card:hover::before {
            top: -100%;
            right: -100%;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }

        .stat-card-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-card-title {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-card-value {
            color: #2c3e50;
            font-weight: 700;
            font-size: 32px;
            margin: 0;
        }

        /* Color Themes for Cards */
        .card-primary {
            background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
            color: white;
        }

        .card-primary .stat-card-title,
        .card-primary .stat-card-value {
            color: white;
        }

        .card-success {
            background: linear-gradient(135deg, #6bc4b0 0%, #4FB99F 100%);
            color: white;
        }

        .card-success .stat-card-title,
        .card-success .stat-card-value {
            color: white;
        }

        .card-info {
            background: linear-gradient(135deg, #67e8f9 0%, #4FB99F 100%);
            color: white;
        }

        .card-info .stat-card-title,
        .card-info .stat-card-value {
            color: white;
        }

        .card-warning {
            background: linear-gradient(135deg, #a8e6d4 0%, #6bc4b0 100%);
            color: white;
        }

        .card-warning .stat-card-title,
        .card-warning .stat-card-value {
            color: white;
        }

        /* Override Bootstrap colors */
        .text-primary {
            color: #4FB99F !important;
        }
        .bg-primary {
            background-color: #4FB99F !important;
        }
        .btn-primary {
            background-color: #4FB99F;
            border-color: #4FB99F;
        }
        .btn-primary:hover {
            background-color: #3a8f7a;
            border-color: #3a8f7a;
        }
        .text-info {
            color: #6bc4b0 !important;
        }
        .bg-info {
            background-color: #6bc4b0 !important;
        }
        .text-success {
            color: #4FB99F !important;
        }
        .bg-success {
            background-color: #4FB99F !important;
        }
        /* Responsive */
        @media (max-width: 768px) {
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

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>

<body>
    <div class="partner-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h4>
                    <i class="fas fa-bus"></i>
                    @if(isset($tenNhaXe) && $tenNhaXe)
                        Nhà Xe {{ $tenNhaXe }}
                    @else
                        Nhà Xe
                    @endif
                </h4>
            </div>

            <div class="sidebar-menu">
                <a href="{{ route('partner.dashboard') }}" class="{{ request()->routeIs('partner.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>

                <a href="{{ route('partner.routes') }}" class="{{ request()->routeIs('partner.routes*') ? 'active' : '' }}">
                    <i class="fas fa-map-marked-alt"></i>
                    Quản lý tuyến đường
                </a>

                <a href="{{ route('partner.vehicles') }}" class="{{ request()->routeIs('partner.vehicles*') ? 'active' : '' }}">
                    <i class="fas fa-bus"></i>
                    Quản lý xe
                </a>

                <a href="{{ route('partner.trips') }}" class="{{ request()->routeIs('partner.trips*') ? 'active' : '' }}">
                    <i class="fas fa-route"></i>
                    Quản lý chuyến đi
                </a>

                <a href="{{ route('partner.seats') }}" class="{{ request()->routeIs('partner.seats*') ? 'active' : '' }}">
                    <i class="fas fa-couch"></i>
                    Sơ đồ ghế
                </a>

                <a href="{{ route('partner.tickets') }}" class="{{ request()->routeIs('partner.tickets*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    Tình trạng vé
                </a>

                <a href="{{ route('partner.revenue') }}" class="{{ request()->routeIs('partner.revenue*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    Báo cáo doanh thu
                </a>

                <hr class="sidebar-divider">

                <a href="{{ route('logout') }}" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Đăng xuất
                </a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
