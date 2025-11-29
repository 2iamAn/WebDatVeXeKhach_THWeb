@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Danh sách yêu cầu hợp tác</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Nhà Xe</th>
                <th>Email</th>
                <th>Ngày Gửi</th>
                <th>Trạng Thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->TenNhaXe }}</td>
                <td>{{ $r->Email }}</td>
                <td>{{ $r->created_at }}</td>
                <td>
                    @if($r->TrangThai == 'ChoDuyet')
                        <span class="badge bg-warning">Chờ duyệt</span>
                    @elseif($r->TrangThai == 'DaDuyet')
                        <span class="badge bg-success">Đã duyệt</span>
                    @else
                        <span class="badge bg-danger">Từ chối</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.partner.view', $r->id) }}" class="btn btn-primary btn-sm">Xem</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
