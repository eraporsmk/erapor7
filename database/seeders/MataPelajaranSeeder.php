<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref.mata_pelajaran')->truncate();
		$json = File::get('database/data/mata_pelajaran.json');
		$data = json_decode($json);
        foreach($data as $obj){
			$find = DB::table('ref.jurusan')->where('jurusan_id', $obj->jurusan_id)->first();
			DB::table('ref.mata_pelajaran')->insert([
				'mata_pelajaran_id' 	=> $obj->mata_pelajaran_id,
				'nama' 					=> $obj->nama,
				'pilihan_sekolah'		=> $obj->pilihan_sekolah,
				'pilihan_buku' 			=> $obj->pilihan_buku,
				'pilihan_kepengawasan'	=> $obj->pilihan_kepengawasan,
				'pilihan_evaluasi'		=> $obj->pilihan_evaluasi,
				'jurusan_id'			=> ($find) ? $obj->jurusan_id : NULL,
				'created_at'			=> $obj->create_date,
				'updated_at'			=> $obj->last_update,
				'deleted_at'			=> $obj->expired_date,
				'last_sync'				=> $obj->last_sync,
			]);
    	}
    }
}
