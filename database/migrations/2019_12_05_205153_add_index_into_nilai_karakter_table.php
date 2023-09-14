<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoNilaiKarakterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai_karakter', function (Blueprint $table) {
            $table->index('sikap_id');
			$table->index('sekolah_id');
			$table->index('catatan_ppk_id');
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
            $table->dropIndex(['sikap_id']);
			$table->dropIndex(['sekolah_id']);
			$table->dropIndex(['catatan_ppk_id']);
        });
    }
}
