<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Rombongan_belajar;
use App\Models\Semester;
use App\Models\Matev_rapor;
use Carbon\Carbon;
use DB;

class KirimNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:nilai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('Email Administrator');
        $user = User::with('sekolah')->where('email', $email)->whereNotNull('sekolah_id')->first();
        if(!$user){
            $this->error('Email '.$email.' tidak terdaftar');
            exit;
        } else {
            $year = date('Y');
            $tahun = $this->choice(
                'Pilih Tahun Ajaran!',
                [
                    $year.'/'.$year+1,
                    $year - 1 .'/'.$year,
                    $year-2 .'/'.$year-1,
                ]
            );
            $segments = Str::of($tahun)->split('/[\s\/]+/');
            $semester = [$segments->first().'2', $segments->first().'1'];
            foreach($semester as $smt){
                $semester = Semester::find($smt);
                $getMatev = getMatev($user->sekolah_id, $user->sekolah->npsn, $semester->semester_id);
                if($getMatev){
                    Matev_rapor::whereHas('rombongan_belajar', function($query) use ($semester){
                        $query->where('semester_id', $semester->semester_id);
                    })->delete();
                    foreach($getMatev as $matev){
                        DB::table('dapodik.matev_rapor')->updateOrInsert(
                            ['id_evaluasi' => $matev->id_evaluasi],
                            [
                                'rombongan_belajar_id' => $matev->rombongan_belajar_id,
                                'mata_pelajaran_id' => $matev->mata_pelajaran_id,
                                'pembelajaran_id' => $matev->pembelajaran_id,
                                'nm_mata_evaluasi' => $matev->nm_mata_evaluasi,
                                'a_dari_template' => $matev->a_dari_template,
                                'no_urut' => $matev->no_urut,
                                'create_date' => $matev->create_date,
                                'last_update' => $matev->last_update,
                                'soft_delete' => $matev->soft_delete,
                                'last_sync' => $matev->last_sync,
                                'updater_id' => $matev->updater_id,
                            ]
                        );
                    }
                    $grouped = $getMatev->groupBy('rombongan_belajar_id');
                    $this->info('Memulai kirim nilai ke Dapodik Tahun Pelajaran '.$semester->nama.' dari '.$grouped->count().' Rombel');
                    $rombel_id = [];
                    foreach($grouped->all() as $rombongan_belajar_id => $group){
                        $rombel_id[] = $rombongan_belajar_id;
                        $rombel = Rombongan_belajar::find($rombongan_belajar_id);
                        $this->info('Mengirim nilai dari kelas '.$rombel->nama);
                        $this->kirim_nilai($rombel->nama, $rombongan_belajar_id, $user->sekolah_id, $user->sekolah->npsn, $smt);
                    }
                    $this->createMatev($semester, $user, $rombel_id);
                } else {
                    $rombongan_belajar = Rombongan_belajar::with(['pembelajaran' => function($query){
                        $query->whereNotNull('kelompok_id');
                        $query->whereNotNull('no_urut');
                        $query->whereNull('induk_pembelajaran_id');
                        $query->orderBy('mata_pelajaran_id');
                    }])->where(function($query) use ($smt, $user){
                        $query->whereIn('jenis_rombel', [1, 16]);
                        $query->where('semester_id', $smt);
                        $query->where('sekolah_id', $user->sekolah_id);
                    })->orderBy('nama')->get();
                    if($rombongan_belajar->count()){
                        $updater_id = getUpdaterID($user->sekolah_id, $user->sekolah->npsn, $semester->semester_id, $user->email);
                        if($updater_id){
                            $this->info('Memulai kirim nilai ke Dapodik Tahun Pelajaran '.$semester->nama.' dari '.$rombongan_belajar->count().' Rombel');
                            foreach ($rombongan_belajar as $rombel) {
                                $this->info('Mengirim nilai dari kelas '.$rombel->nama);
                                $insert = 0;
                                $pembelajaran_id = [];
                                foreach($rombel->pembelajaran as $mapel){
                                    $insert++;
                                    $pembelajaran_id[] = $mapel->pembelajaran_id;
                                    Matev_rapor::updateOrCreate(
                                        [
                                            'rombongan_belajar_id' => $mapel->rombongan_belajar_id,
                                            'mata_pelajaran_id' => $mapel->mata_pelajaran_id,
                                            'pembelajaran_id' => $mapel->pembelajaran_id,
                                        ],
                                        [
                                            'nm_mata_evaluasi' => Str::limit($mapel->mata_pelajaran->nama, 40),
                                            'a_dari_template' => 1,
                                            'no_urut' => $mapel->no_urut,
                                            'create_date' => Carbon::now()->subHour(),
                                            'last_update' => Carbon::now(),
                                            'soft_delete' => 0,
                                            'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                                            'updater_id' => $updater_id,
                                        ]
                                    );
                                }
                                Matev_rapor::where('rombongan_belajar_id', $rombel->rombongan_belajar_id)->whereNotIn('pembelajaran_id', $pembelajaran_id)->update(['soft_delete' => 0]);
                                $this->kirim_nilai($rombel->nama, $rombel->rombongan_belajar_id, $user->sekolah_id, $user->sekolah->npsn, $smt);
                            }
                        }
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
    private function createMatev($semester, $user, $rombel_id){
        $rombongan_belajar = Rombongan_belajar::with(['pembelajaran' => function($query){
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereNull('induk_pembelajaran_id');
            $query->orderBy('mata_pelajaran_id');
        }])->where(function($query) use ($semester, $user, $rombel_id){
            $query->whereIn('jenis_rombel', [1, 16]);
            $query->where('semester_id', $semester->semester_id);
            $query->where('sekolah_id', $user->sekolah_id);
            $query->whereNotIn('rombongan_belajar_id', $rombel_id);
        })->orderBy('nama')->get();
        if($rombongan_belajar->count()){
            $updater_id = getUpdaterID($user->sekolah_id, $user->sekolah->npsn, $semester->semester_id, $user->email);
            if($updater_id){
                $this->info('Memulai kirim nilai ke Dapodik Tahun Pelajaran '.$semester->nama.' dari '.$rombongan_belajar->count().' Rombel');
                foreach ($rombongan_belajar as $rombel) {
                    $this->info('Mengirim nilai dari kelas '.$rombel->nama);
                    $insert = 0;
                    $pembelajaran_id = [];
                    foreach($rombel->pembelajaran as $mapel){
                        $insert++;
                        $pembelajaran_id[] = $mapel->pembelajaran_id;
                        Matev_rapor::updateOrCreate(
                            [
                                'rombongan_belajar_id' => $mapel->rombongan_belajar_id,
                                'mata_pelajaran_id' => $mapel->mata_pelajaran_id,
                                'pembelajaran_id' => $mapel->pembelajaran_id,
                            ],
                            [
                                'nm_mata_evaluasi' => Str::limit($mapel->mata_pelajaran->nama, 40),
                                'a_dari_template' => 1,
                                'no_urut' => $mapel->no_urut,
                                'create_date' => Carbon::now()->subHour(),
                                'last_update' => Carbon::now(),
                                'soft_delete' => 0,
                                'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                                'updater_id' => $updater_id,
                            ]
                        );
                    }
                    Matev_rapor::where('rombongan_belajar_id', $rombel->rombongan_belajar_id)->whereNotIn('pembelajaran_id', $pembelajaran_id)->update(['soft_delete' => 0]);
                    $this->kirim_nilai($rombel->nama, $rombel->rombongan_belajar_id, $user->sekolah_id, $user->sekolah->npsn, $semester->semester_id);
                }
            }
        }
    }
    private function kirim_nilai($nama_kelas, $rombongan_belajar_id, $sekolah_id, $npsn, $semester_id){
        $insert = 0;
        $nilai = 0;
        $all_response = [];
        try {
            $matev_rapor = Matev_rapor::with(['pembelajaran' => function($query){
                $query->with(['all_nilai_akhir_pengetahuan', 'all_nilai_akhir_kurmer', 'deskripsi_mata_pelajaran']);
            }])->where(function($query) use ($rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $rombongan_belajar_id);
            })->get();
            foreach($matev_rapor as $matev){
                $insert++;
                $insert_matev = $matev->toArray();
                unset($insert_matev['status'], $insert_matev['pembelajaran']);
                $insert_matev['last_update'] = Carbon::now()->addHour(6);
                $insert_matev['last_sync'] = Carbon::now()->addMinutes(330);
                $insert_matev['nm_mata_evaluasi'] = Str::limit($matev->nm_mata_evaluasi, 40);
                //$insert_matev['updater_id'] = Str::uuid();
                $response = Http::withToken(get_setting('token_dapodik', $sekolah_id))->post(get_setting('url_dapodik', $sekolah_id).'/WebService/postMatevRapor?npsn='.$npsn.'&semester_id='.$semester_id, $insert_matev);
                if($response->successful()){
                    $matev_dapo = $response->object();
                    if($matev_dapo->success){
                        $matev->status = 1;
                        $matev->save();
                        $params = [];
                        if($matev->pembelajaran->all_nilai_akhir_pengetahuan->count()){
                            foreach($matev->pembelajaran->all_nilai_akhir_pengetahuan as $nilai_akhir){
                                $params[] = [
                                    'nilai_id' => $nilai_akhir->nilai_akhir_id,
                                    'id_evaluasi' => $matev->id_evaluasi,
                                    'anggota_rombel_id' => $nilai_akhir->anggota_rombel_id,
                                    'nilai_kognitif_angka' => $nilai_akhir->nilai,
                                    //'a_beku' => 1,
                                    'create_date' => Carbon::now()->timezone('UTC')->subHour(),
                                    'last_update' => Carbon::now()->timezone('UTC'),
                                    'soft_delete' => 0,
                                    'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                                    'updater_id' => $matev->updater_id,
                                ];
                            }
                        } else {
                            foreach($matev->pembelajaran->all_nilai_akhir_kurmer as $nilai_akhir){
                                /*$get_desc = $matev->pembelajaran->deskripsi_mata_pelajaran()->where('anggota_rombel_id', $nilai_akhir->anggota_rombel_id)->first();
                                $deskripsi = NULL;
                                if($get_desc){
                                    $deskripsi = Str::of($get_desc->deskripsi_pengetahuan.' '.$get_desc->deskripsi_keterampilan)->limit(300);
                                }*/
                                $params[] = [
                                    'nilai_id' => $nilai_akhir->nilai_akhir_id,
                                    'id_evaluasi' => $matev->id_evaluasi,
                                    'anggota_rombel_id' => $nilai_akhir->anggota_rombel_id,
                                    'nilai_kognitif_angka' => $nilai_akhir->nilai,
                                    //'ket_kognitif' => $deskripsi,
                                    //'a_beku' => 1,
                                    'create_date' => Carbon::now()->timezone('UTC')->subHour(),
                                    'last_update' => Carbon::now()->timezone('UTC'),
                                    'soft_delete' => 0,
                                    'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                                    'updater_id' => $matev->updater_id,
                                ];
                            }
                        }
                        if(count($params)){
                            $nilai = 0;
                            foreach($params as $param){
                                $kirim_nilai = Http::withToken(get_setting('token_dapodik', $sekolah_id))->post(get_setting('url_dapodik', $sekolah_id).'/WebService/postNilai?npsn='.$npsn.'&semester_id='.$semester_id.'&table=rapor', $param);
                                if($kirim_nilai->successful()){
                                    $nilai_dapo = $kirim_nilai->object();
                                    //dd($nilai_dapo);
                                    if($nilai_dapo->success){
                                        $nilai++;
                                    }
                                }
                            }
                            $this->info('Nilai Mata Pelajaran '.$matev->nm_mata_evaluasi.' kelas '.$nama_kelas.' berhasil dikirim sebanyak '.$nilai.' data');
                        }
                    }
                }
            }
        } catch (\Exception $e){
            return $this->error('Proses pengiriman nilai gagal. Response Server: '.$e->getMessage());
        }
    }
}
