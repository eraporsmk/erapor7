<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
			array('name' => 'superadmin','display_name' => 'Super Administrator', 'description' => 'Super Administrator'),
			array('name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Administrator'),
			array('name' => 'tu', 'display_name' => 'Tata Usaha','description' => 'Tata Usaha'),
			array('name' => 'guru', 'display_name' => 'Guru','description' => 'Guru'),
			array('name' => 'siswa', 'display_name' => 'Peserta Didik','description' => 'Peserta Didik'),
			array('name' => 'user', 'display_name' => 'General User','description' => 'General User'),
			array('name' => 'waka', 'display_name' => 'Waka Kurikulum','description' => 'Waka Kurikulum'),
			array('name' => 'kaprog', 'display_name' => 'Kepala Program','description' => 'Kepala Program'),
			array('name' => 'internal', 'display_name' => 'Penguji Internal UKK','description' => 'Penguji Internal UKK'),
			array('name' => 'wali', 'display_name' => 'Wali Kelas','description' => 'Wali Kelas'),
			array('name' => 'pembina_ekskul', 'display_name' => 'Pembina Ekstrakurikuler','description' => 'Pembina Ekstrakurikuler'),
			array('name' => 'eksternal', 'display_name' => 'Penguji Eksternal UKK','description' => 'Penguji Eksternal UKK'),
			array('name' => 'guru-p5', 'display_name' => 'Koord P5', 'description' => 'Koord P5')
		);
		//DB::table('roles')->truncate();
    	foreach($roles as $role){
    		DB::table('roles')->updateOrInsert(
				[
					'name' => $role['name'],
				],
				[
					'display_name' => $role['display_name'],
					'description' => $role['description'],
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
    			]
			);
 
    	}
    }
}
