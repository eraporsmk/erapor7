<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRencanaUkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_ukk', function (Blueprint $table) {
            $table->uuid('rencana_ukk_id');
			$table->uuid('sekolah_id');
			$table->string('semester_id', 5);
			$table->uuid('paket_ukk_id');
            $table->uuid('internal');
			$table->uuid('eksternal');
			$table->date('tanggal_sertifikat');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('semester_id')->references('semester_id')->on('ref.semester')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('internal')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('eksternal')->references('guru_id')->on('guru')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('rencana_ukk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rencana_ukk', function (Blueprint $table) {
            $table->dropForeign(['eksternal']);
			$table->dropForeign(['internal']);
			$table->dropForeign(['semester_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('rencana_ukk');
    }
}
