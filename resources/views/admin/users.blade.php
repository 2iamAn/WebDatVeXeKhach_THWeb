@extends('layouts.admin_layout')

@section('title', 'Quản lý người dùng')

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
                                <th>Tên đăng nhập</th>
                                <th>Email</th>
                                <th>SĐT</th>
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
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; margin-right: 12px; font-weight: 600;">
                                                {{ substr($user->HoTen, 0, 1) }}
                                            </div>
                                            {{ $user->HoTen }}
                                        </div>
                                    </td>
                                    <td><code>{{ $user->TenDangNhap }}</code></td>
                                    <td>{{ $user->Email }}</td>
                                    <td>{{ $user->SDT }}</td>
                                    <td>
                                        @php
                                            $roleLabel = 'Khách hàng';
                                            if ((int)$user->LoaiNguoiDung == 3) {
                                                $roleLabel = 'Admin';
                                            } elseif ((int)$user->LoaiNguoiDung == 2) {
                                                $roleLabel = 'Nhà xe';
                                            }
                                        @endphp
                                        <span class="badge bg-info-subtle text-dark">{{ $roleLabel }}</span>
                                    </td>
                                    <td>
                                        @if($user->TrangThai == 1)
                                            <span class="badge bg-success badge-custom">
                                                <i class="fas fa-check-circle me-1"></i> Hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-secondary badge-custom">
                                                <i class="fas fa-times-circle me-1"></i> Khóa
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.users.edit', $user->MaNguoiDung) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Sửa thông tin">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if($user->TrangThai == 0)
                                                <!-- Nút phê duyệt -->
                                                <form method="POST" 
                                                      action="{{ route('admin.users.approve', $user->MaNguoiDung) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn phê duyệt tài khoản này?')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="Phê duyệt">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Nút ngưng hoạt động -->
                                                <form method="POST" 
                                                      action="{{ route('admin.users.toggle-status', $user->MaNguoiDung) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn ngưng hoạt động tài khoản này?')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-secondary" 
                                                            title="Ngưng hoạt động">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <!-- Nút từ chối -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $user->MaNguoiDung }}"
                                                    title="Từ chối">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            
                                            <!-- Nút xóa -->
                                            <a href="{{ route('admin.users.delete', $user->MaNguoiDung) }}" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')"
                                               class="btn btn-sm btn-danger" 
                                               title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </a>
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
