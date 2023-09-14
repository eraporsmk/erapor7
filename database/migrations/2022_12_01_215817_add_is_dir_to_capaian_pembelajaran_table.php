<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDirToCapaianPembelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref.capaian_pembelajaran', function (Blueprint $table) {
            $table->decimal('is_dir', 1, 0)->nullable()->default(0);
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
            $table->dropColumn(['is_dir']);
        });
    }
}
