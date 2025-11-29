@php
    $status = \Illuminate\Support\Str::lower($g->TrangThai ?? '');
    $isEmpty = in_array($status, ['trong','trống']);
    $isBooked = in_array($status, ['đã đặt', 'đã dat', 'da dat', 'đã bán', 'da ban']);
    $isSelected = false; // Có thể thêm logic để kiểm tra ghế đang được chọn
    
    $seatClass = $isSelected ? 'selected' : ($isBooked ? 'booked' : 'available');
    $statusText = $isEmpty ? 'Trống' : ($isBooked ? 'Đã đặt' : $g->TrangThai);
    
    // Lấy số ghế ngắn gọn (bỏ T1, T2 nếu có)
    $soGheDisplay = preg_replace('/T[12]/i', '', $g->SoGhe ?? '');
@endphp

<div class="seat-item {{ $seatClass }}" 
     title="Ghế {{ $g->SoGhe }} - {{ $statusText }}
@if($g->chuyenXe && $g->chuyenXe->tuyenDuong)
Chuyến: {{ optional($g->chuyenXe->tuyenDuong)->DiemDi }} → {{ optional($g->chuyenXe->tuyenDuong)->DiemDen }}
@endif">
    <div style="font-weight: 700; font-size: 13px;">{{ $soGheDisplay }}</div>
    @if($isBooked)
        <i class="fas fa-times-circle" style="font-size: 10px; margin-top: 2px;"></i>
    @elseif($isSelected)
        <i class="fas fa-check-circle" style="font-size: 10px; margin-top: 2px;"></i>
    @else
        <i class="fas fa-circle" style="font-size: 8px; margin-top: 2px; opacity: 0.5;"></i>
    @endif
</div>

