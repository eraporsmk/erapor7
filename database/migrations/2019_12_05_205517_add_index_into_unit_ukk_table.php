<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoUnitUkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_ukk', function (Blueprint $table) {
            $table->index('paket_ukk_id');
			$table->index('sekolah_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unit_ukk', function (Blueprint $table) {
            $table->dropIndex(['paket_ukk_id']);
			$table->dropIndex(['sekolah_id']);
        });
    }
}
