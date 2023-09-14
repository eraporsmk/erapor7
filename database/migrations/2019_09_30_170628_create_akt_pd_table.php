<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAktPdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akt_pd', function (Blueprint $table) {
            $table->uuid('akt_pd_id');
			$table->uuid('akt_pd_id_dapodik');
			$table->uuid('sekolah_id');
            $table->uuid('mou_id');
			$table->decimal('id_jns_akt_pd', 3, 0);
			$table->string('judul_akt_pd', 500);
			$table->string('sk_tugas', 80);
			$table->date('tgl_sk_tugas')->nullable();
			$table->text('ket_akt')->nullable();
			$table->decimal('a_komunal', 1, 0);
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('akt_pd_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('mou_id')->references('mou_id')->on('mou')
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
        Schema::table('akt_pd', function($table) {
			$table->dropForeign(['sekolah_id']);
			$table->dropForeign(['mou_id']);
		});
		Schema::dropIfExists('akt_pd');
    }
}
