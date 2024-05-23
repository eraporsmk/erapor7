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
        Schema::create('nilai_pts', function (Blueprint $table) {
            $table->uuid('nilai_pts_id');
            $table->uuid('rapor_pts_id');
            $table->uuid('anggota_rombel_id');
            $table->smallInteger('nilai');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->timestamp('last_sync')->default('1901-01-01 00:00:00');
            $table->foreign('rapor_pts_id')->references('rapor_pts_id')->on('rapor_pts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('nilai_pts_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_pts');
    }
};
