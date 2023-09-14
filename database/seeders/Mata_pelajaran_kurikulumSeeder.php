<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class Mata_pelajaran_kurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref.mata_pelajaran_kurikulum')->truncate();
		for($i=1;$i<=170;$i++){
			//$this->command->info($i);
			$json = File::get('database/data/mata_pelajaran_kurikulum-'.$i.'.json');
			$data = json_decode($json);
			foreach($data as $obj){
				$find = DB::table('ref.kurikulum')->where('kurikulum_id', $obj->kurikulum_id)->first();
				if($find){
					DB::table('ref.mata_pelajaran_kurikulum')->insert([
						'kurikulum_id' 	=> $obj->kurikulum_id,
						'mata_pelajaran_id' => $obj->mata_pelajaran_id,
						'tingkat_pendidikan_id' => $obj->tingkat_pendidikan_id,
						'jumlah_jam' 			=> $obj->jumlah_jam,
						'jumlah_jam_maksimum'	=> $obj->jumlah_jam_maksimum,
						'wajib'			=> $obj->wajib,
						'sks' => $obj->sks,
						'a_peminatan' => $obj->a_peminatan,
						'area_kompetensi' => $obj->area_kompetensi,
						'gmp_id' => $obj->gmp_id,
						'created_at'			=> $obj->create_date,
						'updated_at'			=> $obj->last_update,
						'deleted_at'			=> $obj->expired_date,
						'last_sync'				=> $obj->last_sync,
					]);
				}
			}
		}
    }
}
