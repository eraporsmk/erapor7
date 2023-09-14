<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitUkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_ukk', function (Blueprint $table) {
            $table->uuid('unit_ukk_id');
			$table->uuid('sekolah_id')->nullable();
			$table->uuid('paket_ukk_id');
			$table->string('kode_unit');
			$table->string('nama_unit');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('paket_ukk_id')->references('paket_ukk_id')->on('ref.paket_ukk')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('unit_ukk_id');
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
            $table->dropForeign(['paket_ukk_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('unit_ukk');
    }
}
