<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMataPelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.mata_pelajaran', function (Blueprint $table) {
            $table->integer('mata_pelajaran_id');
			$table->string('nama');
			$table->decimal('pilihan_sekolah', 1, 0);
			$table->decimal('pilihan_buku', 1, 0);
			$table->decimal('pilihan_kepengawasan', 1, 0);
            $table->decimal('pilihan_evaluasi', 1, 0);
			$table->string('jurusan_id', 25)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('jurusan_id')->references('jurusan_id')->on('ref.jurusan')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('mata_pelajaran_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref.mata_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
        });
		Schema::dropIfExists('ref.mata_pelajaran');
    }
}
