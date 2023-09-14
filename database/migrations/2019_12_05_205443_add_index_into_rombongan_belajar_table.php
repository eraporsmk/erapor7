<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoRombonganBelajarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rombongan_belajar', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('jurusan_sp_id');
			$table->index('jurusan_id');
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
        Schema::table('rombongan_belajar', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['jurusan_sp_id']);
			$table->dropIndex(['jurusan_id']);
			$table->dropIndex(['guru_id']);
        });
    }
}
