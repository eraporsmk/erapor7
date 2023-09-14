<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoNilaiSikapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai_sikap', function (Blueprint $table) {
            $table->index('sikap_id');
			$table->index('sekolah_id');
			$table->index('guru_id');
			$table->index('anggota_rombel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai_sikap', function (Blueprint $table) {
            $table->dropIndex(['sikap_id']);
			$table->dropIndex(['sekolah_id']);
			$table->dropIndex(['guru_id']);
			$table->dropIndex(['anggota_rombel_id']);
        });
    }
}
