<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeskripsiMataPelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deskripsi_mata_pelajaran', function (Blueprint $table) {
            $table->uuid('deskripsi_mata_pelajaran_id');
			$table->uuid('sekolah_id');
			$table->uuid('anggota_rombel_id');
			$table->uuid('pembelajaran_id');
			$table->text('deskripsi_pengetahuan')->nullable();
			$table->text('deskripsi_keterampilan')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('deskripsi_mata_pelajaran_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')
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
        Schema::table('deskripsi_mata_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['pembelajaran_id']);
			$table->dropForeign(['anggota_rombel_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('deskripsi_mata_pelajaran');
    }
}
