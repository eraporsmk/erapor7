<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiEkstrakurikulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_ekstrakurikuler', function (Blueprint $table) {
            $table->uuid('nilai_ekstrakurikuler_id');
			$table->uuid('sekolah_id');
			$table->uuid('ekstrakurikuler_id');
			$table->uuid('anggota_rombel_id');
			$table->integer('nilai')->nullable();
			$table->text('deskripsi_ekskul')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('nilai_ekstrakurikuler_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('ekstrakurikuler_id')->references('ekstrakurikuler_id')->on('ekstrakurikuler')
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
        Schema::table('nilai_ekstrakurikuler', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
			$table->dropForeign(['ekstrakurikuler_id']);
			$table->dropForeign(['anggota_rombel_id']);
        });
        Schema::dropIfExists('nilai_ekstrakurikuler');
    }
}
