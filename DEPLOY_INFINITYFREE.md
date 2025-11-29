# Hướng dẫn Deploy Laravel lên InfinityFree

## Yêu cầu trước khi deploy

1. **Chuẩn bị code:**
   - Đảm bảo đã chạy `composer install --optimize-autoloader --no-dev` trên máy local
   - Đảm bảo đã chạy `php artisan config:cache` và `php artisan route:cache` (tùy chọn)

2. **Tạo database trên InfinityFree:**
   - Đăng nhập vào Control Panel của InfinityFree
   - Tạo MySQL database mới
   - Lưu lại thông tin: Database name, Username, Password, Host (thường là `sqlXXX.epizy.com` hoặc `localhost`)

## Các bước deploy

### Bước 1: Chuẩn bị file .env

1. Tạo file `.env` trên server với nội dung từ `.env.example`
2. Cập nhật các thông tin sau:

```env
APP_NAME="DatVeXeKhach"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.epizy.com

DB_CONNECTION=mysql
DB_HOST=sqlXXX.epizy.com  # hoặc localhost
DB_PORT=3306
DB_DATABASE=epiz_XXXXXX_yourdb
DB_USERNAME=epiz_XXXXXX_youruser
DB_PASSWORD=your_password

SESSION_DRIVER=file
SESSION_LIFETIME=120

FILESYSTEM_DISK=public
```

**Lưu ý quan trọng:**
- `APP_KEY`: Chạy `php artisan key:generate` trên local, copy key vào file .env trên server
- `APP_URL`: Thay bằng domain thực tế của bạn trên InfinityFree
- `DB_HOST`: Thường là `sqlXXX.epizy.com` hoặc `localhost` (kiểm tra trong Control Panel)
- `APP_DEBUG`: Đặt là `false` trong production

### Bước 2: Upload files

**Cấu trúc thư mục trên InfinityFree:**

```
htdocs/
├── .env
├── .htaccess (file này ở thư mục gốc, redirect về public)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── css/
│   ├── image/
│   └── uploads/
├── resources/
├── routes/
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
└── vendor/
```

**Cách upload:**
1. Upload tất cả các file và thư mục lên `htdocs/` (thư mục gốc)
2. **KHÔNG** upload các file sau:
   - `.git/`
   - `.env` (tạo mới trên server)
   - `node_modules/`
   - `tests/`
   - `phpunit.xml`

### Bước 3: Cấu hình .htaccess và index.php ở thư mục gốc

**File `.htaccess` ở thư mục gốc (`htdocs/`):**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**File `index.php` ở thư mục gốc (`htdocs/`):**

File này đã được tạo sẵn trong project, chỉ cần đảm bảo upload lên server. File này sẽ redirect về `public/index.php`.

### Bước 4: Import database

1. Đăng nhập vào phpMyAdmin trên InfinityFree
2. Chọn database của bạn
3. Import file `database/DatVeXeKhach.sql`

### Bước 5: Cấu hình quyền thư mục

Đảm bảo các thư mục sau có quyền ghi (chmod 755 hoặc 777):
- `storage/`
- `storage/app/`
- `storage/framework/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`
- `storage/logs/`
- `bootstrap/cache/`
- `public/uploads/`

**Lưu ý:** InfinityFree có thể tự động set quyền, nhưng nếu có lỗi, bạn có thể cần set qua File Manager.

### Bước 6: Tạo symbolic link cho storage (nếu cần)

Nếu có lỗi về storage link, bạn có thể cần chỉnh sửa `config/filesystems.php` để sử dụng `public` disk thay vì symbolic link.

### Bước 7: Kiểm tra

1. Truy cập `https://yourdomain.epizy.com`
2. Kiểm tra các chức năng:
   - Đăng ký/Đăng nhập
   - Tìm kiếm chuyến xe
   - Đặt vé
   - Upload ảnh

## Xử lý lỗi thường gặp

### Lỗi 500 Internal Server Error
- Kiểm tra file `.env` đã được tạo chưa
- Kiểm tra `APP_KEY` đã được set chưa
- Kiểm tra quyền thư mục `storage/` và `bootstrap/cache/`
- Kiểm tra file log trong `storage/logs/laravel.log`

### Lỗi database connection
- Kiểm tra thông tin database trong `.env`
- Kiểm tra database đã được tạo chưa
- Kiểm tra host database (có thể là `localhost` thay vì `sqlXXX.epizy.com`)

### Lỗi 404 Not Found
- Kiểm tra file `.htaccess` ở thư mục gốc và `public/`
- Kiểm tra `APP_URL` trong `.env`

### Lỗi upload file
- Kiểm tra quyền thư mục `public/uploads/`
- Kiểm tra cấu hình `FILESYSTEM_DISK=public` trong `.env`

### Lỗi session
- Đảm bảo `SESSION_DRIVER=file` trong `.env`
- Kiểm tra quyền thư mục `storage/framework/sessions/`

## Tối ưu hóa

1. **Cache config và routes (Tùy chọn - có thể bỏ qua):**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
   **Lưu ý:** Nếu cache config, bạn sẽ cần chạy `php artisan config:clear` mỗi khi thay đổi `.env`

2. **Optimize autoloader:**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Xóa các file không cần thiết:**
   - `.git/`
   - `tests/`
   - `phpunit.xml`
   - `README.md` (tùy chọn)
   - `package.json`, `webpack.mix.js` (nếu không dùng)

## Cấu hình .env cho InfinityFree

Tạo file `.env` trên server với nội dung mẫu sau (thay thế các giá trị phù hợp):

```env
APP_NAME="DatVeXeKhach"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.epizy.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sqlXXX.epizy.com
DB_PORT=3306
DB_DATABASE=epiz_XXXXXX_yourdb
DB_USERNAME=epiz_XXXXXX_youruser
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Cách lấy APP_KEY:**
1. Trên máy local, chạy: `php artisan key:generate`
2. Mở file `.env` local, copy giá trị `APP_KEY=...`
3. Paste vào file `.env` trên server

## Lưu ý quan trọng

1. **InfinityFree có giới hạn:**
   - Không hỗ trợ SSH
   - Không hỗ trợ cron jobs
   - Giới hạn về tài nguyên (CPU, RAM, Bandwidth)
   - Có thể bị suspend nếu sử dụng quá nhiều tài nguyên

2. **Bảo mật:**
   - Đảm bảo `APP_DEBUG=false` trong production
   - Không commit file `.env` lên git
   - Đảm bảo các file nhạy cảm không được public

3. **Backup:**
   - Thường xuyên backup database
   - Backup các file upload quan trọng

