# 🚌 Hệ Thống Đặt Vé Xe Khách - BUSTRIP

Hệ thống quản lý và đặt vé xe khách trực tuyến, cho phép khách hàng tìm kiếm, so sánh giá và đặt vé xe một cách dễ dàng.

## 📋 Mục Lục

- [Giới thiệu](#giới-thiệu)
- [Tính năng](#tính-năng)
- [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
- [Cài đặt](#cài-đặt)
- [Cấu hình](#cấu-hình)
- [Sử dụng](#sử-dụng)
- [Cấu trúc dự án](#cấu-trúc-dự-án)
- [Công nghệ sử dụng](#công-nghệ-sử-dụng)

## 🎯 Giới thiệu

BUSTRIP là hệ thống đặt vé xe khách trực tuyến được xây dựng bằng Laravel 11, cho phép:

- **Khách hàng**: Tìm kiếm chuyến xe, đặt vé, thanh toán và quản lý đặt chỗ
- **Nhà xe đối tác**: Quản lý chuyến xe, ghế, vé và doanh thu
- **Quản trị viên**: Quản lý toàn bộ hệ thống, duyệt chuyến xe và báo cáo

## ✨ Tính năng

### 👤 Dành cho Khách hàng
- ✅ Tìm kiếm chuyến xe theo điểm đi, điểm đến, ngày khởi hành
- ✅ So sánh giá và thông tin các nhà xe
- ✅ Đặt vé trực tuyến
- ✅ Thanh toán trực tuyến
- ✅ Xem và quản lý đặt chỗ của mình
- ✅ Hủy vé (nếu được phép)
- ✅ Đánh giá nhà xe sau khi sử dụng dịch vụ
- ✅ Khôi phục đặt chỗ bằng mã vé và số điện thoại

### 🏢 Dành cho Nhà xe đối tác
- ✅ Dashboard quản lý tổng quan
- ✅ Quản lý chuyến xe (thêm, sửa, xóa, duyệt)
- ✅ Quản lý ghế và trạng thái ghế
- ✅ Quản lý vé đã bán
- ✅ Xem doanh thu theo thời gian
- ✅ Quản lý tuyến đường
- ✅ Quản lý phương tiện

### 👨‍💼 Dành cho Quản trị viên
- ✅ Quản lý người dùng (thêm, sửa, xóa, phân quyền)
- ✅ Duyệt/từ chối yêu cầu hợp tác của nhà xe
- ✅ Duyệt/từ chối chuyến xe mới
- ✅ Quản lý tất cả chuyến xe trong hệ thống
- ✅ Quản lý đánh giá (hiển thị/ẩn)
- ✅ Xem báo cáo tổng hợp

## 💻 Yêu cầu hệ thống

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.x (chỉ cần cho development, không cần trên production server)
- **npm**: >= 9.x (chỉ cần cho development)
- **MySQL**: >= 5.7 hoặc MariaDB >= 10.3
- **Web Server**: Apache/Nginx (hoặc dùng `php artisan serve`)

### ⚠️ Tại sao cần Node.js?

**Node.js KHÔNG chạy trên server production**, nó chỉ được sử dụng trong quá trình **development** để:

1. **Build CSS và JavaScript**: 
   - File CSS của bạn ở `resources/css/app.css` cần được compile
   - File JS ở `resources/js/app.js` cần được bundle
   - Vite (build tool) sử dụng Node.js để làm việc này

2. **Quy trình hoạt động**:
   ```
   Development: resources/css/app.css → [Vite + Node.js] → public/build/assets/app-xxx.css
   Production:  Chỉ cần file đã build sẵn trong public/build/
   ```

3. **Trên production server**:
   - Chỉ cần chạy `npm run build` một lần để tạo file build
   - Sau đó KHÔNG cần Node.js nữa
   - Server chỉ cần PHP và MySQL

**Tóm lại**: Node.js chỉ là công cụ build, không phải runtime. Trình duyệt chỉ load file CSS/JS đã được build sẵn.

## 🚀 Cài đặt

### Bước 1: Clone dự án
```bash
git clone https://github.com/2iamAn/WebDatVeXeKhach_THWeb.git
cd DatVeXeKhach
```

### Bước 2: Cài đặt dependencies
```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install
```

### Bước 3: Cấu hình môi trường
```bash
# Copy file .env.example thành .env
cp .env.example .env

# Tạo application key
php artisan key:generate
```

### Bước 4: Cấu hình database
Mở file `.env` và cập nhật thông tin database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datvexekhach
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 5: Chạy migrations
```bash
# Tạo database
php artisan migrate

# (Tùy chọn) Seed dữ liệu mẫu
php artisan db:seed
```

### Bước 6: Build assets
```bash
# Development build
npm run dev

# Hoặc watch mode (tự động build khi sửa CSS/JS)
npm run dev -- --watch

# Production build (trước khi deploy)
npm run build
```

### Bước 7: Chạy ứng dụng
```bash
php artisan serve
```

Truy cập: **http://127.0.0.1:8000**

## ⚙️ Cấu hình

### Cấu hình Email (nếu cần gửi email)
Trong file `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Cấu hình Session
Mặc định sử dụng file session. Có thể đổi sang database trong `.env`:
```env
SESSION_DRIVER=database
```

## 📖 Sử dụng

### Tài khoản mặc định
Sau khi chạy migrations và seed, có thể tạo tài khoản admin đầu tiên:
```bash
php artisan tinker
```
Hoặc đăng ký tài khoản mới qua giao diện.

### Các vai trò trong hệ thống:
1. **Admin**: Quản trị viên, có quyền cao nhất
2. **Partner**: Nhà xe đối tác, quản lý chuyến xe của mình
3. **User**: Khách hàng, đặt vé và sử dụng dịch vụ

### Quy trình đặt vé:
1. Khách hàng tìm kiếm chuyến xe trên trang chủ
2. Chọn chuyến xe phù hợp
3. Chọn ghế và điền thông tin
4. Thanh toán
5. Nhận mã vé và thông tin đặt chỗ

## 📁 Cấu trúc dự án

```
DatVeXeKhach/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controllers xử lý logic
│   │   │   ├── AdminController.php
│   │   │   ├── AuthController.php
│   │   │   ├── ChuyenXeController.php
│   │   │   ├── ContactController.php
│   │   │   ├── NhaXeController.php
│   │   │   ├── PartnerController.php
│   │   │   └── ...
│   │   └── Middleware/       # Middleware
│   └── Models/               # Eloquent Models
│       ├── NhaXe.php
│       ├── ChuyenXe.php
│       ├── VeXe.php
│       └── ...
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── public/
│   ├── image/                # Hình ảnh (banner, logo, nhà xe)
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css           # CSS chính
│   ├── js/
│   │   └── app.js            # JavaScript chính
│   └── views/
│       ├── layouts/          # Layout templates
│       ├── admin/             # Views cho admin
│       ├── partner/           # Views cho nhà xe
│       ├── contact.blade.php  # Trang liên hệ
│       └── welcome.blade.php  # Trang chủ
├── routes/
│   └── web.php               # Web routes
├── .env                      # Environment configuration
├── composer.json             # PHP dependencies
├── package.json              # Node.js dependencies
└── vite.config.js            # Vite configuration
```

## 🛠️ Công nghệ sử dụng

### Backend
- **Laravel 11**: PHP Framework
- **MySQL**: Database
- **Eloquent ORM**: Database abstraction

### Frontend
- **Bootstrap 5**: CSS Framework
- **Vite**: Build tool (thay thế Laravel Mix)
- **Font Awesome**: Icons
- **JavaScript (Vanilla)**: Client-side scripting

### Development Tools
- **Composer**: PHP dependency manager
- **npm**: Node.js package manager
- **Git**: Version control

## 📝 Lưu ý quan trọng

### Khi sửa CSS/JavaScript:
```bash
# Phải chạy lệnh này để compile lại
npm run dev

# Hoặc dùng watch mode
npm run dev -- --watch
```

### Khi sửa PHP/Blade:
- Không cần chạy `npm run dev`
- Chỉ cần refresh trình duyệt

### Clear cache khi có vấn đề:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## 🔐 Bảo mật

- Sử dụng CSRF protection cho tất cả forms
- Password được hash bằng bcrypt
- Session-based authentication
- Input validation và sanitization

## 📞 Liên hệ

- **Email**: dinhthuphuong1302@gmail.com
- **Website**: https://nhaxetructuyen.page.gd
- **Điện thoại**: 0777443085

## 📄 License

Dự án này được phát triển cho mục đích học tập và nghiên cứu.

---

**Phát triển bởi**: BUSTRIP Team  
**Phiên bản**: 1.0.0  
**Cập nhật**: 2025
