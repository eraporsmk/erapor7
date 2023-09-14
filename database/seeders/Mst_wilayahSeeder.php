<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Eloquent;
use File;

class Mst_wilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//DB::table('ref.mst_wilayah')->truncate();
		//DB::table('ref.negara')->truncate();
        //DB::table('ref.level_wilayah')->truncate();
		$json = File::get('database/data/negara.json');
		$data = json_decode($json);
		$this->command->getOutput()->progressStart(count($data));
        foreach($data as $obj){
    		DB::table('ref.negara')->updateOrinsert(
				[
					'negara_id' 			=> $obj->negara_id,
				],
				[
					'nama' 		=> $obj->nama,
					'luar_negeri'			=> $obj->luar_negeri,
					'created_at' 			=> $obj->create_date,
					'updated_at' 			=> $obj->last_update,
					'deleted_at'			=> $obj->expired_date,
					'last_sync'				=> $obj->last_sync,
				]
			);
			$this->command->getOutput()->progressAdvance();
    	}
		$this->command->getOutput()->progressFinish();
		$json = File::get('database/data/level_wilayah.json');
		$data = json_decode($json);
		$this->command->getOutput()->progressStart(count($data));
        foreach($data as $obj){
    		DB::table('ref.level_wilayah')->updateOrinsert(
				[
					'id_level_wilayah' 			=> $obj->id_level_wilayah,
				],
				[
					'level_wilayah' 		=> $obj->level_wilayah,
					'created_at' 			=> $obj->create_date,
					'updated_at' 			=> $obj->last_update,
					'deleted_at'			=> $obj->expired_date,
					'last_sync'				=> $obj->last_sync,
				]
			);
			$this->command->getOutput()->progressAdvance();
    	}
		$this->command->getOutput()->progressFinish();
		for($i=0;$i<=3;$i++){
			$json = File::get('database/data/mst_wilayah_'.$i.'.json');
			$data = json_decode($json);
			$this->command->info("\n".'database/data/mst_wilayah_'.$i.'.json => '.count($data));
			$this->command->getOutput()->progressStart(count($data));
			foreach($data as $obj){
				DB::table('ref.mst_wilayah')->updateOrinsert(
					[
						'kode_wilayah' 	=> $obj->kode_wilayah,
					],
					[
						'nama' => $obj->nama,
						'id_level_wilayah' => $obj->id_level_wilayah,
						'mst_kode_wilayah' 			=> $obj->mst_kode_wilayah,
						'negara_id'	=> $obj->negara_id,
						'asal_wilayah'			=> $obj->asal_wilayah,
						'kode_bps' => $obj->kode_bps,
						'kode_dagri' => $obj->kode_dagri,
						'kode_keu' => $obj->kode_keu,
						'created_at'			=> $obj->create_date,
						'updated_at'			=> $obj->last_update,
						'deleted_at'			=> $obj->expired_date,
						'last_sync'				=> $obj->last_sync,
					]
				);
				$this->command->getOutput()->progressAdvance();
			}
			$this->command->getOutput()->progressFinish();
		}
		for($i=1;$i<=183;$i++){
			$json = File::get('database/data/mst_wilayah_4-'.$i.'.json');
			$data = json_decode($json);
			$this->command->info("\n".'database/data/mst_wilayah_4-'.$i.'.json => '.count($data));
			$this->command->getOutput()->progressStart(count($data));
			foreach($data as $obj){
				DB::table('ref.mst_wilayah')->updateOrinsert(
					[
						'kode_wilayah' 	=> $obj->kode_wilayah,
					],
					[
						'nama' => $obj->nama,
						'id_level_wilayah' => $obj->id_level_wilayah,
						'mst_kode_wilayah' 			=> $obj->mst_kode_wilayah,
						'negara_id'	=> $obj->negara_id,
						'asal_wilayah'			=> $obj->asal_wilayah,
						'kode_bps' => $obj->kode_bps,
						'kode_dagri' => $obj->kode_dagri,
						'kode_keu' => $obj->kode_keu,
						'created_at'			=> $obj->create_date,
						'updated_at'			=> $obj->last_update,
						'deleted_at'			=> $obj->expired_date,
						'last_sync'				=> $obj->last_sync,
					]
				);
				$this->command->getOutput()->progressAdvance();
			}
			$this->command->getOutput()->progressFinish();
		}
    }
}
