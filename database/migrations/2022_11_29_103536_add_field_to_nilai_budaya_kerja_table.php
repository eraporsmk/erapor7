<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToNilaiBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai_budaya_kerja', function (Blueprint $table) {
            $table->uuid('guru_id')->nullable();
            $table->uuid('aspek_budaya_kerja_id')->nullable()->change();
            $table->smallInteger('budaya_kerja_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('budaya_kerja_id')->references('budaya_kerja_id')->on('ref.budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai_budaya_kerja', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            if (Schema::hasColumn('nilai_budaya_kerja', 'budaya_kerja_id')) {
                $table->dropForeign(['budaya_kerja_id']);
                $table->dropColumn(['guru_id', 'budaya_kerja_id', 'tanggal', 'deskripsi']);
            } else {
                $table->dropColumn(['guru_id', 'tanggal', 'deskripsi']);
            }
        });
    }
}
