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
        background: url('https://i.imgur.com/tN5y4Yh.jpeg');
        background-size: cover;
        background-position: center;
        min-height: 100%;
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

                    <form action="{{ route('partner.send') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="label">Tên nhà xe</label>
                            <input type="text" name="TenNhaXe" class="form-control" required placeholder="VD: Nhà xe Phương Trang">
                        </div>

                        <div class="mb-3">
                            <label class="label">Người đại diện</label>
                            <input type="text" name="NguoiDaiDien" class="form-control" required placeholder="Tên đại diện nhà xe">
                        </div>

                        <div class="mb-3">
                            <label class="label">Email liên hệ <span class="text-danger">*</span></label>
                            <input type="email" name="Email" class="form-control" required placeholder="nhaxe@gmail.com">
                            <small class="text-muted">Email này sẽ được dùng để đăng nhập vào hệ thống</small>
                        </div>

                        <div class="mb-3">
                            <label class="label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="MatKhau" class="form-control" required placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" minlength="6">
                            <small class="text-muted">Mật khẩu này sẽ được dùng để đăng nhập sau khi được admin phê duyệt</small>
                        </div>

                        <div class="mb-3">
                            <label class="label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="MatKhau_confirmation" class="form-control" required placeholder="Nhập lại mật khẩu" minlength="6">
                        </div>

                        <div class="mb-3">
                            <label class="label">Số điện thoại</label>
                            <input type="text" name="SDT" class="form-control" required placeholder="09xxxxxxxx">
                        </div>

                        <div class="mb-3">
                            <label class="label">Địa chỉ trụ sở</label>
                            <input type="text" name="DiaChi" class="form-control" required placeholder="Địa chỉ đầy đủ">
                        </div>

                        <div class="mb-3">
                            <label class="label">Mô tả nhà xe</label>
                            <textarea name="MoTa" rows="3" class="form-control" placeholder="Giới thiệu sơ về nhà xe của bạn"></textarea>
                        </div>

                        <button class="btn btn-submit w-100 py-2">
                            <i class="fa fa-paper-plane"></i> Gửi yêu cầu hợp tác
                        </button>

                        <p class="text-muted text-center mt-3" style="font-size: 14px">
                            Yêu cầu sẽ được admin kiểm duyệt trong 24–48 giờ.
                        </p>

                    </form>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
