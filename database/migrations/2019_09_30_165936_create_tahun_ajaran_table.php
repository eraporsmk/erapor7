<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTahunAjaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.tahun_ajaran', function (Blueprint $table) {
            $table->decimal('tahun_ajaran_id', 4, 0);
            $table->string('nama', 10);
			$table->decimal('periode_aktif', 1, 0);
			$table->date('tanggal_mulai');
			$table->date('tanggal_selesai');
			$table->timestamps();
			$table->timestamp('last_sync');
            $table->primary('tahun_ajaran_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.tahun_ajaran');
    }
}
