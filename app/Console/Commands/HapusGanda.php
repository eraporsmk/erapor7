<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kompetensi_dasar;
use App\Models\Mata_pelajaran;

class HapusGanda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hapus:ganda';

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
        Mata_pelajaran::has('kompetensi_dasar')->with(['kompetensi_dasar'])->chunk(10, function ($mata_pelajaran) {
            foreach ($mata_pelajaran as $mapel) {
                foreach($mapel->kompetensi_dasar as $kd){
                    $kompetensi_dasar_id[str_replace('.','',$kd->id_kompetensi)] = $kd->kompetensi_dasar_id;
                }
                $a = Kompetensi_dasar::where('mata_pelajaran_id', $mapel->mata_pelajaran_id)->whereNotIn('kompetensi_dasar_id', $kompetensi_dasar_id)->update(['aktif' => 0]);
                if($a){
                    $this->error('KD Mapel '.$mapel->nama.' dinonaktifkan sebanyak ('.$a.')');
                } else {
                    $this->info('Mapel '.$mapel->nama.' tidak memiliki KD Ganda');
                }
            }
        });
    }
}
