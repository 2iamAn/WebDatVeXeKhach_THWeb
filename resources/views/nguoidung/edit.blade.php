@extends('layouts.app')
@section('title','Sửa người dùng')
@section('heading','Sửa người dùng')
@section('content')
<form method="post" action="{{ route('nguoidung.update',$nguoidung->MaNguoiDung) }}" class="row g-3">
  @csrf
  <div class="col-md-4"><label class="form-label">Họ tên</label><input name="HoTen" class="form-control" value="{{ $nguoidung->HoTen }}" required></div>
  <div class="col-md-4"><label class="form-label">Tên đăng nhập</label><input name="TenDangNhap" class="form-control" value="{{ $nguoidung->TenDangNhap }}" required></div>
  <div class="col-md-4">
    <label class="form-label">Loại người dùng</label>
    <select name="LoaiNguoiDung" class="form-select">
      <option value="1" @selected($nguoidung->LoaiNguoiDung == 1)>Khách hàng</option>
      <option value="2" @selected($nguoidung->LoaiNguoiDung == 2)>Nhà xe</option>
      <option value="3" @selected($nguoidung->LoaiNguoiDung == 3)>Admin</option>
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">SDT</label><input name="SDT" class="form-control" value="{{ $nguoidung->SDT }}"></div>
  <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="Email" class="form-control" value="{{ $nguoidung->Email }}"></div>
  <div class="col-md-4"><label class="form-label">Mật khẩu mới (nếu đổi)</label><input type="password" name="MatKhau" class="form-control" placeholder="Để trống nếu giữ nguyên"></div>
  <div class="col-md-3">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="1" @selected($nguoidung->TrangThai == 1)>Hoạt động</option>
      <option value="0" @selected($nguoidung->TrangThai == 0)>Khóa</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('nguoidung.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection