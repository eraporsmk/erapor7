<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefOpsiBudayaKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.opsi_budaya_kerja', function (Blueprint $table) {
            $table->smallInteger('opsi_id');
            $table->string('kode', 10);
            $table->string('nama', 100);
            $table->string('deskripsi');
            $table->string('warna', 10);
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('opsi_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.opsi_budaya_kerja');
    }
}
