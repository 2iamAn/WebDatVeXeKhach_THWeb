@extends('layouts.app')

@section('title', 'Giới thiệu về Bustrip')

@section('content')
<div class="about-page">
    <div class="container-fluid px-0">
        <div class="row g-0 justify-content-center">
            <!-- Nội dung chính -->
            <div class="col-12 col-md-10 col-lg-8 about-content">
                <div class="content-wrapper">
                    <!-- Tiêu đề -->
                    <h1 class="page-title">GIỚI THIỆU VỀ BUSTRIP</h1>

                    <!-- Banner -->
                    <div class="about-banner">
                        <img src="{{ asset('image/about-banner.jpg') }}" alt="Bustrip" class="banner-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="banner-placeholder">
                            <div style="text-align: center;">
                                <div style="font-size: 48px; margin-bottom: 10px;">
                                Nhiều nhà xe - Một điểm đặt</div>
                                <div style="font-size: 18px; font-weight: 400;">Tìm kiếm, so sánh giá và đặt vé chỉ trong vài bước</div>
                            </div>
                        </div>
                    </div>

                    <!-- Nội dung giới thiệu -->
                    <div class="about-text">
                        
                        <p class="intro-paragraph">
                            <strong>Bustrip đối với hành khách:</strong><br>
                           <strong> Bustrip</strong> là website trung gian hỗ trợ đặt vé xe khách, kết nối hành khách với các nhà xe uy tín trên các tuyến đường liên tỉnh. Thay vì phải gọi điện hoặc đến trực tiếp bến xe, bạn có thể truy cập Bustrip để tìm kiếm các chuyến xe phù hợp dựa trên điểm đi, điểm đến, ngày khởi hành, giờ chạy, loại xe và giá vé. Sau đó, bạn có thể tiến hành đặt vé và quản lý vé hoàn toàn trực tuyến.
                        </p>

                        <p class="intro-paragraph">
                            <strong>Bustrip đối với các nhà xe đối tác:</strong><br>
                            <strong>Bustrip</strong> là kênh bán hàng và hệ thống quản lý đặt chỗ hiệu quả cho các nhà xe đối tác. Hệ thống cung cấp các chức năng quản lý chuyến xe, quản lý ghế, theo dõi số lượng vé đã bán và cập nhật tình trạng ghế theo thời gian thực. Những tính năng này giúp các nhà xe tối ưu hóa tỷ lệ lấp đầy ghế và tránh tình trạng đặt chỗ trùng lặp. Với giao diện thân thiện và quy trình đặt chỗ rõ ràng, minh bạch, Bustrip hướng tới trở thành nền tảng đặt vé xe khách tiện lợi, an toàn và đáng tin cậy cho cả hành khách và các nhà xe.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.about-page {
    min-height: calc(100vh - 200px);
    background: #fff;
}

.about-sidebar {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding: 0;
    border-right: 1px solid #e0e0e0;
}

.sidebar-content {
    padding: 30px 20px;
    position: sticky;
    top: 100px;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-item {
    margin-bottom: 5px;
}

.menu-item a {
    display: block;
    padding: 12px 15px;
    color: #2c3e50;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.menu-item a:hover {
    background: #e9ecef;
    color: #4FB99F;
}

.menu-item.active a {
    background: #4FB99F;
    color: #fff;
    font-weight: 600;
}

.about-content {
    padding: 40px 50px;
    background: #fff;
}

.content-wrapper {
    max-width: 1000px;
    margin: 0 auto;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e0e0e0;
}

.about-banner {
    margin-bottom: 40px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.banner-image {
    width: 100%;
    height: auto;
    display: block;
}

.banner-placeholder {
    background: linear-gradient(135deg, #4FB99F 0%, #6bc4b0 50%, #a8e6d4 100%);
    height: 300px;
    display: none;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 700;
}

.about-text {
    line-height: 1.8;
    color: #2c3e50;
    max-width: 900px;
    margin: 0 auto;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #4FB99F;
    margin-bottom: 20px;
    margin-top: 30px;
    text-align: center;
}

.intro-paragraph {
    font-size: 15px;
    line-height: 1.8;
    margin-bottom: 30px;
    text-align: justify;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.founders-title {
    font-size: 20px;
    font-weight: 600;
    color: #2c3e50;
    margin-top: 30px;
    margin-bottom: 15px;
}

.founders-list {
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 15px;
    line-height: 2;
}

.founders-list li {
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .about-sidebar {
        min-height: auto;
        border-right: none;
        border-bottom: 1px solid #e0e0e0;
    }

    .sidebar-content {
        position: relative;
        top: 0;
        padding: 20px;
    }

    .sidebar-menu {
        display: flex;
        overflow-x: auto;
        flex-wrap: nowrap;
    }

    .menu-item {
        margin-right: 10px;
        margin-bottom: 0;
        white-space: nowrap;
    }

    .about-content {
        padding: 30px 20px;
    }

    .page-title {
        font-size: 24px;
    }
}
</style>
@endsection
