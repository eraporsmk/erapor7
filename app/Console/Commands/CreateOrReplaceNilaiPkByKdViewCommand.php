<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceNilaiPkByKdViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNilaiPkByKdView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command CreateOrReplaceNilaiPkByKdView';

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
        DB::statement("CREATE OR REPLACE VIEW get_nilai_pk_siswa_by_kd AS SELECT a.nilai, c.bobot, a.kompetensi_id, a.anggota_rombel_id, b.kompetensi_dasar_id, c.pembelajaran_id, b.id_kompetensi, c.rencana_penilaian_id, round(a.nilai::numeric * c.bobot::numeric, 0) AS nilai_kd_pk FROM nilai a JOIN kd_nilai b ON b.kd_nilai_id = a.kd_nilai_id JOIN rencana_penilaian c ON c.rencana_penilaian_id = b.rencana_penilaian_id WHERE a.deleted_at IS NULL AND b.deleted_at IS NULL AND c.deleted_at IS NULL GROUP BY a.nilai, c.bobot, a.kompetensi_id, a.anggota_rombel_id, b.kompetensi_dasar_id, c.pembelajaran_id, b.id_kompetensi, c.rencana_penilaian_id;");
    }
}
