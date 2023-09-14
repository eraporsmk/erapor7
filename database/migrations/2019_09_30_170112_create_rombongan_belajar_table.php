<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRombonganBelajarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rombongan_belajar', function (Blueprint $table) {
            $table->uuid('rombongan_belajar_id');
			$table->uuid('sekolah_id');
			$table->string('semester_id', 5);
			$table->string('jurusan_id', 25)->nullable();
			$table->uuid('jurusan_sp_id')->nullable();
            $table->integer('kurikulum_id');
			$table->string('nama');
			$table->uuid('guru_id');
			$table->uuid('ptk_id')->nullable();
			$table->integer('tingkat');
			$table->decimal('jenis_rombel', 2, 0);
			$table->uuid('rombel_id_dapodik');
			$table->integer('kunci_nilai')->default('0');
			$table->uuid('rombongan_belajar_id_migrasi')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('rombongan_belajar_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_id')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jurusan_id')->references('jurusan_id')->on('ref.jurusan')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jurusan_sp_id')->references('jurusan_sp_id')->on('jurusan_sp')
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
		Schema::table('rombongan_belajar', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
			$table->dropForeign(['guru_id']);
			$table->dropForeign(['jurusan_id']);
			$table->dropForeign(['jurusan_sp_id']);
        });
        Schema::dropIfExists('rombongan_belajar');
    }
}
