<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiAkhirPengetahuanView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call("view:CreateOrReplaceNilaiAkhirPengetahuanView");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		  DB::statement("DROP VIEW view_nilai_akhir_pengetahuan CASCADE");
    }
}
