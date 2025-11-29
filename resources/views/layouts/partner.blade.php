<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Đối tác nhà xe')</title>
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
                <span>Đối tác: {{ session('role') }}</span>
                <a href="{{ url('/logout') }}" class="btn-login">Đăng xuất</a>
            </div>

            <nav class="header-bottom-links">
                <a href="{{ url('/partner/dashboard') }}">Tổng quan</a>
                <a href="{{ url('/partner/chuyenxe') }}">Chuyến xe</a>
                <a href="{{ url('/partner/donhang') }}">Đơn đặt chỗ</a>
                <a href="{{ url('/partner/doanhthu') }}">Doanh thu</a>
            </nav>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>
</body>
</html>