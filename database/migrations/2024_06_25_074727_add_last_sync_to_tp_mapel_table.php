<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tp_mapel', function (Blueprint $table) {
            $table->timestamp('last_sync')->default('1901-01-01 00:00:00');
        });
        Schema::table('kasek', function (Blueprint $table) {
            $table->timestamp('last_sync')->default('1901-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tp_mapel', function (Blueprint $table) {
            $table->dropColumn('last_sync');
        });
        Schema::table('kasek', function (Blueprint $table) {
            $table->dropColumn('last_sync');
        });
    }
};
