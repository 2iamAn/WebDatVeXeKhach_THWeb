@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-plus-circle"></i>
        Thêm chuyến xe mới
    </h2>
    <p class="text-muted mb-0 mt-2">Tạo chuyến xe mới cho nhà xe của bạn</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('partner.trips.store') }}" class="row g-3">
            @csrf
            
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fas fa-building me-2 text-primary"></i>Nhà xe
                </label>
                <input type="text" class="form-control" 
                       value="{{ $tenNhaXe ?? 'Nhà xe' }}" 
                       readonly
                       style="border-radius: 10px; padding: 12px; background-color: #f8f9fa;"
                       placeholder="Tên nhà xe">
                <small class="form-text text-muted">Tên nhà xe của bạn</small>
            </div>
            
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-route me-2 text-primary"></i>Tuyến đường <span class="text-danger">*</span>
                </label>
                <select name="MaTuyen" class="form-control @error('MaTuyen') is-invalid @enderror" 
                        required
                        style="border-radius: 10px; padding: 12px;">
                    <option value="">-- Chọn tuyến đường --</option>
                    @foreach($tuyens as $tuyen)
                        <option value="{{ $tuyen->MaTuyen }}" {{ old('MaTuyen') == $tuyen->MaTuyen ? 'selected' : '' }}>
                            {{ $tuyen->DiemDi }} → {{ $tuyen->DiemDen }}
                        </option>
                    @endforeach
                </select>
                @error('MaTuyen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Nếu chưa có tuyến đường, vui lòng tạo mới trong <a href="{{ route('partner.routes.create') }}">Quản lý tuyến đường</a></small>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-bus me-2 text-info"></i>Xe <span class="text-muted">(Tùy chọn)</span>
                </label>
                <select name="MaXe" class="form-control @error('MaXe') is-invalid @enderror" 
                        style="border-radius: 10px; padding: 12px;">
                    <option value="">-- Chọn xe --</option>
                    @foreach($xes as $xe)
                        <option value="{{ $xe->MaXe }}" {{ old('MaXe') == $xe->MaXe ? 'selected' : '' }}>
                            {{ $xe->TenXe }} @if($xe->LoaiXe) - {{ $xe->LoaiXe }} @endif
                        </option>
                    @endforeach
                </select>
                @error('MaXe')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Nếu chưa có xe, vui lòng tạo mới trong <a href="{{ route('partner.vehicles.create') }}">Quản lý xe</a></small>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>Điểm lên xe <span class="text-danger">*</span>
                </label>
                <input type="text" name="DiemLenXe" class="form-control @error('DiemLenXe') is-invalid @enderror" 
                       value="{{ old('DiemLenXe') }}" 
                       required
                       style="border-radius: 10px; padding: 12px;"
                       placeholder="Ví dụ: Bến xe An Sương, Ngã 4 Bình Phước...">
                @error('DiemLenXe')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-map-marker-alt me-2 text-success"></i>Điểm xuống xe <span class="text-danger">*</span>
                </label>
                <input type="text" name="DiemXuongXe" class="form-control @error('DiemXuongXe') is-invalid @enderror" 
                       value="{{ old('DiemXuongXe') }}" 
                       required
                       style="border-radius: 10px; padding: 12px;"
                       placeholder="Ví dụ: VP Buôn Hồ 2, VP Buôn Mê Thuột...">
                @error('DiemXuongXe')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-money-bill-wave me-2 text-success"></i>Giá vé (VND)
                </label>
                <input type="number" name="GiaVe" class="form-control" 
                       value="{{ old('GiaVe') }}" 
                       min="0" step="1000" required
                       style="border-radius: 10px; padding: 12px;"
                       placeholder="Nhập giá vé">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt me-2 text-info"></i>Giờ khởi hành
                </label>
                <input type="datetime-local" name="GioKhoiHanh" 
                       class="form-control" 
                       value="{{ old('GioKhoiHanh') }}" 
                       required
                       style="border-radius: 10px; padding: 12px;">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-clock me-2 text-warning"></i>Giờ đến
                </label>
                <input type="datetime-local" name="GioDen" 
                       class="form-control" 
                       value="{{ old('GioDen') }}"
                       style="border-radius: 10px; padding: 12px;">
            </div>


            <div class="col-12 mt-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Lưu ý:</strong> Chuyến xe của bạn sẽ được gửi đến admin để phê duyệt. Sau khi được phê duyệt, chuyến xe mới được hiển thị cho khách hàng.
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 12px 30px;">
                    <i class="fas fa-paper-plane me-2"></i>
                    Gửi yêu cầu phê duyệt
                </button>
                <a href="{{ route('partner.trips') }}" class="btn btn-secondary" style="border-radius: 10px; padding: 12px 30px;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

