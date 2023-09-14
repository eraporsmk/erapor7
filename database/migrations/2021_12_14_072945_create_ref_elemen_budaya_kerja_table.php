<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefElemenBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.elemen_budaya_kerja', function (Blueprint $table) {
            $table->smallInteger('elemen_id');
            $table->smallInteger('budaya_kerja_id');
            $table->string('elemen');
            $table->text('deskripsi');
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('elemen_id');
            $table->foreign('budaya_kerja_id')->references('budaya_kerja_id')->on('ref.budaya_kerja')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.elemen_budaya_kerja');
    }
}
