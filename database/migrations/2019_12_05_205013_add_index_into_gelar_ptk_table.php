<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoGelarPtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gelar_ptk', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('guru_id');
			$table->index('gelar_akademik_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gelar_ptk', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['guru_id']);
			$table->dropIndex(['gelar_akademik_id']);
        });
    }
}
