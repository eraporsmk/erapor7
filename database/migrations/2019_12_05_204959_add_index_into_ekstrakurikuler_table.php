<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoEkstrakurikulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ekstrakurikuler', function (Blueprint $table) {
            $table->index('semester_id');
			$table->index('sekolah_id');
			$table->index('rombongan_belajar_id');
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
        Schema::table('ekstrakurikuler', function (Blueprint $table) {
            $table->dropIndex(['semester_id']);
			$table->dropIndex(['sekolah_id']);
			$table->dropIndex(['rombongan_belajar_id']);
			$table->dropIndex(['guru_id']);
        });
    }
}
