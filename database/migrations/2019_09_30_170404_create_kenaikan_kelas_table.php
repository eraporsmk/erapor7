<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKenaikanKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kenaikan_kelas', function (Blueprint $table) {
            $table->uuid('kenaikan_kelas_id');
			$table->uuid('sekolah_id');
			$table->uuid('anggota_rombel_id');
			$table->uuid('rombongan_belajar_id');
			$table->integer('status')->default('2');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('kenaikan_kelas_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')
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
        Schema::table('kenaikan_kelas', function (Blueprint $table) {
            $table->dropForeign(['rombongan_belajar_id']);
			$table->dropForeign(['anggota_rombel_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('kenaikan_kelas');
    }
}
