<footer class="footer" id="contact">
  <div class="footer-top">
    <div class="container">
      <div class="footer-content">
        <!-- Cột trái: Logo và Đối tác thanh toán -->
        <div class="footer-column footer-left">
          <div class="footer-logo-section">
            <a href="{{ url('/') }}" class="footer-logo-link">
              <img src="{{ asset('image/logo.png') }}" alt="Bustrip" class="footer-logo">
            </a>
            <p class="footer-tagline">NHIỀU NHÀ XE - MỘT ĐIỂM ĐẶT</p>
          </div>
          
          <div class="footer-section">
            <h5 class="footer-heading">Hợp tác với Bustrip</h5>
          </div>
          
          <div class="footer-section">
            <h5 class="footer-heading">Đối tác thanh toán</h5>
            <div class="payment-partners">
              <div class="payment-logo">
                <img src="{{ asset('image/logovisa.jpg') }}" alt="VISA">
              </div>
              <div class="payment-logo">
                <img src="{{ asset('image/vietcombank.jpg') }}" alt="Vietcombank">
              </div>
              <div class="payment-logo">
                <img src="{{ asset('image/bidv.jpg') }}" alt="BIDV">
              </div>
              <div class="payment-logo">
                <img src="{{ asset('image/logombbank.jpg') }}" alt="MB Bank">
              </div>
            </div>
          </div>
        </div>
        
        <!-- Cột giữa: Về Bustrip -->
        <div class="footer-column footer-center">
          <h5 class="footer-heading">Về Bustrip</h5>
          <ul class="footer-links">
            <li><a href="#">Cách đặt chỗ</a></li>
            <li><a href="#contact">Liên hệ chúng tôi</a></li>
            <li><a href="#">Trợ giúp</a></li>
            <li><a href="#">Về chúng tôi</a></li>
          </ul>
        </div>
        
        <!-- Cột phải: Hỗ trợ và Đối tác -->
        <div class="footer-column footer-right">
          <div class="footer-section">
            <h5 class="footer-heading">Hỗ trợ</h5>
            <ul class="footer-links">
              <li><a href="#">Hướng dẫn thanh toán</a></li>
            </ul>
          </div>
          
          <div class="footer-section">
            <h5 class="footer-heading">Trở thành đối tác</h5>
            <ul class="footer-links">
              <li><a href="{{ route('partner.request') }}">Quản lý nhà xe</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="footer-bottom">
    <div class="container">
      <p class="mb-0">Công ty TNHH Thương Mại Dịch Vụ Bustrip</p>
      <p class="mb-0">Địa chỉ: 180 Cao Lỗ, Phường 4, Quận 8, Tp.Hồ Chí Minh, Việt Nam</p>
    </div>
  </div>
</footer>
