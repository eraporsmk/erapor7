<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoPesertaDidikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peserta_didik', function (Blueprint $table) {
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->index('kode_wilayah');
			$table->index('sekolah_id');
			$table->index('kerja_wali');
			$table->index('kerja_ibu');
			$table->index('kerja_ayah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peserta_didik', function (Blueprint $table) {
			$table->dropForeign(['sekolah_id']);
            $table->dropIndex(['kode_wilayah']);
			$table->dropIndex(['sekolah_id']);
			$table->dropIndex(['kerja_wali']);
			$table->dropIndex(['kerja_ibu']);
			$table->dropIndex(['kerja_ayah']);
        });
    }
}
