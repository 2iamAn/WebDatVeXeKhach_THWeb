@extends('layouts.app')
@section('title','Chi tiết vé')
@section('heading','Chi tiết vé')
@section('content')
  <div class="mb-3">
    <div><b>Mã vé:</b> {{ $ve->MaVe }}</div>
    <div><b>Chuyến:</b> {{ $ve->MaChuyenXe }}</div>
    <div><b>Khách:</b> {{ optional($ve->nguoiDung)->HoTen }}</div>
    <div><b>Ghế:</b> {{ optional($ve->ghe)->SoGhe ?? '--' }}</div>
    <div><b>Giá tại thời điểm đặt:</b> {{ number_format($ve->GiaTaiThoiDiemDat) }}</div>
    <div><b>Trạng thái:</b> {{ $ve->TrangThai }}</div>
    <div><b>Ngày đặt:</b> {{ optional($ve->NgayDat)->format('d/m/Y H:i') }}</div>
  </div>
  <h5>Thanh toán</h5>
  @if($ve->thanhToan)
    <ul class="mb-0">
      <li><b>Số tiền:</b> {{ number_format($ve->thanhToan->SoTien) }}</li>
      <li><b>Phương thức:</b> {{ $ve->thanhToan->PhuongThuc }}</li>
      <li><b>Trạng thái:</b> {{ $ve->thanhToan->TrangThai }}</li>
      <li><b>Ngày TT:</b> {{ optional($ve->thanhToan->NgayThanhToan)->format('d/m/Y H:i') }}</li>
    </ul>
  @else
    <div>Chưa có thanh toán.</div>
  @endif
@endsection