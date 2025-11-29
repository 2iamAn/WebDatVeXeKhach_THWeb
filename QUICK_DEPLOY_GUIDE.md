# Hướng dẫn nhanh deploy lên InfinityFree

## Bước 1: Chuẩn bị trên máy local

```bash
# 1. Tối ưu autoloader
composer install --optimize-autoloader --no-dev

# 2. Generate APP_KEY (nếu chưa có)
php artisan key:generate

# 3. Copy APP_KEY từ .env local để dùng trên server
```

## Bước 2: Tạo database trên InfinityFree

1. Đăng nhập Control Panel InfinityFree
2. Tạo MySQL database mới
3. Lưu lại: Database name, Username, Password, Host

## Bước 3: Upload files

Upload tất cả files lên `htdocs/` (trừ `.git/`, `node_modules/`, `tests/`)

**Quan trọng:** Đảm bảo upload file `index.php` và `.htaccess` ở thư mục gốc!

## Bước 4: Tạo file .env trên server

Tạo file `.env` trong thư mục gốc với nội dung:

```env
APP_NAME="DatVeXeKhach"
APP_ENV=production
APP_KEY=base64:PASTE_KEY_TỪ_LOCAL
APP_DEBUG=false
APP_URL=https://yourdomain.epizy.com

DB_CONNECTION=mysql
DB_HOST=sqlXXX.epizy.com
DB_PORT=3306
DB_DATABASE=epiz_XXXXXX_yourdb
DB_USERNAME=epiz_XXXXXX_youruser
DB_PASSWORD=your_password

SESSION_DRIVER=file
FILESYSTEM_DISK=local
```

## Bước 5: Import database

Import file `database/DatVeXeKhach.sql` vào database vừa tạo qua phpMyAdmin

## Bước 6: Kiểm tra quyền thư mục

Đảm bảo các thư mục sau có quyền ghi:
- `storage/`
- `storage/framework/`
- `storage/logs/`
- `bootstrap/cache/`
- `public/uploads/`

## Bước 7: Kiểm tra

Truy cập `https://yourdomain.epizy.com` và test các chức năng

---

**Xem chi tiết:** `DEPLOY_INFINITYFREE.md`
**Checklist:** `DEPLOY_CHECKLIST.md`

