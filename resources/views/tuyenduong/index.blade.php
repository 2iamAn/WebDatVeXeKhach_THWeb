@extends('layouts.app')
@section('title','Tuyến đường')
@section('content')

<style>
    .search-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .search-input-group {
        position: relative;
    }
    
    .search-input-group .form-control {
        padding-left: 45px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        height: 50px;
        transition: all 0.3s;
    }
    
    .search-input-group .form-control:focus {
        border-color: #ff6b35;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }
    
    .search-input-group .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color:rgb(16, 160, 110);
        font-size: 18px;
    }
    
    .swap-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color:rgb(16, 160, 110);
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s;
    }
    
    .swap-icon:hover {
        transform: translateY(-50%) rotate(180deg);
    }
    
    .routes-table {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .routes-table table {
        margin: 0;
    }
    
    .routes-table thead {
        background: linear-gradient(135deg, #ff6b35 0%,rgb(18, 187, 150) 100%);
        color: white;
    }
    
    .routes-table thead th {
        border: none;
        padding: 20px 15px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .routes-table tbody tr {
        transition: all 0.3s;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .routes-table tbody tr:hover {
        background-color: #fff8f5;
        transform: scale(1.01);
    }
    
    .routes-table tbody td {
        padding: 20px 15px;
        vertical-align: middle;
    }
    
    .route-name {
        color:rgb(16, 160, 110);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .route-name i {
        color:rgb(16, 160, 110);
        font-size: 18px;
    }
    
    .vehicle-type {
        background: #fff3e0;
        color:rgb(16, 160, 110);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }
    
    .distance-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }
    
    .time-badge {
        background: #f3e5f5;
        color: #7b1fa2;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }
    
    .price-badge {
        color: #2c3e50;
        font-weight: 600;
        font-size: 15px;
    }
    
    .btn-find-route {
        background: linear-gradient(135deg,rgb(18, 202, 165) 0%, #f7931e 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .btn-find-route:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
</style>

<div class="container my-5">
    <!-- Search Section -->
    <div class="search-section">
        <form method="GET" action="{{ route('tuyenduong.index') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-dark mb-2">Điểm đi</label>
                    <div class="search-input-group">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               name="diem_di" 
                               class="form-control" 
                               placeholder="Nhập điểm đi"
                               value="{{ request('diem_di') }}">
                    </div>

                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-dark mb-2">Điểm đến</label>
                    <div class="search-input-group">
                        <i class="fas fa-exchange-alt swap-icon" id="swapBtn" title="Đổi chiều"></i>
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               name="diem_den" 
                               class="form-control" 
                               placeholder="Nhập điểm đến"
                               value="{{ request('diem_den') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-find-route w-100">
                        <i class="fas fa-search me-2"></i>Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Routes Table -->
    <div class="routes-table">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Tuyến xe</th>
                    <th>Loại xe</th>
                    <th>Quãng đường</th>
                    <th>Thời gian hành trình</th>
                    <th>Giá vé</th>
                    <th style="text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tuyensUnique as $tuyen)
                    <tr>
                        <td>
                            <div class="route-name">
                                <span>{{ $tuyen->DiemDi }}</span>
                                <i class="fas fa-exchange-alt"></i>
                                <span>{{ $tuyen->DiemDen }}</span>
                            </div>
                        </td>
                        <td>
                            @if(isset($tuyen->danhSachNhaXe) && $tuyen->danhSachNhaXe->count() > 0)
                                <span class="vehicle-type">
                                    {{ $tuyen->danhSachNhaXe->implode(', ') }}
                                </span>
                            @else
                                <span class="text-muted">---</span>
                            @endif
                        </td>
                        <td>
                            @if($tuyen->KhoangCach)
                                <span class="distance-badge">
                                    {{ number_format($tuyen->KhoangCach, 0, ',', '.') }}km
                                </span>
                            @else
                                <span class="text-muted">---</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($tuyen->thoiGianHanhTrinh) && $tuyen->thoiGianHanhTrinh)
                                <span class="time-badge">
                                    {{ $tuyen->thoiGianHanhTrinh }}
                                </span>
                            @else
                                <span class="text-muted">---</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($tuyen->giaVeThapNhat) && $tuyen->giaVeThapNhat)
                                <span class="price-badge">
                                    Từ {{ number_format($tuyen->giaVeThapNhat, 0, ',', '.') }} ₫
                                </span>
                            @else
                                <span class="text-muted">---</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('chuyenxe.search', [
                                'diem_di' => $tuyen->DiemDi, 
                                'diem_den' => $tuyen->DiemDen,
                                'ngay_khoi_hanh' => date('Y-m-d'),
                                'so_ghe' => 1
                            ]) }}" 
                               class="btn btn-find-route">
                                Tìm xe
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-route"></i>
                                <h5 class="mt-3">Không tìm thấy tuyến đường nào</h5>
                                <p class="text-muted">Vui lòng thử lại với từ khóa khác.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const swapBtn = document.getElementById('swapBtn');
    const diemDiInput = document.querySelector('input[name="diem_di"]');
    const diemDenInput = document.querySelector('input[name="diem_den"]');
    
    if (swapBtn) {
        swapBtn.addEventListener('click', function() {
            const temp = diemDiInput.value;
            diemDiInput.value = diemDenInput.value;
            diemDenInput.value = temp;
        });
    }
});
</script>

@endsection

