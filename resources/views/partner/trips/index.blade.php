@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-route"></i>
        Quản lý chuyến đi
    </h2>
    <p class="text-muted mb-0 mt-2">Danh sách tất cả các chuyến xe của bạn</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Danh sách chuyến xe
            </h5>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a href="{{ route('partner.trips.create') }}" class="btn btn-primary" style="border-radius: 10px;">
                    <i class="fas fa-plus-circle me-2"></i>
                    Thêm chuyến mới
                </a>
            </div>
        </div>

        <!-- Calendar và bộ lọc tháng -->
        <div class="mb-3">
            <form method="GET" action="{{ route('partner.trips') }}" id="monthForm" class="mb-2">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <label for="month" class="form-label mb-0 d-flex align-items-center" style="font-size: 14px;">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        <strong>Tháng:</strong>
                    </label>
                    <input type="month" 
                           id="month" 
                           name="month" 
                           class="form-control form-control-sm" 
                           style="width: auto; max-width: 180px; border-radius: 8px; font-size: 14px;"
                           value="{{ $selectedMonth ?? now()->format('Y-m') }}"
                           onchange="document.getElementById('monthForm').submit()">
                </div>
            </form>

            <!-- Calendar hiển thị các ngày - Compact -->
            <div class="calendar-container-compact mb-3" style="background: #f8f9fa; border-radius: 8px; padding: 8px; max-width: 380px;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="mb-0" style="font-size: 12px; font-weight: 600; color: #495057;">
                        <i class="fas fa-calendar me-1" style="font-size: 11px;"></i>
                        {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('m/Y') }}
                    </h6>
                </div>
                <div class="calendar-grid">
                    @php
                        $year = substr($selectedMonth, 0, 4);
                        $month = substr($selectedMonth, 5, 2);
                        $firstDay = \Carbon\Carbon::create($year, $month, 1);
                        $lastDay = $firstDay->copy()->endOfMonth();
                        $startDate = $firstDay->copy()->startOfWeek();
                        $endDate = $lastDay->copy()->endOfWeek();
                        $currentDate = $startDate->copy();
                    @endphp
                    
                    <!-- Header các ngày trong tuần - nằm trong cùng grid với các ngày -->
                    <div class="calendar-weekday-compact">CN</div>
                    <div class="calendar-weekday-compact">T2</div>
                    <div class="calendar-weekday-compact">T3</div>
                    <div class="calendar-weekday-compact">T4</div>
                    <div class="calendar-weekday-compact">T5</div>
                    <div class="calendar-weekday-compact">T6</div>
                    <div class="calendar-weekday-compact">T7</div>
                    
                    <!-- Các ngày trong tháng -->
                    @while($currentDate <= $endDate)
                        @php
                            $dateStr = $currentDate->format('Y-m-d');
                            $isCurrentMonth = $currentDate->month == $month;
                            $isToday = $currentDate->isToday();
                            $isSelected = $selectedDate == $dateStr;
                            $tripCount = $daysWithTrips[$dateStr] ?? 0;
                        @endphp
                        <a href="{{ route('partner.trips', ['month' => $selectedMonth, 'date' => $dateStr]) }}" 
                           class="calendar-day-compact {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }} {{ $isSelected ? 'selected' : '' }}"
                           title="{{ $isCurrentMonth ? $currentDate->format('d/m/Y') : '' }} - {{ $tripCount }} chuyến">
                            <div class="calendar-day-number-compact">{{ $currentDate->day }}</div>
                            @if($tripCount > 0 && $isCurrentMonth)
                                <div class="calendar-day-badge-compact">{{ $tripCount }}</div>
                            @endif
                        </a>
                        @php $currentDate->addDay(); @endphp
                    @endwhile
                </div>
            </div>
        </div>

        @if($selectedDate)
            <div class="alert alert-info mb-3 py-2 d-flex justify-content-between align-items-center" style="font-size: 14px;">
                <span>
                    <i class="fas fa-info-circle me-2"></i>
                    Đang hiển thị chuyến xe ngày <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</strong>
                </span>
                <a href="{{ route('partner.trips', ['month' => $selectedMonth]) }}" class="btn btn-sm btn-outline-primary">
                    Xem tất cả tháng
                </a>
            </div>
        @endif

        @if($trips->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%); color: white;">
                        <tr>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-hashtag me-2"></i>Mã
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-map-marked-alt me-2"></i>Tuyến
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-clock me-2"></i>Giờ khởi hành
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-money-bill-wave me-2"></i>Giá vé
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-couch me-2"></i>Ghế trống
                            </th>
                            <th style="border: none; padding: 15px;">
                                <i class="fas fa-info-circle me-2"></i>Trạng thái
                            </th>
                            <th style="border: none; padding: 15px; text-align: center;">
                                <i class="fas fa-cog me-2"></i>Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trips as $cx)
                        <tr style="transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 15px;">
                                <strong class="text-primary">#{{ $cx->MaChuyenXe }}</strong>
                            </td>
                            <td style="padding: 15px;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <span><strong>{{ $cx->DiemDi }}</strong></span>
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    <span><strong>{{ $cx->DiemDen }}</strong></span>
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                <i class="far fa-clock text-info me-2"></i>
                                {{ \Carbon\Carbon::parse($cx->GioKhoiHanh)->format('d/m/Y H:i') }}
                            </td>
                            <td style="padding: 15px;">
                                <span class="badge bg-success" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ number_format($cx->GiaVe) }} ₫
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-info" style="font-size: 12px; padding: 6px 10px;">
                                        <i class="fas fa-couch me-1"></i>
                                        {{ $cx->so_ghe_trong ?? 0 }}/{{ $cx->tong_ghe ?? 0 }} trống
                                    </span>
                                    @if(($cx->so_ve_da_dat ?? 0) > 0)
                                        <small class="text-muted">
                                            <i class="fas fa-ticket-alt me-1"></i>
                                            {{ $cx->so_ve_da_dat }} vé đã đặt
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                @php
                                    $statusClass = 'warning';
                                    $statusIcon = 'clock';
                                    if ($cx->TrangThai == 'DaDuyet' || $cx->TrangThai == 'Còn chỗ') {
                                        $statusClass = 'success';
                                        $statusIcon = 'check-circle';
                                    } elseif ($cx->TrangThai == 'Tạm dừng' || $cx->TrangThai == 'Hết chỗ') {
                                        $statusClass = 'danger';
                                        $statusIcon = 'times-circle';
                                    } elseif ($cx->TrangThai == 'ChoDuyet') {
                                        $statusClass = 'warning';
                                        $statusIcon = 'clock';
                                    } elseif ($cx->TrangThai == 'TuChoi') {
                                        $statusClass = 'danger';
                                        $statusIcon = 'times-circle';
                                    }
                                @endphp
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-{{ $statusClass }}" style="font-size: 13px; padding: 8px 12px;">
                                        <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                        @if($cx->TrangThai == 'ChoDuyet')
                                            Chờ duyệt
                                        @elseif($cx->TrangThai == 'DaDuyet')
                                            Đã duyệt
                                        @elseif($cx->TrangThai == 'TuChoi')
                                            Từ chối
                                        @else
                                            {{ $cx->TrangThai }}
                                        @endif
                                    </span>
                                    @if($cx->TrangThai == 'TuChoi' && isset($cx->LyDoTuChoi) && $cx->LyDoTuChoi)
                                        <small class="text-danger" style="font-size: 11px;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <strong>Lý do:</strong> {{ $cx->LyDoTuChoi }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    <button type="button" 
                                            class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#quickEditModal{{ $cx->MaChuyenXe }}"
                                            title="Sửa nhanh">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(in_array($cx->TrangThai, ['DaDuyet', 'Còn chỗ', 'Tạm dừng']))
                                    <a href="{{ route('partner.trips.toggle', $cx->MaChuyenXe) }}" 
                                       class="btn btn-sm {{ ($cx->TrangThai == 'Còn chỗ' || $cx->TrangThai == 'DaDuyet') ? 'btn-warning' : 'btn-success' }}"
                                       title="{{ ($cx->TrangThai == 'Còn chỗ' || $cx->TrangThai == 'DaDuyet') ? 'Ngưng' : 'Kích hoạt' }}"
                                       onclick="return confirm('Bạn có chắc chắn muốn {{ ($cx->TrangThai == 'Còn chỗ' || $cx->TrangThai == 'DaDuyet') ? 'ngưng' : 'kích hoạt' }} chuyến này?')">
                                        <i class="fas fa-{{ ($cx->TrangThai == 'Còn chỗ' || $cx->TrangThai == 'DaDuyet') ? 'pause' : 'play' }}"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('partner.trips.tickets', $cx->MaChuyenXe) }}" 
                                       class="btn btn-sm btn-info"
                                       title="Xem vé đã đặt">
                                        <i class="fas fa-ticket-alt"></i>
                                    </a>
                                    <a href="{{ route('partner.trips.edit', $cx->MaChuyenXe) }}" 
                                       class="btn btn-sm btn-secondary"
                                       title="Sửa chi tiết">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    <a href="{{ route('partner.trips.delete', $cx->MaChuyenXe) }}"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa chuyến này?')"
                                       title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                
                                <!-- Modal Sửa nhanh -->
                                <div class="modal fade" id="quickEditModal{{ $cx->MaChuyenXe }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit me-2"></i>
                                                    Sửa nhanh chuyến #{{ $cx->MaChuyenXe }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('partner.trips.quick-update', $cx->MaChuyenXe) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <i class="fas fa-calendar-alt me-2"></i>Giờ khởi hành
                                                        </label>
                                                        <input type="datetime-local" 
                                                               name="GioKhoiHanh" 
                                                               class="form-control"
                                                               value="{{ optional($cx->GioKhoiHanh)->format('Y-m-d\TH:i') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <i class="fas fa-clock me-2"></i>Giờ đến
                                                        </label>
                                                        <input type="datetime-local" 
                                                               name="GioDen" 
                                                               class="form-control"
                                                               value="{{ optional($cx->GioDen)->format('Y-m-d\TH:i') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            <i class="fas fa-money-bill-wave me-2"></i>Giá vé (VND)
                                                        </label>
                                                        <input type="number" 
                                                               name="GiaVe" 
                                                               class="form-control"
                                                               value="{{ $cx->GiaVe }}"
                                                               min="0" step="1000">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                <h5 class="text-muted">Chưa có chuyến xe nào</h5>
                <p class="text-muted">Hãy thêm chuyến xe đầu tiên của bạn!</p>
                <a href="{{ route('partner.trips.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle me-2"></i>
                    Thêm chuyến mới
                </a>
            </div>
        @endif
    </div>
