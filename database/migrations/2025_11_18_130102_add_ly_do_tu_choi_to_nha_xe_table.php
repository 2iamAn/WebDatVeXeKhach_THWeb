<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLyDoTuChoiToNhaXeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhaxe', function (Blueprint $table) {
            $table->text('LyDoTuChoi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nhaxe', function (Blueprint $table) {
            $table->dropColumn('LyDoTuChoi');
        });
    }
}
