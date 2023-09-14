<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Eloquent;
use File;

class StatusKepegawaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref.status_kepegawaian')->truncate();
        $json = File::get('database/data/status_kepegawaian.json');
		$data = json_decode($json);
        foreach($data as $obj){
			DB::table('ref.status_kepegawaian')->insert([
				'status_kepegawaian_id' 	=> $obj->status_kepegawaian_id,
				'nama' 					=> $obj->nama,
				'created_at'			=> $obj->create_date,
				'updated_at'			=> $obj->last_update,
				'deleted_at'			=> $obj->expired_date,
				'last_sync'				=> $obj->last_sync,
			]);
    	}
    }
}
