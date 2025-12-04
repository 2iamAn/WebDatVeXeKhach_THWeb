<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Đặt vé xe khách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 24px;
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s;
        }
        .navbar-custom .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .search-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            margin-top: 30px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .popular-routes {
            padding: 60px 0;
            background: white;
        }
        .route-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
            border: 2px solid transparent;
            cursor: pointer;
            text-decoration: none;
            color: #2c3e50;
            display: block;
        }
        .route-card:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            border-color: transparent;
        }
        .route-card i {
            font-size: 32px;
            margin-bottom: 15px;
            display: block;
        }
        .route-card h5 {
            font-weight: 600;
            margin: 0;
        }
        .welcome-badge {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-bus me-2"></i>Đặt Vé Xe Khách
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('user.home') }}">
                            <i class="fas fa-home me-1"></i> Trang chủ
                        </a>
                    </li>
                    @if(session('user'))
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-user me-1"></i> {{ session('user')->HoTen }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login.form') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            @if(session('user'))
                <div class="welcome-badge">
                    <i class="fas fa-user-check me-2"></i>Xin chào, <strong>{{ session('user')->HoTen }}</strong>
                </div>
            @endif
            <h1 class="display-4 fw-bold mb-3">Đặt vé xe khách dễ dàng</h1>
            <p class="lead mb-4">Nhiều nhà xe - Một điểm đặt. Tìm kiếm, so sánh giá và đặt vé chỉ trong vài bước</p>
            
            <div class="search-box">
                <h5 class="mb-4 text-dark"><i class="fas fa-search me-2 text-primary"></i>Tìm chuyến xe</h5>
                <form method="GET" action="{{ route('chuyenxe.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-dark">Điểm đi</label>
                            <input type="text" name="diem_di" class="form-control" placeholder="Nhập điểm đi">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark">Điểm đến</label>
                            <input type="text" name="diem_den" class="form-control" placeholder="Nhập điểm đến">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark">Ngày đi</label>
                            <input type="date" name="ngay_di" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-search">
                            <i class="fas fa-search me-2"></i>Tìm chuyến xe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Popular Routes -->
    <section class="popular-routes">
        <div class="container">
            <h2 class="section-title text-center">Tuyến đường phổ biến</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <a href="#" class="route-card">
                        <i class="fas fa-route"></i>
                        <h5>TP.HCM → Đà Lạt</h5>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="route-card">
                        <i class="fas fa-route"></i>
                        <h5>Hà Nội → Hải Phòng</h5>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="route-card">
                        <i class="fas fa-route"></i>
                        <h5>TP.HCM → Nha Trang</h5>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="route-card">
                        <i class="fas fa-route"></i>
                        <h5>Đà Nẵng → Huế</h5>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
