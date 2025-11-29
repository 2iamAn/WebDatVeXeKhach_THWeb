<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThoiGianToTuyenDuongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tuyenduong', function (Blueprint $table) {
            if (!Schema::hasColumn('tuyenduong', 'ThoiGian')) {
                $table->string('ThoiGian', 50)->nullable()->after('KhoangCach');
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
        Schema::table('tuyenduong', function (Blueprint $table) {
            if (Schema::hasColumn('tuyenduong', 'ThoiGian')) {
                $table->dropColumn('ThoiGian');
            }
        });
    }
}
