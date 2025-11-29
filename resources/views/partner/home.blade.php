<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ Nhà xe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f4f6f9 0%, #e8f5f3 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            max-width: 700px;
            width: 100%;
            text-align: center;
        }
        .welcome-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 56px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .welcome-card h1 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .welcome-card p {
            color: #7f8c8d;
            font-size: 18px;
            margin-bottom: 30px;
        }
        .success-badge {
            background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .btn-dashboard {
            background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            border: none;
            margin: 10px;
        }
        .btn-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(79, 185, 159, 0.4);
            color: white;
        }
        .quick-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
        }
        .quick-links h5 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .link-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .link-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            text-decoration: none;
            color: #2c3e50;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .link-item:hover {
            background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(79, 185, 159, 0.3);
            border-color: transparent;
        }
        .link-item i {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }
        .link-item span {
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="welcome-icon">
                <i class="fas fa-bus"></i>
            </div>
            <h1>Chào mừng Nhà xe!</h1>
            <p>Xin chào, <strong>{{ session('user')->HoTen ?? 'Nhà xe' }}</strong></p>
            
            <div class="success-badge">
                <i class="fas fa-check-circle me-2"></i>Tài khoản của bạn đã được phê duyệt!
            </div>
            
            <p class="text-muted">Bạn đã đăng nhập với tư cách đối tác nhà xe. Vui lòng chọn một tùy chọn bên dưới để tiếp tục.</p>
            
            <a href="{{ route('partner.dashboard') }}" class="btn btn-dashboard">
                <i class="fas fa-tachometer-alt me-2"></i> Vào Dashboard
            </a>

            <div class="quick-links">
                <h5>Menu nhanh</h5>
                <div class="link-grid">
                    <a href="{{ route('partner.dashboard') }}" class="link-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('partner.trips') }}" class="link-item">
                        <i class="fas fa-route"></i>
                        <span>Chuyến xe</span>
                    </a>
                    <a href="{{ route('partner.tickets') }}" class="link-item">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Vé xe</span>
                    </a>
                    <a href="{{ route('partner.revenue') }}" class="link-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Doanh thu</span>
                    </a>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('logout') }}" class="text-danger text-decoration-none">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>