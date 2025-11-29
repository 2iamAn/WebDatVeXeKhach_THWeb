<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DanhGiaSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('DanhGia')->insert([
            [
                'MaNguoiDung' => 1,
                'MaNhaXe' => 1, // Việt Tân Phát
                'MaVeXe' => null,
                'SoSao' => 5,
                'NoiDung' => 'Nhà xe rất ok, tài xế lái xe rất cẩn thận, xe mới và sạch sẽ. Nhân viên phục vụ nhiệt tình. Mình sẽ tiếp tục ủng hộ nhà xe!',
                'DaMuaQua' => true,
                'NgayDanhGia' => $now->copy()->subDays(5),
                'HienThi' => true,
            ],
            [
                'MaNguoiDung' => 2,
                'MaNhaXe' => 1,
                'MaVeXe' => null,
                'SoSao' => 4,
                'NoiDung' => 'Xe đi đúng giờ, ghế ngồi thoải mái. Tuy nhiên giá vé hơi cao so với các nhà xe khác.',
                'DaMuaQua' => false,
                'NgayDanhGia' => $now->copy()->subDays(10),
                'HienThi' => true,
            ],
            [
                'MaNguoiDung' => 3,
                'MaNhaXe' => 2, // Tiến Oanh
                'MaVeXe' => null,
                'SoSao' => 3,
                'NoiDung' => 'Xe khá ổn nhưng điều hòa hơi yếu. Tài xế lái xe ok.',
                'DaMuaQua' => true,
                'NgayDanhGia' => $now->copy()->subDays(15),
                'HienThi' => true,
            ],
        ]);
        
        echo "Đã tạo dữ liệu mẫu cho bảng DanhGia!\n";
    }
}

