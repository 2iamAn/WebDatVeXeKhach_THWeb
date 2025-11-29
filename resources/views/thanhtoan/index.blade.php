@extends('layouts.app')
@section('title','Thanh toán')
@section('heading','Thanh toán')
@section('content')
  <a href="{{ route('thanhtoan.create') }}" class="btn btn-primary mb-3">Thêm thanh toán</a>
  <table class="table table-bordered table-striped">
    <thead><tr><th>Mã TT</th><th>Mã vé</th><th>Khách</th><th>Số tiền</th><th>PT</th><th>Trạng thái</th><th>Ngày TT</th><th>Hành động</th></tr></thead>
    <tbody>
      @forelse($pays ?? [] as $p)
        <tr>
          <td>{{ $p->MaThanhToan }}</td>
          <td>{{ $p->MaVe }}</td>
          <td>{{ optional(optional($p->veXe)->nguoiDung)->HoTen }}</td>
          <td>{{ number_format($p->SoTien) }}</td>
          <td>{{ $p->PhuongThuc }}</td>
          <td>{{ $p->TrangThai }}</td>
          <td>{{ optional($p->NgayThanhToan)->format('d/m/Y H:i') }}</td>
          <td>
            <a class="btn btn-sm btn-info" href="{{ route('thanhtoan.show',$p->MaThanhToan) }}">Xem</a>
            <a class="btn btn-sm btn-warning" href="{{ route('thanhtoan.edit',$p->MaThanhToan) }}">Sửa</a>
            <a class="btn btn-sm btn-danger" href="{{ route('thanhtoan.destroy',$p->MaThanhToan) }}">Xóa</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center">Chưa có dữ liệu</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection