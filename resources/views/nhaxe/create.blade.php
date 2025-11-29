@extends('layouts.app')
@section('title','Thêm nhà xe')
@section('heading','Thêm nhà xe')
@section('content')
<form method="post" action="{{ route('nhaxe.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Người dùng (MaNguoiDung)</label>
    <select name="MaNguoiDung" class="form-select" required>
      <option value="">-- Chọn người phụ trách --</option>
      @foreach($users as $user)
        <option value="{{ $user->MaNguoiDung }}">{{ $user->HoTen }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Tên nhà xe</label><input name="TenNhaXe" class="form-control" required></div>
  <div class="col-12"><label class="form-label">Mô tả</label><textarea name="MoTa" class="form-control"></textarea></div>
  <div class="col-12">
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('nhaxe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection