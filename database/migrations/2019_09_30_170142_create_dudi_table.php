<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDudiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dudi', function (Blueprint $table) {
			$table->uuid('dudi_id');
			$table->uuid('dudi_id_dapodik');
			$table->uuid('sekolah_id');
			$table->string('nama', 100);
			$table->string('bidang_usaha_id', 10);
			$table->string('nama_bidang_usaha', 40);
			$table->string('alamat_jalan', 80);
			$table->decimal('rt', 2,0)->nullable();
			$table->decimal('rw', 2,0)->nullable();
			$table->string('nama_dusun', 60)->nullable();
			$table->string('desa_kelurahan', 60);
			$table->string('kode_wilayah', 8);
			$table->string('kode_pos', 5)->nullable();
			$table->decimal('lintang', 16,12)->nullable();
			$table->decimal('bujur', 16,12)->nullable();
			$table->string('nomor_telepon', 20)->nullable();
			$table->string('nomor_fax', 20)->nullable();
			$table->string('email', 60)->nullable();
			$table->string('website', 100)->nullable();
			$table->string('npwp', 15)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('dudi_id');
        });
		Schema::create('mou', function (Blueprint $table) {
			$table->uuid('mou_id');
			$table->uuid('mou_id_dapodik');
			$table->decimal('id_jns_ks', 6,0);
			$table->uuid('dudi_id');
			$table->uuid('dudi_id_dapodik');
			$table->uuid('sekolah_id');
			$table->string('nomor_mou', 80);
			$table->string('judul_mou', 80);
			$table->date('tanggal_mulai');
			$table->date('tanggal_selesai');
			$table->string('nama_dudi', 100);
			$table->string('npwp_dudi', 15)->nullable();
			$table->string('nama_bidang_usaha', 40);
			$table->string('telp_kantor', 20)->nullable();
			$table->string('fax', 20)->nullable();
			$table->string('contact_person', 100)->nullable();
			$table->string('telp_cp', 20)->nullable();
			$table->string('jabatan_cp', 40)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('dudi_id')->references('dudi_id')->on('dudi')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('mou_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('mou', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
			$table->dropForeign(['dudi_id']);
        });
		Schema::table('dudi', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('mou');
		Schema::dropIfExists('dudi');
    }
}
