<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Sikap;

class SikapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert_sikap = array(
			array(
				'butir_sikap'	=> 'Integritas',
				'last_sync'		=> date('Y-m-d H:i:s'),
				'sub_sikap'		=> array(
					array(
						'butir_sikap'	=> 'Kesetiaan',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Antikorupsi',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Keteladanan',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Keadilan',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Menghargai martabat manusia',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
				),
			),
			array(
				'butir_sikap'	=> 'Religius',
				'last_sync'		=> date('Y-m-d H:i:s'),
				'sub_sikap'		=> array(
					array(
						'butir_sikap'	=> 'Melindungi yang kecil dan tersisih',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Taat beribadah',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Menjalankan ajaran agama',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Menjauhi larangan agama',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
				),
			),
			array(
				'butir_sikap'	=> 'Nasionalis',
				'last_sync'		=> date('Y-m-d H:i:s'),
				'sub_sikap'		=> array(
					array(
						'butir_sikap'	=> 'Rela berkorban',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Taat hukum',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Unggul',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Disiplin',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Berprestasi',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Cinta damai',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
				),
			),
			array(
				'butir_sikap'	=> 'Mandiri',
				'last_sync'		=> date('Y-m-d H:i:s'),
				'sub_sikap'		=> array(
					array(
						'butir_sikap'	=> 'Tangguh',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Kerja keras',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Kreatif',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Keberanian',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Pembelajar',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Daya juang',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Berwawasan informasi dan teknologi',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
				),
			),
			array(
				'butir_sikap'	=> 'Gotong-royong',
				'last_sync'		=> date('Y-m-d H:i:s'),
				'sub_sikap'		=> array(
					array(
						'butir_sikap'	=> 'Musyawarah',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Tolong-menolong',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Kerelawanan',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Solidaritas',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
					array(
						'butir_sikap'	=> 'Antidiskriminasi',
						'last_sync'		=> date('Y-m-d H:i:s'),
					),
				),
			),
		);
		DB::table('ref.sikap')->truncate();
		foreach($insert_sikap as $sikap){
			$induk = Sikap::create([
				'butir_sikap'	=> $sikap['butir_sikap'],
				'last_sync'		=> $sikap['last_sync'],
			]);
			foreach($sikap['sub_sikap'] as $sub_sikap){
				Sikap::create([
					'sikap_induk'	=> $induk->sikap_id,
					'butir_sikap'	=> $sub_sikap['butir_sikap'],
					'last_sync'		=> $sub_sikap['last_sync'],
				]);
			}
		}
    }
}
