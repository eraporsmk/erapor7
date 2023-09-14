<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->uuid('pkl_id');
            $table->uuid('sekolah_id');
            $table->uuid('guru_id');
            $table->uuid('rombongan_belajar_id');
            $table->uuid('akt_pd_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('semester_id', 5);
            $table->timestamps();
            $table->timestamp('last_sync')->default('1901-01-01 00:00:00');
            $table->primary('pkl_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('akt_pd_id')->references('akt_pd_id')->on('akt_pd')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('ref.semester')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('praktik_kerja_lapangan');
    }
};
