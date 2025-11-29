<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanhGiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danhgia', function (Blueprint $table) {
            $table->increments('MaDanhGia');
            $table->unsignedInteger('MaNguoiDung')->comment('Người đánh giá');
            $table->unsignedInteger('MaNhaXe')->comment('Nhà xe được đánh giá');
            $table->unsignedInteger('MaVeXe')->nullable()->comment('Vé xe liên quan (nếu có)');
            $table->tinyInteger('SoSao')->comment('Số sao đánh giá (1-5)');
            $table->text('NoiDung')->nullable()->comment('Nội dung đánh giá');
            $table->boolean('DaMuaQua')->default(false)->comment('Đã mua vé qua hệ thống');
            $table->timestamp('NgayDanhGia')->useCurrent();
            $table->boolean('HienThi')->default(true)->comment('Hiển thị đánh giá hay không');
            
            // Foreign keys
            $table->foreign('MaNguoiDung')->references('MaNguoiDung')->on('nguoidung')->onDelete('cascade');
            $table->foreign('MaNhaXe')->references('MaNhaXe')->on('nhaxe')->onDelete('cascade');
            $table->foreign('MaVeXe')->references('MaVe')->on('vexe')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danhgia');
    }
}
