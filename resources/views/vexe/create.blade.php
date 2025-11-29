@extends('layouts.app')
@section('title','Đặt vé')
@section('heading','Đặt vé')
@section('content')
<form method="post" action="{{ route('vexe.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Chuyến xe</label>
    <select name="MaChuyenXe" class="form-select" required>
      @foreach($chuyens as $chuyen)
        <option value="{{ $chuyen->MaChuyenXe }}">
          #{{ $chuyen->MaChuyenXe }} - {{ optional($chuyen->GioKhoiHanh)->format('d/m H:i') ?? '--' }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Khách hàng</label>
    <select name="MaNguoiDung" class="form-select" required>
      @foreach($users as $user)
        <option value="{{ $user->MaNguoiDung }}">{{ $user->HoTen }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Ghế</label>
    <select name="MaGhe" class="form-select" required>
      @forelse($gheTrongs as $ghe)
        <option value="{{ $ghe->MaGhe }}">
          Chuyến #{{ $ghe->MaChuyenXe }} - Ghế {{ $ghe->SoGhe }}
        </option>
      @empty
        <option value="" disabled>Không còn ghế trống</option>
      @endforelse
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Giá tại thời điểm đặt</label><input type="number" name="GiaTaiThoiDiemDat" class="form-control" min="0" step="500"></div>
  <div class="col-md-4">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="Chưa thanh toán">Chưa thanh toán</option>
      <option value="Đã thanh toán">Đã thanh toán</option>
      <option value="Đã hủy">Đã hủy</option>
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Ngày đặt</label><input type="datetime-local" name="NgayDat" class="form-control"></div>
  <div class="col-12">
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('vexe.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection