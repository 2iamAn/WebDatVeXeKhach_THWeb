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

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.process') }}">
            @csrf

            <input type="text" name="HoTen" class="form-input" placeholder="Họ và tên" value="{{ old('HoTen') }}" required>
            <input type="text" name="TenDangNhap" class="form-input" placeholder="Tên đăng nhập" value="{{ old('TenDangNhap') }}" required>

            <input type="email" name="Email" class="form-input" placeholder="Email" value="{{ old('Email') }}" required>

            <input type="password" name="MatKhau" class="form-input" placeholder="Mật khẩu" required>

            <input type="text" name="SDT" class="form-input" placeholder="Số điện thoại" value="{{ old('SDT') }}" required>

            <button class="btn-register-orange">Đăng ký</button>
        </form>

    </div>
    </div>
</div>

@endsection
