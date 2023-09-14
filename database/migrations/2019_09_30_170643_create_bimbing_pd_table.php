<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBimbingPdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbing_pd', function (Blueprint $table) {
            $table->uuid('bimbing_pd_id');
			$table->uuid('id_bimb_pd');
			$table->uuid('sekolah_id');
			$table->uuid('akt_pd_id');
			$table->uuid('guru_id');
			$table->uuid('ptk_id');
            $table->decimal('urutan_pembimbing', 1,0);
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('bimbing_pd_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('akt_pd_id')->references('akt_pd_id')->on('akt_pd')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_id')->references('guru_id')->on('guru')
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
        Schema::table('bimbing_pd', function($table) {
			$table->dropForeign(['sekolah_id']);
			$table->dropForeign(['akt_pd_id']);
			$table->dropForeign(['guru_id']);
		});
        Schema::dropIfExists('bimbing_pd');
    }
}
