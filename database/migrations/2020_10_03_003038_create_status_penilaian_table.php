<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusPenilaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_penilaian', function (Blueprint $table) {
            $table->uuid('status_penilaian_id');
            $table->uuid('sekolah_id');
            $table->string('semester_id', 5);
            $table->decimal('status', 1, 0);
            $table->timestamps();
            $table->primary('status_penilaian_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('ref.semester')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_penilaian');
    }
}
