<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoAktPdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('akt_pd', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('mou_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('akt_pd', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['mou_id']);
        });
    }
}
