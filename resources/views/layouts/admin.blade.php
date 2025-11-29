<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Quản trị viên')</title>
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
                <span>Admin</span>
                <a href="{{ url('/logout') }}" class="btn-login">Đăng xuất</a>
            </div>

            <nav class="header-bottom-links">
                <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                <a href="{{ url('/admin/users') }}">Người dùng</a>
                <a href="{{ url('/admin/partners') }}">Đối tác</a>
                <a href="{{ url('/admin/reports') }}">Báo cáo</a>
            </nav>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>
</body>
</html>