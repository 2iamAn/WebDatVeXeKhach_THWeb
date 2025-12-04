@extends('layouts.app')

@section('title', 'Đặt vé')

@section('content')
@php
    // Tính toán biến coGheTrong trước khi sử dụng
    $coGheTrong = ($soGheTrong ?? 0) > 0;
@endphp

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Đặt vé xe</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(!$user)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Bạn cần <a href="{{ route('login.form') }}" class="alert-link">đăng nhập</a> để đặt vé.
                        </div>
                    @endif

                    <!-- Thông tin chuyến xe -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h5 class="mb-3">Thông tin chuyến xe</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nhà xe:</strong> {{ $chuyen->nhaXe->TenNhaXe ?? '---' }}</p>
                                <p><strong>Điểm đi:</strong> {{ $chuyen->tuyenDuong->DiemDi ?? '---' }}</p>
                                <p><strong>Điểm đến:</strong> {{ $chuyen->tuyenDuong->DiemDen ?? '---' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Giờ khởi hành:</strong> {{ $chuyen->GioKhoiHanh ? \Carbon\Carbon::parse($chuyen->GioKhoiHanh)->format('d/m/Y H:i') : '---' }}</p>
                                <p><strong>Giờ đến:</strong> {{ $chuyen->GioDen ? \Carbon\Carbon::parse($chuyen->GioDen)->format('d/m/Y H:i') : '---' }}</p>
                                <p><strong>Giá vé:</strong> <span class="text-danger fw-bold">{{ number_format($chuyen->GiaVe, 0, ',', '.') }} VND</span></p>
                            </div>
                        </div>
                    </div>

                    @if($user)
                        <form method="POST" action="{{ route('datve.store') }}">
                            @csrf
                            <input type="hidden" name="MaChuyenXe" value="{{ $chuyen->MaChuyenXe }}">
                            <input type="hidden" name="MaNguoiDung" value="{{ $user->MaNguoiDung }}">

                            <div class="mb-4">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-couch me-2"></i>Chọn ghế <span class="text-danger">*</span>
                                </label>
                                
                                @if($coGheTrong && $gheTrong->count() > 0)
                                    <!-- Chọn số lượng ghế -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Số lượng ghế <span class="text-danger">*</span></label>
                                        <div style="max-width: 200px;">
                                            <input
                                                id="soLuongGhe"
                                                type="number"
                                                class="form-control"
                                                value="1"
                                                min="1"
                                                max="{{ min(10, $soGheTrong) }}"
                                                oninput="if (this.value < 1) this.value = 1; if (this.value > {{ min(10, $soGheTrong) }}) this.value = {{ min(10, $soGheTrong) }}; updateSeatSelection();"
                                            >
                                        </div>
                                        <small class="text-muted">Bạn có thể chọn tối đa {{ min(10, $soGheTrong) }} ghế</small>
                                    </div>
                                    
                                    <!-- Input hidden để lưu các MaGhe được chọn (sẽ được tạo động khi submit) -->
                                    <div id="selectedSeatIdsContainer"></div>
                                    
                                    <!-- Thông tin ghế đã chọn -->
                                    <div id="selectedSeatInfo" class="alert alert-info mb-3" style="display: none;">
                                        <i class="fas fa-check-circle me-2"></i>    
                                        Bạn đã chọn: <strong id="selectedSeatNames"></strong>
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
                                    <div id="seatMapContainer" class="mb-3"></div>
                                    
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Còn {{ $gheTrong->count() }} ghế trống
                                        @if(isset($tongGhe) && $tongGhe > 0)
                                            ({{ $soGheTrong }}/{{ $tongGhe }})
                                        @endif
                                    </small>
                                    
                                    <!-- Chú thích -->
                                    <div class="d-flex gap-3 flex-wrap mt-2">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded me-2" style="width: 30px; height: 30px; background: #e0e0e0; border: 2px solid #28a745;"></div>
                                            <small>Ghế trống</small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded me-2" style="width: 30px; height: 30px; background: #ff6b6b; border: 2px solid #ee5a52;"></div>
                                            <small>Ghế đã đặt</small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded me-2" style="width: 30px; height: 30px; background: #4FB99F; border: 3px solid #3a8f7a;"></div>
                                            <small>Ghế đã chọn</small>
                                        </div>
                                    </div>
                                @elseif($coGheTrong && $gheTrong->count() == 0)
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Chuyến xe này còn {{ $soGheTrong }} ghế trống nhưng chưa có ghế được thiết lập trong hệ thống. Vui lòng liên hệ nhà xe để được hỗ trợ.
                                    </div>
                                @else
                                    <div class="alert alert-danger">
                                        <i class="fas fa-times-circle me-2"></i>
                                        @if(isset($tongGhe) && $tongGhe == 0)
                                            Chuyến xe này chưa có ghế được thiết lập. Vui lòng liên hệ nhà xe.
                                        @else
                                            Không còn ghế trống cho chuyến này ({{ $soGheDaDat ?? 0 }}/{{ $tongGhe ?? 0 }} đã đặt).
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Tổng tiền -->
                            <div class="alert alert-info mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-money-bill-wave me-2"></i><strong>Tổng tiền:</strong></span>
                                    <span class="fs-4 fw-bold text-danger" id="totalPrice">{{ number_format($chuyen->GiaVe, 0, ',', '.') }} VND</span>
                                </div>
                                <small class="text-muted">
                                    <span id="priceDetail">1 ghế × {{ number_format($chuyen->GiaVe, 0, ',', '.') }} VND</span>
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                                </a>
                                @if($coGheTrong && $gheTrong->count() > 0)
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    @else
                        <div class="text-center">
                            <a href="{{ route('login.form') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập để đặt vé
                            </a>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($coGheTrong && $gheTrong->count() > 0)
@php
    // Chuẩn bảo dữ liệu ghế cho JavaScript - CHỈ tính ghế từ vé đã thanh toán thành công
    // Đảm bảo $gheDaDat chỉ chứa ghế từ vé đã thanh toán (đã được verify trong controller)
    $gheDaDatInt = array_map('intval', $gheDaDat ?? []);
    
    // Kiểm tra lại để đảm bảo chỉ có ghế từ vé đã thanh toán
    // (Double check trong view để chắc chắn)
    $gheDaDatVerified = \App\Models\VeXe::where('MaChuyenXe', $chuyen->MaChuyenXe ?? 0)
        ->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
        ->whereHas('thanhToan', function($q) {
            $q->where('TrangThai', 'Success');
        })
        ->pluck('MaGhe')
        ->map(fn($id) => (int)$id)
        ->unique()
        ->toArray();
    
    // Sử dụng dữ liệu đã verify (BỎ QUA $gheDaDat từ controller, lấy trực tiếp từ DB)
    $gheDaDatInt = array_map('intval', $gheDaDatVerified);
    
    $gheDataArray = ($tatCaGhe ?? collect())->map(function($g) use ($gheDaDatInt) {
        $maGheInt = (int)$g->MaGhe;
        // CHỈ đánh dấu là "đã đặt" nếu ghế có trong danh sách vé đã thanh toán thành công
        $isBooked = in_array($maGheInt, $gheDaDatInt, true); // Sử dụng strict comparison
        return [
            'maGhe' => $maGheInt,
            'soGhe' => $g->SoGhe,
            'trangThai' => $isBooked ? 'Đã đặt' : 'Trống',
            'isBooked' => $isBooked,
        ];
    })->values()->toArray();
    
    // Debug: Log để kiểm tra
    \Log::info('DatVe Create - Debug Info', [
        'ma_chuyen' => $chuyen->MaChuyenXe ?? null,
        'gheDaDat_from_controller' => count($gheDaDat ?? []),
        'gheDaDat_verified_in_view' => count($gheDaDatVerified),
        'gheDaDatInt' => $gheDaDatInt,
        'tatCaGhe_count' => $tatCaGhe->count() ?? 0,
        'gheDataArray_count' => count($gheDataArray),
        'gheDataArray_booked_count' => count(array_filter($gheDataArray, function($g) { return $g['isBooked']; })),
        'gheDataArray_booked' => array_filter($gheDataArray, function($g) { return $g['isBooked']; })
    ]);
    
    // Xác định loại xe dựa trên số ghế
    $loaiXe = 34; // Mặc định
    if ($chuyen->xe && $chuyen->xe->SoGhe) {
        $soGheXe = $chuyen->xe->SoGhe;
        // Xe thường: 41-42 ghế đều dùng layout 42
        if ($soGheXe == 41 || $soGheXe == 42) {
            $loaiXe = 42;
        } else {
            $loaiXe = $soGheXe;
        }
    } elseif ($tongGhe > 0) {
        // Xe thường: 41-42 ghế đều dùng layout 42
        if ($tongGhe == 41 || $tongGhe == 42) {
            $loaiXe = 42;
        } else {
            $loaiXe = $tongGhe;
        }
    }
@endphp

<style>
.seat-map-wrapper {
    display: flex;
    gap: 30px;
    justify-content: center;
    flex-wrap: wrap;
    margin: 20px 0;
}

.floor-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    justify-content: center;
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

.seat-row {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
    margin-bottom: 8px;
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
    cursor: not-allowed !important;
    opacity: 1 !important;
    font-weight: 700 !important;
    pointer-events: none !important;
}

.seat-selected {
    background: #4FB99F;
    color: white;
    border: 3px solid #3a8f7a;
    box-shadow: 0 0 10px rgba(79, 185, 159, 0.5);
}

.seat-empty:hover {
    background: #d0d0d0;
}

.seat-booked:hover {
    background: #ff5252;
    transform: none;
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
    margin: 0 auto 15px;
    font-size: 20px;
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

<script>
// Dữ liệu ghế từ database
const gheData = @json($gheDataArray);
const loaiXe = {{ $loaiXe }};

// Ghế đã chọn tạm thời (từ session, khi quay lại từ màn thanh toán)
const initialSelectedSeats = @json($selectedSeats ?? []);

// Tạo map để tra cứu nhanh: soGhe -> maGhe
const gheMap = {};
gheData.forEach(g => {
    gheMap[g.soGhe] = {
        maGhe: g.maGhe,
        soGhe: g.soGhe,
        trangThai: g.trangThai,
        isBooked: g.isBooked === true || g.isBooked === 1 || g.isBooked === '1' // Đảm bảo boolean
    };
});

// Debug: Log để kiểm tra
console.log('=== DEBUG SEAT MAP ===');
console.log('gheData (total):', gheData.length);
console.log('gheData (all):', gheData);
const bookedSeats = gheData.filter(g => {
    const isBooked = g.isBooked === true || g.isBooked === 1 || g.isBooked === '1' || g.isBooked === 'true';
    return isBooked;
});
console.log('gheData (booked):', bookedSeats);
console.log('gheMap sample:', Object.keys(gheMap).slice(0, 10).map(k => ({soGhe: k, ...gheMap[k]})));
const bookedInMap = Object.keys(gheMap).filter(k => {
    const g = gheMap[k];
    return g.isBooked === true || g.isBooked === 1 || g.isBooked === '1' || g.isBooked === 'true';
}).map(k => ({soGhe: k, ...gheMap[k]}));
console.log('gheMap (booked seats):', bookedInMap);
console.log('======================');

// Định nghĩa layout ghế
const seatLayouts = {
    '34': {
        // Limousine: 17 ghế tầng trên, 17 ghế tầng dưới
        floor1: [
            [['A03'], ['A02'], ['A01']],
            [['A06'], ['A05'], ['A04']],
            [['A09'], ['A08'], ['A07']],
            [['A12'], ['A11'], ['A10']],
            [['A15'], ['A14'], ['A13']],
            [['A17'], [], ['A16']]
        ],
        floor2: [
            [['B03'], ['B02'], ['B01']],
            [['B06'], ['B05'], ['B04']],
            [['B09'], ['B08'], ['B07']],
            [['B12'], ['B11'], ['B10']],
            [['B15'], ['B14'], ['B13']],
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

// Mảng lưu các ghế đã chọn: [{maGhe, soGhe}, ...]
let selectedSeats = initialSelectedSeats.map(s => ({
    maGhe: s.MaGhe,
    soGhe: s.SoGhe
}));
const giaVe = {{ $chuyen->GiaVe }};

function getSeatInfo(soGhe) {
    const ghe = gheMap[soGhe];
    if (!ghe) {
        return { 
            available: false, 
            maGhe: null, 
            soGhe: soGhe,
            status: 'Không có trong hệ thống',
            isBooked: false
        };
    }
    
    // Đảm bảo isBooked là boolean
    const isBooked = ghe.isBooked === true || ghe.isBooked === 1 || ghe.isBooked === '1' || ghe.isBooked === 'true';
    
    return {
        available: !isBooked,
        maGhe: ghe.maGhe,
        soGhe: ghe.soGhe,
        status: isBooked ? 'Đã đặt' : 'Trống',
        isBooked: isBooked
    };
}

function selectSeat(maGhe, soGhe) {
    const soLuongGhe = parseInt(document.getElementById('soLuongGhe').value);
    
    // Kiểm tra xem ghế đã được chọn chưa
    const index = selectedSeats.findIndex(s => s.maGhe == maGhe);
    const seatElement = document.querySelector(`[data-ma-ghe="${maGhe}"]`);
    
    if (index !== -1) {
        // Bỏ chọn ghế
        selectedSeats.splice(index, 1);
        if (seatElement) {
            seatElement.classList.remove('seat-selected');
            seatElement.classList.add('seat-empty');
        }
    } else {
        // Chọn ghế mới
        if (selectedSeats.length >= soLuongGhe) {
            alert(`Bạn chỉ có thể chọn tối đa ${soLuongGhe} ghế. Vui lòng bỏ chọn một ghế trước.`);
            return;
        }
        
        selectedSeats.push({ maGhe: maGhe, soGhe: soGhe });
        if (seatElement) {
            seatElement.classList.remove('seat-empty');
            seatElement.classList.add('seat-selected');
        }
    }
    
    updateSeatSelection();
}

function updateSeatSelection() {
    const soLuongGhe = parseInt(document.getElementById('soLuongGhe').value);
    
    // Nếu số ghế đã chọn nhiều hơn số lượng mới, bỏ chọn các ghế thừa
    while (selectedSeats.length > soLuongGhe) {
        const removed = selectedSeats.pop();
        const seatElement = document.querySelector(`[data-ma-ghe="${removed.maGhe}"]`);
        if (seatElement) {
            seatElement.classList.remove('seat-selected');
            seatElement.classList.add('seat-empty');
        }
    }
    
    // Cập nhật input hidden (không cần thiết nữa vì sẽ tạo khi submit)
    // const maGheArray = selectedSeats.map(s => s.maGhe);
    // document.getElementById('selectedSeatIds').value = JSON.stringify(maGheArray);
    
    // Cập nhật hiển thị
    if (selectedSeats.length > 0) {
        const seatNames = selectedSeats.map(s => 'Ghế ' + s.soGhe).join(', ');
        document.getElementById('selectedSeatInfo').style.display = 'block';
        document.getElementById('selectedSeatNames').textContent = seatNames;
    } else {
        document.getElementById('selectedSeatInfo').style.display = 'none';
    }
    
    // Cập nhật tổng tiền
    const soGheDaChon = selectedSeats.length;
    const tongTien = soGheDaChon * giaVe;
    document.getElementById('totalPrice').textContent = tongTien.toLocaleString('vi-VN') + ' VND';
    document.getElementById('priceDetail').textContent = 
        soGheDaChon + ' ghế × ' + giaVe.toLocaleString('vi-VN') + ' VND';
}

function isSeatSelected(maGhe) {
    return selectedSeats.some(s => s.maGhe == maGhe);
}

// Biến lưu tầng hiện tại (1 hoặc 2)
let currentFloor = 1;

function switchFloor(floor) {
    currentFloor = floor;
    renderSeatMap();
}

function renderSeatMap() {
    // Xe 41-42 ghế đều dùng layout 42
    let layoutKey = loaiXe.toString();
    if (layoutKey === '41' || layoutKey === '42') {
        layoutKey = '42'; // Dùng layout 42 cho cả xe 41 và 42 ghế
    }
    const layout = seatLayouts[layoutKey] || seatLayouts['34'];
    const container = document.getElementById('seatMapContainer');
    
    // Debug: Log để kiểm tra
    console.log('=== SEAT MAP DEBUG ===');
    console.log('loaiXe (from server):', loaiXe);
    console.log('layoutKey (used):', layoutKey);
    console.log('currentFloor:', currentFloor);
    console.log('======================');
    
    let html = '<div class="seat-map-wrapper">';
    
    // Chỉ hiển thị tầng được chọn
    if (currentFloor === 1) {
        // Tầng 1
        html += '<div class="floor-container">';
        html += '<div class="floor-title"><i class="fas fa-layer-group me-2"></i>Tầng 1</div>';
        html += '<div class="driver-seat"><i class="fas fa-steering-wheel"></i></div>';
        
        layout.floor1.forEach(row => {
            html += '<div class="seat-row">';
            row.forEach((column, colIndex) => {
                if (column && column.length > 0) {
                    column.forEach(soGhe => {
                        const seatInfo = getSeatInfo(soGhe);
                        if (seatInfo.maGhe) {
                            // Có ghế trong hệ thống
                            const alreadySelected = isSeatSelected(seatInfo.maGhe);
                            if (seatInfo.isBooked === true || seatInfo.isBooked === 1) {
                                // Ghế đã đặt - hiển thị màu đỏ, không thể click
                                html += `<div class="seat-item seat-booked" 
                                             data-ma-ghe="${seatInfo.maGhe}" 
                                             data-so-ghe="${seatInfo.soGhe}"
                                             data-is-booked="true"
                                             title="Ghế ${seatInfo.soGhe} - ${seatInfo.status}"
                                             style="cursor: not-allowed !important; background: #dc3545 !important; color: white !important; border-color: #c82333 !important;">
                                             ${seatInfo.soGhe}
                                         </div>`;
                            } else {
                                // Ghế trống - có thể chọn (hoặc đang được chọn lại từ session)
                                const seatClass = alreadySelected ? 'seat-item seat-selected' : 'seat-item seat-empty';
                                const onClick = `onclick="selectSeat('${seatInfo.maGhe}', '${seatInfo.soGhe}')"`
                                html += `<div class="${seatClass}" 
                                             data-ma-ghe="${seatInfo.maGhe}" 
                                             data-so-ghe="${seatInfo.soGhe}"
                                             ${onClick}
                                             title="Ghế ${seatInfo.soGhe} - ${seatInfo.status}">
                                             ${seatInfo.soGhe}
                                         </div>`;
                            }
                        } else {
                            // Không có ghế trong hệ thống - hiển thị giống ghế trống
                            html += `<div class="seat-item seat-empty" 
                                     title="Ghế ${soGhe} - Trống"
                                     onclick="alert('Ghế ${soGhe} chưa được thiết lập trong hệ thống. Vui lòng liên hệ nhà xe.')"
                                     style="cursor: pointer;">
                                     ${soGhe}
                                 </div>`;
                        }
                    });
                }
                if (colIndex < row.length - 1) {
                    html += '<div class="aisle"></div>';
                }
            });
            html += '</div>';
        });
        html += '</div>';
    } else {
        // Tầng 2
        html += '<div class="floor-container">';
        html += '<div class="floor-title"><i class="fas fa-layer-group me-2"></i>Tầng 2</div>';
        
        layout.floor2.forEach(row => {
            html += '<div class="seat-row">';
            row.forEach((column, colIndex) => {
                if (column && column.length > 0) {
                    column.forEach(soGhe => {
                        const seatInfo = getSeatInfo(soGhe);
                        if (seatInfo.maGhe) {
                            // Có ghế trong hệ thống
                            const alreadySelected = isSeatSelected(seatInfo.maGhe);
                            if (seatInfo.isBooked === true || seatInfo.isBooked === 1) {
                                // Ghế đã đặt - hiển thị màu đỏ, không thể click
                                html += `<div class="seat-item seat-booked" 
                                             data-ma-ghe="${seatInfo.maGhe}" 
                                             data-so-ghe="${seatInfo.soGhe}"
                                             data-is-booked="true"
                                             title="Ghế ${seatInfo.soGhe} - ${seatInfo.status}"
                                             style="cursor: not-allowed !important; background: #dc3545 !important; color: white !important; border-color: #c82333 !important;">
                                             ${seatInfo.soGhe}
                                         </div>`;
                            } else {
                                // Ghế trống - có thể chọn (hoặc đang được chọn lại từ session)
                                const seatClass = alreadySelected ? 'seat-item seat-selected' : 'seat-item seat-empty';
                                const onClick = `onclick="selectSeat('${seatInfo.maGhe}', '${seatInfo.soGhe}')"`
                                html += `<div class="${seatClass}" 
                                             data-ma-ghe="${seatInfo.maGhe}" 
                                             data-so-ghe="${seatInfo.soGhe}"
                                             ${onClick}
                                             title="Ghế ${seatInfo.soGhe} - ${seatInfo.status}">
                                             ${seatInfo.soGhe}
                                         </div>`;
                            }
                        } else {
                            // Không có ghế trong hệ thống - hiển thị giống ghế trống
                            html += `<div class="seat-item seat-empty" 
                                     title="Ghế ${soGhe} - Trống"
                                     onclick="alert('Ghế ${soGhe} chưa được thiết lập trong hệ thống. Vui lòng liên hệ nhà xe.')"
                                     style="cursor: pointer;">
                                     ${soGhe}
                                 </div>`;
                        }
                    });
                }
                if (colIndex < row.length - 1) {
                    html += '<div class="aisle"></div>';
                }
            });
            html += '</div>';
        });
        html += '</div>';
    }
    
    html += '</div>';
    
    container.innerHTML = html;
}

// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', function() {
    renderSeatMap();
    // Nếu có ghế đã chọn từ trước, cập nhật lại summary và tổng tiền
    if (selectedSeats.length > 0) {
        updateSeatSelection();
    }
    
    // Validate form: yêu cầu chọn đủ số ghế trước khi submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const soLuongGhe = parseInt(document.getElementById('soLuongGhe').value);
            
            // Kiểm tra số ghế đã chọn
            if (selectedSeats.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất 1 ghế trước khi đặt vé!');
                return false;
            }
            
            if (selectedSeats.length !== soLuongGhe) {
                e.preventDefault();
                alert(`Vui lòng chọn đủ ${soLuongGhe} ghế trước khi đặt vé! (Đã chọn: ${selectedSeats.length})`);
                return false;
            }
            
            // Xóa các input hidden cũ (nếu có)
            const container = document.getElementById('selectedSeatIdsContainer');
            container.innerHTML = '';
            
            // Tạo các input hidden riêng biệt cho mỗi ghế
            const maGheArray = selectedSeats.map(s => s.maGhe);
            maGheArray.forEach((maGhe) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'MaGhe[]';
                hiddenInput.value = maGhe;
                hiddenInput.required = true;
                container.appendChild(hiddenInput);
            });
            
            console.log('Submitting with seats:', maGheArray);
        });
    }
});
</script>
@endif

@endsection

