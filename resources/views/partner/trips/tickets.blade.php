@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-ticket-alt"></i>
        Vé đã đặt - Chuyến #{{ $chuyen->MaChuyenXe }}
    </h2>
    <p class="text-muted mb-0 mt-2">
        Tuyến: <strong>{{ $chuyen->tuyenDuong->DiemDi }} → {{ $chuyen->tuyenDuong->DiemDen }}</strong> | 
        Ngày: <strong>{{ \Carbon\Carbon::parse($chuyen->GioKhoiHanh)->format('d/m/Y H:i') }}</strong>
    </p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Danh sách vé đã đặt
            </h5>
            <div>
                <span class="badge bg-info me-2" style="font-size: 14px; padding: 8px 15px;">
                    <i class="fas fa-ticket-alt me-1"></i>
                    Tổng: {{ $chuyen->veXe->count() }} vé
                </span>
                <a href="{{ route('partner.trips') }}" class="btn btn-secondary" style="border-radius: 10px;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>
        </div>

        @if($chuyen->veXe->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-hashtag me-2"></i>Mã vé
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-user me-2"></i>Khách hàng
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-phone me-2"></i>Số điện thoại
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-couch me-2"></i>Ghế
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-calendar me-2"></i>Ngày đặt
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-money-bill-wave me-2"></i>Giá vé
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-info-circle me-2"></i>Trạng thái
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chuyen->veXe as $ve)
                        <tr style="transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 15px;">
                                <strong class="text-primary">#{{ $ve->MaVe }}</strong>
                            </td>
                            <td style="padding: 15px;">
                                <i class="fas fa-user-circle text-info me-2"></i>
                                {{ $ve->nguoiDung->HoTen ?? '---' }}
                            </td>
                            <td style="padding: 15px;">
                                <i class="fas fa-phone text-success me-2"></i>
                                {{ $ve->nguoiDung->SDT ?? '---' }}
                            </td>
                            <td style="padding: 15px;">
                                <span class="badge bg-primary" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="fas fa-couch me-1"></i>
                                    {{ $ve->ghe->SoGhe ?? '---' }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <i class="far fa-calendar text-info me-2"></i>
                                {{ \Carbon\Carbon::parse($ve->NgayDat)->format('d/m/Y H:i') }}
                            </td>
                            <td style="padding: 15px;">
                                <span class="badge bg-success" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ number_format($ve->GiaTaiThoiDiemDat) }} ₫
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                @php
                                    $statusClass = 'success';
                                    $statusIcon = 'check-circle';
                                    if ($ve->TrangThai == 'Hủy') {
                                        $statusClass = 'danger';
                                        $statusIcon = 'times-circle';
                                    } elseif ($ve->TrangThai == 'Chờ thanh toán') {
                                        $statusClass = 'warning';
                                        $statusIcon = 'clock';
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}" style="font-size: 13px; padding: 8px 12px;">
                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                    {{ $ve->TrangThai ?? 'Đã đặt' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tổng kết -->
            <div class="mt-4 pt-4 border-top">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Tổng số vé</h6>
                                <h3 class="mb-0">{{ $chuyen->veXe->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Tổng doanh thu</h6>
                                <h3 class="mb-0">{{ number_format($chuyen->veXe->sum('GiaTaiThoiDiemDat')) }} ₫</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Ghế trống</h6>
                                <h3 class="mb-0">{{ max(0, $chuyen->ghe->count() - $chuyen->veXe->count()) }}/{{ $chuyen->ghe->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="text-muted">Chưa có vé nào được đặt</h5>
                <p class="text-muted">Chuyến này chưa có khách hàng nào đặt vé.</p>
            </div>
        @endif
    </div>
</div>
@endsection

