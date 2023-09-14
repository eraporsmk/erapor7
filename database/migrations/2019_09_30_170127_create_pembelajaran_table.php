<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelajaran', function (Blueprint $table) {
            $table->uuid('pembelajaran_id');
			$table->uuid('pembelajaran_id_dapodik')->nullable();
			$table->uuid('sekolah_id');
			$table->string('semester_id', 5);
			$table->uuid('rombongan_belajar_id');
			$table->uuid('guru_id')->nullable();
			$table->uuid('guru_pengajar_id')->nullable();
            $table->integer('mata_pelajaran_id');
			$table->string('nama_mata_pelajaran');
			$table->integer('kelompok_id')->nullable();
			$table->integer('no_urut')->nullable();
			$table->integer('kkm')->nullable();
			$table->integer('is_dapodik')->nullable();
			$table->integer('rasio_p')->nullable();
			$table->integer('rasio_k')->nullable();
			$table->uuid('pembelajaran_id_migrasi')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('mata_pelajaran_id')->references('mata_pelajaran_id')->on('ref.mata_pelajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('semester_id')->references('semester_id')->on('ref.semester')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_id')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_pengajar_id')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('kelompok_id')->references('kelompok_id')->on('ref.kelompok')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('pembelajaran_id');
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
            $table->dropForeign(['sekolah_id']);
			$table->dropForeign(['mata_pelajaran_id']);
			$table->dropForeign(['rombongan_belajar_id']);
			$table->dropForeign(['semester_id']);
			$table->dropForeign(['guru_id']);
			$table->dropForeign(['guru_pengajar_id']);
			$table->dropForeign(['kelompok_id']);
        });
        Schema::dropIfExists('pembelajaran');
    }
}
