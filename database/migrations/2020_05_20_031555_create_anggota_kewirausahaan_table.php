<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaKewirausahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_kewirausahaan', function (Blueprint $table) {
            $table->uuid('anggota_kewirausahaan_id');
            $table->uuid('kewirausahaan_id');
			$table->uuid('anggota_rombel_id');
            $table->timestamps();
            $table->timestamp('last_sync');
            $table->primary('anggota_kewirausahaan_id');
            $table->index('kewirausahaan_id');
            $table->index('anggota_rombel_id');
			$table->foreign('kewirausahaan_id')->references('kewirausahaan_id')->on('kewirausahaan')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota_kewirausahaan');
    }
}
