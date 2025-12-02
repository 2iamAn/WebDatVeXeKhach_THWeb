@extends('layouts.app')

@section('title', 'Hợp tác với chúng tôi')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        background: #f1f8f6;
    }
    .partner-box {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,.1);
        overflow: hidden;
    }
    .left-banner {
        background: url('{{ asset('image/dkdoitac.jpg') }}') no-repeat center center;
        background-size: cover;
        background-position: center;
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        position: relative;
        overflow: hidden;
    }
    @media (max-width: 991px) {
        .left-banner {
            min-height: 300px;
        }
    }
    .title {
        font-weight: 700;
        color: #00a884;
    }
    .btn-submit {
        background: #00a884;
        color: white;
        font-weight: 600;
    }
    .btn-submit:hover {
        background: #028e71;
    }
    .label {
        font-weight: 600;
        margin-bottom: 4px;
        color: #333;
    }
    .form-control.is-invalid {
        border-color: #dc3545;
        background-color: #fff5f5;
    }
    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    .error-message {
        display: block;
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
        padding-left: 5px;
    }
    .error-message i {
        margin-right: 5px;
    }
</style>

<div class="container py-5" style="margin-top: 40px;">

    <div class="text-center mb-4">
        <h2 class="title">Hợp tác với chúng tôi</h2>
        <p class="text-muted">Kết nối – Phát triển – Đồng hành cùng Bustrip</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10 partner-box">
            <div class="row">

                <!-- LEFT IMAGE -->
                <div class="col-lg-5 left-banner d-none d-lg-block"></div>

                <!-- FORM -->
                <div class="col-lg-7 p-5">

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(isset($lyDoTuChoi) && $lyDoTuChoi)
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">
                                <i class="fa fa-exclamation-triangle"></i> Yêu cầu hợp tác đã bị từ chối
                            </h5>
                            <hr>
                            <p class="mb-2"><strong>Lý do từ chối:</strong></p>
                            <p class="mb-0">{{ $lyDoTuChoi }}</p>
                            <hr>
                            <p class="mb-0 small">Vui lòng bổ sung thông tin và gửi lại yêu cầu hợp tác.</p>
                        </div>
                    @endif

                    <h4 class="mb-4">Đăng ký hợp tác nhà xe</h4>

                    <form action="{{ route('partner.send') }}" method="POST" id="partnerForm">
                        @csrf

                        <div class="mb-3">
                            <label class="label">Tên nhà xe <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="TenNhaXe" 
                                class="form-control @error('TenNhaXe') is-invalid @enderror" 
                                required 
                                placeholder="VD: Nhà xe Phương Trang"
                                value="{{ old('TenNhaXe') }}"
                                minlength="2"
                                maxlength="150"
                            >
                            @error('TenNhaXe')
                                <small class="error-message">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="label">Người đại diện <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="NguoiDaiDien" 
                                class="form-control @error('NguoiDaiDien') is-invalid @enderror" 
                                required 
                                placeholder="Tên đại diện nhà xe"
                                value="{{ old('NguoiDaiDien') }}"
                                minlength="2"
                                maxlength="100"
                            >
                            @error('NguoiDaiDien')
                                <small class="error-message">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="label">Email liên hệ <span class="text-danger">*</span></label>
                            <input 
                                type="email" 
                                name="Email" 
                                class="form-control @error('Email') is-invalid @enderror" 
                                required 
                                placeholder="nhaxe@gmail.com" 
                                value="{{ $verified_email ?? old('Email') }}" 
                                readonly 
                                style="background: #f8f9fa; cursor: not-allowed;"
                            >
                            @if(isset($verified_email))
                                <small style="color: #00a884; display: block; margin-top: 5px;">
                                    <i class="fa fa-check-circle"></i> Email đã được xác thực: {{ $verified_email }}
                                </small>
                            @endif
                            @error('Email')
                                <small class="error-message">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                            <small class="text-muted">Email này sẽ được dùng để đăng nhập vào hệ thống</small>
                        </div>

                        <div class="mb-3">
                            <label class="label">Mật khẩu <span class="text-danger">*</span></label>
                            <input 
                                type="password" 
                                name="MatKhau" 
                                class="form-control @error('MatKhau') is-invalid @enderror" 
                                required 
                                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" 
                                minlength="6"
                                maxlength="255"
                            >
                            @error('MatKhau')
                                <small class="error-message">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                            <small class="text-muted">Mật khẩu này sẽ được dùng để đăng nhập sau khi được admin phê duyệt</small>
                        </div>

                        <div class="mb-3">
                            <label class="label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input 
                                type="password" 
                                name="MatKhau_confirmation" 
                                class="form-control @error('MatKhau_confirmation') is-invalid @enderror" 
                                required 
                                placeholder="Nhập lại mật khẩu" 
                                minlength="6"
                            >
                            @error('MatKhau_confirmation')
                                <small class="error-message">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="label">Số điện thoại <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="SDT" 
                                class="form-control @error('SDT') is-invalid @enderror" 
                                required 
                                placeholder="09xxxxxxxx (chỉ số, 10-15 chữ số)"
                                value="{{ old('SDT') }}"
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

                        <div class="mb-3">
                            <label class="label">Địa chỉ trụ sở <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="DiaChi" 
                                class="form-control @error('DiaChi') is-invalid @enderror" 
                                required 
                                placeholder="Địa chỉ đầy đủ"
                                value="{{ old('DiaChi') }}"
                                minlength="5"
                                maxlength="255"
                            >
                            @error('DiaChi')
                                <small class="error-message">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="label">Mô tả nhà xe</label>
                            <textarea 
                                name="MoTa" 
                                rows="3" 
                                class="form-control @error('MoTa') is-invalid @enderror" 
                                placeholder="Giới thiệu sơ về nhà xe của bạn"
                                maxlength="255"
                            >{{ old('MoTa') }}</textarea>
                            @error('MoTa')
                                <small class="error-message">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <button class="btn btn-submit w-100 py-2">
                            <i class="fa fa-paper-plane"></i> Gửi yêu cầu hợp tác
                        </button>

                        <p class="text-muted text-center mt-3" style="font-size: 14px">
                            Yêu cầu sẽ được admin kiểm duyệt trong 24–48 giờ.
                        </p>

                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('partnerForm');
                            const emailInput = document.querySelector('input[name="Email"]');
                            
                            // Kiểm tra email đã được xác thực trước khi submit
                            form?.addEventListener('submit', function(e) {
                                if (emailInput && (!emailInput.value || !emailInput.hasAttribute('readonly'))) {
                                    e.preventDefault();
                                    alert('Vui lòng xác thực email trước khi đăng ký hợp tác!');
                                    window.location.href = '{{ route("verification.email", ["type" => "partner"]) }}';
                                    return false;
                                }
                            });

                            // Real-time validation cho số điện thoại
                            const sdtInput = document.querySelector('input[name="SDT"]');
                            sdtInput?.addEventListener('input', function(e) {
                                // Chỉ cho phép nhập số
                                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                            });
                        });
                    </script>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
