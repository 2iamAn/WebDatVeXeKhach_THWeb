-- Script để hash lại mật khẩu trong database
-- Mật khẩu mặc định: '123'
-- Hash bằng bcrypt: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- Cập nhật mật khẩu cho tất cả user
UPDATE `nguoidung` SET `MatKhau` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `MatKhau` = '123';

-- Hoặc nếu muốn hash bằng PHP, chạy lệnh sau trong tinker:
-- php artisan tinker
-- $users = \App\Models\NguoiDung::all();
-- foreach($users as $user) {
--     if($user->MatKhau === '123') {
--         $user->MatKhau = \Illuminate\Support\Facades\Hash::make('123');
--         $user->save();
--     }
-- }

