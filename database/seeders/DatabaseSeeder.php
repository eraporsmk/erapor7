<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
			SettingSeeder::class,
			AgamaSeeder::class,
			GelarSeeder::class,
			JenisPtkSeeder::class,
			JabatanPtkSeeder::class,
			JurusanSeeder::class,
			KelompokSeeder::class,
			KurikulumSeeder::class,
			MataPelajaranSeeder::class,
			//Mata_pelajaran_kurikulumSeeder::class,
			Mst_wilayahSeeder::class,
			PekerjaanSeeder::class,
			SemesterSeeder::class,
			SikapSeeder::class,
			RoleSeeder::class,
			StatusKepegawaianSeeder::class,
			TeknikSeeder::class,
			KDSeeder::class,
		]);
    }
}
