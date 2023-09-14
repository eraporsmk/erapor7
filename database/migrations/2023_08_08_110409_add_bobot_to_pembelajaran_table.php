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
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->smallInteger('bobot_sumatif_materi')->nullable()->default(1);
            $table->smallInteger('bobot_sumatif_akhir')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropColumn(['bobot_sumatif_materi', 'bobot_sumatif_akhir']);
        });
    }
};
