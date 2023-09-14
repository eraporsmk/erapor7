<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRencananProjekToCatatanProjekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catatan_budaya_kerja', function (Blueprint $table) {
            $table->uuid('rencana_budaya_kerja_id')->nullable();
            $table->foreign('rencana_budaya_kerja_id')->references('rencana_budaya_kerja_id')->on('rencana_budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catatan_budaya_kerja', function (Blueprint $table) {
            $table->dropForeign(['rencana_budaya_kerja_id']);
            $table->dropColumn(['rencana_budaya_kerja_id']);
        });
    }
}
