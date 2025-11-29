@extends('layouts.admin_layout')

@section('title', 'Trang chủ Admin')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-shield-alt me-2"></i> Chào mừng Admin!</h2>
        <p>Xin chào, <strong>{{ session('user')->HoTen ?? 'Administrator' }}</strong></p>
    </div>

    <div class="card-modern">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <div class="mx-auto" style="width: 100px; height: 100px; background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <h3 class="mb-3">Chào mừng bạn đến với Admin Panel!</h3>
            <p class="text-muted mb-4">Bạn đang đăng nhập với quyền quản trị viên. Vui lòng chọn một tùy chọn bên dưới để tiếp tục.</p>
            
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom btn-action" style="padding: 12px 30px; font-size: 16px;">
                <i class="fas fa-tachometer-alt me-2"></i> Vào Dashboard
            </a>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-3">
            <a href="{{ route('admin.users') }}" class="card-modern text-decoration-none text-dark" style="display: block;">
                <div class="card-body text-center py-4">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h6 class="mb-0">Quản lý người dùng</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.partners') }}" class="card-modern text-decoration-none text-dark" style="display: block;">
                <div class="card-body text-center py-4">
                    <i class="fas fa-bus fa-3x text-primary mb-3"></i>
                    <h6 class="mb-0">Quản lý nhà xe</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.trips.pending') }}" class="card-modern text-decoration-none text-dark" style="display: block;">
                <div class="card-body text-center py-4">
                    <i class="fas fa-route fa-3x text-primary mb-3"></i>
                    <h6 class="mb-0">Duyệt chuyến xe</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.reports') }}" class="card-modern text-decoration-none text-dark" style="display: block;">
                <div class="card-body text-center py-4">
                    <i class="fas fa-chart-bar fa-3x text-primary mb-3"></i>
                    <h6 class="mb-0">Báo cáo thống kê</h6>
                </div>
            </a>
        </div>
    </div>
@endsection
