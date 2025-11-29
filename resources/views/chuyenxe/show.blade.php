@extends('layouts.app')
@section('title','Chi tiết chuyến xe')
@section('heading','Chi tiết chuyến xe')
@section('content')
  <div class="mb-3">
    <div><b>Nhà xe:</b> {{ optional($chuyen->nhaXe)->TenNhaXe }}</div>
    <div><b>Tuyến:</b> {{ optional($chuyen->tuyenDuong)->DiemDi }} → {{ optional($chuyen->tuyenDuong)->DiemDen }}</div>
    <div><b>Khởi hành:</b> {{ optional($chuyen->GioKhoiHanh)->format('d/m/Y H:i') ?? '--' }}</div>
    <div><b>Đến nơi:</b> {{ optional($chuyen->GioDen)->format('d/m/Y H:i') ?? '--' }}</div>
    <div><b>Giá vé:</b> {{ number_format($chuyen->GiaVe) }}</div>
    <div><b>Trạng thái:</b> {{ $chuyen->TrangThai }}</div>
  </div>

  <h5>Vé đã đặt</h5>
  <table class="table table-sm table-bordered">
    <thead><tr><th>Mã vé</th><th>Khách</th><th>Ghế</th><th>Trạng thái</th></tr></thead>
    <tbody>
      @forelse($chuyen->veXe as $ve)
        <tr>
          <td>{{ $ve->MaVe }}</td>
          <td>{{ optional($ve->nguoiDung)->HoTen }}</td>
          <td>{{ optional($ve->ghe)->SoGhe }}</td>
          <td>{{ $ve->TrangThai }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center">Chưa có vé</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection