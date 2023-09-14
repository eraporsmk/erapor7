<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKdIdToTpNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tp_nilai', function (Blueprint $table) {
            $table->uuid('tp_id')->nullable()->change();
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
        Schema::table('tp_nilai', function (Blueprint $table) {
            $table->dropForeign(['kd_id']);
            $table->dropColumn(['kd_id']);
        });
    }
}
