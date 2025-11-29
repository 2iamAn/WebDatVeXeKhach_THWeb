@extends('layouts.app')
@section('title','Chi tiết thanh toán')
@section('heading','Chi tiết thanh toán')
@section('content')
  <ul class="mb-0">
    <li><b>Mã TT:</b> {{ $pay->MaThanhToan }}</li>
    <li><b>Mã vé:</b> {{ $pay->MaVe }}</li>
    <li><b>Khách:</b> {{ optional(optional($pay->veXe)->nguoiDung)->HoTen }}</li>
    <li><b>Số tiền:</b> {{ number_format($pay->SoTien) }}</li>
    <li><b>Phương thức:</b> {{ $pay->PhuongThuc }}</li>
    <li><b>Trạng thái:</b> {{ $pay->TrangThai }}</li>
    <li><b>Ngày TT:</b> {{ optional($pay->NgayThanhToan)->format('d/m/Y H:i') }}</li>
  </ul>
@endsection