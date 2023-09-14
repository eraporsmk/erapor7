<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRombel4TahunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rombel_4_tahun', function (Blueprint $table) {
            $table->uuid('rombongan_belajar_id');
			$table->uuid('sekolah_id');
			$table->string('semester_id', 5);
            $table->timestamps();
            $table->timestamp('last_sync');
			$table->primary('rombongan_belajar_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
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
        Schema::table('rombel_4_tahun', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('rombel_4_tahun');
    }
}
