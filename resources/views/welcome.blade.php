@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('content')

@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<!-- HERO -->
<section class="hero" style="background-image: url('{{ asset('image/hinbanner.jpg') }}');">
  <div class="hero-overlay"></div>

  <div class="hero-content">
    <h1>Nhiều nhà xe - Một điểm đặt</h1>
    <p>Tìm kiếm, so sánh giá và đặt vé chỉ trong vài bước</p>

    <!-- FORM TÌM XE -->
  <form class="booking-box" method="GET" action="{{ route('chuyenxe.search') }}">

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
        <input type="text" name="diem_di" list="list-diemdi" placeholder="Chọn điểm đi" required>
        <datalist id="list-diemdi">
          @foreach(\App\Models\TuyenDuong::distinct()->pluck('DiemDi') as $diem)
            <option value="{{ $diem }}">
          @endforeach
        </datalist>
      </div>
    </div>

    <div class="form-group">
      <label>Đến</label>
      <div class="input-icon">
        <img src="{{ asset('image/bus.png') }}">
        <input type="text" name="diem_den" list="list-diemden" placeholder="Chọn điểm đến" required>
        <datalist id="list-diemden">
          @foreach(\App\Models\TuyenDuong::distinct()->pluck('DiemDen') as $diem)
            <option value="{{ $diem }}">
          @endforeach
        </datalist>
      </div>
    </div>
  </div>

  <div class="form-row">

    <div class="form-group">
      <label>Ngày khởi hành</label>
      <div class="input-icon">
        <img src="{{ asset('image/lich.png') }}">
        <input type="date" name="ngay_khoi_hanh" id="ngaydi" min="{{ date('Y-m-d') }}" required>
      </div>
    </div>

    <div class="form-group hidden" id="ngayve-group">
      <label>Ngày về</label>
      <div class="input-icon">
        <img src="{{ asset('image/lich.png') }}">
        <input type="date" name="ngay_ve" id="ngayve" min="{{ date('Y-m-d') }}">
      </div>
    </div>

    <div class="form-group">
      <label>Số ghế</label>
      <div class="input-icon">
        <img src="{{ asset('image/nguoi.png') }}">
        <input type="number" name="so_ghe" placeholder="1" value="1" min="1" required>
      </div>
    </div>

  </div>

  <button type="submit" class="btn-search">🔍 Tìm xe</button>

</form>

  </div>
</section>

<!-- TUYẾN PHỔ BIẾN -->
<section class="popular-routes-section">
  <div class="container">
    <h3 class="section-title">Nhà xe phổ biến</h3>
    
    <div class="routes-carousel-wrapper">
      <button class="carousel-nav carousel-prev" aria-label="Trước">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
      
      <div class="routes-carousel">
        @php
          $nhaxes = \App\Models\NhaXe::with('chuyenXe')->whereHas('chuyenXe')->take(4)->get();
          $busImages = [
            'image/nha-xe-viet-tan-phat-2.jpg',
            'image/phuongtrang.jpg',
            'image/phuonghonglinh1.jpg',
            'image/xe-tien-oanh-374838.jpg'
          ];
          $busColors = [
            'linear-gradient(135deg, #8B7355 0%, #6B5D4F 100%)',
            'linear-gradient(135deg, #5B4C7A 0%, #4A3D66 100%)',
            'linear-gradient(135deg, #4A6FA5 0%, #3A5A8A 100%)',
            'linear-gradient(135deg, #6B8E9F 0%, #5A7A8A 100%)'
          ];
        @endphp
        
        @foreach($nhaxes as $index => $bus)
          <a href="{{ route('nhaxe.show', $bus->MaNhaXe) }}" class="route-card">
            <div class="route-card-image">
              <img src="{{ asset($busImages[$index % count($busImages)]) }}" alt="{{ $bus->TenNhaXe }}">
              <div class="card-overlay"></div>
            </div>
            <div class="route-card-info" style="background: {{ $busColors[$index % count($busColors)] }};">
              <h4 class="route-name">{{ $bus->TenNhaXe }}</h4>
            </div>
          </a>
        @endforeach
      </div>
      
      <button class="carousel-nav carousel-next" aria-label="Sau">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 18l6-6-6-6"/>
        </svg>
      </button>
    </div>
  </div>
