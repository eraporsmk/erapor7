<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceNilaiKurmerPerTpView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNilaiKurmerPerTpView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command CreateOrReplaceNilaiKurmerPerTpView';

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
        DB::statement("CREATE OR REPLACE VIEW view_nilai_kurmer_pertp AS SELECT pembelajaran_id, anggota_rombel_id, tp_id, sum(nilai) AS nilai_tp FROM get_nilai_kurmer_siswa_by_kd GROUP BY pembelajaran_id, anggota_rombel_id, tp_id;");
    }
}
