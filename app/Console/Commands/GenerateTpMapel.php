<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tp_nilai;
use App\Models\Tp_mapel;

class GenerateTpMapel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tp_mapel';

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
        $this->info('Mulai Mengkonversi Tujuan Pembelajaran');
        $count = DB::table('tp_nilai')
        ->join('tujuan_pembelajaran', 'tp_nilai.tp_id', '=', 'tujuan_pembelajaran.tp_id')
        ->join('anggota_rombel', 'tp_nilai.anggota_rombel_id', '=', 'anggota_rombel.anggota_rombel_id')
        ->join('pembelajaran', 'anggota_rombel.rombongan_belajar_id', '=', 'pembelajaran.rombongan_belajar_id')
        ->orderBy('tp_nilai_id')->count();
        $this->output->progressStart($count);
        DB::table('tp_nilai')
        ->join('tujuan_pembelajaran', 'tp_nilai.tp_id', '=', 'tujuan_pembelajaran.tp_id')
        ->join('anggota_rombel', 'tp_nilai.anggota_rombel_id', '=', 'anggota_rombel.anggota_rombel_id')
        ->join('pembelajaran', 'anggota_rombel.rombongan_belajar_id', '=', 'pembelajaran.rombongan_belajar_id')
        ->orderBy('tp_nilai_id')->chunk(100, function ($data) {
            foreach ($data as $d) {
                Tp_mapel::updateOrCreate([
                    'tp_id' => $d->tp_id,
                    'pembelajaran_id' => $d->pembelajaran_id,
                ]);
                $this->output->progressAdvance();
            }
        });
        $this->output->progressFinish();
        $this->info('Konversi Tujuan Pembelajaran Selesai!');
        return Command::SUCCESS;
        Tp_nilai::withWhereHas('tp', function($query){
            $query->withWhereHas('cp', function($query){
                $query->withWhereHas('pembelajaran', function($query){
                    $query->join('anggota_rombel', 'pembelajaran.rombongan_belajar_id', '=', 'anggota_rombel.rombongan_belajar_id');
                });
            });
        })->orderBy('tp_nilai_id')->chunk(100, function ($data) {
            foreach ($data as $d) {
                Tp_mapel::updateOrCreate([
                    'tp_id' => $d->tp_id,
                    'pembelajaran_id' => $d->tp->cp->pembelajaran->pembelajaran_id,
                ]);
            }
        });
        $this->info('Mengkonversi Tujuan Pembelajaran Kurikulum 2013');
        Tp_nilai::withWhereHas('tp', function($query){
            $query->withWhereHas('kd', function($query){
                $query->withWhereHas('pembelajaran', function($query){
                    $query->join('anggota_rombel', 'pembelajaran.rombongan_belajar_id', '=', 'anggota_rombel.rombongan_belajar_id');
                });
            });
        })->orderBy('tp_nilai_id')->chunk(100, function ($data) {
            foreach ($data as $d) {
                Tp_mapel::updateOrCreate([
                    'tp_id' => $d->tp_id,
                    'pembelajaran_id' => $d->tp->kd->pembelajaran->pembelajaran_id,
                ]);
            }
        });
        
    }
}
