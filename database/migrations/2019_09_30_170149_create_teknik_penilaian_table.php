<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeknikPenilaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teknik_penilaian', function (Blueprint $table) {
            $table->uuid('teknik_penilaian_id');
            $table->uuid('sekolah_id')->nullable();
			$table->integer('kompetensi_id');
            $table->string('nama');
			$table->integer('bobot')->nullable();
			$table->uuid('teknik_penilaian_id_migrasi')->nullable();
            $table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('teknik_penilaian_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teknik_penilaian', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
        });
		Schema::dropIfExists('teknik_penilaian');
    }
}
