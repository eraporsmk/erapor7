<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToCatatanBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catatan_budaya_kerja', function (Blueprint $table) {
            $table->smallInteger('budaya_kerja_id')->nullable();
            $table->foreign('budaya_kerja_id')->references('budaya_kerja_id')->on('ref.budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign(['budaya_kerja_id']);
            $table->dropColumn(['budaya_kerja_id']);
        });
    }
}
