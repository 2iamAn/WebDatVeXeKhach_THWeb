<header class="main-header">
    <div class="topbar">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 text-white small fw-semibold">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div class="topbar-links d-flex flex-wrap align-items-center gap-3">
                <a href="{{ route('partner.request') }}">Hợp tác với chúng tôi</a>
                <a href="{{ route('vexe.booking') }}" class="{{ request()->routeIs('vexe.booking') ? 'active' : '' }}" style="{{ request()->routeIs('vexe.booking') ? 'font-weight: 700; text-decoration: underline;' : '' }}">Đặt chỗ của tôi</a>
                @if(session('user'))
                    <span class="user-chip">
                        <i class="fa-solid fa-user-check me-1"></i>
                        {{ session('user')->HoTen }}
                    </span>
                    <a href="{{ route('logout') }}" class="btn-logout-custom">Đăng xuất</a>
                @else
                    <a href="{{ route('login.form') }}" class="btn-login-custom">Đăng nhập</a>
                    <a href="{{ route('register.form') }}" class="btn-register-custom">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Logo ở giữa 2 phần -->
    <div class="header-logo-center">
        <a href="{{ url('/') }}" class="navbar-brand-center">
            <img src="{{ asset('image/logo.png') }}" alt="BusTrip" class="brand-logo-center">
        </a>
    </div>

    <nav class="navbar navbar-expand-lg navbar-main">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ url('/') }}">Trang Chủ</a>
                    </li>
                    <li class="nav-item dropdown">
                        @php
                            // Lấy 3 tuyến duy nhất từ database (nhóm theo DiemDi + DiemDen)
                            // Chỉ lấy các tuyến có chuyến xe
                            $allTuyens = \App\Models\TuyenDuong::with(['chuyenXe' => function($q) {
                                $q->with('nhaXe');
                            }])->get();
                            
                            $tuyensDropdown = $allTuyens
                            ->filter(function($tuyen) {
                                return $tuyen->chuyenXe->count() > 0;
                            })
                            ->groupBy(function($tuyen) {
                                return $tuyen->DiemDi . '|' . $tuyen->DiemDen;
                            })
                            ->map(function($group) {
                                return $group->first();
                            })
                            ->values()
                            ->take(3);
                        @endphp
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('tuyenduong.*') || request()->routeIs('chuyenxe.search') ? 'active' : '' }}" 
                           href="#" 
                           id="tuyenduongDropdown" 
                           role="button" 
                           data-bs-toggle="dropdown" 
                           aria-expanded="false">
                            Tuyến Đường
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="tuyenduongDropdown">
                            @if($tuyensDropdown->count() > 0)
                                @foreach($tuyensDropdown as $tuyen)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('chuyenxe.search', [
                                            'diem_di' => $tuyen->DiemDi, 
                                            'diem_den' => $tuyen->DiemDen,
                                            'ngay_khoi_hanh' => date('Y-m-d'),
                                            'so_ghe' => 1
                                        ]) }}">
                                            <i class="fas fa-route me-2"></i>{{ $tuyen->DiemDi }} → {{ $tuyen->DiemDen }}
                                        </a>
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('tuyenduong.index') }}">
                                    <i class="fas fa-list me-2"></i>Xem tất cả tuyến
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vexe.index') ? 'active' : '' }}" href="{{ route('vexe.index') }}">Vé Xe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Liên Hệ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>