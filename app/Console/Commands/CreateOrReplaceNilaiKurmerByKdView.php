<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceNilaiKurmerByKdView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNilaiKurmerByKdView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command CreateOrReplaceNilaiKurmerByKdView';

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
        DB::statement("CREATE OR REPLACE VIEW get_nilai_kurmer_siswa_by_kd AS SELECT a.nilai, a.tp_nilai_id, a.anggota_rombel_id, b.tp_id, c.pembelajaran_id, c.rencana_penilaian_id, round(a.nilai::numeric) AS nilai_kurmer FROM nilai_tp a JOIN tp_nilai b ON b.tp_nilai_id = a.tp_nilai_id JOIN rencana_penilaian c ON c.rencana_penilaian_id = b.rencana_penilaian_id WHERE c.deleted_at IS NULL GROUP BY a.nilai, a.tp_nilai_id, a.anggota_rombel_id, b.tp_id, c.pembelajaran_id, c.rencana_penilaian_id;");
    }
}
