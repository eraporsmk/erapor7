<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnggotaToKdNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kd_nilai', function (Blueprint $table) {
            $table->uuid('rencana_penilaian_id')->nullable()->change();
            $table->uuid('anggota_rombel_id')->nullable();
            $table->decimal('kompeten', 1, 0)->nullable();
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kd_nilai', function (Blueprint $table) {
            $table->dropForeign(['anggota_rombel_id']);
            $table->dropColumn(['anggota_rombel_id', 'kompeten']);
        });
    }
}
