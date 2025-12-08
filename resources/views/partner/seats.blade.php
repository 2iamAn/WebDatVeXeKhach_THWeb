@extends('partner.layout')

@section('content')
<div class="page-header">
    <h2>
        <i class="fas fa-couch"></i>
        Sơ đồ ghế
    </h2>
    <p class="text-muted mb-0 mt-2">Quản lý và theo dõi tình trạng ghế ngồi</p>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h5 class="mb-0">
                <i class="fas fa-couch me-2 text-primary"></i>
                Sơ đồ ghế ngồi
            </h5>
            <div id="seatStats" class="d-flex flex-wrap gap-2">
                <span class="badge bg-success" style="font-size: 13px; padding: 8px 14px; border-radius: 10px;">
                    <i class="fas fa-check-circle me-1"></i>
                    Trống: <strong id="trongCount">{{ ($tongGhe34 ?? 0) - ($soGheDaDat34 ?? 0) }}</strong>
                </span>
                <span class="badge bg-primary" style="font-size: 13px; padding: 8px 14px; border-radius: 10px;">
                    <i class="fas fa-globe me-1"></i>
                    Đặt online: <strong id="onlineCount">0</strong>
                </span>
                <span class="badge" style="font-size: 13px; padding: 8px 14px; border-radius: 10px; background: #f57c00; color: white;">
                    <i class="fas fa-store me-1"></i>
                    Bán tại quầy: <strong id="counterCount">0</strong>
                </span>
                <span class="badge" style="font-size: 13px; padding: 8px 14px; border-radius: 10px; background: #7b1fa2; color: white;">
                    <i class="fas fa-check-double me-1"></i>
                    Đã lên xe: <strong id="confirmedCount">0</strong>
                </span>
                <span class="badge bg-warning text-dark" style="font-size: 13px; padding: 8px 14px; border-radius: 10px;">
                    <i class="fas fa-lock me-1"></i>
                    Giữ chỗ: <strong id="khoaCount">0</strong>
                </span>
                <span class="badge bg-info" style="font-size: 13px; padding: 8px 14px; border-radius: 10px;">
                    <i class="fas fa-couch me-1"></i>
                    Tổng: <strong id="totalCount">{{ $tongGhe34 ?? 0 }}</strong>
                </span>
            </div>
        </div>

        <!-- Calendar và bộ lọc tháng -->
        <div class="mb-3">
            <form method="GET" action="{{ route('partner.seats') }}" id="monthFormSeats" class="mb-2">
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
                           onchange="document.getElementById('monthFormSeats').submit()">
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
                        <a href="{{ route('partner.seats', ['month' => $selectedMonth, 'date' => $dateStr]) }}" 
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
            <div class="alert alert-info mb-3 py-2" style="font-size: 14px;">
                <i class="fas fa-info-circle me-2"></i>
                Đang hiển thị sơ đồ ghế ngày <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</strong>
            </div>
        @endif

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

        <!-- Thông tin chuyến xe -->
        @if($selectedChuyen)
            <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px;">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="fas fa-route me-2"></i>
                                Thông tin chuyến xe
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <strong>Tuyến:</strong> 
                                    {{ $selectedChuyen->tuyenDuong->DiemDi ?? '---' }} 
                                    <i class="fas fa-arrow-right mx-2"></i>
                                    {{ $selectedChuyen->tuyenDuong->DiemDen ?? '---' }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Giờ khởi hành:</strong> 
                                    {{ $selectedChuyen->GioKhoiHanh ? \Carbon\Carbon::parse($selectedChuyen->GioKhoiHanh)->format('d/m/Y H:i') : '---' }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <i class="fas fa-building me-2"></i>
                                    <strong>Nhà xe:</strong> {{ $selectedChuyen->nhaXe->TenNhaXe ?? '---' }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <i class="fas fa-bus me-2"></i>
                                    <strong>Xe:</strong> 
                                    <span id="busInfo">
                                        @if($selectedChuyen->xe && $selectedChuyen->xe->BienSoXe)
                                            {{ $selectedChuyen->xe->BienSoXe }}@if($selectedChuyen->xe->SoGhe) ({{ $selectedChuyen->xe->SoGhe }} chỗ)@endif
                                        @elseif($selectedChuyen->xe && $selectedChuyen->xe->SoGhe)
                                            --- ({{ $selectedChuyen->xe->SoGhe }} chỗ)
                                        @else
                                            ---
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <span class="badge bg-light text-dark" style="font-size: 14px; padding: 8px 15px;">
                                    <i class="fas fa-ticket-alt me-1"></i>
                                    Mã chuyến: #{{ $selectedChuyen->MaChuyenXe }}
                                </span>
                            </div>
                            @if($chuyens->count() > 1)
                                <select class="form-select" onchange="window.location.href='{{ route('partner.seats', ['month' => $selectedMonth, 'date' => $selectedDate]) }}&chuyen_id=' + this.value">
                                    <option value="">-- Chọn chuyến khác --</option>
                                    @foreach($chuyens as $chuyen)
                                        <option value="{{ $chuyen->MaChuyenXe }}" {{ $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe ? 'selected' : '' }}>
                                            {{ $chuyen->tuyenDuong->DiemDi ?? '---' }} → {{ $chuyen->tuyenDuong->DiemDen ?? '---' }} 
                                            ({{ $chuyen->GioKhoiHanh ? \Carbon\Carbon::parse($chuyen->GioKhoiHanh)->format('H:i') : '---' }})
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!$selectedChuyen && $selectedDate)
            <div class="alert alert-warning mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Không có chuyến xe đã được phê duyệt</strong> trong ngày {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}.
                <br>
                <small>Sơ đồ ghế chỉ hiển thị khi chuyến xe đã được admin phê duyệt.</small>
            </div>
        @endif

        @if($selectedChuyen)
        <!-- Radio buttons để chọn số chỗ -->
        <div class="mb-4">
            <div class="btn-group" role="group" aria-label="Số chỗ">
                <input type="radio" class="btn-check" name="soCho" id="cho34" value="34" checked autocomplete="off" onchange="changeSeatType('34')">
                <label class="btn btn-outline-success" for="cho34">
                    <i class="fas fa-bus me-2"></i>Xe Limousine 34 chỗ
                </label>

                <input type="radio" class="btn-check" name="soCho" id="cho42" value="42" autocomplete="off" onchange="changeSeatType('42')">
                <label class="btn btn-outline-success" for="cho42">
                    <i class="fas fa-bus me-2"></i>Xe thường 42 chỗ
                </label>
            </div>
        </div>

        <!-- Tab chuyển đổi tầng -->
        <div class="mb-3">
            <div class="btn-group" role="group" aria-label="Chọn tầng">
                <input type="radio" class="btn-check" name="floorSelect" id="floor1" value="1" checked autocomplete="off" onchange="switchFloor(1)">
                <label class="btn btn-outline-primary" for="floor1">
                    <i class="fas fa-layer-group me-2"></i>Tầng 1
                </label>
                
                <input type="radio" class="btn-check" name="floorSelect" id="floor2" value="2" autocomplete="off" onchange="switchFloor(2)">
                <label class="btn btn-outline-primary" for="floor2">
                    <i class="fas fa-layer-group me-2"></i>Tầng 2
                </label>
            </div>
        </div>
        
        <!-- Sơ đồ ghế -->
        <div id="seatMapContainer">
            <!-- Sẽ được render bởi JavaScript -->
        </div>

        <!-- Legend -->
        <div class="mt-4 pt-4 border-top">
            <h6 class="mb-3">
                <i class="fas fa-info-circle me-2 text-info"></i>
                Chú thích trạng thái ghế:
            </h6>
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="legend-item">
                        <div class="seat-legend seat-empty"></div>
                        <div class="legend-text">
                            <strong>Ghế trống</strong>
                            <small>Có thể đặt vé</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="legend-item">
                        <div class="seat-legend seat-booked-online"></div>
                        <div class="legend-text">
                            <strong>Đặt online</strong>
                            <small>Khách đặt qua web</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="legend-item">
                        <div class="seat-legend seat-booked-counter"></div>
                        <div class="legend-text">
                            <strong>Bán tại quầy</strong>
                            <small>Nhà xe bán trực tiếp</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="legend-item">
                        <div class="seat-legend seat-confirmed"></div>
                        <div class="legend-text">
                            <strong>Đã lên xe</strong>
                            <small>Đã xác nhận</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="legend-item">
                        <div class="seat-legend seat-locked"></div>
                        <div class="legend-text">
                            <strong>Giữ chỗ</strong>
                            <small>Đang tạm giữ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="legend-item">
                        <div class="seat-legend seat-cancelled"></div>
                        <div class="legend-text">
                            <strong>Đã hủy</strong>
                            <small>Vé bị hủy</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
.seat-map-wrapper {
    display: flex;
    gap: 30px;
    justify-content: center;
    flex-wrap: wrap;
}

.floor-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 20px;
    padding: 30px;
    min-width: 500px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border: 2px solid rgba(0, 184, 148, 0.1);
}

.floor-title {
    font-size: 20px;
    font-weight: 700;
    color: #00b894;
    margin-bottom: 25px;
    text-align: center;
    padding: 12px;
    background: linear-gradient(135deg, rgba(0, 184, 148, 0.1) 0%, rgba(0, 206, 201, 0.1) 100%);
    border-radius: 12px;
    border: 2px solid rgba(0, 184, 148, 0.2);
}

.seat-grid {
    display: grid;
    gap: 10px;
    margin-bottom: 15px;
}

.seat-item {
    width: 55px;
    height: 55px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.seat-item:hover {
    transform: scale(1.15) translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    z-index: 10;
}

/* Ghế trống */
.seat-empty {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    color: #2e7d32;
    border-color: #66bb6a;
}

.seat-empty:hover {
    background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%);
    border-color: #4caf50;
}

/* Đã đặt online */
.seat-booked-online {
    background: linear-gradient(135deg, #e3f2fd 0%, #90caf9 100%);
    color: #0d47a1;
    border-color: #1976d2;
}

.seat-booked-online:hover {
    background: linear-gradient(135deg, #90caf9 0%, #64b5f6 100%);
    box-shadow: 0 8px 20px rgba(25, 118, 210, 0.4);
}

/* Bán trực tiếp tại quầy */
.seat-booked-counter {
    background: linear-gradient(135deg, #fff3e0 0%, #ffcc80 100%);
    color: #e65100;
    border-color: #f57c00;
}

.seat-booked-counter:hover {
    background: linear-gradient(135deg, #ffcc80 0%, #ffb74d 100%);
    box-shadow: 0 8px 20px rgba(245, 124, 0, 0.4);
}

/* Đã xác nhận lên xe */
.seat-confirmed {
    background: linear-gradient(135deg, #f3e5f5 0%, #ce93d8 100%);
    color: #4a148c;
    border-color: #7b1fa2;
}

.seat-confirmed:hover {
    background: linear-gradient(135deg, #ce93d8 0%, #ba68c8 100%);
    box-shadow: 0 8px 20px rgba(123, 31, 162, 0.4);
}

/* Ghế khóa/giữ chỗ */
.seat-locked {
    background: linear-gradient(135deg, #fff9c4 0%, #fff176 100%);
    color: #f57f17;
    border-color: #fbc02d;
}

.seat-locked:hover {
    background: linear-gradient(135deg, #fff176 0%, #ffeb3b 100%);
    box-shadow: 0 8px 20px rgba(251, 192, 45, 0.4);
}

/* Đã hủy */
.seat-cancelled {
    background: linear-gradient(135deg, #ffebee 0%, #ef9a9a 100%);
    color: #b71c1c;
    border-color: #e53935;
    opacity: 0.7;
}

.seat-cancelled:hover {
    opacity: 1;
    box-shadow: 0 8px 20px rgba(229, 57, 53, 0.3);
}

/* Legacy - Ghế đã đặt (tổng quát) */
.seat-booked {
    background: linear-gradient(135deg, #e3f2fd 0%, #90caf9 100%);
    color: #0d47a1;
    border-color: #1976d2;
}

.seat-booked:hover {
    background: linear-gradient(135deg, #90caf9 0%, #64b5f6 100%);
    box-shadow: 0 8px 20px rgba(25, 118, 210, 0.4);
}

.driver-seat {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 22px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.seat-row {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
}

.aisle {
    width: 35px;
}

/* Legend styles */
.legend-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.legend-item:hover {
    border-color: #00b894;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 184, 148, 0.15);
}

.seat-legend {
    width: 45px;
    height: 45px;
    border-radius: 8px;
    flex-shrink: 0;
    border: 2px solid;
}

.legend-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.legend-text strong {
    font-size: 14px;
    color: #1a202c;
}

.legend-text small {
    font-size: 11px;
    color: #6c757d;
}

@media (max-width: 768px) {
    .floor-container {
        min-width: 100%;
        padding: 20px;
    }
    .seat-item {
        width: 45px;
        height: 45px;
        font-size: 11px;
    }
    .driver-seat {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
    .aisle {
        width: 25px;
    }
}
</style>

@php
    // Lấy danh sách ghế đã đặt từ controller
    $gheDaDat = $gheDaDat ?? [];
    $gheDaDatInt = array_map('intval', $gheDaDat);
    $veXeInfoMap = $veXeInfoMap ?? [];
    
    // Chỉ xử lý ghế nếu có chuyến được chọn
    $gheDataArray = [];
    if ($selectedChuyen && $ghe) {
        $gheDataArray = $ghe->map(function($g) use ($gheDaDatInt, $veXeInfoMap) {
        // Kiểm tra xem ghế có trong danh sách ghế đã đặt không (dựa vào VeXe)
        $maGheInt = (int)$g->MaGhe;
        $isBookedFromVe = in_array($maGheInt, $gheDaDatInt, true);
        
        // Lấy thông tin vé nếu có (từ veXeInfoMap với key là MaGhe)
        $veInfo = $veXeInfoMap[$maGheInt] ?? null;
        
        // Nếu có veInfo thì ghế chắc chắn đã đặt
        if ($veInfo) {
            $isBookedFromVe = true;
        }
        
        // Xác định trạng thái ghế:
        // - Đã đặt: Có vé trong VeXe (không bị hủy) - ưu tiên kiểm tra veInfo
        // - Khóa: TrangThai của Ghe = "Giữ chỗ" VÀ không có vé đã đặt
        // - Trống: Không có vé VÀ không bị khóa
        $status = \Illuminate\Support\Str::lower($g->TrangThai ?? '');
        $isLocked = ($status == 'giữ chỗ' || $status == 'giu cho') && !$isBookedFromVe;
        $isEmpty = !$isBookedFromVe && !$isLocked;
        
        // Chuẩn hóa SoGhe (đảm bảo format đúng: A01, A02, ...)
        $soGhe = $g->SoGhe;
        // Nếu SoGhe là "A1" thì chuyển thành "A01"
        if (preg_match('/^([A-Z])(\d+)$/', $soGhe, $matches)) {
            $prefix = $matches[1];
            $number = (int)$matches[2];
            $soGhe = $prefix . str_pad($number, 2, '0', STR_PAD_LEFT);
        }
        
        // Xác định trạng thái hiển thị
        // Ưu tiên kiểm tra veInfo để xác định ghế đã đặt
        if ($veInfo || $isBookedFromVe) {
            $trangThaiHienThi = 'Đã đặt';
            $isEmpty = false; // Đảm bảo ghế có vé không bị đánh dấu là trống
        } elseif ($isLocked) {
            $trangThaiHienThi = 'Giữ chỗ';
        } else {
            $trangThaiHienThi = 'Trống';
        }
        
        return [
            'soGhe' => $soGhe,
            'maGhe' => $maGheInt,
            'trangThai' => $trangThaiHienThi,
            'isEmpty' => $isEmpty,
            'isLocked' => $isLocked,
            'veInfo' => $veInfo // Đảm bảo veInfo luôn được truyền vào
        ];
        })->filter()->values()->toArray();
    }
    
    // Debug log
    \Log::info('Partner Seats - Debug', [
        'gheDaDat_count' => count($gheDaDatInt),
        'gheDaDat_sample' => array_slice($gheDaDatInt, 0, 5),
        'veXeInfoMap_count' => count($veXeInfoMap),
        'veXeInfoMap_sample' => array_slice($veXeInfoMap, 0, 2, true),
        'gheDataArray_count' => count($gheDataArray),
        'gheDataArray_booked' => array_filter($gheDataArray, function($g) { return !$g['isEmpty'] && !empty($g['veInfo']); })
    ]);
@endphp

<script>
// Dữ liệu ghế từ database
const gheData = @json($gheDataArray);

// Debug log
console.log('=== PARTNER SEATS DEBUG ===');
console.log('gheData (total):', gheData.length);
console.log('gheData (all):', gheData);
const bookedSeats = gheData.filter(g => !g.isEmpty);
console.log('gheData (booked):', bookedSeats);

// Tạo map để tra cứu nhanh
const gheMap = {};
gheData.forEach(g => {
    gheMap[g.soGhe] = {
        soGhe: g.soGhe,
        maGhe: g.maGhe,
        trangThai: g.trangThai,
        isEmpty: g.isEmpty,
        isLocked: g.isLocked || false,
        veInfo: g.veInfo || null
    };
});

console.log('gheMap (booked seats):', Object.keys(gheMap).filter(k => !gheMap[k].isEmpty).map(k => ({soGhe: k, ...gheMap[k]})));
console.log('========================');

// Định nghĩa layout ghế cho từng loại xe
// Layout: [cột trái, cột giữa, cột phải]
const seatLayouts = {
    '34': {
        floor1: [
            // Hàng 1: A01 (phải), A02 (giữa), A03 (trái)
            [['A03'], ['A02'], ['A01']],
            // Hàng 2: A04 (phải), A05 (giữa), A06 (trái)
            [['A06'], ['A05'], ['A04']],
            // Hàng 3: A07 (phải), A08 (giữa), A09 (trái)
            [['A09'], ['A08'], ['A07']],
            // Hàng 4: A10 (phải), A11 (giữa), A12 (trái)
            [['A12'], ['A11'], ['A10']],
            // Hàng 5: A13 (phải), A14 (giữa), A15 (trái)
            [['A15'], ['A14'], ['A13']],
            // Hàng 6: A16 (phải), A17 (trái)
            [['A17'], [], ['A16']]
        ],
        floor2: [
            // Hàng 1: B01 (phải), B02 (giữa), B03 (trái)
            [['B03'], ['B02'], ['B01']],
            // Hàng 2: B04 (phải), B05 (giữa), B06 (trái)
            [['B06'], ['B05'], ['B04']],
            // Hàng 3: B07 (phải), B08 (giữa), B09 (trái)
            [['B09'], ['B08'], ['B07']],
            // Hàng 4: B10 (phải), B11 (giữa), B12 (trái)
            [['B12'], ['B11'], ['B10']],
            // Hàng 5: B13 (phải), B14 (giữa), B15 (trái)
            [['B15'], ['B14'], ['B13']],
            // Hàng 6: B16 (phải), B17 (trái)
            [['B17'], [], ['B16']]
        ]
    },
    '42': {
        // Xe thường: 21 ghế tầng trên, 21 ghế tầng dưới
        // Layout 3 cột: trái, giữa, phải (7 hàng x 3 ghế = 21 ghế)
        floor1: [
            // Hàng 1: A01 (phải), A02 (giữa), A03 (trái)
            [['A03'], ['A02'], ['A01']],
            // Hàng 2: A04 (phải), A05 (giữa), A06 (trái)
            [['A06'], ['A05'], ['A04']],
            // Hàng 3: A07 (phải), A08 (giữa), A09 (trái)
            [['A09'], ['A08'], ['A07']],
            // Hàng 4: A10 (phải), A11 (giữa), A12 (trái)
            [['A12'], ['A11'], ['A10']],
            // Hàng 5: A13 (phải), A14 (giữa), A15 (trái)
            [['A15'], ['A14'], ['A13']],
            // Hàng 6: A16 (phải), A17 (giữa), A18 (trái)
            [['A18'], ['A17'], ['A16']],
            // Hàng 7: A19 (phải), A20 (giữa), A21 (trái)
            [['A21'], ['A20'], ['A19']]
        ],
        floor2: [
            // Hàng 1: B01 (phải), B02 (giữa), B03 (trái)
            [['B03'], ['B02'], ['B01']],
            // Hàng 2: B04 (phải), B05 (giữa), B06 (trái)
            [['B06'], ['B05'], ['B04']],
            // Hàng 3: B07 (phải), B08 (giữa), B09 (trái)
            [['B09'], ['B08'], ['B07']],
            // Hàng 4: B10 (phải), B11 (giữa), B12 (trái)
            [['B12'], ['B11'], ['B10']],
            // Hàng 5: B13 (phải), B14 (giữa), B15 (trái)
            [['B15'], ['B14'], ['B13']],
            // Hàng 6: B16 (phải), B17 (giữa), B18 (trái)
            [['B18'], ['B17'], ['B16']],
            // Hàng 7: B19 (phải), B20 (giữa), B21 (trái)
            [['B21'], ['B20'], ['B19']]
        ]
    }
};

function getSeatStatus(soGhe) {
    const ghe = gheMap[soGhe];
    if (!ghe) {
        return { 
            isEmpty: true, 
            isLocked: false, 
            status: 'Trống', 
            trangThai: 'Trống',
            veInfo: null, 
            maGhe: null 
        };
    }
    return {
        isEmpty: ghe.isEmpty,
        isLocked: ghe.isLocked || false,
        status: ghe.isLocked ? 'Giữ chỗ' : (ghe.isEmpty ? 'Trống' : 'Đã đặt'),
        trangThai: ghe.trangThai || (ghe.isLocked ? 'Giữ chỗ' : (ghe.isEmpty ? 'Trống' : 'Đã đặt')),
        veInfo: ghe.veInfo || null,
        maGhe: ghe.maGhe || null
    };
}

// Hàm hiển thị modal thao tác cho ghế (thống nhất cho tất cả trạng thái)
function showSeatActions(soGhe, seatInfo) {
    // Nếu seatInfo là string (JSON), parse nó
    if (typeof seatInfo === 'string') {
        try {
            seatInfo = JSON.parse(seatInfo.replace(/&quot;/g, '"').replace(/&#39;/g, "'"));
        } catch (e) {
            console.error('Error parsing seatInfo:', e);
            seatInfo = getSeatStatus(soGhe);
        }
    }
    
    // Nếu seatInfo không có veInfo, thử lấy từ gheMap
    if (!seatInfo.veInfo && gheMap[soGhe] && gheMap[soGhe].veInfo) {
        seatInfo.veInfo = gheMap[soGhe].veInfo;
    }
    
    // Nếu vẫn không có veInfo, lấy lại từ getSeatStatus
    if (!seatInfo.veInfo) {
        const currentStatus = getSeatStatus(soGhe);
        if (currentStatus.veInfo) {
            seatInfo.veInfo = currentStatus.veInfo;
        }
    }
    
    const { isEmpty, isLocked, veInfo, maGhe, trangThai } = seatInfo;
    
    // Debug log
    console.log('showSeatActions - soGhe:', soGhe);
    console.log('showSeatActions - seatInfo:', seatInfo);
    console.log('showSeatActions - isEmpty:', isEmpty);
    console.log('showSeatActions - isLocked:', isLocked);
    console.log('showSeatActions - maGhe:', maGhe);
    console.log('showSeatActions - veInfo:', veInfo);
    
    let modalHeaderClass = 'bg-secondary';
    let modalTitle = '';
    let modalBody = '';
    let modalFooter = '';
    
    if (isLocked && maGhe) {
        // Ghế đã khóa - hiển thị thông tin và nút mở khóa (ưu tiên check trước)
        modalHeaderClass = 'bg-warning text-dark';
        modalTitle = `<i class="fas fa-lock me-2"></i>Ghế ${soGhe} - Đã khóa`;
        
        modalBody = `
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Trạng thái:</strong> <span class="badge bg-warning">Giữ chỗ</span>
            </div>
            <p class="mb-0">Ghế này đang được giữ chỗ. Bạn có thể mở khóa để cho phép đặt vé.</p>
        `;
        
        modalFooter = `
            <form method="POST" action="{{ url('/partner/seats/unlock') }}/${maGhe}" 
                  style="display: inline-block;" 
                  onsubmit="return unlockSeatAjax(event, ${maGhe});">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-unlock me-1"></i>Mở khóa ghế
                </button>
            </form>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i>Đóng
            </button>
        `;
    } else if (!isEmpty && veInfo) {
        // Ghế đã đặt - hiển thị thông tin khách hàng và các thao tác
        modalHeaderClass = 'bg-primary text-white';
        modalTitle = `<i class="fas fa-user-check me-2"></i>Ghế ${soGhe} - Thông tin vé`;
        
        modalBody = `
            <div class="card border-0 mb-3" style="background: linear-gradient(135deg, #e3f2fd 0%, #f5f5f5 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Thông tin khách hàng</h6>
                        <span class="badge ${veInfo.trangThai === 'Đã thanh toán' || veInfo.trangThai === 'da_dat' ? 'bg-success' : 'bg-warning'}">
                            ${veInfo.trangThai || 'Chưa thanh toán'}
                        </span>
                    </div>
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="info-row">
                                <i class="fas fa-user text-primary"></i>
                                <strong>Tên khách:</strong>
                                <span>${veInfo.hoTen || '---'}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-row">
                                <i class="fas fa-phone text-success"></i>
                                <strong>Số điện thoại:</strong>
                                <span>${veInfo.sdt || '---'}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-row">
                                <i class="fas fa-ticket-alt text-info"></i>
                                <strong>Mã vé:</strong>
                                <span class="badge bg-info">${veInfo.maVe || '---'}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-row">
                                <i class="fas fa-credit-card text-warning"></i>
                                <strong>Thanh toán:</strong>
                                <span class="badge ${veInfo.phuongThuc && veInfo.phuongThuc !== 'Chưa thanh toán' ? 'bg-primary' : 'bg-secondary'}">
                                    ${veInfo.phuongThuc || 'Chưa thanh toán'}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-row">
                                <i class="fas fa-calendar text-danger"></i>
                                <strong>Ngày đặt:</strong>
                                <span>${veInfo.ngayDat || '---'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        modalFooter = `
            <button type="button" class="btn btn-success" onclick="confirmBoarding('${veInfo.maVe}', '${soGhe}')">
                <i class="fas fa-check-circle me-1"></i>Xác nhận lên xe
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-arrow-left me-1"></i>Đóng
            </button>
        `;
    } else if (isEmpty) {
        // Ghế trống - hiển thị tùy chọn bán vé hoặc giữ chỗ
        modalHeaderClass = 'bg-success text-white';
        modalTitle = `<i class="fas fa-couch me-2"></i>Ghế ${soGhe} - Ghế trống`;
        
        modalBody = `
            <div class="alert alert-success mb-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Trạng thái:</strong> <span class="badge bg-success">Ghế trống</span>
            </div>
            <p class="text-muted mb-3">Ghế này đang trống. Vui lòng chọn thao tác:</p>
            <div class="d-grid gap-3">
                <button class="btn btn-lg btn-primary d-flex align-items-center justify-content-between" onclick="sellTicketDirect('${soGhe}', ${maGhe || 'null'})">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-ticket-alt" style="font-size: 24px;"></i>
                        <div class="text-start">
                            <div class="fw-bold">Bán vé trực tiếp tại quầy</div>
                            <small style="opacity: 0.9;">Nhập thông tin khách và bán vé ngay</small>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </button>
                ${maGhe ? `
                <button class="btn btn-lg btn-warning d-flex align-items-center justify-content-between" onclick="lockSeatFromModal(${maGhe})">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-lock" style="font-size: 24px;"></i>
                        <div class="text-start">
                            <div class="fw-bold">Giữ chỗ tạm thời</div>
                            <small style="opacity: 0.9;">Khóa ghế để dành cho khách</small>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </button>` : ''}
            </div>
        `;
        
        modalFooter = `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i>Đóng
            </button>
        `;
    } else {
        // Trường hợp khác
        modalHeaderClass = 'bg-secondary text-white';
        modalTitle = `<i class="fas fa-seat me-2"></i>Ghế ${soGhe}`;
        modalBody = `<p>Không có thông tin về ghế này.</p>`;
        modalFooter = `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i>Đóng
            </button>
        `;
    }
    
    // Tạo nội dung modal
    const modalContent = `
        <div class="modal fade" id="seatActionsModal" tabindex="-1" aria-labelledby="seatActionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header ${modalHeaderClass}">
                        <h5 class="modal-title" id="seatActionsModalLabel">${modalTitle}</h5>
                        <button type="button" class="btn-close ${modalHeaderClass.includes('text-white') ? 'btn-close-white' : ''}" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${modalBody}
                    </div>
                    <div class="modal-footer">
                        ${modalFooter}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Xóa modal cũ nếu có
    const oldModal = document.getElementById('seatActionsModal');
    if (oldModal) {
        oldModal.remove();
    }
    
    // Thêm modal mới vào body
    document.body.insertAdjacentHTML('beforeend', modalContent);
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('seatActionsModal'));
    modal.show();
    
    // Xóa modal khi đóng
    document.getElementById('seatActionsModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Hàm cũ showSeatInfo - giữ lại để tương thích
function showSeatInfo(soGhe, veInfo) {
    const seatInfo = getSeatStatus(soGhe);
    seatInfo.veInfo = veInfo;
    showSeatActions(soGhe, seatInfo);
}

// Hàm khóa ghế từ modal
function lockSeatFromModal(maGhe) {
    // Đóng modal hiện tại
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('seatActionsModal'));
    if (currentModal) {
        currentModal.hide();
    }
    
    if (!confirm('Bạn có chắc chắn muốn giữ chỗ ghế này?')) {
        return false;
    }
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch(`{{ url('/partner/seats/lock') }}/${maGhe}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Giữ chỗ thành công!');
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi giữ chỗ!');
    });
    
    return false;
}

// Hàm khóa ghế bằng AJAX
function lockSeatAjax(event, maGhe) {
    event.preventDefault();
    
    if (!confirm('Bạn có chắc chắn muốn khóa ghế này?')) {
        return false;
    }
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi khóa ghế!');
    });
    
    return false;
}

// Hàm mở khóa ghế bằng AJAX
function unlockSeatAjax(event, maGhe) {
    event.preventDefault();
    
    if (!confirm('Bạn có chắc chắn muốn mở khóa ghế này?')) {
        return false;
    }
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi mở khóa ghế!');
    });
    
    return false;
}

// Hàm helper để render một ghế với màu sắc theo trạng thái
function renderSeat(soGhe) {
    const seatInfo = getSeatStatus(soGhe);
    let seatClass = 'seat-empty';
    let icon = '';
    let title = '';
    
    // Xác định class CSS và icon dựa trên trạng thái chi tiết
    if (seatInfo.isLocked) {
        seatClass = 'seat-locked';
        icon = '<i class="fas fa-lock" style="font-size: 10px;"></i>';
        title = `Ghế ${soGhe} - Giữ chỗ`;
    } else if (!seatInfo.isEmpty && seatInfo.veInfo) {
        const veInfo = seatInfo.veInfo;
        const trangThai = veInfo.trangThai || '';
        const phuongThuc = veInfo.phuongThuc || '';
        
        // Phân loại theo trạng thái vé
        if (trangThai.includes('Đã sử dụng') || trangThai.includes('Da su dung')) {
            // Đã xác nhận lên xe
            seatClass = 'seat-confirmed';
            icon = '<i class="fas fa-check-double" style="font-size: 10px;"></i>';
            title = `Ghế ${soGhe} - Đã lên xe - ${veInfo.hoTen}`;
        } else if (trangThai.includes('Hủy') || trangThai.includes('Huy')) {
            // Vé đã hủy
            seatClass = 'seat-cancelled';
            icon = '<i class="fas fa-ban" style="font-size: 10px;"></i>';
            title = `Ghế ${soGhe} - Đã hủy`;
        } else if (phuongThuc.includes('Tiền mặt') || phuongThuc.includes('Tien mat') || phuongThuc.includes('Bán trực tiếp')) {
            // Bán trực tiếp tại quầy
            seatClass = 'seat-booked-counter';
            icon = '<i class="fas fa-store" style="font-size: 10px;"></i>';
            title = `Ghế ${soGhe} - Bán tại quầy - ${veInfo.hoTen} - ${veInfo.sdt}`;
        } else {
            // Đặt online
            seatClass = 'seat-booked-online';
            icon = '<i class="fas fa-globe" style="font-size: 10px;"></i>';
            title = `Ghế ${soGhe} - Đặt online - ${veInfo.hoTen} - ${veInfo.sdt}`;
        }
    } else {
        title = `Ghế ${soGhe} - Trống - Click để đặt vé hoặc giữ chỗ`;
    }
    
    const displayName = soGhe.replace(/T[12]/i, '');
    const seatInfoJson = JSON.stringify(seatInfo).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    
    return `<div class="seat-item ${seatClass}" 
                 title="${title}"
                 onclick="showSeatActions('${soGhe}', '${seatInfoJson.replace(/'/g, "\\'")}')"
                 style="cursor: pointer !important;">
                 <div style="font-size: 13px; font-weight: 700;">${displayName}</div>
                 ${icon}
             </div>`;
}

// Hàm hiển thị options cho ghế trống
function showEmptySeatOptions(soGhe, maGhe) {
    if (confirm(`Bạn có muốn khóa ghế ${soGhe}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/partner/seats/lock') }}/${maGhe}`;
        
        const csrfToken = '{{ csrf_token() }}';
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        lockSeatAjax({preventDefault: () => {}, target: form}, maGhe);
    }
}

// Hàm hiển thị options cho ghế khóa
function showLockedSeatOptions(soGhe, maGhe) {
    if (confirm(`Bạn có muốn mở khóa ghế ${soGhe}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/partner/seats/unlock') }}/${maGhe}`;
        
        const csrfToken = '{{ csrf_token() }}';
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        unlockSeatAjax({preventDefault: () => {}, target: form}, maGhe);
    }
}

// Biến lưu tầng hiện tại (1 hoặc 2)
let currentFloor = 1;

function switchFloor(floor) {
    currentFloor = floor;
    // Lấy loại xe hiện tại từ radio button
    const selectedType = document.querySelector('input[name="soCho"]:checked')?.value || '34';
    renderSeatMap(selectedType);
}

function renderSeatMap(type) {
    const layout = seatLayouts[type];
    const container = document.getElementById('seatMapContainer');
    
    let html = '<div class="seat-map-wrapper">';
    
    // Chỉ hiển thị tầng được chọn
    if (currentFloor === 1) {
        // Tầng 1
        html += '<div class="floor-container">';
        html += '<div class="floor-title"><i class="fas fa-layer-group me-2"></i>Tầng 1</div>';
        html += '<div class="driver-seat"><i class="fas fa-steering-wheel"></i></div>';
        
        layout.floor1.forEach(row => {
            html += '<div class="seat-row">';
            // Nếu là hàng cuối (mảng 1 phần tử)
            if (row.length === 1 && row[0].length > 1) {
                row[0].forEach(soGhe => {
                    html += renderSeat(soGhe);
                });
            } else {
                // Hàng có 3 cột: trái, giữa, phải
                row.forEach((column, colIndex) => {
                    if (column && column.length > 0) {
                        column.forEach(soGhe => {
                            html += renderSeat(soGhe);
                        });
                    }
                    // Thêm lối đi giữa các cột (trừ cột cuối)
                    if (colIndex < row.length - 1) {
                        html += '<div class="aisle"></div>';
                    }
                });
            }
            html += '</div>';
        });
        html += '</div>';
    } else {
        // Tầng 2
        html += '<div class="floor-container">';
        html += '<div class="floor-title"><i class="fas fa-layer-group me-2"></i>Tầng 2</div>';
        
        layout.floor2.forEach(row => {
            html += '<div class="seat-row">';
            // Nếu là hàng cuối (mảng 1 phần tử)
            if (row.length === 1 && row[0].length > 1) {
                row[0].forEach(soGhe => {
                    html += renderSeat(soGhe);
                });
            } else {
                // Hàng có 3 cột: trái, giữa, phải
                row.forEach((column, colIndex) => {
                    if (column && column.length > 0) {
                        column.forEach(soGhe => {
                            html += renderSeat(soGhe);
                        });
                    }
                    // Thêm lối đi giữa các cột (trừ cột cuối)
                    if (colIndex < row.length - 1) {
                        html += '<div class="aisle"></div>';
                    }
                });
            }
            html += '</div>';
        });
        html += '</div>';
    }
    
    html += '</div>';
    
    container.innerHTML = html;
    
    // Cập nhật thống kê
    updateStats(type);
}

function updateStats(type) {
    const layout = seatLayouts[type];
    let trong = 0;
    let online = 0;
    let counter = 0;
    let confirmed = 0;
    let khoa = 0;
    let total = 0;
    
    [...layout.floor1, ...layout.floor2].forEach(row => {
        row.forEach(column => {
            if (Array.isArray(column) && column.length > 0) {
                column.forEach(soGhe => {
                    const seatInfo = getSeatStatus(soGhe);
                    total++;
                    
                    if (seatInfo.isLocked) {
                        khoa++;
                    } else if (seatInfo.isEmpty) {
                        trong++;
                    } else if (seatInfo.veInfo) {
                        const veInfo = seatInfo.veInfo;
                        const trangThai = veInfo.trangThai || '';
                        const phuongThuc = veInfo.phuongThuc || '';
                        
                        if (trangThai.includes('Đã sử dụng') || trangThai.includes('Da su dung')) {
                            confirmed++;
                        } else if (phuongThuc.includes('Tiền mặt') || phuongThuc.includes('Tien mat') || phuongThuc.includes('Bán trực tiếp')) {
                            counter++;
                        } else {
                            online++;
                        }
                    }
                });
            }
        });
    });
    
    document.getElementById('trongCount').textContent = trong;
    document.getElementById('onlineCount').textContent = online;
    document.getElementById('counterCount').textContent = counter;
    document.getElementById('confirmedCount').textContent = confirmed;
    document.getElementById('khoaCount').textContent = khoa;
    document.getElementById('totalCount').textContent = total;
}

// Dữ liệu chuyến xe từ server
const chuyen34Data = @json($chuyen34 ? [
    'bienSoXe' => $chuyen34->xe->BienSoXe ?? null,
    'soGhe' => $chuyen34->xe->SoGhe ?? null
] : null);
const chuyen41Data = @json($chuyen41 ? [
    'bienSoXe' => $chuyen41->xe->BienSoXe ?? null,
    'soGhe' => $chuyen41->xe->SoGhe ?? null
] : null);

function changeSeatType(type) {
    // Reset về tầng 1 khi chuyển loại xe
    currentFloor = 1;
    document.getElementById('floor1').checked = true;
    document.getElementById('floor2').checked = false;
    
    renderSeatMap(type);
    updateStatsBySeatType(type);
    updateBusInfo(type);
}

// Dữ liệu chuyến xe 42 chỗ cho bus info
const chuyen42InfoData = @json($selectedChuyen && $selectedChuyen->xe && $selectedChuyen->xe->SoGhe == 42 ? [
    'bienSoXe' => $selectedChuyen->xe->BienSoXe ?? null,
    'soGhe' => $selectedChuyen->xe->SoGhe ?? null
] : null);

function updateBusInfo(type) {
    const busInfoElement = document.getElementById('busInfo');
    if (!busInfoElement) return;
    
    let busInfo = '---';
    let chuyenData = null;
    
    if (type === '34') {
        chuyenData = chuyen34Data;
    } else {
        // Xe 41-42 chỗ đều dùng layout 42
        chuyenData = chuyen42InfoData;
    }
    
    if (chuyenData) {
        if (chuyenData.bienSoXe) {
            busInfo = chuyenData.bienSoXe;
            if (chuyenData.soGhe) {
                busInfo += ' (' + chuyenData.soGhe + ' chỗ)';
            }
        } else if (chuyenData.soGhe) {
            busInfo = '--- (' + chuyenData.soGhe + ' chỗ)';
        }
    }
    
    busInfoElement.textContent = busInfo;
}

// Dữ liệu chuyến xe 42 chỗ từ server
@php
    $chuyen42DataArray = null;
    if ($selectedChuyen && $selectedChuyen->xe && $selectedChuyen->xe->SoGhe == 42) {
        $tongGhe42 = $selectedChuyen->ghe->count() > 0 ? $selectedChuyen->ghe->count() : ($selectedChuyen->xe->SoGhe ?? 42);
        $soGheDaDat42 = $selectedChuyen->veXe->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
            ->filter(function($ve) { 
                return $ve->thanhToan && $ve->thanhToan->TrangThai === 'Success'; 
            })
            ->count() ?? 0;
        $chuyen42DataArray = [
            'tongGhe' => $tongGhe42,
            'soGheDaDat' => $soGheDaDat42
        ];
    }
@endphp
const chuyen42Data = @json($chuyen42DataArray);

function updateStatsBySeatType(type) {
    // Sử dụng dữ liệu từ server
    const tongGhe34 = {{ $tongGhe34 ?? 0 }};
    const soGheDaDat34 = {{ $soGheDaDat34 ?? 0 }};
    const tongGhe41 = {{ $tongGhe41 ?? 0 }};
    const soGheDaDat41 = {{ $soGheDaDat41 ?? 0 }};
    
    let totalSeats, bookedSeats, emptySeats;
    
    if (type === '34') {
        totalSeats = tongGhe34;
        bookedSeats = soGheDaDat34;
        emptySeats = tongGhe34 - soGheDaDat34;
    } else {
        // Xe 41-42 chỗ đều dùng layout 42
        // Tính toán từ ghế thực tế của chuyến được chọn
        if (chuyen42Data) {
            totalSeats = chuyen42Data.tongGhe || 42;
            bookedSeats = chuyen42Data.soGheDaDat || 0;
            emptySeats = totalSeats - bookedSeats;
        } else {
            // Fallback: sử dụng giá trị mặc định
            totalSeats = 42;
            bookedSeats = 0;
            emptySeats = 42;
        }
    }
    
    // Cập nhật số liệu thống kê
    document.getElementById('trongCount').textContent = Math.max(0, emptySeats);
    document.getElementById('daDatCount').textContent = bookedSeats;
    document.getElementById('totalCount').textContent = totalSeats;
}

// Hàm xác nhận khách đã lên xe
function confirmBoarding(maVe, soGhe) {
    if (!confirm(`Xác nhận khách hàng ghế ${soGhe} đã lên xe?`)) {
        return;
    }
    
    // Gọi API để cập nhật trạng thái
    fetch(`{{ url('/partner/seats/confirm-boarding') }}/${maVe}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ maVe: maVe, soGhe: soGhe })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Có lỗi xảy ra!');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Đã xác nhận khách lên xe!');
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Có lỗi xảy ra khi xác nhận!');
    });
}

// Hàm bán vé trực tiếp tại quầy
function sellTicketDirect(soGhe, maGhe) {
    // Đóng modal hiện tại
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('seatActionsModal'));
    if (currentModal) {
        currentModal.hide();
    }
    
    // Tạo modal nhập thông tin khách
    const sellModalContent = `
        <div class="modal fade" id="sellDirectModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-ticket-alt me-2"></i>Bán vé trực tiếp - Ghế ${soGhe}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="sellDirectForm" onsubmit="return submitSellDirect(event, '${soGhe}', ${maGhe})">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user me-1"></i>Họ và tên khách hàng *
                                </label>
                                <input type="text" class="form-control" name="hoTen" required placeholder="Nhập họ tên khách hàng">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-phone me-1"></i>Số điện thoại *
                                </label>
                                <input type="tel" class="form-control" name="sdt" required placeholder="Nhập số điện thoại">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-money-bill me-1"></i>Giá vé
                                </label>
                                <input type="number" class="form-control" name="giaVe" value="{{ $selectedChuyen->GiaVe ?? 0 }}" readonly>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vé sẽ được tạo với trạng thái <strong>"Bán trực tiếp"</strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Xác nhận bán vé
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Hủy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Xóa modal cũ nếu có
    const oldSellModal = document.getElementById('sellDirectModal');
    if (oldSellModal) {
        oldSellModal.remove();
    }
    
    // Thêm modal mới
    document.body.insertAdjacentHTML('beforeend', sellModalContent);
    const sellModal = new bootstrap.Modal(document.getElementById('sellDirectModal'));
    sellModal.show();
    
    // Xóa modal khi đóng
    document.getElementById('sellDirectModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Hàm submit bán vé trực tiếp
function submitSellDirect(event, soGhe, maGhe) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Thêm thông tin ghế và chuyến
    formData.append('soGhe', soGhe);
    formData.append('maGhe', maGhe);
    formData.append('maChuyenXe', '{{ $selectedChuyen->MaChuyenXe ?? "" }}');
    
    fetch('{{ url("/partner/seats/sell-direct") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Bán vé thành công!');
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi bán vé!');
    });
    
    return false;
}

// Khởi tạo với loại xe mặc định
document.addEventListener('DOMContentLoaded', function() {
    // Tự động xác định loại xe từ chuyến được chọn
    let defaultType = '34';
    @if($selectedChuyen && $selectedChuyen->xe && $selectedChuyen->xe->SoGhe)
        const selectedSoGhe = {{ $selectedChuyen->xe->SoGhe }};
        if (selectedSoGhe == 42 || selectedSoGhe == 41) {
            // Xe 41-42 chỗ đều dùng layout 42
            defaultType = '42';
            document.getElementById('cho42').checked = true;
        } else {
            document.getElementById('cho34').checked = true;
        }
    @endif
    
    renderSeatMap(defaultType);
    updateStatsBySeatType(defaultType);
    updateBusInfo(defaultType);
    updateStatsBySeatType('34');
    updateBusInfo('34');
});
</script>

<style>
/* Info row trong modal */
.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: rgba(0, 184, 148, 0.03);
    border-radius: 10px;
    margin-bottom: 10px;
    border: 1px solid rgba(0, 184, 148, 0.1);
    transition: all 0.3s ease;
}

.info-row:hover {
    background: rgba(0, 184, 148, 0.08);
    border-color: rgba(0, 184, 148, 0.2);
    transform: translateX(5px);
}

.info-row i {
    font-size: 18px;
    width: 24px;
}

.info-row strong {
    min-width: 120px;
    color: #495057;
    font-weight: 600;
}

.info-row span {
    color: #212529;
    font-weight: 500;
}

/* Modal custom styles */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-header {
    padding: 18px 24px;
    border-bottom: none;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

/* List group trong modal */
.list-group-item {
    border: 1px solid rgba(0, 184, 148, 0.1);
    border-radius: 10px !important;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    padding: 15px;
}

.list-group-item:hover {
    background: rgba(0, 184, 148, 0.05);
    border-color: rgba(0, 184, 148, 0.2);
    transform: translateX(5px);
}

/* Button lớn trong modal ghế trống */
.modal-body .btn-lg {
    padding: 18px 24px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-body .btn-lg:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
}

.modal-body .btn-lg .text-start {
    line-height: 1.3;
}

.modal-body .btn-lg small {
    font-size: 0.85rem;
}

.modal-body .d-grid {
    margin-top: 10px;
}

/* Calendar styles */
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
