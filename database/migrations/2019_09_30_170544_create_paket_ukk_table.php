<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaketUkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.paket_ukk', function (Blueprint $table) {
            $table->uuid('paket_ukk_id');
			$table->uuid('sekolah_id')->nullable();
			$table->string('jurusan_id', 25);
			$table->integer('kurikulum_id');
            $table->integer('kode_kompetensi')->nullable();
			$table->string('nomor_paket')->nullable();
			$table->string('nama_paket_id')->nullable();
			$table->string('nama_paket_en')->nullable();
			$table->integer('status')->default('1');
			$table->integer('jenis_data')->default('1');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jurusan_id')->references('jurusan_id')->on('ref.jurusan')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('kurikulum_id')->references('kurikulum_id')->on('ref.kurikulum')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('paket_ukk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref.paket_ukk', function (Blueprint $table) {
            $table->dropForeign(['kurikulum_id']);
			$table->dropForeign(['jurusan_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('ref.paket_ukk');
    }
}
