<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class CreateOrReplaceNilaiAkhirPkViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNilaiAkhirPkView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command view:CreateOrReplaceNilaiAkhirPkView';

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
        DB::statement("CREATE OR REPLACE VIEW view_nilai_akhir_pk AS SELECT pembelajaran_id, anggota_rombel_id, kompetensi_id, round(avg(nilai_kd), 0) AS nilai_akhir FROM view_nilai_pk_perkd GROUP BY pembelajaran_id, anggota_rombel_id, kompetensi_id;");
    }
}
