@extends('layouts.admin_layout')

@section('title', 'Báo cáo thống kê')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-chart-bar me-2"></i> Báo cáo thống kê</h2>
        <p>Thống kê doanh thu và hiệu suất hệ thống</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
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
        <div class="col-md-6">
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
    </div>

    <!-- Top Routes -->
    @if(isset($topTuyen) && $topTuyen->count() > 0)
        <div class="card-modern">
            <div class="card-body">
                <h5 class="mb-4"><i class="fas fa-route me-2 text-primary"></i>Top 5 tuyến đường phổ biến</h5>
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
@endsection
