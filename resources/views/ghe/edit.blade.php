@extends('layouts.app')
@section('title','Sửa ghế')
@section('heading','Sửa ghế')
@section('content')
<form method="post" action="{{ route('ghe.update',$ghe->MaGhe) }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Chuyến xe</label>
    <select name="MaChuyenXe" class="form-select" required>
      @foreach($chuyens as $chuyen)
        <option value="{{ $chuyen->MaChuyenXe }}" @selected($ghe->MaChuyenXe == $chuyen->MaChuyenXe)>
          #{{ $chuyen->MaChuyenXe }} - {{ optional($chuyen->GioKhoiHanh)->format('d/m H:i') ?? '--' }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">SoGhe</label><input name="SoGhe" class="form-control" value="{{ $ghe->SoGhe }}" required></div>
  <div class="col-md-4">
    <label class="form-label">TrangThai</label>
    <select name="TrangThai" class="form-select">
      @foreach(['Trống','Giữ chỗ','Đã đặt'] as $st)
        <option value="{{ $st }}" @selected($ghe->TrangThai===$st)>{{ $st }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('ghe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection