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
            <div id="seatStats">
                <span class="badge bg-success me-2" style="font-size: 14px; padding: 8px 15px;">
                    <i class="fas fa-check-circle me-1"></i>
                    Trống: <span id="trongCount">{{ ($tongGhe34 ?? 0) - ($soGheDaDat34 ?? 0) }}</span>
                </span>
                <span class="badge bg-warning me-2" style="font-size: 14px; padding: 8px 15px;">
                    <i class="fas fa-lock me-1"></i>
                    Khóa: <span id="khoaCount">0</span>
                </span>
                <span class="badge bg-danger me-2" style="font-size: 14px; padding: 8px 15px;">
                    <i class="fas fa-times-circle me-1"></i>
                    Đã đặt: <span id="daDatCount">{{ $soGheDaDat34 ?? 0 }}</span>
                </span>
                <span class="badge bg-info" style="font-size: 14px; padding: 8px 15px;">
                    <i class="fas fa-couch me-1"></i>
                    Tổng: <span id="totalCount">{{ $tongGhe34 ?? 0 }}</span>
                </span>
            </div>
        </div>

        <!-- Calendar và bộ lọc tháng -->
        <div class="mb-4">
            <form method="GET" action="{{ route('partner.seats') }}" id="monthFormSeats" class="mb-3">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <label for="month" class="form-label mb-0 d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        <strong>Chọn tháng:</strong>
                    </label>
                    <input type="month" 
                           id="month" 
                           name="month" 
                           class="form-control" 
                           style="width: auto; border-radius: 10px;"
                           value="{{ $selectedMonth ?? now()->format('Y-m') }}"
                           onchange="document.getElementById('monthFormSeats').submit()">
                </div>
            </form>

            <!-- Calendar hiển thị các ngày -->
            <div class="calendar-container mb-4" style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h6 class="mb-3 text-center">
                    <i class="fas fa-calendar me-2"></i>
                    Lịch tháng {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('m/Y') }}
                </h6>
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
                    
                    <!-- Header các ngày trong tuần -->
                    <div class="calendar-weekdays">
                        <div class="calendar-weekday">CN</div>
                        <div class="calendar-weekday">T2</div>
                        <div class="calendar-weekday">T3</div>
                        <div class="calendar-weekday">T4</div>
                        <div class="calendar-weekday">T5</div>
                        <div class="calendar-weekday">T6</div>
                        <div class="calendar-weekday">T7</div>
                    </div>
                    
                    <!-- Các ngày trong tháng -->
                    <div class="calendar-days">
                        @while($currentDate <= $endDate)
                            @php
                                $dateStr = $currentDate->format('Y-m-d');
                                $isCurrentMonth = $currentDate->month == $month;
                                $isToday = $currentDate->isToday();
                                $isSelected = $selectedDate == $dateStr;
                                $tripCount = $daysWithTrips[$dateStr] ?? 0;
                            @endphp
                            <a href="{{ route('partner.seats', ['month' => $selectedMonth, 'date' => $dateStr]) }}" 
                               class="calendar-day {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }} {{ $isSelected ? 'selected' : '' }}"
                               title="{{ $isCurrentMonth ? $currentDate->format('d/m/Y') : '' }} - {{ $tripCount }} chuyến">
                                <div class="calendar-day-number">{{ $currentDate->day }}</div>
                                @if($tripCount > 0 && $isCurrentMonth)
                                    <div class="calendar-day-badge">{{ $tripCount }}</div>
                                @endif
                            </a>
                            @php $currentDate->addDay(); @endphp
                        @endwhile
                    </div>
                </div>
            </div>
        </div>

        @if($selectedDate)
            <div class="alert alert-info mb-4">
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

        <!-- Radio buttons để chọn số chỗ -->
        <div class="mb-4">
            <div class="btn-group" role="group" aria-label="Số chỗ">
                <input type="radio" class="btn-check" name="soCho" id="cho34" value="34" checked autocomplete="off" onchange="changeSeatType('34')">
                <label class="btn btn-outline-success" for="cho34">
                    <i class="fas fa-bus me-2"></i>Xe 34 chỗ
                </label>

                <input type="radio" class="btn-check" name="soCho" id="cho41" value="41" autocomplete="off" onchange="changeSeatType('41')">
                <label class="btn btn-outline-success" for="cho41">
                    <i class="fas fa-bus me-2"></i>Xe 41 chỗ
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
                Chú thích:
            </h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded me-3 seat-legend seat-empty" style="width: 40px; height: 40px;"></div>
                        <span><strong>Ghế trống</strong> - Có thể đặt vé</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded me-3 seat-legend seat-locked" style="width: 40px; height: 40px; background: #ffc107; border: 2px solid #ff9800;"></div>
                        <span><strong>Ghế khóa</strong> - Đang giữ chỗ</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded me-3 seat-legend seat-booked" style="width: 40px; height: 40px;"></div>
                        <span><strong>Ghế đã đặt</strong> - Đã có khách hàng</span>
                    </div>
                </div>
            </div>
        </div>

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
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    min-width: 500px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.floor-title {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    text-align: center;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}

