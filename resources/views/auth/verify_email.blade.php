@extends('layouts.app')

@section('title', 'Xác thực Email')

@section('content')

<style>
.verify-container {
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #e0f6f1, #f6fffd);
}

.verify-box {
    max-width: 500px;
    width: 100%;
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
    padding: 50px 40px;
    text-align: center;
}

.verify-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    font-size: 40px;
    color: white;
}

.verify-box h2 {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.verify-box p {
    color: #666;
    font-size: 16px;
    margin-bottom: 30px;
    line-height: 1.6;
}

.form-input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid rgba(168, 230, 212, 0.6);
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #4FB99F;
    box-shadow: 0 0 0 3px rgba(79, 185, 159, 0.15);
}

.btn-verify {
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
    margin-top: 10px;
}

.btn-verify:hover {
    background: linear-gradient(135deg, #3a8f7a 0%, #2d6f5e 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(79, 185, 159, 0.5);
    color: #fff;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #4FB99F;
    text-decoration: none;
    font-size: 15px;
}

.back-link:hover {
    text-decoration: underline;
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<div class="verify-container">
    <div class="verify-box">
        <div class="verify-icon">
            <i class="fa fa-envelope"></i>
        </div>
        
        <h2>Xác thực Email</h2>
        <p>
            @if($type === 'partner')
                Để đăng ký hợp tác nhà xe, vui lòng nhập email của bạn. 
                Chúng tôi sẽ gửi mã xác thực đến email này.
            @else
                Để đăng ký tài khoản, vui lòng nhập email của bạn. 
                Chúng tôi sẽ gửi mã xác thực đến email này.
            @endif
        </p>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('email_exists'))
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fa fa-exclamation-triangle"></i> <strong>Email đã tồn tại!</strong><br>
                Email này đã được sử dụng trong hệ thống. Vui lòng:
                <ul style="margin-top: 10px; margin-bottom: 0; padding-left: 20px;">
                    <li>Sử dụng email khác để đăng ký, hoặc</li>
                    <li><a href="{{ route('login.form') }}" style="color: #721c24; font-weight: 600;">Đăng nhập</a> nếu đây là tài khoản của bạn</li>
                </ul>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0" style="text-align: left; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            
            <input 
                type="email" 
                name="email" 
                class="form-input" 
                placeholder="Nhập địa chỉ email của bạn" 
                value="{{ old('email') }}" 
                required
                autofocus
            >
            
            @if($type === 'partner')
                <input 
                    type="text" 
                    name="name" 
                    class="form-input" 
                    placeholder="Tên người đại diện (tùy chọn)" 
                    value="{{ old('name') }}"
                >
            @endif

            <button type="submit" class="btn-verify">
                <i class="fa fa-paper-plane"></i> Gửi mã xác thực
            </button>
        </form>

        <a href="{{ $type === 'partner' ? route('partner.request') : route('register.form') }}" class="back-link">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

@endsection
