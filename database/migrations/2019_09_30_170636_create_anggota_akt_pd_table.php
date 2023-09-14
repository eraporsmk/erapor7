<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaAktPdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_akt_pd', function (Blueprint $table) {
            $table->uuid('anggota_akt_pd_id');
			$table->uuid('id_ang_akt_pd');
			$table->uuid('sekolah_id');
			$table->uuid('akt_pd_id');
			$table->uuid('peserta_didik_id');
            $table->string('nm_pd', 100);
			$table->string('nipd', 24);
			$table->string('jns_peran_pd')->default('3');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('anggota_akt_pd_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('akt_pd_id')->references('akt_pd_id')->on('akt_pd')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('peserta_didik_id')->references('peserta_didik_id')->on('peserta_didik')
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
        Schema::table('anggota_akt_pd', function($table) {
			$table->dropForeign(['sekolah_id']);
			$table->dropForeign(['akt_pd_id']);
			$table->dropForeign(['peserta_didik_id']);
		});
		Schema::dropIfExists('anggota_akt_pd');
    }
}
