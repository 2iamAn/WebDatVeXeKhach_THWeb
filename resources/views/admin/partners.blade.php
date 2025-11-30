@extends('layouts.admin_layout')

@section('title', 'Duyệt yêu cầu hợp tác')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-handshake me-2"></i> Duyệt yêu cầu hợp tác</h2>
        <p>Quản lý và duyệt các yêu cầu hợp tác từ nhà xe</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Yêu cầu đang chờ phê duyệt -->
    @if($pendingPartners->count() > 0)
        <div class="card-modern mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Yêu cầu đang chờ phê duyệt 
                        <span class="badge bg-warning text-dark ms-2">{{ $pendingPartners->count() }}</span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tên nhà xe</th>
                                <th>Người đại diện</th>
                                <th>Thông tin đăng nhập</th>
                                <th>SĐT</th>
                                <th>Mô tả</th>
                                <th style="text-align: center;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingPartners as $partner)
                                <tr style="background-color: #fff3cd;">
                                    <td><strong>{{ $partner->MaNhaXe }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; margin-right: 12px; font-weight: 600;">
                                                <i class="fas fa-bus"></i>
                                            </div>
                                            <strong>{{ $partner->TenNhaXe }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $partner->nguoiDung ? $partner->nguoiDung->HoTen : '—' }}</td>
                                    <td>
                                        <div>
                                            <div class="mb-1">
                                                <i class="fas fa-envelope text-primary me-1"></i>
                                                <strong>Email:</strong> <code>{{ $partner->nguoiDung ? $partner->nguoiDung->Email : '—' }}</code>
                                            </div>
                                            <div class="mb-1">
                                                <i class="fas fa-user text-info me-1"></i>
                                                <strong>Tên ĐN:</strong> <code>{{ $partner->nguoiDung ? $partner->nguoiDung->TenDangNhap : '—' }}</code>
                                            </div>
                                            <div class="alert alert-info mb-0 py-1 px-2" style="font-size: 11px; margin-top: 4px;">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Mật khẩu:</strong> Do nhà xe tự tạo khi đăng ký
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $partner->nguoiDung ? $partner->nguoiDung->SDT : '—' }}</td>
                                    <td>{{ $partner->MoTa ? \Illuminate\Support\Str::limit($partner->MoTa, 50) : '—' }}</td>
                                    <td style="text-align: center;">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form method="POST" action="{{ route('admin.partners.approve', $partner->MaNhaXe) }}" style="display: inline;">
                                                @csrf
                                                <button class="btn btn-approve btn-action" onclick="return confirm('Bạn có chắc chắn muốn duyệt nhà xe này?\n\nNhà xe sẽ đăng nhập bằng:\n- Email: {{ $partner->Email }}\n- Mật khẩu: (mật khẩu đã đăng ký)')">
                                                    <i class="fas fa-check me-1"></i> Duyệt
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger-custom btn-action" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $partner->MaNhaXe }}">
                                                <i class="fas fa-times me-1"></i> Từ chối
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Từ chối - Đặt bên ngoài vòng lặp để tránh trùng lặp -->
        @foreach($pendingPartners as $partner)
        <div class="modal fade" id="rejectModal{{ $partner->MaNhaXe }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $partner->MaNhaXe }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.partners.reject', $partner->MaNhaXe) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel{{ $partner->MaNhaXe }}">Từ chối yêu cầu hợp tác #{{ $partner->MaNhaXe }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="LyDoTuChoi{{ $partner->MaNhaXe }}" class="form-label">
                                    <strong>Lý do từ chối</strong> <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="LyDoTuChoi{{ $partner->MaNhaXe }}" 
                                          name="LyDoTuChoi" 
                                          rows="4" 
                                          required
                                          placeholder="Nhập lý do từ chối yêu cầu hợp tác này..."></textarea>
                                <small class="text-muted">Lý do này sẽ được gửi đến nhà xe để họ biết và bổ sung thông tin.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">Gửi từ chối</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="card-modern mb-4">
            <div class="card-body">
                <div class="empty-state">
                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                    <h5 class="mt-3">Không có yêu cầu đang chờ</h5>
                    <p class="text-muted">Tất cả yêu cầu hợp tác đã được xử lý.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Nhà xe đã được duyệt -->
    @if($approvedPartners->count() > 0)
        <div class="card-modern">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Nhà xe đã được duyệt 
                        <span class="badge bg-success ms-2">{{ $approvedPartners->count() }}</span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tên nhà xe</th>
                                <th>Người phụ trách</th>
                                <th>Thông tin đăng nhập</th>
                                <th>SĐT</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th style="text-align: center;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedPartners as $partner)
                                <tr>
                                    <td><strong>{{ $partner->MaNhaXe }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; margin-right: 12px; font-weight: 600;">
                                                <i class="fas fa-bus"></i>
                                            </div>
                                            <strong>{{ $partner->TenNhaXe }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $partner->nguoiDung ? $partner->nguoiDung->HoTen : '—' }}</td>
                                    <td>
                                        <div>
                                            <div class="mb-1">
                                                <i class="fas fa-envelope text-primary me-1"></i>
                                                <strong>Email:</strong> <code>{{ $partner->nguoiDung ? $partner->nguoiDung->Email : '—' }}</code>
                                            </div>
                                            <div>
                                                <i class="fas fa-user text-info me-1"></i>
                                                <strong>Tên ĐN:</strong> <code>{{ $partner->nguoiDung ? $partner->nguoiDung->TenDangNhap : '—' }}</code>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $partner->nguoiDung ? $partner->nguoiDung->SDT : '—' }}</td>
                                    <td>{{ $partner->MoTa ? \Illuminate\Support\Str::limit($partner->MoTa, 50) : '—' }}</td>
                                    <td>
                                        <span class="badge bg-success badge-custom">
                                            <i class="fas fa-check-circle me-1"></i> Hoạt động
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('admin.partners.delete', $partner->MaNhaXe) }}" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa nhà xe này?')"
                                           class="btn btn-danger-custom btn-action">
                                            <i class="fas fa-trash me-1"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
