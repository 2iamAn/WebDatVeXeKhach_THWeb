@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h3>Chi tiết yêu cầu hợp tác</h3>

    <div class="card p-3">
        <p><strong>Tên nhà xe:</strong> {{ $data->TenNhaXe }}</p>
        <p><strong>Người đại diện:</strong> {{ $data->NguoiDaiDien }}</p>
        <p><strong>Email:</strong> {{ $data->Email }}</p>
        <p><strong>SDT:</strong> {{ $data->SDT }}</p>
        <p><strong>Địa chỉ:</strong> {{ $data->DiaChi }}</p>
        <p><strong>Mô tả:</strong> {{ $data->MoTa }}</p>
        <p><strong>Giấy phép:</strong></p>
        <img src="{{ asset('storage/'.$data->GiayPhep) }}" style="max-width:300px">

        <hr>

        @if($data->TrangThai == 'ChoDuyet')
            <form action="{{ route('admin.partner.approve', $data->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-success">✔ Duyệt</button>
            </form>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">✖ Từ chối</button>
        @endif
    </div>
</div>

<!-- Modal nhập lý do từ chối -->
<div class="modal fade" id="rejectModal">
  <div class="modal-dialog">
    <form action="{{ route('admin.partner.reject', $data->id) }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Từ chối yêu cầu</h5>
            </div>
            <div class="modal-body">
                <textarea name="LyDoTuChoi" class="form-control" required placeholder="Nhập lý do từ chối"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button class="btn btn-danger">Xác nhận</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection
