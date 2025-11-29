@extends('layouts.app')
@section('title','Sửa vé')
@section('heading','Sửa vé')
@section('content')
<form method="post" action="{{ route('vexe.update',$ve->MaVe) }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Chuyến xe</label>
    <select name="MaChuyenXe" class="form-select" required>
      @foreach($chuyens as $chuyen)
        <option value="{{ $chuyen->MaChuyenXe }}" @selected($ve->MaChuyenXe == $chuyen->MaChuyenXe)>
          #{{ $chuyen->MaChuyenXe }} - {{ optional($chuyen->GioKhoiHanh)->format('d/m H:i') ?? '--' }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Khách hàng</label>
    <select name="MaNguoiDung" class="form-select" required>
      @foreach($users as $user)
        <option value="{{ $user->MaNguoiDung }}" @selected($ve->MaNguoiDung == $user->MaNguoiDung)>{{ $user->HoTen }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Ghế</label>
    <select name="MaGhe" class="form-select" required>
      @forelse($gheOptions as $ghe)
        <option value="{{ $ghe->MaGhe }}" @selected($ve->MaGhe == $ghe->MaGhe)>Ghế {{ $ghe->SoGhe }}</option>
      @empty
        <option value="" disabled>Chưa có ghế cho chuyến này</option>
      @endforelse
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Giá tại thời điểm đặt</label><input type="number" name="GiaTaiThoiDiemDat" class="form-control" value="{{ $ve->GiaTaiThoiDiemDat }}" min="0" step="500"></div>
  <div class="col-md-4">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="Chưa thanh toán" @selected($ve->TrangThai === 'Chưa thanh toán')>Chưa thanh toán</option>
      <option value="Đã thanh toán" @selected($ve->TrangThai === 'Đã thanh toán')>Đã thanh toán</option>
      <option value="Đã hủy" @selected($ve->TrangThai === 'Đã hủy')>Đã hủy</option>
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Ngày đặt</label>
    <input type="datetime-local" name="NgayDat" class="form-control"
      value="{{ $ve->NgayDat ? \Carbon\Carbon::parse($ve->NgayDat)->format('Y-m-d\TH:i') : '' }}">
  </div>
  <div class="col-12">
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('vexe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection