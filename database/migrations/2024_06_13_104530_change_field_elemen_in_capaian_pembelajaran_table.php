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
        Schema::table('ref.capaian_pembelajaran', function (Blueprint $table) {
            $table->longText('elemen')->change();
            $table->longText('deskripsi')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref.capaian_pembelajaran', function (Blueprint $table) {
            //
        });
    }
};
