@extends('layouts.app')
@section('title','Tra cứu vé')
@section('content')

<style>
    body {
        background: #f5f7fa;
    }
    
    .lookup-section {
        max-width: 600px;
        margin: 50px auto;
        background: white;
        padding: 50px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .lookup-title {
        color: #4FB99F;
        font-weight: 700;
        font-size: 28px;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .lookup-form-group {
        margin-bottom: 25px;
    }
    
    .lookup-form-group label {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
    }
    
    .lookup-form-group .form-control {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s;
    }
    
    .lookup-form-group .form-control:focus {
        border-color: #4FB99F;
        box-shadow: 0 0 0 0.2rem rgba(79, 185, 159, 0.25);
        outline: none;
    }
    
    .btn-lookup {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg,rgb(26, 188, 139) );
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
    }
    
    .btn-lookup:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
    }
    
    .ticket-result {
        margin-top: 30px;
        padding: 25px;
        background: #f8f9fa;
        border-radius: 15px;
        border-left: 5px solid #4FB99F;
    }
    
    .ticket-info-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .ticket-info-item:last-child {
        border-bottom: none;
    }
    
    .ticket-info-label {
        color: #6c757d;
        font-weight: 500;
    }
    
    .ticket-info-value {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .status-success {
        background: #d4edda;
        color: #155724;
    }
    
    .status-warning {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-danger {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<div class="lookup-section">
    <h1 class="lookup-title">TRA CỨU THÔNG TIN ĐẶT VÉ</h1>
    
    <form method="GET" action="{{ route('vexe.index') }}">
        <div class="lookup-form-group">
            <label for="sdt">Số điện thoại</label>
            <input type="text" 
                   id="sdt" 
                   name="sdt" 
                   class="form-control" 
                   placeholder="Vui lòng nhập số điện thoại"
                   value="{{ request('sdt') }}"
                   required>
        </div>
        
        <div class="lookup-form-group">
            <label for="ma_ve">Mã vé</label>
            <input type="text" 
                   id="ma_ve" 
                   name="ma_ve" 
                   class="form-control" 
                   placeholder="Vui lòng nhập mã vé"
                   value="{{ request('ma_ve') }}"
                   required>
        </div>
        
        <button type="submit" class="btn-lookup">
            Tra cứu
        </button>
    </form>
    
    @if(request()->has('sdt') && request()->has('ma_ve'))
        @php
            $ve = \App\Models\VeXe::with(['chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'nguoiDung', 'ghe', 'thanhToan'])
                ->whereHas('nguoiDung', function($q) {
                    $q->where('SDT', request('sdt'));
                })
                ->where('MaVe', request('ma_ve'))
                ->first();
        @endphp
        
        @if($ve)
            <div class="ticket-result">
                <h5 class="mb-4" style="color: #4FB99F;">
                    <i class="fas fa-ticket-alt me-2"></i>
                    Thông tin vé
                </h5>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Mã vé:</span>
                    <span class="ticket-info-value">#{{ $ve->MaVe }}</span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Khách hàng:</span>
                    <span class="ticket-info-value">{{ $ve->nguoiDung->HoTen ?? '---' }}</span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Số điện thoại:</span>
                    <span class="ticket-info-value">{{ $ve->nguoiDung->SDT ?? '---' }}</span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Tuyến:</span>
                    <span class="ticket-info-value">
                        {{ $ve->chuyenXe->tuyenDuong->DiemDi }} → {{ $ve->chuyenXe->tuyenDuong->DiemDen }}
                    </span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Nhà xe:</span>
                    <span class="ticket-info-value">{{ $ve->chuyenXe->nhaXe->TenNhaXe ?? '---' }}</span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Ghế:</span>
                    <span class="ticket-info-value">{{ $ve->ghe->SoGhe ?? '---' }}</span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Giờ khởi hành:</span>
                    <span class="ticket-info-value">
                        {{ \Carbon\Carbon::parse($ve->chuyenXe->GioKhoiHanh)->format('d/m/Y H:i') }}
                    </span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Ngày đặt:</span>
                    <span class="ticket-info-value">
                        {{ \Carbon\Carbon::parse($ve->NgayDat)->format('d/m/Y H:i') }}
                    </span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Giá vé:</span>
                    <span class="ticket-info-value">{{ number_format($ve->GiaTaiThoiDiemDat ?: $ve->chuyenXe->GiaVe) }} ₫</span>
                </div>
                
                <div class="ticket-info-item">
                    <span class="ticket-info-label">Trạng thái:</span>
                    <span>
                        @php
                            $statusClass = 'warning';
                            if ($ve->TrangThai == 'Đã thanh toán') {
                                $statusClass = 'success';
                            } elseif (strpos($ve->TrangThai, 'Hủy') !== false) {
                                $statusClass = 'danger';
                            }
                        @endphp
                        <span class="status-badge status-{{ $statusClass }}">
                            {{ $ve->TrangThai }}
                        </span>
                    </span>
                </div>
                
                @if($ve->thanhToan)
                    <div class="ticket-info-item">
                        <span class="ticket-info-label">Phương thức thanh toán:</span>
                        <span class="ticket-info-value">{{ $ve->thanhToan->PhuongThuc }}</span>
                    </div>
                    
                    <div class="ticket-info-item">
                        <span class="ticket-info-label">Ngày thanh toán:</span>
                        <span class="ticket-info-value">
                            {{ \Carbon\Carbon::parse($ve->thanhToan->NgayThanhToan)->format('d/m/Y H:i') }}
                        </span>
                    </div>
                @endif
            </div>
        @else
            <div class="alert alert-warning mt-4" style="border-radius: 10px;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Không tìm thấy vé với thông tin đã nhập. Vui lòng kiểm tra lại số điện thoại và mã vé.
            </div>
        @endif
    @endif
</div>
@endsection

