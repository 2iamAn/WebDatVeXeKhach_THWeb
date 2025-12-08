@extends('layouts.admin_layout')

@section('title', 'Quản lý người dùng')

@push('styles')
<style>
    /* Tối ưu bảng responsive */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0;
        padding: 0;
    }
    
    .table-modern {
        width: 100%;
        table-layout: auto;
        margin-bottom: 0;
    }
    
    .table-modern th,
    .table-modern td {
        padding: 10px 8px;
        vertical-align: middle;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    /* Điều chỉnh độ rộng cột - sử dụng min-width để đảm bảo không quá nhỏ */
    .table-modern th:nth-child(1),
    .table-modern td:nth-child(1) {
        width: 5%;
        min-width: 60px;
    }
    
    .table-modern th:nth-child(2),
    .table-modern td:nth-child(2) {
        width: 15%;
        min-width: 140px;
    }
    
    .table-modern th:nth-child(3),
    .table-modern td:nth-child(3) {
        width: 12%;
        min-width: 110px;
    }
    
    .table-modern th:nth-child(4),
    .table-modern td:nth-child(4) {
        width: 18%;
        min-width: 160px;
    }
    
    .table-modern th:nth-child(5),
    .table-modern td:nth-child(5) {
        width: 10%;
        min-width: 90px;
    }
    
    .table-modern th:nth-child(6),
    .table-modern td:nth-child(6) {
        width: 9%;
        min-width: 80px;
    }
    
    .table-modern th:nth-child(7),
    .table-modern td:nth-child(7) {
        width: 11%;
        min-width: 100px;
    }
    
    .table-modern th:nth-child(8),
    .table-modern td:nth-child(8) {
        width: 12%;
        min-width: 90px;
        text-align: center;
    }
    
    /* Dropdown menu tối ưu */
    .dropdown-menu {
        min-width: 180px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
        border-radius: 6px;
        padding: 4px 0;
    }
    
    .dropdown-item {
        padding: 8px 16px;
        font-size: 13px;
        transition: all 0.2s;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .dropdown-item i {
        width: 18px;
    }
    
    /* Nút hành động nhỏ gọn hơn */
    .btn-group .btn-sm {
        padding: 4px 6px;
        font-size: 12px;
    }
    
    /* Text truncate cho các cột dài */
    .text-truncate-custom {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Responsive cho màn hình nhỏ */
    @media (max-width: 1400px) {
        .table-modern th:nth-child(2),
        .table-modern td:nth-child(2) {
            min-width: 120px;
        }
        
        .table-modern th:nth-child(4),
        .table-modern td:nth-child(4) {
            min-width: 140px;
        }
    }
    
    @media (max-width: 1200px) {
        .table-modern th:nth-child(2),
        .table-modern td:nth-child(2) {
            min-width: 110px;
        }
        
        .table-modern th:nth-child(4),
        .table-modern td:nth-child(4) {
            min-width: 130px;
        }
    }
    
    @media (max-width: 992px) {
        .table-modern th,
        .table-modern td {
            padding: 8px 6px;
            font-size: 13px;
        }
        
        .table-modern th:nth-child(2),
        .table-modern td:nth-child(2) {
            min-width: 100px;
        }
        
        .table-modern th:nth-child(3),
        .table-modern td:nth-child(3) {
            min-width: 90px;
        }
        
        .table-modern th:nth-child(4),
        .table-modern td:nth-child(4) {
            min-width: 120px;
        }
        
        .table-modern th:nth-child(8),
        .table-modern td:nth-child(8) {
            min-width: 80px;
        }
    }
    
    @media (max-width: 768px) {
        .table-modern th,
        .table-modern td {
            padding: 6px 4px;
            font-size: 12px;
        }
        
        .btn-sm {
            padding: 3px 5px;
            font-size: 11px;
        }
    }
    
    /* Đảm bảo container không bị tràn */
    .card-modern {
        overflow: hidden;
    }
    
    .card-body {
        overflow-x: hidden;
    }
</style>
@endpush

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-users me-2"></i> Quản lý người dùng</h2>
        <p>Quản lý, phê duyệt/từ chối và ngưng hoạt động tài khoản người dùng (Khách hàng và Nhà xe)</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-modern">
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Họ tên</th>
                                <th class="d-none d-md-table-cell">Tên đăng nhập</th>
                                <th class="d-none d-lg-table-cell">Email</th>
                                <th class="d-none d-xl-table-cell">SĐT</th>
                                <th>Loại</th>
                                <th>Trạng thái</th>
                                <th style="text-align: center;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><strong>{{ $user->MaNguoiDung }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" 
                                                 style="width: 32px; height: 32px; margin-right: 8px; font-weight: 600; font-size: 13px;">
                                                {{ substr($user->HoTen, 0, 1) }}
                                            </div>
                                            <div class="flex-grow-1 text-truncate-custom" title="{{ $user->HoTen }}">
                                                {{ $user->HoTen }}
                                            </div>
                                        </div>
                                        <small class="text-muted d-md-none mt-1 d-block">
                                            <code style="font-size: 10px;">{{ $user->TenDangNhap }}</code>
                                        </small>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <code class="text-truncate-custom d-block" style="font-size: 11px;" title="{{ $user->TenDangNhap }}">{{ $user->TenDangNhap }}</code>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <div class="text-truncate-custom" title="{{ $user->Email }}">
                                            {{ $user->Email }}
                                        </div>
                                    </td>
                                    <td class="d-none d-xl-table-cell">{{ $user->SDT }}</td>
                                    <td>
                                        @php
                                            $roleLabel = 'Khách hàng';
                                            if ((int)$user->LoaiNguoiDung == 3) {
                                                $roleLabel = 'Admin';
                                            } elseif ((int)$user->LoaiNguoiDung == 2) {
                                                $roleLabel = 'Nhà xe';
                                            }
                                        @endphp
                                        <span class="badge bg-info-subtle text-dark" style="font-size: 11px;">{{ $roleLabel }}</span>
                                    </td>
                                    <td>
                                        @if($user->TrangThai == 1)
                                            <span class="badge bg-success badge-custom" style="font-size: 11px;">
                                                <i class="fas fa-check-circle me-1"></i> Hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-secondary badge-custom" style="font-size: 11px;">
                                                <i class="fas fa-times-circle me-1"></i> Khóa
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary dropdown-toggle" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false"
                                                    style="font-size: 11px; padding: 4px 6px;">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.users.edit', $user->MaNguoiDung) }}">
                                                        <i class="fas fa-edit text-warning me-2"></i> Sửa thông tin
                                                    </a>
                                                </li>
                                                
                                                @if($user->TrangThai == 0)
                                                    <li>
                                                        <form method="POST" 
                                                              action="{{ route('admin.users.approve', $user->MaNguoiDung) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Bạn có chắc chắn muốn phê duyệt tài khoản này?')">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check me-2"></i> Phê duyệt
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <form method="POST" 
                                                              action="{{ route('admin.users.toggle-status', $user->MaNguoiDung) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Bạn có chắc chắn muốn ngưng hoạt động tài khoản này?')">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-secondary">
                                                                <i class="fas fa-ban me-2"></i> Ngưng hoạt động
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                
                                                <li>
                                                    <button type="button" 
                                                            class="dropdown-item text-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal{{ $user->MaNguoiDung }}">
                                                        <i class="fas fa-times me-2"></i> Từ chối
                                                    </button>
                                                </li>
                                                
                                                <li><hr class="dropdown-divider"></li>
                                                
                                                <li>
                                                    <a class="dropdown-item text-danger" 
                                                       href="{{ route('admin.users.delete', $user->MaNguoiDung) }}" 
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                                        <i class="fas fa-trash me-2"></i> Xóa
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Từ chối - Đặt bên ngoài bảng để tránh trùng lặp -->
                @foreach($users as $user)
                <div class="modal fade" id="rejectModal{{ $user->MaNguoiDung }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Từ chối tài khoản {{ $user->MaNguoiDung }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.users.reject', $user->MaNguoiDung) }}">
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
                                                  placeholder="Nhập lý do từ chối tài khoản này..."></textarea>
                                        <small class="text-muted">Tài khoản sẽ bị khóa sau khi từ chối.</small>
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
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h5 class="mt-3">Chưa có người dùng nào</h5>
                    <p class="text-muted">Hiện tại chưa có người dùng trong hệ thống.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
