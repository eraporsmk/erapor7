<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoBobotKeterampilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bobot_keterampilan', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('pembelajaran_id');
			$table->index('metode_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bobot_keterampilan', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['pembelajaran_id']);
			$table->dropIndex(['metode_id']);
        });
    }
}
