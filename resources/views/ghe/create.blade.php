@extends('layouts.app')
@section('title','Thêm ghế')
@section('heading','Thêm ghế')
@section('content')
<form method="post" action="{{ route('ghe.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Chuyến xe</label>
    <select name="MaChuyenXe" class="form-select" required>
      <option value="">-- Chọn chuyến --</option>
      @foreach($chuyens as $chuyen)
        <option value="{{ $chuyen->MaChuyenXe }}">
          #{{ $chuyen->MaChuyenXe }} - {{ optional($chuyen->GioKhoiHanh)->format('d/m H:i') ?? '--' }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">SoGhe</label><input name="SoGhe" class="form-control" placeholder="A01" required></div>
  <div class="col-md-4">
    <label class="form-label">TrangThai</label>
    <select name="TrangThai" class="form-select">
      <option value="Trống">Trống</option>
      <option value="Giữ chỗ">Giữ chỗ</option>
      <option value="Đã đặt">Đã đặt</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('ghe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection