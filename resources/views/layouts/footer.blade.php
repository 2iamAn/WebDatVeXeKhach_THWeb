<footer class="footer" id="contact">
  <div class="footer-top">
    <div class="container">
      <div class="footer-content">
        <!-- Cột trái: Logo và Thông tin liên hệ -->
        <div class="footer-column footer-left">
          <div class="footer-logo-section">
            <a href="{{ url('/') }}" class="footer-logo-link">
              <img src="{{ asset('image/logo.png') }}" alt="Bustrip" class="footer-logo">
            </a>
            <p class="footer-tagline">
              <i class="fas fa-bus me-2"></i>NHIỀU NHÀ XE - MỘT ĐIỂM ĐẶT
            </p>
            <p class="footer-description">
              Nền tảng đặt vé xe khách tiện lợi, an toàn và đáng tin cậy cho cả hành khách và các nhà xe.
            </p>
          </div>
          
          <div class="footer-section">
            <h5 class="footer-heading">
              <i class="fas fa-map-marker-alt me-2"></i>Thông tin liên hệ
            </h5>
            <ul class="footer-contact-info">
              <li>
                <i class="fas fa-building me-2"></i>
                <span>Công ty TNHH Thương Mại Dịch Vụ Bustrip</span>
              </li>
              <li>
                <i class="fas fa-map-pin me-2"></i>
                <span>180 Cao Lỗ, Phường 4, Quận 8, Tp.Hồ Chí Minh, Việt Nam</span>
              </li>
              <li>
                <i class="fas fa-phone me-2"></i>
                <a href="tel:0777443085">0777443085</a>
              </li>
              <li>
                <i class="fas fa-envelope me-2"></i>
                <a href="mailto:dinhthuphuong1302@gmail.com">dinhthuphuong1302@gmail.com</a>
              </li>
            </ul>
          </div>

          <!-- Social Media -->
       
        </div>
        
        <!-- Cột giữa: Về Bustrip -->
        <div class="footer-column footer-center">
          <h5 class="footer-heading">
            <i class="fas fa-info-circle me-2"></i>Về Bustrip
          </h5>
          <ul class="footer-links">
            <li>
              <a href="#">
                <i class="fas fa-book-open me-2"></i>Cách đặt chỗ
              </a>
            </li>
            <li>
              <a href="{{ route('contact.index') }}">
                <i class="fas fa-address-card me-2"></i>Liên hệ chúng tôi
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fas fa-question-circle me-2"></i>Trợ giúp
              </a>
            </li>
            <li>
              <a href="{{ route('about.index') }}">
                <i class="fas fa-users me-2"></i>Về chúng tôi
              </a>
            </li>
            <li>
              <a href="{{ route('vexe.booking') }}">
                <i class="fas fa-ticket-alt me-2"></i>Đặt chỗ của tôi
              </a>
            </li>
          </ul>

          <div class="footer-section">
            <h5 class="footer-heading">
              <i class="fas fa-handshake me-2"></i>Hợp tác với Bustrip
            </h5>
            <ul class="footer-links">
              <li>
                <a href="{{ route('partner.request') }}">
                  <i class="fas fa-building me-2"></i>Đăng ký nhà xe
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fas fa-user-tie me-2"></i>Trở thành đối tác
                </a>
              </li>
            </ul>
          </div>
        </div>
        
        <!-- Cột phải: Hỗ trợ và Đối tác thanh toán -->
        <div class="footer-column footer-right">
          <div class="footer-section">
            <h5 class="footer-heading">
              <i class="fas fa-life-ring me-2"></i>Hỗ trợ
            </h5>
            <ul class="footer-links">
              <li>
                <a href="#">
                  <i class="fas fa-credit-card me-2"></i>Hướng dẫn thanh toán
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fas fa-shield-alt me-2"></i>Chính sách bảo mật
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fas fa-file-contract me-2"></i>Điều khoản sử dụng
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fas fa-headset me-2"></i>Hỗ trợ 24/7
                </a>
              </li>
            </ul>
          </div>
          
          <div class="footer-section">
            <h5 class="footer-heading">
              <i class="fas fa-credit-card me-2"></i>Đối tác thanh toán
            </h5>
            <div class="payment-partners">
              <div class="payment-logo" title="VISA">
                <img src="{{ asset('image/logovisa.jpg') }}" alt="VISA">
              </div>
              <div class="payment-logo" title="Vietcombank">
                <img src="{{ asset('image/vietcombank.jpg') }}" alt="Vietcombank">
              </div>
              <div class="payment-logo" title="BIDV">
                <img src="{{ asset('image/bidv.jpg') }}" alt="BIDV">
              </div>
              <div class="payment-logo" title="MB Bank">
                <img src="{{ asset('image/logombbank.jpg') }}" alt="MB Bank">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="footer-bottom">
    <div class="container">
      <div class="footer-bottom-content">
        <p class="mb-0">
          <i class="far fa-copyright me-1"></i>
          {{ date('Y') }} Công ty TNHH Thương Mại Dịch Vụ Bustrip. Tất cả quyền được bảo lưu.
        </p>
        <p class="mb-0">
          <i class="fas fa-map-marker-alt me-1"></i>
          Địa chỉ: 180 Cao Lỗ, Phường 4, Quận 8, Tp.Hồ Chí Minh, Việt Nam
        </p>
      </div>
    </div>
  </div>
</footer>
