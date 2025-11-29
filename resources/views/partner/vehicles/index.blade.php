@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-bus"></i>
        Quản lý xe
    </h2>
    <p class="text-muted mb-0 mt-2">Danh sách các xe của nhà xe</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Danh sách xe
            </h5>
            <a href="{{ route('partner.vehicles.create') }}" class="btn btn-primary" style="border-radius: 10px;">
                <i class="fas fa-plus-circle me-2"></i>
                Thêm xe mới
            </a>
        </div>

        @if($vehicles->count() > 0)
            <div class="row g-4">
                @foreach($vehicles as $vehicle)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; transition: all 0.3s;" 
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)'" 
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)'">
                        @if($vehicle->HinhAnh1)
                            <img src="{{ asset($vehicle->HinhAnh1) }}" class="card-img-top" alt="{{ $vehicle->TenXe }}" 
                                 style="height: 200px; object-fit: cover; border-radius: 15px 15px 0 0;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                 style="height: 200px; border-radius: 15px 15px 0 0;">
                                <i class="fas fa-bus fa-4x text-muted" style="opacity: 0.3;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-bus text-primary me-2"></i>
                                {{ $vehicle->TenXe }}
                            </h5>
                            
                            @if($vehicle->LoaiXe)
                                <p class="mb-2">
                                    <i class="fas fa-tag me-2 text-info"></i>
                                    <strong>Loại xe:</strong> {{ $vehicle->LoaiXe }}
                                </p>
                            @endif

                            @if($vehicle->BienSoXe)
                                <p class="mb-2">
                                    <i class="fas fa-car me-2 text-success"></i>
                                    <strong>Biển số:</strong> {{ $vehicle->BienSoXe }}
                                </p>
                            @endif

                            <div class="row mb-2">
                                @if($vehicle->SoGhe)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-couch me-1"></i>
                                            <strong>{{ $vehicle->SoGhe }}</strong> ghế
                                        </small>
                                    </div>
                                @endif
                            </div>

                            @if($vehicle->TienNghi)
                                <p class="mb-2">
                                    <i class="fas fa-star me-2 text-warning"></i>
                                    <strong>Tiện nghi:</strong>
                                </p>
                                <p class="text-muted small mb-3">{{ $vehicle->TienNghi }}</p>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('partner.vehicles.edit', $vehicle->MaXe) }}" 
                                   class="btn btn-sm btn-primary flex-fill">
                                    <i class="fas fa-edit me-1"></i>
                                    Sửa
                                </a>
                                <a href="{{ route('partner.vehicles.delete', $vehicle->MaXe) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa xe này?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bus fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="text-muted">Chưa có xe nào</h5>
                <p class="text-muted">Hãy thêm xe đầu tiên của bạn!</p>
                <a href="{{ route('partner.vehicles.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle me-2"></i>
                    Thêm xe mới
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

