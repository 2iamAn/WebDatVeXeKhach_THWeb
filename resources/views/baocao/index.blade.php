@extends('layouts.app')
@section('title','Báo cáo')
@section('heading','Báo cáo')
@section('content')
  <form method="get" class="row g-2 mb-3">
    <div class="col-md-2"><input class="form-control" name="MaNhaXe" value="{{ request('MaNhaXe') }}" placeholder="MaNhaXe"></div>
    <div class="col-md-2"><input class="form-control" type="date" name="from" value="{{ request('from') }}" placeholder="from"></div>
    <div class="col-md-2"><input class="form-control" type="date" name="to" value="{{ request('to') }}" placeholder="to"></div>
    <div class="col-md-2"><input class="form-control" type="date" name="date" value="{{ request('date') }}" placeholder="date"></div>
    <div class="col-md-2"><button class="btn btn-outline-primary">Lọc</button></div>
  </form>

  <div class="card mb-3">
    <div class="card-body">
      <form method="post" action="{{ route('baocao.ketso.ngay') }}" class="row g-2">
        @csrf
        <div class="col-md-2"><input name="MaNhaXe" class="form-control" placeholder="MaNhaXe" required></div>
        <div class="col-md-2"><input type="date" name="date" class="form-control" required></div>
        <div class="col-md-4"><input name="GhiChu" class="form-control" placeholder="Ghi chú"></div>
        <div class="col-md-2"><button class="btn btn-success">Kết sổ ngày</button></div>
      </form>
      <hr>
      <form method="post" action="{{ route('baocao.ketso.thang') }}" class="row g-2">
        @csrf
        <div class="col-md-2"><input name="MaNhaXe" class="form-control" placeholder="MaNhaXe" required></div>
        <div class="col-md-2"><input type="number" name="year" class="form-control" placeholder="Năm" required></div>
        <div class="col-md-2"><input type="number" name="month" class="form-control" placeholder="Tháng" required></div>
        <div class="col-md-4"><input name="GhiChu" class="form-control" placeholder="Ghi chú"></div>
        <div class="col-md-2"><button class="btn btn-primary">Kết sổ tháng</button></div>
      </form>
    </div>
  </div>

  <table class="table table-bordered table-striped">
    <thead><tr><th>Mã BC</th><th>Nhà xe</th><th>Thời gian</th><th>Tổng vé</th><th>Doanh thu</th><th>Ghi chú</th></tr></thead>
    <tbody>
      @forelse($baocao ?? [] as $bc)
        <tr>
          <td>{{ $bc->MaBaoCao }}</td>
          <td>{{ optional($bc->nhaXe)->TenNhaXe }}</td>
          <td>{{ $bc->ThoiGianBaoCao }}</td>
          <td>{{ $bc->TongSoVe }}</td>
          <td>{{ number_format($bc->TongDoanhThu) }}</td>
          <td>{{ $bc->GhiChu }}</td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center">Chưa có dữ liệu</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection