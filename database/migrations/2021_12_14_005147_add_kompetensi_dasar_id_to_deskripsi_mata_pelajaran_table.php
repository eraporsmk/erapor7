<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKompetensiDasarIdToDeskripsiMataPelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deskripsi_mata_pelajaran', function (Blueprint $table) {
            $table->uuid('kompetensi_dasar_id')->nullable();
            $table->foreign('kompetensi_dasar_id')->references('kompetensi_dasar_id')->on('ref.kompetensi_dasar')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deskripsi_mata_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['kompetensi_dasar_id']);
            $table->dropColumn('kompetensi_dasar_id');
        });
    }
}
