# 🔧 Hướng dẫn sửa lỗi ảnh không hiển thị

## Nguyên nhân
Ảnh không hiển thị thường do:
1. `APP_URL` trong `.env` chưa đúng
2. Cache chưa được clear
3. Đường dẫn ảnh không đúng

## Cách sửa

### Bước 1: Kiểm tra `.env`

Mở file `.env` và đảm bảo:

```env
APP_URL=http://localhost:8000
```

**Lưu ý:** 
- Nếu chạy bằng `php artisan serve` → dùng `http://localhost:8000`
- Nếu chạy bằng XAMPP Apache → dùng `http://localhost` hoặc `http://datvexekhach.local`

### Bước 2: Clear cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Bước 3: Kiểm tra đường dẫn ảnh

Đảm bảo ảnh nằm trong: `public/image/`

Ví dụ:
- `public/image/logo.png` ✅
- `public/image/phuongtrang.jpg` ✅

### Bước 4: Kiểm tra trong trình duyệt

1. Mở Developer Tools (F12)
2. Vào tab **Network**
3. Reload trang
4. Xem các request ảnh có bị lỗi 404 không

Nếu ảnh bị 404, kiểm tra:
- URL ảnh trong HTML: `http://localhost:8000/image/logo.png`
- File có tồn tại: `public/image/logo.png`

## Test nhanh

Mở trình duyệt và truy cập trực tiếp:
- http://localhost:8000/image/logo.png

Nếu thấy ảnh → Ảnh OK, vấn đề ở code
Nếu không thấy → Kiểm tra đường dẫn file

