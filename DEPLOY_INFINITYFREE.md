# Hướng dẫn Deploy lên InfinityFree

## 📋 Yêu cầu

- Tài khoản InfinityFree (miễn phí)
- Database đã được tạo trên InfinityFree
- FTP client hoặc File Manager trong cPanel

## 🔧 Thông tin Database InfinityFree

- **Host**: `sql102.infinityfree.com`
- **Database**: `if0_40241895_db_xekhach`
- **Username**: `if0_40241895`
- **Password**: `JzycvT6DM1`
- **Port**: `3306`

## 📦 Bước 1: Chuẩn bị code

### 1.1. Build assets (nếu chưa build)
```bash
npm run build
```

### 1.2. Tối ưu hóa cho production
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📤 Bước 2: Upload code lên InfinityFree

### 2.1. Cấu trúc thư mục trên InfinityFree

InfinityFree thường có cấu trúc:
```
htdocs/
├── .env
├── .htaccess
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── image/
│   └── uploads/
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
├── composer.json
└── composer.lock
```

### 2.2. Upload files

**Cách 1: Upload toàn bộ project**
- Upload tất cả files vào thư mục `htdocs/` hoặc `public_html/`
- Đảm bảo `.htaccess` ở root được upload

**Cách 2: Chỉ upload cần thiết (khuyến nghị)**
- Upload tất cả files trừ `node_modules/`, `.git/`, `tests/`

## ⚙️ Bước 3: Cấu hình trên server

### 3.1. Tạo file .env

1. Đăng nhập vào cPanel InfinityFree
2. Vào File Manager
3. Tạo file `.env` ở thư mục root (htdocs)
4. Copy nội dung từ file `.env.infinitifree` và chỉnh sửa:

```env
APP_NAME="DatVeXeKhach"

APP_ENV=production
APP_KEY=base64:uAr9LSKyxPSfP6UAsSahHNXpdZRaTc4f4WiFQ1/65+Q=
APP_DEBUG=false
APP_URL=https://yourdomain.infinityfreeapp.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sql102.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_40241895_db_xekhach
DB_USERNAME=if0_40241895
DB_PASSWORD=JzycvT6DM1

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=bustriplimousine@gmail.com
MAIL_PASSWORD="lsnv lzvz bfjo mfxv"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=bustriplimousine@gmail.com
MAIL_FROM_NAME="DatVeXeKhach"
```

**Lưu ý quan trọng:**
- Thay `APP_URL` bằng domain thực tế của bạn (ví dụ: `https://yourdomain.infinityfreeapp.com`)
- Nếu có domain riêng, dùng domain đó

### 3.2. Set quyền cho thư mục storage

Trong File Manager hoặc qua FTP, set quyền 755 cho:
- `storage/`
- `storage/framework/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`
- `storage/logs/`
- `bootstrap/cache/`

### 3.3. Kiểm tra .htaccess

Đảm bảo file `.htaccess` ở root có nội dung:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect to public directory
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/$1 [L]
    
    # Redirect root to public
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^(.*)$ public/index.php [L]
</IfModule>
```

## 🗄️ Bước 4: Import Database

### 4.1. Import qua phpMyAdmin

1. Đăng nhập vào cPanel InfinityFree
2. Vào phpMyAdmin
3. Chọn database `if0_40241895_db_xekhach`
4. Import file SQL từ `database/datvexekhach_fixed.sql`

### 4.2. Hoặc chạy migrations

Nếu có quyền truy cập SSH hoặc có thể chạy PHP:
```bash
php artisan migrate
```

## 🚀 Bước 5: Chạy lệnh cần thiết trên server

### 5.1. Cài đặt dependencies

Nếu có thể truy cập SSH hoặc Terminal trong cPanel:
```bash
cd /path/to/htdocs
composer install --optimize-autoloader --no-dev
```

### 5.2. Tạo application key (nếu chưa có)
```bash
php artisan key:generate
```

### 5.3. Cache config và routes
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ✅ Bước 6: Kiểm tra

1. Truy cập domain của bạn
2. Kiểm tra xem website có chạy không
3. Kiểm tra database connection
4. Test các chức năng chính

## 🔧 Xử lý lỗi thường gặp

### Lỗi 500 Internal Server Error
- Kiểm tra quyền thư mục `storage/` và `bootstrap/cache/`
- Kiểm tra file `.env` có đúng không
- Kiểm tra log trong `storage/logs/laravel.log`

### Lỗi database connection
- Kiểm tra thông tin database trong `.env`
- Đảm bảo database đã được tạo trên InfinityFree
- Kiểm tra hostname có đúng không

### Lỗi file not found
- Kiểm tra `.htaccess` có đúng không
- Đảm bảo file `public/index.php` tồn tại
- Kiểm tra đường dẫn trong `public/index.php`

## 📝 Lưu ý quan trọng

1. **PHP Version**: InfinityFree hỗ trợ PHP 8.3, đảm bảo chọn đúng version trong cPanel
2. **File Permissions**: 
   - Thư mục: 755
   - File: 644
   - `storage/` và `bootstrap/cache/`: 755
3. **Composer**: Nếu không có Composer trên server, upload thư mục `vendor/` từ local
4. **Assets**: Đảm bảo đã build assets (`npm run build`) trước khi upload
5. **Environment**: Luôn set `APP_ENV=production` và `APP_DEBUG=false` trên production

## 🎯 Checklist trước khi deploy

- [ ] Đã build assets (`npm run build`)
- [ ] Đã tối ưu composer (`composer install --no-dev`)
- [ ] Đã tạo file `.env` với thông tin đúng
- [ ] Đã set quyền cho `storage/` và `bootstrap/cache/`
- [ ] Đã import database hoặc chạy migrations
- [ ] Đã kiểm tra `.htaccess` ở root
- [ ] Đã cập nhật `APP_URL` trong `.env`
- [ ] Đã test trên local trước khi deploy

---

**Chúc bạn deploy thành công! 🎉**
