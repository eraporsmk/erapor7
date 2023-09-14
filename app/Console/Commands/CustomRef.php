<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use File;

class CustomRef extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:ref';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $json = File::get(database_path('data/mata_pelajaran.json'));
		$data = json_decode($json);
		foreach($data as $obj){
            $find = DB::table('ref.jurusan')->where('jurusan_id', $obj->jurusan_id)->first();
			DB::table('ref.mata_pelajaran')->updateOrInsert(
                [
                    'mata_pelajaran_id' 	=> $obj->mata_pelajaran_id,
                ],
                [
                    'nama' 					=> $obj->nama,
                    'pilihan_sekolah'		=> $obj->pilihan_sekolah,
                    'pilihan_buku' 			=> $obj->pilihan_buku,
                    'pilihan_kepengawasan'	=> $obj->pilihan_kepengawasan,
                    'pilihan_evaluasi'		=> $obj->pilihan_evaluasi,
                    'jurusan_id'			=> ($find) ? $obj->jurusan_id : NULL,
                    'created_at'			=> $obj->create_date,
                    'updated_at'			=> $obj->last_update,
                    'deleted_at'			=> $obj->expired_date,
                    'last_sync'				=> $obj->last_sync,
                ]
            );
		}
        $json = File::get(database_path('data/pekerjaan.json'));
		$data = json_decode($json);
        foreach($data as $obj){
    		DB::table('ref.pekerjaan')->updateOrInsert(
                [
                    'pekerjaan_id' 	=> $obj->pekerjaan_id,
                ],
                [
                    'nama' 			=> $obj->nama,
                    'created_at' 	=> $obj->create_date,
                    'updated_at' 	=> $obj->last_update,
                    'deleted_at'	=> $obj->expired_date,
                    'last_sync'		=> $obj->last_sync,
                ]
            );
    	}
    }
}
