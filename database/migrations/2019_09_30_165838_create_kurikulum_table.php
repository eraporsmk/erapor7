<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKurikulumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.kurikulum', function (Blueprint $table) {
            $table->smallInteger('kurikulum_id');
			$table->string('nama_kurikulum', 120);
			$table->date('mulai_berlaku');
			$table->decimal('sistem_sks', 1, 0);
            $table->decimal('total_sks', 3, 0);
			$table->decimal('jenjang_pendidikan_id', 2, 0);
			$table->string('jurusan_id', 25)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('jurusan_id')->references('jurusan_id')->on('ref.jurusan')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('kurikulum_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref.kurikulum', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
        });
		Schema::dropIfExists('ref.kurikulum');
    }
}
