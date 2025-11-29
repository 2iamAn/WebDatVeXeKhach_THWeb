@extends('layouts.app')

@section('title', 'Liên Hệ')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Cột trái: Thông tin liên hệ -->
        <div class="col-md-5 mb-4">
            <h2 class="mb-4" style="font-weight: 700; color: #2c3e50;">LIÊN HỆ VỚI CHÚNG TÔI</h2>
            
            <div class="mb-3">
                <small style="color: #666;">Bustrip</small>
                <h3 style="color:rgb(29, 211, 144); font-weight: 700; font-size: 20px; margin-top: 5px;">
                    CÔNG TY TNHH THƯƠNG MẠI DỊCH VỤ BUSTRIP
                </h3>
            </div>

            <div class="contact-info" style="color: #2c3e50; line-height: 1.8;">
                <p style="margin-bottom: 15px;">
                    <strong>Địa chỉ:</strong><br>
                    180 Cao Lỗ, Phường 4, Quận 8, Tp.Hồ Chí Minh, Việt Nam.
                </p>
                
                <p style="margin-bottom: 15px;">
                    <strong>Website:</strong><br>
                    <a href="https://nhaxetructuyen.page.gd" target="_blank" style="color: #4FB99F; text-decoration: none;">https://nhaxetructuyen.page.gd</a>
                </p>
                
                <p style="margin-bottom: 15px;">
                    <strong>Điện thoại:</strong><br>
                    0777443085
                </p>
                
                <p style="margin-bottom: 15px;">
                    <strong>Email:</strong><br>
                    <a href="dinhthuphuong1302@gmail.com" style="color: #4FB99F; text-decoration: none;">dinhthuphuong1302@gmail.com</a>
                </p>
               
            </div>
        </div>

        <!-- Cột phải: Form liên hệ -->
        <div class="col-md-7">
            <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: rgb(29, 211, 144); font-weight: 600; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-envelope" style="font-size: 24px;"></i>
                    Gửi thông tin liên hệ đến chúng tôi
                </h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0" style="padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-4" id="contactTab" role="tablist" style="border-bottom: 2px solid #e0e0e0;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="partner-tab" data-bs-toggle="tab" data-bs-target="#partner-form" type="button" role="tab" aria-controls="partner-form" aria-selected="true" style="color: #2c3e50; font-weight: 600; border: none; border-bottom: 3px solid transparent; padding: 12px 20px;">
                            <i class="fas fa-building me-2"></i>Nhà xe đối tác
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer-form" type="button" role="tab" aria-controls="customer-form" aria-selected="false" style="color: #2c3e50; font-weight: 600; border: none; border-bottom: 3px solid transparent; padding: 12px 20px;">
                            <i class="fas fa-user me-2"></i>Khách hàng
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="contactTabContent">
                    <!-- Form Nhà xe đối tác -->
                    <div class="tab-pane fade show active" id="partner-form" role="tabpanel" aria-labelledby="partner-tab">
                        <form method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <input type="hidden" name="loai_lien_he" value="nha_xe">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nha_xe_partner" class="form-label" style="font-weight: 600; color: #2c3e50;">Tên nhà xe <span style="color: red;">*</span></label>
                                    <select class="form-select" id="nha_xe_partner" name="nha_xe" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                        <option value="">Chọn nhà xe</option>
                                        @foreach($nhaxes as $nhaxe)
                                            <option value="{{ $nhaxe->TenNhaXe }}">{{ $nhaxe->TenNhaXe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="ho_ten_partner" class="form-label" style="font-weight: 600; color: #2c3e50;">Họ và tên <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="ho_ten_partner" name="ho_ten" placeholder="Họ và tên" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email_partner" class="form-label" style="font-weight: 600; color: #2c3e50;">Email <span style="color: red;">*</span></label>
                                    <input type="email" class="form-control" id="email_partner" name="email" placeholder="Email" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                </div>
                                <div class="col-md-6">
                                    <label for="dien_thoai_partner" class="form-label" style="font-weight: 600; color: #2c3e50;">Điện thoại <span style="color: red;">*</span></label>
                                    <input type="tel" class="form-control" id="dien_thoai_partner" name="dien_thoai" placeholder="Điện thoại" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tieu_de_partner" class="form-label" style="font-weight: 600; color: #2c3e50;">Tiêu đề <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="tieu_de_partner" name="tieu_de" placeholder="Nhập Tiêu đề" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                            </div>

                            <div class="mb-4">
                                <label for="ghi_chu_partner" class="form-label" style="font-weight: 600; color: #2c3e50;">Ghi chú</label>
                                <textarea class="form-control" id="ghi_chu_partner" name="ghi_chu" rows="5" placeholder="Nhập ghi chú" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;"></textarea>
                            </div>

                            <button type="submit" class="btn w-100" style="background:rgb(29, 211, 144); color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; transition: all 0.3s;">
                                <i class="fas fa-paper-plane me-2"></i>Gửi
                            </button>
                        </form>
                    </div>

                    <!-- Form Khách hàng -->
                    <div class="tab-pane fade" id="customer-form" role="tabpanel" aria-labelledby="customer-tab">
                        <form method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <input type="hidden" name="loai_lien_he" value="khach_hang">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ho_ten_customer" class="form-label" style="font-weight: 600; color: #2c3e50;">Họ và tên <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="ho_ten_customer" name="ho_ten" placeholder="Họ và tên" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                </div>
                                <div class="col-md-6">
                                    <label for="email_customer" class="form-label" style="font-weight: 600; color: #2c3e50;">Email <span style="color: red;">*</span></label>
                                    <input type="email" class="form-control" id="email_customer" name="email" placeholder="Email" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="dien_thoai_customer" class="form-label" style="font-weight: 600; color: #2c3e50;">Điện thoại <span style="color: red;">*</span></label>
                                    <input type="tel" class="form-control" id="dien_thoai_customer" name="dien_thoai" placeholder="Điện thoại" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                </div>
                                <div class="col-md-6">
                                    <label for="tieu_de_customer" class="form-label" style="font-weight: 600; color: #2c3e50;">Tiêu đề <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="tieu_de_customer" name="tieu_de" placeholder="Nhập Tiêu đề" required style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="ghi_chu_customer" class="form-label" style="font-weight: 600; color: #2c3e50;">Ghi chú</label>
                                <textarea class="form-control" id="ghi_chu_customer" name="ghi_chu" rows="5" placeholder="Nhập ghi chú" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;"></textarea>
                            </div>

                            <button type="submit" class="btn w-100" style="background:rgb(29, 211, 144); color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; transition: all 0.3s;">
                                <i class="fas fa-paper-plane me-2"></i>Gửi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn:hover {
        background: rgb(29, 211, 144) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 208, 150, 0.4);
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #4FB99F;
        box-shadow: 0 0 0 0.2rem rgba(79, 185, 159, 0.25);
    }
    
    .nav-tabs .nav-link {
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        color: rgb(29, 211, 144) !important;
        border-bottom-color: rgba(29, 211, 144, 0.3) !important;
    }
    
    .nav-tabs .nav-link.active {
        color: rgb(29, 211, 144) !important;
        border-bottom-color: rgb(29, 211, 144) !important;
        background-color: transparent;
    }
</style>
@endsection
