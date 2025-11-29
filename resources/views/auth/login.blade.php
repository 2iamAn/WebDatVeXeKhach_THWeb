@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<style>
.login-container {
    display: flex;
    justify-content: space-between;
    padding: 40px 80px;
    background: #fff;
}

.login-left h1 {
    font-size: 40px;
    font-weight: 800;
    color: #4FB99F;
}

.login-left p {
    font-size: 26px;
    margin-top: -10px;
    color: #3a8f7a;
}

.login-box {
    width: 420px;
    padding: 30px 0;
}

.tab {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.tab button {
    background: none;
    border: none;
    padding: 10px 25px;
    font-size: 18px;
    cursor: pointer;
}

.tab .active {
    color: #4FB99F;
    border-bottom: 3px solid #4FB99F;
}

.form-input {
    width: 100%;
    padding: 14px 12px;
    border: 2px solid rgba(168, 230, 212, 0.6);
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #4FB99F;
    box-shadow: 0 0 0 3px rgba(79, 185, 159, 0.15);
}

.btn-login-orange {
    margin-top: 10px;
    width: 100%;
    background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
    padding: 14px;
    color: #fff;
    border: none;
    border-radius: 30px;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(79, 185, 159, 0.4);
}

.btn-login-orange:hover {
    background: linear-gradient(135deg, #3a8f7a 0%, #2d6f5e 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(79, 185, 159, 0.5);
    color: #fff;
}
</style>

<div class="login-container">

    <!-- BÊN TRÁI -->
    <div class="login-left">
        <h1>BUSTRIP</h1>
        <p>Nhiều nhà xe - Một điểm đặt</p>

        <img src="{{ asset('image/bus-login.png') }}" style="width:450px;">
        
    </div>

    <!-- FORM ĐĂNG NHẬP -->
    <div class="login-box">

        <h2 style="text-align:center; font-size:26px; margin-bottom:20px;">Đăng nhập tài khoản</h2>

        <div class="tab">
            <button class="active">ĐĂNG NHẬP</button>
            <a href="{{ route('register.form') }}"><button>ĐĂNG KÝ</button></a>
        </div>

        <form method="POST" action="{{ route('login.process') }}" class="login-form">
            @csrf

            <label class="form-label fw-semibold text-muted">Tên đăng nhập hoặc Email</label>
            <input type="text" name="TenDangNhap" class="form-input" placeholder="Nhập tên đăng nhập hoặc email" value="{{ old('TenDangNhap') }}" required>
            @error('TenDangNhap')
                <p class="text-danger small mb-2">{{ $message }}</p>
            @enderror
            <small class="text-muted">Bạn có thể đăng nhập bằng tên đăng nhập hoặc email</small>

            <label class="form-label fw-semibold text-muted mt-2">Mật khẩu</label>
            <input type="password" name="MatKhau" class="form-input" placeholder="Nhập mật khẩu" required>
            @error('MatKhau')
                <p class="text-danger small mb-2">{{ $message }}</p>
            @enderror

            @error('login_error')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror

            <button class="btn-login-orange">Đăng nhập</button>
        </form>

        <a href="#" style="color:#4FB99F; display:block; margin-top:15px; text-align:center; font-weight: 500;">
            Quên mật khẩu
        </a>

    </div>
</div>

@endsection
