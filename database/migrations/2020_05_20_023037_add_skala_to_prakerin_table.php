<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkalaToPrakerinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prakerin', function (Blueprint $table) {
            $table->string('bidang_usaha', 100)->nullable();
            $table->integer('skala')->default('0')->nullable();
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
            $table->dropColumn('bidang_usaha');
            $table->dropColumn('skala');
        });
    }
}
