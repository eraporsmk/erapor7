<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class KurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref.kurikulum')->truncate();
		$json = File::get('database/data/kurikulum.json');
		$data = json_decode($json);
        foreach($data as $obj){
			$find = NULL;
			if($obj->jurusan_id){
				$find = DB::table('ref.jurusan')->where('jurusan_id', $obj->jurusan_id)->first();
			}
			DB::table('ref.kurikulum')->insert([
				'kurikulum_id' 			=> $obj->kurikulum_id,
				'nama_kurikulum' 		=> $obj->nama_kurikulum,
				'mulai_berlaku'			=> $obj->mulai_berlaku,
				'sistem_sks'			=> $obj->sistem_sks,
				'total_sks'				=> $obj->total_sks,
				'jenjang_pendidikan_id'	=> $obj->jenjang_pendidikan_id,
				'jurusan_id'			=> ($find) ? $obj->jurusan_id : NULL,
				'created_at' 			=> $obj->create_date,
				'updated_at' 			=> $obj->last_update,
				'deleted_at'			=> $obj->expired_date,
				'last_sync'				=> $obj->last_sync,
			]);
    	}
    }
}
