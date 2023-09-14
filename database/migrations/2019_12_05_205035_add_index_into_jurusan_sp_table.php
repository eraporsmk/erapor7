<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoJurusanSpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurusan_sp', function (Blueprint $table) {
            $table->index('jurusan_id');
			$table->index('sekolah_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurusan_sp', function (Blueprint $table) {
            $table->dropIndex(['jurusan_id']);
			$table->dropIndex(['sekolah_id']);
        });
    }
}
