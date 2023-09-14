<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanPtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.jabatan_ptk', function (Blueprint $table) {
            $table->decimal('jabatan_ptk_id', 5, 0);
            $table->string('nama', 50);
            $table->decimal('jabatan_utama', 1, 0);
            $table->decimal('tugas_tambahan_guru', 1,0);
            $table->decimal('jumlah_jam_diakui', 2,0)->nullable();
            $table->decimal('harus_refer_unit_org', 1, 0)->default(0);
            $table->timestamps();
            $table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('jabatan_ptk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.jabatan_ptk');
    }
}
