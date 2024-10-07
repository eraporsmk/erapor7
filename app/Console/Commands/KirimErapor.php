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
    protected $signature = 'kirim:erapor {table?} {sekolah_id?} {tahun_ajaran_id?} {semester_id?} {akses?} {user_id?} {--force}';

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
            $email = $this->ask('Email Administrator');
            $user = User::where('email', $email)->first();
            if($user){
                $semester = Semester::where('periode_aktif', 1)->first();
                if($user->hasRole('admin', $semester->nama)){
                    $sekolah = Sekolah::with(['user' => function($query) use ($semester){
                        $query->whereRoleIs('admin', $semester->nama);
                    }])->find($user->sekolah_id);
                    if($sekolah){
                        $response = NULL;
                        try {
                            $spinner = $this->spinner(100);
                            $spinner->setMessage('Mengecek koneksi ke Server Direktorat...'."\n");
                            $spinner->start();
                            for ($i=0; $i < 100; $i++) { 
                                $spinner->advance();
                            }
                            $data_sync = [
                                'username_dapo'		=> $user->email,
                                'password_dapo'		=> $user->password,
                                'npsn'				=> $user->sekolah->npsn,
                                'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
                                'semester_id'		=> $semester->semester_id,
                                'sekolah_id'		=> $sekolah->sekolah_id,
                                'kirim'              => TRUE,
                            ];
                            $response = http_client('status', $data_sync);
                            $spinner->finish();
                        } catch (\Throwable $th) {
                            $this->error('Terdeteksi ada repositori belum terinstall di aplikasi. Silahkan jalankan "composer update" di Command Prompt ini!');
                            exit;
                        }        
                        if($response){
                            if($response->message){
                                $this->error($response->message);
                                exit;
                            } else {
                                $table_sync = table_sync();
                                if ($this->option('force')){
                                    foreach($table_sync as $table){
                                        $force = $this->getTableForce($user->user_id, $table, $user->sekolah_id, $semester->tahun_ajaran_id, $semester->semester_id);
                                        //$this->info($force['table'].' : '.$force['count']);
                                    }
                                    Sync_log::updateOrCreate(['user_id' => $user->user_id, 'updated_at' => now()]);
                                } else {
                                    foreach($table_sync as $table){
                                        $this->proses_kirim($user->user_id, $table, $sekolah->sekolah_id, $semester->tahun_ajaran_id, $semester->semester_id);
                                    }
                                }
                                $this->info('Proses pengiriman data e-Rapor selesai!');
                            }
                        } else {
                            $this->error('Tidak dapat terhubung ke Server Direktorat. Silahkan coba beberapa saat lagi!');
                            exit;
                        }
                    } else {
                        $this->error('Email '.$email.' tidak terhubung ke Data Sekolah');
                        exit;
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
        $response = http_dashboard('sinkronisasi/kirim-data', $data_sync);
        if($response->status){
            if($this->argument('akses')){
                $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => count($data).' data '.nama_table($table).' berhasil dikirim']);
            }
            $this->info(count($data).' data '.nama_table($table). ' berhasil dikirim');
            $this->update_last_sync($user_id, $table, $data, $sekolah_id);
        } else {
            if($this->argument('akses')){
                $this->call('respon:artisan', ['status' => 'error', 'title' => 'Gagal', 'respon' => 'Proses pengiriman data '.nama_table($table).' gagal. Server tidak merespon. Status Server: '.$response->message]);
            }
            $this->proses_sync('', 'Proses pengiriman data '.nama_table($table).' gagal. Server tidak merespon', 0, 0, 0);
            $this->error('Proses pengiriman data '.nama_table($table).' gagal. Server tidak merespon. Status Server: '.$response->message);
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
    private function getTableForce($user_id, $table, $sekolah_id, $tahun_ajaran_id, $semester_id){
        $jml = DB::table($table)->where(function($query) use ($table, $sekolah_id, $tahun_ajaran_id, $semester_id){
            if(in_array($table, ['ref.kompetensi_dasar'])){
                $query->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('users')
                          ->whereColumn('ref.kompetensi_dasar.user_id', 'users.user_id');
                });
            }
            if(in_array($table, ['ref.paket_ukk', 'users']) || Schema::hasColumn($table, 'sekolah_id')){
                $query->where('sekolah_id', $sekolah_id);
            }
            if(in_array($table, ['ref.capaian_pembelajaran'])){
                $query->where('is_dir', 0);
            }
        })->count();
        if($jml){
            $this->info('Memulai kirim data '.nama_table($table));
            $bar = $this->output->createProgressBar($jml);
            $bar->start();
            $getData = DB::table($table)->where(function($query) use ($table, $sekolah_id, $tahun_ajaran_id, $semester_id){
                if(in_array($table, ['ref.kompetensi_dasar'])){
                    $query->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('users')
                            ->whereColumn('ref.kompetensi_dasar.user_id', 'users.user_id');
                    });
                }
                if(in_array($table, ['ref.paket_ukk', 'users']) || Schema::hasColumn($table, 'sekolah_id')){
                    $query->where('sekolah_id', $sekolah_id);
                }
                if(in_array($table, ['ref.capaian_pembelajaran'])){
                    $query->where('is_dir', 0);
                }
            });
            if (Schema::hasColumn($table, 'last_sync')) {
                $getData->update(['last_sync' => now()]);
            }
            $getData->orderBy('created_at')->chunk(1000, function ($data) use ($bar, $user_id, $table, $sekolah_id, $tahun_ajaran_id, $semester_id){
                foreach($data as $d){
                    $bar->advance();
                }
                try {
                    $data_sync = [
                        'sekolah_id' => $sekolah_id,
                        'tahun_ajaran_id' => $tahun_ajaran_id,
                        'semester_id' => $semester_id,
                        'table' => $table,
                        'json' => prepare_send(json_encode($data)),
                    ];
                    http_dashboard('sinkronisasi/kirim-data', $data_sync);
                } catch (\Throwable $th) {
                    $this->error('Proses pengiriman data '.nama_table($table).' gagal. Server tidak merespon. Status Server: '.$th->getMessage());
                }
                //$this->kirim_data($user_id, $table, $data, $sekolah_id, $tahun_ajaran_id, $semester_id);
            });
            $bar->finish();
            $this->newLine();
            $this->warn('Kirim data '.nama_table($table).' selesai!');
        }
    }
}
