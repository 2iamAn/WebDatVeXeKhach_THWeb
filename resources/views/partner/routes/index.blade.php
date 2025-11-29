@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-route"></i>
        Quản lý tuyến đường
    </h2>
    <p class="text-muted mb-0 mt-2">Danh sách các tuyến đường của nhà xe</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Danh sách tuyến đường
            </h5>
            <a href="{{ route('partner.routes.create') }}" class="btn btn-primary" style="border-radius: 10px;">
                <i class="fas fa-plus-circle me-2"></i>
                Thêm tuyến mới
            </a>
        </div>

        @if($routes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-hashtag me-2"></i>Mã tuyến
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-map-marked-alt me-2"></i>Điểm đi
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-map-marked-alt me-2"></i>Điểm đến
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-route me-2"></i>Khoảng cách
                            </th>
                            <th style="border: none; padding: 15px; text-align: center;">
                                <i class="fas fa-info-circle me-2"></i>Trạng thái
                            </th>
                            <th style="border: none; padding: 15px; text-align: center;">
                                <i class="fas fa-cog me-2"></i>Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $route)
                        <tr style="transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 15px;">
                                <strong class="text-primary">#{{ $route->MaTuyen }}</strong>
                            </td>
                            <td style="padding: 15px;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <span><strong>{{ $route->DiemDi }}</strong></span>
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    <span><strong>{{ $route->DiemDen }}</strong></span>
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                <span class="badge bg-info" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="fas fa-route me-1"></i>
                                    {{ number_format($route->KhoangCach) }} km
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if($route->TrangThai == 'DaDuyet')
                                    <span class="badge bg-success" style="font-size: 14px; padding: 8px 12px;">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Đã phê duyệt
                                    </span>
                                @elseif($route->TrangThai == 'ChoDuyet')
                                    <span class="badge bg-warning text-dark" style="font-size: 14px; padding: 8px 12px;">
                                        <i class="fas fa-clock me-1"></i>
                                        Chờ phê duyệt
                                    </span>
                                @elseif($route->TrangThai == 'TuChoi')
                                    <span class="badge bg-danger" style="font-size: 14px; padding: 8px 12px;">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Đã từ chối
                                    </span>
                                    @if($route->LyDoTuChoi)
                                        <br>
                                        <small class="text-muted mt-1 d-block" style="font-size: 11px; margin-top: 5px;">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ $route->LyDoTuChoi }}
                                        </small>
                                    @endif
                                @else
                                    <span class="badge bg-secondary" style="font-size: 14px; padding: 8px 12px;">
                                        <i class="fas fa-question-circle me-1"></i>
                                        Chưa xác định
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    <a href="{{ route('partner.routes.edit', $route->MaTuyen) }}" 
                                       class="btn btn-sm btn-primary"
                                       title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('partner.routes.delete', $route->MaTuyen) }}"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa tuyến đường này?')"
                                       title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-route fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="text-muted">Chưa có tuyến đường nào</h5>
                <p class="text-muted">Hãy thêm tuyến đường đầu tiên của bạn!</p>
                <a href="{{ route('partner.routes.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle me-2"></i>
                    Thêm tuyến mới
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

