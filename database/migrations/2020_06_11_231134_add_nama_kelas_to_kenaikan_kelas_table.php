<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNamaKelasToKenaikanKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kenaikan_kelas', function (Blueprint $table) {
            $table->string('nama_kelas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kenaikan_kelas', function (Blueprint $table) {
            $table->dropColumn('nama_kelas');
        });
    }
}
