@extends('layouts.app')
@section('title','Chi tiết tuyến đường')
@section('heading','Chi tiết tuyến đường')
@section('content')
  <div class="mb-3">
    <div><b>Mã tuyến:</b> {{ $tuyen->MaTuyen }}</div>
    <div><b>Tuyến:</b> {{ $tuyen->DiemDi }} → {{ $tuyen->DiemDen }}</div>
    <div><b>Khoảng cách:</b> {{ $tuyen->KhoangCach }} km</div>
  </div>

  <h5>Chuyến xe thuộc tuyến này</h5>
  <table class="table table-sm table-bordered">
    <thead><tr><th>Mã chuyến</th><th>Nhà xe</th><th>Khởi hành</th><th>Giá vé</th><th></th></tr></thead>
    <tbody>
      @forelse($tuyen->chuyenXe as $cx)
        <tr>
          <td>{{ $cx->MaChuyenXe }}</td>
          <td>{{ optional($cx->nhaXe)->TenNhaXe }}</td>
          <td>{{ optional($cx->GioKhoiHanh)->format('d/m/Y H:i') ?? '--' }}</td>
          <td>{{ number_format($cx->GiaVe) }}</td>
          <td><a class="btn btn-sm btn-info" href="{{ route('chuyenxe.show',$cx->MaChuyenXe) }}">Xem</a></td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center">Chưa có chuyến</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection