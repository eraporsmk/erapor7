<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Mst_wilayah;
use App\Models\Kompetensi_dasar;
use App\Models\User;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Capaian_pembelajaran;
use App\Models\Mata_pelajaran;
use Carbon\Carbon;
use Storage;

class SinkronErapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:erapor {satuan?} {email?} {created_at?} {akses?}';

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
            'wilayah', 
            'kompetensi-dasar', 
            'capaian-pembelajaran', 
        ];
        $satuan = $this->argument('satuan');
        if($satuan){
            $semester = Semester::where('periode_aktif', 1)->first();
            $user = User::where('email', $this->argument('email'))->first();
            $sekolah = Sekolah::with(['user' => function($query) use ($user){
                $query->where('email', $user->email);
            }])->find($user->sekolah_id);
        } else {
            $email = $this->ask('Email Administrator:');
            $user = User::where('email', $email)->first();
            if($user){
                $semester = Semester::where('periode_aktif', 1)->first();
                if($user->hasRole('admin', $semester->nama)){
                    $sekolah = Sekolah::with(['user' => function($query) use ($user){
                        $query->where('email', $user->email);
                    }])->find($user->sekolah_id);
                    $satuan = $this->choice(
                        'Pilih data untuk di sinkronisasi!',
                        $list_data
                    );
                } else {
                    $this->error('Email '.$email.' tidak memiliki akses Administrator');
                    exit;
                }
            } else {
                $this->error('Email '.$email.' tidak terdaftar');
                exit;
            }            
        }
        $created_at = NULL;
        if($satuan == 'wilayah'){
            $created_at = Mst_wilayah::orderBy('created_at', 'DESC')->first();
        } elseif($satuan == 'kompetensi-dasar'){
            $created_at = Kompetensi_dasar::orderBy('created_at', 'DESC')->first();
        } elseif($satuan == 'capaian-pembelajaran'){
            $created_at = Capaian_pembelajaran::orderBy('created_at', 'DESC')->first();
        }
        if(!$created_at){
            $created_at = (object) ['created_at' => '2021-01-01 00:00:01'];
        }
        if($this->argument('created_at')){
            $created_at = (object) ['created_at' => '2010-01-01 00:00:01'];
        }
        $args = [
            'created_at' => Carbon::parse($created_at->created_at)->format('Y-m-d H:i:s'),
        ];
        $server_dashboard = [
            'wilayah', 
            'kompetensi-dasar', 
            'capaian-pembelajaran',
        ];
        $ref_lokal = 0;
        if(in_array($satuan, $server_dashboard)){
            $this->info('Mengambil data '.$this->get_table($satuan));
            $hitung_data = $this->ambil_data($satuan.'/hitung', $args);
            if($satuan == 'wilayah'){
                $ref_lokal = Mst_wilayah::count();
            } elseif($satuan == 'kompetensi-dasar'){
                $ref_lokal = Kompetensi_dasar::count();
            } else {
                try {
                    $ref_lokal = Capaian_pembelajaran::where(function($query){
                        $query->where('is_dir', 1);
                    })->count();
                } catch (\Exception $e){
                    $this->call('respon:artisan', ['status' => 'error', 'title' => 'Gagal', 'respon' => 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Jalankan php artisan erapor:update']);
                    return $this->error("\n".'Proses pengambilan data '.$this->get_table($satuan).' gagal! Jalankan php artisan erapor:update');
                }
            }
            if($hitung_data && $hitung_data->dapodik > -1){
                if($hitung_data->dapodik > $ref_lokal){
                    $this->hitung_data($hitung_data, $satuan, $sekolah, $semester, $args);
                } else {
                    $bar = $this->output->createProgressBar($ref_lokal);
                    $bar->start(0);
                    for($i=1;$i<=$ref_lokal;$i++){
                        $bar->advance();
                    }
                    $bar->finish();
                    if($this->argument('akses')){
                        $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => 'Sinkronisasi '.$this->get_table($satuan).' berhasil']);
                    }
                    return $this->info("\n".'Proses pengambilan data '.$this->get_table($satuan).' berhasil!');
                }
            } else {
                if($this->argument('akses')){
                    $this->call('respon:artisan', ['status' => 'error', 'title' => 'Gagal', 'respon' => 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon!']);
                }
                return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon!');
            }
        } else {
            $sync_data = [
                'wilayah', 
                'kompetensi-dasar', 
                'capaian-pembelajaran',
            ];
            foreach($sync_data as $satuan){
                $this->info('Mengambil data '.$this->get_table($satuan));
                $hitung_data = $this->ambil_data($satuan.'/hitung', $args);
                $this->hitung_data($hitung_data, $satuan, $sekolah, $semester, $args);
            }
        }
    }
    private function hitung_data($hitung_data, $satuan, $sekolah, $semester, $args){
        $limit = 1000;
        $this->info('Memproses data '.$this->get_table($satuan));
        $bar = $this->output->createProgressBar($hitung_data->dapodik);
        $bar->start(0);
        if($hitung_data->dapodik > $limit){
            for ($counter = 0; $counter <= $hitung_data->dapodik; $counter += $limit) {
                $args = [
                    'created_at' => $args['created_at'],
                    'offset' => $counter,
                    'limit' => $limit,
                ];
                $referensi = $this->ambil_data($satuan, $args);
                $this->proses_data($referensi, $satuan, $sekolah->user, $semester, $bar);
            }
        } else {
            $args = [
                'created_at' => $args['created_at'],
                'offset' => 0,
                'limit' => $limit,
            ];
            $referensi = $this->ambil_data($satuan, $args);
            $this->proses_data($referensi, $satuan, $sekolah->user, $semester, $bar);
        }
        if($this->argument('akses')){
            $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => 'Sinkronisasi '.$this->get_table($satuan).' berhasil']);
        }
        $this->info("\n".'Sinkronisasi '.$this->get_table($satuan).' berhasil'."\n"."\n");
    }
    private function proses_data($referensi, $satuan, $user, $semester, $bar){
        if($referensi){
            $function = 'simpan_'.str_replace('-', '_', $satuan);
            $this->{$function}($referensi->dapodik, $satuan, $user, $semester, $bar);
        }
    }
    private function simpan_wilayah($dapodik, $satuan, $user, $semester, $bar){
        $i=1;
        $record['table'] = 'Referensi Wilayah';
		$record['jumlah'] = count($dapodik);
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_wilayah($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
            $i++;
            $bar->advance();
        }
        $bar->finish();
    }
    private function simpan_kompetensi_dasar($dapodik, $satuan, $user, $semester, $bar){
        $i=1;
        $record['table'] = 'Referensi Kompetensi Dasar';
		$record['jumlah'] = count($dapodik);
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_kd($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
            $i++;
            $bar->advance();
        }
        $bar->finish();
    }
    private function simpan_capaian_pembelajaran($dapodik, $satuan, $user, $semester, $bar){
        $i=1;
        $record['table'] = 'Referensi Capaian Pembelajaran';
		$record['jumlah'] = count($dapodik);
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_cp($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
            $i++;
            $bar->advance();
        }
        $bar->finish();
    }
    private function get_table($table){
        $list_data = [
            'semua_data' => 'Semua Referensi e-Rapor SMK', 
            'wilayah' => 'Referensi Wilayah', 
            'kompetensi-dasar' => 'Referensi Kompetensi Dasar', 
            'capaian-pembelajaran' => 'Referensi Capaian Pembelajaran'
        ];
        if(isset($list_data[$table])){
            return $list_data[$table];
        }
        return $table;
    }
    private function ambil_data($satuan, $data_sync){
        try {
            $response = Http::post('http://app.erapor-smk.net/api/sinkronisasi/'.$satuan, $data_sync);
            if($response->status() == 200){
                return $response->object();
            } else {
                if($this->argument('akses')){
                    $this->call('respon:artisan', ['status' => 'info', 'title' => 'Gagal', 'respon' => 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: '.$response->status()]);
                }
                return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: '.$response->status());
                return false;
            }
            return false;
        } catch (\Exception $e){
            return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: 500');
        }
    }
    private function proses_kd($data){
        $find = Mata_pelajaran::find($data->mata_pelajaran_id);
        if($find){
            Kompetensi_dasar::withTrashed()->updateOrCreate(
                [
                    'kompetensi_dasar_id' => $data->kompetensi_dasar_id,
                ],
                [
                    'id_kompetensi' => $data->id_kompetensi,
                    'kompetensi_id' => $data->kompetensi_id,
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                    'kelas_10' => $data->kelas_10,
                    'kelas_11' => $data->kelas_11,
                    'kelas_12' => $data->kelas_12,
                    'kelas_13' => $data->kelas_13,
                    'id_kompetensi_nas' => $data->id_kompetensi_nas,
                    'kompetensi_dasar' => $data->kompetensi_dasar,
                    'kompetensi_dasar_alias' => $data->kompetensi_dasar_alias,
                    'user_id' => $data->user_id,
                    'kurikulum' => $data->kurikulum,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'deleted_at' => $data->deleted_at,
                    'last_sync' => $data->last_sync,
                ]
            );
        }
    }
    private function proses_wilayah($wilayah){
        $find = NULL;
        if($wilayah->mst_kode_wilayah){
            $find = Mst_wilayah::find($wilayah->mst_kode_wilayah);
        }
        Mst_wilayah::updateOrCreate(
            [
                'kode_wilayah' => $wilayah->kode_wilayah,
            ],
            [
                'nama' => $wilayah->nama,
                'id_level_wilayah' => $wilayah->id_level_wilayah,
                'mst_kode_wilayah' => ($find) ? $wilayah->mst_kode_wilayah : NULL,
                'negara_id' => $wilayah->negara_id,
                'asal_wilayah' => $wilayah->asal_wilayah,
                'kode_bps' => $wilayah->kode_bps,
                'kode_dagri' => $wilayah->kode_dagri,
                'kode_keu' => $wilayah->kode_keu,
                'created_at' => $wilayah->created_at,
                'updated_at' => $wilayah->updated_at,
                'deleted_at' => $wilayah->deleted_at,
                'last_sync' => $wilayah->last_sync,
            ]
        );
    }
    private function proses_cp($data){
        $find = Mata_pelajaran::find($data->mata_pelajaran_id);
        if($find){
            Capaian_pembelajaran::updateOrCreate(
                [
                    'cp_id' => $data->cp_id,
                ],
                [
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                    'fase' => $data->fase,
                    'elemen' => $data->elemen,
                    'deskripsi' => $data->deskripsi,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'last_sync' => $data->last_sync,
                    'is_dir' => 1,
                ]
            );
        }
    }
}
