<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiTpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_tp', function (Blueprint $table) {
            $table->uuid('nilai_tp_id');
			$table->uuid('sekolah_id');
			$table->uuid('tp_nilai_id');
			$table->uuid('anggota_rombel_id');
			$table->integer('nilai');
			$table->timestamps();
			$table->timestamp('last_sync');
			$table->primary('nilai_tp_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tp_nilai_id')->references('tp_nilai_id')->on('tp_nilai')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_tp');
    }
}
