<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xe', function (Blueprint $table) {
            $table->increments('MaXe');
            $table->integer('MaNhaXe');
            $table->string('TenXe', 150);
            $table->string('LoaiXe', 100)->nullable();
            $table->integer('SoGhe')->nullable();
            $table->integer('SoGiuong')->nullable();
            $table->text('TienNghi')->nullable();
            $table->string('HinhAnh1', 255)->nullable();
            $table->string('HinhAnh2', 255)->nullable();
            $table->string('HinhAnh3', 255)->nullable();
            $table->index('MaNhaXe');
            $table->foreign('MaNhaXe')->references('MaNhaXe')->on('nhaxe')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xe');
    }
}
