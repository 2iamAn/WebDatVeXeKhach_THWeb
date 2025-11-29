@extends('layouts.app')
@section('title','Sửa chuyến xe')
@section('heading','Sửa chuyến xe')
@section('content')
<form method="post" action="{{ route('chuyenxe.update',$chuyen->MaChuyenXe) }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Nhà xe</label>
    <select name="MaNhaXe" class="form-select" required>
      @foreach($nhaxes as $nhaxe)
        <option value="{{ $nhaxe->MaNhaXe }}" @selected($nhaxe->MaNhaXe == $chuyen->MaNhaXe)>{{ $nhaxe->TenNhaXe }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Tuyến đường</label>
    <select name="MaTuyen" class="form-select" required>
      @foreach($tuyens as $tuyen)
        <option value="{{ $tuyen->MaTuyen }}" @selected($tuyen->MaTuyen == $chuyen->MaTuyen)>{{ $tuyen->DiemDi }} → {{ $tuyen->DiemDen }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Giờ khởi hành</label>
    <input type="datetime-local" name="GioKhoiHanh" class="form-control"
      value="{{ optional($chuyen->GioKhoiHanh)->format('Y-m-d\TH:i') }}" required>
  </div>
  <div class="col-md-4"><label class="form-label">Giờ đến</label>
    <input type="datetime-local" name="GioDen" class="form-control"
      value="{{ optional($chuyen->GioDen)->format('Y-m-d\TH:i') }}">
  </div>
  <div class="col-md-4"><label class="form-label">Giá vé</label><input type="number" name="GiaVe" class="form-control" value="{{ $chuyen->GiaVe }}" min="0" step="1000" required></div>
  <div class="col-md-4">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="Còn chỗ" @selected($chuyen->TrangThai === 'Còn chỗ')>Còn chỗ</option>
      <option value="Hết chỗ" @selected($chuyen->TrangThai === 'Hết chỗ')>Hết chỗ</option>
      <option value="Tạm dừng" @selected($chuyen->TrangThai === 'Tạm dừng')>Tạm dừng</option>
      <option value="DaDuyet" @selected($chuyen->TrangThai === 'DaDuyet')>Đã duyệt</option>
      <option value="ChoDuyet" @selected($chuyen->TrangThai === 'ChoDuyet')>Chờ duyệt</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('chuyenxe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection