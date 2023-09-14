<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->uuid('guru_id');
			$table->uuid('guru_id_dapodik')->nullable();
            $table->uuid('sekolah_id');
			$table->string('nama');
			$table->string('nuptk');
			$table->string('nip')->nullable();
			$table->string('jenis_kelamin');
			$table->string('tempat_lahir');
			$table->date('tanggal_lahir');
			$table->string('nik', 16)->nullable();
			$table->integer('jenis_ptk_id');
			$table->integer('agama_id');
			$table->integer('status_kepegawaian_id');
			$table->string('alamat')->nullable();
			$table->string('rt')->nullable();
			$table->string('rw')->nullable();
			$table->string('desa_kelurahan')->nullable();
			$table->string('kecamatan')->nullable();
			$table->string('kode_wilayah');
			$table->string('kode_pos')->nullable();
			$table->string('no_hp')->nullable();
			$table->string('email')->nullable();
			$table->string('photo')->nullable();
			$table->integer('guru_id_erapor')->nullable();
			$table->integer('is_dapodik')->default('0');
			$table->uuid('guru_id_migrasi')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('guru_id');
			$table->foreign('kode_wilayah')->references('kode_wilayah')->on('ref.mst_wilayah')
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
		Schema::table('guru', function (Blueprint $table) {
            $table->dropForeign(['kode_wilayah']);
        });
        Schema::dropIfExists('guru');
    }
}
