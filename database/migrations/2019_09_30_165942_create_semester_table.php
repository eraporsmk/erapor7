<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.semester', function (Blueprint $table) {
            $table->string('semester_id', 5);
            $table->decimal('tahun_ajaran_id', 4, 0);
			$table->string('nama');
			$table->decimal('semester', 1, 0);
			$table->decimal('periode_aktif', 1, 0);
			$table->date('tanggal_mulai');
			$table->date('tanggal_selesai');
			$table->timestamps();
			$table->timestamp('last_sync');
            $table->primary('semester_id');
			$table->foreign('tahun_ajaran_id')->references('tahun_ajaran_id')->on('ref.tahun_ajaran')
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
        Schema::table('ref.semester', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
        });
		Schema::dropIfExists('ref.semester');
    }
}
