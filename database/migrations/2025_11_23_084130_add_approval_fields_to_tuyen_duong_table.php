<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalFieldsToTuyenDuongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tuyenduong', function (Blueprint $table) {
            $table->integer('MaNhaXe')->nullable()->after('MaTuyen');
            $table->string('TrangThai', 20)->default('ChoDuyet')->after('KhoangCach');
            $table->text('LyDoTuChoi')->nullable()->after('TrangThai');
            
            $table->index('MaNhaXe');
            $table->index('TrangThai');
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
            $table->dropIndex(['MaNhaXe']);
            $table->dropIndex(['TrangThai']);
            $table->dropColumn(['MaNhaXe', 'TrangThai', 'LyDoTuChoi']);
        });
    }
}
