@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-chart-line"></i>
        Báo cáo doanh thu
    </h2>
    <p class="text-muted mb-0 mt-2">Thống kê và phân tích doanh thu của nhà xe</p>
</div>

<div class="row g-4">
    <!-- Doanh thu hôm nay -->
    <div class="col-md-4">
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
    <div class="col-md-4">
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

    <!-- Tổng vé bán -->
    <div class="col-md-4">
        <div class="stat-card card-success">
            <div class="stat-card-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-card-title">Tổng vé bán</div>
            <div class="stat-card-value">{{ number_format($tongVe) }}</div>
            <small style="opacity: 0.8; font-size: 12px;">
                <i class="fas fa-check-circle me-1"></i>
                Tổng số vé đã bán
            </small>
        </div>
    </div>
</div>

<!-- Thống kê chi tiết -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h5 class="mb-4">
                    <i class="fas fa-chart-pie text-primary me-2"></i>
                    Phân tích doanh thu
                </h5>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-4 rounded" style="background: linear-gradient(135deg, rgba(79, 185, 159, 0.1) 0%, rgba(58, 143, 122, 0.1) 100%); border-left: 4px solid #4FB99F;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">
                                    <i class="fas fa-percentage me-2"></i>
                                    Tỷ lệ tăng trưởng
                                </span>
                                <span class="badge bg-success">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    {{ $doanhThuThang > 0 ? number_format(($doanhThuNgay / $doanhThuThang) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <h4 class="mb-0">{{ number_format($doanhThuNgay) }} ₫</h4>
                            <small class="text-muted">Hôm nay</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="p-4 rounded" style="background: linear-gradient(135deg, #43e97b15 0%, #38f9d715 100%); border-left: 4px solid #43e97b;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    Trung bình mỗi vé
                                </span>
                                <span class="badge bg-info">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ $tongVe > 0 ? number_format($doanhThuThang / $tongVe) : 0 }} ₫
                                </span>
                            </div>
                            <h4 class="mb-0">{{ number_format($tongVe) }}</h4>
                            <small class="text-muted">Tổng vé đã bán</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
