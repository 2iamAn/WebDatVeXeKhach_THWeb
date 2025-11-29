@extends('layouts.app')
@section('title','Sửa nhà xe')
@section('heading','Sửa nhà xe')
@section('content')
<form method="post" action="{{ route('nhaxe.update',$nhaxe->MaNhaXe) }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Người phụ trách</label>
    <select name="MaNguoiDung" class="form-select" required>
      @foreach($users as $user)
        <option value="{{ $user->MaNguoiDung }}" @selected($nhaxe->MaNguoiDung == $user->MaNguoiDung)>{{ $user->HoTen }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Tên nhà xe</label><input name="TenNhaXe" class="form-control" value="{{ $nhaxe->TenNhaXe }}" required></div>
  <div class="col-12"><label class="form-label">Mô tả</label><textarea name="MoTa" class="form-control">{{ $nhaxe->MoTa }}</textarea></div>
  <div class="col-12">
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('nhaxe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection