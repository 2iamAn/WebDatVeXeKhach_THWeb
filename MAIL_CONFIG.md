# Hướng dẫn cấu hình Mail để gửi email từ chối yêu cầu hợp tác

## Cấu hình trong file .env

Thêm hoặc cập nhật các dòng sau trong file `.env`:

### Option 1: Sử dụng SMTP (Gmail, Outlook, etc.)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Lưu ý với Gmail:**
- Cần bật "Ứng dụng kém an toàn" hoặc tạo "Mật khẩu ứng dụng"
- Vào Google Account → Bảo mật → Mật khẩu ứng dụng → Tạo mật khẩu mới

### Option 2: Sử dụng Mailtrap (cho testing)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bustrip.com
MAIL_FROM_NAME="Bustrip"
```

### Option 3: Sử dụng log driver (chỉ để test, không gửi email thật)

```env
MAIL_MAILER=log
```

Email sẽ được lưu vào file `storage/logs/laravel.log` thay vì gửi đi.

## Kiểm tra cấu hình

Sau khi cấu hình, chạy lệnh:

```bash
php artisan config:clear
php artisan cache:clear
```

## Test gửi email

Bạn có thể test bằng cách từ chối một yêu cầu hợp tác trong admin panel. Nếu có lỗi, kiểm tra file log:

```bash
tail -f storage/logs/laravel.log
```

## Xử lý lỗi

Nếu email không được gửi:
1. Kiểm tra file log: `storage/logs/laravel.log`
2. Kiểm tra cấu hình trong `.env`
3. Đảm bảo đã chạy `php artisan config:clear`
4. Kiểm tra firewall/antivirus có chặn kết nối SMTP không