.seat-grid {
    display: grid;
    gap: 8px;
    margin-bottom: 15px;
}

.seat-item {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
    position: relative;
}

.seat-item:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    z-index: 10;
}

.seat-empty {
    background: #e0e0e0;
    color: #495057;
    border: 2px solid #28a745;
}

.seat-booked {
    background: #dc3545 !important;
    color: white !important;
    border-color: #c82333 !important;
    font-weight: 700 !important;
    opacity: 1 !important;
    cursor: pointer !important;
}

.seat-booked:hover {
    background: #c82333 !important;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.seat-locked {
    background: #ffc107 !important;
    color: #000 !important;
    border-color: #ff9800 !important;
    font-weight: 700 !important;
    cursor: pointer !important;
}

.seat-locked:hover {
    background: #ff9800 !important;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
}

.seat-empty:hover {
    background: #d0d0d0;
}

.driver-seat {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #6c757d;
    color: white;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 20px;
}

.seat-row {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
    margin-bottom: 8px;
}

.aisle {
    width: 30px;
}

@media (max-width: 768px) {
    .floor-container {
        min-width: 100%;
    }
    .seat-item {
        width: 40px;
        height: 40px;
        font-size: 10px;
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
    '41': {
        floor1: [
            // Hàng 1: A1 (phải), B1 (giữa), C1 (trái)
            [['C1'], ['B1'], ['A1']],
            // Hàng 2: A2 (phải), B2 (giữa), C2 (trái)
            [['C2'], ['B2'], ['A2']],
            // Hàng 3: A3 (phải), B3 (giữa), C3 (trái)
            [['C3'], ['B3'], ['A3']],
            // Hàng 4: A4 (phải), B4 (giữa), C4 (trái)
            [['C4'], ['B4'], ['A4']],
            // Hàng 5: A5 (phải), B5 (giữa), C5 (trái)
            [['C5'], ['B5'], ['A5']],
            // Hàng 6: A6 (phải), B6 (giữa), C6 (trái)
            [['C6'], ['B6'], ['A6']],
            // Hàng cuối: 5 ghế A7
            [['A7', 'A7', 'A7', 'A7', 'A7']]
        ],
        floor2: [
            // Hàng 1: AA1 (phải), BB1 (giữa), CC1 (trái)
            [['CC1'], ['BB1'], ['AA1']],
            // Hàng 2: AA2 (phải), BB2 (giữa), CC2 (trái)
            [['CC2'], ['BB2'], ['AA2']],
            // Hàng 3: AA3 (phải), BB3 (giữa), CC3 (trái)
            [['CC3'], ['BB3'], ['AA3']],
            // Hàng 4: AA4 (phải), BB4 (giữa), CC4 (trái)
            [['CC4'], ['BB4'], ['AA4']],
            // Hàng 5: AA5 (phải), BB5 (giữa), CC5 (trái)
            [['CC5'], ['BB5'], ['AA5']],
            // Hàng 6: AA6 (phải), BB6 (giữa), CC6 (trái)
            [['CC6'], ['BB6'], ['AA6']],
            // Hàng cuối: 5 ghế AA7
            [['AA7', 'AA7', 'AA7', 'AA7', 'AA7']]
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
    console.log('showSeatActions - veInfo:', veInfo);
    
    let modalHeaderClass = 'bg-secondary';
    let modalTitle = '';
    let modalBody = '';
    let modalFooter = '';
    
    if (!isEmpty && veInfo) {
        // Ghế đã đặt - hiển thị thông tin khách hàng và các thao tác
        modalHeaderClass = 'bg-danger text-white';
        modalTitle = `<i class="fas fa-user-check me-2"></i>Ghế ${soGhe} - Đã đặt`;
        
        modalBody = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Trạng thái:</strong> <span class="badge bg-danger">Đã đặt</span>
            </div>
            <h6 class="mb-3"><i class="fas fa-user me-2"></i>Thông tin khách hàng:</h6>
            <div class="row mb-3">
                <div class="col-md-4"><strong><i class="fas fa-user me-1"></i>Tên khách:</strong></div>
                <div class="col-md-8">${veInfo.hoTen || '---'}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong><i class="fas fa-phone me-1"></i>SĐT khách:</strong></div>
                <div class="col-md-8">${veInfo.sdt || '---'}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong><i class="fas fa-info-circle me-1"></i>Trạng thái vé:</strong></div>
                <div class="col-md-8">
                    <span class="badge bg-${veInfo.trangThai === 'Đã thanh toán' || veInfo.trangThai === 'da_dat' ? 'success' : 'warning'}">
                        ${veInfo.trangThai || '---'}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong><i class="fas fa-credit-card me-1"></i>Phương thức thanh toán:</strong></div>
                <div class="col-md-8">
                    <span class="badge bg-${veInfo.phuongThuc && veInfo.phuongThuc !== 'Chưa thanh toán' ? 'primary' : 'secondary'}">
                        ${veInfo.phuongThuc || 'Chưa thanh toán'}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong><i class="fas fa-calendar me-1"></i>Ngày đặt:</strong></div>
                <div class="col-md-8">${veInfo.ngayDat || '---'}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong><i class="fas fa-ticket-alt me-1"></i>Mã vé:</strong></div>
                <div class="col-md-8"><span class="badge bg-info">#${veInfo.maVe || '---'}</span></div>
            </div>
        `;
        
        modalFooter = `
            <form method="POST" action="{{ url('/partner/seats/cancel-ticket') }}/${veInfo.maVe}" 
                  style="display: inline-block;" 
                  onsubmit="return confirm('Bạn có chắc chắn muốn hủy vé #${veInfo.maVe}? Hành động này sẽ cập nhật trạng thái vé và ghế.');">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times-circle me-1"></i>Hủy vé
                </button>
            </form>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i>Đóng
            </button>
        `;
    } else if (isLocked && maGhe) {
        // Ghế đã khóa - hiển thị thông tin và nút mở khóa
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
    } else if (isEmpty && maGhe) {
        // Ghế trống - hiển thị thông tin và nút khóa
        modalHeaderClass = 'bg-success text-white';
        modalTitle = `<i class="fas fa-check-circle me-2"></i>Ghế ${soGhe} - Trống`;
        
        modalBody = `
            <div class="alert alert-success">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Trạng thái:</strong> <span class="badge bg-success">Trống</span>
            </div>
            <p class="mb-0">Ghế này đang trống và có thể đặt vé. Bạn có thể khóa ghế để giữ chỗ.</p>
        `;
        
        modalFooter = `
            <form method="POST" action="{{ url('/partner/seats/lock') }}/${maGhe}" 
                  style="display: inline-block;" 
                  onsubmit="return lockSeatAjax(event, ${maGhe});">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-lock me-1"></i>Khóa ghế
                </button>
            </form>
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

// Hàm helper để render một ghế
function renderSeat(soGhe) {
    const seatInfo = getSeatStatus(soGhe);
    let seatClass = 'seat-empty';
    if (seatInfo.isLocked) {
        seatClass = 'seat-locked';
    } else if (!seatInfo.isEmpty) {
        seatClass = 'seat-booked';
    }
    const displayName = soGhe.replace(/T[12]/i, '');
    
    // Tất cả ghế đều có thể click để xem thông tin và thao tác
    // Escape JSON để truyền vào onclick
    const seatInfoJson = JSON.stringify(seatInfo).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    
    if (!seatInfo.isEmpty && seatInfo.veInfo) {
        // Ghế đã đặt
        return `<div class="seat-item ${seatClass}" 
                     title="Ghế ${soGhe} - Đã đặt - Click để xem thông tin và thao tác"
                     onclick="showSeatActions('${soGhe}', '${seatInfoJson.replace(/'/g, "\\'")}')"
                     style="cursor: pointer !important;">
                     ${displayName}
                 </div>`;
    } else if (seatInfo.isLocked && seatInfo.maGhe) {
        // Ghế khóa
        return `<div class="seat-item ${seatClass}" 
                     title="Ghế ${soGhe} - Đã khóa - Click để mở khóa"
                     onclick="showSeatActions('${soGhe}', '${seatInfoJson.replace(/'/g, "\\'")}')"
                     style="cursor: pointer !important;">
                     <i class="fas fa-lock"></i> ${displayName}
                 </div>`;
    } else if (seatInfo.isEmpty && seatInfo.maGhe) {
        // Ghế trống
        return `<div class="seat-item ${seatClass}" 
                     title="Ghế ${soGhe} - Trống - Click để khóa"
                     onclick="showSeatActions('${soGhe}', '${seatInfoJson.replace(/'/g, "\\'")}')"
                     style="cursor: pointer !important;">
                     ${displayName}
                 </div>`;
    } else {
        // Trường hợp khác
        return `<div class="seat-item ${seatClass}" 
                     title="Ghế ${soGhe} - ${seatInfo.status} - Click để xem thông tin"
                     onclick="showSeatActions('${soGhe}', '${seatInfoJson.replace(/'/g, "\\'")}')"
                     style="cursor: pointer !important;">
                     ${displayName}
                 </div>`;
    }
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

function renderSeatMap(type) {
    const layout = seatLayouts[type];
    const container = document.getElementById('seatMapContainer');
    
    let html = '<div class="seat-map-wrapper">';
    
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
    
    html += '</div>';
    
    container.innerHTML = html;
    
    // Cập nhật thống kê
    updateStats(type);
}

function updateStats(type) {
    const layout = seatLayouts[type];
    let trong = 0;
    let daDat = 0;
    let khoa = 0;
    
    [...layout.floor1, ...layout.floor2].forEach(row => {
        row.forEach(column => {
            if (Array.isArray(column) && column.length > 0) {
                column.forEach(soGhe => {
                    const seatInfo = getSeatStatus(soGhe);
                    if (seatInfo.isLocked) {
                        khoa++;
                    } else if (seatInfo.isEmpty) {
                        trong++;
                    } else {
                        daDat++;
                    }
                });
            }
        });
    });
    
    document.getElementById('trongCount').textContent = trong;
    document.getElementById('daDatCount').textContent = daDat;
    document.getElementById('khoaCount').textContent = khoa;
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
    renderSeatMap(type);
    updateStatsBySeatType(type);
    updateBusInfo(type);
}

function updateBusInfo(type) {
    const busInfoElement = document.getElementById('busInfo');
    if (!busInfoElement) return;
    
    let busInfo = '---';
    const chuyenData = type === '34' ? chuyen34Data : chuyen41Data;
    
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
        totalSeats = tongGhe41;
        bookedSeats = soGheDaDat41;
        emptySeats = tongGhe41 - soGheDaDat41;
    }
    
    // Cập nhật số liệu thống kê
    document.getElementById('trongCount').textContent = Math.max(0, emptySeats);
    document.getElementById('daDatCount').textContent = bookedSeats;
    document.getElementById('totalCount').textContent = totalSeats;
}

// Khởi tạo với loại xe mặc định
document.addEventListener('DOMContentLoaded', function() {
    renderSeatMap('34');
    updateStatsBySeatType('34');
    updateBusInfo('34');
});
</script>

<style>
.calendar-container {
    max-width: 100%;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    margin-bottom: 10px;
}

.calendar-weekday {
    text-align: center;
    font-weight: 700;
    color: #4FB99F;
    padding: 10px;
    background: white;
    border-radius: 8px;
    font-size: 14px;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s;
    position: relative;
    padding: 5px;
    border: 2px solid transparent;
}

.calendar-day:hover {
    background: #e9ecef;
    transform: scale(1.05);
    border-color: #4FB99F;
    z-index: 10;
}

.calendar-day.other-month {
    color: #adb5bd;
    background: #f8f9fa;
}

.calendar-day.today {
    background: #fff3cd;
    border-color: #ffc107;
    font-weight: 700;
}

.calendar-day.selected {
    background: #4FB99F;
    color: white;
    font-weight: 700;
    border-color: #3a8f7a;
}

.calendar-day-number {
    font-size: 16px;
    font-weight: 600;
}

.calendar-day-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
}

.calendar-day.selected .calendar-day-badge {
    background: white;
    color: #4FB99F;
}

@media (max-width: 768px) {
    .calendar-weekday,
    .calendar-day-number {
        font-size: 12px;
    }
    
    .calendar-day-badge {
        width: 16px;
        height: 16px;
        font-size: 9px;
    }
}
</style>
@endsection
