<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAspekBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aspek_budaya_kerja', function (Blueprint $table) {
            $table->uuid('aspek_budaya_kerja_id');
            $table->uuid('sekolah_id');
            $table->uuid('rencana_budaya_kerja_id');
            $table->smallInteger('budaya_kerja_id');
            $table->timestamps();
            $table->timestamp('last_sync');
            $table->primary('aspek_budaya_kerja_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('aspek_budaya_kerja');
    }
}
