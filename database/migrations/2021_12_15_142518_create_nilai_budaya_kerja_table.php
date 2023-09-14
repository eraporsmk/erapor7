<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_budaya_kerja', function (Blueprint $table) {
            $table->uuid('nilai_budaya_kerja_id');
            $table->uuid('sekolah_id');
            $table->uuid('anggota_rombel_id');
            $table->uuid('aspek_budaya_kerja_id');
            $table->smallInteger('elemen_id');
            $table->smallInteger('opsi_id');
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('nilai_budaya_kerja_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('aspek_budaya_kerja_id')->references('aspek_budaya_kerja_id')->on('aspek_budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('elemen_id')->references('elemen_id')->on('ref.elemen_budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('opsi_id')->references('opsi_id')->on('ref.opsi_budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_budaya_kerja');
    }
}
