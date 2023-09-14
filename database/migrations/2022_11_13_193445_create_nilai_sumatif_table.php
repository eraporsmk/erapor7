<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiSumatifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_sumatif', function (Blueprint $table) {
            $table->uuid('nilai_sumatif_id');
			$table->uuid('sekolah_id');
			$table->uuid('pembelajaran_id');
			$table->uuid('anggota_rombel_id');
			$table->integer('nilai');
			$table->timestamps();
			$table->timestamp('last_sync');
			$table->primary('nilai_sumatif_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_sumatif');
    }
}
