@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')

<style>
.register-container {
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #e0f6f1, #f6fffd);
}

.register-inner {
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

.register-left h1 {
    font-size: 40px;
    font-weight: 800;
    color: #4FB99F;
}

.register-left p {
    font-size: 26px;
    margin-top: -10px;
    color: #3a8f7a;
}

.register-box {
    width: 450px;
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

.form-group {
    margin-bottom: 15px;
}

.form-input.is-invalid {
    border-color: #dc3545;
    background-color: #fff5f5;
}

.form-input.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
}

.error-message {
    display: block;
    color: #dc3545;
    font-size: 13px;
    margin-top: -10px;
    margin-bottom: 10px;
    padding-left: 5px;
}

.error-message i {
    margin-right: 5px;
}

.btn-register-orange {
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

.btn-register-orange:hover {
    background: linear-gradient(135deg, #3a8f7a 0%, #2d6f5e 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(79, 185, 159, 0.5);
    color: #fff;
}

@media (max-width: 992px) {
    .register-inner {
        flex-direction: column;
        text-align: center;
        padding: 30px 24px;
    }

    .register-left img {
        max-width: 320px;
        width: 100%;
        margin: 0 auto 10px;
    }

    .register-box {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .register-left h1 {
        font-size: 32px;
    }

    .register-left p {
        font-size: 20px;
    }
}
</style>

<div class="register-container">

    <div class="register-inner">

    <!-- BÊN TRÁI -->
    <div class="register-left">
        <h1>BUSTRIP</h1>
        <p>Nhiều nhà xe - Một điểm đặt</p>

        <img src="{{ asset('image/buss.png') }}" style="width:450px;">

    </div>

    <!-- FORM ĐĂNG KÝ -->
    <div class="register-box">

        <h2 style="text-align:center; font-size:26px; margin-bottom:20px;">Đăng ký tài khoản</h2>

        <div class="tab">
            <a href="{{ route('login.form') }}"><button>ĐĂNG NHẬP</button></a>
            <button class="active">ĐĂNG KÝ</button>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" style="padding: 12px; border-radius: 8px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="padding: 12px; border-radius: 8px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                <ul class="mb-0" style="padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.process') }}" id="registerForm">
            @csrf

            <!-- Họ và tên -->
            <div class="form-group">
                <input 
                    type="text" 
                    name="HoTen" 
                    class="form-input @error('HoTen') is-invalid @enderror" 
                    placeholder="Họ và tên" 
                    value="{{ old('HoTen') }}" 
                    required
                    minlength="2"
                    maxlength="100"
                >
                @error('HoTen')
                    <small class="error-message">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </small>
                @enderror
            </div>

            <!-- Tên đăng nhập -->
            <div class="form-group">
                <input 
                    type="text" 
                    name="TenDangNhap" 
                    class="form-input @error('TenDangNhap') is-invalid @enderror" 
                    placeholder="Tên đăng nhập (chỉ chữ, số, dấu _)" 
                    value="{{ old('TenDangNhap') }}" 
                    required
                    minlength="3"
                    maxlength="50"
                    pattern="[a-zA-Z0-9_]+"
                    title="Chỉ được chứa chữ cái, số và dấu gạch dưới"
                >
                @error('TenDangNhap')
                    <small class="error-message">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </small>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <input 
                    type="email" 
                    name="Email" 
                    class="form-input @error('Email') is-invalid @enderror" 
                    placeholder="Email" 
                    value="{{ $verified_email ?? old('Email') }}" 
                    required 
                    readonly 
                    style="background: #f8f9fa; cursor: not-allowed;"
                >
                @if(isset($verified_email))
                    <small style="color: #4FB99F; display: block; margin-top: -10px; margin-bottom: 15px;">
                        <i class="fa fa-check-circle"></i> Email đã được xác thực: {{ $verified_email }}
                    </small>
                @endif
                @error('Email')
                    <small class="error-message">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </small>
                @enderror
            </div>

            <!-- Mật khẩu -->
            <div class="form-group">
                <input 
                    type="password" 
                    name="MatKhau" 
                    class="form-input @error('MatKhau') is-invalid @enderror" 
                    placeholder="Mật khẩu (tối thiểu 4 ký tự)" 
                    required
                    minlength="4"
                    maxlength="255"
                >
                @error('MatKhau')
                    <small class="error-message">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </small>
                @enderror
            </div>

            <!-- Số điện thoại -->
            <div class="form-group">
                <input 
                    type="text" 
                    name="SDT" 
                    class="form-input @error('SDT') is-invalid @enderror" 
                    placeholder="Số điện thoại (chỉ số, 10-15 chữ số)" 
                    value="{{ old('SDT') }}" 
                    required
                    minlength="10"
                    maxlength="15"
                    pattern="[0-9]+"
                    title="Chỉ được nhập số"
                >
                @error('SDT')
                    <small class="error-message">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </small>
                @enderror
            </div>

            <button type="submit" class="btn-register-orange" id="registerButton">
                <i class="fa fa-user-plus"></i> Đăng ký
            </button>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('registerForm');
                const emailInput = document.querySelector('input[name="Email"]');
                
                // Kiểm tra email đã được xác thực trước khi submit
                form?.addEventListener('submit', function(e) {
                    if (emailInput && (!emailInput.value || !emailInput.hasAttribute('readonly'))) {
                        e.preventDefault();
                        alert('Vui lòng xác thực email trước khi đăng ký!');
                        window.location.href = '{{ route("verification.email", ["type" => "register"]) }}';
                        return false;
                    }
                });

                // Real-time validation cho số điện thoại
                const sdtInput = document.querySelector('input[name="SDT"]');
                sdtInput?.addEventListener('input', function(e) {
                    // Chỉ cho phép nhập số
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });

                // Real-time validation cho tên đăng nhập
                const usernameInput = document.querySelector('input[name="TenDangNhap"]');
                usernameInput?.addEventListener('input', function(e) {
                    // Chỉ cho phép chữ, số và dấu gạch dưới
                    e.target.value = e.target.value.replace(/[^a-zA-Z0-9_]/g, '');
                });
            });
        </script>

    </div>
    </div>
</div>

@endsection
