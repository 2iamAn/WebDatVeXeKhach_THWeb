<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiemLenXeAndMaXeToChuyenXeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chuyenxe', function (Blueprint $table) {
            if (!Schema::hasColumn('chuyenxe', 'DiemLenXe')) {
                $table->string('DiemLenXe', 255)->nullable()->after('MaTuyen')->comment('Điểm lên xe cụ thể');
            }
            if (!Schema::hasColumn('chuyenxe', 'DiemXuongXe')) {
                $table->string('DiemXuongXe', 255)->nullable()->after('DiemLenXe')->comment('Điểm xuống xe cụ thể');
            }
            if (!Schema::hasColumn('chuyenxe', 'MaXe')) {
                $table->integer('MaXe')->nullable()->after('DiemXuongXe');
                $table->index('MaXe');
                $table->foreign('MaXe')->references('MaXe')->on('xe')
                    ->onDelete('set null')->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chuyenxe', function (Blueprint $table) {
            $table->dropForeign(['MaXe']);
            $table->dropIndex(['MaXe']);
            $table->dropColumn(['DiemLenXe', 'DiemXuongXe', 'MaXe']);
        });
    }
}
