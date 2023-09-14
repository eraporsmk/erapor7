<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class CreateOrReplaceNilaiKeterampilanPerKdViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNilaiKeterampilanPerKdView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command CreateOrReplaceNilaiKeterampilanPerKdView';

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
        DB::statement("CREATE OR REPLACE VIEW view_nilai_keterampilan_perkd AS SELECT kompetensi_id, anggota_rombel_id, pembelajaran_id, kompetensi_dasar_id, round(sum(nilai_kd_keterampilan) / sum(bobot)::numeric, 0) AS nilai_kd FROM get_nilai_keterampilan_siswa_by_kd GROUP BY kompetensi_id, anggota_rombel_id, pembelajaran_id, kompetensi_dasar_id;");
    }
}
