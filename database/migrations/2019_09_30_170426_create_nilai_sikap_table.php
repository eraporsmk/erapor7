<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiSikapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_sikap', function (Blueprint $table) {
            $table->uuid('nilai_sikap_id');
			$table->uuid('sekolah_id');
			$table->uuid('guru_id');
			$table->uuid('anggota_rombel_id');
			$table->date('tanggal_sikap');
			$table->integer('sikap_id');
			$table->integer('opsi_sikap');
			$table->text('uraian_sikap');
			$table->integer('nilai_sikap_id_erapor')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('nilai_sikap_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_id')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('sikap_id')->references('sikap_id')->on('ref.sikap')
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
        Schema::table('nilai_sikap', function (Blueprint $table) {
            $table->dropForeign(['sikap_id']);
			$table->dropForeign(['anggota_rombel_id']);
			$table->dropForeign(['guru_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('nilai_sikap');
    }
}
