<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsesorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asesor', function (Blueprint $table) {
            $table->uuid('asesor_id');
			$table->uuid('sekolah_id');
            $table->uuid('guru_id');
			$table->uuid('dudi_id');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_id')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('dudi_id')->references('dudi_id')->on('dudi')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('asesor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asesor', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
			$table->dropForeign(['guru_id']);
			$table->dropForeign(['dudi_id']);
        });
        Schema::dropIfExists('asesor');
    }
}
