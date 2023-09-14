<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElemenIdToAspekBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aspek_budaya_kerja', function (Blueprint $table) {
            $table->smallInteger('elemen_id')->nullable();
            $table->foreign('elemen_id')->references('elemen_id')->on('ref.elemen_budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aspek_budaya_kerja', function (Blueprint $table) {
            $table->dropForeign(['elemen_id']);
            $table->dropColumn('elemen_id');
        });
    }
}
