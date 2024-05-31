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
        Schema::table('unit_ukk', function (Blueprint $table) {
            if (Schema::hasColumn('unit_ukk', 'nama_unit')) {
                $table->renameColumn('nama_unit', 'nama_unit_id');
            }
            $table->string('nama_unit_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unit_ukk', function (Blueprint $table) {
            $table->dropColumn('nama_unit_en');
        });
    }
};
