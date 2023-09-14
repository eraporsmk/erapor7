<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class CreateOrReplaceNilaiAkhirKeterampilanViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNilaiAkhirKeterampilanView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CreateOrReplaceNilaiAkhirKeterampilanView';

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
     * @return mixed
     */
    public function handle()
    {
        DB::statement("CREATE OR REPLACE VIEW view_nilai_akhir_keterampilan AS SELECT kompetensi_id, anggota_rombel_id, pembelajaran_id, round(avg(nilai_kd), 0) AS nilai_akhir FROM view_nilai_keterampilan_perkd GROUP BY kompetensi_id, anggota_rombel_id, pembelajaran_id;");
    }
}
