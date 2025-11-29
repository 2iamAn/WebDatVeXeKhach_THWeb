@extends('layouts.app')
@section('title','Người dùng')
@section('heading','Người dùng')
@section('content')
  <a href="{{ route('nguoidung.create') }}" class="btn btn-primary mb-3">Thêm người dùng</a>
  <table class="table table-bordered table-striped">
    <thead><tr>
      <th>Mã</th><th>Họ tên</th><th>Tên đăng nhập</th><th>Loại</th><th>SDT</th><th>Email</th><th>Trạng thái</th><th>Hành động</th>
    </tr></thead>
    <tbody>
      @forelse($nguoidungs as $u)
        <tr>
          <td>{{ $u->MaNguoiDung }}</td>
          <td>{{ $u->HoTen }}</td>
          <td>{{ $u->TenDangNhap }}</td>
          <td>{{ $u->role_label ?? $u->LoaiNguoiDung }}</td>
          <td>{{ $u->SDT }}</td>
          <td>{{ $u->Email }}</td>
          <td>{{ $u->TrangThai ? 'Hoạt động' : 'Khóa' }}</td>
          <td>
            <a class="btn btn-sm btn-warning" href="{{ route('nguoidung.edit',$u->MaNguoiDung) }}">Sửa</a>
            <a class="btn btn-sm btn-danger" href="{{ route('nguoidung.destroy',$u->MaNguoiDung) }}">Xóa</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center">Chưa có dữ liệu</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection