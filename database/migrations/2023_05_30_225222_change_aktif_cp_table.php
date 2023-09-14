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
            $table->integer('aktif')->default(1)->change();
            $table->integer('is_dir')->nullable()->default(0)->change();
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
            $table->decimal('aktif', 1, 0)->default(1)->change();
            $table->decimal('is_dir', 1, 0)->nullable()->default(0)->change();
        });
    }
};
