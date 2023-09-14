<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSikapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.sikap', function (Blueprint $table) {
			$table->increments('sikap_id');
			$table->string('butir_sikap');
			$table->integer('sikap_induk')->nullable();
			$table->integer('sikap_id_migrasi')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sikap_induk')->references('sikap_id')->on('ref.sikap')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref.sikap', function (Blueprint $table) {
            //
        });
		Schema::dropIfExists('ref.sikap');
    }
}
