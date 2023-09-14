<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatatanBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catatan_budaya_kerja', function (Blueprint $table) {
            $table->uuid('catatan_budaya_kerja_id');
            $table->uuid('sekolah_id');
            $table->uuid('anggota_rombel_id');
            $table->text('catatan');
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('catatan_budaya_kerja_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('catatan_budaya_kerja');
    }
}
