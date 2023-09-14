<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pekerjaan;

class SinkronReferensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:referensi';

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
        $ref_kerja = [
            1 => "Tidak bekerja",
            2 => "Nelayan",
            3 => "Petani",
            4 => "Peternak",
            5 => "PNS/TNI/Polri",
            6 => "Karyawan Swasta",
            7 => "Pedagang Kecil",
            8 => "Pedagang Besar",
            9 => "Wiraswasta",
            10 => "Wirausaha",
            11 => "Buruh",
            12 => "Pensiunan",
            13 => "Tenaga Kerja Indonesia",
            14 => "Karyawan BUMN",
            90 => "Tidak dapat diterapkan",
            98 => "Sudah Meninggal",
            99 => "Lainnya",
        ];
        foreach($ref_kerja as $id => $nama){
            Pekerjaan::updateOrCreate(
                [
                    'pekerjaan_id' => $id,
                ],
                [
                    'nama' => $nama,
                    'last_sync' => now(),
                ]
            );            
        }
    }
}
