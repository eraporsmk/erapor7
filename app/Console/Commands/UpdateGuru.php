<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Models\Guru;
use App\Models\Rombongan_belajar;
use App\Models\Ekstrakurikuler;
use App\Models\Pembelajaran;
use App\Models\Jurusan_sp;
use App\Models\Dudi;
use App\Models\Mou;
use App\Models\Akt_pd;
use App\Models\Anggota_akt_pd;

class UpdateGuru extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:guru {sekolah_id} {semester_id}';

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
        foreach (Guru::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->guru_id_dapodik){
                if($data->guru_id != $data->guru_id_dapodik && !Guru::find($data->guru_id_dapodik)){
                    $data->guru_id = $data->guru_id_dapodik;
                    $data->save();
                    Guru::where('guru_id_dapodik', $data->guru_id_dapodik)->where('guru_id', '<>', $data->guru_id_dapodik)->delete();
                }
            }
        }
        foreach (Rombongan_belajar::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->rombel_id_dapodik){
                if($data->rombongan_belajar_id != $data->rombel_id_dapodik && !Rombongan_belajar::find($data->rombel_id_dapodik)){
                    $data->rombongan_belajar_id = $data->rombel_id_dapodik;
                    $data->save();
                    Rombongan_belajar::where('rombel_id_dapodik', $data->rombel_id_dapodik)->where('rombongan_belajar_id', '<>', $data->rombel_id_dapodik)->delete();
                }
            }
        }
        foreach (Ekstrakurikuler::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->id_kelas_ekskul){
                if($data->ekstrakurikuler_id != $data->id_kelas_ekskul && !Ekstrakurikuler::find($data->id_kelas_ekskul)){
                    $data->ekstrakurikuler_id = $data->id_kelas_ekskul;
                    $data->save();
                    Ekstrakurikuler::where('id_kelas_ekskul', $data->id_kelas_ekskul)->where('ekstrakurikuler_id', '<>', $data->id_kelas_ekskul)->delete();
                }
            }
        }
        foreach (Pembelajaran::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->pembelajaran_id_dapodik){
                if($data->pembelajaran_id != $data->pembelajaran_id_dapodik && !Pembelajaran::find($data->pembelajaran_id_dapodik)){
                    $data->pembelajaran_id = $data->pembelajaran_id_dapodik;
                    $data->save();
                    Pembelajaran::where('pembelajaran_id_dapodik', $data->pembelajaran_id_dapodik)->where('pembelajaran_id', '<>', $data->pembelajaran_id_dapodik)->delete();
                }
            }
        }
        foreach (Jurusan_sp::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->jurusan_sp_id_dapodik){
                if($data->jurusan_sp_id != $data->jurusan_sp_id_dapodik && !Jurusan_sp::find($data->jurusan_sp_id_dapodik)){
                    $data->jurusan_sp_id = $data->jurusan_sp_id_dapodik;
                    $data->save();
                    Jurusan_sp::where('jurusan_sp_id_dapodik', $data->jurusan_sp_id_dapodik)->where('jurusan_sp_id', '<>', $data->jurusan_sp_id_dapodik)->delete();
                }
            }
        }
        foreach (Dudi::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->dudi_id_dapodik){
                if($data->dudi_id != $data->dudi_id_dapodik && !Dudi::find($data->dudi_id_dapodik)){
                    $data->dudi_id = $data->dudi_id_dapodik;
                    $data->save();
                    Dudi::where('dudi_id_dapodik', $data->dudi_id_dapodik)->where('dudi_id', '<>', $data->dudi_id_dapodik)->delete();
                }
            }
        }
        foreach (Mou::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->mou_id_dapodik){
                if($data->mou_id != $data->mou_id_dapodik && !Mou::find($data->mou_id_dapodik)){
                    $data->mou_id = $data->mou_id_dapodik;
                    $data->save();
                    Mou::where('mou_id_dapodik', $data->mou_id_dapodik)->where('mou_id', '<>', $data->mou_id_dapodik)->delete();
                }
            }
        }
        foreach (Akt_pd::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->akt_pd_id_dapodik){
                if($data->akt_pd_id != $data->akt_pd_id_dapodik && !Akt_pd::find($data->akt_pd_id_dapodik)){
                    $data->akt_pd_id = $data->akt_pd_id_dapodik;
                    $data->save();
                    Akt_pd::where('akt_pd_id_dapodik', $data->akt_pd_id_dapodik)->where('akt_pd_id', '<>', $data->akt_pd_id_dapodik)->delete();
                }
            }
        }
        foreach (Anggota_akt_pd::where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
            if($data->id_ang_akt_pd){
                if($data->anggota_akt_pd_id != $data->id_ang_akt_pd && !Anggota_akt_pd::find($data->id_ang_akt_pd)){
                    $data->anggota_akt_pd_id = $data->id_ang_akt_pd;
                    $data->save();
                    Anggota_akt_pd::where('id_ang_akt_pd', $data->id_ang_akt_pd)->where('anggota_akt_pd_id', '<>', $data->id_ang_akt_pd)->delete();
                }
            }
        }
        if(Schema::hasTable('ptk_keluar')){
            foreach (Guru::onlyTrashed()->where('sekolah_id', $this->argument('sekolah_id'))->lazy() as $data) {
                \App\Models\Ptk_keluar::updateOrCreate(
                    [
                        'guru_id' => $data->guru_id,
                    ],
                    [
                        'sekolah_id' => $this->argument('sekolah_id'),
                        'semester_id' => $this->argument('semester_id'),
                        'last_sync' => now(),
                    ]
                );
                $data->restore();
            }
        }
    }
}
