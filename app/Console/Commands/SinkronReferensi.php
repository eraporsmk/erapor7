<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Kurikulum;
use App\Models\Mata_pelajaran;
use App\Models\Mst_wilayah;
use Carbon\Carbon;

class SinkronReferensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:referensi {satuan?}';

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
        $list_data = [
            'semua_data',
            'ref.jurusan', 
            'ref.kurikulum', 
            'ref.mata_pelajaran', 
            'ref.mst_wilayah', 
            //'kompetensi_dasar', 
            //'capaian_pembelajaran'
        ];
        $email = $this->ask('Email Administrator:');
        $user = User::where('email', $email)->first();
        if(!$user){
            return $this->error('Email tidak terdaftar');
        }
        $satuan = $this->choice(
            'Pilih data untuk di sinkronisasi!',
            $list_data
        );
        $data = collect($list_data);
        if($satuan == 'semua_data'){
            $data->forget(0);
            foreach($data->all() as $d){
                $this->proses_data($d, $user->sekolah_id);
            }
        } else {
            $this->proses_data($satuan, $user->sekolah_id);
            /*$dapodik = $this->ambil_data($satuan, $user->sekolah_id, 1);
            $this->info("\n".'Memperoses referensi '.$satuan);
            $bar = $this->output->createProgressBar($dapodik->total);
            $bar->start();
            $function = 'simpan_'.str_replace('ref.', '', $satuan);
            $this->{$function}($dapodik->data, $satuan, $bar);*/
        }
        /*
        if($this->argument('satuan')){
        $ref_kerja = [
            1 => "Tidak bekerja",
            2 => "Nelayan",
            3 => "Petani",
            4 => "Peternak",
            5 => "PNS/TNI/Polri",
            6 => "Karyawan Swasta",
            7 => "Pedagang Kecil",
            8 => "Pedagang Besar",
            9 => "Wiraswasta",
            10 => "Wirausaha",
            11 => "Buruh",
            12 => "Pensiunan",
            13 => "Tenaga Kerja Indonesia",
            14 => "Karyawan BUMN",
            90 => "Tidak dapat diterapkan",
            98 => "Sudah Meninggal",
            99 => "Lainnya",
        ];
        foreach($ref_kerja as $id => $nama){
            Pekerjaan::updateOrCreate(
                [
                    'pekerjaan_id' => $id,
                ],
                [
                    'nama' => $nama,
                    'last_sync' => now(),
                ]
            );            
        }*/
    }
    private function proses_data($table, $sekolah_id){
        $dapodik = $this->ambil_data($table, $sekolah_id);
        if($dapodik){
            $this->info("\n".'Memperoses referensi '.$table.' ('.$dapodik->total.')');
            $bar = $this->output->createProgressBar($dapodik->total);
            $bar->start();
            $function = 'simpan_'.str_replace('ref.', '', $table);
            foreach($dapodik->data as $data){
                $this->{$function}($data, $table);
                $bar->advance();
            }
            $i=1;
            for($i=2;$i<=$dapodik->last_page;$i++){
                $dapodik = $this->ambil_data($table, $sekolah_id, $i);
                if($dapodik){
                    foreach($dapodik->data as $data){
                        $this->{$function}($data, $table);
                        $bar->advance();
                    }
                }
            }
            $bar->finish();
        }
    }
    private function ambil_data($table, $sekolah_id, $page = NULL){
        $data = [
            'table' => $table,
            'where' => '',
            'value' => '',
            'limit' => '',
            'offset' => '',
            'satuan' => '',
            'sekolah_id' => $sekolah_id,
        ];
        if($page){
            $response = http_client('referensi?page='.$page, $data);
        } else {
            $response = http_client('referensi', $data);
        }
        return $response->dapodik;
    }
    public function simpan_jurusan($data, $table){
        Jurusan::updateOrCreate(
            [
                'jurusan_id' => $data->jurusan_id
            ],
            [
                'nama_jurusan' => $data->nama_jurusan,
                'untuk_sma' => $data->untuk_sma,
                'untuk_smk' => $data->untuk_smk,
                'untuk_pt' => $data->untuk_pt,
                'untuk_slb' => $data->untuk_slb,
                'untuk_smklb' => $data->untuk_smklb,
                'jenjang_pendidikan_id' => $data->jenjang_pendidikan_id,
                'jurusan_induk' => $data->jurusan_induk,
                'level_bidang_id' => $data->level_bidang_id,
                'deleted_at' => $data->expired_date,
                'last_sync' => Carbon::now()->subDays(30),
            ]
        );
    }
    public function simpan_kurikulum($data, $table){
        Kurikulum::updateOrCreate(
            [
                'kurikulum_id' => $data->kurikulum_id
            ],
            [
                'nama_kurikulum'			=> $data->nama_kurikulum,
                'mulai_berlaku'				=> $data->mulai_berlaku,
                'sistem_sks'				=> $data->sistem_sks,
                'total_sks'					=> $data->total_sks,
                'jenjang_pendidikan_id'		=> $data->jenjang_pendidikan_id,
                'jurusan_id'				=> $data->jurusan_id,
                'deleted_at'				=> $data->expired_date,
                'last_sync'					=> Carbon::now()->subDays(30),
            ]
        );
    }
    public function simpan_mata_pelajaran($data, $table){
        Mata_pelajaran::updateOrCreate(
            [
                'mata_pelajaran_id' => $data->mata_pelajaran_id,
            ],
            [
                'jurusan_id' 				=> $data->jurusan_id,
                'nama'						=> $data->nama,
                'pilihan_sekolah'			=> $data->pilihan_sekolah,
                'pilihan_kepengawasan'		=> $data->pilihan_kepengawasan,
                'pilihan_buku'				=> $data->pilihan_buku,
                'pilihan_evaluasi'			=> $data->pilihan_evaluasi,
                'deleted_at'				=> $data->expired_date,
                'last_sync'					=> Carbon::now()->subDays(30),
            ]
        );
    }
    public function simpan_mst_wilayah($data, $table){
        try {
            Mst_wilayah::updateOrCreate(
                [
                    'kode_wilayah' => $data->kode_wilayah,
                ],
                [
                    'nama' => $data->nama,
                    'id_level_wilayah' => $data->id_level_wilayah,
                    'mst_kode_wilayah' => $data->mst_kode_wilayah,
                    'negara_id' => $data->negara_id,
                    'asal_wilayah' => $data->asal_wilayah,
                    'kode_bps' => $data->kode_bps,
                    'kode_dagri' => $data->kode_dagri,
                    'kode_keu' => $data->kode_keu,
                    'created_at' => $data->create_date,
                    'deleted_at' => $data->expired_date,
                    'last_sync' => $data->last_sync,
                ]
            );
        } catch (\Throwable $e) {
            $this->error('Sinkronisasi gagal. Status server: '.$e->getMessage());
        }
    }
}
