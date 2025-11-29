# Checklist Deploy lên InfinityFree

## Trước khi upload

- [ ] Chạy `composer install --optimize-autoloader --no-dev` trên local
- [ ] Tạo file `.env` với cấu hình đúng (xem DEPLOY_INFINITYFREE.md)
- [ ] Generate APP_KEY: `php artisan key:generate` (copy key vào .env trên server)
- [ ] Kiểm tra database đã được tạo trên InfinityFree
- [ ] Backup database hiện tại (nếu có)

## Files cần upload

- [ ] Upload tất cả thư mục: `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`
- [ ] Upload file `.htaccess` ở thư mục gốc
- [ ] Upload file `index.php` ở thư mục gốc (quan trọng cho InfinityFree)
- [ ] Upload file `.env` (tạo mới trên server với cấu hình đúng)
- [ ] Upload các file: `artisan`, `composer.json`, `composer.lock`

## Files KHÔNG upload

- [ ] `.git/` - Không upload
- [ ] `.env` từ local - Tạo mới trên server
- [ ] `node_modules/` - Không cần thiết
- [ ] `tests/` - Không cần thiết
- [ ] `phpunit.xml` - Không cần thiết
- [ ] `README.md` - Tùy chọn
- [ ] `package.json`, `webpack.mix.js` - Không cần nếu không dùng

## Cấu hình trên server

- [ ] Tạo file `.env` với thông tin đúng
- [ ] Cấu hình `.htaccess` ở thư mục gốc
- [ ] Kiểm tra quyền thư mục `storage/` (755 hoặc 777)
- [ ] Kiểm tra quyền thư mục `bootstrap/cache/` (755 hoặc 777)
- [ ] Kiểm tra quyền thư mục `public/uploads/` (755 hoặc 777)
- [ ] Import database từ `database/DatVeXeKhach.sql`

## Kiểm tra sau khi deploy

- [ ] Website có load được không?
- [ ] Đăng ký/Đăng nhập hoạt động?
- [ ] Tìm kiếm chuyến xe hoạt động?
- [ ] Upload ảnh xe hoạt động?
- [ ] Đặt vé hoạt động?
- [ ] Thanh toán hoạt động?
- [ ] Admin panel hoạt động?
- [ ] Partner panel hoạt động?

## Xử lý lỗi

- [ ] Kiểm tra `storage/logs/laravel.log` nếu có lỗi
- [ ] Kiểm tra quyền thư mục nếu không upload được file
- [ ] Kiểm tra cấu hình database trong `.env`
- [ ] Kiểm tra `APP_URL` trong `.env`
- [ ] Kiểm tra `APP_KEY` đã được set chưa

