<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Eloquent;
use File;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref.jurusan')->truncate();
		$json = File::get('database/data/jurusan.json');
		$data = json_decode($json);
		//Eloquent::unguard();
		//DB::statement('BEGIN;');
		//DB::statement('ALTER TABLE ref.jurusan DISABLE TRIGGER ALL;');
        foreach($data as $obj){
			$find = NULL;
			if($obj->jurusan_induk){
				$find = DB::table('ref.jurusan')->where('jurusan_id', $obj->jurusan_induk)->first();
			}
			DB::table('ref.jurusan')->updateOrInsert([
				'jurusan_id' 			=> trim($obj->jurusan_id),
				'nama_jurusan' 			=> $obj->nama_jurusan,
				'nama_jurusan_en' 		=> NULL,
				'untuk_sma'				=> $obj->untuk_sma,
				'untuk_smk'				=> $obj->untuk_smk,
				'untuk_pt'				=> $obj->untuk_pt,
				'untuk_slb'				=> $obj->untuk_slb,
				'untuk_smklb'			=> $obj->untuk_smklb,
				'jenjang_pendidikan_id'	=> $obj->jenjang_pendidikan_id,
				'jurusan_induk'			=> ($find) ? $obj->jurusan_induk : NULL,
				'level_bidang_id'		=> $obj->level_bidang_id,
				'created_at' 			=> $obj->create_date,
				'updated_at' 			=> $obj->last_update,
				'deleted_at'			=> $obj->expired_date,
				'last_sync'				=> $obj->last_sync,
			]);
    	}
		//DB::statement('ALTER TABLE ref.jurusan ENABLE TRIGGER ALL;');
		//DB::statement('COMMIT;');
    }
}
