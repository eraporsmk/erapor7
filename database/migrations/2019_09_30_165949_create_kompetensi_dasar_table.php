<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKompetensiDasarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.kompetensi_dasar', function (Blueprint $table) {
			$table->uuid('kompetensi_dasar_id');
			$table->string('id_kompetensi', 11);
			$table->integer('kompetensi_id');
			$table->integer('mata_pelajaran_id');
			$table->integer('kelas_10')->nullable();
			$table->integer('kelas_11')->nullable();
			$table->integer('kelas_12')->nullable();
			$table->integer('kelas_13')->nullable();
            $table->string('id_kompetensi_nas', 11)->nullable();
			$table->text('kompetensi_dasar');
			$table->text('kompetensi_dasar_alias')->nullable();
			$table->uuid('user_id')->nullable();
			$table->integer('aktif')->default('1');
			$table->integer('kurikulum')->default('0')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('kompetensi_dasar_id');
			$table->foreign('mata_pelajaran_id')->references('mata_pelajaran_id')->on('ref.mata_pelajaran')
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
        Schema::table('ref.kompetensi_dasar', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
        });
		Schema::dropIfExists('ref.kompetensi_dasar');
    }
}
