@extends('layouts.app')
@section('title','Chuyến xe')
@section('heading','Chuyến xe')

@section('content')
  <div class="page-section">
    <div class="container">
      <div class="page-card">
        <div class="page-card__header">
          <div>
            <p class="eyebrow">Quản lý</p>
            <h2 class="mb-2">Danh sách chuyến xe</h2>
            <p class="text-muted mb-0">Quản lý tất cả các chuyến xe trong hệ thống.</p>
          </div>
          <a href="{{ route('chuyenxe.create') }}" class="btn btn-gradient d-flex align-items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Thêm chuyến mới
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-modern align-middle mb-0">
            <thead>
              <tr>
                <th>Mã</th>
                <th>Nhà xe</th>
                <th>Tuyến đường</th>
                <th>Khởi hành</th>
                <th>Đến</th>
                <th>Giá vé</th>
                <th>Trạng thái</th>
                <th class="text-end">Hành động</th>
              </tr>
            </thead>

            <tbody>
              @forelse($chuyens as $cx)
                <tr>
                  <td class="fw-semibold">#{{ $cx->MaChuyenXe }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-2" 
                           style="width: 32px; height: 32px; font-size: 14px;">
                        <i class="fas fa-bus"></i>
                      </div>
                      <span class="fw-semibold">{{ optional($cx->nhaXe)->TenNhaXe ?? 'N/A' }}</span>
                    </div>
                  </td>
                  <td>
                    <span class="text-primary fw-semibold">{{ optional($cx->tuyenDuong)->DiemDi ?? '--' }}</span>
                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                    <span class="text-primary fw-semibold">{{ optional($cx->tuyenDuong)->DiemDen ?? '--' }}</span>
                  </td>
                  <td>
                    <i class="fas fa-calendar-alt text-muted me-1"></i>
                    {{ optional($cx->GioKhoiHanh)->format('d/m/Y H:i') ?? '--' }}
                  </td>
                  <td>
                    <i class="fas fa-clock text-muted me-1"></i>
                    {{ optional($cx->GioDen)->format('d/m/Y H:i') ?? '--' }}
                  </td>
                  <td class="fw-semibold text-success">{{ number_format($cx->GiaVe, 0, ',', '.') }} đ</td>
                  <td>
                    <span class="badge-status {{ $cx->TrangThai === 'Còn chỗ' ? 'success' : 'warning' }}">
                      {{ $cx->TrangThai }}
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons d-flex gap-2 justify-content-end">
                      <a class="btn btn-sm btn-outline-primary" href="{{ route('chuyenxe.show',$cx->MaChuyenXe) }}">
                        <i class="fa-regular fa-eye me-1"></i> Xem
                      </a>
                      <a class="btn btn-sm btn-outline-warning" href="{{ route('chuyenxe.edit',$cx->MaChuyenXe) }}">
                        <i class="fa-regular fa-pen-to-square me-1"></i> Sửa
                      </a>
                      <a class="btn btn-sm btn-outline-danger" href="{{ route('chuyenxe.destroy',$cx->MaChuyenXe) }}"
                         onclick="return confirm('Bạn có chắc chắn muốn xóa chuyến xe này?')">
                        <i class="fa-regular fa-trash-can me-1"></i> Xóa
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-muted py-5">
                    <i class="fas fa-route fa-3x mb-3" style="opacity: 0.3;"></i>
                    <p class="mb-0">Chưa có chuyến xe nào</p>
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