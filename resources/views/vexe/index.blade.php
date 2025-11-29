@extends('layouts.app')
@section('title','Đặt chỗ của tôi')
@section('content')

@if(session('role') == 'admin')
  <!-- Admin view: Danh sách vé -->
  <div class="page-section">
    <div class="container">
      <div class="page-card">
        <div class="page-card__header">
          <div>
            <p class="eyebrow">Đặt chỗ</p>
            <h2 class="mb-2">Danh sách vé xe</h2>
            <p class="text-muted mb-0">Theo dõi trạng thái thanh toán và thông tin khách hàng theo thời gian thực.</p>
          </div>
          <a href="{{ route('vexe.create') }}" class="btn btn-gradient d-flex align-items-center gap-2">
            <i class="fa-solid fa-ticket"></i>
            Đặt vé mới
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-modern align-middle mb-0">
            <thead>
              <tr>
                <th>Mã vé</th>
                <th>Chuyến</th>
                <th>Khách</th>
                <th>Ghế</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ves ?? [] as $v)
                @php
                  $statusClass = \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($v->TrangThai ?? ''), 'chưa') ? 'warning' : 'success';
                  $bookingTime = optional($v->NgayDat)->format('d/m/Y H:i') ?? '--';
                @endphp
                <tr>
                  <td class="fw-semibold">#{{ $v->MaVe }}</td>
                  <td class="fw-semibold text-primary">Chuyến #{{ $v->MaChuyenXe }}</td>
                  <td>{{ optional($v->nguoiDung)->HoTen ?? 'Khách lẻ' }}</td>
                  <td><span class="badge-status info">Ghế {{ optional($v->ghe)->SoGhe ?? '--' }}</span></td>
                  <td><span class="badge-status {{ $statusClass }}">{{ $v->TrangThai }}</span></td>
                  <td>{{ $bookingTime }}</td>
                  <td>
                    <div class="action-buttons d-flex gap-2 justify-content-end">
                      <a class="btn btn-sm btn-outline-primary" href="{{ route('vexe.show',$v->MaVe) }}">
                        <i class="fa-regular fa-eye me-1"></i> Xem
                      </a>
                      <a class="btn btn-sm btn-outline-warning" href="{{ route('vexe.edit',$v->MaVe) }}">
                        <i class="fa-regular fa-pen-to-square me-1"></i> Sửa
                      </a>
                      <a class="btn btn-sm btn-outline-danger" href="{{ route('vexe.destroy',$v->MaVe) }}">
                        <i class="fa-regular fa-trash-can me-1"></i> Xóa
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">Chưa có dữ liệu vé xe.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@elseif(session('role') == 'user' && isset($ves))
  <!-- Customer view: Danh sách vé của khách hàng -->
  <div class="container my-5">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
          <div class="card-header bg-white border-0 pb-0" style="border-radius: 15px 15px 0 0;">
            <div class="d-flex justify-content-between align-items-center py-3">
              <div>
                <h4 class="mb-1" style="color: #2c3e50; font-weight: 700;">
                  <i class="fas fa-ticket-alt me-2" style="color: #4FB99F;"></i>
                  Đặt chỗ của tôi
                </h4>
                <p class="text-muted mb-0">Danh sách tất cả các vé bạn đã đặt</p>
              </div>
            </div>
          </div>
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            @if($ves->count() > 0)
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); color: white;">
                    <tr>
                      <th style="border: none; padding: 15px;">
                        <i class="fas fa-hashtag me-2"></i>Mã vé
                      </th>
                      <th style="border: none; padding: 15px;">
                        <i class="fas fa-building me-2"></i>Nhà xe
                      </th>
                      <th style="border: none; padding: 15px;">
                        <i class="fas fa-route me-2"></i>Tuyến đường
                      </th>
                      <th style="border: none; padding: 15px;">
                        <i class="fas fa-couch me-2"></i>Ghế
                      </th>
                      <th style="border: none; padding: 15px;">
                        <i class="fas fa-calendar-alt me-2"></i>Ngày đi
                      </th>
                      <th style="border: none; padding: 15px;">
                        <i class="fas fa-money-bill-wave me-2"></i>Giá vé
                      </th>
                      <th style="border: none; padding: 15px;">
                        <i class="fas fa-info-circle me-2"></i>Trạng thái
                      </th>
                      <th style="border: none; padding: 15px; text-align: center;">
                        <i class="fas fa-cog me-2"></i>Thao tác
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($ves as $v)
                      @php
                        $statusClass = 'warning';
                        $statusBg = '#fff3cd';
                        $statusColor = '#856404';
                        if (strtolower($v->TrangThai ?? '') == 'đã thanh toán' || strtolower($v->TrangThai ?? '') == 'da_dat') {
                          $statusClass = 'success';
                          $statusBg = '#d4edda';
                          $statusColor = '#155724';
                        } elseif (strpos(strtolower($v->TrangThai ?? ''), 'hủy') !== false || strpos(strtolower($v->TrangThai ?? ''), 'huy') !== false) {
                          $statusClass = 'danger';
                          $statusBg = '#f8d7da';
                          $statusColor = '#721c24';
                        }
                        $canCancel = !in_array(strtolower($v->TrangThai ?? ''), ['hủy', 'huy', 'hoàn tiền', 'hoan tien']);
                      @endphp
                      <tr style="transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                        <td style="padding: 15px;">
                          <strong class="text-primary">#{{ $v->MaVe }}</strong>
                        </td>
                        <td style="padding: 15px;">
                          <i class="fas fa-building text-info me-2"></i>
                          {{ $v->chuyenXe->nhaXe->TenNhaXe ?? '---' }}
                        </td>
                        <td style="padding: 15px;">
                          <i class="fas fa-map-marker-alt text-danger me-1"></i>
                          <strong>{{ $v->chuyenXe->tuyenDuong->DiemDi ?? '---' }}</strong>
                          <i class="fas fa-arrow-right mx-2 text-muted"></i>
                          <i class="fas fa-map-marker-alt text-success me-1"></i>
                          <strong>{{ $v->chuyenXe->tuyenDuong->DiemDen ?? '---' }}</strong>
                        </td>
                        <td style="padding: 15px;">
                          <span class="badge bg-primary" style="font-size: 14px; padding: 8px 12px;">
                            <i class="fas fa-couch me-1"></i>
                            {{ $v->ghe->SoGhe ?? '---' }}
                          </span>
                        </td>
                        <td style="padding: 15px;">
                          <i class="far fa-calendar text-info me-2"></i>
                          {{ $v->chuyenXe->GioKhoiHanh ? \Carbon\Carbon::parse($v->chuyenXe->GioKhoiHanh)->format('d/m/Y H:i') : '---' }}
                        </td>
                        <td style="padding: 15px;">
                          <span class="badge bg-success" style="font-size: 14px; padding: 8px 12px;">
                            <i class="fas fa-money-bill-wave me-1"></i>
                            {{ number_format($v->GiaTaiThoiDiemDat ?? $v->chuyenXe->GiaVe ?? 0, 0, ',', '.') }} ₫
                          </span>
                        </td>
                        <td style="padding: 15px;">
                          <span class="badge" style="background: {{ $statusBg }}; color: {{ $statusColor }}; font-size: 14px; padding: 8px 12px; font-weight: 600;">
                            {{ $v->TrangThai ?? '---' }}
                          </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                          @if($canCancel)
                            <form method="POST" action="{{ route('vexe.cancel', $v->MaVe) }}" style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn hủy vé #{{ $v->MaVe }}?');">
                              @csrf
                              <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;">
                                <i class="fas fa-times-circle me-1"></i>Hủy vé
                              </button>
                            </form>
                          @else
                            <span class="text-muted">Đã hủy</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-5">
                <i class="fas fa-ticket-alt" style="font-size: 64px; color: #dee2e6;"></i>
                <h5 class="mt-3 text-muted">Bạn chưa có vé nào</h5>
                <p class="text-muted">Hãy đặt vé để bắt đầu hành trình của bạn!</p>
                <a href="{{ route('chuyenxe.search') }}" class="btn btn-primary mt-3" style="background: #4FB99F; border: none; border-radius: 8px;">
                  <i class="fas fa-search me-2"></i>Tìm chuyến xe
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@else
<style>
    body {
        background: #f5f7fa;
    }
    
    .booking-recovery-container {
        display: flex;
        min-height: calc(100vh - 200px);
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }
    
    /* Sidebar */
    .recovery-sidebar {
        width: 280px;
        background: white;
        border-radius: 12px;
        padding: 25px 20px;
        margin-right: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        height: fit-content;
    }
    
    .sidebar-section {
        margin-bottom: 30px;
    }
    
    .sidebar-section h6 {
        color: #2c3e50;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .sidebar-menu-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #6c757d;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .sidebar-menu-item i {
        width: 24px;
        margin-right: 12px;
        font-size: 18px;
    }
    
    .sidebar-menu-item:hover {
        background: rgba(79, 185, 159, 0.1);
        color: #4FB99F;
    }
    
    .sidebar-menu-item.active {
        background: linear-gradient(90deg, rgba(79, 185, 159, 0.15), rgba(79, 185, 159, 0.05));
        color: #4FB99F;
        font-weight: 600;
        border-left: 3px solid #4FB99F;
    }
    
    .booking-type-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #6c757d;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s;
        font-size: 14px;
        cursor: pointer;
    }
    
    .booking-type-item i {
        width: 24px;
        margin-right: 12px;
        font-size: 18px;
    }
    
    .booking-type-item:hover {
        background: rgba(79, 185, 159, 0.1);
        color: #4FB99F;
    }
    
    .booking-type-item.selected {
        background: #4FB99F;
        color: white;
        font-weight: 600;
    }
    
    .booking-type-item.selected i {
        color: white;
    }
    
    /* Main Content */
    .recovery-main {
        flex: 1;
    }
    
    /* Info Banner */
    .info-banner {
        background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
        border-radius: 12px;
        padding: 25px 30px;
        margin-bottom: 25px;
        color: white;
        display: flex;
        align-items: flex-start;
        gap: 20px;
        position: relative;
    }
    
    .info-banner-icon {
        font-size: 48px;
        opacity: 0.9;
        flex-shrink: 0;
    }
    
    .info-banner-content h4 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .info-banner-content p {
        font-size: 14px;
        opacity: 0.95;
        line-height: 1.6;
        margin: 0;
    }
    
    .info-banner-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    
    .info-banner-close:hover {
        background: rgba(255,255,255,0.3);
    }
    
    /* Recovery Form Card */
    .recovery-form-card {
        background: white;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .recovery-form-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
    }
    
    .form-row-recovery {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group-recovery {
        margin-bottom: 20px;
    }
    
    .form-group-recovery label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .form-group-recovery input,
    .form-group-recovery select {
        width: 100%;
        padding: 12px 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
    }
    
    .form-group-recovery input:focus,
    .form-group-recovery select:focus {
        outline: none;
        border-color: #4FB99F;
        box-shadow: 0 0 0 3px rgba(79, 185, 159, 0.1);
    }
    
    .phone-input-group {
        display: flex;
        gap: 10px;
    }
    
    .phone-country {
        width: 120px;
        flex-shrink: 0;
    }
    
    .phone-number {
        flex: 1;
    }
    
    .btn-recover {
        background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
        color: white;
        border: none;
        padding: 14px 14px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(79, 185, 159, 0.3);
    }
    
    .btn-recover:hover {
        background: linear-gradient(135deg, #3a8f7a 0%, #2d6f5e 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 185, 159, 0.4);
    }
    
    .form-help-text {
        font-size: 13px;
        color: #6c757d;
        margin-top: 8px;
    }
    
    .form-instruction {
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #4FB99F;
    }
    
    .form-instruction p {
        font-size: 14px;
        color: #6c757d;
        line-height: 1.6;
        margin: 0;
    }
    
    /* Results Section */
    .booking-results {
        margin-top: 30px;
    }
    
    .booking-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #4FB99F;
    }
    
    @media (max-width: 992px) {
        .booking-recovery-container {
            flex-direction: column;
        }
        
        .recovery-sidebar {
            width: 100%;
            margin-right: 0;
            margin-bottom: 25px;
        }
        
        .form-row-recovery {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="booking-recovery-container">
    <!-- Sidebar -->
    <div class="recovery-sidebar">
        <div class="sidebar-section">
            <h6>Giao dịch đang tiến hành</h6>
            <a href="#" class="sidebar-menu-item">
                <i class="fas fa-list-check"></i>
                Tất cả sản phẩm
            </a>
        </div>
        
        <div class="sidebar-section">
            <h6>Khôi phục đặt chỗ</h6>
            <div class="booking-type-item selected" data-type="vexe">
                <i class="fas fa-bus"></i>
                Vé xe
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="recovery-main">
        <!-- Info Banner -->
        <div class="info-banner" id="infoBanner">
            <div class="info-banner-icon">
                <i class="fas fa-window-restore"></i>
            </div>
            <div class="info-banner-content">
                <h4>Dễ dàng truy cập đặt chỗ của bạn trên Bustrip</h4>
                <p>Đăng nhập vào tài khoản Bustrip hoặc đăng ký để xem các đặt chỗ hiện tại và trước đây của bạn, cũng như quản lý mọi vấn đề liên quan tới đặt chỗ (ví dụ: yêu cầu hoàn tiền hoặc đổi lịch).</p>
            </div>
            <button type="button" class="info-banner-close" onclick="document.getElementById('infoBanner').style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Recovery Form -->
        <div class="recovery-form-card">
            <h2 class="recovery-form-title">Khôi phục đặt chỗ</h2>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form method="GET" action="{{ route('vexe.index') }}" id="recoveryForm">
                <div class="form-row-recovery">
                    <div class="form-group-recovery">
                        <label for="ma_dat_cho">Mã đặt chỗ Bustrip</label>
                        <input type="text" 
                               id="ma_dat_cho" 
                               name="ma_ve" 
                               class="form-control" 
                               placeholder="Nhập mã đặt chỗ"
                               value="{{ request('ma_ve') }}"
                               required>
                    </div>
                    
                    <div class="form-group-recovery">
                        <label for="ngay_khoi_hanh">Ngày khởi hành</label>
                        <input type="date" 
                               id="ngay_khoi_hanh" 
                               name="ngay_khoi_hanh" 
                               class="form-control"
                               value="{{ request('ngay_khoi_hanh', date('Y-m-d')) }}"
                               required>
                    </div>
                </div>
                
                <div class="form-group-recovery">
                    <label for="so_dien_thoai">Số di động</label>
                    <div class="phone-input-group">
                        <select class="phone-country form-control" id="country_code" name="country_code">
                            <option value="+84" selected>🇻🇳 +84</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+86">🇨🇳 +86</option>
                        </select>
                        <input type="text" 
                               id="so_dien_thoai" 
                               name="sdt" 
                               class="phone-number form-control" 
                               placeholder="Nhập số điện thoại"
                               value="{{ request('sdt') }}"
                               required>
                        <button type="submit" class="btn-recover">
                            Khôi phục đặt chỗ
                        </button>
                    </div>
                    <small class="form-help-text">Số điện thoại bạn cung cấp khi đặt chỗ.</small>
                </div>
            </form>
            
            <div class="form-instruction">
                <p>
                    <strong>Lưu ý:</strong> Sau khi gửi biểu mẫu bên trên, chúng tôi sẽ gửi vé điện tử trực tiếp đến địa chỉ email bạn đã sử dụng khi đặt chỗ. Biểu mẫu này cũng có thể sử dụng để hoàn thành các giao dịch chưa thanh toán của bạn.
                </p>
            </div>
        </div>
        
        <!-- Booking Results -->
        @if(request()->has('sdt') && request()->has('ma_ve'))
            @php
                $ve = \App\Models\VeXe::with(['chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'nguoiDung', 'ghe', 'thanhToan'])
                    ->whereHas('nguoiDung', function($q) {
                        $q->where('SDT', request('sdt'));
                    })
                    ->where('MaVe', request('ma_ve'));
                
                // Nếu có ngày khởi hành, kiểm tra thêm
                if (request()->has('ngay_khoi_hanh') && request('ngay_khoi_hanh')) {
                    $ve->whereHas('chuyenXe', function($q) {
                        $q->whereDate('GioKhoiHanh', request('ngay_khoi_hanh'));
                    });
                }
                
                $ve = $ve->first();
            @endphp
            
            @if($ve)
                <div class="booking-results">
                    <div class="booking-card">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="mb-2" style="color: #2c3e50;">
                                    <i class="fas fa-ticket-alt me-2" style="color: #4FB99F;"></i>
                                    Thông tin đặt chỗ
                                </h4>
                                <p class="text-muted mb-0">Mã đặt chỗ: <strong>#{{ $ve->MaVe }}</strong></p>
                            </div>
                            @php
                                $statusClass = 'warning';
                                $statusBg = '#fff3cd';
                                $statusColor = '#856404';
                                if ($ve->TrangThai == 'Đã thanh toán') {
                                    $statusClass = 'success';
                                    $statusBg = '#d4edda';
                                    $statusColor = '#155724';
                                } elseif (strpos($ve->TrangThai, 'Hủy') !== false) {
                                    $statusClass = 'danger';
                                    $statusBg = '#f8d7da';
                                    $statusColor = '#721c24';
                                }
                            @endphp
                            <span class="badge" style="background: {{ $statusBg }}; color: {{ $statusColor }}; padding: 8px 16px; font-size: 14px; font-weight: 600;">
                                {{ $ve->TrangThai }}
                            </span>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Khách hàng</label>
                                    <p class="mb-0 fw-semibold">{{ $ve->nguoiDung->HoTen ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Số điện thoại</label>
                                    <p class="mb-0 fw-semibold">{{ $ve->nguoiDung->SDT ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Email</label>
                                    <p class="mb-0 fw-semibold">{{ $ve->nguoiDung->Email ?? '---' }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Tuyến đường</label>
                                    <p class="mb-0 fw-semibold">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        {{ $ve->chuyenXe->tuyenDuong->DiemDi ?? '---' }}
                                        <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                        <i class="fas fa-map-marker-alt text-success me-1"></i>
                                        {{ $ve->chuyenXe->tuyenDuong->DiemDen ?? '---' }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Nhà xe</label>
                                    <p class="mb-0 fw-semibold">{{ $ve->chuyenXe->nhaXe->TenNhaXe ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Ghế số</label>
                                    <p class="mb-0 fw-semibold">
                                        <span class="badge" style="background: #4FB99F; color: white; padding: 6px 12px;">
                                            {{ $ve->ghe->SoGhe ?? '---' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="text-muted small">Giờ khởi hành</label>
                                <p class="mb-0 fw-semibold">
                                    <i class="far fa-clock me-2" style="color: #4FB99F;"></i>
                                    {{ \Carbon\Carbon::parse($ve->chuyenXe->GioKhoiHanh)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Ngày đặt</label>
                                <p class="mb-0 fw-semibold">
                                    <i class="far fa-calendar me-2" style="color: #4FB99F;"></i>
                                    {{ \Carbon\Carbon::parse($ve->NgayDat)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Giá vé</label>
                                <p class="mb-0 fw-semibold" style="color: #4FB99F; font-size: 18px;">
                                    {{ number_format($ve->GiaTaiThoiDiemDat) }} ₫
                                </p>
                            </div>
                        </div>
                        
                        @if($ve->thanhToan)
                            <hr class="my-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">Phương thức thanh toán</label>
                                    <p class="mb-0 fw-semibold">{{ $ve->thanhToan->PhuongThuc }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Ngày thanh toán</label>
                                    <p class="mb-0 fw-semibold">
                                        {{ \Carbon\Carbon::parse($ve->thanhToan->NgayThanhToan)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4 pt-4 border-top">
                            <a href="{{ route('vexe.show', $ve->MaVe) }}" class="btn" style="background: #4FB99F; color: white; padding: 10px 25px; border-radius: 8px; text-decoration: none;">
                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="booking-results">
                    <div class="alert alert-warning" style="border-radius: 12px; padding: 20px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Không tìm thấy đặt chỗ</strong><br>
                        Không tìm thấy đặt chỗ với thông tin đã nhập. Vui lòng kiểm tra lại mã đặt chỗ, ngày khởi hành và số điện thoại.
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set today's date as default if not provided
    const ngayKhoiHanh = document.getElementById('ngay_khoi_hanh');
    if (ngayKhoiHanh && !ngayKhoiHanh.value) {
        ngayKhoiHanh.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endif
@endsection
