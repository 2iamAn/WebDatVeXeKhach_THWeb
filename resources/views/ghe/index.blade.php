@extends('layouts.app')
@section('title','Ghế')
@section('heading','Ghế')
@section('content')
  <div class="page-section">
    <div class="container">
      <div class="page-card">
        <div class="page-card__header">
          <div>
            <p class="eyebrow">Quản lý</p>
            <h2 class="mb-2">Danh sách ghế</h2>
            <p class="text-muted mb-0">Quản lý ghế của các chuyến xe.</p>
          </div>
          <a href="{{ route('ghe.create') }}" class="btn btn-gradient d-flex align-items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Thêm ghế mới
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-modern align-middle mb-0">
            <thead>
              <tr>
                <th>Mã ghế</th>
                <th>Chuyến xe</th>
                <th>Số ghế</th>
                <th>Trạng thái</th>
                <th class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ghes ?? [] as $g)
                <tr>
                  <td class="fw-semibold">#{{ $g->MaGhe }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-route text-primary me-2"></i>
                      <span class="fw-semibold">Chuyến #{{ $g->MaChuyenXe }}</span>
                    </div>
                    @if(optional($g->chuyenXe)->tuyenDuong)
                      <small class="text-muted d-block ms-4">
                        {{ optional($g->chuyenXe->tuyenDuong)->DiemDi }} → {{ optional($g->chuyenXe->tuyenDuong)->DiemDen }}
                      </small>
                    @endif
                  </td>
                  <td>
                    <span class="badge-status info" style="font-size: 14px; padding: 6px 12px;">
                      <i class="fas fa-chair me-1"></i>{{ $g->SoGhe }}
                    </span>
                  </td>
                  <td>
                    @php
                      $statusClass = $g->TrangThai === 'Trống' ? 'success' : ($g->TrangThai === 'Đã đặt' ? 'danger' : 'warning');
                    @endphp
                    <span class="badge-status {{ $statusClass }}">
                      {{ $g->TrangThai }}
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons d-flex gap-2 justify-content-end">
                      <a class="btn btn-sm btn-outline-warning" href="{{ route('ghe.edit',$g->MaGhe) }}">
                        <i class="fa-regular fa-pen-to-square me-1"></i> Sửa
                      </a>
                      <a class="btn btn-sm btn-outline-danger" href="{{ route('ghe.destroy',$g->MaGhe) }}"
                         onclick="return confirm('Bạn có chắc chắn muốn xóa ghế này?')">
                        <i class="fa-regular fa-trash-can me-1"></i> Xóa
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-5">
                    <i class="fas fa-chair fa-3x mb-3" style="opacity: 0.3;"></i>
                    <p class="mb-0">Chưa có ghế nào</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection