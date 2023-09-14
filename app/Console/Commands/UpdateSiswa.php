<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;

class UpdateSiswa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:siswa {sekolah_id}';

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
    private function bagi($i, $note, $jml){
        if ( $bagi = $i % $jml == 0 ) {
            if($i>0){
                sleep(1);
                $this->info($i.' '.$note.' terproses');
            }
        }
    }
    public function handle()
    {
        Peserta_didik::onlyTrashed()->where('sekolah_id', $this->argument('sekolah_id'))->where('peserta_didik_id', DB::raw("peserta_didik_id_dapodik"))->restore();
        foreach (Peserta_didik::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $i => $data) {
            if($data->peserta_didik_id_dapodik){
                if($data->peserta_didik_id != $data->peserta_didik_id_dapodik && !Peserta_didik::find($data->peserta_didik_id_dapodik)){
                    $data->peserta_didik_id = $data->peserta_didik_id_dapodik;
                    $data->save();
                    Peserta_didik::where('peserta_didik_id_dapodik', $data->peserta_didik_id_dapodik)->where('peserta_didik_id', '<>', $data->peserta_didik_id_dapodik)->delete();
                }
            }
            $this->bagi($i, 'Peserta Didik', 500);
            $i++;
        }
        Anggota_rombel::onlyTrashed()->where('sekolah_id', $this->argument('sekolah_id'))->where('anggota_rombel_id', DB::raw("anggota_rombel_id_dapodik"))->restore();
        foreach (Anggota_rombel::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $i => $data) {
            if($data->anggota_rombel_id_dapodik){
                if($data->anggota_rombel_id != $data->anggota_rombel_id_dapodik && !Anggota_rombel::find($data->anggota_rombel_id_dapodik)){
                    $data->anggota_rombel_id = $data->anggota_rombel_id_dapodik;
                    $data->save();
                    Anggota_rombel::where('anggota_rombel_id_dapodik', $data->anggota_rombel_id_dapodik)->where('anggota_rombel_id', '<>', $data->anggota_rombel_id_dapodik)->delete();
                }
            }
            $this->bagi($i, 'Anggota Rombel', 500);
        }
        /*foreach (Anggota_rombel::onlyTrashed()->where('sekolah_id', $this->argument('sekolah_id'))->with([
            'all_catatan_budaya_kerja',
            'catatan_ppk',
            'catatan_wali',
            'deskripsi_mata_pelajaran',
            'deskripsi_sikap',
            'kenaikan_kelas',
            'kewirausahaan',
            'nilai_kd', 
            'all_nilai_remedial',
            'nilai_akhir',
            'nilai_budaya_kerja',
            'nilai_ekstrakurikuler',
            'nilai_rapor',
            'nilai_sikap',
            'nilai_ukk',
            'nilai_un',
            'nilai_us',
            'prakerin',
            'prestasi',
        ])->lazy() as $i => $b) {
            $a=0;
            foreach($b->all_catatan_budaya_kerja as $catatan_budaya_kerja){
                $this->find_anggota($catatan_budaya_kerja, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'catatan_budaya_kerja Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->catatan_ppk as $catatan_ppk){
                $this->find_anggota($catatan_ppk, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'catatan_ppk Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->catatan_wali as $catatan_wali){
                $this->find_anggota($catatan_wali, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'catatan_wali Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->deskripsi_mata_pelajaran as $deskripsi_mata_pelajaran){
                $this->find_anggota($deskripsi_mata_pelajaran, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'deskripsi_mata_pelajaran Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->deskripsi_sikap as $deskripsi_sikap){
                $this->find_anggota($deskripsi_sikap, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'deskripsi_sikap Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->kenaikan_kelas as $kenaikan_kelas){
                $this->find_anggota($kenaikan_kelas, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'kenaikan_kelas Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->kewirausahaan as $kewirausahaan){
                $this->find_anggota($kewirausahaan, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'kewirausahaan Anggota Rombel', 50);
                $a++;
            }
            foreach($b->nilai_kd as $c => $nilai_kd){ 
                $this->find_anggota($nilai_kd, $b->anggota_rombel_id_dapodik);
                $this->bagi($c, 'nilai_kd Anggota Rombel', 50);
                $c++;
            }
            $a=0;
            foreach($b->all_nilai_remedial as $all_nilai_remedial){
                $this->find_anggota($all_nilai_remedial, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_remedial Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_akhir as $nilai_akhir){
                $this->find_anggota($nilai_akhir, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_akhir Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_budaya_kerja as $nilai_budaya_kerja){
                $this->find_anggota($nilai_budaya_kerja, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_budaya_kerja Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_ekstrakurikuler as $nilai_ekstrakurikuler){
                $this->find_anggota($nilai_ekstrakurikuler, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_ekstrakurikuler Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_rapor as $nilai_rapor){
                $this->find_anggota($nilai_rapor, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_rapor Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_sikap as $nilai_sikap){
                $this->find_anggota($nilai_sikap, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_sikap Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_ukk as $nilai_ukk){
                $this->find_anggota($nilai_ukk, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_ukk Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_un as $nilai_un){
                $this->find_anggota($nilai_un, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_un Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->nilai_us as $nilai_us){
                $this->find_anggota($nilai_us, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'nilai_us Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->prakerin as $prakerin){
                $this->find_anggota($prakerin, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'prakerin Anggota Rombel', 50);
                $a++;
            }
            $a=0;
            foreach($b->prestasi as $prestasi){
                $this->find_anggota($prestasi, $b->anggota_rombel_id_dapodik);
                $this->bagi($a, 'prestasi Anggota Rombel', 50);
                $a++;
            }
            $this->bagi($i, 'Relasi Data Anggota Rombel', 500);
            $i++;
        }*/
    }
    private function find_anggota($data, $anggota_rombel_id_dapodik){
        $find_anggota_lama = Anggota_rombel::withTrashed()->find($data->anggota_rombel_id);
        if($find_anggota_lama){
            $find_anggota_baru = Anggota_rombel::withTrashed()->find($find_anggota_lama->anggota_rombel_id_dapodik);
            if($find_anggota_baru){
                $data->anggota_rombel_id = $find_anggota_baru->anggota_rombel_id_dapodik;
                $data->save();
            } else {
                $find_anggota_lama->anggota_rombel_id = $find_anggota_lama->anggota_rombel_id_dapodik;
                $find_anggota_lama->save();
            }
        }
    }
}
