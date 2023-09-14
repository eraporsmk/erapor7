<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoRencanaUkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rencana_ukk', function (Blueprint $table) {
            $table->index('semester_id');
			$table->index('sekolah_id');
			$table->index('internal');
			$table->index('eksternal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rencana_ukk', function (Blueprint $table) {
            $table->dropIndex(['semester_id']);
			$table->dropIndex(['sekolah_id']);
			$table->dropIndex(['internal']);
			$table->dropIndex(['eksternal']);
        });
    }
}
