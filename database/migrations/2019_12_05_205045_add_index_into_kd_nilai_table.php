<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoKdNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kd_nilai', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('rencana_penilaian_id');
			$table->index('kompetensi_dasar_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kd_nilai', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['rencana_penilaian_id']);
			$table->dropIndex(['kompetensi_dasar_id']);
        });
    }
}
