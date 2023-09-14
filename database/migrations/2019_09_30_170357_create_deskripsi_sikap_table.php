<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeskripsiSikapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deskripsi_sikap', function (Blueprint $table) {
            $table->uuid('deskripsi_sikap_id');
			$table->uuid('sekolah_id');
			$table->uuid('anggota_rombel_id');
			$table->text('uraian_deskripsi_spiritual')->nullable();
			$table->text('uraian_deskripsi_sosial')->nullable();
			$table->string('predikat_spiritual', 3)->nullable();
			$table->string('predikat_sosial', 3)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('deskripsi_sikap_id');
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
        Schema::table('deskripsi_sikap', function (Blueprint $table) {
            $table->dropForeign(['anggota_rombel_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('deskripsi_sikap');
    }
}
