@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-edit"></i>
        Sửa tuyến đường
    </h2>
    <p class="text-muted mb-0 mt-2">Cập nhật thông tin tuyến đường</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('partner.routes.update', $route->MaTuyen) }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="DiemDi" class="form-label">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        Điểm đi <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('DiemDi') is-invalid @enderror" 
                           id="DiemDi" 
                           name="DiemDi" 
                           value="{{ old('DiemDi', $route->DiemDi) }}"
                           placeholder="Ví dụ: Sài Gòn, Hà Nội..."
                           required>
                    @error('DiemDi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="DiemDen" class="form-label">
                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                        Điểm đến <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('DiemDen') is-invalid @enderror" 
                           id="DiemDen" 
                           name="DiemDen" 
                           value="{{ old('DiemDen', $route->DiemDen) }}"
                           placeholder="Ví dụ: Gia Lai, Đăk Lăk..."
                           required>
                    @error('DiemDen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="KhoangCach" class="form-label">
                        <i class="fas fa-route me-2"></i>
                        Khoảng cách (km) <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           class="form-control @error('KhoangCach') is-invalid @enderror" 
                           id="KhoangCach" 
                           name="KhoangCach" 
                           value="{{ old('KhoangCach', $route->KhoangCach) }}"
                           placeholder="Ví dụ: 300, 450..."
                           min="1"
                           required>
                    @error('KhoangCach')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="ThoiGianHanhTrinh" class="form-label">
                        <i class="fas fa-clock me-2"></i>
                        Thời gian
                    </label>
                    <input type="text" 
                           class="form-control @error('ThoiGianHanhTrinh') is-invalid @enderror" 
                           id="ThoiGianHanhTrinh" 
                           name="ThoiGianHanhTrinh" 
                           value="{{ old('ThoiGianHanhTrinh', $route->ThoiGianHanhTrinh) }}"
                           placeholder="Ví dụ: 9 giờ, 6 giờ 45 phút...">
                    @error('ThoiGianHanhTrinh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Thời gian di chuyển dự kiến</small>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('partner.routes') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Cập nhật tuyến đường
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

