@extends('layouts.app')
@section('title','Thêm người dùng')
@section('heading','Thêm người dùng')
@section('content')
<form method="post" action="{{ route('nguoidung.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4"><label class="form-label">Họ tên</label><input name="HoTen" class="form-control" required></div>
  <div class="col-md-4"><label class="form-label">Tên đăng nhập</label><input name="TenDangNhap" class="form-control" required></div>
  <div class="col-md-4"><label class="form-label">Mật khẩu</label><input type="password" name="MatKhau" class="form-control" required></div>
  <div class="col-md-4">
    <label class="form-label">Loại người dùng</label>
    <select name="LoaiNguoiDung" class="form-select" required>
      <option value="1">Khách hàng</option>
      <option value="2">Nhà xe</option>
      <option value="3">Admin</option>
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">SDT</label><input name="SDT" class="form-control"></div>
  <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="Email" class="form-control"></div>
  <div class="col-md-3">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="1" selected>Hoạt động</option>
      <option value="0">Khóa</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('nguoidung.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection