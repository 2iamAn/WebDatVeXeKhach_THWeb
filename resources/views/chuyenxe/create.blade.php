@extends('layouts.app')
@section('title','Thêm chuyến xe')
@section('heading','Thêm chuyến xe')
@section('content')
<form method="post" action="{{ route('chuyenxe.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Nhà xe</label>
    <select name="MaNhaXe" class="form-select" required>
      <option value="">-- Chọn nhà xe --</option>
      @foreach($nhaxes as $nhaxe)
        <option value="{{ $nhaxe->MaNhaXe }}">{{ $nhaxe->TenNhaXe }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Tuyến đường</label>
    <select name="MaTuyen" class="form-select" required>
      <option value="">-- Chọn tuyến --</option>
      @foreach($tuyens as $tuyen)
        <option value="{{ $tuyen->MaTuyen }}">{{ $tuyen->DiemDi }} → {{ $tuyen->DiemDen }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Giờ khởi hành</label><input type="datetime-local" name="GioKhoiHanh" class="form-control" required></div>
  <div class="col-md-4"><label class="form-label">Giờ đến</label><input type="datetime-local" name="GioDen" class="form-control"></div>
  <div class="col-md-4"><label class="form-label">Giá vé</label><input type="number" name="GiaVe" class="form-control" required step="1000" min="0"></div>
  <div class="col-md-4">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="Còn chỗ">Còn chỗ</option>
      <option value="Hết chỗ">Hết chỗ</option>
      <option value="Tạm dừng">Tạm dừng</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('chuyenxe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection