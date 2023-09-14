<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class JenisPtkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('ref.jenis_ptk')->truncate();
      $json = File::get('database/data/jenis_ptk.json');
      $data = json_decode($json);
      foreach($data as $obj){
        DB::table('ref.jenis_ptk')->insert([
          'jenis_ptk_id' => $obj->jenis_ptk_id,
          'jenis_ptk' 				=> $obj->jenis_ptk,
          'guru_kelas' 				=> $obj->guru_kelas,
          'guru_matpel'		=> $obj->guru_matpel,
          'guru_bk' => $obj->guru_bk,
          'guru_inklusi' => $obj->guru_inklusi,
          'pengawas_satdik' => $obj->pengawas_satdik,
          'pengawas_plb' => $obj->pengawas_plb,
          'pengawas_matpel' => $obj->pengawas_matpel,
          'pengawas_bidang' => $obj->pengawas_bidang,
          'tas' => $obj->tas,
          'formal' => $obj->formal,
          'created_at' 		=> $obj->create_date,
          'updated_at' 		=> $obj->last_update,
          'deleted_at'		=> $obj->expired_date,
          'last_sync'			=> $obj->last_sync,
        ]);
      }
    }
}
