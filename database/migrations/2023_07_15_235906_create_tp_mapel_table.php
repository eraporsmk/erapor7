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
        Schema::create('tp_mapel', function (Blueprint $table) {
            $table->uuid('tp_mapel_id');
            $table->uuid('tp_id');
            $table->uuid('pembelajaran_id');
            $table->timestamps();
            $table->foreign('tp_id')->references('tp_id')->on('tujuan_pembelajaran')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->primary('tp_mapel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_mapel');
    }
};
