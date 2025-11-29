@extends('layouts.app')

@section('title', 'Yêu cầu hợp tác')

@section('content')
<div class="container" style="margin-top: 40px;">
    <h2 class="mb-4">Danh sách yêu cầu hợp tác</h2>

    @if(session('success'))
        <div style="padding: 12px; background: #d1f7d6; border-left: 4px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên nhà xe</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
                <th>Xem</th>
                <th>Duyệt</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $item)
            <tr>
                <td>{{ $item->TenNhaXe }}</td>
                <td>{{ $item->Email }}</td>
                <td>{{ $item->SDT }}</td>
                <td>
                    @if($item->TrangThai == 'ChoDuyet')
                        <span style="color: orange;">Chờ duyệt</span>
                    @else
                        <span style="color: green;">Đã duyệt</span>
                    @endif
                </td>
                <td>
                    <a href="{{ asset('storage/'.$item->GiayPhep) }}" target="_blank">Xem PDF</a>
                </td>
                <td>
                    @if($item->TrangThai == 'ChoDuyet')
                    <a href="{{ route('partner.request.approve', $item->id) }}" 
                       class="btn btn-success btn-sm">
                        Duyệt
                    </a>
                    @else
                    ✔
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
