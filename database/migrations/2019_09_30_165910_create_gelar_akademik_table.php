<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGelarAkademikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.gelar_akademik', function (Blueprint $table) {
            $table->bigIncrements('gelar_akademik_id');
            $table->string('kode', 10);
            $table->string('nama', 40);
			$table->decimal('posisi_gelar', 1, 0);
            $table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ref.gelar_akademik');
    }
}
