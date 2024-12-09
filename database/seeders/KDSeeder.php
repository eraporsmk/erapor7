<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;
//use App\Kompetensi_dasar;
//use App\Kd;
//use Illuminate\Support\Facades\Storage;
class KDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//DB::table('ref.kompetensi_dasar')->truncate();
		$this->command->info("Memulai proses import ref. Kompetensi Dasar");
		for($i=1;$i<=169;$i++){
			$json = File::get('database/data/kompetensi_dasar-'.$i.'.json');
			$data = json_decode($json);
			foreach($data as $obj){
				$find = DB::table('ref.mata_pelajaran')->where('mata_pelajaran_id', $obj->mata_pelajaran_id)->first();
				if($find){
					DB::table('ref.kompetensi_dasar')->insert([
						'kompetensi_dasar_id' 	=> $obj->kompetensi_dasar_id,
						'id_kompetensi' => $obj->id_kompetensi,
						'kompetensi_id' => $obj->kompetensi_id,
						'mata_pelajaran_id' 			=> $obj->mata_pelajaran_id,
						'kelas_10'	=> $obj->kelas_10,
						'kelas_11'			=> $obj->kelas_11,
						'kelas_12' => $obj->kelas_12,
						'kelas_13' => $obj->kelas_13,
						'id_kompetensi_nas' => $obj->id_kompetensi_nas,
						'kompetensi_dasar' => $obj->kompetensi_dasar,
						'kompetensi_dasar_alias'			=> $obj->kompetensi_dasar_alias,
						'user_id'			=> $obj->user_id,
						'aktif'			=> $obj->aktif,
						'kurikulum'				=> $obj->kurikulum,
						'created_at' => $obj->created_at,
						'updated_at' => $obj->updated_at,
						'deleted_at' => $obj->deleted_at,
						'last_sync' => $obj->last_sync,
					]);
					$this->command->info('mata_pelajaran_id: '.$obj->mata_pelajaran_id);
				} else {
					$this->command->error('mata_pelajaran_id: '.$obj->mata_pelajaran_id.' belum terdaftar di database!');
				}
			}
		}
		$this->command->info("Proses import ref. Kompetensi Dasar selesai");
    }
}
