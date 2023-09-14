<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstWilayahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.level_wilayah', function (Blueprint $table) {
			$table->smallInteger('id_level_wilayah');
			$table->string('level_wilayah', 15);
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('id_level_wilayah');
        });
		Schema::create('ref.negara', function (Blueprint $table) {
			$table->string('negara_id', 2);
			$table->string('nama', 45);
			$table->decimal('luar_negeri', 1, 0);
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
            $table->primary('negara_id');
        });
        Schema::create('ref.mst_wilayah', function (Blueprint $table) {
			$table->string('kode_wilayah', 8);
			$table->string('nama', 60);
			$table->smallInteger('id_level_wilayah');
			$table->string('mst_kode_wilayah', 8)->nullable();
			$table->string('negara_id',2);
			$table->string('asal_wilayah', 8)->nullable();
			$table->string('kode_bps', 7)->nullable();
			$table->string('kode_dagri', 7)->nullable();
			$table->string('kode_keu', 10)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
            $table->foreign('id_level_wilayah')->references('id_level_wilayah')->on('ref.level_wilayah')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('negara_id')->references('negara_id')->on('ref.negara')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->primary('kode_wilayah');
			$table->foreign('mst_kode_wilayah')->references('kode_wilayah')->on('ref.mst_wilayah')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref.mst_wilayah', function (Blueprint $table) {
            //
        });
    }
}
