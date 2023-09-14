<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPembelajaranIndukToPembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->uuid('induk_pembelajaran_id')->nullable();
            $table->foreign('induk_pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign(['induk_pembelajaran_id']);
            $table->dropColumn('induk_pembelajaran_id');
        });
    }
}
