@extends('layouts.admin_layout')

@section('title', 'Quản lý chuyến xe')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-route me-2"></i> Quản lý chuyến xe</h2>
        <p>Xem tất cả chuyến xe, duyệt/chặn chuyến xe mới, và khóa tạm thời chuyến xe vi phạm</p>
    </div>

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

    <!-- Phần chuyến xe chờ duyệt -->
    @if($pendingTrips->count() > 0)
    <div class="card-modern mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-clock me-2"></i>
                Chuyến xe chờ duyệt ({{ $pendingTrips->count() }})
            </h5>
        </div>
        <div class="card-body">
            @foreach($pendingTrips as $trip)
                    <div class="card mb-3 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                                    <h5 class="mb-1">Chuyến xe #{{ $trip->MaChuyenXe }}</h5>
                                    <span class="badge bg-warning badge-custom">
                                        <i class="fas fa-clock me-1"></i> Chờ duyệt
                            </span>
                        </div>
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.trips.approve', $trip->MaChuyenXe) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-action" 
                                                onclick="return confirm('Bạn có chắc chắn muốn phê duyệt chuyến xe này?')">
                                            <i class="fas fa-check me-2"></i> Phê duyệt
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-action" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $trip->MaChuyenXe }}">
                                        <i class="fas fa-times me-2"></i> Từ chối
                                    </button>
                            <a href="{{ route('admin.trips.show', $trip->MaChuyenXe) }}" class="btn btn-primary btn-action">
                                <i class="fas fa-eye me-2"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bus me-2 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Nhà xe</small>
                                    <strong>{{ $trip->TenNhaXe ?? 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-route me-2 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Tuyến</small>
                                    <strong>{{ $trip->DiemDi ?? '--' }} → {{ $trip->DiemDen ?? '--' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Khởi hành</small>
                                    <strong>{{ \Carbon\Carbon::parse($trip->GioKhoiHanh)->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                <div>
                                    <small class="text-muted d-block">Giá vé</small>
                                    <strong class="text-success">{{ number_format($trip->GiaVe, 0, ',', '.') }} đ</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Modal Từ chối -->
                    <div class="modal fade" id="rejectModal{{ $trip->MaChuyenXe }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Từ chối chuyến xe #{{ $trip->MaChuyenXe }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.trips.reject', $trip->MaChuyenXe) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-comment-alt me-2"></i>
                                                Lý do từ chối <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="LyDoTuChoi" 
                                                      class="form-control" 
                                                      rows="4" 
                                                      required
                                                      placeholder="Nhập lý do từ chối chuyến xe này..."></textarea>
                                            <small class="text-muted">Lý do này sẽ được gửi đến nhà xe để họ biết và chỉnh sửa.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times me-2"></i>Xác nhận từ chối
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
        @endforeach
        </div>
    </div>
    @else
    <div class="card-modern mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-clock me-2"></i>
                Chuyến xe chờ duyệt (0)
            </h5>
        </div>
        <div class="card-body">
            <div class="text-center py-4">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>Không có chuyến xe nào chờ duyệt</h5>
                <p class="text-muted">Tất cả các chuyến xe đã được xử lý.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Phần tất cả chuyến xe -->
    <div class="card-modern">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Tất cả chuyến xe ({{ $allTrips->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($allTrips->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Nhà xe</th>
                                <th>Tuyến</th>
                                <th>Khởi hành</th>
                                <th>Giá vé</th>
                                <th>Trạng thái</th>
                                <th>Lý do</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allTrips as $trip)
                                <tr>
                                    <td><strong>#{{ $trip->MaChuyenXe }}</strong></td>
                                    <td>{{ $trip->TenNhaXe ?? 'N/A' }}</td>
                                    <td>{{ $trip->DiemDi ?? '--' }} → {{ $trip->DiemDen ?? '--' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trip->GioKhoiHanh)->format('d/m/Y H:i') }}</td>
                                    <td class="text-success"><strong>{{ number_format($trip->GiaVe, 0, ',', '.') }} đ</strong></td>
                                    <td>
                                        @php
                                            $statusClass = 'success';
                                            $statusIcon = 'check-circle';
                                            if ($trip->TrangThai == 'Hết chỗ' || $trip->TrangThai == 'Tạm dừng') {
                                                $statusClass = 'warning';
                                                $statusIcon = 'exclamation-circle';
                                            } elseif ($trip->TrangThai == 'TuChoi') {
                                                $statusClass = 'danger';
                                                $statusIcon = 'times-circle';
                                            } elseif ($trip->TrangThai == 'BiKhoa') {
                                                $statusClass = 'danger';
                                                $statusIcon = 'lock';
                                            } elseif ($trip->TrangThai == 'ChoDuyet') {
                                                $statusClass = 'warning';
                                                $statusIcon = 'clock';
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                            @if($trip->TrangThai == 'TuChoi')
                                                Từ chối
                                            @elseif($trip->TrangThai == 'DaDuyet')
                                                Đã duyệt
                                            @elseif($trip->TrangThai == 'BiKhoa')
                                                Bị khóa
                                            @elseif($trip->TrangThai == 'ChoDuyet')
                                                Chờ duyệt
                                            @else
                                                {{ $trip->TrangThai }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if(($trip->TrangThai == 'TuChoi' || $trip->TrangThai == 'BiKhoa') && $trip->LyDoTuChoi)
                                            <span class="text-danger" style="font-size: 12px;" title="{{ $trip->LyDoTuChoi }}">
                                                <i class="fas fa-info-circle me-1"></i>
                                                {{ Str::limit($trip->LyDoTuChoi, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.trips.show', $trip->MaChuyenXe) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($trip->TrangThai == 'BiKhoa')
                                                <!-- Nút mở khóa -->
                                                <form method="POST" 
                                                      action="{{ route('admin.trips.unlock', $trip->MaChuyenXe) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn mở khóa chuyến xe này?')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="Mở khóa chuyến xe">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </form>
                                            @elseif($trip->TrangThai != 'ChoDuyet' && $trip->TrangThai != 'TuChoi')
                                                <!-- Nút khóa tạm thời -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#lockModal{{ $trip->MaChuyenXe }}"
                                                        title="Khóa tạm thời">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                                
                                                <!-- Modal Khóa chuyến xe -->
                                                <div class="modal fade" id="lockModal{{ $trip->MaChuyenXe }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-lock me-2"></i>
                                                                    Khóa tạm thời chuyến xe #{{ $trip->MaChuyenXe }}
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('admin.trips.lock', $trip->MaChuyenXe) }}">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                                        Chuyến xe này sẽ bị khóa tạm thời và không hiển thị cho khách hàng.
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">
                                                                            <i class="fas fa-comment-alt me-2"></i>
                                                                            Lý do khóa <span class="text-danger">*</span>
                                                                        </label>
                                                                        <textarea name="LyDoKhoa" 
                                                                                  class="form-control" 
                                                                                  rows="4" 
                                                                                  required
                                                                                  placeholder="Nhập lý do khóa chuyến xe này (ví dụ: Vi phạm quy định, thông tin sai lệch...)"></textarea>
                                                                        <small class="text-muted">Lý do này sẽ được gửi đến nhà xe.</small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-lock me-2"></i>Xác nhận khóa
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-route fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h5 class="text-muted">Chưa có chuyến xe nào</h5>
                </div>
            @endif
        </div>
    </div>
@endsection
