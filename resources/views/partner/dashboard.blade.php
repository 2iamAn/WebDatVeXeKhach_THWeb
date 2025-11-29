@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-chart-line"></i>
        Bảng điều khiển Nhà Xe
    </h2>
    <p class="text-muted mb-0 mt-2">Tổng quan hoạt động và thống kê của nhà xe</p>
</div>

<div class="row g-4">
    <!-- Tổng chuyến đi -->
    <div class="col-md-3">
        <div class="stat-card card-primary">
            <div class="stat-card-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-route"></i>
            </div>
            <div class="stat-card-title">Tổng chuyến đi</div>
            <div class="stat-card-value">{{ number_format($tongChuyen) }}</div>
            <small style="opacity: 0.8; font-size: 12px;">
                <i class="fas fa-info-circle me-1"></i>
                Tất cả chuyến xe của bạn
            </small>
        </div>
    </div>

    <!-- Vé đã bán -->
    <div class="col-md-3">
        <div class="stat-card card-success">
            <div class="stat-card-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-card-title">Vé đã bán</div>
            <div class="stat-card-value">{{ number_format($veDaBan) }}</div>
            <small style="opacity: 0.8; font-size: 12px;">
                <i class="fas fa-check-circle me-1"></i>
                Tổng số vé đã bán
            </small>
        </div>
    </div>

    <!-- Doanh thu hôm nay -->
    <div class="col-md-3">
        <div class="stat-card card-info">
            <div class="stat-card-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-card-title">Doanh thu hôm nay</div>
            <div class="stat-card-value">{{ number_format($doanhThuNgay) }} ₫</div>
            <small style="opacity: 0.8; font-size: 12px;">
                <i class="fas fa-calendar-day me-1"></i>
                {{ date('d/m/Y') }}
            </small>
        </div>
    </div>

    <!-- Doanh thu tháng -->
    <div class="col-md-3">
        <div class="stat-card card-warning">
            <div class="stat-card-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-card-title">Doanh thu tháng</div>
            <div class="stat-card-value">{{ number_format($doanhThuThang) }} ₫</div>
            <small style="opacity: 0.8; font-size: 12px;">
                <i class="fas fa-calendar-alt me-1"></i>
                Tháng {{ date('m/Y') }}
            </small>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h5 class="mb-4">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    Thao tác nhanh
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('partner.trips') }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)'">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-route fa-3x text-primary"></i>
                                    </div>
                                    <h6 class="mb-0">Quản lý chuyến</h6>
                                    <small class="text-muted">Thêm/sửa chuyến xe</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('partner.seats') }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)'">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-couch fa-3x text-success"></i>
                                    </div>
                                    <h6 class="mb-0">Sơ đồ ghế</h6>
                                    <small class="text-muted">Quản lý ghế ngồi</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('partner.tickets') }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)'">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-ticket-alt fa-3x text-info"></i>
                                    </div>
                                    <h6 class="mb-0">Tình trạng vé</h6>
                                    <small class="text-muted">Xem vé đã bán</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('partner.revenue') }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)'">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-chart-line fa-3x text-warning"></i>
                                    </div>
                                    <h6 class="mb-0">Báo cáo</h6>
                                    <small class="text-muted">Xem doanh thu</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
