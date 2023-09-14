<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoAnggotaAktPdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota_akt_pd', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('akt_pd_id');
			$table->index('peserta_didik_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota_akt_pd', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['akt_pd_id']);
			$table->dropIndex(['peserta_didik_id']);
        });
    }
}
