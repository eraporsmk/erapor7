<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiKarakterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_karakter', function (Blueprint $table) {
            $table->uuid('nilai_karakter_id');
			$table->uuid('sekolah_id');
			$table->uuid('catatan_ppk_id');
			$table->integer('sikap_id');
			$table->text('deskripsi')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('nilai_karakter_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('catatan_ppk_id')->references('catatan_ppk_id')->on('catatan_ppk')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('sikap_id')->references('sikap_id')->on('ref.sikap')
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
        Schema::table('nilai_karakter', function (Blueprint $table) {
            $table->dropForeign(['sikap_id']);
			$table->dropForeign(['catatan_ppk_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('nilai_karakter');
    }
}
