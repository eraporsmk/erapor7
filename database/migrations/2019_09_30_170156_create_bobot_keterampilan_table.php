<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBobotKeterampilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bobot_keterampilan', function (Blueprint $table) {
            $table->uuid('bobot_keterampilan_id');
			$table->uuid('sekolah_id');
			$table->uuid('pembelajaran_id');
			$table->uuid('metode_id');
			$table->integer('bobot');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('bobot_keterampilan_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('metode_id')->references('teknik_penilaian_id')->on('teknik_penilaian')
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
        Schema::table('bobot_keterampilan', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
			$table->dropForeign(['pembelajaran_id']);
			$table->dropForeign(['metode_id']);
        });
		Schema::dropIfExists('bobot_keterampilan');
    }
}
