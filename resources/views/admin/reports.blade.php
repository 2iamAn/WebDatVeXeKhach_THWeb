@extends('layouts.admin_layout')

@section('title', 'Báo cáo thống kê')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-chart-bar me-2"></i> Báo cáo thống kê</h2>
        <p>Thống kê doanh thu và hiệu suất hệ thống</p>
    </div>

    <!-- Filter Section -->
    <div class="card-modern mb-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="fas fa-filter me-2 text-primary"></i>Bộ lọc</h5>
            <form method="GET" action="{{ route('admin.reports') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Loại thời gian</label>
                        <select name="loai_thoi_gian" id="loai_thoi_gian" class="form-select">
                            <option value="ngay" {{ $loaiThoiGian == 'ngay' ? 'selected' : '' }}>Hôm nay</option>
                            <option value="thang" {{ $loaiThoiGian == 'thang' ? 'selected' : '' }}>Tháng này</option>
                            <option value="tuy_chon" {{ $loaiThoiGian == 'tuy_chon' ? 'selected' : '' }}>Tùy chọn</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="tu_ngay_group" style="display: {{ $loaiThoiGian == 'tuy_chon' ? 'block' : 'none' }};">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" value="{{ $tuNgay }}">
                    </div>
                    <div class="col-md-3" id="den_ngay_group" style="display: {{ $loaiThoiGian == 'tuy_chon' ? 'block' : 'none' }};">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" value="{{ $denNgay }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nhà xe</label>
                        <select name="ma_nha_xe" class="form-select">
                            <option value="">Tất cả nhà xe</option>
                            @foreach($nhaxes as $nhaxe)
                                <option value="{{ $nhaxe->MaNhaXe }}" {{ $maNhaXe == $nhaxe->MaNhaXe ? 'selected' : '' }}>
                                    {{ $nhaxe->TenNhaXe }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Áp dụng bộ lọc
                        </button>
                        <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards - Hôm nay / Tháng này (Cố định) - Chỉ hiển thị khi không filter nhà xe -->
    @if(!$maNhaXe || $maNhaXe == '')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5>Doanh thu hôm nay</h5>
                        <h3 class="text-success">{{ number_format($doanhThuNgay ?? 0) }} đ</h3>
                    </div>
                    <i class="fas fa-money-bill-wave card-icon text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5>Doanh thu tháng này</h5>
                        <h3 class="text-primary">{{ number_format($doanhThuThang ?? 0) }} đ</h3>
                    </div>
                    <i class="fas fa-calendar-check card-icon text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5>Tổng số vé hôm nay</h5>
                        <h3 class="text-info">{{ number_format($tongSoVeNgay ?? 0) }} vé</h3>
                    </div>
                    <i class="fas fa-ticket-alt card-icon text-info"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5>Tổng số vé tháng này</h5>
                        <h3 class="text-warning">{{ number_format($tongSoVeThang ?? 0) }} vé</h3>
                    </div>
                    <i class="fas fa-tickets card-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5>Số chuyến chạy hôm nay</h5>
                        <h3 class="text-danger">{{ number_format($soChuyenChayNgay ?? 0) }} chuyến</h3>
                    </div>
                    <i class="fas fa-bus card-icon text-danger"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards - Theo bộ lọc -->
    @if($maNhaXe)
    <!-- Khi có filter nhà xe, chỉ hiển thị thống kê của nhà xe đó -->
    <div class="card-modern mb-4">
        <div class="card-body">
            <h5 class="mb-3">
                <i class="fas fa-building me-2 text-primary"></i>
                Thống kê nhà xe: <span class="badge bg-primary">{{ $nhaxes->where('MaNhaXe', $maNhaXe)->first()->TenNhaXe ?? '' }}</span>
            </h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="stat-card-small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Doanh thu</h6>
                                <h4 class="text-success mb-0">{{ number_format($doanhThu ?? 0) }} đ</h4>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Tổng số vé</h6>
                                <h4 class="text-info mb-0">{{ number_format($tongSoVe ?? 0) }} vé</h4>
                            </div>
                            <i class="fas fa-ticket-alt fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Số chuyến chạy</h6>
                                <h4 class="text-danger mb-0">{{ number_format($soChuyenChay ?? 0) }} chuyến</h4>
                            </div>
                            <i class="fas fa-bus fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($loaiThoiGian != 'ngay')
    <!-- Khi không có filter nhà xe nhưng có filter thời gian khác "hôm nay" -->
    <div class="card-modern mb-4">
        <div class="card-body">
            <h5 class="mb-3">
                <i class="fas fa-chart-line me-2 text-primary"></i>
                Thống kê theo bộ lọc thời gian
            </h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="stat-card-small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Doanh thu</h6>
                                <h4 class="text-success mb-0">{{ number_format($doanhThu ?? 0) }} đ</h4>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Tổng số vé</h6>
                                <h4 class="text-info mb-0">{{ number_format($tongSoVe ?? 0) }} vé</h4>
                            </div>
                            <i class="fas fa-ticket-alt fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Số chuyến chạy</h6>
                                <h4 class="text-danger mb-0">{{ number_format($soChuyenChay ?? 0) }} chuyến</h4>
                            </div>
                            <i class="fas fa-bus fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Top Routes -->
    @if(isset($topTuyen) && $topTuyen->count() > 0)
        <div class="card-modern">
            <div class="card-body">
                <h5 class="mb-4">
                    <i class="fas fa-route me-2 text-primary"></i>
                    Top 5 tuyến đường phổ biến
                    @if($maNhaXe)
                        <span class="badge bg-primary ms-2">{{ $nhaxes->where('MaNhaXe', $maNhaXe)->first()->TenNhaXe ?? '' }}</span>
                    @endif
                </h5>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tuyến đường</th>
                                <th>Số lượng vé</th>
                                <th>Tỷ lệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topTuyen as $index => $tuyen)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary" style="font-size: 14px; padding: 8px 12px;">
                                            #{{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $tuyen->TuyenDuong }}</strong></td>
                                    <td><span class="text-primary fw-bold">{{ $tuyen->SoLuong }} vé</span></td>
                                    <td>
                                        @php
                                            $total = $topTuyen->sum('SoLuong');
                                            $percentage = $total > 0 ? round(($tuyen->SoLuong / $total) * 100, 1) : 0;
                                        @endphp
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card-modern">
            <div class="empty-state">
                <i class="fas fa-route"></i>
                <h5 class="mt-3">Chưa có dữ liệu tuyến đường</h5>
                <p class="text-muted">Hiện tại chưa có dữ liệu thống kê về tuyến đường.</p>
            </div>
        </div>
    @endif

    <style>
        .stat-card-small {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .stat-card-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        document.getElementById('loai_thoi_gian').addEventListener('change', function() {
            const value = this.value;
            const tuNgayGroup = document.getElementById('tu_ngay_group');
            const denNgayGroup = document.getElementById('den_ngay_group');
            
            if (value === 'tuy_chon') {
                tuNgayGroup.style.display = 'block';
                denNgayGroup.style.display = 'block';
            } else {
                tuNgayGroup.style.display = 'none';
                denNgayGroup.style.display = 'none';
            }
        });
    </script>
@endsection
