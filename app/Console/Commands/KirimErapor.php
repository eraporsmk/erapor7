<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Sync_log;
use Storage;

class KirimErapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:erapor {table?} {sekolah_id?}, {tahun_ajaran_id?}, {semester_id?} {akses?} {user_id?}';

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
        $table = $this->argument('table');
        $sekolah_id = $this->argument('sekolah_id');
        $tahun_ajaran_id = $this->argument('tahun_ajaran_id');
        $semester_id = $this->argument('semester_id');
        $user_id = $this->argument('user_id');
        if(!$table){
            $email = $this->ask('Email Administrator:');
            $user = User::where('email', $email)->first();
            if($user){
                $semester = Semester::where('periode_aktif', 1)->first();
                if($user->hasRole('admin', $semester->nama)){
                    $sekolah = Sekolah::with(['user' => function($query) use ($semester){
                        $query->whereRoleIs('admin', $semester->nama);
                    }])->find($user->sekolah_id);
                    $table_sync = table_sync();
                    foreach($table_sync as $table){
                        $this->proses_kirim($user->user_id, $table, $sekolah->sekolah_id, $semester->tahun_ajaran_id, $semester->semester_id);
                    }
                } else {
                    $this->error('Email '.$email.' tidak memiliki akses Administrator');
                    exit;
                }
            } else {
                $this->error('Email '.$email.' tidak terdaftar');
                exit;
            }    
        } else {
            $this->proses_kirim($user_id, $table, $sekolah_id, $tahun_ajaran_id, $semester_id);
        }
    }
    private function proses_kirim($user_id, $table, $sekolah_id, $tahun_ajaran_id, $semester_id){
        $count = get_table($table, $sekolah_id, $tahun_ajaran_id, $semester_id, 1);
        if($count){
            $data = get_table($table, $sekolah_id, $tahun_ajaran_id, $semester_id);
            $this->kirim_data($user_id, $table, $data, $sekolah_id, $tahun_ajaran_id, $semester_id);
        }
        Sync_log::updateOrCreate(['user_id' => $user_id, 'updated_at' => now()]);
    }
    private function kirim_data($user_id, $table, $data, $sekolah_id, $tahun_ajaran_id, $semester_id){
        $data_sync = [
            'sekolah_id' => $sekolah_id,
            'tahun_ajaran_id' => $tahun_ajaran_id,
            'semester_id' => $semester_id,
            'table' => $table,
            'json' => prepare_send(json_encode($data)),
        ];
        $url = 'http://app.erapor-smk.net/api/sinkronisasi/kirim-data';
        $response = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36',
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        ])->post($url, $data_sync);
        if($response->status() == 200){
            if($this->argument('akses')){
                $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => count($data).' data '.nama_table($table).' berhasil dikirim']);
            }
            $this->info(count($data).' data '.nama_table($table). ' berhasil dikirim');
            $this->update_last_sync($user_id, $table, $data, $sekolah_id);
        } else {
            if($this->argument('akses')){
                $this->call('respon:artisan', ['status' => 'error', 'title' => 'Gagal', 'respon' => 'Proses pengiriman data '.nama_table($table).' gagal. Server tidak merespon. Status Server: '.$response->status()]);
            }
            $this->proses_sync('', 'Proses pengiriman data '.nama_table($table).' gagal. Server tidak merespon', 0, 0, 0);
            $this->error('Proses pengiriman data '.nama_table($table).' gagal. Server tidak merespon. Status Server: '.$response->status());
        }
    }
    private function update_last_sync($user_id, $table, $data, $sekolah_id){
        $i=0;
        foreach($data as $d){
            $this->proses_sync('Mengirim data', $table, $i, count($data), $sekolah_id);
            if(in_array($table, ['ref.kompetensi_dasar', 'ref.paket_ukk', 'ref.capaian_pembelajaran']) || Schema::hasColumn($table, 'last_sync')){
                $field = (array) $d;
                $collection = collect($field);
                $keys = $collection->keys();
                $keys->all();
                $update = DB::table($table)->where($keys[0], $collection->first())->update(['last_sync' => now()]);
            }
            $i++;
        }
    }
    private function proses_sync($title, $table, $inserted, $jumlah, $sekolah_id){
        $record['table'] = $title.' '.nama_table($table);
		$record['jumlah'] = $jumlah;
		$record['inserted'] = $inserted;
		Storage::disk('public')->put('proses_sync_'.$sekolah_id.'.json', json_encode($record));
    }
}
