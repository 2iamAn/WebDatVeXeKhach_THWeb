# 📦 Hướng dẫn sử dụng npm run dev trong Laravel 11

## 🎯 npm run dev là gì?

`npm run dev` là lệnh để **build và compile** các file CSS và JavaScript trong Laravel 11 khi sử dụng **Vite** (thay thế Laravel Mix cũ).

### Vite là gì?
- **Vite** là build tool hiện đại, nhanh hơn Laravel Mix
- Tự động compile CSS/JS từ `resources/` sang `public/build/`
- Hỗ trợ Hot Module Replacement (HMR) - tự động reload khi sửa code

---

## 🚀 Cách sử dụng

### 1. **Cài đặt dependencies (lần đầu tiên)**
```bash
npm install
```
Lệnh này sẽ cài đặt:
- `vite` - Build tool
- `laravel-vite-plugin` - Plugin tích hợp Vite với Laravel
- `axios` - HTTP client

### 2. **Chạy development server**
```bash
npm run dev
```

**Khi nào cần chạy?**
- ✅ **CẦN chạy:** Khi bạn sửa file CSS/JavaScript trong `resources/css/` hoặc `resources/js/`
- ❌ **KHÔNG cần:** Khi chỉ sửa PHP/Blade files

### 3. **Watch mode (tự động build khi sửa)**
```bash
npm run dev -- --watch
```
Lệnh này sẽ tự động build lại mỗi khi bạn sửa CSS/JS, không cần chạy lại.

### 4. **Production build (trước khi deploy)**
```bash
npm run build
```
Tạo file minified và optimized cho production.

---

## 📁 Cấu trúc file

```
resources/
├── css/
│   └── app.css          ← File CSS chính (sửa ở đây)
└── js/
    ├── app.js           ← File JS chính (sửa ở đây)
    └── bootstrap.js     ← Cấu hình axios, CSRF token

public/
└── build/               ← File đã compile (tự động tạo)
    ├── assets/
    │   ├── app-[hash].css
    │   └── app-[hash].js
    └── manifest.json
```

---

## 🔧 Cách hoạt động

1. **Bạn sửa code** trong `resources/css/app.css` hoặc `resources/js/app.js`
2. **Vite compile** và tạo file trong `public/build/`
3. **Laravel tự động load** file đã compile qua `@vite()` directive trong Blade

### Ví dụ trong Blade:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Laravel sẽ tự động load file đã compile từ `public/build/`.

---

## ⚠️ Lưu ý quan trọng

### 1. **Phải chạy npm run dev sau khi sửa CSS/JS**
Nếu bạn sửa `resources/css/app.css` nhưng không chạy `npm run dev`, thay đổi sẽ **KHÔNG** hiển thị trên trình duyệt.

### 2. **Không cần chạy khi chỉ sửa PHP/Blade**
Nếu chỉ sửa file `.php` hoặc `.blade.php`, không cần chạy `npm run dev`.

### 3. **Clear cache nếu có vấn đề**
```bash
php artisan optimize:clear
npm run build
```

---

## 🎨 Thêm CSS/JS mới

### Thêm file CSS mới:
1. Tạo file trong `resources/css/`, ví dụ: `custom.css`
2. Thêm vào `vite.config.js`:
```js
input: [
    'resources/css/app.css',
    'resources/css/custom.css',  // ← Thêm dòng này
    'resources/js/app.js',
],
```
3. Thêm vào Blade:
```blade
@vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
```

### Thêm file JS mới:
Tương tự như CSS, thêm vào `vite.config.js` và `@vite()` directive.

---

## 🔍 Troubleshooting

### Lỗi: "Cannot find module 'vite'"
```bash
npm install
```

### Lỗi: "Vite manifest not found"
```bash
npm run build
```

### CSS/JS không cập nhật
1. Clear cache: `php artisan optimize:clear`
2. Rebuild: `npm run build`
3. Hard refresh trình duyệt: `Ctrl + Shift + R`

---

## 📝 Tóm tắt

| Lệnh | Khi nào dùng |
|------|--------------|
| `npm install` | Lần đầu tiên hoặc sau khi update package.json |
| `npm run dev` | Khi sửa CSS/JS (development) |
| `npm run dev -- --watch` | Tự động build khi sửa (recommended) |
| `npm run build` | Trước khi deploy (production) |

---

**💡 Tip:** Chạy `npm run dev -- --watch` trong một terminal riêng và để nó chạy trong khi bạn code. Nó sẽ tự động build mỗi khi bạn save file CSS/JS!
