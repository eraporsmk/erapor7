<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKdNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kd_nilai', function (Blueprint $table) {
            $table->uuid('kd_nilai_id');
			$table->uuid('sekolah_id');
			$table->uuid('rencana_penilaian_id');
			$table->uuid('kompetensi_dasar_id');
			$table->string('id_kompetensi',10);
			$table->uuid('kd_nilai_id_migrasi')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('kd_nilai_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('rencana_penilaian_id')->references('rencana_penilaian_id')->on('rencana_penilaian')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('kompetensi_dasar_id')->references('kompetensi_dasar_id')->on('ref.kompetensi_dasar')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
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
			$table->dropForeign(['kompetensi_dasar_id']);
            $table->dropForeign(['rencana_penilaian_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('kd_nilai');
    }
}
