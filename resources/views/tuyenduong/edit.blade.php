@extends('layouts.app')
@section('title','Sửa tuyến đường')
@section('heading','Sửa tuyến đường')
@section('content')
<form method="post" action="{{ route('tuyenduong.update',$tuyen->MaTuyen) }}" class="row g-3">
  @csrf
  <div class="col-md-4"><label class="form-label">Điểm đi</label><input name="DiemDi" class="form-control" value="{{ $tuyen->DiemDi }}" required></div>
  <div class="col-md-4"><label class="form-label">Điểm đến</label><input name="DiemDen" class="form-control" value="{{ $tuyen->DiemDen }}" required></div>
  <div class="col-md-4"><label class="form-label">Khoảng cách</label><input type="number" name="KhoangCach" class="form-control" value="{{ $tuyen->KhoangCach }}"></div>
  <div class="col-12">
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('tuyenduong.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection