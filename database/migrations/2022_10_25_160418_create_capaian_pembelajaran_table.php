<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapaianPembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.capaian_pembelajaran', function (Blueprint $table) {
            $table->bigInteger('cp_id');
            $table->integer('mata_pelajaran_id');
            $table->string('fase', 5);
            $table->string('elemen');
            $table->text('deskripsi');
            $table->decimal('aktif', 1, 0)->default(1);
            $table->timestamps();
            $table->timestamp('last_sync');
			$table->primary('cp_id');
            $table->foreign('mata_pelajaran_id')->references('mata_pelajaran_id')->on('ref.mata_pelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.capaian_pembelajaran');
    }
}
