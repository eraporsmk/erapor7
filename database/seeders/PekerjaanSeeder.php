<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref.pekerjaan')->truncate();
		$json = File::get('database/data/pekerjaan.json');
		$data = json_decode($json);
        foreach($data as $obj){
    		DB::table('ref.pekerjaan')->insert([
    			'pekerjaan_id' 	=> $obj->pekerjaan_id,
    			'nama' 			=> $obj->nama,
    			'created_at' 	=> $obj->create_date,
				'updated_at' 	=> $obj->last_update,
				'deleted_at'	=> $obj->expired_date,
				'last_sync'		=> $obj->last_sync,
    		]);
    	}
    }
}
