@extends('layouts.admin_layout')

@section('title', 'Quản lý tuyến đường')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-route me-2"></i> Quản lý tuyến đường</h2>
        <p>Xem tất cả tuyến đường, duyệt/từ chối tuyến đường mới từ nhà xe</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter -->
    <div class="card-modern mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.routes.pending') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả</option>
                        <option value="ChoDuyet" {{ request('status') == 'ChoDuyet' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="DaDuyet" {{ request('status') == 'DaDuyet' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="TuChoi" {{ request('status') == 'TuChoi' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nhà xe</label>
                    <select name="nha_xe" class="form-select">
                        <option value="all" {{ request('nha_xe') == 'all' ? 'selected' : '' }}>Tất cả</option>
                        @foreach($allNhaXe as $nhaXe)
                            <option value="{{ $nhaXe->MaNhaXe }}" {{ request('nha_xe') == $nhaXe->MaNhaXe ? 'selected' : '' }}>
                                {{ $nhaXe->TenNhaXe }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo điểm đi, điểm đến, nhà xe..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Phần tuyến đường chờ duyệt -->
    @if($pendingRoutes->count() > 0 && (!request('status') || request('status') == 'all' || request('status') == 'ChoDuyet'))
    <div class="card-modern mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-clock me-2"></i>
                Tuyến đường chờ duyệt ({{ $pendingRoutes->count() }})
            </h5>
        </div>
        <div class="card-body">
            @foreach($pendingRoutes as $route)
                <div class="card mb-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">Tuyến đường #{{ $route->MaTuyen }}</h5>
                                <span class="badge bg-warning badge-custom">
                                    <i class="fas fa-clock me-1"></i> Chờ duyệt
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('admin.routes.approve', $route->MaTuyen) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-action" 
                                            onclick="return confirm('Bạn có chắc chắn muốn phê duyệt tuyến đường này?')">
                                        <i class="fas fa-check me-2"></i> Phê duyệt
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal{{ $route->MaTuyen }}">
                                    <i class="fas fa-times me-2"></i> Từ chối
                                </button>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-bus me-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted d-block">Nhà xe</small>
                                        <strong>{{ $route->TenNhaXe ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                    <div>
                                        <small class="text-muted d-block">Điểm đi</small>
                                        <strong>{{ $route->DiemDi ?? '--' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt me-2 text-success"></i>
                                    <div>
                                        <small class="text-muted d-block">Điểm đến</small>
                                        <strong>{{ $route->DiemDen ?? '--' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-route me-2 text-info"></i>
                                    <div>
                                        <small class="text-muted d-block">Khoảng cách</small>
                                        <strong>{{ number_format($route->KhoangCach, 0, ',', '.') }} km</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal từ chối -->
                <div class="modal fade" id="rejectModal{{ $route->MaTuyen }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.routes.reject', $route->MaTuyen) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Từ chối tuyến đường</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Bạn có chắc chắn muốn từ chối tuyến đường <strong>#{{ $route->MaTuyen }}</strong>?</p>
                                    <div class="mb-3">
                                        <label class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                                        <textarea name="LyDoTuChoi" class="form-control" rows="3" required placeholder="Nhập lý do từ chối..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-danger">Từ chối</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Phần tuyến đường đã duyệt -->
    @if($approvedRoutes->count() > 0 && (!request('status') || request('status') == 'all' || request('status') == 'DaDuyet'))
    <div class="card-modern mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-check-circle me-2"></i>
                Tuyến đường đã duyệt ({{ $approvedRoutes->count() }})
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Nhà xe</th>
                            <th>Điểm đi</th>
                            <th>Điểm đến</th>
                            <th>Khoảng cách</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedRoutes as $route)
                        <tr>
                            <td>#{{ $route->MaTuyen }}</td>
                            <td>{{ $route->TenNhaXe }}</td>
                            <td>{{ $route->DiemDi }}</td>
                            <td>{{ $route->DiemDen }}</td>
                            <td>{{ number_format($route->KhoangCach, 0, ',', '.') }} km</td>
                            <td><span class="badge bg-success">Đã duyệt</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Phần tuyến đường bị từ chối -->
    @if($rejectedRoutes->count() > 0 && (!request('status') || request('status') == 'all' || request('status') == 'TuChoi'))
    <div class="card-modern mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="fas fa-times-circle me-2"></i>
                Tuyến đường bị từ chối ({{ $rejectedRoutes->count() }})
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Nhà xe</th>
                            <th>Điểm đi</th>
                            <th>Điểm đến</th>
                            <th>Khoảng cách</th>
                            <th>Lý do từ chối</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rejectedRoutes as $route)
                        <tr>
                            <td>#{{ $route->MaTuyen }}</td>
                            <td>{{ $route->TenNhaXe }}</td>
                            <td>{{ $route->DiemDi }}</td>
                            <td>{{ $route->DiemDen }}</td>
                            <td>{{ number_format($route->KhoangCach, 0, ',', '.') }} km</td>
                            <td><small class="text-danger">{{ $route->LyDoTuChoi ?? 'N/A' }}</small></td>
                            <td><span class="badge bg-danger">Từ chối</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($pendingRoutes->count() == 0 && $approvedRoutes->count() == 0 && $rejectedRoutes->count() == 0)
    <div class="card-modern">
        <div class="card-body text-center py-5">
            <i class="fas fa-route fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
            <h5 class="text-muted">Không có tuyến đường nào</h5>
        </div>
    </div>
    @endif
@endsection

