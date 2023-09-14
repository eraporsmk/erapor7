<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoDeskripsiSikapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deskripsi_sikap', function (Blueprint $table) {
            $table->index('sekolah_id');
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
        Schema::table('deskripsi_sikap', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['anggota_rombel_id']);
        });
    }
}
