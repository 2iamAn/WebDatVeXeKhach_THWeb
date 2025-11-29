@extends('layouts.admin_layout')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-chart-line me-2"></i> Bảng điều khiển</h2>
        <p>Tổng quan hệ thống và thống kê</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-white-50 mb-2">Khách hàng</h5>
                        <h3 class="text-white mb-0">{{ number_format($tongUser) }}</h3>
                    </div>
                    <i class="fas fa-users card-icon text-white" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #6bc4b0 0%, #4FB99F 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-white-50 mb-2">Nhà xe</h5>
                        <h3 class="text-white mb-0">{{ number_format($tongNhaXe) }}</h3>
                    </div>
                    <i class="fas fa-bus card-icon text-white" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #67e8f9 0%, #4FB99F 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-white-50 mb-2">Chuyến xe</h5>
                        <h3 class="text-white mb-0">{{ number_format($tongChuyen) }}</h3>
                    </div>
                    <i class="fas fa-route card-icon text-white" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #a8e6d4 0%, #6bc4b0 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-white-50 mb-2">Vé đã bán</h5>
                        <h3 class="text-white mb-0">{{ number_format($tongVe) }}</h3>
                    </div>
                    <i class="fas fa-ticket-alt card-icon text-white" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Doanh thu -->
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card-modern">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2 text-success"></i>Tổng doanh thu</h5>
                    <h2 class="text-success mb-0">{{ number_format($tongDoanhThu) }} đ</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3">
        <div class="col-md-4">
            <a href="{{ route('admin.users') }}" class="card-modern text-decoration-none">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5>Quản lý người dùng</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.partners') }}" class="card-modern text-decoration-none position-relative">
                <div class="card-body text-center">
                    <i class="fas fa-handshake fa-3x text-info mb-3"></i>
                    <h5>Duyệt yêu cầu hợp tác</h5>
                    @if($yeuCauChoDuyet > 0)
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 14px; padding: 6px 10px;">
                            {{ $yeuCauChoDuyet }} mới
                        </span>
                    @endif
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.routes.pending') }}" class="card-modern text-decoration-none position-relative">
                <div class="card-body text-center">
                    <i class="fas fa-map-marked-alt fa-3x text-warning mb-3"></i>
                    <h5>Phê duyệt tuyến đường</h5>
                    @if(isset($tuyenChoDuyet) && $tuyenChoDuyet > 0)
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 14px; padding: 6px 10px;">
                            {{ $tuyenChoDuyet }} mới
                        </span>
                    @endif
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.trips.pending') }}" class="card-modern text-decoration-none">
                <div class="card-body text-center">
                    <i class="fas fa-route fa-3x text-primary mb-3"></i>
                    <h5>Quản lý chuyến xe</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.reports') }}" class="card-modern text-decoration-none">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                    <h5>Báo cáo thống kê</h5>
                </div>
            </a>
        </div>
    </div>
@endsection
