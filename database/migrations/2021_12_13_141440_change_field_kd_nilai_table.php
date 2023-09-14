<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldKdNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW get_nilai_pengetahuan_siswa_by_kd CASCADE");
        DB::statement("DROP VIEW get_nilai_keterampilan_siswa_by_kd CASCADE");
        Schema::table('kd_nilai', function(Blueprint $table) {
            $table->string('id_kompetensi', 255)->change();
        });
        Artisan::call("view:CreateOrReplaceNilaiPengetahuanByKdView");
        Artisan::call("view:CreateOrReplaceNilaiKeterampilanByKdView");
        Artisan::call("view:CreateOrReplaceNilaiKeterampilanPerKdView");
        Artisan::call("view:CreateOrReplaceNilaiPengetahuanPerKdView");
        Artisan::call("view:CreateOrReplaceNilaiAkhirPengetahuanView");
        Artisan::call("view:CreateOrReplaceNilaiAkhirKeterampilanView");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
