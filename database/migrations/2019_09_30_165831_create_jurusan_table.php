<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.jurusan', function (Blueprint $table) {
            $table->string('jurusan_id', 25);
			$table->string('nama_jurusan', 100);
			$table->string('nama_jurusan_en', 100)->nullable();
			$table->decimal('untuk_sma', 1, 0);
			$table->decimal('untuk_smk', 1, 0);
			$table->decimal('untuk_pt', 1, 0);
			$table->decimal('untuk_slb', 1, 0);
			$table->decimal('untuk_smklb', 1, 0);
			$table->decimal('jenjang_pendidikan_id', 1, 0)->nullable();
			$table->string('jurusan_induk', 25)->nullable();
			$table->string('level_bidang_id', 5);
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('jurusan_id');
			$table->foreign('jurusan_induk')->references('jurusan_id')->on('ref.jurusan')
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
        Schema::table('ref.jurusan', function (Blueprint $table) {
            $table->dropForeign(['jurusan_induk']);
        });
		Schema::dropIfExists('ref.jurusan');
    }
}
