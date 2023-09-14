<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class JabatanPtkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/data/jabatan_ptk.json');
		$data = json_decode($json);
        foreach($data as $obj){
            DB::table('ref.jabatan_ptk')->updateOrInsert(
                ['jabatan_ptk_id' => $obj->jabatan_ptk_id],
                [
                    'nama' => $obj->nama,
                    'jabatan_utama' => $obj->jabatan_utama,
                    'tugas_tambahan_guru' => $obj->tugas_tambahan_guru,
                    'jumlah_jam_diakui' => $obj->jumlah_jam_diakui,
                    'harus_refer_unit_org' => $obj->harus_refer_unit_org,
                    'created_at' => $obj->create_date,
                    'updated_at' => $obj->last_update,
                    'deleted_at' => $obj->expired_date,
                    'last_sync' => $obj->last_sync,
                ]
            );
        }
    }
}
