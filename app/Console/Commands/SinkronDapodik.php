<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Mst_wilayah;
use App\Models\Guru;
use App\Models\Ptk_keluar;
use App\Models\Jurusan;
use App\Models\Jurusan_sp;
use App\Models\Gelar;
use App\Models\Gelar_ptk;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Pd_keluar;
use App\Models\Anggota_rombel;
use App\Models\Mata_pelajaran;
use App\Models\Mata_pelajaran_kurikulum;
use App\Models\Pembelajaran;
use App\Models\Kurikulum;
use App\Models\Ekstrakurikuler;
use App\Models\Dudi;
use App\Models\Mou;
use App\Models\Akt_pd;
use App\Models\Anggota_akt_pd;
use App\Models\Bimbing_pd;
use App\Models\User;
use App\Models\Jabatan_ptk;
use App\Models\Kompetensi_dasar;
use App\Models\Capaian_pembelajaran;
use Carbon\Carbon;
use Storage;

class SinkronDapodik extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:dapodik {satuan?} {akses?} {sekolah_id?} {semester_id?}';

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
        if(jam_sinkron()){
            $this->info('Penyesuaian Waktu Layanan Sinkronisasi Dapodik');
            $this->info('Dikarenakan adanya proses rutin sinkronisasi data Dapodik antara Server PUSDATIN dan Server Direktorat SMK, maka layanan sinkronisasi hanya dapat diakses antara pukul:');
            $this->error('03.00 s/d 24.00 (WIB)');
            return false;
        }
        $semester = Semester::where(function($query){
            if($this->argument('semester_id')){
                $query->where('semester_id', $this->argument('semester_id'));
            } else {
                $query->where('periode_aktif', 1);
            }
        })->first();
        $general = [
            'semua_data',
            'sekolah', 
            'ptk', 
            'rombongan_belajar', 
            'peserta_didik_aktif', 
            'peserta_didik_keluar', 
            'anggota_rombel_pilihan',
            'pembelajaran', 
            'ekstrakurikuler', 
            'anggota_ekskul', 
        ];
        $smk = [
            'dudi'
        ];
        /*$list_data = [
            'semua_data',
            'sekolah', 
            'ptk', 
            'rombongan_belajar', 
            'peserta_didik_aktif', 
            'peserta_didik_keluar', 
            'anggota_rombel_pilihan',
            'pembelajaran', 
            'ekstrakurikuler', 
            'anggota_ekskul', 
            'dudi'
        ];*/
        if($this->argument('satuan')){
            $satuan = $this->argument('satuan');
            $sekolah = Sekolah::with(['user' => function($query) use ($semester){
                $query->whereRoleIs('admin', $semester->nama);
            }])->find($this->argument('sekolah_id'));
        } else {
            $email = $this->ask('Email Administrator:');
            $user = User::where('email', $email)->first();
            $sekolah = NULL;
            if($user){
                if($user->hasRole('admin', $semester->nama)){
                    $sekolah = Sekolah::with(['user' => function($query) use ($user){
                        $query->where('email', $user->email);
                    }])->find($user->sekolah_id);
                    $list_data = array_merge($general, $smk);
                    if($sekolah->bentuk_pendidikan_id){
                        if($sekolah->bentuk_pendidikan_id !== 15){
                            $list_data = $general;
                        }
                    }
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
        if($sekolah){
            $list_data = array_merge($general, $smk);
            if($sekolah->bentuk_pendidikan_id){
                if($sekolah->bentuk_pendidikan_id !== 15){
                    $list_data = $general;
                }
            }
            $data = collect($list_data);
            if($satuan != 'semua_data'){
                if($data->contains($satuan)){
                    $dapodik = $this->ambil_data($sekolah, $semester, $satuan);
                    if($dapodik){
                        $this->proses_data($dapodik, $satuan, $sekolah->user, $semester);
                        if($this->argument('akses')){
                            $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => 'Sinkronisasi '.$this->get_table($satuan).' berhasil']);
                        }
                        $this->info("\n".'Sinkronisasi '.$this->get_table($satuan).' berhasil');
                    }
                } else {
                    $this->error('Permintaan '.$satuan.' tidak ditemukan');
                }
            } else {
                $data->forget(0);
                foreach($data as $d){
                    $dapodik = $this->ambil_data($sekolah, $semester, $d);
                    if($dapodik){
                        $this->proses_data($dapodik, $d, $sekolah->user, $semester);
                        if($this->argument('akses')){
                            $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => 'Sinkronisasi '.$this->get_table($satuan).' berhasil']);
                        }
                        $this->info("\n".'Sinkronisasi '.$this->get_table($d).' berhasil');
                    }
                }
            }
        } else {
            $this->error('Sekolah tidak ditemukan');
        }
    }
    private function get_table($table){
        $list_data = [
            'jurusan' => 'Referensi Jurusan', 
            'kurikulum' => 'Referensi Kurikulum', 
            'mata_pelajaran' => 'Referensi Mata Pelajaran', 
            //'mata_pelajaran_kurikulum' => 'Referensi Mata Pelajaran Kurikulum', 
            'semua_data' => 'Semua Referensi e-Rapor SMK', 
            'wilayah' => 'Referensi Wilayah', 
            'kd' => 'Referensi Kompetensi Dasar', 
            'cp' => 'Referensi Capaian Pembelajaran',
            'sekolah' => 'Data Sekolah',
            'jurusan_sp' => 'Data Jurusan SP',
            'ptk' => 'Data PTK', 
            'rombongan_belajar' => 'Data Rombongan Belajar', 
            'peserta_didik_aktif' => 'Data Peserta Didik Aktif', 
            'anggota_rombel_pilihan' => 'Anggota Rombel Matpel Pilihan',
            'peserta_didik_keluar' => 'Data Peserta Didik Keluar', 
            'pembelajaran' => 'Data Pembelajaran', 
            'ekstrakurikuler' => 'Data Ekstrakurikuler', 
            'anggota_ekskul' => 'Data Anggota Ekskul', 
            'dudi' => 'Data DUDI'
        ];
        if(isset($list_data[$table])){
            return $list_data[$table];
        }
        return $table;
    }
    /*private function url_server($server, $ep){
        return config('erapor.'.$server).$ep;
    }*/
    private function proses_sync($title, $table, $inserted, $jumlah, $sekolah_id){
        $record['table'] = $title.' '.$this->get_table($table);
		$record['jumlah'] = $jumlah;
		$record['inserted'] = $inserted;
		Storage::disk('public')->put('proses_sync_'.$sekolah_id.'.json', json_encode($record));
    }
    private function ambil_data($sekolah, $semester, $satuan){
        $this->info("\n".'Mengambil '.$this->get_table($satuan));
        $this->proses_sync('Mengambil', $satuan, 0, 0, $sekolah->sekolah_id);
        try {
            $updated_at = NULL;
            if($satuan == 'mata_pelajaran_kurikulum'){
                $updated_at = Mata_pelajaran_kurikulum::orderBy('updated_at', 'DESC')->first()->created_at;
            }
            if($sekolah->user){
                $data_sync = [
                    'username_dapo'		=> $sekolah->user->email,
                    'password_dapo'		=> $sekolah->user->password,
                    'npsn'				=> $sekolah->npsn,
                    'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
                    'semester_id'		=> $semester->semester_id,
                    'sekolah_id'		=> $sekolah->sekolah_id,
                    'updated_at'        => ($updated_at) ? Carbon::parse($updated_at)->format('Y-m-d H:i:s') : NULL,
                    'last_sync'         => NULL,
                ];
                $response = http_client($satuan, $data_sync);
                if($response){
                    $this->info('Memproses '.$this->get_table($satuan));
                    return $response->dapodik;
                } else {
                    if($this->argument('akses')){
                        $this->call('respon:artisan', ['status' => 'error', 'title' => 'Gagal', 'respon' => 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon']);
                    }
                    $this->proses_sync('', 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon', 0, 0, 0);
                    return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon');
                    return false;
                }
            } else {
                return $this->error('Sekolah tidak memiliki pengguna Admin');
            }
        } catch (\Exception $e){
            if($this->argument('akses')){
                $this->call('respon:artisan', ['status' => 'error', 'title' => 'Gagal', 'respon' => 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: '.$e->getMessage()]);
            }
            $this->proses_sync('', 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon', 0, 0, 0);
            return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: '.$e->getMessage());
        }
    }
    private function proses_data($dapodik, $satuan, $user, $semester){
        $function = 'simpan_'.$satuan;
        if(isset($dapodik->current_page)){
            if($dapodik->last_page > 1){
                $this->{$function}($dapodik->data, $user, $semester);
                for($i=2;$i<=$dapodik->last_page;$i++){
                    $dapodik = $this->ambil_data($user->sekolah, $semester, $satuan.'?page='.$i);
                    if($dapodik){
                        $this->{$function}($dapodik->data, $user, $semester);
                    }
                }
            } else {
                $this->{$function}($dapodik->data, $user, $semester);
            }
        } else {
            $this->{$function}($dapodik, $user, $semester);
        }
    }
    public function simpan_kd($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'kd', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->proses_kd($data);
            $this->proses_sync('Memperoses', 'kd', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    public function simpan_cp($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'cp', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->proses_cp($data);
            $this->proses_sync('Memperoses', 'cp', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
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
    private function simpan_wilayah($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'wilayah', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->proses_wilayah($data, FALSE);
            $this->proses_sync('Memperoses', 'wilayah', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function simpan_peserta_didik_aktif($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'peserta_didik_aktif', $i, count($dapodik), $user->sekolah_id);
        $anggota_rombel_id = [];
        foreach($dapodik as $data){
            $anggota_rombel_id[] = $data->anggota_rombel->anggota_rombel_id;
            $this->simpan_pd($data, $user, $semester, NULL);
            $a = Pd_keluar::where('peserta_didik_id', $data->peserta_didik_id)->where('semester_id', $semester->semester_id)->delete();
            $this->proses_sync('Memperoses', 'peserta_didik_aktif', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        /*Anggota_rombel::whereHas('rombongan_belajar', function($query) use ($semester){
            $query->where('semester_id', $semester->semester_id);
            $query->where('jenis_rombel', 1);
        })->whereNotIn('anggota_rombel_id', $anggota_rombel_id)->where('semester_id', $semester->semester_id)->where('sekolah_id', $user->sekolah_id)->delete();*/
    }
    private function simpan_peserta_didik_keluar($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'peserta_didik_keluar', $i, count($dapodik), $user->sekolah_id);
        $peserta_didik_id = [];
        foreach($dapodik as $data){
            $peserta_didik_id[] = $data->peserta_didik_id;
            $this->simpan_pd($data, $user, $semester, now());
            $a = Pd_keluar::updateOrCreate(
                [
                    'peserta_didik_id' => $data->peserta_didik_id,
                ],
                [
                    'sekolah_id' => $user->sekolah_id,
                    'semester_id' => $semester->semester_id,
                    'last_sync' => Carbon::now()->subDays(30),
                ]
            );
            $this->proses_sync('Memperoses', 'peserta_didik_keluar', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function ambil_dapo($user, $semester, $satuan, $data){
        try {
            $data_sync = [
                'sekolah_id' => $user->sekolah_id,
                'username_dapo' => $user->email,
                'password_dapo' => $user->password,
                'tahun_ajaran_id' => $semester->tahun_ajaran_id,
                'semester_id' => $semester->semester_id,
                'npsn' => $user->sekolah->npsn,
                'table' => $data['table'],
                'where' => $data['where'],
                'value' => $data['value'],
                'limit' => $data['limit'],
                'offset' => $data['offset'],
                'satuan' => $data['satuan'],
            ];
            $response = http_client($satuan, $data_sync);
            if($response->status() == 200){
                return $response->object();
            } else {
                return false;
            }
        } catch (\Exception $e){
            return false;
        }
    }
    private function cari_wilayah($user, $semester, $kode_wilayah){
        $find = Mst_wilayah::find($kode_wilayah);
        if(!$find){
            $data = [
                'table' => 'ref.mst_wilayah',
                'where' => 'kode_wilayah',
                'value' => $kode_wilayah,
                'limit' => '',
                'offset' => '',
                'satuan' => 1,
            ];
            $dapodik = $this->ambil_dapo($user, $semester, 'referensi', $data);
            if($dapodik && $dapodik->dapodik){
                $this->update_wilayah($dapodik->dapodik);
            }
        }
    }
    private function cari_mapel($user, $semester, $mata_pelajaran_id){
        $find = Mata_pelajaran::find($mata_pelajaran_id);
        if(!$find){
            $data = [
                'table' => 'ref.mata_pelajaran',
                'where' => 'mata_pelajaran_id',
                'value' => $mata_pelajaran_id,
                'limit' => '',
                'offset' => '',
                'satuan' => 1,
                'sekolah_id' => $user->sekolah_id,
            ];
            $response = http_client('referensi', $data);
            if($response && $response->dapodik){
                $this->insert_mata_pelajaran($response->dapodik, $user, $semester);
            }
        }
    }
    private function simpan_pd($data, $user, $semester, $deleted_at){
        $wilayah = NULL;
        if(isset($data->wilayah)){
            $wilayah = $this->proses_wilayah($data->wilayah, TRUE);
        }
        if(isset($data->kode_wilayah)){
            $this->cari_wilayah($user, $semester, $data->kode_wilayah);
        }
        $kecamatan = NULL;
        if($wilayah){
            try {
                $kecamatan = ($wilayah['kecamatan']) ? $wilayah['kecamatan']->nama : 0;
            } catch (\Throwable $e) {
                $this->error("\n".'Alamat peserta didik '.strtoupper($data->nama).' tidak valid!');
            }
        }
        Peserta_didik::withTrashed()->updateOrCreate(
            [
                'peserta_didik_id' => $data->peserta_didik_id
            ],
            [
                'peserta_didik_id_dapodik' => $data->peserta_didik_id,
                'sekolah_id'		=> $user->sekolah_id,
                'nama' 				=> $data->nama,
                'no_induk' 			=> ($data->registrasi_peserta_didik->nipd) ? $data->registrasi_peserta_didik->nipd : 0,
                //($data->registrasi_peserta_didik) ? ($data->registrasi_peserta_didik->nipd) ?? 0 : 0,
                'nisn' 				=> $data->nisn,
                'nik'               => $data->nik,
                'jenis_kelamin' 	=> ($data->jenis_kelamin) ?? 0,
                'tempat_lahir' 		=> ($data->tempat_lahir) ?? 0,
                'tanggal_lahir' 	=> $data->tanggal_lahir,
                'agama_id' 			=> ($data->agama_id) ?? 0,
                'status' 			=> 'Anak Kandung',
                'anak_ke' 			=> ($data->anak_keberapa) ?? 0,
                'alamat' 			=> ($data->alamat_jalan) ?? 0,
                'rt' 				=> ($data->rt) ?? 0,
                'rw' 				=> ($data->rw) ?? 0,
                'desa_kelurahan' 	=> ($data->desa_kelurahan) ?? 0,
                'kecamatan' 		=> $kecamatan,
                'kode_pos' 			=> ($data->kode_pos) ?? 0,
                'no_telp' 			=> ($data->nomor_telepon_seluler) ?? 0,
                'sekolah_asal' 		=> ($data->registrasi_peserta_didik) ? $data->registrasi_peserta_didik->sekolah_asal : 0,
                'diterima' 			=> ($data->registrasi_peserta_didik) ? $data->registrasi_peserta_didik->tanggal_masuk_sekolah : NULL,
                'diterima_kelas'    => ($data->diterima_dikelas) ? ($data->diterima_dikelas->rombongan_belajar) ? $data->diterima_dikelas->rombongan_belajar->nama : NULL : NULL,
                'kode_wilayah' 		=> $data->kode_wilayah,
                'email' 			=> $data->email,
                'nama_ayah' 		=> ($data->nama_ayah) ?? 0,
                'nama_ibu' 			=> ($data->nama_ibu_kandung) ?? 0,
                'kerja_ayah' 		=> ($data->pekerjaan_id_ayah) ? $data->pekerjaan_id_ayah : 1,
                'kerja_ibu' 		=> ($data->pekerjaan_id_ibu) ? $data->pekerjaan_id_ibu : 1,
                'nama_wali' 		=> ($data->nama_wali) ?? 0,
                'alamat_wali' 		=> ($data->alamat_jalan) ?? 0,
                'telp_wali' 		=> ($data->nomor_telepon_seluler) ?? 0,
                'kerja_wali' 		=> ($data->pekerjaan_id_wali) ? $data->pekerjaan_id_wali : 1,
                'deleted_at'        => NULL,
                'active' 			=> 1,
                'last_sync' => Carbon::now()->subDays(30),
            ]
        );
        if(isset($data->anggota_rombel)){
            $find = Rombongan_belajar::find($data->anggota_rombel->rombongan_belajar_id);
            if($find){
                $this->simpan_anggota_rombel($data->anggota_rombel, $user, $semester, $deleted_at);
            }
        }
    }
    private function simpan_ekstrakurikuler($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'ekstrakurikuler', $i, count($dapodik), $user->sekolah_id);
        $id_kelas_ekskul = [];
        foreach($dapodik as $data){
            $id_kelas_ekskul[] = $data->ID_kelas_ekskul;
            $this->insert_rombel($data->rombongan_belajar, $user, $semester, TRUE);
            $find = Guru::find($data->rombongan_belajar->ptk_id);
            if($find){
                Ekstrakurikuler::withTrashed()->updateOrCreate(
                    [
                        'ekstrakurikuler_id' => $data->ID_kelas_ekskul,
                    ],
                    [
                        'id_kelas_ekskul' => $data->ID_kelas_ekskul,
                        'semester_id' => $semester->semester_id,
                        'sekolah_id'	=> $user->sekolah_id,
                        'guru_id' => $data->rombongan_belajar->ptk_id,
                        'nama_ekskul' => $data->nm_ekskul,
                        'is_dapodik' => 1,
                        'rombongan_belajar_id'	=> $data->rombongan_belajar_id,
                        'alamat_ekskul' => $data->rombongan_belajar->ruang->nm_ruang, 
                        'last_sync'	=> Carbon::now()->subDays(30),
                    ]
                );
            }
            $this->proses_sync('Memperoses', 'ekstrakurikuler', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        if($id_kelas_ekskul){
            Ekstrakurikuler::where('sekolah_id', $user->sekolah_id)->where('semester_id', $semester->semester_id)->whereNotIn('id_kelas_ekskul', $id_kelas_ekskul)->delete();
        }
    }
    private function insert_rombel($data, $user, $semester, $ekskul){
        if($data->jurusan_sp_id){
            $this->insert_jurusan_sp($data->jurusan_sp, $user, $semester);
        }
        if(isset($data->Soft_delete)){
            $soft_delete = ($data->Soft_delete) ? now() : NULL;
        } else {
            $soft_delete = ($data->soft_delete) ? now() : NULL;
        }
        $jurusan = NULL;
        $jurusan_sp = NULL;
        if($data->jurusan_sp_id){
            $jurusan = Jurusan::find($data->jurusan_sp->jurusan_id);
            $jurusan_sp = Jurusan_sp::find($data->jurusan_sp_id);
        }
        Rombongan_belajar::withTrashed()->updateOrCreate(
            [
                'rombongan_belajar_id' => $data->rombongan_belajar_id,
            ],
            [
                'sekolah_id' => $data->sekolah_id,
                'semester_id' => $data->semester_id,
                'jurusan_id' => ($jurusan) ? $data->jurusan_sp->jurusan_id : NULL,
                'jurusan_sp_id' => ($jurusan_sp) ? $data->jurusan_sp_id : NULL,
                'kurikulum_id' => $data->kurikulum_id,
                'nama' => $data->nama,
                'guru_id' => $data->ptk_id,
                'ptk_id' => $data->ptk_id,
                'tingkat' => $data->tingkat_pendidikan_id,
                'jenis_rombel' => $data->jenis_rombel,
                'rombel_id_dapodik' => $data->rombongan_belajar_id,
                'deleted_at' => $soft_delete,
                //'deleted_at' => ($data->soft_delete) ? now() : NULL,
                'last_sync' => Carbon::now()->subDays(30),
            ]
        );
        /*$guru = Guru::withTrashed()->find($data->ptk_id);
        if($guru){
            $jurusan = NULL;
            $jurusan_sp = NULL;
            if($data->jurusan_sp_id){
                $jurusan = Jurusan::find($data->jurusan_sp->jurusan_id);
                $jurusan_sp = Jurusan_sp::find($data->jurusan_sp_id);
            }
            $kurikulum = Kurikulum::find($data->kurikulum_id);
            if($kurikulum){
                Rombongan_belajar::withTrashed()->updateOrCreate(
                    [
                        'rombongan_belajar_id' => $data->rombongan_belajar_id,
                    ],
                    [
                        'sekolah_id' => $data->sekolah_id,
                        'semester_id' => $data->semester_id,
                        'jurusan_id' => ($jurusan) ? $data->jurusan_sp->jurusan_id : NULL,
                        'jurusan_sp_id' => ($jurusan_sp) ? $data->jurusan_sp_id : NULL,
                        'kurikulum_id' => $data->kurikulum_id,
                        'nama' => $data->nama,
                        'guru_id' => $data->ptk_id,
                        'ptk_id' => $data->ptk_id,
                        'tingkat' => $data->tingkat_pendidikan_id,
                        'jenis_rombel' => $data->jenis_rombel,
                        'rombel_id_dapodik' => $data->rombongan_belajar_id,
                        'deleted_at' => $soft_delete,
                        //'deleted_at' => ($data->soft_delete) ? now() : NULL,
                        'last_sync' => Carbon::now()->subDays(30),
                    ]
                );
            }
        }*/
    }
    private function simpan_rombongan_belajar($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'rombongan_belajar', $i, count($dapodik), $user->sekolah_id);
        $rombongan_belajar_id = [];
        foreach($dapodik as $data){
            $rombongan_belajar_id[] = $data->rombongan_belajar_id;
            if($data->jurusan_sp){
                $this->insert_jurusan($data->jurusan_sp->jurusan, $user, $semester);
            }
            $this->insert_kurikulum($data->kurikulum, $user, $semester);
            $this->insert_rombel($data, $user, $semester, FALSE);
            $this->proses_sync('Memperoses', 'rombongan_belajar', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        if($rombongan_belajar_id){
            Rombongan_belajar::where('sekolah_id', $user->sekolah_id)->where('semester_id', $semester->semester_id)->whereNotIn('rombongan_belajar_id', $rombongan_belajar_id)->whereIn('jenis_rombel', [1, 16])->delete();
        }
    }
    private function simpan_ptk($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'ptk', $i, count($dapodik), $user->sekolah_id);
        $guru_id = [];
        foreach($dapodik as $data){
            $guru_id[] = $data->ptk_id;
            $this->simpan_guru($data, $user, $semester);
            $this->proses_sync('Memperoses', 'ptk', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        //Guru::where('sekolah_id', $user->sekolah_id)->where('is_dapodik', 1)->whereNotIn('guru_id_dapodik', $guru_id)->delete();
        if(Schema::hasTable('ptk_keluar')){
            $this->simpan_ptk_keluar($guru_id, $user, $semester);
        }
        $bar->finish();
    }
    private function simpan_ptk_keluar($dapodik, $user, $semester){
        if($dapodik){
            $guru = Guru::withTrashed()->where('sekolah_id', $user->sekolah_id)->where('is_dapodik', 1)->whereNotIn('guru_id_dapodik', $dapodik)->get();
			foreach($guru as $data){
                Ptk_keluar::updateOrCreate(
                    [
                        'guru_id' => $data->guru_id,
                    ],
                    [
                        'sekolah_id' => $data->sekolah_id,
                        'semester_id' => $semester->semester_id,
                        'last_sync' => Carbon::now()->subDays(30),
                    ]
                );
            }
			Ptk_keluar::whereIn('guru_id', $dapodik)->where('sekolah_id', $user->sekolah_id)->delete();
            Guru::onlyTrashed()->where('sekolah_id', $user->sekolah_id)->where('is_dapodik', 1)->whereIn('guru_id_dapodik', $dapodik)->restore();
        }
    }
    private function ambil_referensi($data_sync){
        return NULL;
        //$server = 'http://jembatan.test/api';
        $server = 'http://api.erapor-smk.net/api';
        $response = Http::withBasicAuth('masadi', '@Bismill4h#')->post($server.'/dapodik', $data_sync);
        return $response->object();
    }
    private function simpan_gelar($data){
        $gelar = Gelar::find($data->gelar_akademik_id);
        return $gelar;
        if(!$gelar){
            $data_sync = [
                'server' => 'local',
                'table_utama' => [
                    'table' => 'ref.gelar_akademik',
                    'primary_key' => 'gelar_akademik_id', 
                ],
                'table_join' => NULL, 
                'satuan' => TRUE,
                'select' => NULL,
                'where' => [
                    [
                        'field' => 'gelar_akademik_id',
                        'value' => $data->gelar_akademik_id,
                    ]
                ]
            ];
            $gelar = $this->ambil_referensi($data_sync);
        }
        return $gelar;
    }
    private function simpan_guru($data, $user, $semester){
        if($data){
            $this->proses_wilayah($data->wilayah, TRUE);
            $random = Str::random(6);
            $data->email = ($data->email) ? $data->email : strtolower($random).'@erapor-smk.net';
            $data->email = ($data->email != $user->email) ? $data->email : strtolower($random).'@erapor-smk.net';
            $data->email = strtolower($data->email);
            $data->nuptk = ($data->nuptk) ? $data->nuptk : mt_rand();
            $jenis_ptk_id = 0;
            $create_guru = Guru::withTrashed()->updateOrCreate(
                [
                    'guru_id' => $data->ptk_id
                ],
                [
                    'guru_id_dapodik'       => $data->ptk_id,
                    'sekolah_id' 			=> $user->sekolah_id,
                    'nama' 					=> $data->nama,
                    'nuptk' 				=> $data->nuptk,
                    'nip' 					=> $data->nip,
                    'nik' 					=> $data->nik,
                    'jenis_kelamin' 		=> $data->jenis_kelamin,
                    'tempat_lahir' 			=> $data->tempat_lahir,
                    'tanggal_lahir' 		=> $data->tanggal_lahir,
                    'status_kepegawaian_id'	=> $data->status_kepegawaian_id,
                    'jenis_ptk_id' 			=> $data->ptk_terdaftar->jenis_ptk_id,
                    'jabatan_ptk_id' 		=> ($data->tugas_tambahan) ? $data->tugas_tambahan->jabatan_ptk_id : NULL,
                    'agama_id' 				=> $data->agama_id,
                    'alamat' 				=> $data->alamat_jalan,
                    'rt' 					=> $data->rt,
                    'rw' 					=> $data->rw,
                    'desa_kelurahan' 		=> $data->desa_kelurahan,
                    'kecamatan' 			=> $data->wilayah->nama,
                    'kode_wilayah'			=> $data->kode_wilayah,
                    'kode_pos'				=> ($data->kode_pos) ? $data->kode_pos : 0,
                    'no_hp'					=> ($data->no_hp) ? $data->no_hp : 0,
                    'email' 				=> $data->email,
                    'is_dapodik'			=> 1,
                    'last_sync'				=> Carbon::now()->subDays(30),
                ]
            );
            if(isset($data->rwy_pend_formal)){
                $gelar_ptk_id = [];
                foreach($data->rwy_pend_formal as $rwy_pend_formal){
                    $gelar_ptk_id[] = $rwy_pend_formal->riwayat_pendidikan_formal_id;
                    $riwayat_pendidikan_formal_id = strtolower($rwy_pend_formal->riwayat_pendidikan_formal_id);
                    $ptk_id = $rwy_pend_formal->ptk_id;
                    $gelar = $this->simpan_gelar($rwy_pend_formal);
                    if($gelar){
                        Gelar_ptk::withTrashed()->updateOrCreate(
                            [
                                'gelar_ptk_id' => $riwayat_pendidikan_formal_id,
                            ],
                            [
                                'guru_id' => $rwy_pend_formal->ptk_id,
                                'gelar_akademik_id' => $rwy_pend_formal->gelar_akademik_id,
                                'sekolah_id' => $user->sekolah_id,
                                'ptk_id' => $rwy_pend_formal->ptk_id,
                                'deleted_at' => ($rwy_pend_formal->Soft_delete) ? now() : NULL,
                                'last_sync' => Carbon::now()->subDays(30),
                            ]
                        );
                    }
                }
                if($gelar_ptk_id){
                    Gelar_ptk::where('ptk_id', $data->ptk_id)->whereNotIn('gelar_ptk_id', $gelar_ptk_id)->delete();
                }
            }
            return $create_guru;
        }
    }
    private function simpan_sekolah($data, $user, $semester){
        $kepala_sekolah = $this->simpan_guru($data->kepala_sekolah, $user, $semester);
        $sekolah = Sekolah::find($data->sekolah_id);
        $wilayah = $this->proses_wilayah($data->wilayah, TRUE);
        $sekolah->npsn = $data->npsn;
		$sekolah->nama = $data->nama;
		$sekolah->nss = $data->nss;
		$sekolah->alamat = $data->alamat_jalan;
		$sekolah->desa_kelurahan = $data->desa_kelurahan;
		$sekolah->kecamatan = ($wilayah['kecamatan']) ? $wilayah['kecamatan']->nama : NULL;
		$sekolah->kode_wilayah = $data->kode_wilayah;
		$sekolah->kabupaten = ($wilayah['kabupaten']) ? $wilayah['kabupaten']->nama : NULL;
		$sekolah->provinsi = ($wilayah['provinsi']) ? $wilayah['provinsi']->nama : NULL;
		$sekolah->kode_pos = $data->kode_pos;
		$sekolah->lintang = $data->lintang;
		$sekolah->bujur = $data->bujur;
		$sekolah->no_telp = $data->nomor_telepon;
		$sekolah->no_fax = $data->nomor_fax;
		$sekolah->email = $data->email;
		$sekolah->website = $data->website;
		$sekolah->status_sekolah = $data->status_sekolah;
		$sekolah->last_sync = now();
        $sekolah->guru_id = ($kepala_sekolah) ? $kepala_sekolah->guru_id : NULL;
		$sekolah->sinkron = 1;
        if (Schema::hasColumn('sekolah', 'bentuk_pendidikan_id')) {
            $sekolah->bentuk_pendidikan_id = $data->bentuk_pendidikan_id;
        }
		$sekolah->save();
        $this->simpan_jurusan_sp($data->jurusan_sp, $user, $semester);
    }
    private function insert_jurusan_sp($data, $user, $semester){
        $find = Jurusan::find($data->jurusan_id);
        if($find){
            Jurusan_sp::withTrashed()->updateOrCreate(
                [
                    'jurusan_sp_id' => $data->jurusan_sp_id,
                ],
                [
                    'jurusan_sp_id_dapodik' => $data->jurusan_sp_id,
                    'sekolah_id' => $data->sekolah_id,
                    'jurusan_id' => $data->jurusan_id,
                    'nama_jurusan_sp' => $data->nama_jurusan_sp,
                    'last_sync' => Carbon::now()->subDays(30),
                ]
            );
        }
    }
    private function simpan_jurusan_sp($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'jurusan_sp', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $jurusan_sp_id[] = $data->jurusan_sp_id;
            $this->insert_jurusan_sp($data, $user, $semester);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        Jurusan_sp::where('sekolah_id', $user->sekolah_id)->whereNotIn('jurusan_sp_id', $jurusan_sp_id)->delete();
    }
    private function simpan_jurusan($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'jurusan', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->insert_jurusan($data, $user, $semester);
            $this->proses_sync('Memperoses', 'jurusan', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function insert_jurusan($data, $user, $semester){
        $jurusan_induk = NULL;
        if($data->jurusan_induk){
            $jurusan_induk = Jurusan::find($data->jurusan_induk);
        }
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
                'jurusan_induk' => ($jurusan_induk) ? $data->jurusan_induk : NULL,
                'level_bidang_id' => $data->level_bidang_id,
                'deleted_at' => $data->expired_date,
                'last_sync' => Carbon::now()->subDays(30),
            ]
        );
    }
    private function proses_wilayah($wilayah, $recursive){
        if(!$recursive){
            $this->update_wilayah($wilayah);
        } else {
            $kecamatan = NULL;
            $kabupaten = NULL;
            $provinsi = NULL;
            if($wilayah->id_level_wilayah == 4){
                if($wilayah->parrent_recursive){
                    if($wilayah->parrent_recursive->parrent_recursive){
                        if($wilayah->parrent_recursive->parrent_recursive->parrent_recursive){
                            $provinsi = $this->update_wilayah($wilayah->parrent_recursive->parrent_recursive->parrent_recursive);
                            $kabupaten = $this->update_wilayah($wilayah->parrent_recursive->parrent_recursive);
                            $kecamatan = $this->update_wilayah($wilayah->parrent_recursive);
                            $desa = $this->update_wilayah($wilayah);
                        }
                    }
                }
            } else {
                $kecamatan = $wilayah->nama;
                if($wilayah->parrent_recursive){
                    $kabupaten = $wilayah->parrent_recursive->nama;
                    if($wilayah->parrent_recursive->parrent_recursive){
                        $provinsi = $this->update_wilayah($wilayah->parrent_recursive->parrent_recursive);
                        $kabupaten = $this->update_wilayah($wilayah->parrent_recursive);
                        $kecamatan = $this->update_wilayah($wilayah);
                    }
                }
            }
            return [
                'kecamatan' => $kecamatan,
                'kabupaten' => $kabupaten,
                'provinsi' => $provinsi,
            ];
        }
    }
    private function update_wilayah($wilayah){
        $data = Mst_wilayah::updateOrCreate(
            [
                'kode_wilayah' => $wilayah->kode_wilayah,
            ],
            [
                'nama' => $wilayah->nama,
                'id_level_wilayah' => $wilayah->id_level_wilayah,
                'mst_kode_wilayah' => $wilayah->mst_kode_wilayah,
                'negara_id' => $wilayah->negara_id,
                'asal_wilayah' => $wilayah->asal_wilayah,
                'kode_bps' => $wilayah->kode_bps,
                'kode_dagri' => $wilayah->kode_dagri,
                'kode_keu' => $wilayah->kode_keu,
                'deleted_at' => $wilayah->expired_date,
                'last_sync' => $wilayah->last_sync,
            ]
        );
        return $data;
    }
    private function simpan_pembelajaran($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'pembelajaran', $i, count($dapodik), $user->sekolah_id);
        $pembelajaran_id = [];
        $sub_mapel = [];
        foreach($dapodik as $data){
            $pembelajaran_id[] = $data->pembelajaran_id;
            $sub_mapel[] = $data->induk_pembelajaran_id;
            $find = Rombongan_belajar::find($data->rombongan_belajar_id);
            $this->simpan_guru($data->ptk_terdaftar, $user, $semester);
            //$mapel = $this->cari_mapel($user, $semester, $data->mata_pelajaran_id);
            $this->insert_mata_pelajaran($data->mata_pelajaran, $user, $semester);
            if($find){
                Pembelajaran::withTrashed()->updateOrCreate(
                    [
                        'pembelajaran_id' => $data->pembelajaran_id
                    ],
                    [
                        'pembelajaran_id_dapodik' => $data->pembelajaran_id,
                        'induk_pembelajaran_id' => $data->induk_pembelajaran_id,
                        'semester_id' => $data->semester_id,
                        'sekolah_id'				=> $user->sekolah_id,
                        'rombongan_belajar_id'		=> $data->rombongan_belajar_id,
                        'guru_id'					=> $data->ptk_terdaftar->ptk_id,
                        'mata_pelajaran_id'			=> $data->mata_pelajaran_id,
                        'nama_mata_pelajaran'		=> $data->nama_mata_pelajaran,
                        'kkm'						=> 0,
                        'is_dapodik'				=> 1,
                        'deleted_at'                => NULL,
                        'last_sync'					=> Carbon::now()->subDays(30),
                    ]
                );
                if($data->sub_mapel){
                    $this->simpan_pembelajaran($data->sub_mapel, $user, $semester);
                }    
                $this->proses_sync('Memperoses', 'pembelajaran', $i, count($dapodik), $user->sekolah_id);
                $bar->advance();
                $i++;
            }
        }
        $bar->finish();
        if($pembelajaran_id){
            Pembelajaran::where(function($query) use ($user, $semester, $pembelajaran_id, $sub_mapel){
                $query->where('sekolah_id', $user->sekolah_id);
                $query->where('semester_id', $semester->semester_id);
                $query->whereNotIn('pembelajaran_id', $pembelajaran_id);
                if($sub_mapel){
                    $query->whereIn('induk_pembelajaran_id', $sub_mapel);
                } else {
                    $query->whereNull('induk_pembelajaran_id');
                }
            })->delete();
        }
    }
    private function insert_mata_pelajaran($data, $user, $semester){
        if($data && $data->jurusan_id){
            $jurusan = Jurusan::find($data->jurusan_id);
            if($jurusan){
                $this->simpan_mapel($data);
            }
        } else {
            $this->simpan_mapel($data);
        }
    }
    private function simpan_mapel($data){
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
    private function simpan_kurikulum($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'pembelajaran', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->insert_kurikulum($data, $user, $semester);
            $this->proses_sync('Memperoses', 'kurikulum', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function insert_kurikulum($data, $user, $semester){
        $jurusan = NULL;
        if($data->jurusan_id){
            $jurusan = Jurusan::find($data->jurusan_id);
        }
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
                'jurusan_id'				=> ($jurusan) ? $data->jurusan_id : NULL,
                'deleted_at'				=> $data->expired_date,
                'last_sync'					=> Carbon::now()->subDays(30),
            ]
        );
    }
    private function simpan_anggota_ekskul($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'anggota_ekskul', $i, count($dapodik), $user->sekolah_id);
        $anggota_rombel_id = [];
        foreach($dapodik as $data){
            $this->simpan_pd($data->pd, $user, $semester, NULL);
            $this->simpan_anggota_rombel($data, $user, $semester, NULL);
            /*$anggota_rombel_id[] = $data->anggota_rombel_id;
            $pd = Peserta_didik::find($data->peserta_didik_id);
            $rombel = Rombongan_belajar::find($data->rombongan_belajar_id);
            if($pd && $rombel){
                $this->simpan_anggota_rombel($data, $user, $semester, NULL);
            }*/
            $this->proses_sync('Memperoses', 'anggota_ekskul', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        /*if($anggota_rombel_id){
            Anggota_rombel::where(function($query) use ($anggota_rombel_id, $user, $semester){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 51);
                });
                $query->where('sekolah_id', $user->sekolah_id);
                $query->where('semester_id', $semester->semester_id);
                $query->whereNotIn('anggota_rombel_id', $anggota_rombel_id);
            })->delete();
        }*/
    }
    private function simpan_anggota_rombel_pilihan($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'anggota_rombel_pilihan', $i, count($dapodik), $user->sekolah_id);
        $anggota_rombel_id = [];
        foreach($dapodik as $data){
            $anggota_rombel_id[] = $data->anggota_rombel_id;
            $pd = Peserta_didik::find($data->peserta_didik_id);
            $rombel = Rombongan_belajar::find($data->rombongan_belajar_id);
            if($pd && $rombel){
                $this->simpan_anggota_rombel($data, $user, $semester, NULL);
                $this->proses_sync('Memperoses', 'anggota_rombel_pilihan', $i, count($dapodik), $user->sekolah_id);
                $bar->advance();
                $i++;
            }
        }
        $bar->finish();
        /*if($anggota_rombel_id){
            Anggota_rombel::where(function($query) use ($anggota_rombel_id, $user, $semester){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 16);
                });
                $query->where('sekolah_id', $user->sekolah_id);
                $query->where('semester_id', $semester->semester_id);
                $query->whereNotIn('anggota_rombel_id', $anggota_rombel_id);
            })->delete();
        }*/
    }
    private function simpan_anggota_rombel($data, $user, $semester, $deleted_at){
        Anggota_rombel::withTrashed()->updateOrCreate(
            [
                'anggota_rombel_id' => $data->anggota_rombel_id,
            ],
            [
                'sekolah_id' => $user->sekolah_id,
                'semester_id' => $semester->semester_id,
                'rombongan_belajar_id' => $data->rombongan_belajar_id,
                'peserta_didik_id' => $data->peserta_didik_id,
                'anggota_rombel_id_dapodik' => $data->anggota_rombel_id,
                'deleted_at' => $deleted_at,
                'last_sync' => Carbon::now()->subDays(30),
            ]
        );
    }
    private function simpan_dudi($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'dudi', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $dudi = Dudi::withTrashed()->updateOrCreate(
                [
                    'dudi_id' => $data->dudi_id
                ],
                [
                    'dudi_id_dapodik' => $data->dudi_id,
                    'sekolah_id'		=> $user->sekolah_id,
                    'nama'				=> $data->nama,
                    'bidang_usaha_id'	=> $data->bidang_usaha_id,
                    'nama_bidang_usaha'	=> '-',
                    //$mou->nama_bidang_usaha,
                    'alamat_jalan'		=> $data->alamat_jalan,
                    'rt'				=> $data->rt,
                    'rw'				=> $data->rw,
                    'nama_dusun'		=> $data->nama_dusun,
                    'desa_kelurahan'	=> $data->desa_kelurahan,
                    'kode_wilayah'		=> $data->kode_wilayah,
                    'kode_pos'			=> $data->kode_pos,
                    'lintang'			=> $data->lintang,
                    'bujur'				=> $data->bujur,
                    'nomor_telepon'		=> $data->nomor_telepon,
                    'nomor_fax'			=> $data->nomor_fax,
                    'email'				=> $data->email,
                    'website'			=> $data->website,
                    'npwp'				=> $data->npwp,
                    'last_sync' => Carbon::now()->subDays(30),
                ]
            );
            foreach($data->mou as $mou){
                $dudi->nama_bidang_usaha = $mou->nama_bidang_usaha;
                $dudi->save();
                Mou::withTrashed()->updateOrCreate(
                    [
                        'mou_id' => $mou->mou_id
                    ],
                    [
                        'mou_id_dapodik' => $mou->mou_id,
                        'id_jns_ks'			=> $mou->id_jns_ks,
                        'dudi_id'			=> $mou->dudi_id,
                        'dudi_id_dapodik'	=> $mou->dudi_id,
                        'sekolah_id'		=> $mou->sekolah_id,
                        'nomor_mou'			=> $mou->nomor_mou,
                        'judul_mou'			=> $mou->judul_mou,
                        'tanggal_mulai'		=> $mou->tanggal_mulai,
                        'tanggal_selesai'	=> ($mou->tanggal_selesai) ? $mou->tanggal_selesai : date('Y-m-d'),
                        'nama_dudi'			=> $mou->nama_dudi,
                        'npwp_dudi'			=> $mou->npwp_dudi,
                        'nama_bidang_usaha'	=> $mou->nama_bidang_usaha,
                        'telp_kantor'		=> $mou->telp_kantor,
                        'fax'				=> $mou->fax,
                        'contact_person'	=> $mou->contact_person,
                        'telp_cp'			=> $mou->telp_cp,
                        'jabatan_cp'		=> $mou->jabatan_cp,
                        'last_sync' => Carbon::now()->subDays(30),
                    ]
                );
                foreach($mou->akt_pd as $akt_pd){
                    Akt_pd::withTrashed()->updateOrCreate(
                        [
                            'akt_pd_id' => $akt_pd->id_akt_pd
                        ],
                        [
                            'akt_pd_id_dapodik' => $akt_pd->id_akt_pd,
                            'sekolah_id'	=> $user->sekolah_id,
                            'mou_id'		=> $mou->mou_id,
                            'id_jns_akt_pd'	=> $akt_pd->id_jns_akt_pd,
                            'judul_akt_pd'	=> $akt_pd->judul_akt_pd,
                            'sk_tugas'		=> ($akt_pd->sk_tugas) ? $akt_pd->sk_tugas : '-',
                            'tgl_sk_tugas'	=> $akt_pd->tgl_sk_tugas,
                            'ket_akt'		=> $akt_pd->ket_akt,
                            'a_komunal'		=> $akt_pd->a_komunal,
                            'last_sync'		=> Carbon::now()->subDays(30),
                        ]
                    );
                    if($akt_pd->anggota_akt_pd){
                        foreach($akt_pd->anggota_akt_pd as $anggota_akt_pd){
                            if($anggota_akt_pd->registrasi_peserta_didik){
                                $find = Peserta_didik::find($anggota_akt_pd->registrasi_peserta_didik->peserta_didik_id);
                                if($find){
                                    $create_anggota_akt_pd = Anggota_akt_pd::withTrashed()->updateOrCreate(
                                        [
                                            'anggota_akt_pd_id' => $anggota_akt_pd->id_ang_akt_pd,
                                        ],
                                        [
                                            'id_ang_akt_pd' => $anggota_akt_pd->id_ang_akt_pd,
                                            'sekolah_id'		=> $user->sekolah_id,
                                            'akt_pd_id'			=> $akt_pd->id_akt_pd,
                                            'peserta_didik_id'	=> $anggota_akt_pd->registrasi_peserta_didik->peserta_didik_id,
                                            'nm_pd'				=> $anggota_akt_pd->nm_pd,
                                            'nipd'				=> $anggota_akt_pd->nipd,
                                            'jns_peran_pd'		=> $anggota_akt_pd->jns_peran_pd,
                                            'last_sync' => Carbon::now()->subDays(30),
                                        ]
                                    );
                                }
                            }
                        }
                    }
                    if($akt_pd->bimbing_pd){
                        foreach($akt_pd->bimbing_pd as $bimbing_pd){
                            $find = Guru::withTrashed()->find($bimbing_pd->ptk_id);
                            if($find){
                                $create_bimbing_pd = Bimbing_pd::withTrashed()->updateOrCreate(
                                    [
                                        'bimbing_pd_id' => $bimbing_pd->id_bimb_pd
                                    ],
                                    [
                                        'id_bimb_pd' => $bimbing_pd->id_bimb_pd,
                                        'sekolah_id'		=> $user->sekolah_id,
                                        'akt_pd_id'			=> $akt_pd->id_akt_pd,
                                        'guru_id'			=> $bimbing_pd->ptk_id,
                                        'ptk_id'			=> $bimbing_pd->ptk_id,
                                        'urutan_pembimbing'	=> $bimbing_pd->urutan_pembimbing,
                                        'last_sync' => Carbon::now()->subDays(30),
                                    ]
                                );
                            }
                        }
                    }
                }
            }
            $this->proses_sync('Memperoses', 'dudi', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    public function simpan_mata_pelajaran($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'mata_pelajaran', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->insert_mata_pelajaran($data, $user, $semester);
            $this->proses_sync('Memperoses', 'mata_pelajaran', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
}
