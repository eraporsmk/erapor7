<?php

namespace App\Console\Commands;

use App\Models\Pembelajaran;
use Illuminate\Console\Command;

class GenerateGuruMapelErapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateMapel:GuruMapel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis set guru id pada mapel erapor sesuai dengan guru pada mapel Dapodik dan memberikan no urut sesuai struktur rapor SMK';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pembelajaran = Pembelajaran::get();
        foreach($pembelajaran as $mapel):
            $data = [
                'guru_pengajar_id' => $mapel->guru_id
            ];
            //Update Data Guru_Pengajar_id
            Pembelajaran::where('pembelajaran_id',$mapel->pembelajaran_id)
                        ->update($data);

        endforeach;
        return Command::SUCCESS;
    }
}
