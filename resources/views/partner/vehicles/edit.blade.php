@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-edit"></i>
        Sửa thông tin xe
    </h2>
    <p class="text-muted mb-0 mt-2">Cập nhật thông tin xe</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('partner.vehicles.update', $vehicle->MaXe) }}" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="TenXe" class="form-label">
                        <i class="fas fa-bus me-2"></i>
                        Tên xe hiển thị <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('TenXe') is-invalid @enderror" 
                           id="TenXe" 
                           name="TenXe" 
                           value="{{ old('TenXe', $vehicle->TenXe) }}"
                           placeholder="Ví dụ: Xe Limousine VIP, Xe giường nằm 40 chỗ..."
                           required>
                    @error('TenXe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="LoaiXe" class="form-label">
                        <i class="fas fa-tag me-2"></i>
                        Loại xe
                    </label>
                    <input type="text" 
                           class="form-control @error('LoaiXe') is-invalid @enderror" 
                           id="LoaiXe" 
                           name="LoaiXe" 
                           value="{{ old('LoaiXe', $vehicle->LoaiXe) }}"
                           placeholder="Ví dụ: Limousine 22 phòng, 34 giường...">
                    @error('LoaiXe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="BienSoXe" class="form-label">
                        <i class="fas fa-car me-2"></i>
                        Biển số xe
                    </label>
                    <input type="text" 
                           class="form-control @error('BienSoXe') is-invalid @enderror" 
                           id="BienSoXe" 
                           name="BienSoXe" 
                           value="{{ old('BienSoXe', $vehicle->BienSoXe) }}"
                           placeholder="Ví dụ: 59A-07777"
                           maxlength="20">
                    @error('BienSoXe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="SoGhe" class="form-label">
                        <i class="fas fa-couch me-2"></i>
                        Số ghế
                    </label>
                    <input type="number" 
                           class="form-control @error('SoGhe') is-invalid @enderror" 
                           id="SoGhe" 
                           name="SoGhe" 
                           value="{{ old('SoGhe', $vehicle->SoGhe) }}"
                           placeholder="Ví dụ: 40"
                           min="0">
                    @error('SoGhe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="TienNghi" class="form-label">
                        <i class="fas fa-star me-2"></i>
                        Tiện nghi
                    </label>
                    <textarea class="form-control @error('TienNghi') is-invalid @enderror" 
                              id="TienNghi" 
                              name="TienNghi" 
                              rows="3"
                              placeholder="Ví dụ: Wifi, nước, khăn, WC, điều hòa, TV...">{{ old('TienNghi', $vehicle->TienNghi) }}</textarea>
                    @error('TienNghi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Liệt kê các tiện nghi của xe, cách nhau bởi dấu phẩy</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="HinhAnh1" class="form-label">
                        <i class="fas fa-image me-2"></i>
                        Hình ảnh 1
                    </label>
                    @if($vehicle->HinhAnh1)
                        <div class="mb-2">
                            <img src="{{ asset($vehicle->HinhAnh1) }}" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                            <p class="text-muted small mb-0">Hình ảnh hiện tại</p>
                        </div>
                    @endif
                    <input type="file" 
                           class="form-control @error('HinhAnh1') is-invalid @enderror" 
                           id="HinhAnh1" 
                           name="HinhAnh1" 
                           accept="image/*"
                           onchange="previewImage(this, 'preview1')">
                    @error('HinhAnh1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Để trống nếu không muốn thay đổi</small>
                    <div id="preview1" class="mt-2"></div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="HinhAnh2" class="form-label">
                        <i class="fas fa-image me-2"></i>
                        Hình ảnh 2
                    </label>
                    @if($vehicle->HinhAnh2)
                        <div class="mb-2">
                            <img src="{{ asset($vehicle->HinhAnh2) }}" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                            <p class="text-muted small mb-0">Hình ảnh hiện tại</p>
                        </div>
                    @endif
                    <input type="file" 
                           class="form-control @error('HinhAnh2') is-invalid @enderror" 
                           id="HinhAnh2" 
                           name="HinhAnh2" 
                           accept="image/*"
                           onchange="previewImage(this, 'preview2')">
                    @error('HinhAnh2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Để trống nếu không muốn thay đổi</small>
                    <div id="preview2" class="mt-2"></div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="HinhAnh3" class="form-label">
                        <i class="fas fa-image me-2"></i>
                        Hình ảnh 3
                    </label>
                    @if($vehicle->HinhAnh3)
                        <div class="mb-2">
                            <img src="{{ asset($vehicle->HinhAnh3) }}" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                            <p class="text-muted small mb-0">Hình ảnh hiện tại</p>
                        </div>
                    @endif
                    <input type="file" 
                           class="form-control @error('HinhAnh3') is-invalid @enderror" 
                           id="HinhAnh3" 
                           name="HinhAnh3" 
                           accept="image/*"
                           onchange="previewImage(this, 'preview3')">
                    @error('HinhAnh3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Để trống nếu không muốn thay đổi</small>
                    <div id="preview3" class="mt-2"></div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('partner.vehicles') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Cập nhật xe
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '';
    }
}
</script>
@endsection
@endsection

