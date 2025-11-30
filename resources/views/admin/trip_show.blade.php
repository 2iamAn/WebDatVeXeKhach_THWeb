@extends('layouts.admin_layout')

@section('title', 'Chi tiết chuyến xe')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-route me-2"></i> Chi tiết chuyến xe</h2>
                <p>Thông tin chi tiết và danh sách vé đã đặt</p>
            </div>
            <a href="{{ route('admin.trips.pending') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Thông tin chuyến xe -->
    <div class="card-modern mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Thông tin chuyến xe
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-bus me-2 text-primary"></i>
                            Nhà xe
                        </div>
                        <div class="info-value">{{ optional($chuyen->nhaXe)->TenNhaXe ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-route me-2 text-primary"></i>
                            Tuyến
                        </div>
                        <div class="info-value">
                            <strong>{{ optional($chuyen->tuyenDuong)->DiemDi ?? '--' }}</strong>
                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            <strong>{{ optional($chuyen->tuyenDuong)->DiemDen ?? '--' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            Khởi hành
                        </div>
                        <div class="info-value">
                            {{ optional($chuyen->GioKhoiHanh)->format('d/m/Y H:i') ?? '--' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-check me-2 text-primary"></i>
                            Đến nơi
                        </div>
                        <div class="info-value">
                            {{ optional($chuyen->GioDen)->format('d/m/Y H:i') ?? '--' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-money-bill-wave me-2 text-success"></i>
                            Giá vé
                        </div>
                        <div class="info-value text-success">
                            <strong>{{ number_format($chuyen->GiaVe, 0, ',', '.') }} ₫</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Trạng thái
                        </div>
                        <div class="info-value">
                            @php
                                $statusClass = 'success';
                                $statusIcon = 'check-circle';
                                if ($chuyen->TrangThai == 'Hết chỗ' || $chuyen->TrangThai == 'Tạm dừng') {
                                    $statusClass = 'warning';
                                    $statusIcon = 'exclamation-circle';
                                } elseif ($chuyen->TrangThai == 'TuChoi') {
                                    $statusClass = 'danger';
                                    $statusIcon = 'times-circle';
                                } elseif ($chuyen->TrangThai == 'BiKhoa') {
                                    $statusClass = 'danger';
                                    $statusIcon = 'lock';
                                } elseif ($chuyen->TrangThai == 'ChoDuyet') {
                                    $statusClass = 'warning';
                                    $statusIcon = 'clock';
                                }
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">
                                <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                @if($chuyen->TrangThai == 'TuChoi')
                                    Từ chối
                                @elseif($chuyen->TrangThai == 'DaDuyet')
                                    Đã duyệt
                                @elseif($chuyen->TrangThai == 'BiKhoa')
                                    Bị khóa
                                @elseif($chuyen->TrangThai == 'ChoDuyet')
                                    Chờ duyệt
                                @else
                                    {{ $chuyen->TrangThai }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @if($chuyen->LyDoTuChoi)
                <div class="col-12">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-comment-alt me-2 text-danger"></i>
                            Lý do
                        </div>
                        <div class="info-value text-danger">
                            {{ $chuyen->LyDoTuChoi }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Danh sách vé đã đặt -->
    <div class="card-modern">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-ticket-alt me-2"></i>
                Vé đã đặt ({{ $chuyen->veXe->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($chuyen->veXe->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã vé</th>
                                <th>Khách hàng</th>
                                <th>Số ghế</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chuyen->veXe as $ve)
                                <tr>
                                    <td><strong>{{ $ve->MaVe }}</strong></td>
                                    <td>{{ optional($ve->nguoiDung)->HoTen ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-chair me-1"></i>
                                            {{ optional($ve->ghe)->SoGhe ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $ve->TrangThai }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h5 class="text-muted">Chưa có vé nào được đặt</h5>
                    <p class="text-muted">Chuyến xe này chưa có khách hàng nào đặt vé.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }
        .info-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 16px;
            color: #212529;
            font-weight: 500;
        }
    </style>
@endsection

