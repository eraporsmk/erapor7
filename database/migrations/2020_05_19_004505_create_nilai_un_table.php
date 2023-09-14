<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiUnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_un', function (Blueprint $table) {
            $table->uuid('nilai_un_id');
			$table->uuid('sekolah_id');
			$table->uuid('pembelajaran_id');
			$table->uuid('anggota_rombel_id');
			$table->integer('nilai')->default('0')->nullable();
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('nilai_un_id');
            $table->index('sekolah_id');
			$table->index('pembelajaran_id');
			$table->index('anggota_rombel_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')
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
        Schema::table('nilai_un', function (Blueprint $table) {
            $table->dropForeign(['anggota_rombel_id']);
			$table->dropForeign(['pembelajaran_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('nilai_un');
    }
}
