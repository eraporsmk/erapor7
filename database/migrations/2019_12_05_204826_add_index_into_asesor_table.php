<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoAsesorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asesor', function (Blueprint $table) {
            $table->index('sekolah_id');
			$table->index('guru_id');
			$table->index('dudi_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asesor', function (Blueprint $table) {
            $table->dropIndex(['sekolah_id']);
			$table->dropIndex(['guru_id']);
			$table->dropIndex(['dudi_id']);
        });
    }
}
