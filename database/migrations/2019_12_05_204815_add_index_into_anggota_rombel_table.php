<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoAnggotaRombelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota_rombel', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('peserta_didik_id');
			$table->index('rombongan_belajar_id');
			$table->index('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota_rombel', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['peserta_didik_id']);
			$table->dropIndex(['rombongan_belajar_id']);
			$table->dropIndex(['semester_id']);
        });
    }
}
