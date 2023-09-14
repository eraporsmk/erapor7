<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKewirausahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kewirausahaan', function (Blueprint $table) {
            $table->uuid('kewirausahaan_id');
			$table->uuid('sekolah_id');
            $table->uuid('anggota_rombel_id')->nullable();
            $table->string('pola', 10);
            $table->string('jenis', 10);
			$table->string('nama_produk');
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('kewirausahaan_id');
            $table->index('sekolah_id');
			$table->index('anggota_rombel_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
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
        Schema::dropIfExists('kewirausahaan');
    }
}
