<?php

/**
 * Script để fix 2 vấn đề:
 * 1. Hash lại mật khẩu trong database (từ plain text '123' sang bcrypt)
 * 2. Kiểm tra đường dẫn ảnh
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== FIX LOGIN AND IMAGES ===\n\n";

// 1. Fix passwords
echo "1. Đang hash lại mật khẩu...\n";
$users = NguoiDung::all();
$count = 0;

foreach($users as $user) {
    // Kiểm tra nếu mật khẩu là plain text '123'
    if($user->MatKhau === '123' || strlen($user->MatKhau) < 20) {
        $user->MatKhau = Hash::make('123');
        $user->save();
        $count++;
        echo "   ✓ Đã hash mật khẩu cho user: {$user->TenDangNhap}\n";
    }
}

echo "   → Đã hash {$count} mật khẩu\n\n";

// 2. Kiểm tra đường dẫn ảnh
echo "2. Đang kiểm tra đường dẫn ảnh...\n";
$imageDir = public_path('image');
$requiredImages = [
    'logo.png',
    'phuongtrang.jpg',
    'phuonghonglinh.jpg',
    'xe-tien-oanh-374838.jpg',
    'xe-viet-tan-phat.jpg',
    'nha-xe-viet-tan-phat-2.jpg',
    'nha-xe-viet-tan-phat-3.jpg',
];

$missing = [];
foreach($requiredImages as $img) {
    $path = $imageDir . '/' . $img;
    if(!file_exists($path)) {
        $missing[] = $img;
        echo "   ✗ Thiếu: {$img}\n";
    } else {
        echo "   ✓ Có: {$img}\n";
    }
}

if(empty($missing)) {
    echo "   → Tất cả ảnh đều có!\n";
} else {
    echo "   → Thiếu " . count($missing) . " ảnh\n";
}

echo "\n=== HOÀN TẤT ===\n";
echo "Bạn có thể đăng nhập với:\n";
echo "  - Username: admin, Password: 123\n";
echo "  - Username: khach1, Password: 123\n";
echo "  - Username: nhaxe1, Password: 123\n";

