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
        Schema::create('tp_pkl', function (Blueprint $table) {
            $table->uuid('tp_pkl_id');
            $table->uuid('tp_id');
            $table->uuid('pkl_id');
            $table->timestamps();
            $table->timestamp('last_sync')->default('1901-01-01 00:00:00');
            $table->foreign('tp_id')->references('tp_id')->on('tujuan_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pkl_id')->references('pkl_id')->on('praktik_kerja_lapangan')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('tp_pkl_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_pkl');
    }
};
