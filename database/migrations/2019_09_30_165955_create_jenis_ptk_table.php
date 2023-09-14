<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenisPtkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.jenis_ptk', function (Blueprint $table) {
			$table->decimal('jenis_ptk_id', 2, 0);
			$table->string('jenis_ptk', 30);
			$table->decimal('guru_kelas', 1, 0);
			$table->decimal('guru_matpel', 1, 0);
            $table->decimal('guru_bk', 1, 0);
			$table->decimal('guru_inklusi', 1, 0);
			$table->decimal('pengawas_satdik', 1, 0);
			$table->decimal('pengawas_plb', 1, 0);
			$table->decimal('pengawas_matpel', 1, 0);
			$table->decimal('pengawas_bidang', 1, 0);
			$table->decimal('tas', 1, 0);
			$table->decimal('formal', 1, 0);
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->primary('jenis_ptk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.jenis_ptk');
    }
}
