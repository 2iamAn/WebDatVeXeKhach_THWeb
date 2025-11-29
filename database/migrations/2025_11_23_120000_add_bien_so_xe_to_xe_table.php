<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBienSoXeToXeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xe', function (Blueprint $table) {
            if (!Schema::hasColumn('xe', 'BienSoXe')) {
                $table->string('BienSoXe', 20)->nullable()->after('LoaiXe');
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
        Schema::table('xe', function (Blueprint $table) {
            if (Schema::hasColumn('xe', 'BienSoXe')) {
                $table->dropColumn('BienSoXe');
            }
        });
    }
}
















