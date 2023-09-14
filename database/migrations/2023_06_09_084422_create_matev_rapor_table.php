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
        Schema::create('dapodik.matev_rapor', function (Blueprint $table) {
            $table->uuid('id_evaluasi');
            $table->string('nm_mata_evaluasi', 50);// character varying(50) NOT NULL,
            $table->decimal('a_dari_template', 1, 0);// numeric(1,0) NOT NULL,
            $table->decimal('no_urut', 3, 0);// numeric(3,0) NOT NULL,
            $table->decimal('kkm_kognitif', 5, 2)->nullable();// numeric(5,2),
            $table->decimal('kkm_psikomotorik', 5, 2)->nullable();// numeric(5,2),
            $table->uuid('rombongan_belajar_id');// uuid NOT NULL,
            $table->integer('mata_pelajaran_id');// integer NOT NULL,
            $table->uuid('pembelajaran_id')->nullable();// uuid,
            $table->timestamp('create_date');
            $table->timestamp('last_update');
            $table->decimal('soft_delete', 1, 0);
            $table->timestamp('last_sync');
            $table->uuid('updater_id');
            $table->smallInteger('status')->default(0);
            $table->primary('id_evaluasi');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('mata_pelajaran_id')->references('mata_pelajaran_id')->on('ref.mata_pelajaran')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dapodik.matev_rapor');
    }
};
