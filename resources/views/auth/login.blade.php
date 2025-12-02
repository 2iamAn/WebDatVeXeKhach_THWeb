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
    min-height: calc(100vh - 120px); /* trừ phần header/footer nếu có */
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #e0f6f1, #f6fffd);
}

.login-inner {
    max-width: 1100px;
    width: 100%;
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 40px 50px;
    gap: 40px;
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

@media (max-width: 992px) {
    .login-inner {
        flex-direction: column;
        text-align: center;
        padding: 30px 24px;
    }

    .login-left img {
        max-width: 320px;
        width: 100%;
        margin: 0 auto 10px;
    }

    .login-box {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .login-left h1 {
        font-size: 32px;
    }

    .login-left p {
        font-size: 20px;
    }
}
</style>

<div class="login-container">

    <div class="login-inner">

    <!-- BÊN TRÁI -->
    <div class="login-left">
        <h1>BUSTRIP</h1>
        <p>Nhiều nhà xe - Một điểm đặt</p>

        <img src="{{ asset('image/buss.png') }}" style="width:450px;">
        
    </div>

    <!-- FORM ĐĂNG NHẬP -->
    <div class="login-box">

        <h2 style="text-align:center; font-size:26px; margin-bottom:20px;">Đăng nhập tài khoản</h2>

        <div class="tab">
            <button class="active">ĐĂNG NHẬP</button>
            <a href="{{ route('register.form') }}"><button>ĐĂNG KÝ</button></a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="padding: 12px; border-radius: 8px; margin-bottom: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('pending_approval'))
            <div class="alert alert-warning" style="padding: 12px; border-radius: 8px; margin-bottom: 20px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">
                <i class="fa fa-info-circle"></i> Tài khoản của bạn đang chờ admin phê duyệt. Bạn sẽ nhận được thông báo khi tài khoản được kích hoạt.
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}" class="login-form" id="loginForm">
            @csrf

            <label class="form-label fw-semibold text-muted">Tên đăng nhập hoặc Email</label>
            <input type="text" name="TenDangNhap" id="loginEmail" class="form-input" placeholder="Nhập tên đăng nhập hoặc email" value="{{ $auto_email ?? old('TenDangNhap') }}" required>
            @error('TenDangNhap')
                <p class="text-danger small mb-2">{{ $message }}</p>
            @enderror
            <small class="text-muted">Bạn có thể đăng nhập bằng tên đăng nhập hoặc email</small>

            <label class="form-label fw-semibold text-muted mt-2">Mật khẩu</label>
            <input type="password" name="MatKhau" id="loginPassword" class="form-input" placeholder="Nhập mật khẩu" value="{{ $auto_password ?? '' }}" required>
            @error('MatKhau')
                <p class="text-danger small mb-2">{{ $message }}</p>
            @enderror

            @error('login_error')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn-login-orange" id="loginButton">
                @if(isset($auto_email) && isset($auto_password))
                    <i class="fa fa-sign-in-alt"></i> Đăng nhập ngay
                @else
                    Đăng nhập
                @endif
            </button>
        </form>

        @if(isset($auto_email) && isset($auto_password))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Highlight các trường đã được điền sẵn
                const emailInput = document.getElementById('loginEmail');
                const passwordInput = document.getElementById('loginPassword');
                
                if (emailInput && emailInput.value) {
                    emailInput.style.background = '#e8f5e9';
                    emailInput.style.borderColor = '#4FB99F';
                }
                
                if (passwordInput && passwordInput.value) {
                    passwordInput.style.background = '#e8f5e9';
                    passwordInput.style.borderColor = '#4FB99F';
                }
                
                // Tự động focus vào nút đăng nhập
                setTimeout(function() {
                    const loginButton = document.getElementById('loginButton');
                    if (loginButton) {
                        loginButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        loginButton.focus();
                    }
                }, 300);
            });
        </script>
        @endif

        <a href="#" style="color:#4FB99F; display:block; margin-top:15px; text-align:center; font-weight: 500;">
            Quên mật khẩu
        </a>

    </div>
    </div>
</div>

@endsection
