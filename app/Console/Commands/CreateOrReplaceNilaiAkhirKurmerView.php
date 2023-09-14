<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceNilaiAkhirKurmerView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNilaiAkhirKurmerView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command CreateOrReplaceNilaiAkhirKurmerView';

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
        DB::statement("CREATE OR REPLACE VIEW view_nilai_akhir_kurmer AS SELECT pembelajaran_id, anggota_rombel_id, round(avg(nilai_tp), 0) AS nilai_akhir FROM view_nilai_kurmer_pertp GROUP BY pembelajaran_id, anggota_rombel_id;");
    }
}