</section>

@endsection



@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const motChieu = document.getElementById('motchieu');
    const khuHoi = document.getElementById('khuhui');
    const ngayVeGroup = document.getElementById('ngayve-group');

    // Ban đầu ẩn ngày về
    ngayVeGroup.style.display = "none";

    motChieu.addEventListener("change", function () {
        ngayVeGroup.style.display = "none";
        ngayVeGroup.classList.add("hidden");
    });

    khuHoi.addEventListener("change", function () {
        ngayVeGroup.style.display = "block";
        ngayVeGroup.classList.remove("hidden");
        // Cập nhật min của ngày về khi chuyển sang khứ hồi
        const ngayDi = document.getElementById('ngaydi');
        const ngayVe = document.getElementById('ngayve');
        if (ngayDi && ngayVe) {
            if (ngayDi.value) {
                ngayVe.setAttribute('min', ngayDi.value);
            } else {
                const today = new Date().toISOString().split('T')[0];
                ngayVe.setAttribute('min', today);
            }
        }
    });

    // Thiết lập ngày tối thiểu cho date picker
    const ngayDi = document.getElementById('ngaydi');
    const ngayVe = document.getElementById('ngayve');
    const today = new Date().toISOString().split('T')[0];
    
    if (ngayDi) {
        // Đảm bảo min được set từ server-side
        if (!ngayDi.getAttribute('min')) {
            ngayDi.setAttribute('min', today);
        }
        
        // Khi ngày đi thay đổi, cập nhật min của ngày về
        ngayDi.addEventListener('change', function() {
            if (ngayVe && this.value) {
                ngayVe.setAttribute('min', this.value);
                // Nếu ngày về đã được chọn và nhỏ hơn ngày đi, xóa giá trị
                if (ngayVe.value && ngayVe.value < this.value) {
                    ngayVe.value = '';
                }
            }
        });
    }
    
    if (ngayVe) {
        // Đảm bảo min được set từ server-side
        if (!ngayVe.getAttribute('min')) {
            ngayVe.setAttribute('min', today);
        }
    }

    // Carousel functionality
    const carousel = document.querySelector('.routes-carousel');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const cards = document.querySelectorAll('.route-card');
    
    if (carousel && prevBtn && nextBtn && cards.length > 0) {
        let currentIndex = 0;
        let cardsPerView = 4;
        
        function getCardsPerView() {
            const width = window.innerWidth;
            if (width <= 768) return 1;
            if (width <= 992) return 2;
            if (width <= 1200) return 3;
            return 4;
        }
        
        function updateCarousel() {
            cardsPerView = getCardsPerView();
            const totalCards = cards.length;
            const maxIndex = Math.max(0, totalCards - cardsPerView);
            
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            
            const cardWidth = cards[0].offsetWidth;
            const gap = 20;
            const translateX = -currentIndex * (cardWidth + gap);
            carousel.style.transform = `translateX(${translateX}px)`;
            
            // Update button states
            prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
            prevBtn.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
            prevBtn.style.cursor = currentIndex === 0 ? 'not-allowed' : 'pointer';
            
            nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
            nextBtn.style.pointerEvents = currentIndex >= maxIndex ? 'none' : 'auto';
            nextBtn.style.cursor = currentIndex >= maxIndex ? 'not-allowed' : 'pointer';
        }
        
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
        
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            cardsPerView = getCardsPerView();
            const maxIndex = Math.max(0, cards.length - cardsPerView);
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });
        
        // Initialize
        setTimeout(() => {
            updateCarousel();
        }, 100);
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                updateCarousel();
            }, 250);
        });
    }

});
</script>
@endsection