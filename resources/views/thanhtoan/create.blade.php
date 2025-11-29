@extends('layouts.app')
@section('title','Thêm thanh toán')
@section('heading','Thêm thanh toán')
@section('content')
<form method="post" action="{{ route('thanhtoan.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Vé</label>
    <select name="MaVe" class="form-select" required>
      @forelse($ves as $ve)
        <option value="{{ $ve->MaVe }}">
          Vé #{{ $ve->MaVe }} - Chuyến #{{ $ve->MaChuyenXe }} - Ghế {{ optional($ve->ghe)->SoGhe }}
        </option>
      @empty
        <option value="" disabled>Chưa có vé để thanh toán</option>
      @endforelse
    </select>
  </div>
  <div class="col-md-3"><label class="form-label">Số tiền</label><input type="number" name="SoTien" class="form-control" required min="0" step="1000"></div>
  <div class="col-md-3"><label class="form-label">Phương thức</label><input name="PhuongThuc" class="form-control" placeholder="Momo|VNPAY|..."></div>
  <div class="col-md-3">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="Success">Thành công</option>
      <option value="Pending">Đang xử lý</option>
      <option value="Failed">Thất bại</option>
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Ngày thanh toán</label><input type="datetime-local" name="NgayThanhToan" class="form-control"></div>
  <div class="col-12">
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('thanhtoan.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection