@extends('layouts.admin_layout')

@section('title', 'Sửa người dùng')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-user-edit me-2"></i> Sửa thông tin người dùng</h2>
        <p>Cập nhật thông tin người dùng trong hệ thống</p>
    </div>

    <div class="card-modern">
        <div class="card-body p-4">
            <div class="mb-3">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user->MaNguoiDung) }}">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="HoTen" class="form-control" 
                               value="{{ $user->HoTen }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="SDT" class="form-control" 
                               value="{{ $user->SDT }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="Email" class="form-control" 
                               value="{{ $user->Email }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="TrangThai" class="form-select">
                            <option value="1" @selected($user->TrangThai == 1)>Hoạt động</option>
                            <option value="0" @selected($user->TrangThai == 0)>Khóa</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" 
                               value="{{ $user->TenDangNhap }}" disabled>
                        <small class="text-muted">Tên đăng nhập không thể thay đổi</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Loại người dùng</label>
                        <input type="text" class="form-control" 
                               value="{{ $user->LoaiNguoiDung }}" disabled>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-action">
                        <i class="fas fa-times me-2"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary-custom btn-action">
                        <i class="fas fa-save me-2"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
