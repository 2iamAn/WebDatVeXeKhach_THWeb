@extends('layouts.app')
@section('title', $nhaxe->TenNhaXe)

@push('styles')
<style>
    .nhaxe-header {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .nhaxe-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .nhaxe-phone {
        color: #666;
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .nhaxe-images {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 10px 0;
    }
    
    .nhaxe-image-main {
        width: 100%;
        max-width: 500px;
        height: auto;
        border-radius: 10px;
        object-fit: cover;
    }
    
    .nhaxe-image-thumb {
        width: 120px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .nhaxe-image-thumb:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .rating-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .rating-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .rating-score {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .rating-number {
        font-size: 36px;
        font-weight: 700;
        color: #f39c12;
    }
    
    .rating-star {
        color: #f39c12;
        font-size: 20px;
    }
    
    .rating-count {
        color: #666;
        font-size: 14px;
    }
    
    .review-item {
        border-bottom: 1px solid #eee;
        padding: 20px 0;
    }
    
    .review-item:last-child {
        border-bottom: none;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #3498db;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .reviewer-name {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .reviewer-date {
        color: #999;
        font-size: 13px;
    }
    
    .review-rating {
        color: #f39c12;
        margin-bottom: 5px;
    }
    
    .review-text {
        color: #555;
        line-height: 1.6;
    }
    
    .review-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #e8f5e9;
        color: #27ae60;
        border-radius: 4px;
        font-size: 12px;
        margin-left: 10px;
    }
    
    .routes-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .routes-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
    }
    
    .route-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
        transition: all 0.3s;
    }
    
    .route-item:hover {
        background: #f8f9fa;
    }
    
    .route-item:last-child {
        border-bottom: none;
    }
    
    .route-icon {
        color: #3498db;
        font-size: 18px;
        margin-right: 15px;
    }
    
    .route-text {
        color: #3498db;
        font-weight: 500;
        text-decoration: none;
    }
    
    .route-text:hover {
        text-decoration: underline;
    }
    
    /* Rating input stars */
    .rating-input {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        gap: 5px;
        margin: 15px 0;
    }
    
    .rating-input input[type="radio"] {
        display: none;
    }
    
    .rating-input label {
        cursor: pointer;
        font-size: 36px;
        color: #ddd !important;
        transition: all 0.2s ease;
        line-height: 1;
        display: inline-block;
    }
    
    .rating-input label:hover {
        color: #f39c12 !important;
        transform: scale(1.15);
    }
    
    /* Khi chọn sao */
    .rating-input input[type="radio"]:checked + label {
        color: #f39c12 !important;
    }
    
    .rating-input label.active {
        color: #f39c12 !important;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <!-- Header: Tên nhà xe và số điện thoại -->
    <div class="nhaxe-header">
        <h1 class="nhaxe-title">{{ $nhaxe->TenNhaXe }}</h1>
        <p class="nhaxe-phone">
            <i class="fas fa-phone-alt me-2"></i>
            Số điện thoại: {{ optional($nhaxe->nguoiDung)->SDT ?? '1900252547' }}
        </p>
        
        <!-- Hình ảnh nhà xe -->
        <div class="row">
            <div class="col-md-8">
                @php
                    // Chuẩn hóa tên nhà xe để matching
                    $tenNhaXe = trim($nhaxe->TenNhaXe);
                    
                    // Xác định hình ảnh dựa trên tên nhà xe
                    if (stripos($tenNhaXe, 'Phương Trang') !== false || stripos($tenNhaXe, 'Phuong Trang') !== false) {
                        $images = [
                            'main' => 'image/phuongtrang.jpg',
                            'thumbs' => ['image/phuongtrang2.jpg', 'image/phuongtrang3.jpg', 'image/phuongtrang4.jpg', 'image/phuongtrang5.jpg']
                        ];
                    } elseif (stripos($tenNhaXe, 'Phương Hồng Linh') !== false || stripos($tenNhaXe, 'Phuong Hong Linh') !== false) {
                        $images = [
                            'main' => 'image/phuonghonglinh.jpg',
                            'thumbs' => ['image/phuonghonglinh1.jpg', 'image/phuonghonglinh2.jpg']
                        ];
                    } elseif (stripos($tenNhaXe, 'Tiến Oanh') !== false || stripos($tenNhaXe, 'Tien Oanh') !== false) {
                        $images = [
                            'main' => 'image/xe-tien-oanh-374838.jpg',
                            'thumbs' => ['image/tienoanh34.jpg', 'image/xe-tien-oanh-374839.jpg',]
                        ];
                    } else {
                        // Mặc định là Việt Tân Phát
                        $images = [
                            'main' => 'image/xe-viet-tan-phat.jpg',
                            'thumbs' => ['image/nha-xe-viet-tan-phat-2.jpg', 'image/nha-xe-viet-tan-phat-3.jpg',]
                        ];
                    }
                @endphp
                
                <h5 class="mb-3">Hình ảnh nhà xe</h5>
                <img src="{{ asset($images['main']) }}" alt="{{ $nhaxe->TenNhaXe }}" class="nhaxe-image-main mb-3">
                
                <div class="nhaxe-images">
                    @foreach($images['thumbs'] as $thumb)
                        <img src="{{ asset($thumb) }}" alt="Xe {{ $nhaxe->TenNhaXe }}" class="nhaxe-image-thumb">
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <!-- Đánh giá -->
            <div class="rating-section">
                <h2 class="rating-title">Dánh giá về nhà xe</h2>
                
                <div class="rating-score">
                    <span class="rating-number">{{ number_format($rating, 1) }}</span>
                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($rating))
                                <i class="fas fa-star rating-star"></i>
                            @elseif($i - 0.5 <= $rating)
                                <i class="fas fa-star-half-alt rating-star"></i>
                            @else
                                <i class="far fa-star rating-star"></i>
                            @endif
                        @endfor
                        <div class="rating-count">/ 5 • {{ $totalReviews }} đánh giá</div>
                    </div>
                </div>
                
                <!-- Form đánh giá -->
                @if(session()->has('user') && session('user'))
                    @if($daDanhGia)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Bạn đã đánh giá nhà xe này rồi!
                        </div>
                    @elseif(!$duocPhepDanhGia)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Bạn chỉ có thể đánh giá sau khi đã hoàn thành chuyến xe của nhà xe này!
                        </div>
                    @else
                        <div class="mb-4 p-3 border rounded" style="background: #f8f9fa;">
                            <h5 class="mb-3">Viết đánh giá của bạn</h5>
                            <form action="{{ route('danhgia.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="MaNhaXe" value="{{ $nhaxe->MaNhaXe }}">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Đánh giá của bạn <span class="text-danger">*</span></label>
                                    <select name="SoSao" class="form-select" required>
                                        <option value="">-- Chọn đánh giá --</option>
                                        <option value="5">5/5 - Rất hài lòng</option>
                                        <option value="4">4/5 - Hài lòng</option>
                                        <option value="3">3/5 - Trung bình</option>
                                        <option value="2">2/5 - Chưa hài lòng</option>
                                        <option value="1">1/5 - Không hài lòng</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nội dung đánh giá</label>
                                    <textarea name="NoiDung" class="form-control" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về nhà xe này..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Vui lòng <a href="{{ route('login.form') }}">đăng nhập</a> để đánh giá nhà xe này!
                    </div>
                @endif
                
                <hr class="my-4">
                
                <!-- Danh sách đánh giá từ database -->
                @if($danhGias->count() > 0)
                    @foreach($danhGias as $dg)
                        <div class="review-item">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar" style="background: {{ ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6'][rand(0,4)] }};">
                                    {{ strtoupper(substr($dg->nguoiDung->HoTen ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="reviewer-name">
                                        {{ $dg->nguoiDung->HoTen ?? 'Người dùng' }}
                                        @if($dg->DaMuaQua)
                                            <span class="review-badge">
                                                <i class="fas fa-check me-1"></i>Đã mua vé qua hệ thống
                                            </span>
                                        @endif
                                    </div>
                                    <div class="reviewer-date">{{ $dg->NgayDanhGia->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                <span>{{ $dg->SoSao }}/5</span> - 
                                @if($dg->SoSao >= 4.5)
                                    Rất hài lòng
                                @elseif($dg->SoSao >= 3.5)
                                    Hài lòng
                                @elseif($dg->SoSao >= 2.5)
                                    Trung bình
                                @elseif($dg->SoSao >= 1.5)
                                    Chưa hài lòng
                                @else
                                    Không hài lòng
                                @endif
                                
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $dg->SoSao)
                                        <i class="fas fa-star" style="color: #f39c12; font-size: 14px;"></i>
                                    @else
                                        <i class="far fa-star" style="color: #ccc; font-size: 14px;"></i>
                                    @endif
                                @endfor
                            </div>
                            @if($dg->NoiDung)
                                <p class="review-text">{{ $dg->NoiDung }}</p>
                            @endif
                            
                            @if(session('role') === 'admin')
                                <div class="mt-2">
                                    <a href="{{ route('danhgia.toggle', $dg->MaDanhGia) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-eye{{ $dg->HienThi ? '-slash' : '' }} me-1"></i>
                                        {{ $dg->HienThi ? 'Ẩn' : 'Hiện' }}
                                    </a>
                                    <a href="{{ route('danhgia.destroy', $dg->MaDanhGia) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa đánh giá này?')">
                                        <i class="fas fa-trash me-1"></i>Xóa
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá nhà xe này!
                    </div>
                @endif
                
                <!-- Nút quay lại trang chủ -->
                <div class="mt-4 text-center">
                    <a href="{{ route('welcome') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); border: none; padding: 10px 10px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(79, 185, 159, 0.3);">
                        <i class="fas fa-home me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Các tuyến đường -->
            <div class="routes-section">
                <h3 class="routes-title">Các tuyến đường mà nhà xe {{ $nhaxe->TenNhaXe }} đang hoạt động</h3>
                
                @if($tuyens->count() > 0)
                    @foreach($tuyens as $tuyen)
                        <div class="route-item">
                            <i class="fas fa-bus route-icon"></i>
                            <a href="{{ route('chuyenxe.search', [
                                'diem_di' => $tuyen->DiemDi,
                                'diem_den' => $tuyen->DiemDen,
                                'ngay_khoi_hanh' => date('Y-m-d'),
                                'so_ghe' => 1
                            ]) }}" class="route-text">
                                {{ $tuyen->DiemDi }} đi {{ $tuyen->DiemDen }}
                            </a>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Chưa có tuyến đường nào.</p>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection
