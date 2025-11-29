<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Khách hàng')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <header class="main-header">
        <div class="header-grid">
            <div class="header-left">
                <img src="{{ asset('image/logo.png') }}" class="logo">
            </div>

            <div class="header-right">
                <div class="header-top-links">
                    <a href="#">Hợp tác với chúng tôi</a>
                    <a href="#">Đặt chỗ của tôi</a>
                    <a href="{{ url('/login-user') }}" class="btn-login">Đăng nhập Khách Hàng</a>
                </div>

                <nav class="header-bottom-links">
                    <a href="{{ url('/') }}">Trang Chủ</a>
                    <a href="{{ url('/tuyenduong') }}">Tuyến Đường</a>
                    <a href="{{ url('/vexe') }}">Vé Xe</a>
                    <a href="{{ url('/nhaxe') }}">Nhà Xe</a>
                    <a href="#">Liên Hệ</a>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>