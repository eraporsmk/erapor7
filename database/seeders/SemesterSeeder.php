<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('ref.semester')->truncate();
		DB::table('ref.tahun_ajaran')->truncate();
		$json = File::get('database/data/tahun_ajaran.json');
		$data = json_decode($json);
        foreach($data as $obj){
			DB::table('ref.tahun_ajaran')->insert([
				'tahun_ajaran_id' 	=> $obj->tahun_ajaran_id,
				'nama' 					=> $obj->nama,
				'periode_aktif'		=> $obj->periode_aktif,
				'tanggal_mulai' 			=> $obj->tanggal_mulai,
				'tanggal_selesai'	=> $obj->tanggal_selesai,
				'created_at'			=> $obj->create_date,
				'updated_at'			=> $obj->last_update,
				'last_sync'				=> $obj->last_sync,
			]);
    	}
		$json = File::get('database/data/semester.json');
		$data = json_decode($json);
        foreach($data as $obj){
			DB::table('ref.semester')->insert([
				'semester_id' 	=> $obj->semester_id,
				'tahun_ajaran_id' 					=> $obj->tahun_ajaran_id,
				'nama'		=> $obj->nama,
				'semester' 			=> $obj->semester,
				'periode_aktif'	=> $obj->periode_aktif,
				'tanggal_mulai' => $obj->tanggal_mulai,
				'tanggal_selesai' => $obj->tanggal_selesai,
				'created_at'			=> $obj->create_date,
				'updated_at'			=> $obj->last_update,
				'last_sync'				=> $obj->last_sync,
			]);
    	}
    }
}
