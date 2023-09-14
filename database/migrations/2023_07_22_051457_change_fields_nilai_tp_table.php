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
        Schema::table('nilai_tp', function (Blueprint $table) {
            $table->uuid('pembelajaran_id')->nullable();
            $table->uuid('tp_id')->nullable();
            $table->uuid('tp_nilai_id')->nullable()->change();
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('tp_id')->references('tp_id')->on('tujuan_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai_tp', function (Blueprint $table) {
            $table->dropForeign(['pembelajaran_id']);
            $table->dropForeign(['tp_id']);
            $table->dropColumn(['pembelajaran_id', 'tp_id']);
        });
    }
};
