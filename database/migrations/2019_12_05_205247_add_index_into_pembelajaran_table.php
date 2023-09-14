<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoPembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->index('semester_id');
			$table->index('sekolah_id');
			$table->index('rombongan_belajar_id');
			$table->index('mata_pelajaran_id');
			$table->index('kelompok_id');
			$table->index('guru_pengajar_id');
			$table->index('guru_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropIndex(['semester_id']);
			$table->dropIndex(['sekolah_id']);
			$table->dropIndex(['rombongan_belajar_id']);
			$table->dropIndex(['mata_pelajaran_id']);
			$table->dropIndex(['kelompok_id']);
			$table->dropIndex(['guru_pengajar_id']);
			$table->dropIndex(['guru_id']);
        });
    }
}
