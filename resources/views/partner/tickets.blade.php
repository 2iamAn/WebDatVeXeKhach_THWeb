@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-ticket-alt"></i>
        Tình trạng vé
    </h2>
    <p class="text-muted mb-0 mt-2">Danh sách vé đã bán của nhà xe</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Danh sách vé bán
            </h5>
            <div>
                <span class="badge bg-info" style="font-size: 14px; padding: 8px 15px;">
                    <i class="fas fa-ticket-alt me-1"></i>
                    Tổng: {{ $tickets->count() }} vé
                </span>
            </div>
        </div>

        @if($tickets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-hashtag me-2"></i>Mã vé
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-user me-2"></i>Hành khách
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-route me-2"></i>Tuyến đường
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-couch me-2"></i>Ghế
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-calendar-alt me-2"></i>Ngày đặt
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-money-bill-wave me-2"></i>Giá vé
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-info-circle me-2"></i>Trạng thái
                            </th>
                            <th style="border: none; padding: 15px; text-align: center;">
                                <i class="fas fa-cog me-2"></i>Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $v)
                        <tr style="transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 15px;">
                                <strong class="text-primary">
                                    <i class="fas fa-ticket-alt me-1"></i>
                                    {{ $v->MaVe }}
                                </strong>
                            </td>
                            <td style="padding: 15px;">
                                <div class="d-flex flex-column">
                                    <strong>{{ $v->nguoiDung->HoTen ?? '--' }}</strong>
                                    @if($v->nguoiDung)
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $v->nguoiDung->SDT ?? '--' }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>{{ $v->nguoiDung->Email ?? '--' }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                @if($v->chuyenXe && $v->chuyenXe->tuyenDuong)
                                    <div class="d-flex flex-column">
                                        <div>
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            <strong>{{ $v->chuyenXe->tuyenDuong->DiemDi }}</strong>
                                        </div>
                                        <div class="my-1">
                                            <i class="fas fa-arrow-down text-muted"></i>
                                        </div>
                                        <div>
                                            <i class="fas fa-map-marker-alt text-success me-1"></i>
                                            <strong>{{ $v->chuyenXe->tuyenDuong->DiemDen }}</strong>
                                        </div>
                                        <small class="text-muted mt-1">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($v->chuyenXe->GioKhoiHanh)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                <span class="badge bg-secondary" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="fas fa-couch me-1"></i>
                                    {{ optional($v->ghe)->SoGhe ?? '--' }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <i class="far fa-calendar text-muted me-2"></i>
                                {{ optional($v->NgayDat)->format('d/m/Y H:i') ?? '--' }}
                            </td>
                            <td style="padding: 15px;">
                                <span class="badge bg-success" style="font-size: 13px; padding: 8px 12px;">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ number_format($v->GiaTaiThoiDiemDat ?? $v->chuyenXe->GiaVe ?? 0, 0, ',', '.') }} ₫
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                @php
                                    $status = $v->TrangThai ?? 'Chưa thanh toán';
                                    $badgeClass = 'secondary';
                                    $icon = 'question-circle';
                                    
                                    if (stripos($status, 'đã thanh toán') !== false || stripos($status, 'đã đi') !== false) {
                                        $badgeClass = 'success';
                                        $icon = 'check-circle';
                                    } elseif (stripos($status, 'hủy') !== false) {
                                        $badgeClass = 'danger';
                                        $icon = 'times-circle';
                                    } elseif (stripos($status, 'hoàn tiền') !== false) {
                                        $badgeClass = 'warning';
                                        $icon = 'undo';
                                    } elseif (stripos($status, 'chưa') !== false) {
                                        $badgeClass = 'warning';
                                        $icon = 'clock';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}" style="font-size: 13px; padding: 8px 12px;">
                                    <i class="fas fa-{{ $icon }} me-1"></i>
                                    {{ $status }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="statusDropdown{{ $v->MaVe }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-edit me-1"></i>
                                        Đổi trạng thái
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $v->MaVe }}">
                                        <li>
                                            <form method="POST" action="{{ route('partner.tickets.update-status', $v->MaVe) }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="TrangThai" value="Đã đi">
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Xác nhận đổi trạng thái vé {{ $v->MaVe }} sang \"Đã đi\"?')">
                                                    <i class="fas fa-check-circle text-success me-2"></i>Đã đi
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('partner.tickets.update-status', $v->MaVe) }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="TrangThai" value="Hủy">
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Xác nhận hủy vé {{ $v->MaVe }}?')">
                                                    <i class="fas fa-times-circle me-2"></i>Hủy
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('partner.tickets.update-status', $v->MaVe) }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="TrangThai" value="Hoàn tiền">
                                                <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Xác nhận hoàn tiền cho vé {{ $v->MaVe }}?')">
                                                    <i class="fas fa-undo me-2"></i>Hoàn tiền
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('partner.tickets.update-status', $v->MaVe) }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="TrangThai" value="Đã thanh toán">
                                                <button type="submit" class="dropdown-item text-info" onclick="return confirm('Xác nhận đổi trạng thái vé {{ $v->MaVe }} sang \"Đã thanh toán\"?')">
                                                    <i class="fas fa-check me-2"></i>Đã thanh toán
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('partner.tickets.update-status', $v->MaVe) }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="TrangThai" value="Chưa thanh toán">
                                                <button type="submit" class="dropdown-item text-muted" onclick="return confirm('Xác nhận đổi trạng thái vé {{ $v->MaVe }} sang \"Chưa thanh toán\"?')">
                                                    <i class="fas fa-clock me-2"></i>Chưa thanh toán
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="text-muted">Chưa có vé nào</h5>
                <p class="text-muted">Chưa có vé nào được bán.</p>
            </div>
        @endif
    </div>
</div>
@endsection
