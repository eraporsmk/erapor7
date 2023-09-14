<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRencanaBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_budaya_kerja', function (Blueprint $table) {
            $table->uuid('rencana_budaya_kerja_id');
            $table->uuid('sekolah_id');
            $table->uuid('rombongan_belajar_id');
            $table->string('nama');
            $table->string('deskripsi');
            $table->timestamps();
            $table->timestamp('last_sync');
            $table->primary('rencana_budaya_kerja_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rencana_budaya_kerja');
    }
}
