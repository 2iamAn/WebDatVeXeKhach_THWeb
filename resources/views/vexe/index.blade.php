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
        min-height: calc(100vh - 200px);
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }
    
    /* Main Content */
    .recovery-main {
        width: 100%;
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
        .alert-info .d-flex {
            flex-direction: column;
        }
        
        .alert-info .d-flex > div:last-child {
            margin-left: 0;
            margin-top: 15px;
            width: 100%;
        }
        
        .alert-info .d-flex > div:last-child .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

<div class="booking-recovery-container">
    <!-- Main Content -->
    <div class="recovery-main">
        @if(!session('user') || session('role') != 'user')
        <!-- Login Required Alert -->
        <div class="alert alert-info d-flex align-items-center" role="alert" style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); color: white; border: none; border-radius: 12px; padding: 20px 25px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(79, 185, 159, 0.3);">
            <div style="font-size: 32px; margin-right: 20px;">
                <i class="fas fa-info-circle"></i>
            </div>
            <div style="flex: 1;">
                <h5 class="mb-2" style="font-weight: 700; margin: 0;">Vui lòng đăng nhập để xem đặt chỗ của bạn</h5>
                <p class="mb-0" style="opacity: 0.95; line-height: 1.6;">
                    Đăng nhập vào tài khoản Bustrip của bạn để xem tất cả các đặt chỗ hiện tại và trước đây, cũng như quản lý các vấn đề liên quan đến đặt chỗ (ví dụ: yêu cầu hoàn tiền hoặc đổi lịch).
                </p>
            </div>
            <div style="margin-left: 20px; display: flex; gap: 10px; flex-shrink: 0;">
                <a href="{{ route('login.form') }}" class="btn btn-light" style="border-radius: 8px; padding: 10px 20px; font-weight: 600; white-space: nowrap;">
                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                </a>
                <a href="{{ route('register.form') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white; border-radius: 8px; padding: 10px 20px; font-weight: 600; white-space: nowrap;">
                    <i class="fas fa-user-plus me-2"></i>Đăng ký
                </a>
            </div>
        </div>
        @endif
        
        <!-- Info Banner -->
        
           
           
        </div>
        
    </div>
</div>
@endif
@endsection
