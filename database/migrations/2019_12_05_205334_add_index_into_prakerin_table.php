<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoPrakerinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prakerin', function (Blueprint $table) {
            $table->index('anggota_rombel_id');
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
        Schema::table('prakerin', function (Blueprint $table) {
            $table->dropIndex(['anggota_rombel_id']);
			$table->dropIndex(['sekolah_id']);
        });
    }
}
