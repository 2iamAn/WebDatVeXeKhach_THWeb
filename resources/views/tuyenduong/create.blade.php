@extends('layouts.app')
@section('title','Thêm tuyến đường')
@section('heading','Thêm tuyến đường')
@section('content')
<form method="post" action="{{ route('tuyenduong.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4"><label class="form-label">Điểm đi</label><input name="DiemDi" class="form-control" required></div>
  <div class="col-md-4"><label class="form-label">Điểm đến</label><input name="DiemDen" class="form-control" required></div>
  <div class="col-md-4"><label class="form-label">Khoảng cách (km)</label><input type="number" name="KhoangCach" class="form-control" min="1" required></div>
  <div class="col-12">
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('tuyenduong.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection