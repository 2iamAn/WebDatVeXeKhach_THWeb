<header class="main-header">
    <nav class="navbar navbar-expand-lg navbar-main">
        <div class="container d-flex align-items-center">
            {{-- Logo + tên thương hiệu --}}
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none me-3">
                <img src="{{ asset('image/logo.png') }}" alt="BusTrip" class="brand-logo">
            </a>

            {{-- Nút toggle trên mobile --}}
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                {{-- Menu chính ở giữa --}}
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ url('/') }}">Trang Chủ</a>
                    </li>
                    <li class="nav-item dropdown">
                        @php
                            // Lấy tất cả tuyến có ít nhất 1 chuyến đang chạy
                            $allTuyens = \App\Models\TuyenDuong::with(['chuyenXe' => function($q) {
                                $q->with('nhaXe');
                            }])->get();

                            // Gom theo cặp (Điểm đi - Điểm đến) để không trùng lặp
                            // Dùng lower + trim để tránh khác biệt do hoa/thường hoặc khoảng trắng
                            $tuyensDropdown = $allTuyens
                                ->filter(function($tuyen) {
                                    return $tuyen->chuyenXe->count() > 0;
                                })
                                ->groupBy(function($tuyen) {
                                    $diemDi = \Illuminate\Support\Str::lower(trim($tuyen->DiemDi));
                                    $diemDen = \Illuminate\Support\Str::lower(trim($tuyen->DiemDen));
                                    return $diemDi . '|' . $diemDen;
                                })
                                ->map(function($group) {
                                    return $group->first();
                                })
                                // Loại trùng thêm lần nữa dựa trên slug (bỏ dấu, chuẩn hóa khoảng trắng)
                                ->unique(function($tuyen) {
                                    return \Illuminate\Support\Str::slug($tuyen->DiemDi . '-' . $tuyen->DiemDen);
                                })
                                ->sortBy('DiemDi')
                                ->sortBy('DiemDen')
                                ->values();
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
                                        {{-- Khi nhấn tuyến, chuyển sang trang Tuyến đường với filter để hiển thị các nhà xe chạy tuyến đó --}}
                                        <a class="dropdown-item" href="{{ route('tuyenduong.index', [
                                            'diem_di' => $tuyen->DiemDi,
                                            'diem_den' => $tuyen->DiemDen,
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
                </ul>

                {{-- Khu vực tài khoản / liên kết phụ bên phải --}}
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('partner.request') }}" class="text-white fw-semibold text-decoration-none d-none d-lg-inline">
                        Hợp tác với chúng tôi
                    </a>
                    @if(session('user'))
                        <a href="{{ route('vexe.booking') }}" class="text-white fw-semibold text-decoration-none">
                            Đặt chỗ của tôi
                        </a>
                        <span class="user-chip d-none d-md-inline-flex">
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
    </nav>
</header>