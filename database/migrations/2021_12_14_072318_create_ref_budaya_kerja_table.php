<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefBudayaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.budaya_kerja', function (Blueprint $table) {
            $table->smallInteger('budaya_kerja_id');
            $table->string('aspek');
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('budaya_kerja_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.budaya_kerja');
    }
}
