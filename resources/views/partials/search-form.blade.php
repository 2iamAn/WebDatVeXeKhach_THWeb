<form class="booking-box">

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

  <div class="form-row">
    <div class="form-group">
      <label>Từ</label>
      <div class="input-icon">
        <img src="{{ asset('image/bus.png') }}">
        <input list="list-diemdi" placeholder="Chọn điểm đi">
      </div>
    </div>

    <div class="form-group">
      <label>Đến</label>
      <div class="input-icon">
        <img src="{{ asset('image/bus.png') }}">
        <input list="list-diemden" placeholder="Chọn điểm đến">
      </div>
    </div>
  </div>

  <div class="form-row">

    <div class="form-group">
      <label>Ngày khởi hành</label>
      <div class="input-icon">
        <img src="{{ asset('image/lich.png') }}">
        <input type="date" id="ngaydi" min="{{ date('Y-m-d') }}">
      </div>
    </div>

    <div class="form-group hidden" id="ngayve-group">
      <label>Ngày về</label>
      <div class="input-icon">
        <img src="{{ asset('image/lich.png') }}">
        <input type="date" id="ngayve" min="{{ date('Y-m-d') }}">
      </div>
    </div>

    <div class="form-group">
      <label>Số ghế</label>
      <div class="input-icon">
        <img src="{{ asset('image/nguoi.png') }}">
        <input type="number" placeholder="1">
      </div>
    </div>

  </div>

  <button class="btn-search">🔍 Tìm xe</button>

</form>
