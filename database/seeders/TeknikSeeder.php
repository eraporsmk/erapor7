<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Teknik_penilaian;

class TeknikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('teknik_penilaian')->truncate();
        $insert_teknik = array(
				array(
					'kompetensi_id'	=> 1,
					'nama'			=> 'Tes Tertulis',
					'last_sync'		=> date('Y-m-d H:i:s'),
				),
				array(
					'kompetensi_id'	=> 1,
					'nama'			=> 'Tes Lisan',
					'last_sync'		=> date('Y-m-d H:i:s'),
				),
				array(
					'kompetensi_id'	=> 1,
					'nama'			=> 'Penugasan',
					'last_sync'		=> date('Y-m-d H:i:s'),
				),
				array(
					'kompetensi_id'	=> 2,
					'nama'			=> 'Portofolio',
					'last_sync'		=> date('Y-m-d H:i:s'),
				),
				array(
					'kompetensi_id'	=> 2,
					'nama'			=> 'Kinerja',
					'last_sync'		=> date('Y-m-d H:i:s'),
				),
				array(
					'kompetensi_id'	=> 2,
					'nama'			=> 'Proyek',
					'last_sync'		=> date('Y-m-d H:i:s'),
				),
			);
			foreach($insert_teknik as $teknik){
				Teknik_penilaian::create($teknik);
			}
    }
}
