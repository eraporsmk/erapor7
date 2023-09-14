<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKdIdToTujuanPembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tujuan_pembelajaran', function (Blueprint $table) {
            $table->bigInteger('cp_id')->nullable()->change();
            $table->uuid('kd_id')->nullable();
            $table->foreign('kd_id')->references('kompetensi_dasar_id')->on('ref.kompetensi_dasar')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tujuan_pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['kd_id']);
            $table->dropColumn(['kd_id']);
        });
    }
}
