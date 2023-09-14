<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTujuanPembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tujuan_pembelajaran', function (Blueprint $table) {
            $table->uuid('tp_id');
            $table->bigInteger('cp_id');
            $table->text('deskripsi');
            $table->timestamps();
            $table->timestamp('last_sync');
			$table->primary('tp_id');
            $table->foreign('cp_id')->references('cp_id')->on('ref.capaian_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tujuan_pembelajaran');
    }
}
