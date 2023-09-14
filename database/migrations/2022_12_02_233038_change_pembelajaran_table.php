<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['guru_pengajar_id']);
            $table->foreign('guru_pengajar_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('set null');
        });
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
