@extends('layouts.app')
@section('title','Sửa thanh toán')
@section('heading','Sửa thanh toán')
@section('content')
<form method="post" action="{{ route('thanhtoan.update',$pay->MaThanhToan) }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Vé</label>
    <select name="MaVe" class="form-select" required>
      @forelse($ves as $ve)
        <option value="{{ $ve->MaVe }}" @selected($pay->MaVe == $ve->MaVe)>
          Vé #{{ $ve->MaVe }} - Ghế {{ optional($ve->ghe)->SoGhe }}
        </option>
      @empty
        <option value="" disabled>Chưa có vé</option>
      @endforelse
    </select>
  </div>
  <div class="col-md-3"><label class="form-label">Số tiền</label><input type="number" name="SoTien" class="form-control" value="{{ $pay->SoTien }}" required min="0" step="1000"></div>
  <div class="col-md-3"><label class="form-label">Phương thức</label><input name="PhuongThuc" class="form-control" value="{{ $pay->PhuongThuc }}"></div>
  <div class="col-md-3">
    <label class="form-label">Trạng thái</label>
    <select name="TrangThai" class="form-select">
      <option value="Success" @selected($pay->TrangThai === 'Success')>Thành công</option>
      <option value="Pending" @selected($pay->TrangThai === 'Pending')>Đang xử lý</option>
      <option value="Failed" @selected($pay->TrangThai === 'Failed')>Thất bại</option>
    </select>
  </div>
  <div class="col-md-4"><label class="form-label">Ngày thanh toán</label>
    <input type="datetime-local" name="NgayThanhToan" class="form-control"
      value="{{ $pay->NgayThanhToan ? \Carbon\Carbon::parse($pay->NgayThanhToan)->format('Y-m-d\TH:i') : '' }}">
  </div>
  <div class="col-12">
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('thanhtoan.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</form>
@endsection