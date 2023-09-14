<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class GelarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('ref.gelar_akademik')->truncate();
		$json = File::get('database/data/gelar.json');
		$data = json_decode($json);
        foreach($data as $obj){
			DB::table('ref.gelar_akademik')->updateOrInsert(
				[
					'gelar_akademik_id' => $obj->gelar_akademik_id,
				],
				[
					'kode' 				=> $obj->kode,
					'nama' 				=> $obj->nama,
					'posisi_gelar'		=> $obj->posisi_gelar,
					'created_at' 		=> $obj->created_at,
					'updated_at' 		=> $obj->updated_at,
					'deleted_at'		=> $obj->deleted_at,
					'last_sync'			=> now(),
				]
			);
    	}
    }
}
