<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoNilaiUkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai_ukk', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('rencana_ukk_id');
			$table->index('peserta_didik_id');
			$table->index('anggota_rombel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai_ukk', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['rencana_ukk_id']);
			$table->dropIndex(['peserta_didik_id']);
			$table->dropIndex(['anggota_rombel_id']);
        });
    }
}
