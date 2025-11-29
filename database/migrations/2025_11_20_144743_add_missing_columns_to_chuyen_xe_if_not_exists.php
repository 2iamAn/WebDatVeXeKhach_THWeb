<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToChuyenXeIfNotExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Kiểm tra và thêm các cột nếu chưa có
        if (!Schema::hasColumn('chuyenxe', 'DiemLenXe')) {
            Schema::table('chuyenxe', function (Blueprint $table) {
                $table->string('DiemLenXe', 255)->nullable()->after('MaTuyen');
            });
        }
        
        if (!Schema::hasColumn('chuyenxe', 'DiemXuongXe')) {
            Schema::table('chuyenxe', function (Blueprint $table) {
                $table->string('DiemXuongXe', 255)->nullable()->after('DiemLenXe');
            });
        }
        
        if (!Schema::hasColumn('chuyenxe', 'MaXe')) {
            Schema::table('chuyenxe', function (Blueprint $table) {
                $table->integer('MaXe')->nullable()->after('DiemXuongXe');
                $table->index('MaXe');
                $table->foreign('MaXe')->references('MaXe')->on('xe')
                    ->onDelete('set null')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chuyenxe', function (Blueprint $table) {
            if (Schema::hasColumn('chuyenxe', 'MaXe')) {
                $table->dropForeign(['MaXe']);
                $table->dropIndex(['MaXe']);
            }
            if (Schema::hasColumn('chuyenxe', 'DiemLenXe')) {
                $table->dropColumn('DiemLenXe');
            }
            if (Schema::hasColumn('chuyenxe', 'DiemXuongXe')) {
                $table->dropColumn('DiemXuongXe');
            }
            if (Schema::hasColumn('chuyenxe', 'MaXe')) {
                $table->dropColumn('MaXe');
            }
        });
    }
}
