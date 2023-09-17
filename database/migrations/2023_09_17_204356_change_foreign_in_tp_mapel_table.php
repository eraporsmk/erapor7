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
        Schema::table('tp_mapel', function (Blueprint $table) {
            $table->dropForeign(['tp_id']);
            $table->dropForeign(['pembelajaran_id']);
            //$table->foreignUuid('tp_id')->constrained('tujuan_pembelajaran')->onUpdate('cascade')->onDelete('cascade');
            //$table->foreignUuid('pembelajaran_id')->constrained('pembelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tp_id')->references('tp_id')->on('tujuan_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tp_mapel', function (Blueprint $table) {
            //
        });
    }
};
