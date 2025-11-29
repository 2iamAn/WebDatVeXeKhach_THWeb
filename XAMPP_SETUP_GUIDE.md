# 🚀 Hướng dẫn chạy Laravel 11 với XAMPP

## 📋 Yêu cầu

- XAMPP đã cài đặt (Apache + MySQL + PHP 8.4)
- Composer đã cài đặt
- Node.js và npm đã cài đặt (cho Vite)

## 🔧 Bước 1: Khởi động XAMPP

1. Mở **XAMPP Control Panel**
2. Start **Apache** và **MySQL**
3. Đảm bảo cả 2 đều chạy (màu xanh)

## 📝 Bước 2: Cấu hình Database

1. Mở **phpMyAdmin**: http://localhost/phpmyadmin
2. Tạo database mới:
   - Tên database: `datvexekhach` (hoặc tên bạn muốn)
   - Chọn collation: `utf8mb4_unicode_ci`
3. Import database:
   - Chọn database vừa tạo
   - Click tab **Import**
   - Chọn file: `database/datvexekhach.sql` (hoặc file SQL của bạn)
   - Click **Go**

## ⚙️ Bước 3: Cấu hình .env

1. Mở file `.env` trong thư mục project
2. Cập nhật thông tin database:

```env
APP_NAME="DatVeXeKhach"
APP_ENV=local
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datvexekhach
DB_USERNAME=root
DB_PASSWORD=
```

**Lưu ý:**
- `DB_PASSWORD`: Để trống nếu XAMPP MySQL chưa set password
- Nếu có password, điền vào đây

## 🔑 Bước 4: Generate APP_KEY

Mở terminal/PowerShell trong thư mục project và chạy:

```bash
php artisan key:generate
```

## 📦 Bước 5: Cài đặt Dependencies

```bash
# Cài đặt PHP packages
composer install

# Cài đặt Node packages
npm install
```

## 🗄️ Bước 6: Chạy Migrations (nếu cần)

Nếu database chưa có đầy đủ cấu trúc, chạy:

```bash
php artisan migrate
```

## 🎨 Bước 7: Build Assets (CSS/JS)

**Có 2 cách:**

### Cách 1: Development (khuyến nghị khi đang phát triển)

```bash
# Chạy Vite dev server (tự động reload khi sửa CSS/JS)
npm run dev
```

**Lưu ý:** Giữ terminal này chạy trong khi làm việc.

### Cách 2: Production Build (khi đã hoàn thành)

```bash
# Build một lần
npm run build
```

## 🚀 Bước 8: Chạy Laravel Server

Mở terminal/PowerShell mới (giữ terminal `npm run dev` đang chạy) và chạy:

```bash
php artisan serve
```

## 🌐 Bước 9: Truy cập ứng dụng

Mở trình duyệt và truy cập:

```
http://127.0.0.1:8000
```

hoặc

```
http://localhost:8000
```

---

## 🔄 Quy trình chạy hàng ngày

1. **Khởi động XAMPP:**
   - Mở XAMPP Control Panel
   - Start Apache và MySQL

2. **Chạy Vite (nếu cần sửa CSS/JS):**
   ```bash
   npm run dev
   ```

3. **Chạy Laravel server:**
   ```bash
   php artisan serve
   ```

4. **Truy cập:** http://localhost:8000

---

## 🛠️ Cách 2: Chạy trực tiếp qua Apache (Virtual Host)

Nếu muốn chạy trực tiếp qua Apache thay vì `php artisan serve`:

### 1. Cấu hình Virtual Host

Mở file `C:\xampp\apache\conf\extra\httpd-vhosts.conf` và thêm:

```apache
<VirtualHost *:80>
    ServerName datvexekhach.local
    DocumentRoot "D:/DOANTTCN/DatVeXeKhach/public"
    
    <Directory "D:/DOANTTCN/DatVeXeKhach/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Lưu ý:** Thay đường dẫn `D:/DOANTTCN/DatVeXeKhach` bằng đường dẫn thực tế của bạn.

### 2. Cập nhật hosts file

Mở file `C:\Windows\System32\drivers\etc\hosts` (với quyền Admin) và thêm:

```
127.0.0.1    datvexekhach.local
```

### 3. Restart Apache

Trong XAMPP Control Panel, click **Stop** rồi **Start** lại Apache.

### 4. Truy cập

Mở trình duyệt: `http://datvexekhach.local`

---

## ⚠️ Xử lý lỗi thường gặp

### Lỗi: "SQLSTATE[HY000] [1045] Access denied"

**Nguyên nhân:** Sai username/password database

**Giải pháp:**
- Kiểm tra `DB_USERNAME` và `DB_PASSWORD` trong `.env`
- Mặc định XAMPP: `root` / password trống

### Lỗi: "SQLSTATE[HY000] [2002] No connection could be made"

**Nguyên nhân:** MySQL chưa chạy

**Giải pháp:**
- Mở XAMPP Control Panel
- Start MySQL

### Lỗi: "Vite manifest not found"

**Nguyên nhân:** Chưa build assets

**Giải pháp:**
```bash
npm run dev
# hoặc
npm run build
```

### Lỗi: "Class 'PDO' not found"

**Nguyên nhân:** PHP extension chưa bật

**Giải pháp:**
1. Mở `C:\xampp\php\php.ini`
2. Tìm và bỏ comment (xóa dấu `;`) các dòng:
   ```ini
   extension=pdo_mysql
   extension=mysqli
   ```
3. Restart Apache

### Lỗi: "Storage link not found"

**Giải pháp:**
```bash
php artisan storage:link
```

---

## 📌 Lưu ý quan trọng

1. **Luôn chạy `npm run dev`** khi đang phát triển và sửa CSS/JS
2. **Chạy `npm run build`** trước khi deploy
3. **Giữ XAMPP Apache và MySQL chạy** trong khi làm việc
4. **Kiểm tra PHP version:** Chạy `php -v` để đảm bảo PHP 8.4+

---

## 🎯 Tóm tắt nhanh

```bash
# 1. Start XAMPP (Apache + MySQL)

# 2. Cài đặt dependencies
composer install
npm install

# 3. Cấu hình .env (database, APP_KEY)

# 4. Generate key
php artisan key:generate

# 5. Chạy migrations (nếu cần)
php artisan migrate

# 6. Build assets
npm run dev  # (giữ terminal này chạy)

# 7. Chạy server (terminal mới)
php artisan serve

# 8. Truy cập: http://localhost:8000
```

---

**Chúc bạn thành công! 🎉**