</div>

<style>
/* Calendar Compact Styles */
.calendar-container-compact {
    max-width: 380px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 3px;
}

.calendar-weekday-compact {
    text-align: center;
    font-weight: 600;
    color: #4FB99F;
    padding: 4px 2px;
    background: white;
    border-radius: 4px;
    font-size: 10px;
}

.calendar-day-compact {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 4px;
    text-decoration: none;
    color: #495057;
    transition: all 0.2s;
    position: relative;
    padding: 2px;
    border: 1px solid transparent;
    min-height: 28px;
}

.calendar-day-compact:hover {
    background: #e9ecef;
    transform: scale(1.08);
    border-color: #4FB99F;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(79, 185, 159, 0.3);
}

.calendar-day-compact.other-month {
    color: #adb5bd;
    background: #f8f9fa;
    opacity: 0.6;
}

.calendar-day-compact.today {
    background: #fff3cd;
    border-color: #ffc107;
    font-weight: 700;
}

.calendar-day-compact.selected {
    background: #4FB99F;
    color: white;
    font-weight: 700;
    border-color: #3a8f7a;
    box-shadow: 0 2px 8px rgba(79, 185, 159, 0.4);
}

.calendar-day-number-compact {
    font-size: 11px;
    font-weight: 600;
    line-height: 1;
}

.calendar-day-badge-compact {
    position: absolute;
    top: 1px;
    right: 1px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 12px;
    height: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7px;
    font-weight: 700;
    line-height: 1;
}

.calendar-day-compact.selected .calendar-day-badge-compact {
    background: white;
    color: #4FB99F;
}

@media (max-width: 768px) {
    .calendar-container-compact {
        max-width: 100%;
    }
    
    .calendar-weekday-compact {
        font-size: 9px;
        padding: 3px 1px;
    }
    
    .calendar-day-number-compact {
        font-size: 10px;
    }
    
    .calendar-day-badge-compact {
        width: 10px;
        height: 10px;
        font-size: 6px;
        top: 1px;
        right: 1px;
    }
    
    .calendar-day-compact {
        min-height: 24px;
    }
}
</style>
@endsection
