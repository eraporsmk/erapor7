<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->uuid('sekolah_id');
			$table->string('npsn');
			$table->string('nama');
			$table->string('nss')->nullable();
			$table->string('alamat')->nullable();
			$table->string('desa_kelurahan')->nullable();
			$table->string('kecamatan')->nullable();
			$table->string('kode_wilayah')->nullable();
			$table->string('kabupaten')->nullable();
			$table->string('provinsi')->nullable();
			$table->string('kode_pos')->nullable();
			$table->string('lintang')->nullable();
			$table->string('bujur')->nullable();
			$table->string('no_telp')->nullable();
			$table->string('no_fax')->nullable();
			$table->string('email')->nullable();
			$table->string('website')->nullable();
			$table->uuid('guru_id')->nullable();
			$table->integer('status_sekolah');
			$table->integer('sinkron')->default(0);
			$table->string('logo_sekolah')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('sekolah_id');
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
		Schema::table('sekolah', function (Blueprint $table) {
            $table->dropForeign(['kode_wilayah']);
        });
        Schema::dropIfExists('sekolah');
    }
}
