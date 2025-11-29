@extends('layouts.app')
@section('title','Nhà xe')
@section('heading','Nhà xe')
@section('content')
  <div class="page-section">
    <div class="container">
      <div class="page-card">
        <div class="page-card__header">
          <div>
            <p class="eyebrow">Đối tác</p>
            <h2 class="mb-2">Danh sách nhà xe</h2>
            <p class="text-muted mb-0">Xem thông tin các nhà xe đối tác trong hệ thống.</p>
          </div>
          @if(session('role') == 'admin')
            <a href="{{ route('nhaxe.create') }}" class="btn btn-gradient d-flex align-items-center gap-2">
              <i class="fa-solid fa-building"></i>
              Thêm nhà xe
            </a>
          @endif
        </div>

        <div class="table-responsive">
          <table class="table table-modern align-middle mb-0">
            <thead>
              <tr>
                <th>Mã</th>
                <th>Tên nhà xe</th>
                <th>Người phụ trách</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Mô tả</th>
                @if(session('role') == 'admin')
                  <th class="text-end">Hành động</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @forelse($nhaxes as $x)
                <tr>
                  <td class="fw-semibold">#{{ $x->MaNhaXe }}</td>
                  <td>
                    <a href="{{ route('nhaxe.show',$x->MaNhaXe) }}" class="fw-semibold text-decoration-none text-primary">
                      {{ $x->TenNhaXe }}
                    </a>
                  </td>
                  <td>
                    <span class="badge-status info">{{ optional($x->nguoiDung)->HoTen ?? 'Chưa cập nhật' }}</span>
                  </td>
                  <td>{{ optional($x->nguoiDung)->Email ?? '—' }}</td>
                  <td>{{ optional($x->nguoiDung)->SDT ?? '—' }}</td>
                  <td>{{ $x->MoTa ? \Illuminate\Support\Str::limit($x->MoTa, 70) : '—' }}</td>
                  @if(session('role') == 'admin')
                    <td>
                      <div class="action-buttons d-flex gap-2 justify-content-end">
                        <a class="btn btn-sm btn-outline-warning" href="{{ route('nhaxe.edit',$x->MaNhaXe) }}">
                          <i class="fa-regular fa-pen-to-square me-1"></i> Sửa
                        </a>
                        <a class="btn btn-sm btn-outline-danger" href="{{ route('nhaxe.destroy',$x->MaNhaXe) }}">
                          <i class="fa-regular fa-trash-can me-1"></i> Xóa
                        </a>
                      </div>
                    </td>
                  @endif
                </tr>
              @empty
                <tr>
                  <td colspan="{{ session('role') == 'admin' ? '7' : '6' }}" class="text-center text-muted py-4">Chưa có dữ liệu nhà xe.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection