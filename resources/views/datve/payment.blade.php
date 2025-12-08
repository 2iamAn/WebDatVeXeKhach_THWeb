@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thanh toán vé xe</h4>
                </div>
                <div class="card-body">
                    @if(session('payment_success'))
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="font-size: 1.1rem; padding: 1.5rem;">
                            <i class="fas fa-check-circle me-2" style="font-size: 1.5rem;"></i>
                            <strong>{{ session('payment_success') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($daThanhToan && !session('payment_success'))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Vé này đã được thanh toán thành công!
                        </div>
                    @endif

                    <!-- Thông tin vé -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h5 class="mb-3"><i class="fas fa-ticket-alt me-2"></i>Thông tin vé</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mã vé:</strong> 
                                    <span class="badge bg-primary" style="font-size: 1rem; padding: 6px 12px;">
                                        @if(isset($tatCaVe) && $tatCaVe->count() > 0)
                                            @foreach($tatCaVe as $ve)
                                                {{ $ve->MaVe }}@if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            {{ $veXe->MaVe }}
                                        @endif
                                    </span>
                                </p>
                                <p><strong>Số lượng ghế:</strong> {{ $soGhe ?? 1 }} ghế</p>
                                <p><strong>Nhà xe:</strong> {{ $veXe->chuyenXe->nhaXe->TenNhaXe ?? '---' }}</p>
                                <p><strong>Điểm đi:</strong> {{ $veXe->chuyenXe->tuyenDuong->DiemDi ?? '---' }}</p>
                                <p><strong>Điểm đến:</strong> {{ $veXe->chuyenXe->tuyenDuong->DiemDen ?? '---' }}</p>
                                <p><strong>Số điện thoại:</strong> {{ $veXe->nguoiDung->SDT ?? ($user->SDT ?? '---') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Ghế đã đặt:</strong> 
                                    @if(isset($tatCaVe) && $tatCaVe->count() > 0)
                                        {{ $tatCaVe->map(function($ve) { return $ve->ghe->SoGhe ?? null; })->filter()->sort()->implode(', ') }}
                                    @else
                                        {{ $veXe->ghe->SoGhe ?? '---' }}
                                    @endif
                                </p>
                                <p><strong>Giờ khởi hành:</strong> {{ $veXe->chuyenXe->GioKhoiHanh ? \Carbon\Carbon::parse($veXe->chuyenXe->GioKhoiHanh)->format('d/m/Y H:i') : '---' }}</p>
                                <p><strong>Giờ đến:</strong> {{ $veXe->chuyenXe->GioDen ? \Carbon\Carbon::parse($veXe->chuyenXe->GioDen)->format('d/m/Y H:i') : '---' }}</p>
                                <p><strong>Ngày đặt:</strong> {{ $veXe->NgayDat ? \Carbon\Carbon::parse($veXe->NgayDat)->format('d/m/Y H:i') : '---' }}</p>
                            </div>
                        </div>
                    </div>

                    @if(!$daThanhToan)
                        <!-- Form thanh toán -->
                        <form method="POST" action="{{ route('datve.processPayment', $veXe->MaVe) }}">
                            @csrf
                            
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Thông tin thanh toán</h5>
                                
                                <div class="alert alert-warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><strong>Tổng tiền cần thanh toán:</strong></span>
                                        <span class="fs-4 fw-bold text-danger">{{ number_format($tongTien ?? $veXe->chuyenXe->GiaVe, 0, ',', '.') }} VND</span>
                                    </div>
                                    <small class="text-muted">
                                        {{ $soGhe ?? 1 }} ghế × {{ number_format($veXe->chuyenXe->GiaVe, 0, ',', '.') }} VND
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phương thức thanh toán <span class="text-danger">*</span></label>
                                    <select name="PhuongThuc" class="form-select" required>
                                        <option value="">-- Chọn phương thức thanh toán --</option>
                                        <option value="Chuyển khoản">Chuyển khoản ngân hàng</option>
                                        <option value="Momo">Ví điện tử Momo</option>
                                        <option value="VNPAY">VNPAY</option>
                                        <option value="ZaloPay">ZaloPay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('datve.create', $veXe->MaChuyenXe) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Xác nhận thanh toán
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center">
                            <a href="{{ route('vexe.booking') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-list me-2"></i>Xem danh sách vé
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

