<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGelarPtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gelar_ptk', function (Blueprint $table) {
            $table->uuid('gelar_ptk_id');
			$table->uuid('sekolah_id');
			$table->integer('gelar_akademik_id');
			$table->uuid('guru_id');
			$table->uuid('ptk_id');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_id')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('gelar_akademik_id')->references('gelar_akademik_id')->on('ref.gelar_akademik')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('gelar_ptk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('gelar_ptk', function (Blueprint $table) {
            $table->dropForeign(['gelar_akademik_id']);
			$table->dropForeign(['guru_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('gelar_ptk');
    }
}
