<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateDanhGiaTableSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS DanhGia (
                MaDanhGia INT AUTO_INCREMENT PRIMARY KEY,
                MaNguoiDung INT NOT NULL COMMENT 'Người đánh giá',
                MaNhaXe INT NOT NULL COMMENT 'Nhà xe được đánh giá',
                MaVeXe INT NULL COMMENT 'Vé xe liên quan (nếu có)',
                SoSao TINYINT NOT NULL COMMENT 'Số sao đánh giá (1-5)',
                NoiDung TEXT NULL COMMENT 'Nội dung đánh giá',
                DaMuaQua TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Đã mua vé qua hệ thống',
                NgayDanhGia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                HienThi TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Hiển thị đánh giá hay không',
                INDEX (MaNguoiDung),
                INDEX (MaNhaXe),
                INDEX (MaVeXe),
                FOREIGN KEY (MaNguoiDung) REFERENCES NguoiDung(MaNguoiDung) ON DELETE CASCADE,
                FOREIGN KEY (MaNhaXe) REFERENCES NhaXe(MaNhaXe) ON DELETE CASCADE,
                FOREIGN KEY (MaVeXe) REFERENCES VeXe(MaVeXe) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        echo "Bảng DanhGia đã được tạo thành công!\n";
    }
}

