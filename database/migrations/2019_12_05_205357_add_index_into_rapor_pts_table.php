<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoRaporPtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rapor_pts', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('rombongan_belajar_id');
			$table->index('rencana_penilaian_id');
			$table->index('pembelajaran_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rapor_pts', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['rombongan_belajar_id']);
			$table->dropIndex(['rencana_penilaian_id']);
			$table->dropIndex(['pembelajaran_id']);
        });
    }
}
