<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_nilai', function (Blueprint $table) {
            $table->uuid('tp_nilai_id');
			$table->uuid('sekolah_id');
			$table->uuid('rencana_penilaian_id')->nullable();
            $table->uuid('anggota_rombel_id')->nullable();
            $table->bigInteger('cp_id')->nullable();
			$table->uuid('tp_id');
            $table->decimal('kompeten', 1, 0);
			$table->timestamps();
			$table->timestamp('last_sync');
			$table->primary('tp_nilai_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('rencana_penilaian_id')->references('rencana_penilaian_id')->on('rencana_penilaian')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('cp_id')->references('cp_id')->on('ref.capaian_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_nilai');
    }
}
