<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Semester;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\Rombongan_belajar;
use App\Models\User;
use App\Models\Sekolah;
use DB;
use App\Models\Nilai;

class Debugger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:run';

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
        $data_pd = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('tingkat', 10);
                $query->where('semester_id', 20221);
            });
        })->get();
        $kelas_ekskul = Rombongan_belajar::whereHas('kelas_ekskul')->where('semester_id', 20221)->get();
        foreach($data_pd as $pd){
            foreach($kelas_ekskul as $ekskul){
                $anggota = Anggota_rombel::where('rombongan_belajar_id', $ekskul->rombongan_belajar_id)->where('peserta_didik_id', $pd->peserta_didik_id)->first();
                if(!$anggota){
                    $anggota_rombel_id = Str::uuid();
                    Anggota_rombel::create([
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'sekolah_id' => $pd->sekolah_id,
                        'semester_id' => $ekskul->semester_id,
                        'rombongan_belajar_id' => $ekskul->rombongan_belajar_id,
                        'peserta_didik_id' => $pd->peserta_didik_id,
                        'anggota_rombel_id_dapodik' => $anggota_rombel_id,
                        'last_sync' => now(),
                    ]);
                }
            }
        }
    }
}
