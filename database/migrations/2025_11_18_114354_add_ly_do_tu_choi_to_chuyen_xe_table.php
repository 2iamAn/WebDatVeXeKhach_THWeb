<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLyDoTuChoiToChuyenXeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chuyenxe', function (Blueprint $table) {
            $table->text('LyDoTuChoi')->nullable()->after('TrangThai');
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
            $table->dropColumn('LyDoTuChoi');
        });
    }
}
