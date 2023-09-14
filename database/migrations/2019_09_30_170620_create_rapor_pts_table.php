<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaporPtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapor_pts', function (Blueprint $table) {
            $table->uuid('rapor_pts_id');
			$table->uuid('sekolah_id');
            $table->uuid('rombongan_belajar_id');
			$table->uuid('pembelajaran_id');
			$table->uuid('rencana_penilaian_id');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('rencana_penilaian_id')->references('rencana_penilaian_id')->on('rencana_penilaian')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('rapor_pts_id');
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
            $table->dropForeign(['rencana_penilaian_id']);
			$table->dropForeign(['pembelajaran_id']);
			$table->dropForeign(['rombongan_belajar_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('rapor_pts');
    }
}
