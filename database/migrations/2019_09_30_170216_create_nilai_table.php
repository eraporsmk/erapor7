<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->uuid('nilai_id');
			$table->uuid('sekolah_id');
			$table->uuid('kd_nilai_id');
			$table->uuid('anggota_rombel_id');
			$table->integer('kompetensi_id');
			$table->integer('nilai');
			$table->string('rerata', 10);
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('nilai_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('kd_nilai_id')->references('kd_nilai_id')->on('kd_nilai')
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
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
			$table->dropForeign(['kd_nilai_id']);
			$table->dropForeign(['anggota_rombel_id']);
        });
        Schema::dropIfExists('nilai');
    }
}
