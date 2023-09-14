<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pd_keluar', function (Blueprint $table) {
            $table->uuid('pd_keluar_id');
            $table->uuid('peserta_didik_id');
            $table->uuid('sekolah_id');
            $table->string('semester_id', 5);
            $table->timestamps();
            $table->timestamp('last_sync');
            $table->foreign('peserta_didik_id')->references('peserta_didik_id')->on('peserta_didik')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('ref.semester')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('pd_keluar_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pd_keluar');
    }
}
