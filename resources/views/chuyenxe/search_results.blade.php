@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<style>
    .search-results-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 30px 0;
    }
    
    /* Header Section */
    .search-header {
        background: white;
        border-radius: 15px;
        padding: 25px 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .search-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .search-info {
        flex: 1;
    }
    
    .search-route {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .search-details {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        color: #6c757d;
        font-size: 14px;
    }
    
    .search-details-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-change-search {
        background: linear-gradient(135deg, rgb(16, 160, 110) 0%, rgb(18, 187, 150) 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-change-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 160, 110, 0.4);
        color: white;
    }
    
    /* Filter Panel */
    .filter-panel {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        position: sticky;
        top: 20px;
    }
    
    .filter-title {
        font-weight: 700;
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .filter-section {
        margin-bottom: 25px;
    }
    
    .filter-section-title {
        font-weight: 600;
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-checkbox {
        display: flex;
        align-items: center;
        padding: 10px 0;
        cursor: pointer;
    }
    
    .filter-checkbox input[type="checkbox"] {
        margin-right: 10px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .filter-checkbox label {
        cursor: pointer;
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .filter-badge {
        background: #e9ecef;
        color: #6c757d;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .filter-btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .filter-btn {
        padding: 8px 16px;
        border: 2px solid #e9ecef;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
        font-weight: 500;
    }
    
    .filter-btn:hover {
        border-color: rgb(16, 160, 110);
        color: rgb(16, 160, 110);
    }
    
    .filter-btn.active {
        background: rgb(16, 160, 110);
        color: white;
        border-color: rgb(16, 160, 110);
    }
    
    .btn-clear-filter {
        background: #e9ecef;
        color: #6c757d;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
    }
    
    /* Collapsible Filter Section */
    .filter-collapsible {
        cursor: pointer;
        user-select: none;
    }
    
    .filter-collapsible-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        font-weight: 600;
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-collapsible-header:hover {
        color: rgb(16, 160, 110);
    }
    
    .filter-collapsible-arrow {
        transition: transform 0.3s ease;
        font-size: 12px;
        color: #6c757d;
    }
    
    .filter-collapsible-arrow.open {
        transform: rotate(180deg);
    }
    
    .filter-collapsible-body {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s ease;
        padding: 0;
    }
    
    .filter-collapsible-body.open {
        max-height: 350px;
        padding: 10px 0;
        overflow-y: auto;
    }
    
    .filter-collapsible-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .filter-collapsible-body::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }
    
    .filter-collapsible-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .filter-checkbox-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .filter-checkbox-item:hover {
        padding-left: 5px;
    }
    
    .filter-checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: rgb(16, 160, 110);
    }
    
    .filter-checkbox-item span {
        font-size: 14px;
        color: #2c3e50;
        flex: 1;
    }
    
    /* Results Section */
    .results-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .results-count {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .sort-dropdown {
        position: relative;
    }
    
    .sort-btn {
        background: white;
        border: 2px solid #e9ecef;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Bus Card */
    .bus-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s;
    }
    
    .bus-card:hover {
        border-color: rgb(16, 160, 110);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .bus-card-header {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .bus-image {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 48px;
        flex-shrink: 0;
    }
    
    .bus-info {
        flex: 1;
    }
    
    .bus-name {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .bus-price {
        font-size: 20px;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 8px;
    }
    
    .bus-type {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 8px;
    }
    
    .bus-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #ffa726;
        font-weight: 600;
    }
    
    .bus-schedule {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 20px;
        margin-bottom: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .schedule-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .schedule-time {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .schedule-location {
        font-size: 14px;
        color: #6c757d;
    }
    
    .schedule-duration {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 14px;
    }
    
    .schedule-duration i {
        font-size: 24px;
        margin-bottom: 5px;
    }
    
    .bus-amenities {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .bus-info-main {
        margin-bottom: 20px;
    }
    
    .bus-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        margin-top: 20px;
    }
    
    .bus-price-section {
        flex: 1;
    }
    
    .bus-price {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .bus-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    
    .btn-view-details {
        color: rgb(16, 160, 110);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-view-details:hover {
        color: rgb(18, 187, 150);
    }
    
    .btn-info-toggle {
        background: white;
        color: #3498db;
        border: 2px solid #3498db;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-right: 10px;
    }
    
    .btn-info-toggle:hover {
        background: #3498db;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
    }
    
    .btn-info-toggle i {
        transition: transform 0.3s ease;
        font-size: 14px;
    }
    
    .btn-book-now {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-book-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        color: white;
    }
    
    /* No Results */
    .no-results {
        background: white;
        border-radius: 15px;
        padding: 80px 20px;
        text-align: center;
        min-height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .no-results-icon {
        width: 200px;
        height: 200px;
        margin: 0 auto 30px;
        position: relative;
    }
    
    .no-results h4 {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .no-results p {
        color: #6c757d;
        font-size: 16px;
        margin-bottom: 30px;
    }
    
    /* Bus Tabs */
    .bus-info-container {
        border-top: 2px solid #e9ecef;
        margin-top: 15px;
        padding-top: 10px;
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .bus-tabs {
        display: flex;
        gap: 0;
        margin-top: 20px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .bus-tab {
        background: none;
        border: none;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        position: relative;
        top: 2px;
    }
    
    .bus-tab:hover {
        color: rgb(16, 160, 110);
    }
    
    .bus-tab.active {
        color: rgb(16, 160, 110);
        border-bottom-color: rgb(16, 160, 110);
        font-weight: 600;
    }
    
    .bus-tab-content {
        padding: 20px 0;
        display: none;
    }
    
    .bus-tab-content.active {
        display: block;
    }
    
    .route-info {
        padding: 10px 0;
    }
    
    .route-details {
        margin-top: 15px;
    }
    
    .route-point {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        font-size: 14px;
    }
    
    .ticket-info {
        padding: 10px 0;
    }
    
    .ticket-info p {
        margin-bottom: 8px;
        font-size: 14px;
    }

</style>

<div class="search-results-container">
    <div class="container">
        <!-- Search Header -->
        <div class="search-header">
            <div class="search-header-content">
                <div class="search-info">
                    <div class="search-route">
                        {{ $request->diem_di }} → {{ $request->diem_den }}
                    </div>
                    <div class="search-details">
                        <div class="search-details-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ \Carbon\Carbon::parse($request->ngay_khoi_hanh)->locale('vi')->isoFormat('dddd, D [tháng] M YYYY') }}</span>
                        </div>
                        <div class="search-details-item">
                            <i class="fas fa-user"></i>
                            <span>{{ $request->so_ghe }} chỗ ngồi</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('welcome') }}" class="btn-change-search">
                    <i class="fas fa-edit me-2"></i>Thay đổi tìm kiếm
                </a>
            </div>
        </div>
        
        <div class="row">
            <!-- Bộ lọc bên trái -->
            <div class="col-md-3">
                <div class="filter-panel">
                    <div class="filter-title">
                        BỘ LỌC TÌM KIẾM
                        <button class="btn-clear-filter" onclick="clearFilters()">
                            <i class="fas fa-trash me-1"></i>Bỏ lọc
                        </button>
                    </div>
                    
                    <form id="filterForm" method="GET" action="{{ route('chuyenxe.search') }}">
                        <input type="hidden" name="diem_di" value="{{ $request->diem_di }}">
                        <input type="hidden" name="diem_den" value="{{ $request->diem_den }}">
                        <input type="hidden" name="ngay_khoi_hanh" value="{{ $request->ngay_khoi_hanh }}">
                        <input type="hidden" name="so_ghe" value="{{ $request->so_ghe }}">
                        <input type="hidden" name="sort" value="{{ $request->sort ?? 'gia_re' }}" id="sortInput">
                        
                        <!-- Chọn điểm lên xe -->
                        <div class="filter-section">
                            <div class="filter-collapsible" onclick="toggleCollapsible('pickupPoints')">
                                <div class="filter-collapsible-header">
                                    <span>Chọn điểm lên xe</span>
                                    <span class="filter-collapsible-arrow" id="arrow-pickupPoints">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="filter-collapsible-body" id="body-pickupPoints">
                                @php
                                    $pickupPoints = [
                                        'Bến xe An Sương',
                                        'Ngã 4 Bình Phước (Cây xăng 47)',
                                        'Bến xe Miền Đông cũ (Dãy 7 - C5)',
                                        'Linh Xuân',
                                        'Ngã 4 Bình Phước',
                                        'Bến xe Miền Đông Cũ - Dãy 4B11',
                                        'VP Tân Bình',
                                        'Văn phòng Thủ Đức',
                                        'Bến xe Miền Đông - Quầy vé 94',
                                        'Bến xe Miền Đông - Quầy vé 37',
                                        'Bến xe Phước An 1',
                                        'Bến xe khách phía nam Buôn Ma Thuột'
                                    ];
                                @endphp
                                @foreach($pickupPoints as $point)
                                    <label class="filter-checkbox-item">
                                        <input type="checkbox" name="diem_len_xe[]" value="{{ $point }}" />
                                        <span>{{ $point }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Giờ đi -->
                        <div class="filter-section">
                            <div class="filter-section-title">Giờ Đi</div>
                            
                            <div class="filter-checkbox">
                                <input type="checkbox" name="gio_di" value="sang_som" id="sang_som" 
                                       {{ $request->gio_di == 'sang_som' ? 'checked' : '' }}
                                       onchange="document.getElementById('filterForm').submit()">
                                <label for="sang_som">
                                    Sáng : 00:00 - 06:00
                                </label>
                            </div>
                            
                            <div class="filter-checkbox">
                                <input type="checkbox" name="gio_di" value="buoi_sang" id="buoi_sang"
                                       {{ $request->gio_di == 'buoi_sang' ? 'checked' : '' }}
                                       onchange="document.getElementById('filterForm').submit()">
                                <label for="buoi_sang">
                                    Sáng : 06:00 - 12:00
                                </label>
                            </div>
                            
                            <div class="filter-checkbox">
                                <input type="checkbox" name="gio_di" value="buoi_chieu" id="buoi_chieu"
                                       {{ $request->gio_di == 'buoi_chieu' ? 'checked' : '' }}
                                       onchange="document.getElementById('filterForm').submit()">
                                <label for="buoi_chieu">
                                    Chiều : 12:00 - 18:00
                                </label>
                            </div>
                            
                            <div class="filter-checkbox">
                                <input type="checkbox" name="gio_di" value="buoi_toi" id="buoi_toi"
                                       {{ $request->gio_di == 'buoi_toi' ? 'checked' : '' }}
                                       onchange="document.getElementById('filterForm').submit()">
                                <label for="buoi_toi">
                                    Chiều : 18:00 - 24:00
                                </label>
                            </div>
                        </div>
                    </form>

                    
                    <!-- Loại xe -->
                    <div class="filter-section">
                        <div class="filter-section-title">Loại Xe</div>
                        <div class="filter-btn-group">
                            <button type="button" class="filter-btn" onclick="toggleFilterBtn(this)">Thường</button>
                            <button type="button" class="filter-btn" onclick="toggleFilterBtn(this)">Limousine</button>
                        </div>
                    </div>
                    
                    <!-- Hàng ghế -->
                    <div class="filter-section">
                        <div class="filter-section-title">Dãy</div>
                        <div class="filter-btn-group">
                            <button type="button" class="filter-btn" onclick="toggleFilterBtn(this)">A</button>
                            <button type="button" class="filter-btn" onclick="toggleFilterBtn(this)">B</button>
                            <button type="button" class="filter-btn" onclick="toggleFilterBtn(this)">C</button>
                        </div>
                    </div>
                    
                    <!-- Tầng -->
                    <div class="filter-section">
                        <div class="filter-section-title">Tầng</div>
                        <div class="filter-btn-group">
                            <button class="filter-btn">Tầng trên</button>
                            <button class="filter-btn">Tầng dưới</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Kết quả bên phải -->
            <div class="col-md-9">
                <div class="results-section">
                    <div class="results-header">
                        <div class="results-count">
                            {{ $request->diem_di }} → {{ $request->diem_den }} ({{ $chuyens->count() }} tuyến)
                        </div>
                    </div>
                    
                    @if($chuyens->count() > 0)
                        @foreach($chuyens as $chuyen)
                            @php
                                $thoiGianHanhTrinh = null;
                                if ($chuyen->GioKhoiHanh && $chuyen->GioDen) {
                                    $start = \Carbon\Carbon::parse($chuyen->GioKhoiHanh);
                                    $end = \Carbon\Carbon::parse($chuyen->GioDen);
                                    $diff = $start->diff($end);
                                    $thoiGianHanhTrinh = $diff->h . ' giờ';
                                    if ($diff->i > 0) {
                                        $thoiGianHanhTrinh .= ' ' . $diff->i . ' phút';
                                    }
                                }
                                $gioDi = \Carbon\Carbon::parse($chuyen->GioKhoiHanh)->format('H:i');
                                $gioDen = $chuyen->GioDen ? \Carbon\Carbon::parse($chuyen->GioDen)->format('H:i') : '---';
                                $ngayDen = $chuyen->GioDen ? \Carbon\Carbon::parse($chuyen->GioDen) : null;
                                $ngayDi = \Carbon\Carbon::parse($chuyen->GioKhoiHanh);
                                $isNextDay = $ngayDen && $ngayDen->format('Y-m-d') != $ngayDi->format('Y-m-d');
                            @endphp
                            
                            <div class="bus-card">
                                <div class="bus-info-main">
                                    <div class="bus-name">
                                        {{ $chuyen->nhaXe->TenNhaXe ?? 'Nhà xe' }}
                                        @if($chuyen->nhaXe && isset($chuyen->nhaXe->total_reviews) && $chuyen->nhaXe->total_reviews > 0)
                                            <span style="color: #f39c12; font-size: 16px; margin-left: 10px;">
                                                <i class="fas fa-star"></i> {{ number_format($chuyen->nhaXe->rating, 1) }}/5
                                            </span>
                                        @endif
                                    </div>
                                    <div class="bus-type">
                                        @if($chuyen->xe)
                                            {{ $chuyen->xe->TenXe }}
                                            @if($chuyen->xe->LoaiXe)
                                                - {{ $chuyen->xe->LoaiXe }}
                                            @endif
                                        @else
                                            Ghế thường - Giường nằm 40 chỗ mới
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="bus-schedule">
                                    <div class="schedule-item">
                                        <div class="schedule-time">{{ $gioDi }}</div>
                                        <div class="schedule-location">{{ $chuyen->DiemLenXe ?? '---' }}</div>
                                    </div>
                                    <div class="schedule-duration">
                                        <i class="fas fa-arrow-right"></i>
                                        <div>{{ $thoiGianHanhTrinh ?? '---' }}</div>
                                    </div>
                                    <div class="schedule-item" style="text-align: right;">
                                        <div class="schedule-time">
                                            {{ $gioDen }}
                                            @if($isNextDay)
                                                <span style="font-size: 12px; color: #6c757d;">(+1ngày)</span>
                                            @endif
                                        </div>
                                        <div class="schedule-location">{{ $chuyen->DiemXuongXe ?? '---' }}</div>
                                    </div>
                                </div>
                                
                                <div class="bus-card-footer">
                                    <div class="bus-price-section">
                                        <div class="bus-price">{{ number_format($chuyen->GiaVe, 0, ',', '.') }} VND/khách</div>
                                    </div>
                                    <div class="bus-actions">
                                        <button onclick="toggleBusInfo({{ $chuyen->MaChuyenXe }})" class="btn-info-toggle">
                                            <span>Thông tin nhà xe</span>
                                            <i class="fas fa-chevron-down" id="info-arrow-{{ $chuyen->MaChuyenXe }}"></i>
                                        </button>
                                        <a href="{{ route('datve.create', ['ma_chuyen' => $chuyen->MaChuyenXe]) }}" class="btn-book-now">
                                            Đặt Ngay
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Tabs - Ẩn mặc định -->
                                <div class="bus-info-container" id="bus-info-{{ $chuyen->MaChuyenXe }}" style="display: none;">
                                    <div class="bus-tabs">
                                        <button class="bus-tab active" onclick="showTab(this, 'info-{{ $chuyen->MaChuyenXe }}')">Thông tin nhà xe</button>
                                        <button class="bus-tab" onclick="showTab(this, 'reviews-{{ $chuyen->MaChuyenXe }}')">Đánh giá</button>
                                        <button class="bus-tab" onclick="showTab(this, 'amenities-{{ $chuyen->MaChuyenXe }}')">Tiện ích</button>
                                        <button class="bus-tab" onclick="showTab(this, 'policy-{{ $chuyen->MaChuyenXe }}')">Chính sách hủy vé</button>
                                        <button class="bus-tab" onclick="showTab(this, 'rules-{{ $chuyen->MaChuyenXe }}')">Quy định nhà xe</button>
                                    </div>
                                
                                <!-- Tab Thông tin nhà xe -->
                                <div class="bus-tab-content active" id="info-{{ $chuyen->MaChuyenXe }}">
                                    <div style="padding: 20px;">
                                        <h6 style="font-weight: 600; margin-bottom: 15px; font-size: 18px;">{{ $chuyen->nhaXe->TenNhaXe ?? 'Nhà xe' }}</h6>
                                        @if($chuyen->nhaXe && $chuyen->nhaXe->total_reviews > 0)
                                            <div style="margin-bottom: 15px;">
                                                <span style="color: #f39c12; font-size: 28px; font-weight: 700;">
                                                    <i class="fas fa-star"></i> {{ number_format($chuyen->nhaXe->rating, 1) }}/5
                                                </span>
                                                <span style="color: #6c757d; margin-left: 10px; font-size: 14px;">
                                                    • {{ $chuyen->nhaXe->total_reviews }} đánh giá
                                                </span>
                                            </div>
                                        @endif
                                        <p style="color: #6c757d; margin-top: 10px; line-height: 1.6;">
                                            {{ $chuyen->nhaXe->MoTa ?? 'Nhà xe chuyên nghiệp với nhiều năm kinh nghiệm trong ngành vận tải hành khách.' }}
                                        </p>
                                        
                                        @if($chuyen->xe)
                                            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                                <h6 style="font-weight: 600; margin-bottom: 10px;">Thông tin xe</h6>
                                                <p style="margin: 5px 0;"><strong>Tên xe:</strong> {{ $chuyen->xe->TenXe }}</p>
                                                <p style="margin: 5px 0;"><strong>Loại xe:</strong> {{ $chuyen->xe->LoaiXe }}</p>
                                                <p style="margin: 5px 0;"><strong>Biển số:</strong> {{ $chuyen->xe->BienSoXe ?? 'N/A' }}</p>
                                                @if($chuyen->xe->SoGiuong > 0)
                                                    <p style="margin: 5px 0;"><strong>Số giường:</strong> {{ $chuyen->xe->SoGiuong }} giường</p>
                                                @else
                                                    <p style="margin: 5px 0;"><strong>Số ghế:</strong> {{ $chuyen->xe->SoGhe }} ghế</p>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($chuyen->nhaXe)
                                            <a href="{{ route('nhaxe.show', $chuyen->nhaXe->MaNhaXe) }}" class="btn btn-sm btn-outline-primary mt-3">
                                                <i class="fas fa-info-circle me-1"></i> Xem chi tiết nhà xe
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Tab Đánh giá -->
                                <div class="bus-tab-content" id="reviews-{{ $chuyen->MaChuyenXe }}" style="display: none;">
                                    <div style="padding: 20px;">
                                        <h6 style="font-weight: 600; margin-bottom: 15px; font-size: 18px;">Đánh giá từ khách hàng</h6>
                                        
                                        @if($chuyen->nhaXe && $chuyen->nhaXe->total_reviews > 0)
                                            <div style="margin-bottom: 20px; text-align: center; padding: 15px; background: #fff3cd; border-radius: 8px;">
                                                <div style="font-size: 48px; font-weight: 700; color: #f39c12;">
                                                    <i class="fas fa-star"></i> {{ number_format($chuyen->nhaXe->rating, 1) }}/5
                                                </div>
                                                <div style="color: #6c757d; margin-top: 5px;">
                                                    {{ $chuyen->nhaXe->total_reviews }} đánh giá
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($chuyen->nhaXe && $chuyen->nhaXe->recent_reviews && count($chuyen->nhaXe->recent_reviews) > 0)
                                            @foreach($chuyen->nhaXe->recent_reviews as $review)
                                                <div style="border-bottom: 1px solid #eee; padding: 15px 0; margin-bottom: 10px;">
                                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #{{ ['e74c3c', '3498db', '2ecc71', 'f39c12', '9b59b6'][rand(0,4)] }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                            {{ strtoupper(substr($review->HoTen ?? 'K', 0, 1)) }}
                                                        </div>
                                                        <div style="flex: 1;">
                                                            <div style="font-weight: 600;">{{ $review->HoTen ?? 'Khách hàng' }}</div>
                                                            <div style="font-size: 12px; color: #999;">{{ \Carbon\Carbon::parse($review->NgayDanhGia)->format('d/m/Y') }}</div>
                                                        </div>
                                                        <div style="color: #f39c12; font-size: 14px;">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->SoSao)
                                                                    <i class="fas fa-star"></i>
                                                                @else
                                                                    <i class="far fa-star"></i>
                                                                @endif
                                                            @endfor
                                                            <span style="margin-left: 5px;">{{ $review->SoSao }}/5</span>
                                                        </div>
                                                    </div>
                                                    @if($review->NoiDung)
                                                        <p style="color: #555; font-size: 14px; margin: 0; line-height: 1.6;">{{ $review->NoiDung }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <a href="{{ route('nhaxe.show', $chuyen->nhaXe->MaNhaXe) }}" class="btn btn-sm btn-outline-primary mt-2">
                                                Xem tất cả {{ $chuyen->nhaXe->total_reviews }} đánh giá
                                            </a>
                                        @else
                                            <p style="color: #6c757d; text-align: center; padding: 20px;">Chưa có đánh giá nào.</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Tab Tiện ích -->
                                <div class="bus-tab-content" id="amenities-{{ $chuyen->MaChuyenXe }}" style="display: none;">
                                    <div style="padding: 20px;">
                                        <h6 style="font-weight: 600; margin-bottom: 20px; font-size: 18px;">Tiện ích trên xe</h6>
                                        
                                        @php
                                            $tienNghi = [];
                                            if ($chuyen->xe && $chuyen->xe->TienNghi) {
                                                $tienNghi = explode('|', $chuyen->xe->TienNghi);
                                            }
                                            
                                            $iconMap = [
                                                'Tivi LED' => 'fa-tv',
                                                'Đèn đọc sách' => 'fa-book-reader',
                                                'Rèm cửa' => 'fa-window-maximize',
                                                'Chăn đắp' => 'fa-bed',
                                                'Nước uống' => 'fa-tint',
                                                'Khăn lạnh' => 'fa-hand-sparkles',
                                                'Búa phá kính' => 'fa-hammer',
                                                'Sạc điện thoại' => 'fa-charging-station',
                                                'Dép' => 'fa-shoe-prints',
                                                'Dây đai an toàn' => 'fa-user-shield',
                                                'Wifi' => 'fa-wifi',
                                                'Điều hòa' => 'fa-snowflake',
                                                'Gối nằm' => 'fa-couch',
                                            ];
                                        @endphp
                                        
                                        <div class="row g-3">
                                            @forelse($tienNghi as $item)
                                                @php
                                                    $parts = explode(':', $item);
                                                    $name = trim($parts[0]);
                                                    $desc = isset($parts[1]) ? trim($parts[1]) : '';
                                                    $icon = $iconMap[$name] ?? 'fa-check-circle';
                                                @endphp
                                                <div class="col-md-6">
                                                    <div style="display: flex; gap: 12px; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                                                        <i class="fas {{ $icon }}" style="color: #ffa726; font-size: 24px; min-width: 24px;"></i>
                                                        <div>
                                                            <strong style="display: block; margin-bottom: 3px;">{{ $name }}</strong>
                                                            @if($desc)
                                                                <small style="color: #6c757d; line-height: 1.4;">{{ $desc }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <p style="color: #6c757d; text-align: center;">Thông tin tiện ích đang được cập nhật.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tab Chính sách hủy vé -->
                                <div class="bus-tab-content" id="policy-{{ $chuyen->MaChuyenXe }}" style="display: none;">
                                    <div style="padding: 20px;">
                                        <h6 style="font-weight: 600; margin-bottom: 20px; font-size: 18px;">Phí hủy</h6>
                                        
                                        @if($chuyen->nhaXe && $chuyen->nhaXe->ChinhSachHuyVe)
                                            @php
                                                $policies = explode('|', $chuyen->nhaXe->ChinhSachHuyVe);
                                            @endphp
                                            <ul style="list-style: none; padding: 0;">
                                                @foreach($policies as $policy)
                                                    @php
                                                        $isFree = stripos($policy, 'miễn phí') !== false || stripos($policy, 'hoàn 100') !== false || stripos($policy, 'hoàn 90') !== false;
                                                        $isPartial = stripos($policy, 'hoàn') !== false && !$isFree;
                                                        $isPaid = stripos($policy, 'không hoàn') !== false;
                                                        $color = $isFree ? '#27ae60' : ($isPaid ? '#e74c3c' : '#f39c12');
                                                        $bgColor = $isFree ? '#d4edda' : ($isPaid ? '#f8d7da' : '#fff3cd');
                                                    @endphp
                                                    <li style="padding: 12px; margin-bottom: 10px; background: {{ $bgColor }}; border-left: 4px solid {{ $color }}; border-radius: 4px;">
                                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                                            <span style="font-weight: 500;">
                                                                @if(strpos($policy, ':') !== false)
                                                                    {{ explode(':', $policy)[0] }}
                                                                @else
                                                                    {{ $policy }}
                                                                @endif
                                                            </span>
                                                            <span style="color: {{ $color }}; font-weight: 600;">
                                                                @if(strpos($policy, ':') !== false)
                                                                    {{ trim(explode(':', $policy)[1]) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div style="color: #6c757d; line-height: 1.8;">
                                                <p style="padding: 12px; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 4px; margin-bottom: 10px;">
                                                    <i class="fas fa-check-circle"></i> <strong>Hủy vé trước 24 giờ:</strong> Hoàn 90% giá trị vé
                                                </p>
                                                <p style="padding: 12px; background: #fff3cd; border-left: 4px solid #f39c12; border-radius: 4px; margin-bottom: 10px;">
                                                    <i class="fas fa-exclamation-circle"></i> <strong>Hủy vé trước 12 giờ:</strong> Hoàn 70% giá trị vé
                                                </p>
                                                <p style="padding: 12px; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 4px;">
                                                    <i class="fas fa-times-circle"></i> <strong>Hủy vé trong vòng 12 giờ:</strong> Không hoàn tiền
                                                </p>
                                                <p style="margin-top: 15px; font-size: 13px; color: #999;">
                                                    * Thời gian tính từ giờ khởi hành của chuyến xe
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Tab Quy định nhà xe -->
                                <div class="bus-tab-content" id="rules-{{ $chuyen->MaChuyenXe }}" style="display: none;">
                                    <div style="padding: 20px;">
                                        @if($chuyen->nhaXe && $chuyen->nhaXe->QuyDinhNhaXe)
                                            @php
                                                $rules = explode('|', $chuyen->nhaXe->QuyDinhNhaXe);
                                            @endphp
                                            
                                            <div style="margin-bottom: 20px;">
                                                <h6 style="font-weight: 600; margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Yêu cầu khi lên xe</h6>
                                                <ul style="list-style: none; padding: 0; color: #6c757d; line-height: 2;">
                                                    @foreach(array_slice($rules, 0, ceil(count($rules) * 0.6)) as $rule)
                                                        <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                                            <i class="fas fa-check-circle" style="color: #27ae60; margin-right: 8px;"></i>
                                                            {{ $rule }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            
                                            <div>
                                                <h6 style="font-weight: 600; margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Hành lý xách tay</h6>
                                                <ul style="list-style: none; padding: 0; color: #6c757d; line-height: 2;">
                                                    @foreach(array_slice($rules, ceil(count($rules) * 0.6)) as $rule)
                                                        <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                                            <i class="fas fa-info-circle" style="color: #3498db; margin-right: 8px;"></i>
                                                            {{ $rule }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div>
                                                <h6 style="font-weight: 600; margin-bottom: 15px; font-size: 16px;">Yêu cầu khi lên xe</h6>
                                                <ul style="list-style: none; padding: 0; color: #6c757d; line-height: 2;">
                                                    <li style="padding: 8px 0;"><i class="fas fa-clock" style="color: #3498db; margin-right: 8px;"></i> Có mặt tại bến xuất phát trước giờ khởi hành 15 phút</li>
                                                    <li style="padding: 8px 0;"><i class="fas fa-id-card" style="color: #3498db; margin-right: 8px;"></i> Xuất trình giấy tờ tùy thân khi lên xe</li>
                                                    <li style="padding: 8px 0;"><i class="fas fa-ban" style="color: #e74c3c; margin-right: 8px;"></i> Không mang theo vật dễ cháy nổ, chất gây nghiện</li>
                                                    <li style="padding: 8px 0;"><i class="fas fa-smoking-ban" style="color: #e74c3c; margin-right: 8px;"></i> Không hút thuốc trên xe</li>
                                                </ul>
                                                
                                                <h6 style="font-weight: 600; margin: 20px 0 15px; font-size: 16px;">Hành lý xách tay</h6>
                                                <ul style="list-style: none; padding: 0; color: #6c757d; line-height: 2;">
                                                    <li style="padding: 8px 0;"><i class="fas fa-suitcase" style="color: #3498db; margin-right: 8px;"></i> Hành lý miễn phí tối đa 20kg/khách</li>
                                                    <li style="padding: 8px 0;"><i class="fas fa-box" style="color: #f39c12; margin-right: 8px;"></i> Không vận chuyển hàng hóa cồng kềnh</li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                </div><!-- Đóng bus-info-container -->
                            </div>
                        @endforeach
                    @else
                        <div class="no-results">
                            <div class="no-results-icon">
                                <svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Người với tài liệu -->
                                    <circle cx="100" cy="80" r="25" fill="#ffa726" opacity="0.8"/>
                                    <path d="M 70 120 Q 70 100 100 100 Q 130 100 130 120 L 130 160 Q 130 180 100 180 Q 70 180 70 160 Z" fill="#ffa726" opacity="0.8"/>
                                    <!-- Tài liệu phía sau -->
                                    <rect x="60" y="140" width="80" height="100" rx="5" fill="#e0e0e0" opacity="0.5"/>
                                    <rect x="65" y="145" width="70" height="90" rx="3" fill="#f5f5f5" opacity="0.7"/>
                                    <!-- Các tài liệu mờ phía sau -->
                                    <rect x="55" y="135" width="70" height="95" rx="5" fill="#e0e0e0" opacity="0.3" transform="rotate(-5 90 182)"/>
                                    <rect x="75" y="150" width="65" height="85" rx="4" fill="#e0e0e0" opacity="0.2" transform="rotate(3 107 192)"/>
                                </svg>
                            </div>
                            <h4>Không có kết quả được tìm thấy.</h4>
                            <p>Vui lòng thử lại với tiêu chí tìm kiếm khác.</p>
                            <a href="{{ route('welcome') }}" class="btn-change-search">
                                <i class="fas fa-arrow-left me-2"></i>
                                Tìm lại
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = '{{ route("chuyenxe.search", $request->only(["diem_di", "diem_den", "ngay_khoi_hanh", "so_ghe"])) }}';
}

function toggleCollapsible(id) {
    const body = document.getElementById('body-' + id);
    const arrow = document.getElementById('arrow-' + id);
    
    if (body.classList.contains('open')) {
        body.classList.remove('open');
        arrow.classList.remove('open');
    } else {
        body.classList.add('open');
        arrow.classList.add('open');
    }
}

// Không mở mặc định phần điểm lên xe - chỉ mở khi người dùng click

// Tab switching function
// Toggle hiển thị thông tin nhà xe
function toggleBusInfo(chuyenXeId) {
    const infoContainer = document.getElementById('bus-info-' + chuyenXeId);
    const arrow = document.getElementById('info-arrow-' + chuyenXeId);
    
    if (infoContainer.style.display === 'none' || !infoContainer.style.display) {
        infoContainer.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        infoContainer.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}

function showTab(button, contentId) {
    // Remove active class from all tabs and contents
    const tabs = button.parentElement.querySelectorAll('.bus-tab');
    const contents = button.closest('.bus-info-container').querySelectorAll('.bus-tab-content');
    
    tabs.forEach(tab => tab.classList.remove('active'));
    contents.forEach(content => {
        content.classList.remove('active');
        content.style.display = 'none';
    });
    
    // Add active class to clicked tab and corresponding content
    button.classList.add('active');
    const content = document.getElementById(contentId);
    if (content) {
        content.classList.add('active');
        content.style.display = 'block';
    }
}
</script>

@endsection

