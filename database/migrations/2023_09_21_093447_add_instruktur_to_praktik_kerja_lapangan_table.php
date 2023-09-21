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
        Schema::table('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->string('instruktur')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->dropColumn('instruktur');
        });
    }
};
