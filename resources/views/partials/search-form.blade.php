{{-- Form tìm kiếm chuyến xe --}}
<form class="booking-box">
    {{-- Chọn loại vé: một chiều hoặc khứ hồi --}}
    <div class="trip-type-top">
        <div class="trip-type-right">
            <label>
                <input type="radio" name="loaive" id="motchieu" value="motchieu" checked>
                Một chiều
            </label>
            <label>
                <input type="radio" name="loaive" id="khuhui" value="khuhui">
                Khứ hồi
            </label>
        </div>
    </div>

    {{-- Điểm đi và điểm đến --}}
    <div class="form-row">
        <div class="form-group">
            <label>Từ</label>
            <div class="input-icon">
                <img src="{{ asset('image/bus.png') }}" alt="Điểm đi">
                <input list="list-diemdi" id="diem-di" placeholder="Chọn điểm đi">
            </div>
        </div>

        <div class="form-group">
            <label>Đến</label>
            <div class="input-icon">
                <img src="{{ asset('image/bus.png') }}" alt="Điểm đến">
                <input list="list-diemden" id="diem-den" placeholder="Chọn điểm đến">
            </div>
        </div>
    </div>

    {{-- Ngày khởi hành, ngày về và số ghế --}}
    <div class="form-row">
        <div class="form-group">
            <label>Ngày khởi hành</label>
            <div class="input-icon">
                <img src="{{ asset('image/lich.png') }}" alt="Ngày đi">
                <input type="date" id="ngaydi" min="{{ date('Y-m-d') }}">
            </div>
        </div>

        <div class="form-group hidden" id="ngayve-group">
            <label>Ngày về</label>
            <div class="input-icon">
                <img src="{{ asset('image/lich.png') }}" alt="Ngày về">
                <input type="date" id="ngayve" min="{{ date('Y-m-d') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Số ghế</label>
            <div class="input-icon">
                <img src="{{ asset('image/nguoi.png') }}" alt="Số ghế">
                <input type="number" id="so-ghe" placeholder="1" min="1" max="10" value="1">
            </div>
        </div>
    </div>

    <button type="button" class="btn-search">🔍 Tìm xe</button>
</form>
